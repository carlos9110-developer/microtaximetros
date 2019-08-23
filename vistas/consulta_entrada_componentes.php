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
	<li class="active"><i class="fa fa-dolly"></i><b> Consulta Entrada Componentes</b></li>
</ol>	
<?php
if( $_SESSION['tipo']=='Secretario' )
{ ?>
<!-- div que contiene la clase container-fluid -->
<div  class="container">
	<div class="row">
        
        <div class="col-lg-12 col-md-12 col-sm-12" id="div_contenido_tabla" >
            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label for="fecha_consulta">Fecha Consulta Entrada Componentes</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-calendar-alt"></i></span>
                    <input type="date"  name="fecha_consulta" id="fecha_consulta" placeholder="Elija la fecha de la consulta" value="<?php echo $fecha_actual;  ?>" class="form-control"  required/>
                    <span class="input-group-btn">
                        <a title='<b>Consultar Entradas</b>' data-html="true" data-toggle="tooltip" data-placement="bottom"  onclick="listar_entrada_componentes()" class="btn btn-primary"><span class="fa fa-search"></span></a>
                    </span>
                </div>
            </div>
            
            <div class="table-responsive col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <table id="tbl_entrada_componentes" style="width: 99%; text-align: center;" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Proveedor</th>
                            <th>Fecha</th>
                            <th>Total Compra</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 13px;">
                    </tbody>
                </table>
            </div>  
        </div>

	    <div class="col-lg-12 col-md-12 col-sm-12" id="div_contenido_formulario" style="display: none;"  >
            <div class="text-right">
                <button class="btn btn-md btn-danger" data-html="true" title="<b>Cerrar Formulario</b>" data-toggle="tooltip" data-placement="bottom"  type="button" onclick="cancelar_form()"><i class="fa fa-times-circle"></i></button>
            </div>
			<form  method="post" name="form_registro_entrada_componentes"  id="form_registro_entrada_componentes" >
                <div  style="margin-bottom: 30px;"> 
                	<span class="label label-default" style="font-size: 16px;" id="id_titulo_formulario"></span> 
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label for="proveedor">Proveedor</label>
                    <div class="input-group" title="Proveedor">
                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        <input  type="text" name="proveedor" id="proveedor" placeholder="Digite el nombre del proveedor" class="form-control"   required/>
                    </div>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label for="fecha">Fecha</label>
                    <div class="input-group" title="Fecha">
                        <span class="input-group-addon"><i class="fa fa-calendar-alt"></i></span>
                        <input type="date"  name="fecha" id="fecha" placeholder="Digite el nombre del proveedor" class="form-control"  required/>
                    </div>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label for="observacion">Observación</label>
                    <textarea class="form-control" placeholder="Digite la observación" name="observacion" id="observacion" cols="30" rows="2"></textarea>
                </div>
                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12" style="height: 250px;overflow-y: auto;">
                    <table style="font-size: 12px" id="tbl_detalles" class="table table-striped  table-condensed table-hover">
                        <thead style="background-color:#A9D0F5;">
                            <tr>
                               <th>Opciones</th>
                                <th>Componente</th>
                                <th>Cantidad</th>
                                <th>Precio Unidad</th>
                                <th>Subtotal</th> 
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>TOTAL</th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>
                                  <h4 id="h4_total_compra"></h4>
                                  <input type="hidden" name="input_total_compra" id="input_total_compra">
                                </th>
                            </tr>  
                        </tfoot>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </form>
		</div>
	</div>
    <!-- div que contiene la clase row -->
</div>
<!-- final fiv que contiene la clase container  -->
<?php
}// final if donde entra cuando el usuario si es tipo administrador
	require "footer.php"; ?>
	<script src="js/consulta_entrada_componentes.js"></script>
</body>
</html>
<?php 
 }// final else donde entra cuando la session id si esta definida 
ob_end_flush();
?>