<?php
    namespace app\controllers;
    use app\models\mainModel;

    class userController extends mainModel{
        // Controlador para registrar un usuario
        public function registrarUsuarioControlador(){

            // Almacenando Datos
            $nombre = mainModel::cleanString($_POST['usuario_nombre']);
            $apellido = mainModel::cleanString($_POST['usuario_apellido']);
            $usuario = mainModel::cleanString($_POST['usuario_usuario']);
            $email = mainModel::cleanString($_POST['usuario_email']);
            $clave1 = mainModel::cleanString($_POST['usuario_clave_1']);
            $clave2 = mainModel::cleanString($_POST['usuario_clave_2']);

            # Validar campos obligatorios
            if($nombre == "" || $apellido == "" || $usuario == "" || $clave1 == "" || $clave2 == ""){
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "No has llenado todos los campos obligatorios",
                    "icono" => "error"
                ];
                return json_encode($alerta);
            }

            # Validar integridad de los datos
            // Nombre
            if($this->checkData("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}", $nombre)){
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "El campo NOMBRE no coincide con el formato solicitado",
                    "icono" => "error"
                ];
                return json_encode($alerta);
            }
            // Apellido
            if($this->checkData("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}", $apellido)){
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "El campo APELLIDO no coincide con el formato solicitado",
                    "icono" => "error"
                ];
                return json_encode($alerta);
            }
            // Usuario
            if($this->checkData("[a-zA-Z0-9]{4,20}", $usuario)){
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "El campo USUARIO no coincide con el formato solicitado",
                    "icono" => "error"
                ];
                return json_encode($alerta);
            }
            // Email
            if($email != ""){
                if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                    // Validar que el email no exista en la BD
                    $checkEmail = $this->executeQuery("SELECT usuario_email FROM usuario WHERE usuario_email = '$email'");
                    if($checkEmail->rowCount() > 0){
                        $alerta = [
                            "tipo" => "simple",
                            "titulo" => "Ocurrió un error inesperado",
                            "texto" => "El EMAIL ingresado ya existe en el sistema",
                            "icono" => "error"
                        ];
                        return json_encode($alerta);
    
                    }
                }else{
                    $alerta = [
                        "tipo" => "simple",
                        "titulo" => "Ocurrió un error inesperado",
                        "texto" => "El campo EMAIL no es válido",
                        "icono" => "error"
                    ];
                    return json_encode($alerta);

                }
            }
            // Clave
            if($this->checkData("[a-zA-Z0-9$@.\-]{7,100}", $clave1)
            || $this->checkData("[a-zA-Z0-9$@.\-]{7,100}", $clave2)){
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "Las CLAVES no coinciden con el formato solicitado",
                    "icono" => "error"
                ];
                return json_encode($alerta);
            }

            // Verificar que las claves coincidan
            if($clave1 != $clave2){
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "Las CLAVES no coinciden",
                    "icono" => "error"
                ];
                return json_encode($alerta);
            }else{
                $clave = password_hash($clave1, PASSWORD_BCRYPT, ["cost" => 10]);
            }

            // Verificar Usuario
            $checkUser = $this->executeQuery("SELECT usuario_usuario FROM usuario WHERE usuario_usuario = '$usuario'");
            if($checkUser->rowCount() > 0){
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "El USUARIO ingresado ya existe en el sistema",
                    "icono" => "error"
                ];
                return json_encode($alerta);
            }

            # Directorio de Imágenes
            $directorioImg = "../views/fotos/";

            # comprobar si se ha seleccionado una imagen
            if($_FILES['usuario_foto']['name'] != "" && $_FILES['usuario_foto']['size'] > 0){
                # creando directorio   #
                if(!file_exists($directorioImg)){
                    if(!mkdir($directorioImg, 0777)){
                        $alerta = [
                            "tipo" => "simple",
                            "titulo" => "Ocurrió un error inesperado",
                            "texto" => "No se pudo crear el directorio de imágenes",
                            "icono" => "error"
                        ];
                        return json_encode($alerta);
    
                    }
                }

                # verificando el formato de la imagen
                if(mime_content_type($_FILES['usuario_foto']['tmp_name']) != "image/jpeg" &&
                    mime_content_type($_FILES['usuario_foto']['tmp_name']) != "image/jpg" &&
                    mime_content_type($_FILES['usuario_foto']['tmp_name']) != "image/png"){
                    $alerta = [
                        "tipo" => "simple",
                        "titulo" => "Ocurrió un error inesperado",
                        "texto" => "El formato de la imagen no es válido",
                        "icono" => "error"
                    ];
                    return json_encode($alerta);

                }

                # verificando el tamaño de la imagen
                if(($_FILES['usuario_foto']['size'])/1024 > 5120){
                    $alerta = [
                        "tipo" => "simple",
                        "titulo" => "Ocurrió un error inesperado",
                        "texto" => "El tamaño de la imagen no es válido",
                        "icono" => "error"
                    ];
                    return json_encode($alerta);

                }

                # Nombre de la foto
                $foto = str_ireplace(" ", "_", $nombre);
                $foto = $foto."_".rand(0, 1000);
                
                # Extension de la foto
                switch(mime_content_type($_FILES['usuario_foto']['tmp_name'])){
                    case "image/jpeg":
                        $foto = $foto.".jpg";
                        break;
                    case "image/jpg":
                        $foto = $foto.".jpg";
                        break;
                    case "image/png":
                        $foto = $foto.".png";
                        break;
                }

                chmod($directorioImg, 0777);

                # Moviendo la imagen al directorio
                if(!move_uploaded_file($_FILES['usuario_foto']['tmp_name'], $directorioImg.$foto)){
                    $alerta = [
                        "tipo" => "simple",
                        "titulo" => "Ocurrió un error inesperado",
                        "texto" => "No se pudo guardar la imagen",
                        "icono" => "error"
                    ];
                    return json_encode($alerta);

                }

            }else{
                $foto = "";
            }

            $usuario_datos_reg = [
                [
                    "campo_nombre" => "usuario_nombre",
                    "campo_marcador" => ":usuario_nombre",
                    "campo_valor" => $nombre
                ],
                [
                    "campo_nombre" => "usuario_apellido",
                    "campo_marcador" => ":usuario_apellido",
                    "campo_valor" => $apellido
                ],
                [
                    "campo_nombre" => "usuario_usuario",
                    "campo_marcador" => ":usuario_usuario",
                    "campo_valor" => $usuario
                ],
                [
                    "campo_nombre" => "usuario_email",
                    "campo_marcador" => ":usuario_email",
                    "campo_valor" => $email
                ],
                [
                    "campo_nombre" => "usuario_clave",
                    "campo_marcador" => ":usuario_clave",
                    "campo_valor" => $clave
                ],
                [
                    "campo_nombre" => "usuario_foto",
                    "campo_marcador" => ":usuario_foto",
                    "campo_valor" => $foto
                ],
                [
                    "campo_nombre" => "usuario_creado",
                    "campo_marcador" => ":Creado",
                    "campo_valor" => date("Y-m-d H:i:s")
                ],
                [
                    "campo_nombre" => "usuario_actualizado",
                    "campo_marcador" => ":Actualizado",
                    "campo_valor" => date("Y-m-d H:i:s")
                ]
            ];

            $registrar_usuario = $this->saveData("usuario", $usuario_datos_reg);

            if($registrar_usuario->rowCount() == 1){
                $alerta = [
                    "tipo" => "limpiar",
                    "titulo" => "Usuario registrado",
                    "texto" => "El usuario ".$nombre." ".$apellido." ha sido registrado exitosamente",
                    "icono" => "success"
                ];
            }else{
                if(is_file($directorioImg.$foto)){
                    chmod($directorioImg.$foto, 0777);
                    unlink($directorioImg.$foto);
                }

                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "No se pudo registrar el usuario",
                    "icono" => "error"
                ];
            }

            return json_encode($alerta);
        }

        // Controlador para listar usuarios
        public function listarUsuarioControlador($pagina, $registros, $url, $busqueda){
            $pagina = mainModel::cleanString($pagina);
            $registros = mainModel::cleanString($registros);

            $url = mainModel::cleanString($url);
            $url = APP_URL.$url."/";

            $busqueda = mainModel::cleanString($busqueda);
            $tabla = "";

            $pagina = (isset($pagina) && $pagina > 0) ? (int)$pagina : 1;

            $inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;

            if(isset($busqueda) && $busqueda != ""){
                $consulta_datos = "SELECT * FROM usuario WHERE usuario_id != '".$_SESSION['id']."' AND (usuario_nombre LIKE '%$busqueda%' OR usuario_apellido LIKE '%$busqueda%' OR usuario_usuario LIKE '%$busqueda%' OR usuario_email LIKE '%$busqueda%') ORDER BY usuario_nombre ASC LIMIT $inicio, $registros";

                $consulta_total = "SELECT COUNT(usuario_id) FROM usuario WHERE usuario_id != '".$_SESSION['id']."' AND (usuario_nombre LIKE '%$busqueda%' OR usuario_apellido LIKE '%$busqueda%' OR usuario_usuario LIKE '%$busqueda%' OR usuario_email LIKE '%$busqueda%')";
            }else{
                $consulta_datos = "SELECT * FROM usuario WHERE usuario_id != '".$_SESSION['id']."' ORDER BY usuario_nombre ASC LIMIT $inicio, $registros";

                $consulta_total = "SELECT COUNT(usuario_id) FROM usuario WHERE usuario_id != '".$_SESSION['id']."'"; 
            }

            $datos = mainModel::executeQuery($consulta_datos);
            $datos = $datos->fetchAll();

            $total = mainModel::executeQuery($consulta_total);
            $total = (int)$total->fetchColumn();

            $Npaginas = ceil($total/$registros);

            $tabla .= '
            <div class="table-container">
            <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
                <thead>
                    <tr>
                        <th class="has-text-centered">#</th>
                        <th class="has-text-centered">Nombre</th>
                        <th class="has-text-centered">Usuario</th>
                        <th class="has-text-centered">Email</th>
                        <th class="has-text-centered">Creado</th>
                        <th class="has-text-centered">Actualizado</th>
                        <th class="has-text-centered" colspan="3">Opciones</th>
                    </tr>
                </thead>
                <tbody>
            ';

            if($total >= 1 && $pagina <= $Npaginas){
                $contador = $inicio + 1;
                $pag_inicio = $inicio + 1;

                foreach($datos as $rows){
                    $tabla .= '
                    <tr class="has-text-centered">
                        <td>'.$contador.'</td>
                        <td>'.$rows['usuario_nombre'].' '.$rows['usuario_apellido'].'</td>
                        <td>'.$rows['usuario_usuario'].'</td>
                        <td>'.$rows['usuario_email'].'</td>
                        <td>'.date("d-m-Y h:i:s A", strtotime($rows['usuario_creado'])).'</td>
                        <td>'.date("d-m-Y h:i:s A", strtotime($rows['usuario_actualizado'])).'</td>
                        <td>
                            <a href="'.APP_URL.'userPhoto/'.$rows['usuario_id'].'/" class="button is-info is-rounded is-small">Foto</a>
                        </td>
                        <td>
                            <a href="'.APP_URL.'userUpdate/'.$rows['usuario_id'].'/" class="button is-success is-rounded is-small">Actualizar</a>
                        </td>
                        <td>
                            <form class="FormularioAjax" action="'.APP_URL.'APP/ajax/usuarioAjax.php"
                             method="POST" autocomplete="off">

                                <input type="hidden" name="modulo_usuario" value="eliminar">
                                <input type="hidden" name="usuario_id" value="'.$rows['usuario_id'].'">

                                <button type="submit" class="button is-danger is-rounded is-small">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    ';

                    $contador ++;
                }
                $pag_final = $contador - 1;

            }else{
                if($total >=1){
                    $tabla .= '
                        <tr class="has-text-centered" >
                            <td colspan="7">
                                <a href="'.$url.'1/" class="button is-link is-rounded is-small mt-4 mb-4">
                                    Haga clic acá para recargar el listado
                                </a>
                            </td>
                        </tr>
                    ';
                }else{
                    $tabla .= '
                        <tr class="has-text-centered" >
                            <td colspan="7">
                                No hay registros en el sistema
                            </td>
                        </tr>
                    ';
                }
            }

            $tabla .= '</tbody></table></div>';

            if($total >= 1 && $pagina <= $Npaginas){
                $tabla .= '
                    <p class="has-text-right">
                        Mostrando usuarios <strong class="resaltado">'.$pag_inicio.'</strong> al <strong class="resaltado">'.$pag_final.'</strong> de un <strong class="resaltado">total de '.$total.'</strong>
                    </p>
                ';

                $tabla .= mainModel::pagerTable($pagina, $Npaginas, $url, 7);
            }

            return $tabla;
        }

        // Controlador para eliminar un usuario
        public function eliminarUsuarioControlador(){
            // Almacenando Datos
            $id = mainModel::cleanString($_POST['usuario_id']);

            // Verificar que el usuario exista en la BD
            $datos = mainModel::executeQuery("SELECT * FROM usuario WHERE usuario_id = '$id'");

            if($datos->rowCount() <= 0){
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "El usuario que intenta eliminar no existe en el sistema",
                    "icono" => "error"
                ];
                return json_encode($alerta);
            }else{
                $datos = $datos->fetch();
            }

            // eliminar usuario
            $eliminar_usuario = mainModel::deleteData('usuario', 'usuario_id', $id);

            if($eliminar_usuario->rowCount() == 1){
                if($datos['usuario_foto'] != ""){
                    $directorioImg = "../views/fotos/";
                    if(is_file($directorioImg.$datos['usuario_foto'])){
                        chmod($directorioImg.$datos['usuario_foto'], 0777);
                        unlink($directorioImg.$datos['usuario_foto']);
                    }
                }
                
                $alerta = [
                    "tipo" => "recargar",
                    "titulo" => "Usuario eliminado",
                    "texto" => "El usuario ".$datos['usuario_nombre']." ".$datos['usuario_apellido']." ha sido eliminado exitosamente",
                    "icono" => "success"
                ];

            }else{
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "No se pudo eliminar el usuario",
                    "icono" => "error"
                ];
            }

            return json_encode($alerta);
        }

        // Controlador para actualizar un usuario
        public function actualizarUsuarioControlador(){

            $id=mainModel::cleanString($_POST['usuario_id']);
        
            # Verificando usuario #
            $datos=mainModel::executeQuery("SELECT * FROM usuario WHERE usuario_id='$id'");
            if($datos->rowCount()<=0){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"No hemos encontrado el usuario en el sistema",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                
            }else{
                $datos=$datos->fetch();
            }
        
            $admin_usuario = mainModel::cleanString($_POST['administrador_usuario']);
            $admin_clave = mainModel::cleanString($_POST['administrador_clave']);
        
            # Verificando campos obligatorios admin #
            if($admin_usuario=="" || $admin_clave==""){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"No ha llenado todos los campos que son obligatorios, que corresponden a su USUARIO y CLAVE",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                
            }
        
            if(mainModel::checkData("[a-zA-Z0-9]{4,20}",$admin_usuario)){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"Su USUARIO no coincide con el formato solicitado",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
            }
        
            if(mainModel::checkData("[a-zA-Z0-9$@.-]{7,100}",$admin_clave)){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"Su CLAVE no coincide con el formato solicitado",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                
            }
        
            # Verificando administrador #
            $check_admin = mainModel::executeQuery("SELECT * FROM usuario WHERE usuario_usuario='$admin_usuario' AND usuario_id='".$_SESSION['id']."'");
            if($check_admin->rowCount()==1){
                $check_admin=$check_admin->fetch();
        
                if($check_admin['usuario_usuario']!=$admin_usuario || !password_verify($admin_clave,$check_admin['usuario_clave'])){
        
                    $alerta=[
                        "tipo"=>"simple",
                        "titulo"=>"Ocurrió un error inesperado",
                        "texto"=>"USUARIO o CLAVE de administrador incorrectos",
                        "icono"=>"error"
                    ];
                    return json_encode($alerta);
                    
                }
            }else{
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"USUARIO o CLAVE de administrador incorrectos",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
            }
        
        
            # Almacenando datos#
            $nombre = mainModel::cleanString($_POST['usuario_nombre']);
            $apellido = mainModel::cleanString($_POST['usuario_apellido']);
        
            $usuario=mainModel::cleanString($_POST['usuario_usuario']);
            $email=mainModel::cleanString($_POST['usuario_email']);
            $clave1=mainModel::cleanString($_POST['usuario_clave_1']);
            $clave2=mainModel::cleanString($_POST['usuario_clave_2']);
        
            # Verificando campos obligatorios #
            if($nombre=="" || $apellido=="" || $usuario==""){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"No has llenado todos los campos que son obligatorios",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                
            }
        
            # Verificando integridad de los datos #
            if(mainModel::checkData("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}",$nombre)){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"El NOMBRE no coincide con el formato solicitado",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                
            }
        
            if(mainModel::checkData("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}",$apellido)){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"El APELLIDO no coincide con el formato solicitado",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                
            }
        
            if(mainModel::checkData("[a-zA-Z0-9]{4,20}",$usuario)){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"El USUARIO no coincide con el formato solicitado",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                
            }
        
            # Verificando email #
            if($email!="" && $datos['usuario_email']!=$email){
                if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                    $check_email=mainModel::executeQuery("SELECT usuario_email FROM usuario WHERE usuario_email='$email'");
                    if($check_email->rowCount()>0){
                        $alerta=[
                            "tipo"=>"simple",
                            "titulo"=>"Ocurrió un error inesperado",
                            "texto"=>"El EMAIL que acaba de ingresar ya se encuentra registrado en el sistema, por favor verifique e intente nuevamente",
                            "icono"=>"error"
                        ];
                        return json_encode($alerta);
                        
                    }
                }else{
                    $alerta=[
                        "tipo"=>"simple",
                        "titulo"=>"Ocurrió un error inesperado",
                        "texto"=>"Ha ingresado un correo electrónico no valido",
                        "icono"=>"error"
                    ];
                    return json_encode($alerta);
                    
                }
            }
        
            # Verificando claves #
            if($clave1!="" || $clave2!=""){
                if(mainModel::checkData("[a-zA-Z0-9$@.-]{7,100}",$clave1) || mainModel::checkData("[a-zA-Z0-9$@.-]{7,100}",$clave2)){
                    $alerta=[
                        "tipo"=>"simple",
                        "titulo"=>"Ocurrió un error inesperado",
                        "texto"=>"Las CLAVES no coinciden con el formato solicitado",
                        "icono"=>"error"
                    ];
                    return json_encode($alerta);
                    
                }else{
                    if($clave1!=$clave2){
        
                        $alerta=[
                            "tipo"=>"simple",
                            "titulo"=>"Ocurrió un error inesperado",
                            "texto"=>"Las nuevas CLAVES que acaba de ingresar no coinciden, por favor verifique e intente nuevamente",
                            "icono"=>"error"
                        ];
                        return json_encode($alerta);
                        
                    }else{
                        $clave=password_hash($clave1,PASSWORD_BCRYPT,["cost" => 10]);
                    }
                }
            }else{
                $clave=$datos['usuario_clave'];
            }
        
            # Verificando usuario #
            if($datos['usuario_usuario']!=$usuario){
                $check_usuario=mainModel::executeQuery("SELECT usuario_usuario FROM usuario WHERE usuario_usuario='$usuario'");
                if($check_usuario->rowCount() > 0){
                    $alerta=[
                        "tipo"=>"simple",
                        "titulo"=>"Ocurrió un error inesperado",
                        "texto"=>"El USUARIO ingresado ya se encuentra registrado, por favor elija otro",
                        "icono"=>"error"
                    ];
                    return json_encode($alerta);
                    
                }
            }
        
            $usuario_datos_up=[
                [
                    "campo_nombre"=>"usuario_nombre",
                    "campo_marcador"=>":Nombre",
                    "campo_valor"=>$nombre
                ],
                [
                    "campo_nombre"=>"usuario_apellido",
                    "campo_marcador"=>":Apellido",
                    "campo_valor"=>$apellido
                ],
                [
                    "campo_nombre"=>"usuario_usuario",
                    "campo_marcador"=>":Usuario",
                    "campo_valor"=>$usuario
                ],
                [
                    "campo_nombre"=>"usuario_email",
                    "campo_marcador"=>":Email",
                    "campo_valor"=>$email
                ],
                [
                    "campo_nombre"=>"usuario_clave",
                    "campo_marcador"=>":Clave",
                    "campo_valor"=>$clave
                ],
                [
                    "campo_nombre"=>"usuario_actualizado",
                    "campo_marcador"=>":Actualizado",
                    "campo_valor"=>date("Y-m-d H:i:s")
                ]
            ];
        
            $condicion=[
                "condicion_campo" => "usuario_id",
                "condicion_marcador" => ":ID",
                "condicion_valor" => $id
            ];
        
            if(mainModel::updateData("usuario",$usuario_datos_up,$condicion)){
        
                if($id==$_SESSION['id']){
                    $_SESSION['nombre']=$nombre;
                    $_SESSION['apellido']=$apellido;
                    $_SESSION['usuario']=$usuario;
                }
        
                $alerta=[
                    "tipo"=>"recargar",
                    "titulo"=>"Usuario actualizado",
                    "texto"=>"Los datos del usuario ".$datos['usuario_nombre']." ".$datos['usuario_apellido']." se actualizaron correctamente",
                    "icono"=>"success"
                ];
            }else{
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"No hemos podido actualizar los datos del usuario ".$datos['usuario_nombre']." ".$datos['usuario_apellido'].", por favor intente nuevamente",
                    "icono"=>"error"
                ];
            }
        
            return json_encode($alerta);
        }

        // Controlador para actualizar la foto de un usuario
        public function actualizarFotoControlador(){
            $id = mainModel::cleanString($_POST['usuario_id']);

            # Verificando usuario #
            $datos = mainModel::executeQuery("SELECT * FROM usuario WHERE usuario_id = '$id'");
            if($datos->rowCount() <= 0){
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "No hemos encontrado el usuario en el sistema",
                    "icono" => "error"
                ];
                return json_encode($alerta);
            }else{
                $datos = $datos->fetch();
            }

            # Directorio de Imágenes
            $directorioImg = "../views/fotos/";

            # comprobar si se ha seleccionado una imagen
            if($_FILES['usuario_foto']['name'] == "" && $_FILES['usuario_foto']['size'] <= 0){
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "No ha seleccionado una imagen",
                    "icono" => "error"
                ];
                return json_encode($alerta);
            }

            # Creando directorio
            if(!file_exists($directorioImg)){
                if(!mkdir($directorioImg, 0777)){
                    $alerta = [
                        "tipo" => "simple",
                        "titulo" => "Ocurrió un error inesperado",
                        "texto" => "No se pudo crear el directorio de imágenes",
                        "icono" => "error"
                    ];
                    return json_encode($alerta);
                }
            }

            # Verificando el formato de la imagen
            if(mime_content_type($_FILES['usuario_foto']['tmp_name']) != "image/jpeg" &&
                mime_content_type($_FILES['usuario_foto']['tmp_name']) != "image/jpg" &&
                mime_content_type($_FILES['usuario_foto']['tmp_name']) != "image/png"){
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "El formato de la imagen no es válido",
                    "icono" => "error"
                ];
                return json_encode($alerta);
            }

            # Verificando el tamaño de la imagen
            if(($_FILES['usuario_foto']['size'])/1024 > 5120){
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "El tamaño de la imagen no es válido",
                    "icono" => "error"
                ];
                return json_encode($alerta);
            }

            # Nombre de la foto
            if($datos['usuario_foto'] != ""){
                $foto = explode(".", $datos['usuario_foto']);
                $foto = $foto[0];
            }else{
                $foto = str_ireplace(" ", "_", $datos['usuario_nombre']);
                $foto = $foto."_".rand(0, 100);
            }

            # Extension de la foto
            switch(mime_content_type($_FILES['usuario_foto']['tmp_name'])){
                case "image/jpeg":
                    $foto = $foto.".jpg";
                    break;
                case "image/jpg":
                    $foto = $foto.".jpg";
                    break;
                case "image/png":
                    $foto = $foto.".png";
                    break;
            }

            chmod($directorioImg, 0777);

            # Moviendo la imagen al directorio
            if(!move_uploaded_file($_FILES['usuario_foto']['tmp_name'], $directorioImg.$foto)){
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "No se pudo guardar la imagen",
                    "icono" => "error"
                ];
                return json_encode($alerta);
            }

            # Eliminando la foto anterior
            if(is_file($directorioImg.$datos['usuario_foto']) && $datos['usuario_foto'] != $foto){
                chmod($directorioImg.$datos['usuario_foto'], 0777);
                unlink($directorioImg.$datos['usuario_foto']);
            }

            $usuario_datos_up = [
                [
                    "campo_nombre" => "usuario_foto",
                    "campo_marcador" => ":Foto",
                    "campo_valor" => $foto
                ],
                [
                    "campo_nombre" => "usuario_actualizado",
                    "campo_marcador" => ":Actualizado",
                    "campo_valor" => date("Y-m-d H:i:s")
                ]
            ];

            $condicion = [
                "condicion_campo" => "usuario_id",
                "condicion_marcador" => ":ID",
                "condicion_valor" => $id
            ];

            if(mainModel::updateData("usuario", $usuario_datos_up, $condicion)){
                if($id == $_SESSION['id']){
                    $_SESSION['foto'] = $foto;
                }
                $alerta = [
                    "tipo" => "recargar",
                    "titulo" => "Foto actualizada",
                    "texto" => "La foto del usuario ".$datos['usuario_nombre']." ".$datos['usuario_apellido']." se actualizó correctamente",
                    "icono" => "success"
                ];
            }else{
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "No hemos podido actualizar la foto del usuario ".$datos['usuario_nombre']." ".$datos['usuario_apellido'].", por favor intente nuevamente",
                    "icono" => "error"
                ];
            }

            return json_encode($alerta);
                
        }
        
        // Controlador para eliminar la foto de un usuario
        public function eliminarFotoControlador(){
            $id = mainModel::cleanString($_POST['usuario_id']);

            # Verificando usuario #
            $datos = mainModel::executeQuery("SELECT * FROM usuario WHERE usuario_id = '$id'");
            if($datos->rowCount() <= 0){
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "No hemos encontrado el usuario en el sistema",
                    "icono" => "error"
                ];
                return json_encode($alerta);
            }else{
                $datos = $datos->fetch();
            }

            # Directorio de Imágenes
            $directorioImg = "../views/fotos/";
            chmod($directorioImg, 0777);

            # Eliminando la foto
            if(is_file($directorioImg.$datos['usuario_foto'])){
                chmod($directorioImg.$datos['usuario_foto'], 0777);
                if(!unlink($directorioImg.$datos['usuario_foto'])){
                    $alerta = [
                        "tipo" => "limpiar",
                        "titulo" => "Foto eliminada",
                        "texto" => "La foto del usuario ".$datos['usuario_nombre']." ".$datos['usuario_apellido']." se eliminó correctamente",
                        "icono" => "success"
                    ];
                    return json_encode($alerta);
                }
            }else{
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "No se pudo eliminar la foto del usuario ".$datos['usuario_nombre']." ".$datos['usuario_apellido'],
                    "icono" => "error"
                ];
                return json_encode($alerta);
            }

            $usuario_datos_up = [
                [
                    "campo_nombre" => "usuario_foto",
                    "campo_marcador" => ":Foto",
                    "campo_valor" => ""
                ],
                [
                    "campo_nombre" => "usuario_actualizado",
                    "campo_marcador" => ":Actualizado",
                    "campo_valor" => date("Y-m-d H:i:s")
                ]
            ];

            $condicion = [
                "condicion_campo" => "usuario_id",
                "condicion_marcador" => ":ID",
                "condicion_valor" => $id
            ];

            if(mainModel::updateData("usuario", $usuario_datos_up, $condicion)){
                if($id == $_SESSION['id']){
                    $_SESSION['foto'] = "";
                }
                $alerta = [
                    "tipo" => "recargar",
                    "titulo" => "Foto eliminada",
                    "texto" => "La foto del usuario ".$datos['usuario_nombre']." ".$datos['usuario_apellido']." se eliminó correctamente",
                    "icono" => "success"
                ];
            }else{
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "No hemos podido eliminar la foto del usuario ".$datos['usuario_nombre']." ".$datos['usuario_apellido'].", por favor intente nuevamente",
                    "icono" => "warning"
                ];
            }

            return json_encode($alerta);
        }
    }
?>