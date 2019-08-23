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
</head>
<body>
<ol class="breadcrumb migas_pan">
	<li class="hidden-xs"><a href="inicio_secretario"><i class="fa fa-home"></i> <b>Inicio</b></a></li>
	<li class="active"><i class="fa fa-cogs"></i><b> Inventario Componentes</b></li>
</ol>	
<?php
if( $_SESSION['tipo']=='Secretario' )
{ ?>
<!-- div que contiene la clase container-fluid -->
<div  class="container">
	<div class="row">

	    <div class="col-lg-12 col-md-12 col-sm-12" id="div_contenido_tabla" >
	    	<div class="text-left">
		        <span class="label label-default" style="font-size: 16px;">Listado Componentes</span>
		    </div>
	    	<div class="text-right" style="margin-top: -35px;">
		        <button style="font-weight: bold; margin-bottom: 10px;"  data-html="true" title="<b>Valor Total Inventario</b>" data-toggle="tooltip" data-placement="bottom" type="button" class="btn btn-default">
		            $ <span id="id_span_valor_total"></span>
		        </button>
		    </div>
	   		<div class="table-responsive">
	            <table id="tbl_componentes" style="width: 99%; text-align: center;" class="table table-striped table-bordered">
	                <thead>
	                    <tr>
	                        <th>ID</th>
	                        <th>Nombre</th>
	                        <th>Referencia</th>
	                        <th># Unidades</th>
	                        <th>Precio Unidad</th>
	                        <th>Valor Total</th>
	                    </tr>
	                </thead>
	                <tbody style="font-size: 13px;">
	                </tbody>
	            </table>
	        </div> 	
	    </div>

	</div>
</div>
<!-- final fiv que contiene la clase container  -->
<?php
}// final if donde entra cuando el usuario si es tipo administrador
	require "footer.php"; ?>
	<script src="js/componentes_secretario.js"></script>
</body>
</html>
<?php 
 }// final else donde entra cuando la session id si esta definida 
ob_end_flush();
?>