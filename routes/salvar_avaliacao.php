<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

try {
    // Conexão ao banco
    $pdo = new PDO('pgsql:host=localhost;dbname=hospitalweb', 'postgres', 'postgres');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verifica se é POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método não permitido');
    }

    // Coleta os dados recebidos
    $input = file_get_contents("php://input");
    $data = json_decode($input, true);

    if (!$data || !isset($data['avaliacoes']) || !is_array($data['avaliacoes'])) {
        throw new Exception('Dados inválidos');
    }

    $avaliacoes = $data['avaliacoes'];
    $feedback = isset($data['feedback']) ? $data['feedback'] : null;

    // Salva no banco
    $stmt = $pdo->prepare("
        INSERT INTO avaliacoes (pergunta_id, resposta, feedback, data_hora) 
        VALUES (:pergunta_id, :resposta, :feedback, NOW())
    ");

    foreach ($avaliacoes as $avaliacao) {
        $stmt->execute([
            ':pergunta_id' => $avaliacao['pergunta_id'],
            ':resposta' => $avaliacao['resposta'],
            ':feedback' => $feedback,
        ]);
    }

    echo json_encode(['success' => true, 'message' => 'Avaliação salva com sucesso!']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
