<?php
// /password_entry.php (Simplified)
session_start();
require_once "includes/db_connect.php";

$username_from_url = "";
$profile_login_err = "";

// Get login error from session if it exists
if (isset($_SESSION["profile_login_error"])) {
    $profile_login_err = $_SESSION["profile_login_error"];
    unset($_SESSION["profile_login_error"]);
}

// Ensure username is provided in the URL
if (!isset($_GET['username']) || empty(trim($_GET['username']))) {
    // If no username, maybe redirect to the main login page
    header("location: login.php");
    exit;
}
$username_from_url = trim($_GET['username']);

// Check if the user from the URL is already logged in. If so, redirect to the dashboard.
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && $_SESSION["username"] === $username_from_url) {
    header("Location: dashboard.php");
    exit;
}

// Verify if the user exists in the database
$user_exists_in_db = false;
$sql_check = "SELECT id FROM users WHERE username = ?";
if ($stmt_check = $mysqli->prepare($sql_check)) {
    $stmt_check->bind_param("s", $param_username_check);
    $param_username_check = $username_from_url;
    if ($stmt_check->execute()) {
        $stmt_check->store_result();
        if ($stmt_check->num_rows == 1) {
            $user_exists_in_db = true;
        }
    }
    $stmt_check->close();
}

// If user does not exist, redirect to login page with an error
if (!$user_exists_in_db) {
    $_SESSION["login_error"] = "Usuário \"" . htmlspecialchars($username_from_url) . "\" não encontrado.";
    header("location: login.php");
    exit;
}

// If we reach here, it means the user exists but is not logged in as this user.
// So, we show the login form.
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar como <?php echo htmlspecialchars($username_from_url); ?> - AnotaMed</title>
    
    <!-- Using a simplified stylesheet or inline styles for the login form -->
    <link rel="stylesheet" href="/css/style.css"> <!-- Assuming style.css contains login form styles -->
    <link rel="icon" type="image/png" href="/images/icon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">

    <style>
        /* This is the styling for the centered login form from the original file */
        html, body {
            height: 100%;
            box-sizing: border-box;
        }
        body {
            font-family: 'Open Sans', sans-serif;
            margin: 0;
            background-color: #f0f2f5;
            display: flex; 
            justify-content: center; 
            align-items: center;   
            padding: 20px; 
        }
        *, *:before, *:after {
            box-sizing: inherit;
        }
        .login-container {
            background: #fff;
            padding: 30px 40px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            text-align: center;
            width: 100%; 
            max-width: 380px;
        }
        .login-container img { width: 70px; margin-bottom:10px; }
        .login-container h2 { margin-bottom: 5px; color: #333; font-size:1.8em; }
        .login-container h3 { margin-bottom: 25px; color: #555; font-weight: normal; font-size: 1.1em; }
        .form-group { margin-bottom: 20px; text-align: left; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; color: #555; }
        .form-group input { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size:1em; }
        .btn-login { background-color: #007bff; color: white; padding: 12px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; width: 100%; transition: background-color 0.3s ease; }
        .btn-login:hover { background-color: #0056b3; }
        .error-message { color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 10px; border-radius: 4px; margin-bottom: 20px; font-size: 0.9em; text-align:left; }
        .login-link-alt { margin-top: 20px; font-size: 0.9em;}
        .login-link-alt a { color: #007bff; text-decoration: none; }
        .login-link-alt a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="login-container">
        <img src="/images/anotamed1.png" alt="Logo AnotaMed">
        <h2 style="color: #000000;">anotamed</h2>
        <h3>Entrar como <strong><?php echo htmlspecialchars($username_from_url); ?></strong></h3>

        <?php if(!empty($profile_login_err)): ?>
            <p class="error-message"><?php echo $profile_login_err; ?></p>
        <?php endif; ?>

        <form action="auth.php" method="post">
            <input type="hidden" name="username" value="<?php echo htmlspecialchars($username_from_url); ?>">
            <input type="hidden" name="login_source" value="profile_url">

            <div class="form-group">
                <label for="password">Senha:</label>
                <input type="password" name="password" id="password" required autofocus>
            </div>
            <button type="submit" class="btn-login">Entrar</button>
        </form>
        <p class="login-link-alt">
            Não é <?php echo htmlspecialchars($username_from_url); ?>?
            <a href="login.php">Use outra conta</a>
            <?php if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && $_SESSION['username'] !== $username_from_url): ?>
                 ou <a href="/<?php echo urlencode($_SESSION['username']); ?>">ir para o seu perfil (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a>.
                 Ou <a href="logout.php?redirect_to=<?php echo urlencode($username_from_url); ?>">Sair da conta atual</a> para tentar como <?php echo htmlspecialchars($username_from_url); ?>.
            <?php endif; ?>
        </p>
    </div>
</body>
</html>