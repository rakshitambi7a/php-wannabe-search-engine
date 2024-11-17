<?php
require_once 'db_config.php';

try {
    $search_query = isset($_GET['q']) ? trim($_GET['q']) : '';
    if (empty($search_query)) {
        header('Location: index.html');
        exit;
    }
    $sql = "
(SELECT id, title, description, url, keywords, 1 as source
 FROM search_results
 WHERE
     title LIKE :query OR
     description LIKE :query OR
     keywords LIKE :query)
UNION ALL
(SELECT id,
        CONCAT(UPPER(SUBSTRING(domain, 1, 1)), SUBSTRING(domain, 2)) AS title,
        CONCAT('Rank: ', CAST(`rank` AS CHAR)) AS description,
        CONCAT('https://', domain) AS url,
        '' AS keywords,
        2 AS source
 FROM top_sites
 WHERE domain LIKE :query)

LIMIT 10
;
";

    $stmt = $pdo->prepare($sql);
    $query = "%{$search_query}%";
    $stmt->execute(['query' => $query]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - <?php echo htmlspecialchars($search_query); ?></title>
    <link rel="icon" href="/public/assets/icons8-search-16.png" type="image/x-icon" sizes="16x16">
    <style>
        /* Your existing CSS here */
        @font-face{
            font-family: "Papyrus W01";
            src: url("../../public/assets/a0e1b1883c0cf520e9c50d0cd91cd0d0.eot");
            src: url("../../public/assets/a0e1b1883c0cf520e9c50d0cd91cd0d0.eot?#iefix")format("embedded-opentype"),
            url("../../public/assets/a0e1b1883c0cf520e9c50d0cd91cd0d0.woff")format("woff"),
            url("../../public/assets/a0e1b1883c0cf520e9c50d0cd91cd0d0.woff2")format("woff2"),
            url("../../public/assets/a0e1b1883c0cf520e9c50d0cd91cd0d0.ttf")format("truetype"),
            url("../../public/assets/a0e1b1883c0cf520e9c50d0cd91cd0d0.svg#Papyrus W01")format("svg");
            font-weight:normal;
            font-style:normal;
            font-display:swap;
        }

        :root {
            --gradient-start: rgb(255, 148, 133);
            --gradient-end: rgba(175, 175, 255, 0.75);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, "Papyrus W01", "Segoe UI", Roboto, sans-serif;
            background-color: #1a1a1a;
            color: #ffffff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        header {
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            text-decoration: none;
        }
        .logo > h1{
            background: linear-gradient(to right, var(--gradient-start), var(--gradient-end));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-fill-color: transparent;
            font-size: 2em; /* Adjust the font size as needed */
            font-weight: bold; /* Adjust the font weight as needed */
        }

        .sign-in {
            padding: 0.5rem 1rem;
            background: gainsboro;
            border: 1px solid aliceblue;
            color: #1c1c1c;
            font-weight: 600;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .sign-in:hover {
            background-color: #ffffff;
        }

        main {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 2rem;
        }

        .search-container {
            width: 100%;
            max-width: 600px;
            position: relative;
            margin-bottom: 2rem;
        }

        .search-form {
            width: 100%;
            position: relative;
        }

        .search-input {
            width: 100%;
            padding: 1rem;
            font-size: 1rem;
            background-color: #2a2a2a;
            border: none;
            border-radius: 8px;
            color: #fff;
            padding-right: 50px;
        }

        .search-input:focus {
            outline: none;
        }

        .search-container::before {
            content: '';
            position: absolute;
            inset: -2px;
            background: linear-gradient(to right, var(--gradient-start), var(--gradient-end));
            border-radius: 10px;
            z-index: -1;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .search-container:focus-within::before {
            opacity: 1;
        }

        .search-button {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #fff;
            cursor: pointer;
            padding: 8px;
        }

        .search-button:hover {
            color: var(--gradient-end);
        }

        .search-results {
            width: 100%;
            max-width: 800px;
        }

        .result-item {
            background-color: #2a2a2a;
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 8px;
        }

        .result-title {
            margin-bottom: 0.5rem;
        }

        .result-title a {
            color: skyblue;
            text-decoration: none;
        }

        .result-content {
            margin-bottom: 0.5rem;
            color: #ccc;
        }

        .url {
            color: var(--gradient-start);
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
<header>
    <a href="../../public" class="logo"><h1 >SearchEngine<h1></a>
    <button class="sign-in">Sign In</button>
</header>
<main>
    <div class="search-container">
        <form class="search-form" action="search.php" method="GET">
            <input
                    type="text"
                    name="q"
                    class="search-input"
                    placeholder="Enter your search query..."
                    autocomplete="off"
                    required
                    value="<?php echo htmlspecialchars($search_query); ?>"
            >
            <button type="submit" class="search-button">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </button>
        </form>
    </div>
    <div class="search-results">
        <?php if (count($results) > 0): ?>
            <?php foreach ($results as $result): ?>
                <div class="result-item">
                    <h2 class="result-title">
                        <a href="<?php echo $result['source'] == 1 ? htmlspecialchars($result['url']) : 'https://' . htmlspecialchars($result['title']); ?>">
                            <?php echo htmlspecialchars($result['title']); ?>
                        </a>
                    </h2>
                    <p class="result-content"><?php echo htmlspecialchars($result['description']); ?></p>
                    <?php if ($result['source'] == 1): ?>
                        <p class="url"><?php echo htmlspecialchars($result['url']); ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No results found for "<?php echo htmlspecialchars($search_query); ?>"</p>
        <?php endif; ?>
    </div>
</main>
</body>
</html>