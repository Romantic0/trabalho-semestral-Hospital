

<?php
require_once '../config/db.php';

// Registrar avaliação
function registrarAvaliacao($pdo, $avaliacoes, $feedback) {
    foreach ($avaliacoes as $avaliacao) {
        $stmt = $pdo->prepare("
            INSERT INTO avaliacoes (pergunta_id, resposta, feedback)
            VALUES (:pergunta_id, :resposta, :feedback)
        ");
        $stmt->execute([
            'pergunta_id' => $avaliacao['pergunta_id'],
            'resposta' => $avaliacao['resposta'],
            'feedback' => $feedback,
        ]);
    }
}

// Listar avaliações com média por pergunta
function listarAvaliacoes($pdo) {
    $stmt = $pdo->prepare("
        SELECT p.texto AS pergunta, 
               AVG(a.resposta) AS media_resposta, 
               COUNT(a.id) AS total_respostas
        FROM avaliacoes a
        JOIN perguntas p ON a.pergunta_id = p.id
        GROUP BY p.texto
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>