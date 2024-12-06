<?php
require_once './functions/perguntas.php';
require_once './functions/avaliacoes.php';
require_once './functions/auth.php';

header("Content-Type: application/json");

$action = $_GET['action'] ?? null;

switch ($action) {
    case "listar_perguntas":
        echo json_encode(listarPerguntas($pdo));
        break;
    case "adicionar_pergunta":
        adicionarPergunta($pdo, $_POST['texto']);
        echo json_encode(["success" => true]);
        break;
    case "registrar_avaliacao":
        registrarAvaliacao($pdo, $_POST['pergunta_id'], $_POST['resposta'], $_POST['feedback'] ?? null);
        echo json_encode(["success" => true]);
        break;
    case "listar_avaliacoes":
        echo json_encode(listarAvaliacoes($pdo));
        break;
    default:
        echo json_encode(["error" => "Ação inválida"]);
        break;
        case "listar_resumo":
            $stmt = $pdo->prepare("
                SELECT p.texto AS setor, 
                       AVG(a.resposta) AS media,
                       COUNT(a.id) AS total
                FROM avaliacoes a
                JOIN perguntas p ON a.pergunta_id = p.id
                GROUP BY p.texto
            ");
            $stmt->execute();
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            break;
            case "dashboard_resumo":
                $stmt = $pdo->prepare("
                    SELECT p.texto AS setor,
                           AVG(a.resposta) AS media,
                           COUNT(a.id) AS total
                    FROM avaliacoes a
                    JOIN perguntas p ON a.pergunta_id = p.id
                    GROUP BY p.texto
                ");
                $stmt->execute();
                echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
                break;
                case "registrar_avaliacao":
                    $data = json_decode(file_get_contents("php://input"), true);
                    $avaliacoes = $data['avaliacoes'];
                    $feedback = $data['feedback'] ?? null;
                
                    registrarAvaliacao($pdo, $avaliacoes, $feedback);
                    echo json_encode(["success" => true]);
                    break;
                    case "media_por_setor":
                        $stmt = $pdo->prepare("
                            SELECT p.texto AS setor, 
                                   AVG(a.resposta) AS media
                            FROM avaliacoes a
                            JOIN perguntas p ON a.pergunta_id = p.id
                            GROUP BY p.texto
                        ");
                        $stmt->execute();
                        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
                        break;
                    
                
              
}
?>