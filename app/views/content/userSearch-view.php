<div class="container is-fluid mt-3">
    <h1 class="title">Usuarios</h1>
    <h2 class="subtitle">Buscar usuarios</h2>
</div>

<div class="container px-3 pb-6 pt-5">
    <?php
    
        use app\controllers\userController;
        $insUsuario = new userController();

        if(!isset($_SESSION[$url[0]]) && empty($_SESSION[$url[0]])){
    ?>
    <div class="columns">
        <div class="column">
            <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/buscadorAjax.php" method="POST" autocomplete="off" >
                <input type="hidden" name="modulo_buscador" value="buscar">
                <input type="hidden" name="modulo_url" value="<?php echo $url[0]; ?>">
                <div class="field is-grouped">
                    <p class="control is-expanded">
                        <input class="input is-rounded" type="text" name="txt_buscador" placeholder="¿Qué estas buscando?" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}" maxlength="30" required >
                    </p>
                    <p class="control">
                        <button class="button is-info" type="submit" >Buscar</button>
                    </p>
                </div>
            </form>
        </div>
    </div>
    <?php }else{ ?>
    <div class="columns">
        <div class="column">
            <form class="has-text-centered mt-2 mb-2 FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/buscadorAjax.php" method="POST" autocomplete="off" >
                <input type="hidden" name="modulo_buscador" value="eliminar">
                <input type="hidden" name="modulo_url" value="<?php echo $url[0]; ?>">
                <div class="columns has-text-centered">
                    <div class="column has-text-right">
                        <p>Estas buscando <strong class="resaltado" >“<?php echo $_SESSION[$url[0]]; ?>”</strong></p>
                    </div>
                    <div class="column has-text-left">
                        <button type="submit" class="button is-danger is-rounded">Eliminar busqueda</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php
            echo $insUsuario->listarUsuarioControlador($url[1],15,$url[0],$_SESSION[$url[0]]);
        }
    ?>
</div>