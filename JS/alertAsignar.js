// Función para mostrar alertas de error
function Error(md) {
    Swal.fire({
        title: "¡Error!",
        text: md,
        icon: 'error',
        confirmButtonText: 'Aceptar'
    });
}
function Exito(t, md) {
    Swal.fire({
        title: t,
        text: md,
        icon: 'success',
        confirmButtonText: 'Aceptar'
    });
}