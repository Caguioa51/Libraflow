// Node.js stress test for borrow testing endpoint
// Usage: node scripts/stress_borrow.js --url=http://127.0.0.1:8000 --book=1 --user=2 --requests=100 --concurrency=20
import axios from 'axios';
import yargs from 'yargs';
import { hideBin } from 'yargs/helpers'
// We'll manage cookies manually by capturing set-cookie and sending Cookie header

const argv = yargs(hideBin(process.argv))
  .option('url', { type: 'string', default: 'http://127.0.0.1:8000' })
  .option('book', { type: 'number', demandOption: true })
  .option('user', { type: 'number', demandOption: true })
  .option('requests', { type: 'number', default: 100 })
  .option('concurrency', { type: 'number', default: 10 })
  .option('secret', { type: 'string', description: 'Local STRESS_TEST_SECRET to use no-csrf endpoint' })
  .parse();

const endpoint = argv.secret ? `${argv.url}/api/testing/borrow/no-csrf` : `${argv.url}/testing/borrow`;
const loginUrl = `${argv.url}/login`;
let success = 0;
let fail = 0;
let inFlight = 0;
let sent = 0;

let cookieHeader = '';
const client = axios.create({ withCredentials: true });
let debugFailures = 0;

function buildCookieHeaderFromSetCookieArray(arr) {
  if (!arr || !arr.length) return '';
  const map = {};
  for (const c of arr) {
    const pair = c.split(';')[0];
    const eq = pair.indexOf('=');
    if (eq === -1) continue;
    const name = pair.slice(0, eq).trim();
    const value = pair.slice(eq + 1).trim();
    // keep last-seen value for a given cookie name
    map[name] = value;
  }
  return Object.entries(map).map(([k, v]) => `${k}=${v}`).join('; ');
}

async function obtainCsrfAndLogin() {
  if (argv.secret) {
    // no login needed when using the secret no-csrf endpoint
    return null;
  }
  // Get the login page to receive a session cookie and CSRF token
  const loginResp = await client.get(loginUrl, { validateStatus: s => s < 500 });
  const html = loginResp.data;
  const match = html.match(/name="_token" value="([^"]+)"/);
  const token = match ? match[1] : null;
  if (!token) throw new Error('CSRF token not found on login page');

  // Capture cookies from the GET (session/XSRF token cookies) and include them in the login POST
  const initialSet = loginResp.headers['set-cookie'];
  if (initialSet && initialSet.length) {
    cookieHeader = buildCookieHeaderFromSetCookieArray(initialSet);
  }

  // Perform login and capture any additional cookies (follow redirects manually)
  const resp = await client.post(loginUrl, new URLSearchParams({ email: argv.loginEmail, password: argv.loginPassword, _token: token }).toString(), {
    headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'Cookie': cookieHeader },
    maxRedirects: 0,
    validateStatus: s => s >= 200 && s < 400
  });
  const setCookie = resp.headers['set-cookie'];
  if (setCookie && setCookie.length) {
    // merge by cookie name, favoring the latest values
    const merged = {};
    // start from existing header
    if (cookieHeader) {
      for (const part of cookieHeader.split(';')) {
        const p = part.trim();
        if (!p) continue;
        const eq = p.indexOf('=');
        if (eq === -1) continue;
        merged[p.slice(0, eq)] = p.slice(eq + 1);
      }
    }
    // apply new set-cookie values
    for (const c of setCookie) {
      const pair = c.split(';')[0];
      const eq = pair.indexOf('=');
      if (eq === -1) continue;
      const name = pair.slice(0, eq).trim();
      const value = pair.slice(eq + 1).trim();
      merged[name] = value;
    }
    cookieHeader = Object.entries(merged).map(([k, v]) => `${k}=${v}`).join('; ');
  }
  // After login Laravel may regenerate the session and CSRF token. Fetch the home page with the updated cookies
  // and extract the new CSRF token that matches the authenticated session.
  // Include cookieHeader in the GET so session is used.
  const afterResp = await client.get(argv.url + '/', { headers: { 'Cookie': cookieHeader }, validateStatus: s => s < 500 });
  const afterHtml = afterResp.data;
  const afterMatch = afterHtml.match(/name="_token" value="([^"]+)"/);
  const newToken = afterMatch ? afterMatch[1] : null;
  // Log cookie keys to ensure we only have one laravel_session and one XSRF-TOKEN
  const cookieKeys = cookieHeader ? cookieHeader.split(';').map(p => p.split('=')[0].trim()) : [];
  console.log('cookieHeader length', cookieHeader ? cookieHeader.length : 0, 'cookieKeys', cookieKeys, 'initial token present', !!token, 'new token present', !!newToken);
  return newToken || token;
}

async function sendOne(csrfToken) {
  if (sent >= argv.requests) return Promise.resolve();
  sent++;
  inFlight++;
  try {
  // Prepare form data as application/x-www-form-urlencoded to match form submissions
  const form = new URLSearchParams({ user_id: String(argv.user), book_id: String(argv.book) }).toString();
  // Try to pull XSRF-TOKEN from cookieHeader if present and send as X-XSRF-TOKEN (Laravel expects this when using the cookie method)
  let xsrfHeader = null;
  const xsrfMatch = cookieHeader.match(/XSRF-TOKEN=([^;]+)/);
  if (xsrfMatch) {
    try { xsrfHeader = decodeURIComponent(xsrfMatch[1]); } catch (e) { xsrfHeader = xsrfMatch[1]; }
  }
  const headers = { 'X-CSRF-TOKEN': csrfToken, 'Cookie': cookieHeader, 'Content-Type': 'application/x-www-form-urlencoded' };
  if (xsrfHeader) headers['X-XSRF-TOKEN'] = xsrfHeader;
  if (debugFailures < 5) console.log('Sending borrow with headers:', { cookieHeader, csrfTokenPresent: !!csrfToken, xsrfHeaderPresent: !!xsrfHeader });
  // If running against the no-csrf endpoint, send the secret header and don't rely on cookies
  if (argv.secret) {
    headers['X-TEST-SECRET'] = argv.secret;
    // don't send cookie header to avoid confusion
    delete headers['Cookie'];
  }
  const res = await client.post(endpoint, form, { headers, timeout: 10000 });
    if (res.status === 201 && res.data && res.data.success) {
      success++;
    } else {
      fail++;
      if (debugFailures < 5) {
        console.log('/testing/borrow non-201 response:', res.status, res.data);
        debugFailures++;
      }
    }
  } catch (err) {
    fail++;
    if (debugFailures < 5) {
      if (err.response) {
        console.log('/testing/borrow error:', err.response.status, err.response.data);
      } else {
        console.log('/testing/borrow error no response:', err.message);
      }
      debugFailures++;
    }
  } finally {
    inFlight--;
  }
}

async function run() {
  const csrf = await obtainCsrfAndLogin();
  const pool = [];
  for (let i = 0; i < argv.concurrency; i++) pool.push(Promise.resolve());
  while (sent < argv.requests) {
    if (inFlight < argv.concurrency) {
      pool.push(sendOne(csrf));
    } else {
      await new Promise(r => setTimeout(r, 10));
    }
  }
  // wait for all in-flight
  while (inFlight > 0) await new Promise(r => setTimeout(r, 50));

  console.log(`Done. Sent: ${sent}, Success: ${success}, Fail: ${fail}`);
}

run();
