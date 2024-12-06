<?php
require_once '../config/db.php';

// Verifica a ação na URL
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'list':
        listarPerguntas($pdo);
        break;
    case 'add':
        adicionarPergunta($pdo);
        break;
    case 'delete':
        excluirPergunta($pdo); // Chama a função de exclusão
        break;
    default:
        echo json_encode(['error' => 'Ação não reconhecida']);
        break;
}

function listarPerguntas($pdo) {
    $stmt = $pdo->prepare("SELECT * FROM perguntas WHERE status = TRUE");
    $stmt->execute();
    $perguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($perguntas ?: []);
}

function adicionarPergunta($pdo) {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['texto'])) {
        $stmt = $pdo->prepare("INSERT INTO perguntas (texto) VALUES (:texto)");
        $stmt->execute(['texto' => $data['texto']]);

        echo json_encode(['message' => 'Pergunta adicionada com sucesso!']);
    } else {
        echo json_encode(['error' => 'Texto da pergunta não fornecido']);
    }
}

// Função para excluir a pergunta
function excluirPergunta($pdo) {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['id'])) {
        // Altere o status para 'false' em vez de excluir fisicamente da tabela
        $stmt = $pdo->prepare("UPDATE perguntas SET status = FALSE WHERE id = :id");
        $stmt->execute(['id' => $data['id']]);

        // Retorna uma resposta de sucesso
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'ID da pergunta não fornecido']);
    }
}
?>
