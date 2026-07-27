<?php
// API REST simple para reservas de canchas (sin login)
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// Configuración de la base de datos (intento con nombres posibles)
$dbHosts = [
    ['host'=>'localhost','user'=>'root','pass'=>'','db'=>'sistema_canchas'],
    ['host'=>'localhost','user'=>'root','pass'=>'','db'=>'reserva_canchas']
];
$conn = null;
foreach ($dbHosts as $cfg) {
    $c = new mysqli($cfg['host'],$cfg['user'],$cfg['pass'],$cfg['db']);
    if (!$c->connect_error) { $conn = $c; break; }
}
if (!$conn) {
    http_response_code(500);
    echo json_encode(['error'=>'No se pudo conectar a la base de datos.']);
    exit;
}

function json_response($data, $code = 200) {
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function get_json_input() {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

$method = $_SERVER['REQUEST_METHOD'];
$resource = $_GET['resource'] ?? null;

// Rutas simples
if ($method === 'GET' && $resource === 'canchas') {
    $res = $conn->query("SELECT id_cancha, nombre_cancha, tipo_superficie, precio_hora, estado FROM canchas");
    $rows = [];
    while ($r = $res->fetch_assoc()) $rows[] = $r;
    json_response(['canchas'=>$rows]);
}

if ($method === 'GET' && $resource === 'reservas') {
    $date = $conn->real_escape_string($_GET['date'] ?? '');
    $id_cancha = isset($_GET['id_cancha']) ? (int)$_GET['id_cancha'] : null;

    $params = [];
    $sql = "SELECT r.id_reserva, r.id_cancha, c.nombre_cancha, r.fecha, r.hora_inicio, r.estado_pago, u.nombre as nombre_usuario, u.telefono, u.correo FROM reservas r JOIN canchas c ON r.id_cancha = c.id_cancha JOIN usuarios u ON r.id_usuario = u.id_usuario";
    $where = [];
    if ($date) $where[] = "r.fecha = '". $date ."'";
    if ($id_cancha) $where[] = "r.id_cancha = " . $id_cancha;
    if (count($where)) $sql .= ' WHERE ' . implode(' AND ', $where);
    $sql .= ' ORDER BY r.fecha, r.hora_inicio';

    $res = $conn->query($sql);
    $rows = [];
    while ($r = $res->fetch_assoc()) $rows[] = $r;
    json_response(['reservas'=>$rows]);
}

if ($method === 'POST' && $resource === 'reservas') {
    $data = get_json_input();
    $nombre = trim($data['nombre'] ?? '');
    $telefono = trim($data['telefono'] ?? '');
    $correo = trim($data['correo'] ?? '');
    $id_cancha = isset($data['id_cancha']) ? (int)$data['id_cancha'] : 0;
    $fecha = $data['fecha'] ?? '';
    $hora = $data['hora'] ?? '';

    if (!$nombre || !$id_cancha || !$fecha || !$hora) {
        json_response(['error'=>'Faltan campos requeridos: nombre, id_cancha, fecha, hora'], 400);
    }

    // 1) Buscar usuario por correo si existe
    $conn->begin_transaction();
    try {
        $id_usuario = null;
        if ($correo) {
            $stmt = $conn->prepare('SELECT id_usuario FROM usuarios WHERE correo = ? LIMIT 1');
            $stmt->bind_param('s', $correo);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($row = $res->fetch_assoc()) $id_usuario = (int)$row['id_usuario'];
            $stmt->close();
        }

        if (!$id_usuario) {
            $stmt = $conn->prepare('INSERT INTO usuarios (nombre, telefono, correo) VALUES (?, ?, ?)');
            $stmt->bind_param('sss', $nombre, $telefono, $correo);
            $stmt->execute();
            $id_usuario = $stmt->insert_id;
            $stmt->close();
        }

        // 2) Intentar insertar reserva (la restricción UNIQUE en la BD evitará duplicados)
        $stmt = $conn->prepare('INSERT INTO reservas (id_usuario, id_cancha, fecha, hora_inicio) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('iiss', $id_usuario, $id_cancha, $fecha, $hora);
        $ok = $stmt->execute();
        if (!$ok) {
            // Duplicate slot?
            if ($conn->errno === 1062) {
                $conn->rollback();
                json_response(['error'=>'La cancha ya está reservada en ese día y hora.'], 409);
            }
            $conn->rollback();
            json_response(['error'=>'Error al crear la reserva: '.$conn->error], 500);
        }
        $new_id = $stmt->insert_id;
        $stmt->close();
        $conn->commit();
        json_response(['ok'=>true,'id_reserva'=>$new_id], 201);
    } catch (Exception $e) {
        $conn->rollback();
        json_response(['error'=>'Error interno: '.$e->getMessage()], 500);
    }
}

if ($method === 'DELETE' && $resource === 'reservas') {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    if (!$id) json_response(['error'=>'Se requiere id de reserva'], 400);
    $stmt = $conn->prepare('DELETE FROM reservas WHERE id_reserva = ?');
    $stmt->bind_param('i', $id);
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) json_response(['ok'=>true]);
        else json_response(['error'=>'Reserva no encontrada'], 404);
    } else {
        json_response(['error'=>'Error al eliminar reserva: '.$conn->error], 500);
    }
}

// Ruta no encontrada
json_response(['error'=>'Recurso no encontrado o método no permitido'], 404);

?>
