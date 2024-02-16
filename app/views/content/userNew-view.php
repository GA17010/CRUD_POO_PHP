<div class="container is-fluid mt-2">
	<h1 class="title">Usuarios</h1>
	<h2 class="subtitle">Nuevo usuario</h2>
</div>

<div class="containerUpdate">
	<div class="container px-4 pb-4 pt-4">
		<form class="FormularioAjax" action="<?php echo APP_URL; ?>/app/ajax/usuarioAjax.php"
			method="POST" autocomplete="off" enctype="multipart/form-data" >

			<input type="hidden" name="modulo_usuario" value="registrar">

			<div class="columns">
				<div class="column">
					<div class="control">
						<label for="nombres">Nombres</label>
						<input id="nombres" class="input" type="text" name="usuario_nombre" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" required >
					</div>
				</div>
				<div class="column">
					<div class="control">
						<label for="apellidos">Apellidos</label>
						<input id = "apellidos" class="input" type="text" name="usuario_apellido" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" required >
					</div>
				</div>
			</div>
			<div class="columns">
				<div class="column">
					<div class="control">
						<label for="usuario">Usuario</label>
						<input id="usuario" class="input" type="text" name="usuario_usuario" pattern="[a-zA-Z0-9]{4,20}" maxlength="20" required >
					</div>
				</div>
				<div class="column">
					<div class="control">
						<label for = "email">Email</label>
						<input id="email" class="input" type="email" name="usuario_email" maxlength="70" autocomplete='off' >
					</div>
				</div>
			</div>
			<div class="columns">
				<div class="column">
					<div class="control">
						<label for="clave1">Clave</label>
						<input id="clave1" class="input" type="password" name="usuario_clave_1" pattern="[a-zA-Z0-9$@.\-]{7,100}" maxlength="100" required >
					</div>
				</div>
				<div class="column">
					<div class="control">
						<label for="clave2">Repetir clave</label>
						<input id="clave2" class="input" type="password" name="usuario_clave_2" pattern="[a-zA-Z0-9$@.\-]{7,100}" maxlength="100" required >
					</div>
				</div>
			</div>
			<div class="columns">
				<div class="column">
					<div class="file has-name is-boxed">
						<label class="file-label">
							<input class="file-input" type="file" name="usuario_foto" accept=".jpg, .png, .jpeg" >
							<span class="file-cta">
								<span class="file-label">
									Seleccione una foto
								</span>
							</span>
							<span class="file-name">JPG, JPEG, PNG. (MAX 5MB)</span>
						</label>
					</div>
				</div>
			</div>
			<p class="has-text-centered">
				<button type="reset" class="button is-link is-light is-rounded">Limpiar</button>
				<button type="submit" class="button is-info is-rounded">Guardar</button>
			</p>
		</form>
	</div>
</div>