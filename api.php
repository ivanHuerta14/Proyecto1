
<?php
header('Content-Type: application/json');
session_start();

require_once 'config.php'; 

$action = $_GET['action'] ?? null;

try {
    if ($action === 'read') {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
            exit();
        }
        
        // Lee los datos de la base de datos
        $search = $_GET['search'] ?? '';
        $sql = "SELECT * FROM registros WHERE nombre LIKE :search";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($data);
        exit();
    } elseif ($action === 'create') {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
            exit();
        }

        $nombre = $_POST['nombre'] ?? null;
        $valor = $_POST['valor'] ?? null;

        if (!$nombre || !$valor) {
            echo json_encode(['status' => 'error', 'message' => 'Nombre o valor están vacíos']);
            exit();
        }

        // Inserta un nuevo registro en la base de datos
        $sql = "INSERT INTO registros (nombre, valor) VALUES (:nombre, :valor)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':nombre' => $nombre, ':valor' => $valor]);
        
        echo json_encode(['status' => 'success', 'message' => 'Registro agregado exitosamente']);
        exit();
    } elseif ($action === 'update') {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
            exit();
        }

        $id = $_POST['id'] ?? null;
        $nombre = $_POST['nombre'] ?? null;
        $valor = $_POST['valor'] ?? null;

        if (!$id || !$nombre || !$valor) {
            echo json_encode(['status' => 'error', 'message' => 'ID, nombre o valor están vacíos']);
            exit();
        }

        // Actualiza un registro en la base de datos
        $sql = "UPDATE registros SET nombre = :nombre, valor = :valor WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':nombre' => $nombre, ':valor' => $valor, ':id' => $id]);
        
        echo json_encode(['status' => 'success', 'message' => 'Registro actualizado exitosamente']);
        exit();
    } elseif ($action === 'delete') {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
            exit();
        }

        $id = $_POST['id'] ?? null;

        if (!$id) {
            echo json_encode(['status' => 'error', 'message' => 'ID está vacío']);
            exit();
        }

        // Elimina un registro de la base de datos
        $sql = "DELETE FROM registros WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        
        echo json_encode(['status' => 'success', 'message' => 'Registro eliminado exitosamente']);
        exit();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Acción no válida']);
        exit();
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
    exit();
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
    exit();
}
?>
