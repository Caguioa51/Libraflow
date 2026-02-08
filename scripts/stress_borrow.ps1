# PowerShell stress script to fire concurrent borrow requests
# Usage: Start a local server (php artisan serve) then run this script from project root.
# Example: php artisan serve; then in another terminal: powershell -ExecutionPolicy Bypass -File .\scripts\stress_borrow.ps1 -Url http://127.0.0.1:8000 -BookId 1 -StudentId 2 -Requests 50 -Concurrency 10
param(
    [string]$Url = "http://127.0.0.1:8000",
    [int]$BookId = 1,
    [int]$StudentId = 2,
    [int]$Requests = 50,
    [int]$Concurrency = 10
)

$uri = "$Url/borrowings"
Write-Host "Stress test: $Requests requests against $uri (book_id=$BookId, user_id=$StudentId) with concurrency=$Concurrency"

$jobs = @()
for ($i = 0; $i -lt $Requests; $i++) {
    $body = @{ user_id = $StudentId; book_id = $BookId } | ConvertTo-Json
    $job = Start-Job -ScriptBlock {
        param($uri, $body)
        try {
            $res = Invoke-RestMethod -Uri $uri -Method Post -Body $body -ContentType 'application/json'
            return @{ success = $true; body = $res }
        } catch {
            return @{ success = $false; error = $_.Exception.Message }
        }
    } -ArgumentList $uri, $body
    $jobs += $job
    while ($jobs.Count -ge $Concurrency) {
        Start-Sleep -Milliseconds 200
        $jobs = $jobs | Where-Object { $_.State -eq 'Running' }
    }
}

# Wait for remaining jobs
while (($jobs | Where-Object { $_.State -eq 'Running' }).Count -gt 0) { Start-Sleep -Milliseconds 200 }

# Collect results
$all = Get-Job | Receive-Job -Keep
$successCount = ($all | Where-Object { $_.success -eq $true }).Count
$failCount = ($all | Where-Object { $_.success -ne $true }).Count
Write-Host "Total: $Requests, Success: $successCount, Fail: $failCount"

# Cleanup jobs
Get-Job | Remove-Job
