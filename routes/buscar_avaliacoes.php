<?php
header('Content-Type: application/json');

// Configuração do banco de dados
$host = 'localhost';
$dbname = 'hospitalweb';  // Alterar conforme seu banco de dados
$user = 'postgres';       // Alterar conforme seu usuário
$pass = 'postgres';       // Alterar conforme sua senha

// Conectar ao banco de dados
try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta para obter as avaliações detalhadas
    $query = "
        SELECT a.id, p.texto AS pergunta_texto, a.resposta, a.feedback, a.data_hora
        FROM avaliacoes a
        JOIN perguntas p ON a.pergunta_id = p.id
        ORDER BY a.data_hora DESC
    ";

    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $avaliacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($avaliacoes);

} catch (PDOException $e) {
    echo json_encode(['error' => 'Erro ao conectar com o banco de dados: ' . $e->getMessage()]);
}
?>
