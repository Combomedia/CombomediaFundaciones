<?php
header('Content-Type: application/json');

// Configuración de la base de datos (reemplazar con tus credenciales)
$servername = "http://localhost/phpmyadmin/index.php?route=/sql&pos=0&db=combomediaplatform&table=registros";
$username = "root";
$password = "12345678";
$dbname = "combomediaplatform";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die(json_encode([
        'status' => 'error',
        'message' => 'Error de conexión: ' . $conn->connect_error
    ]));
}

// Recibir datos
$data = json_decode(file_get_contents('php://input'), true);

// Sanitizar entradas
$name = $conn->real_escape_string($data['name']);
$email = $conn->real_escape_string($data['email']);
$phone = $conn->real_escape_string($data['phone'] ?? '');
$interest = $conn->real_escape_string($data['interest']);
$message = $conn->real_escape_string($data['message'] ?? '');

// Validar email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Correo electrónico inválido'
    ]);
    exit;
}

// Preparar y ejecutar consulta
$sql = "INSERT INTO registros (nombre, email, telefono, interes, mensaje, fecha_registro)
        VALUES ('$name', '$email', '$phone', '$interest', '$message', NOW())";

if ($conn->query($sql) === TRUE) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Registro guardado correctamente'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Error al guardar: ' . $conn->error
    ]);
}

$conn->close();
?>