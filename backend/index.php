<?php
// 1. Configuración de la Base de Datos
$host = "localhost";
$usuario = "root";
$password = "";
$base_datos = "reserva_canchas";

$conexion = new mysqli($host, $usuario, $password, $base_datos);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// 2. Procesar el Formulario al hacer un envío (POST)
$mensaje = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $conexion->real_escape_string($_POST['nombre']);
    $cancha = $conexion->real_escape_string($_POST['cancha']);
    $fecha = $conexion->real_escape_string($_POST['fecha']);
    $hora = $conexion->real_escape_string($_POST['hora']);

    // Validar si la cancha ya está ocupada en ese horario
    $comprobar = "SELECT * FROM reservas WHERE cancha = '$cancha' AND fecha = '$fecha' AND hora = '$hora'";
    $resultado = $conexion->query($comprobar);

    if ($resultado->num_rows > 0) {
        $mensaje = "<div class='alerta error'>Lo sentimos, esta cancha ya está reservada para ese día y hora.</div>";
    } else {
        // Insertar la reserva
        $insertar = "INSERT INTO reservas (nombre_usuario, cancha, fecha, hora) VALUES ('$nombre', '$cancha', '$fecha', '$hora')";
        if ($conexion->query($insertar) === TRUE) {
            $mensaje = "<div class='alerta exito'>¡Reserva realizada con éxito!</div>";
        } else {
            $mensaje = "<div class='alerta error'>Error al guardar la reserva: " . $conexion->error . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva de Canchas</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; margin: 0; padding: 20px; }
        .contenedor { max-width: 500px; background: white; padding: 20px; margin: auto; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #333; }
        label { display: block; margin-top: 10px; font-weight: bold; }
        input, select { width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background-color: #28a745; color: white; border: none; border-radius: 4px; font-size: 16px; margin-top: 20px; cursor: pointer; }
        button:hover { background-color: #218838; }
        .alerta { padding: 10px; margin-bottom: 15px; border-radius: 4px; text-align: center; font-weight: bold; }
        .exito { background-color: #d4edda; color: #155724; }
        .error { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>

<div class="contenedor">
    <h2>Reservar Cancha</h2>
    
    <!-- Mostrar mensaje de éxito o error -->
    <?php echo $mensaje; ?>

    <form action="index.php" method="POST">
        <label for="nombre">Tu Nombre:</label>
        <input type="text" id="nombre" name="nombre" required placeholder="Ej. Juan Pérez">

        <label for="cancha">Selecciona la Cancha:</label>
        <select id="cancha" name="cancha" required>
            <option value="">-- Elige una opción --</option>
            <option value="Fútbol 5">Fútbol 5</option>
            <option value="Fútbol 7">Fútbol 7</option>
            <option value="Tenis">Tenis</option>
            <option value="Pádel">Pádel</option>
        </select>

        <label for="fecha">Fecha:</label>
        <input type="date" id="fecha" name="fecha" min="<?php echo date('Y-m-d'); ?>" required>

        <label for="hora">Hora:</label>
        <select id="hora" name="hora" required>
            <option value="">-- Elige un horario --</option>
            <option value="08:00">08:00 AM</option>
            <option value="09:00">09:00 AM</option>
            <option value="17:00">05:00 PM</option>
            <option value="18:00">06:00 PM</option>
            <option value="19:00">07:00 PM</option>
            <option value="20:00">08:00 PM</option>
            <option value="21:00">09:00 PM</option>
        </select>

        <button type="submit">Confirmar Reserva</button>
    </form>
</div>

</body>
</html>
<?php $conexion->close(); ?>
