<?php
session_start();
header('Content-Type: application/json'); // Asegura que la respuesta sea JSON

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Aquí puedes validar el usuario y la contraseña, por ejemplo, comparando con la base de datos
    // Este es solo un ejemplo básico de verificación
    if ($username === 'usuario_correcto' && $password === 'contraseña_correcta') {
        $_SESSION['user'] = $username;
        echo json_encode(['status' => 'success', 'message' => 'Inicio de sesión exitoso']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Usuario o contraseña incorrectos']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
}
?>