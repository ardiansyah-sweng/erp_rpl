<?php
// Test direct database insert to erp_rpl_test database
echo "Testing direct database insertion...\n";

try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=erp_rpl_test', 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check current data
    echo "Current branches:\n";
    $stmt = $pdo->query("SELECT * FROM branches");
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "- " . $row['branch_name'] . "\n";
    }
    
    // Try direct insert
    $stmt = $pdo->prepare("INSERT INTO branches (branch_name, branch_address, branch_telephone, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
    $result = $stmt->execute(['Direct Test Branch', 'Direct Test Address', '08199999999', 1]);
    
    if($result) {
        echo "Direct insert SUCCESS!\n";
        
        // Check again
        echo "After insert:\n";
        $stmt = $pdo->query("SELECT * FROM branches");
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "- " . $row['branch_name'] . "\n";
        }
        
        // Clean up
        $pdo->exec("DELETE FROM branches WHERE branch_name = 'Direct Test Branch'");
        echo "Cleaned up test data.\n";
    } else {
        echo "Direct insert FAILED!\n";
    }
    
} catch(Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
