<?php
// Test HTTP POST langsung ke /branches endpoint
echo "Testing direct HTTP POST to branches endpoint...\n";

// Get CSRF token first
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://127.0.0.1:8000/branches/create');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

echo "GET /branches/create - HTTP Code: $httpCode\n";

// Extract CSRF token
preg_match('/<input[^>]*name="_token"[^>]*value="([^"]*)"/', $response, $matches);
$csrfToken = $matches[1] ?? '';

echo "CSRF Token: " . ($csrfToken ? "Found ($csrfToken)" : "NOT FOUND") . "\n";

if ($csrfToken) {
    // Now POST data
    $postData = http_build_query([
        '_token' => $csrfToken,
        'branch_name' => 'HTTP Test Branch',
        'branch_address' => 'HTTP Test Address',
        'branch_telephone' => '08199999999'
    ]);

    curl_setopt($ch, CURLOPT_URL, 'http://127.0.0.1:8000/branches');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_HEADER, true);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    echo "POST /branches - HTTP Code: $httpCode\n";
    echo "Response headers:\n" . substr($response, 0, 500) . "\n";
    
    // Check database
    try {
        $pdo = new PDO('mysql:host=127.0.0.1;dbname=erp_rpl_test', 'root', 'root');
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM branches WHERE branch_name = ?");
        $stmt->execute(['HTTP Test Branch']);
        $count = $stmt->fetchColumn();
        
        echo "Database check: " . ($count > 0 ? "SUCCESS - Data found!" : "FAILED - No data") . "\n";
        
        if ($count > 0) {
            // Clean up
            $pdo->prepare("DELETE FROM branches WHERE branch_name = ?")->execute(['HTTP Test Branch']);
            echo "Cleaned up test data.\n";
        }
    } catch (Exception $e) {
        echo "Database error: " . $e->getMessage() . "\n";
    }
}

curl_close($ch);

// Clean up cookie file
if (file_exists('cookie.txt')) {
    unlink('cookie.txt');
}
