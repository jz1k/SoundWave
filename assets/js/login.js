// Controla las frases dinámicas
document.addEventListener("DOMContentLoaded", function () {
    const dynamicPhrases = [
        "Accede a tus archivos de audio en cualquier momento",
        "Tu privacidad es nuestra prioridad",
        "Almacena tus grabaciones con total confianza",
        "Tu confidencialidad está protegida con nosotros",
        "Tu colección de archivos, siempre contigo",
        "Guarda tus reuniones importantes para referencia futura",
        "Un lugar seguro para tus grabaciones personales"
    ];

    new Typed('#dynamic-text', {
        strings: dynamicPhrases,
        typeSpeed: 50,
        backSpeed: 25,
        backDelay: 2000,
        startDelay: 1500,
        loop: true
    });

    const registerLink = document.getElementById('show-register-form');
    const registerForm = document.getElementById('register-form');

    registerLink.addEventListener('click', function (event) {
        event.preventDefault();
        registerForm.classList.toggle('active');

    });


});

// Función para obtener el saludo según la hora del día
function getGreeting() {
    const now = new Date();
    const hour = now.getHours();

    if (hour >= 5 && hour < 12) {
        return "¡Buenos Días!";
    } else if (hour >= 12 && hour < 20) {
        return "¡Buenas Tardes!";
    } else {
        return "¡Buenas Noches!";
    }
}

// Función para mostrar el saludo
function displayGreetingAndText() {
    const greetingElement = document.getElementById("greeting");
    const greeting = getGreeting();
    greetingElement.textContent = greeting;
}

// Llamar a la función para mostrar el saludo y las frases dinámicas
document.addEventListener('DOMContentLoaded', function () {
    displayGreetingAndText();

    const form = document.getElementById('register-form');
    const passwordInput = document.getElementById('contrasena-reg');
    const confirmPasswordInput = document.getElementById('contrasena-conf');
    const errorMessagesDiv = document.getElementById('error-messages');

    form.addEventListener('submit', function(event) {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        const errorMessages = [];

        // Limpiar mensajes de error previos
        errorMessagesDiv.innerHTML = '';

        // Validaciones de la contraseña
        if (password.length < 8) {
            errorMessages.push("- La contraseña debe tener al menos 8 caracteres.");
        }
        
        if (!/[a-z]/.test(password)) {
            errorMessages.push("- La contraseña debe contener al menos una letra minúscula.");
        }
        
        if (!/[A-Z]/.test(password)) {
            errorMessages.push("- La contraseña debe contener al menos una letra mayúscula.");
        }
        
        if (!/\d/.test(password)) {
            errorMessages.push("- La contraseña debe contener al menos un dígito.");
        }
        
        if (!/[@$!%*?&,\.]/.test(password)) {
            errorMessages.push("- La contraseña debe contener al menos uno de los siguientes caracteres especiales: @$!%*?&,.");
        }
        
        if (password !== confirmPassword) {
            errorMessages.push("- Las contraseñas no coinciden.");
        }

        // Mostrar mensajes de error si hay
        if (errorMessages.length > 0) {
            event.preventDefault();
            errorMessagesDiv.innerHTML = errorMessages.join('<br>');
        }
    });
});
