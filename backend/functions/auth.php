<?php
session_start();
require_once '../config/db.php'; // Inclui a conexão com o banco

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Valida os campos
    if (empty($username) || empty($password)) {
        $_SESSION['login_error'] = "Usuário e senha são obrigatórios!";
        header("Location: ../../frontend/login.php");

        exit();
    }

    try {
        // Verifica o usuário no banco de dados
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Login bem-sucedido, salva a sessão
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            // Redireciona para o dashboard
            header("Location: ../../frontend/dashboard.html");
            exit();
        } else {
            // Credenciais inválidas
            $_SESSION['login_error'] = "Usuário ou senha inválidos!";
            header("Location: ../../frontend/login.php");
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['login_error'] = "Erro no sistema: " . $e->getMessage();
        header("Location: ../../frontend/login.php");
        exit();
    }
} else {
    // Acesso direto ao arquivo sem formulário
    header("Location: ../../frontend/login.php");
    exit();
}
?>