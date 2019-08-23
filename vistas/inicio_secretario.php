<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();
if( !isset($_SESSION["id"]) )
{
  	header("Location: login.php");
}
else
{
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<?php 
	 require "header.php"; 
	 include "menu.php"; 
	 ?>
	 <title>Sistemas Microtaximetros</title>
</head>
<body>
<ol class="breadcrumb migas_pan">
	<li class="active"><i class="fa fa-home"></i><b> Inicio</b></li>
</ol>	
<?php
if( $_SESSION['tipo']=='Secretario' )
{ ?>
<!-- div que contiene la clase container-fluid -->
<div  class="container-fluid">
	<div class="row">
		<center><img style="width: 200px;opacity: 0.4;" src="../public/img/logo_micro.png" class="logoPlataforma"></center>
	</div>
</div>
<!-- final fiv que contiene la clase container fluid -->
<?php
}// final if donde entra cuando el usuario si es tipo administrador
	require "footer.php"; ?>
</body>
</html>
<?php 
 }// final else donde entra cuando la session id si esta definida 
ob_end_flush();
?>