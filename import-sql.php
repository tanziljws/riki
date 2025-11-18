<?php
/**
 * Script untuk import SQL ke MySQL Railway
 * Usage: php import-sql.php
 */

$host = 'trolley.proxy.rlwy.net';
$port = 49593;
$username = 'root';
$password = 'BUNIgCsnyeQPwCuZpxLXrBPNYAJoolki';
$database = 'railway';
$sqlFile = __DIR__ . '/galeriweb (3).sql';

// Koneksi ke MySQL
try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
    
    echo "✓ Connected to MySQL database\n";
    
    // Baca file SQL
    if (!file_exists($sqlFile)) {
        die("✗ SQL file not found: $sqlFile\n");
    }
    
    echo "✓ Reading SQL file...\n";
    $sql = file_get_contents($sqlFile);
    
    // Hapus komentar dan SET statements yang tidak perlu
    $sql = preg_replace('/^--.*$/m', '', $sql);
    $sql = preg_replace('/^\/\*.*?\*\//ms', '', $sql);
    
    // Split by semicolon, tapi hati-hati dengan semicolon di dalam string
    $statements = array_filter(
        array_map('trim', explode(';', $sql)),
        function($stmt) {
            return !empty($stmt) && 
                   !preg_match('/^(SET|START|COMMIT|\/\*|\*\/)/i', trim($stmt));
        }
    );
    
    echo "✓ Executing SQL statements...\n";
    $count = 0;
    $errors = 0;
    
    // Disable foreign key checks sementara
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (empty($statement)) continue;
        
        try {
            $pdo->exec($statement);
            $count++;
            if ($count % 10 == 0) {
                echo "  Processed $count statements...\n";
            }
        } catch (PDOException $e) {
            // Skip error jika table sudah exists atau constraint sudah ada
            if (strpos($e->getMessage(), 'already exists') === false && 
                strpos($e->getMessage(), 'Duplicate') === false) {
                echo "  ⚠ Warning: " . $e->getMessage() . "\n";
                $errors++;
            }
        }
    }
    
    // Enable foreign key checks kembali
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    
    echo "\n✓ Import completed!\n";
    echo "  - Statements executed: $count\n";
    if ($errors > 0) {
        echo "  - Warnings: $errors\n";
    }
    
} catch (PDOException $e) {
    die("✗ Database error: " . $e->getMessage() . "\n");
}

