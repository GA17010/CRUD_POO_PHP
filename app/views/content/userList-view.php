<div class="container is-fluid mt-4">
	<h1 class="title">Usuarios</h1>
	<h2 class="subtitle">Lista de usuario</h2>
</div>
<div class="container px-3 pb-2 pt-6">
    <?php
        use app\controllers\userController;

        $insUser = new userController();

        echo $insUser->listarUsuarioControlador($url[1], 7, $url[0], "");


    ?>

</div>