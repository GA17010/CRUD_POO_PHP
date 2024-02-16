<?php
    # Autoload - Carga automatica de clases
    spl_autoload_register(function($clase){
        $archivo = __DIR__."/".$clase.".php";
        $archivo = str_replace("\\", "/", $archivo);
        if(is_file($archivo)){
            require_once($archivo);
        }else{
            echo $archivo;
            echo "El archivo no existe";
            exit();
        }
    });




    /*
    # Autoload - Carga automatica de clases
    spl_autoload_register(
        function($clase){
        $nombre_archivo = str_replace("\\", "/", $clase).".php";
        if(is_file($nombre_archivo)){
            require_once($nombre_archivo);
        }else{
            echo "El archivo no existe";
            exit();
        }
    });
    */
?>