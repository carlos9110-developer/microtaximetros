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
	 echo '<link rel="stylesheet" type="text/css" href="../public/css/inicio_administradores.css">';
	 include "menu.php"; 
	 ?>
	 <title>Sistemas Microtaximetros</title>
</head>
<body>
<ol class="breadcrumb migas_pan">
	<li class="active"><i class="fa fa-home"></i><b> Inicio</b></li>
</ol>	
<?php
if( $_SESSION['tipo']=='Administrador' )
{ ?>
<!-- div que contiene la clase container-fluid -->
<div  class="container-fluid">
	<div class="row">
	<!-- div donde se lista la información de la campaña !-->
		<div class="process" id="info_campana">
			<div class="process-row nav nav-tabs">
				<div class="process-step">
					<button  type="button" class="btn btn-default btn-circle" ">
						<i class="fa fa-cogs fa-3x"></i>
					</button>
					<p><small># Componentes Agotados<br><button data-toggle="tooltip" data-placement="bottom" data-html="true" title="Presione para ver el listado de componentes agotados"  id="btn_ver_componentes_agotados" onclick="ver_componentes_agotados(this.value)" class="btn btn-default btn-xs"><b id="id_b_total_componentes_agodatos"></b>&nbsp;&nbsp;<i class="fa fa-external-link-alt"></i>&nbsp;</button></small></p>
				</div>
				<div class="process-step">
					<button type="button" class="btn btn-default btn-circle">
						<i class="fa fa-exclamation-triangle fa-3x"></i>
					</button>
					<p><small># Ordenes Pendientes<br><button id="btn_ver_ordenes_pendientes" data-placement="bottom" data-toggle="tooltip" data-html="true" title="Presione para ver el listado de ordenes pendientes" class="btn btn-default btn-xs" onclick="ver_ordenes_pendientes(this.value)"><b id="id_b_total_ordenes_pendientes"></b>&nbsp;&nbsp;<i class="fa fa-external-link-alt"></i>&nbsp;</button></small></p>
				</div>

			</div>
		</div>
	</div>
	<div class="row">
		<center><img style="width: 200px;opacity: 0.4;" src="../public/img/logo_micro.png" class="logoPlataforma"></center>
	</div>
</div>
<!-- final fiv que contiene la clase container fluid -->

<!-- modal donde se observa el listado de componentes que tienen las unidades agotadas -->	  
<div class="modal fade" id="modal_componentes_agotados" data-backdrop='static' data-keyboard='false' role="dialog">
	<div class="modal-dialog modal-lg">
	    <div class="modal-content">
			<div class="modal-header">
			    <button type="button" class="close" onclick="cerrar_modal_componentes_agotados()">&times;</button>
			    <div class="modal-title">
		        	<span class="alert alert-info">
		        		<img width="30px" src="../public/imagenes/exclamation.png" >
		 				<span  style="font-size: 17px;"><b>Componentes Agotados</b></span>
		        	</span>	
		        </div>
			</div>
			<div class="modal-body">
				<div class="table-responsive">
					<table id="tbl_componentes_agotados" style="width: 99%; text-align: center;" class="table table-striped table-bordered">
		                <thead>
		                    <tr>
		                        <th>ID</th>
		                        <th>Nombre</th>
		                        <th>Referencia</th>
		                        <th># Unidades</th>
		                        <th>Cantidad Minima</th>
		                    </tr>
		                </thead>
		                <tbody style="font-size: 13px;">
		                </tbody>
		            </table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" onclick="cerrar_modal_componentes_agotados()"  class="btn btn-danger btn-md" ><i class="fa fa-times-circle"></i><b> Cerrar</b></button>
			</div>
	    </div>
	</div>
</div>
<!-- final modal donse muestran los componentes que estan agotados -->

<!-- modal donde se oberva el numero de ordenes qeu estan en estado pendiente -->	  
<div class="modal fade" id="modal_ordenes_pendientes" data-backdrop='static' data-keyboard='false' role="dialog">
	<div class="modal-dialog modal-lg">
	    <div class="modal-content">
			<div class="modal-header">
			    <button type="button" class="close" onclick="cerrar_modal_ordenes_pendientes()">&times;</button>
			    <div class="modal-title">
		        	<span class="alert alert-info">
		        		<img width="30px" src="../public/imagenes/exclamation.png" >
		 				<span  style="font-size: 17px;"><b>Ordenes Pendientes</b></span>
		        	</span>	
		        </div>
			</div>
			<div class="modal-body">
				<div class="table-responsive">
					<table id="tbl_ordenes_pendientes" style="width: 99%; text-align: center;" class="table table-striped table-bordered">
	                    <thead>
	                        <tr>
	                            <th>ID</th>
	                            <th>Técnico</th>
	                            <th>Producto</th>
	                            <th>Cantidad</th>
	                            <th>Fecha</th>
	                        </tr>
	                    </thead>
	                    <tbody style="font-size: 13px;">
	                    </tbody>
	                </table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" onclick="cerrar_modal_ordenes_pendientes()"  class="btn btn-danger btn-md" ><i class="fa fa-times-circle"></i><b> Cerrar</b></button>
			</div>
	    </div>
	</div>
</div>
<!-- final modal para cambiar clave de todo tipo de usuarios -->


<?php
}// final if donde entra cuando el usuario si es tipo administrador
	require "footer.php"; ?>
	<script src="js/inicio_admin.js"></script>
</body>
</html>
<?php 
 }// final else donde entra cuando la session id si esta definida 
ob_end_flush();
?>