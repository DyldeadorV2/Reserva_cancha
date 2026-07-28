<?php
require_once __DIR__ . '/config/db.php';

header('Content-Type: application/json; charset=utf-8');

function obtenerCanchas()
{
    $conn = conect();
    $res = $conn->query('SELECT * FROM canchas');

    if ($res === false) {
        throw new Exception('No se pudo consultar las canchas: ' . $conn->error, $conn->errno);
    }

    return $res->fetch_all(MYSQLI_ASSOC);
}

function reservar($id_usuario, $id_cancha, $fecha, $hora_inicio, $estado_pago)
{
    $conn = conect();
    $stmt = $conn->prepare("INSERT INTO reservas (id_usuario, id_cancha, fecha, hora_inicio, estado_pago) VALUES (?, ?, ?, ?, ?)");

    if ($stmt === false) {
        throw new Exception('No se pudo preparar la reserva: ' . $conn->error, $conn->errno);
    }

    $stmt->bind_param('iisss', $id_usuario, $id_cancha, $fecha, $hora_inicio, $estado_pago);

    if (!$stmt->execute()) {
        throw new Exception('No se pudo guardar la reserva: ' . $stmt->error, $stmt->errno);
    }

    return ['success' => true, 'message' => 'Reserva creada correctamente'];
}

function reservas()
{
    $conn = conect();
    $res = $conn->query("SELECT r.fecha, r.hora_inicio, c.nombre_cancha FROM reservas r INNER JOIN canchas c ON c.id_cancha = r.id_cancha");

    if ($res === false) {
        throw new Exception('No se pudo consultar las reservas: ' . $conn->error, $conn->errno);
    }

    return $res->fetch_all(MYSQLI_ASSOC);
}

function registrarUsuario($nombre, $telefono, $email, $password)
{
    $conn = conect();
    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, teléfono, correo, contraseña) VALUES (?, ?, ?, ?)");

    if ($stmt === false) {
        throw new Exception('No se pudo preparar el registro de usuario: ' . $conn->error, $conn->errno);
    }

    $stmt->bind_param('ssss', $nombre, $telefono, $email, $password);

    if (!$stmt->execute()) {
        throw new Exception('No se pudo registrar el usuario: ' . $stmt->error, $stmt->errno);
    }

    return ['success' => true, 'message' => 'Usuario registrado correctamente'];
}

function login($email, $password)
{
    $conn = conect();
    $stmt = $conn->prepare('SELECT * FROM usuarios WHERE correo = ? AND contraseña = ?');

    if ($stmt === false) {
        throw new Exception('No se pudo preparar la consulta de login: ' . $conn->error, $conn->errno);
    }

    $stmt->bind_param('ss', $email, $password);

    if (!$stmt->execute()) {
        throw new Exception('No se pudo verificar el login: ' . $stmt->error, $stmt->errno);
    }

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return ['success' => true, 'message' => 'Inicio de sesión correcto'];
    }

    return ['success' => false, 'message' => 'Credenciales inválidas'];
}

function eliminarReserva($id_reserva)
{
    $conn = conect();
    $stmt = $conn->prepare('DELETE FROM reservas WHERE id_reserva = ?');

    if ($stmt === false) {
        throw new Exception('No se pudo preparar la eliminación de reserva: ' . $conn->error, $conn->errno);
    }

    $stmt->bind_param('i', $id_reserva);

    if (!$stmt->execute()) {
        throw new Exception('No se pudo eliminar la reserva: ' . $stmt->error, $stmt->errno);
    }

    if ($stmt->affected_rows === 0) {
        return ['success' => false, 'message' => 'No se encontró la reserva para eliminar'];
    }

    return ['success' => true, 'message' => 'Reserva eliminada correctamente'];
}
?>