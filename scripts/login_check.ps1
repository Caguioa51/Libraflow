# login_check.ps1
# Fetch login page, extract CSRF, post credentials, fetch /dashboard and print results
$loginUrl = 'http://127.0.0.1:8000/login'
$dashboardUrl = 'http://127.0.0.1:8000/dashboard'

Write-Host "Waiting briefly for server to become ready..."
Start-Sleep -Seconds 2

try {
    Write-Host "Fetching login page: $loginUrl"
    $resp = Invoke-WebRequest -Uri $loginUrl -SessionVariable session -UseBasicParsing -ErrorAction Stop
} catch {
    Write-Host "ERROR: Could not fetch login page:`n$($_.Exception.Message)"; exit 2
}

# Extract CSRF token
$token = ''
if ($resp.Content -match 'name="_token" value="([^\"]+)"') {
    $token = $matches[1]
    Write-Host "Found CSRF token: (length) $($token.Length)"
} else {
    Write-Host "WARNING: CSRF token not found in login page; attempting to proceed anyway"
}

# Prepare form body
$body = @{
    _token = $token
    email = 'admin@gmail.com'
    password = '11111111'
}

Write-Host "Submitting login POST..."
try {
    # Add CSRF header as some setups expect it in headers as well
    $headers = @{ 'X-CSRF-TOKEN' = $token; 'X-Requested-With' = 'XMLHttpRequest' }
    $post = Invoke-WebRequest -Uri $loginUrl -Method Post -Body $body -WebSession $session -Headers $headers -UseBasicParsing -ErrorAction Stop

    # Try to read status code from available properties
    $status = $null
    if ($post -and $post.BaseResponse -and $post.BaseResponse.StatusCode) { $status = $post.BaseResponse.StatusCode }
    elseif ($post -and $post.StatusCode) { $status = $post.StatusCode }
    Write-Host "Login POST completed. StatusCode: $status"
    if ($post.Headers.Location) { Write-Host "Redirect location header: $($post.Headers.Location)" }
} catch {
    Write-Host "Login POST finished with exception: $($_.Exception.Message)";
    if ($_.Exception.Response) { Write-Host "Exception response status: $($_.Exception.Response.StatusCode.Value__)" }
}

# Show current cookies in session for debugging
Write-Host "Cookies stored in session (after POST):"
if ($session -and $session.Cookies) {
    $session.Cookies | ForEach-Object { Write-Host "- $($_.Name) = $($_.Value) (Domain: $($_.Domain); Path: $($_.Path))" }
} else { Write-Host "(no cookies found in session)" }

Start-Sleep -Seconds 1

Write-Host "Fetching dashboard: $dashboardUrl"
try {
    $dash = Invoke-WebRequest -Uri $dashboardUrl -WebSession $session -UseBasicParsing -ErrorAction Stop
} catch {
    Write-Host "ERROR: Could not fetch dashboard:`n$($_.Exception.Message)"; exit 3
}

# Check for admin marker in dashboard content
$foundAdmin = $false
if ($dash.Content -match 'Admin Administrator') { $foundAdmin = $true }
if ($dash.Content -match 'admin@gmail.com') { $foundAdmin = $true }

Write-Host "---- Dashboard check result ----"
if ($foundAdmin) {
    Write-Host "SUCCESS: Dashboard page contains 'Admin Administrator' or admin email. Login appears to have succeeded."
} else {
    Write-Host "FAIL: Dashboard page did NOT contain admin name/email. Dumping a small snippet for debugging..."
    $snippet = $dash.Content.Substring(0, [Math]::Min(800, $dash.Content.Length))
    Write-Host $snippet
}

# Save dashboard HTML for manual inspection
$dashPath = Join-Path -Path (Get-Location) -ChildPath 'storage\logs\dashboard_check.html'
$dash.Content | Out-File -FilePath $dashPath -Encoding utf8
Write-Host "Saved dashboard HTML to: $dashPath"

exit 0
