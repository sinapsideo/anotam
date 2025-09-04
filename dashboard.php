<?php
// /dashboard.php
// Inicia a sessão para acessar as variáveis de sessão.
session_start();

// Se o usuário não estiver logado, redireciona para a página de login.
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// O nome de usuário está na sessão, então podemos usá-lo se necessário.
$username = htmlspecialchars($_SESSION["username"]);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - AnotaMed</title>

    <!-- CSS Principal -->
    <link rel="stylesheet" href="/css/style.css">

    <!-- Fontes do Google -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">

    <!-- Ícone da página -->
    <link rel="icon" type="image/png" href="/images/icon.png">

    <!-- Font Awesome para ícones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        /* Estilos básicos que foram movidos de password_entry.php para garantir a estrutura */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #e9ecef; /* Cor de fundo padrão para o dashboard */
            display: flex;
            flex-direction: column;
        }
        *, *:before, *:after {
            box-sizing: inherit;
        }
        .dashboard-wrapper {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
    </style>
</head>
<body>

    <div class="dashboard-wrapper">
        <?php
        // Inclui o conteúdo principal do dashboard.
        // O `dashboard_content.php` contém as abas, calculadoras, etc.
        include_once "dashboard_content.php";
        ?>
    </div>

    <!-- Scripts necessários para a funcionalidade do dashboard -->
    <!-- Marked.js para renderizar Markdown (se usado nas notas) -->
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <!-- Script principal com a lógica das abas, calculadoras, etc. -->
    <script src="js/script.js"></script>

</body>
</html>
