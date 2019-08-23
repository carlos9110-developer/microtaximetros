<?php
ob_start();
session_start();
date_default_timezone_set("America/Bogota");
$fecha_actual =  date('Y-m-d'); // formato fecha colombiana
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
	<li class="active"><i class="fa fa-file-invoice"></i><b> Consulta Ordenes Ensamblado</b></li>
</ol>	
<?php
if( $_SESSION['tipo']=='Secretario' )
{ ?>
<!-- div que contiene la clase container-fluid -->
<div  class="container">
	<div class="row">
        
        <div class="col-lg-12 col-md-12 col-sm-12" id="div_contenido_tabla" >
            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label for="fecha_consulta">Fecha Consulta Ordenes Ensamblado</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-calendar-alt"></i></span>
                    <input type="date"  name="fecha_consulta" id="fecha_consulta" placeholder="Elija la fecha de la consulta" value="<?php echo $fecha_actual;  ?>" class="form-control"  required/>
                    <span class="input-group-btn">
                        <a title='<b>Consultar Ordenes</b>' data-html="true" data-toggle="tooltip" data-placement="bottom"  onclick="listar_ordenes()" class="btn btn-primary"><span class="fa fa-search"></span></a>
                    </span>
                </div>
            </div>
            
            <div class="table-responsive col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <table id="tbl_ordenes_ensamblado" style="width: 99%; text-align: center;" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Técnico</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Valor Unidad</th>
                            <th>Valor Total</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 13px;">
                    </tbody>
                </table>
            </div>  
        </div>

	</div>
    <!-- div que contiene la clase row -->
</div>
<!-- final fiv que contiene la clase container  -->
<?php
}// final if donde entra cuando el usuario si es tipo administrador
	require "footer.php"; ?>
	<script src="js/consulta_ordenes_ensamblado.js"></script>
</body>
</html>
<?php 
 }// final else donde entra cuando la session id si esta definida 
ob_end_flush();
?>