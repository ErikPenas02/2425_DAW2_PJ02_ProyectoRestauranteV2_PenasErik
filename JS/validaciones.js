// Esperamos a que el DOM esté cargado
document.addEventListener("DOMContentLoaded", function () {
    
    // Llamamos al formulario mediante su ID
    const form = document.getElementById("login");

    // Agregamos un evento de escucha para que se ejecute cuando se envia el formulario
    form.addEventListener("submit", function (event) {
        
        let hasErrors = false;
        const username = document.getElementById("username").value.trim();
        const password = document.getElementById("pwd").value.trim();
        const errorUsername = document.getElementById("errorUsername");
        const errorContraseña = document.getElementById("errorContraseña");

        // Limpiamos errores previos
        errorUsername.textContent = "";
        errorContraseña.textContent = "";

        let errors = {
            username: [],
            password: []
        };

        // VALIDACIÓN USERNAME
        // Campo vacío
        if (username === "" || username === null) {
            errors.username.push("- El nombre de usuario no puede estar vacío.");
            hasErrors = true;
        }

        if (username.length < 3) {
            errors.username.push("- El nombre de usuario debe contener mínimo 3 caracteres.");
            hasErrors = true;
        }

        // // Sin números
        // const nums = /[0-9]/;
        // if (nums.test(username)) {
        //     errors.username.push("- El nombre de usuario no puede contener números.");
        //     hasErrors = true;
        // }

        // VALIDACIÓN CONTRASEÑA
        // Campo vacío
        if (password === "" || password === null) {
            errors.password.push("- La contraseña no puede estar vacía.");
            hasErrors = true;
        }

        // // Más de 8 caracteres
        // if (password.length < 8) {
        //     errors.password.push("- La contraseña debe tener más de 8 caracteres.");
        //     hasErrors = true;
        // }

        // // // Contener 1 numero
        // const num = /[0-9]/;
        // if (!num.test(password)) {
        //     errors.password.push("- La contraseña debe contener al menos un número.");
        //     hasErrors = true;
        // }

        // // // Contener 1 mayúscula
        // const mayus = /[A-Z]/;
        // if (!mayus.test(password)) {
        //     errors.password.push("- La contraseña debe contener al menos una letra mayúscula.");
        //     hasErrors = true;
        // }

        // // // Contener 1 minúscula
        // const minus = /[a-z]/;
        // if (!minus.test(password)) {
        //     errors.password.push("- La contraseña debe contener al menos una letra minúscula.");
        //     hasErrors = true;
        // }

        if (hasErrors) {
            // Mostramos todos los errores acumulados
            errorUsername.innerHTML = errors.username.join('<br>');
            errorContraseña.innerHTML  = errors.password.join('<br>');

            // Prevenimos el envío del formulario
            event.preventDefault();
        } 
    });
});

// Función para mostrar alertas de éxito
function Exito(t, md) {
    Swal.fire({
        title: t,
        text: md,
        icon: 'success',
        confirmButtonText: 'Aceptar'
    });
}


document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("form-asignar");

    // Campos del formulario
    const assignedToInput = document.getElementById("assigned_to");
    const fechaInicioInput = document.getElementById("fecha_inicio");
    const fechaFinInput = document.getElementById("fecha_fin");

    // Campos para mostrar los errores
    const errorAssignedTo = document.getElementById("errorAssignedTo");
    const errorFechaInicio = document.getElementById("errorFechaInicio");
    const errorFechaFin = document.getElementById("errorFechaFin");

    // Función de validación del campo "Asignar a"
    function validarAssignedTo() {
        const assignedTo = assignedToInput.value.trim();
        errorAssignedTo.textContent = "";
        assignedToInput.style.borderColor = "";

        const nombreRegex = /^[a-zA-ZÁÉÍÓÚáéíóúÑñ\s]+$/; // Solo letras y espacios
        if (assignedTo === "") {
            errorAssignedTo.textContent = "El campo 'Asignar a' no puede estar vacío.";
            assignedToInput.style.borderColor = "red";
            return false;
        } else if (!nombreRegex.test(assignedTo)) {
            errorAssignedTo.textContent = "El campo 'Asignar a' solo puede contener letras y espacios.";
            assignedToInput.style.borderColor = "red";
            return false;
        }
        return true;
    }

    // Función de validación de "Fecha de inicio"
    function validarFechaInicio() {
        const fechaInicio = fechaInicioInput.value;
        errorFechaInicio.textContent = "";
        fechaInicioInput.style.borderColor = "";

        const ahora = new Date();
        ahora.setHours(ahora.getHours() + 1);
        const currentDateTime = ahora.toISOString().slice(0, 16);
        
        if (!fechaInicio) {
            errorFechaInicio.textContent = "La fecha de inicio no puede estar vacía.";
            fechaInicioInput.style.borderColor = "red";
            return false;
        }else if (fechaInicio < currentDateTime) {
            errorFechaInicio.textContent = "La fecha de inicio no puede ser menor que ahora";
            fechaInicioInput.style.borderColor = "red";
            return false;
        }

        return true;
    }

    // Función de validación de "Fecha de fin"
    function validarFechaFin() {
        const fechaInicio = fechaInicioInput.value;
        const fechaFin = fechaFinInput.value;
        errorFechaFin.textContent = "";
        fechaFinInput.style.borderColor = "";

        if (!fechaFin) {
            errorFechaFin.textContent = "La fecha de fin no puede estar vacía.";
            fechaFinInput.style.borderColor = "red";
            return false;
        } else if (fechaInicio && new Date(fechaInicio) >= new Date(fechaFin)) {
            errorFechaFin.textContent = "La fecha de inicio debe ser anterior a la fecha de fin.";
            fechaFinInput.style.borderColor = "red";
            return false;
        } else if (fechaInicio && fechaFin) {
            const diferencia = (new Date(fechaFin) - new Date(fechaInicio)) / (1000 * 60); // Diferencia en minutos
            if (diferencia < 30) {
                errorFechaFin.textContent = "La reserva debe durar al menos 30 minutos.";
                fechaFinInput.style.borderColor = "red";
                return false;
            }
        }
        return true;
    }

    // Eventos para validación en tiempo real
    assignedToInput.addEventListener("blur", validarAssignedTo);
    assignedToInput.addEventListener("keyup", validarAssignedTo);

    fechaInicioInput.addEventListener("blur", validarFechaInicio);
    fechaInicioInput.addEventListener("keyup", validarFechaInicio);

    fechaFinInput.addEventListener("blur", validarFechaFin);
    fechaFinInput.addEventListener("keyup", validarFechaFin);

    // Validación al enviar el formulario
    form.addEventListener("submit", function (event) {
        const isAssignedToValid = validarAssignedTo();
        const isFechaInicioValid = validarFechaInicio();
        const isFechaFinValid = validarFechaFin();

        if (!isAssignedToValid || !isFechaInicioValid || !isFechaFinValid) {
            event.preventDefault(); // Prevenir el envío si hay errores
        }
    });
});
