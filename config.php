<?php
header('Content-Type: application/json'); // Asegura que el contenido siempre sea JSON

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "registros_db";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // En caso de error, devolver JSON con mensaje de error
    echo json_encode(['error' => 'Error de conexión: ' . $e->getMessage()]);
    exit(); // Termina la ejecución si falla la conexión
}
?>
