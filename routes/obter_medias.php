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

    // Consulta para obter as médias de avaliação
    $query = "
        SELECT p.texto AS pergunta_texto, AVG(a.resposta) AS media
        FROM avaliacoes a
        JOIN perguntas p ON a.pergunta_id = p.id
        WHERE p.status = TRUE
        GROUP BY p.texto
    ";

    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $medias = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($medias);

} catch (PDOException $e) {
    echo json_encode(['error' => 'Erro ao conectar com o banco de dados: ' . $e->getMessage()]);
}
?>
