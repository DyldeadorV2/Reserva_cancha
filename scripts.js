function reserva() {
    const cancha = document.getElementById("cancha").value;
    const fecha = document.getElementById("fecha").value;
    const hora = document.getElementById("hora").value;
    alert(`Reserva confirmada para ${cancha} el ${fecha} a las ${hora}`);
} 