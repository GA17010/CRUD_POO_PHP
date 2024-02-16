<?php
    // Se incluyen los archivos necesarios
    require_once("./config/app.php");
    require_once("autoload.php");
    require_once("./app/views/inc/session_start.php");

    // Se verifica si se ha proporcionado un parámetro 'views' en la URL
    if(isset($_GET['views'])){
        // Se divide la URL en partes utilizando '/' como separador
        $url = explode("/", $_GET['views']);
    }else{
        // Si no se proporciona un parámetro 'views', se establece un valor predeterminado
        $url = ["login"];
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <?php require_once("./app/views/inc/head.php"); ?>
</head>
<body>
    <?php 
        // Se importa el controlador de vistas
        use app\controllers\viewsController;
		use app\controllers\loginController;

        $insLogin = new loginController();

        // Se crea una instancia del controlador de vistas
        $viewsController = new viewsController();

        // Se obtiene la vista correspondiente al primer elemento de la URL
        $view = $viewsController->getViewsController($url[0]);

        // Si la vista es 'login' o '404', se incluye el archivo de vista correspondiente
        if($view == "login" || $view == "404"){
            require_once("./app/views/content/".$view."-view.php");
        }else{
            # Cerrar Sesión
            if(!isset($_SESSION['id']) || !isset($_SESSION['usuario']) || !isset($_SESSION['nombre']) || !isset($_SESSION['apellido']) || $_SESSION['id'] == "" || $_SESSION['usuario'] == "" || $_SESSION['nombre'] == "" || $_SESSION['apellido'] == ""){
                $insLogin->cerrarSesionControlador();
                exit();
            }

            // Si la vista no es 'login' ni '404', se incluye la barra de navegación y la vista correspondiente
            require_once("./app/views/inc/navbar.php");
            require_once $view;
        }

        // Se incluyen los scripts necesarios
        require_once("./app/views/inc/script.php");
    ?>
</body>
</html>
