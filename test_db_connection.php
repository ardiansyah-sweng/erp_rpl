<?php
echo "Testing DB connection...\n";
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=erp_rpl_test', 'root', 'root');
    echo "MySQL Connection: SUCCESS\n";
    echo "Database connected successfully!\n";
} catch(Exception $e) {
    echo "MySQL Connection ERROR: " . $e->getMessage() . "\n";
}
