document.addEventListener('DOMContentLoaded', () => {

    // Hacer visible la contraseña de un input
    const hiddenPassword = document.getElementById('mostrar_contrasena');
    const inputPassword = document.getElementById('password');
    if (hiddenPassword && inputPassword){
        hiddenPassword.addEventListener('change', () => {
            if (hiddenPassword.checked) {
                inputPassword.type = 'text';
            } else {
                inputPassword.type = 'password';
            }
        });
    }

    // Aplicar el modo oscuro si el usuario lo prefiere
    function applyDarkMode() {
        const isDarkMode = getDarkModePreference();
        document.body.classList.toggle('dark-mode', isDarkMode);
        updateNavbar(isDarkMode);
        updateIconDark(isDarkMode);
        updateModeToggle(isDarkMode);
    }

    // Cambiar el modo según el usuario lo prefiera
    var modeToggle = document.getElementById('modeToggle');
    if (modeToggle) {
        modeToggle.addEventListener('change', () => {
            // Cambiar el color del body según el modo
            const body = document.body;
            body.classList.toggle('dark-mode');
            const isDarkMode = body.classList.contains('dark-mode');
            setDarkModePreference(isDarkMode);
            updateNavbar(isDarkMode);
            updateIconDark(isDarkMode);
            updateModeToggle(isDarkMode);
        });
    }

    // Actualizar el icono del modo según el modo actual
    function updateIconDark(isDarkMode) {
        const modeIcon = document.getElementById('iconDark');
        if(modeIcon){
            modeIcon.classList.toggle('fa-moon', isDarkMode);
            modeIcon.classList.toggle('fa-sun', !isDarkMode);
        }
    }

    // Almacenar la preferencia del usuario en localStorage
    function setDarkModePreference(isDarkMode) {
        localStorage.setItem('dark-mode', isDarkMode);
    }

    // Obtener la preferencia del usuario del localStorage
    function getDarkModePreference() {
        return JSON.parse(localStorage.getItem('dark-mode'));
    }

    // Actualizar el navbar según el modo actual
    function updateNavbar(isDarkMode) {
        const navbar = document.getElementById('navbarDark');
        if (navbar) {
            navbar.classList.toggle('is-dark', isDarkMode);
            navbar.classList.toggle('is-light', !isDarkMode);
        }
    }

    // Actualizar el modeToggle segundo el modo actual
    function updateModeToggle(isDarkMode) {
        const modeToggle = document.getElementById('modeToggle');
        if (modeToggle) {
            modeToggle.checked = isDarkMode;
        }
    }

    // Aplicar el modo oscuro al cargar la página
    applyDarkMode();
    
    

});