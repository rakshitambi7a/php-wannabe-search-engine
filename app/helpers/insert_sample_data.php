<?php
// insert_sample_data.php

require_once './db_config.php';
require_once './insert_sample_data.php';

$sample_data = [
    [
        'title' => 'Example Domain',
        'description' => 'This domain is for use in illustrative examples in documents. You may use this domain in literature without prior coordination or asking for permission.',
        'url' => 'https://example.com',
        'keywords' => 'example, domain, illustrative'
    ],
    [
        'title' => 'Stack Overflow - Where Developers Learn, Share, & Build Careers',
        'description' => 'Stack Overflow is the largest, most trusted online community for developers to learn, share their programming knowledge, and build their careers.',
        'url' => 'https://stackoverflow.com',
        'keywords' => 'programming, developers, questions, answers'
    ],
    [
        'title' => 'GitHub: Where the world builds software',
        'description' => 'GitHub is where over 65 million developers shape the future of software, together. Contribute to the open source community, manage your Git repositories, review code like a pro, track bugs and features, power your CI/CD and DevOps workflows, and secure code before you commit it.',
        'url' => 'https://github.com',
        'keywords' => 'git, version control, open source, developers'
    ],
    [
        'title' => 'Google: Search the world\'s information, including webpages, images, videos and more. Google has many special features to help you find exactly what you\'re looking for.',
        'description' => 'Google is the world\'s most popular search engine. It is the second most popular search engine after Yahoo! Search.',
        'url' => 'https://google.com',
        'keywords' => 'google, search, engine, developers'
    ]

];

$stmt = $pdo->prepare("INSERT INTO search_results (title, description, url, keywords) VALUES (?, ?, ?, ?)");

foreach ($sample_data as $data) {
    $stmt->execute([$data['title'], $data['description'], $data['url'], $data['keywords']]);
}

echo "Sample data inserted successfully.";