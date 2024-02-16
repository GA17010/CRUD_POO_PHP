<?php
    namespace app\controllers;
    use app\models\mainModel;

    class loginController extends mainModel{

        // Controlador para iniciar sesión
        public function iniciarSesionControlador(){

            // Almacenar el usuario y la contraseña en variables
            $usuario = $this->cleanString($_POST['login_usuario']);
            $clave = $this->cleanString($_POST['login_clave']);

            // Verficando que los campos no estén vacíos
            if($usuario == "" || $clave == ""){
                echo '<script>
                        Swal.fire({
                            title: "Error",
                            text: "Los campos no pueden estar vacíos",
                            icon: "error",
                            confirmButtonText: "Aceptar"
                        });
                    </script>';
                exit();
            }else{
                // Verificando que el usuario cumpla con el formato solicitado
                if($this->checkData("[a-zA-Z0-9]{4,20}", $usuario)){
                    echo '<script>
                            Swal.fire({
                                title: "Error",
                                text: "El usuario no cumple con el formato solicitado",
                                icon: "error",
                                confirmButtonText: "Aceptar"
                            });
                        </script>';
                    exit();
                }else{
                    if($this->checkData("[a-zA-Z0-9$@.\-]{7,100}", $clave)){
                        echo '<script>
                                Swal.fire({
                                    title: "Error",
                                    text: "La contraseña no cumple con el formato solicitado",
                                    icon: "error",
                                    confirmButtonText: "Aceptar"
                                });
                            </script>';
                        exit();
                    }else{
                        # Verificar que el usuario exista en la base de datos
                        $check_usuario = $this->executeQuery("SELECT * FROM usuario WHERE usuario_usuario = '$usuario'");

                        if($check_usuario->rowCount() == 1){
                            // Si el usuario existe, se verifica que la contraseña sea correcta
                            $check_usuario = $check_usuario->fetch();
                            if($check_usuario['usuario_usuario'] == $usuario && password_verify($clave, $check_usuario['usuario_clave'])){
                                // Si la contraseña es correcta, se inicia la sesión
                                $_SESSION['id'] = $check_usuario['usuario_id'];
                                $_SESSION['nombre'] = $check_usuario['usuario_nombre'];
                                $_SESSION['apellido'] = $check_usuario['usuario_apellido'];
                                $_SESSION['usuario'] = $check_usuario['usuario_usuario'];
                                $_SESSION['foto'] = $check_usuario['usuario_foto'];

                                if(headers_sent()){
                                    echo '<script>
                                            window.location.href = "'.APP_URL.'dashboard/";
                                        </script>';
                                }else{
                                    header("Location: ".APP_URL."dashboard/");
                                }

                            }else{
                                // Si la contraseña es incorrecta, se muestra un mensaje de error
                                echo '<script>
                                        Swal.fire({
                                            title: "Error",
                                            text: "La contraseña es incorrecta",
                                            icon: "error",
                                            confirmButtonText: "Aceptar"
                                        });
                                    </script>';
                                exit();
                            }
                        }else{
                            // Si el usuario no existe, se muestra un mensaje de error
                            echo '<script>
                                    Swal.fire({
                                        title: "Error",
                                        text: "El usuario no existe",
                                        icon: "error",
                                        confirmButtonText: "Aceptar"
                                    });
                                </script>';
                            exit();
                        }
                    }
                }
            }
            
        }

        // Controlador para cerrar sesión
        public function cerrarSesionControlador(){
            // Se destruye la sesión
            session_destroy();

            // Se redirecciona al login
            if(headers_sent()){
                echo '<script>
                        window.location.href = "'.APP_URL.'login/";
                    </script>';
            }else{
                header("Location: ".APP_URL."login/");
            }
        }


    }