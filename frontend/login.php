<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../styles/login.css">
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>

        <!-- Exibir mensagem de erro, se existir -->
        <?php
        session_start();
        if (isset($_SESSION['login_error'])) {
            echo "<p style='color: red;'>" . $_SESSION['login_error'] . "</p>";
            unset($_SESSION['login_error']); // Remove o erro após exibir
        }
        ?>

        <form action="/hospitalv2/backend/functions/auth.php" method="POST">
            <label for="username">Usuário:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Senha:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Entrar</button>
        </form>
    </div>
</body>
</html>