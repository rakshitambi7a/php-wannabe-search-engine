<?php
require_once '../config/db_config.php';

function downloadFile($url, $outputFile) {
    $fileContent = file_get_contents($url);
    if ($fileContent === FALSE) {
        throw new RuntimeException("Error downloading the file from $url.");
    }
    if (file_put_contents($outputFile, $fileContent) === FALSE) {
        throw new RuntimeException("Error saving the downloaded file to $outputFile.");
    }
}

function extractZipFile($zipFile, $destination = './') {
    try {
        $phar = new PharData($zipFile);
        $phar->extractTo($destination);
    } catch (Exception $e) {
        throw new RuntimeException("Could not extract the zip file: " . $e->getMessage());
    }
}

function readCsvFile($csvFile, $limit) {
    $sites = [];
    if (($handle = fopen($csvFile, "r")) !== FALSE) {
        $count = 0;
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE && $count < $limit) {
            $sites[] = [
                'rank' => (int)$data[0],
                'domain' => $data[1]
            ];
            $count++;
        }
        fclose($handle);
        return $sites;
    } else {
        throw new RuntimeException("Error reading the CSV file.");
    }
}

function cleanupFiles($files) {
    foreach ($files as $file) {
        if (!unlink($file)) {
            throw new RuntimeException("Error deleting the file $file.");
        }
    }
}

function fetchTrancoList($limit = 1000000) {
    $url = "https://tranco-list.eu/top-1m.csv.zip";
    $zipFile = "top-1m.csv.zip";
    $csvFile = "top-1m.csv";

    downloadFile($url, $zipFile);
    extractZipFile($zipFile);
    $sites = readCsvFile($csvFile, $limit);
    cleanupFiles([$zipFile, $csvFile]);

    return $sites;
}

function updateDatabase($pdo, $sites) {
    if (!$pdo || !($pdo instanceof PDO)) {
        throw new InvalidArgumentException("Invalid PDO instance.");
    }

    $stmt = $pdo->prepare("INSERT INTO top_sites (`rank`, domain) VALUES (:rank, :domain) 
                           ON DUPLICATE KEY UPDATE `rank` = VALUES(`rank`), domain = VALUES(domain)");

    $pdo->beginTransaction();

    try {
        foreach ($sites as $site) {
            $stmt->execute([
                ':rank' => $site['rank'],
                ':domain' => $site['domain']
            ]);
        }
        $pdo->commit();
        echo "Database updated successfully.\n";
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Error updating database: " . $e->getMessage() . "\n";
    }
}

// Fetch and update the top 1 million sites
try {
    $sites = fetchTrancoList();
    updateDatabase($pdo, $sites);
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}