<?php
// db_config.php
require_once '../config/config.php';

const PDO_ERROR_MODE = PDO::ATTR_ERRMODE;
const ERRMODE_EXCEPTION = PDO::ERRMODE_EXCEPTION;

function createSearchResultsTable($pdo) {
    $sql = "CREATE TABLE IF NOT EXISTS search_results (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        url VARCHAR(255) NOT NULL,
        keywords TEXT
    )";
    $pdo->exec($sql);
}

// Get database credentials
$dbHost = DB_HOST;
$dbName = DB_NAME;
$dbUser = DB_USER;
$dbPass = DB_PASS;

try {
    // Create a new PDO instance and set error mode
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
    $pdo->setAttribute(PDO_ERROR_MODE, ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Create the search_results table
createSearchResultsTable($pdo);