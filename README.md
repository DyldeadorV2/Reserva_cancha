# Sistema de Reservas de Canchas

## Descripción

Este proyecto es una aplicación web desarrollada para gestionar reservas de canchas deportivas. La plataforma permite a los usuarios registrarse, iniciar sesión y reservar una cancha de fútbol, pádel o tenis según el día y horario disponible.

El objetivo principal del proyecto es centralizar el proceso de reserva en una misma plataforma, permitiendo consultar la disponibilidad, seleccionar una fecha y horario, realizar el pago y registrar la reserva en la base de datos.

## Funcionalidades

* Registro de nuevos usuarios.
* Inicio y cierre de sesión.
* Gestión de usuarios mediante sesiones.
* Selección del tipo de deporte.
* Selección de cancha.
* Selección de fecha y horario.
* Verificación de disponibilidad.
* Realización del pago.
* Registro de la reserva en la base de datos.
* Asociación de cada reserva con el usuario correspondiente.

## Funcionamiento

El usuario debe crear una cuenta para poder utilizar el sistema de reservas. Una vez registrado, puede iniciar sesión y acceder a las opciones disponibles.

El proceso de reserva consiste en seleccionar el deporte, elegir una cancha, seleccionar el día y horario disponible y completar el proceso de pago.

Una vez finalizado el proceso, la reserva se almacena en MySQL junto con la información necesaria para identificar al usuario, la cancha, la fecha y el horario seleccionado.

De esta manera, la aplicación mantiene un registro de las reservas realizadas y permite controlar la disponibilidad de las canchas.

## Tecnologías utilizadas

### Frontend

* HTML5
* CSS3
* JavaScript

HTML y CSS se utilizan para la estructura y el diseño de la interfaz, mientras que JavaScript se utiliza para agregar funcionalidades e interacción en el lado del cliente.

### Backend

* PHP

PHP se encarga de la lógica de la aplicación, el procesamiento de formularios, la gestión de sesiones, la autenticación de usuarios y la comunicación con la base de datos.

### Base de datos

* MySQL

MySQL se utiliza para almacenar la información de los usuarios, canchas y reservas realizadas.

## Flujo de una reserva

```text
Registro de usuario
        ↓
Inicio de sesión
        ↓
Selección del deporte
        ↓
Selección de la cancha
        ↓
Selección del día
        ↓
Selección del horario
        ↓
Realización del pago
        ↓
Confirmación de la reserva
        ↓
Registro en MySQL
```

## Estructura general

La estructura del proyecto está organizada separando los archivos correspondientes al frontend, backend, estilos, scripts y conexión con la base de datos.

```text
/
├── css/
├── js/
├── img/
├── php/
├── index.php
├── login.php
├── registro.php
└── README.md
```

La estructura puede variar dependiendo de la organización utilizada en cada parte del proyecto.

## Base de datos

La aplicación utiliza MySQL para guardar y consultar la información necesaria para el funcionamiento del sistema.

Entre los datos gestionados se encuentran:

* Usuarios registrados.
* Datos de las canchas.
* Fechas y horarios.
* Reservas.
* Información relacionada con los pagos.

La base de datos permite relacionar las reservas con los usuarios y las canchas correspondientes.

## Instalación

Para ejecutar el proyecto de manera local se necesita un entorno capaz de ejecutar PHP y MySQL, como XAMPP.

### 1. Clonar el repositorio

```bash
git clone URL_DEL_REPOSITORIO
```

### 2. Ubicar el proyecto

Si se utiliza XAMPP, colocar la carpeta del proyecto dentro del directorio:

```text
htdocs/
```

### 3. Crear la base de datos

Crear una nueva base de datos desde phpMyAdmin e importar el archivo `.sql` del proyecto, en caso de estar incluido en el repositorio.

### 4. Configurar la conexión

Configurar los datos de conexión a MySQL en el archivo correspondiente del proyecto.

Ejemplo:

```php
$host = "localhost";
$user = "root";
$password = "";
$database = "nombre_base_de_datos";
```

### 5. Ejecutar

Iniciar Apache y MySQL desde XAMPP y acceder al proyecto desde el navegador:

```text
http://localhost/nombre-del-proyecto/
```

## Objetivo del proyecto

Este proyecto fue desarrollado como una aplicación práctica para trabajar con diferentes tecnologías de desarrollo web y, principalmente, para implementar un sistema completo que combine frontend, backend y base de datos.

Además de permitir realizar reservas, el proyecto busca aplicar conceptos como autenticación de usuarios, manejo de sesiones, consultas a bases de datos, validación de información y comunicación entre el cliente y el servidor.

## Posibles mejoras

Algunas funcionalidades que podrían incorporarse en futuras versiones son:

* Panel de administración.
* Gestión de canchas y horarios desde el sistema.
* Cancelación y modificación de reservas.
* Historial de reservas de cada usuario.
* Sistema de notificaciones.
* Envío de confirmaciones por correo electrónico.
* Integración con una plataforma de pagos real.
* Mejoras en el diseño responsive.
* Calendario para visualizar la disponibilidad de las canchas.

## Autor

Proyecto desarrollado como aplicación web para la gestión y reserva de canchas deportivas.

**Tecnologías:** PHP, MySQL, JavaScript, HTML y CSS.
