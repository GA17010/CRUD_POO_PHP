<?php
    namespace app\controllers;
    use app\models\viewsModel;

    class viewsController extends viewsModel{

        public function getViewsController($views){
            // Comprueba si la variable $views no está vacía
            if($views != ""){
                // Llama al método getViewsModel de la clase viewsModel y asigna el resultado a la variable $answer
                $answer = $this->getViewsModel($views);
            }else{
                // Asigna el valor "login" a la variable $answer
                $answer = "login";
            }
            // Retorna el valor de la variable $answer
            return $answer;
        }
    }