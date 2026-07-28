<?php
function conect()
{
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    try {
        $conn = new mysqli('localhost', 'root', '', 'reservas_cancha');
        $conn->set_charset('utf8mb4');
        return $conn;
    } catch (mysqli_sql_exception $e) {
        throw new Exception('No se pudo conectar a la base de datos: ' . $e->getMessage(), (int) $e->getCode());
    }
}
?>