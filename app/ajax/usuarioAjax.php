<?php
    require_once("../../config/app.php");
    require_once("../views/inc/session_start.php");
    require_once("../../autoload.php");

    use app\controllers\userController;

    if(isset($_POST['modulo_usuario'])){
        // Instancia del controlador de usuarios
        $userController = new userController();
        
        // registrar usuario
        if($_POST['modulo_usuario'] == "registrar"){
            echo $userController->registrarUsuarioControlador();
        }

        // eliminar usuario
        if($_POST['modulo_usuario'] == "eliminar"){
            echo $userController->eliminarUsuarioControlador();
        }

        // actualizar usuario
        if($_POST['modulo_usuario'] == "actualizar"){
            echo $userController->actualizarUsuarioControlador();
        }

        // actualizar foto
        if($_POST['modulo_usuario'] == "actualizarFoto"){
            echo $userController->actualizarFotoControlador();
        }

        // eliminar foto
        if($_POST['modulo_usuario'] == "eliminarFoto"){
            echo $userController->eliminarFotoControlador();
        }

    }else{
        session_destroy();
        header("Location: ".APP_URL."login/");
    }