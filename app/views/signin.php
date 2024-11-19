<?php
session_start();
require_once '../config/config.php';

// Function to create a new user
function createUser($conn, $username, $password) {
    $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    return $stmt->execute();
}

// Database connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: ../../");
    exit();
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        $username = $conn->real_escape_string($_POST['username']);
        $password = $_POST['password'];

        if ($_POST['action'] == 'signin') {
            // Sign In logic
            $sql = "SELECT * FROM users WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                if ($password == $user['password']) {
                    // Start the session and store user information
                    session_start();
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $username;

                    // Redirect to the home page
                    header("Location: ../../");
                    exit();
                } else {
                    $message = "Invalid password.";
                }
            } else {
                $message = "User not found.";
            }
            $stmt->close();
        } elseif ($_POST['action'] == 'register') {
            // Register new user logic
            $sql = "SELECT * FROM users WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $message = "Username already exists. Please choose a different username.";
            } else {
                if (createUser($conn, $username, $password)) {
                    $message = "User registered successfully. You can now sign in.";
                } else {
                    $message = "Error registering user. Please try again.";
                }
            }
            $stmt->close();
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In / Register</title>
    <style>
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
            font-size: 2em;
            font-weight: bold;
        }

        main {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .form-container {
            background-color: #2a2a2a;
            padding: 2rem;
            border-radius: 8px;
            width: 100%;
            max-width: 400px;
        }

        h2 {
            margin-bottom: 1rem;
            text-align: center;
            background: linear-gradient(to right, var(--gradient-start), var(--gradient-end));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-fill-color: transparent;
        }

        form {
            margin-bottom: 1rem;
        }

        input {
            display: block;
            width: 100%;
            padding: 0.75rem;
            margin-bottom: 1rem;
            background-color: #3a3a3a;
            border: none;
            border-radius: 4px;
            color: #fff;
        }

        input[type="submit"] {
            background: linear-gradient(to right, var(--gradient-start), var(--gradient-end));
            color: #fff;
            font-weight: bold;
            cursor: pointer;
            transition: opacity 0.2s;
        }

        input[type="submit"]:hover {
            opacity: 0.9;
        }

        .message {
            margin-top: 1rem;
            text-align: center;
            color: #ccc;
        }

        .toggle-form {
            text-align: center;
            margin-top: 1rem;
        }

        .toggle-form a {
            color: var(--gradient-start);
            text-decoration: none;
        }

        .toggle-form a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<header>
    <a href="../../" class="logo">
        <h1>Search Engine</h1>
    </a>
</header>
<main>
    <div class="form-container">
        <h2 id="formTitle">Sign In</h2>
        <form id="signinForm" action="" method="post">
            <input type="hidden" name="action" value="signin">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Sign In">
        </form>
        <form id="registerForm" action="" method="post" style="display: none;">
            <input type="hidden" name="action" value="register">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Register">
        </form>
        <div class="toggle-form">
            <a href="#" id="toggleForm">Don't have an account? Register here</a>
        </div>
        <div class="message">
            <?php echo $message; ?>
        </div>
    </div>
</main>
<script>
    const signinForm = document.getElementById('signinForm');
    const registerForm = document.getElementById('registerForm');
    const toggleForm = document.getElementById('toggleForm');
    const formTitle = document.getElementById('formTitle');

    toggleForm.addEventListener('click', function(e) {
        e.preventDefault();
        if (signinForm.style.display === 'none') {
            signinForm.style.display = 'block';
            registerForm.style.display = 'none';
            toggleForm.textContent = "Don't have an account? Register here";
            formTitle.textContent = "Sign In";
        } else {
            signinForm.style.display = 'none';
            registerForm.style.display = 'block';
            toggleForm.textContent = "Already have an account? Sign in here";
            formTitle.textContent = "Register";
        }
    });
</script>
</body>
</html>