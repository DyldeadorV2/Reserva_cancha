function reserva() {
    const cancha = document.getElementById("cancha-select").value;
    const fecha = document.getElementById("fecha-input").value;
    const hora = document.getElementById("hora-input").value;
    alert(`Reserva confirmada para ${cancha} el ${fecha} a las ${hora}`);
}
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
    const teléfono = document.getElementById("phone").value;
    const nombre = document.getElementById("name").value;

    if (email === "" || password === "" || teléfono === "" || nombre === "") {
        alert("Por favor, complete todos los campos.");
        return;
    }
    const url = `http://localhost/reserva_cancha/backend/index.php?caso=register&email=${email}&contraseña=${password}&teléfono=${telefono}&nombre=${nombre}`;

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

