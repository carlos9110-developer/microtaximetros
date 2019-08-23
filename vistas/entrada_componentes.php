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
	<li class="hidden-xs"><a href="inicio_admin"><i class="fa fa-home"></i> <b>Inicio</b></a></li>
	<li class="active"><i class="fa fa-dolly"></i><b> Entrada Componentes</b></li>
</ol>	
<?php
if( $_SESSION['tipo']=='Administrador' || $_SESSION['tipo']=='Secretario' )
{ ?>
<!-- div que contiene la clase container-fluid -->
<div  class="container">
	<div class="row">
        
        <div class="col-lg-12 col-md-12 col-sm-12" id="div_contenido_tabla" >
            <span class="label label-default" style="font-size: 16px;">Listado Entrada Componentes</span>
            <div class="text-right" style="margin-top: -25px;">
                <button style="font-weight: bold;" onclick="abrir_modal_registro_entrada_componentes()"  data-html="true" title="<b>Registrar Entrada Componentes</b>" data-toggle="tooltip" data-placement="bottom" type="button" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Registrar Entrada Componentes
                </button>
            </div>
            <div class="table-responsive">
                <table id="tbl_entrada_componentes" style="width: 99%; text-align: center;" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Empleado</th>
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
			<form  method="post" name="form_registro_entrada_componentes"  id="form_registro_entrada_componentes" >
                <input type="hidden" id="op" name="op" value="registro_entrada_componentes">
                <input type="hidden" id="id_registro_entrada_componentes" name="id_registro_entrada_componentes" value="vacio">
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
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <button id="btn_abrir_registro_componentes"  style="font-weight: bold;" title="Agregar Componente" onclick="abrir_agregar_componente()"  type="button" class="btn btn-primary btn-lg"><i class="fa fa-plus"></i> Agregar Componente</button>
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
                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" id="div_btn_submit_formulario">
                </div>
            </form>
		</div>

	</div>
    <!-- div que contiene la clase row -->

    <!-- modal donde donde se agregan detalles a la tabla  -->
    <div id="modal_detalles_entrada" class="modal fade" tabindex="-1" data-backdrop='static' data-keyboard='false' role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="margin-bottom: 35px;">
                    <button type="button" class="close" onclick="cerrar_modal_detalles_entrada()">&times;</button>
                    <div class="modal-title">
                        <span class="alert alert-success">
                            <img width="32px" src="../public/imagenes/add.png" >
                            <span  style="font-size: 18px;"><b>Agregar Componentes</b></span>
                        </span> 
                    </div>
                </div>
                <div class="modal-body">
                    <table id="tbl_componentes" class="table table-striped table-bordered table-condensed table-hover" style="width: 99%; text-align: center;">
                        <thead>
                            <tr>
                                <td>ID</td>
                                <td>Opciones</td>
                                <td>Nombre</td>
                                <td>Referencia</td>
                                <td># Unidades</td>
                            </tr>
                        </thead>
                        <tbody style="font-size: 12px;"> 
                        </tbody>
                    </table>
                    <p>&nbsp;</p>
                </div>
                <!-- final div que contiene la clase modal-body -->
                <div class="modal-footer">
                    <button onclick="cerrar_modal_detalles_entrada()" style="font-weight: bold;" class="btn btn-danger btn-md">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Final modal donde se desactiva un determinado usuario -->

</div>
<!-- final fiv que contiene la clase container  -->
<?php
}// final if donde entra cuando el usuario si es tipo administrador
	require "footer.php"; ?>
	<script src="js/entrada_componentes.js"></script>
</body>
</html>
<?php 
 }// final else donde entra cuando la session id si esta definida 
ob_end_flush();
?>