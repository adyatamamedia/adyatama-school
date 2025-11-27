<?php
// Database connection
$host = 'localhost';
$db = 'adyatama_school';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== SETTINGS IN 'general' GROUP ===\n\n";
    $stmt = $pdo->query("SELECT key_name, group_name, type, description FROM settings WHERE group_name = 'general' ORDER BY id");
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($results as $row) {
        echo "Key: {$row['key_name']}\n";
        echo "Type: {$row['type']}\n";
        echo "Description: {$row['description']}\n";
        echo "---\n";
    }
    
    echo "\nTotal: " . count($results) . " settings in 'general'\n";
    
    echo "\n\n=== ALL GROUP NAMES ===\n\n";
    $stmt2 = $pdo->query("SELECT group_name, COUNT(*) as total FROM settings GROUP BY group_name ORDER BY group_name");
    $groups = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($groups as $group) {
        echo "{$group['group_name']}: {$group['total']} settings\n";
    }
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
