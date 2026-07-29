function login() {
    try {
        const email = document.getElementById("email-input").value;
        const password = document.getElementById("password-input").value;

        if (email === "" || password === "") {
            alert("Por favor, complete todos los campos.");
            return;
        }
        const url = `http://localhost/reserva_cancha/backend/index.php?caso=login&email=${email}&contraseña=${password}`;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    localStorage.setItem("token", JSON.stringify('logguedUser'));
                    if (data.user) {
                        localStorage.setItem("user", JSON.stringify(data.user));
                    }
                    alert("Inicio de sesión exitoso");
                    window.location.href = "index.html";
                }
            })

    } catch (error) {
        console.error("Error al iniciar sesión:", error);
        alert("Ocurrió un error al intentar iniciar sesión.");
    }
}


function register() {
    try {
        const email = document.getElementById("email").value;
        const password = document.getElementById("password").value;
        const telefono = document.getElementById("phone").value;
        const nombre = document.getElementById("name").value;

        if (email === "" || password === "" || telefono === "" || nombre === "") {
            alert("Por favor, complete todos los campos.");
            return;
        }
        const url = `http://localhost/reserva_cancha/backend/index.php?caso=registrarUsuario&email=${email}&contraseña=${password}&teléfono=${telefono}&nombre=${nombre}`;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Registro exitoso");
                    window.location.href = "login.html";
                }
            })

    } catch (error) {
        console.error("Error al registrar:", error);
        alert("Ocurrió un error al intentar registrarse.");
    }
}

function reservar() {
    const cancha = document.getElementById("cancha-select").value;
    const fecha = document.getElementById("fecha-input").value;
    const hora = document.getElementById("hora-input").value;
    const id_usuario = JSON.parse(localStorage.getItem("user")).id_usuario;

    if (!cancha || !fecha || !hora) {
        alert("Por favor, complete todos los campos.");
        return;
    }
alert (`Reserva confirmada para ${cancha} el ${fecha} a las ${hora} por el usuario con ID: ${id_usuario}`);
    alert(`Reserva confirmada para ${cancha} el ${fecha} a las ${hora}`);

const url = `http://localhost/reserva_cancha/backend/index.php?caso=reservar&id_usuario=${id_usuario}&id_cancha=${cancha}&fecha=${fecha}&hora_inicio=${hora}&estado_pago=pagado`;

fetch(url)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("Reserva confirmada exitosamente.");
        } else {
            alert("Error al confirmar la reserva.");
        }
    });
}