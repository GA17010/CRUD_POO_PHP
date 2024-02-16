<?php
    namespace app\models;
    use \PDO;

    # Importar la configuración de la base de datos
    if(is_file(__DIR__."/../../config/server.php")){
        require_once(__DIR__."/../../config/server.php");
    }

    class mainModel{
        # Atributos de la clase
        private $server = DB_SERVER;
        private $db = DB_NAME;
        private $user = DB_USER;
        private $pass = DB_PASS;
        
        # Función para conectar a la base de datos
        protected function connect(){
            # Conexión a la base de datos
            $connection = new PDO("mysql:host=".$this->server.";dbname=".$this->db.";charset=utf8", $this->user, $this->pass);
            $connection->exec("SET CHARACTER SET utf8"); #Establecer el conjunto de caracteres predeterminado a usar cuando se envían datos desde y hacia el servidor de la base de datos.
            return $connection;
        }

        # Función para ejecutar una consulta
        protected function executeQuery($query){
            $sql = $this->connect()->prepare($query); #Prepara una sentencia para su ejecución y devuelve un objeto sentencia
            $sql->execute(); 
            return $sql;
        }

        # Funcion para limpiar cadena de texto
        public function cleanString($string){
            $words = ["SELECT", "INSERT", "UPDATE", "DELETE", "FROM", "WHERE", "DROP", "TABLE", "DATABASE",
                "TRUNCATE", "INTO", "VALUES", "ALTER", "ADD", "MODIFY", "ORDER", "BY", "GROUP", "HAVING", "LIMIT",
                "<script>", "</script>", "<script", "</script", "</script src", "</script type=", "SELECT * FROM",
                "<?php", "?>", "<?", "?>", "php", "echo", "print", "if", "else", "elseif", "while", "for", "foreach",
                "SHOW DATABASES", "SHOW TABLES", "SHOW COLUMNS FROM", "SHOW COLUMNS", "SHOW", "DESCRIBE", "DESC",
                "LIKE", "DROP TABLE", "DROP DATABASE", "TRUNCATE TABLE", "ALTER TABLE", "ALTER DATABASE",
                "CREATE TABLE", ">", "<", ";", "::", "=", "=="];
            $string =trim($string); #Elimina espacio en blanco (u otro tipo de caracteres) del inicio y el final de la cadena
            $string = stripslashes($string); #Quita las barras de un string con comillas escapadas

            # Elimina todas las apariciones de los strings buscados en el string dado
            foreach($words as $word){
                $string = str_ireplace($word, "", $string); #Reemplaza todas las apariciones del string buscado con el string de reemplazo
            }

            $string = trim($string); #Elimina espacio en blanco (u otro tipo de caracteres) del inicio y el final de la cadena
            $string = stripslashes($string); #Quita las barras de un string con comillas escapadas

            return $string;
        }

        # Verificar Datos
        protected function checkData($filter, $string){
            # Verifica si la cadena de texto contiene los caracteres buscados
            if(preg_match("/^".$filter."$/", $string)){
                return false;
            }else{
                return true;
            }
        }

        # Guardar Datos
        protected function saveData($table, $data){
            $query = "INSERT INTO ".$table." (";

            $i = 0;
            foreach($data as $key){
                if($i >= 1){
                    $query .= ", ";
                }
                $query .= $key["campo_nombre"];
                $i++;
            }

            $query .= ") VALUES (";

            $i = 0;
            foreach($data as $key){
                if($i >= 1){
                    $query .= ", ";
                }
                $query .= $key["campo_marcador"];
                $i++;
            }

            $query .= ")";

            $sql = $this->connect()->prepare($query);
            
            foreach($data as $key){
                $sql->bindParam($key["campo_marcador"], $key["campo_valor"]);
            }
            
            $sql->execute();

            return $sql;
        }

        # Seleccionar Datos
        public function selectData($type, $table, $field, $id){
            # Limpiar los datos
            $type = $this->cleanString($type);
            $table = $this->cleanString($table);
            $field = $this->cleanString($field);
            $id = $this->cleanString($id);

            if($type == "Unico"){
                # Seleccionar todos los datos de una tabla
                $sql = $this->connect()->prepare("SELECT * FROM ".$table." WHERE ".$field." = :ID");
                $sql->bindParam(":ID", $id);
            }elseif($type == "Normal"){
                # Seleccionar todos los datos de una tabla
                $sql = $this->connect()->prepare("SELECT $campo FROM $tabla");
            }

            $sql->execute();
            return $sql;
        }

        # Actualizar Datos
        protected function updateData($table, $data, $condition){
            $query = "UPDATE ".$table." SET ";

            $i = 0;
            foreach($data as $key){
                if($i >= 1){
                    $query .= ",";
                }
                $query .= $key["campo_nombre"]."=".$key["campo_marcador"];
                $i++;
            }

            $query .= " WHERE ".$condition["condicion_campo"]."=".$condition["condicion_marcador"];

            $sql = $this->connect()->prepare($query);

            foreach($data as $key){
                $sql->bindParam($key["campo_marcador"], $key["campo_valor"]);
            }

            $sql->bindParam($condition["condicion_marcador"], $condition["condicion_valor"]);

            $sql->execute();

            return $sql;
        }

        # Eliminar Datos
        protected function deleteData($table, $field, $id){
            $sql = $this->connect()->prepare("DELETE FROM ".$table." WHERE ".$field." = :id");
            $sql->bindParam(":id", $id);
            $sql->execute();
            return $sql;
        }

        # Paginador de Tablas
        protected function pagerTable($page, $numberPage, $url, $buttons){
            $tabla = '<nav class="pagination is-centered is-rounded" role="navigation" aria-label="pagination">';

            if($page <= 1){
                $tabla .= '<a class="pagination-previous" disabled>Anterior</a>
                    <ul class="pagination-list">
                ';

            }else{
                $tabla .= '<a class="pagination-previous" href="'.$url.($page-1).'/">Anterior</a>
                    <ul class="pagination-list">
                        <li><a class="pagination-link" href="'.$url.'1/">1</a></li>
                        <li><span class="pagination-ellipsis">&hellip;</span></li>
                ';
            }

            $i = 0;

            for($i = $page; $i <= $numberPage; $i++){
                if($i >= $buttons){
                    break;
                }

                if($page == $i){
                    $tabla .= '<li><a class="pagination-link is-current" href="'.$url.$i.'/">'.$i.'</a></li>';
                }else{
                    $tabla .= '<li><a class="pagination-link" href="'.$url.$i.'/">'.$i.'</a></li>';
                }

                $i++;
            }

            if($page == $numberPage){
                $tabla .= '
                    </ul>
                <a class="pagination-next" disabled>Siguiente</a>
                ';
            }else{
                $tabla .= '
                        <li><span class="pagination-ellipsis">&hellip;</span></li>
                        <li><a class="pagination-link" href="'.$url.$numberPage.'/">'.$numberPage.'</a></li>
                    </ul>
                <a class="pagination-next" href="'.$url.($page+1).'/">Siguiente</a>
                ';
            }

            $tabla .= '</nav>';

            return $tabla;
        }


    }