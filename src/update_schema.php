<?php
// update_schema.php

require_once 'db_config.php'; // Ensures this file sets up a valid $pdo object

$sql = "CREATE TABLE IF NOT EXISTS top_sites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    `rank` INT NOT NULL,
    domain VARCHAR(255) NOT NULL,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY (domain)
)";

try {
    $pdo->exec($sql);
    echo "Table 'top_sites' created successfully.\n";
} catch(PDOException $e) {
    error_log("Error creating table: " . $e->getMessage(), 0); // Logs error
    echo "An error occurred while creating the table.\n"; // User-friendly message
}