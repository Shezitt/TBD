document.querySelector("form").addEventListener("submit", function(event) {
    const usuario = document.getElementById("usuario").value;

    // Validación simple
    if (!usuario) {
        alert("Por favor ingrese un nombre de usuario.");
        event.preventDefault();  // Evita que el formulario se envíe si el campo está vacío
    }
});

