<div class="main-container">

    <form class="box login" action="" method="POST" autocomplete="off" >
		<h5 class="title is-5 has-text-centered is-uppercase">LOGIN</h5>

		<div class="field">
			<label for="usuario" class="label">Usuario</label>
			<div class="control">
			    <input id="usuario" class="input" type="text" name="login_usuario" pattern="[a-zA-Z0-9]{4,20}" maxlength="20" required >
			</div>
		</div>

		<div class="field">
		  	<label for="password" class="label">Contraseña</label>
		  	<div class="control has-icons-right">
				<input id="password" class="input" type="password" name="login_clave" pattern="[a-zA-Z0-9$@.\-]{7,100}" maxlength="100" required>
			</div>
		</div>

		<!-- checkbox Mostrar Contrasena -->
		<div class="field">
			<input id="mostrar_contrasena" type="checkbox" class="checkbox">
			<label for="mostrar_contrasena">Mostrar contraseña</label>
		</div>

		<p class="has-text-centered mb-4 mt-3">
			<button type="submit" class="button is-info is-rounded">
				Iniciar sesion
			</button>
		</p>

	</form>
</div>

<?php
	if(isset($_POST['login_usuario']) && isset($_POST['login_clave'])){
		// Se crea una instancia del controlador de usuarios
		$insLogin->iniciarSesionControlador();


	}
?>