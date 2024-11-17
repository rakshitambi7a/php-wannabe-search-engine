<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Engine</title>
    <link rel="icon" href="/public/assets/icons8-search-16.png" type="image/x-icon" sizes="16x16">
    <style>

        @font-face{
            font-family: "Papyrus W01";
            src: url("assets/a0e1b1883c0cf520e9c50d0cd91cd0d0.eot");
            src: url("assets/a0e1b1883c0cf520e9c50d0cd91cd0d0.eot?#iefix")format("embedded-opentype"),
            url("assets/a0e1b1883c0cf520e9c50d0cd91cd0d0.woff")format("woff"),
            url("assets/a0e1b1883c0cf520e9c50d0cd91cd0d0.woff2")format("woff2"),
            url("assets/a0e1b1883c0cf520e9c50d0cd91cd0d0.ttf")format("truetype"),
            url("assets/a0e1b1883c0cf520e9c50d0cd91cd0d0.svg#Papyrus W01")format("svg");
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
            justify-content: flex-end;
        }

        .sign-in {
            padding: 0.5rem 1rem;
            background: transparent;
            border: 1px solid #333;
            color: #fff;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .sign-in:hover {
            background-color: #333;
        }

        main {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .logo {
            font-size: 4rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .tagline {
            font-size: 1.5rem;
            color: #888;
            margin-bottom: 2rem;
        }

        .search-container {
            width: 100%;
            max-width: 600px;
            position: relative;
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
    </style>
</head>
<body>
<header>
    <button class="sign-in">Sign In</button>
</header>
<main>
    <div class="logo">SearchEngine</div>
    <div class="tagline">Find what you're looking for</div>
    <div class="search-container">
        <form class="search-form" action="../app/views/search.php" method="GET">
            <input
                type="text"
                name="q"
                class="search-input"
                placeholder="Enter your search query..."
                autocomplete="off"
                required
            >
            <button type="submit" class="search-button">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </button>
        </form>
    </div>
</main>
</body>
</html>