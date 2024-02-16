<?php
    namespace app\models;

    class viewsModel{

        protected function getViewsModel($views){
            $whitelist = ["dashboard", "userNew", "userList", "userSearch", "userUpdate", "userPhoto", "logOut"];
            if(in_array($views, $whitelist)){
                // Si la vista está en la lista blanca
                if(is_file("./app/views/content/".$views."-view.php")){
                    // Si el archivo de la vista existe
                    $content = "./app/views/content/".$views."-view.php";
                }else{
                    // Si el archivo de la vista no existe
                    $content = "404";
                }
            }elseif($views == "login" || $views == "index"){
                // Si la vista es "login" o "index"
                $content = "login";
            }else{
                // Si la vista no está en la lista blanca ni es "login" ni "index"
                $content = "404";
            }
            return $content;
        }
    }