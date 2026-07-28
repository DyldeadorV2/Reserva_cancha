<?php
require_once 'api.php';

$caso = $_GET['caso'] ?? '';

try {
    switch ($caso) {
        case 'canchas':
            $canchas = obtenerCanchas();
            echo json_encode($canchas);
            break;

        case 'reservas':
            $reservas = reservas();
            echo json_encode($reservas);
            break;

        case 'reservar':
            $id_usuario = $_GET['id_usuario'] ?? null;
            $id_cancha = $_GET['id_cancha'] ?? null;
            $fecha = $_GET['fecha'] ?? null;
            $hora_inicio = $_GET['hora_inicio'] ?? null;
            $estado_pago = $_GET['estado_pago'] ?? null;

            if ($id_usuario === null || $id_cancha === null || $fecha === null || $hora_inicio === null || $estado_pago === null) {
                throw new Exception('Faltan parámetros para reservar: id_usuario, id_cancha, fecha, hora_inicio y estado_pago son obligatorios.');
            }

            $resultado = reservar($id_usuario, $id_cancha, $fecha, $hora_inicio, $estado_pago);
            echo json_encode($resultado);
            break;

        case 'registrarUsuario':
            $nombre = $_GET['nombre'] ?? null;
            $email = $_GET['email'] ?? null;
            $telefono = $_GET['teléfono'] ?? null;
            $password = $_GET['contraseña'] ?? null;

            if ($nombre === null || $email === null || $telefono === null || $password === null) {
                throw new Exception('Faltan parámetros para registrar usuario: nombre, email, teléfono y contraseña son obligatorios.');
            }

            $resultado = registrarUsuario($nombre, $telefono, $email, $password);
            echo json_encode($resultado);
            break;

        case 'login':
            $email = $_GET['email'] ?? null;
            $password = $_GET['contraseña'] ?? null;

            if ($email === null || $password === null) {
                throw new Exception('Faltan parámetros para iniciar sesión: email y contraseña son obligatorios.');
            }

            $resultado = login($email, $password);
            echo json_encode($resultado);
            break;

        case 'eliminarReserva':
            $id_reserva = $_GET['id_reserva'] ?? null;

            if ($id_reserva === null) {
                throw new Exception('Falta el parámetro id_reserva para eliminar la reserva.');
            }

            $resultado = eliminarReserva($id_reserva);
            echo json_encode($resultado);
            break;

        default:
            echo json_encode(['success' => false, 'error' => 'Caso no válido']);
            break;
    }
} catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'code' => $e->getCode(),
        'file' => basename($e->getFile()),
        'line' => $e->getLine()
    ]);
}
?>