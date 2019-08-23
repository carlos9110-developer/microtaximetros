<?php
session_start();
//Limpiamos las variables de sesión   
session_unset();
//Destruìmos la sesión
session_destroy();
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<?php require "header.php"; ?>
	<title>Sistema Microtaximetros</title>
	<link rel="stylesheet" href="../public/css/login.css">
</head>
<body>
	<div class="container" style="margin-top: 20px;">
		<div class="row">
			<div class="col-xs-12 col-sm-3 col-lg-4"></div>		
			<div class="col-xs-12 col-sm-6 col-lg-4">
				<div class="panel panel-default">
					<div class="panel-heading"></div>
				  	<div class="panel-body">
					  	<center><img src="../public/img/logo_micro.png" width="200px" alt=""><br><br></center>
					  	<form action="" id="form_login" name="form_login" method="post" autocomplete="off">
					  		<div class="input-group">
					  			<span class="input-group-addon">
					  				<i class="glyphicon glyphicon-user"></i>
					  			</span>
					  			<input id="usuario" type="text" class="form-control" name="usuario" placeholder="Digite el usuario" required="">  
					  		</div>
					  		<div class="input-group">
					  			<span class="input-group-addon"><i class="fa fa-key"></i></span>
					  			<input id="clave" type="password" class="form-control" name="clave" placeholder="Digite la contraseña">
					  		</div><br>
					  		<button type="submit" class="btn btn-primary btn-sm pull-right">
					  			<i class="fa fa-sign-in-alt"></i>&nbsp; INICIAR SESION
					  		</button>
					  	</form>
				  	</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-3 col-lg-4"></div>
		</div>
	</div>
	<div id="particles-js"></div>	
	<?php require "footer.php"; ?>
	<script src="js/login.js"></script>
	<script src="../public/js/particles.js"></script>
	<script src="../public/js/plugin_particles.js"></script>
</body>
</html>