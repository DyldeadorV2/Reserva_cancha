function reserva() {
    const cancha = document.getElementById("cancha-select").value;
    const fecha = document.getElementById("fecha-input").value;
    const hora = document.getElementById("hora-input").value;
    alert(`Reserva confirmada para ${cancha} el ${fecha} a las ${hora}`);
}
function login() {
    const email = document.getElementById("email-input").value;
    const password = document.getElementById("password-input").value;
    if (email === "" || password === "") {
        alert("Por favor, complete todos los campos.");
        return;
    }
    const url = `http://localhost/reserva_cancha/index.php?caso=login&email=${email}&password=${password}`;
 
    
    
}