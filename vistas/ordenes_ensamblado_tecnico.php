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
	<li class="hidden-xs"><a href="inicio_tecnico"><i class="fa fa-home"></i> <b>Inicio</b></a></li>
	<li class="active"><i class="fa fa-file-invoice"></i><b> Ordenes de Ensamblado</b></li>
</ol>	
<?php
if( $_SESSION['tipo']=='Técnico' )
{ ?>
<!-- div que contiene la clase container-fluid -->
<div  class="container">
	<div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12" id="div_contenido_tabla" >
            <span class="label label-default" style="font-size: 16px;">Listado Ordenes de Ensamblado</span>
            <div class="text-right" style="margin-top: -25px;">
                <button style="font-weight: bold;" onclick="abrir_modal_registro_orden_ensamblado()"  data-html="true" title="<b>Registrar Orden Ensamblado</b>" data-toggle="tooltip" data-placement="bottom" type="button" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Registrar Orden Ensamblado
                </button>
            </div>
            <div class="table-responsive">
                <table id="tbl_ordenes_ensamblado" style="width: 99%; text-align: center;" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Fecha</th>
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
			<form  method="post" name="form_registro_orden_ensamblado"  id="form_registro_orden_ensamblado" >
                <input type="hidden" id="op" name="op" value="registro_orden_ensamblado">
                <div  style="margin-bottom: 30px;"> 
                	<span class="label label-default" style="font-size: 16px;" id="id_titulo_formulario">Registro Orden Ensamblado</span> 
                </div>
                <div class="form-group col-lg-5 col-md-5 col-sm-12 col-xs-12">
                    <label for="select_productos">Producto</label>
                    <div class="input-group" title="Productos">
                        <span class="input-group-addon"><i class="fa fa-fax"></i></span>
                       <select  required class="select2 form-control custom-select" id="select_productos" name="select_productos" style="width: 100%; height:40px;"></select>
                    </div>
                </div>
                <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <label for="input_cantidad">Cantidad</label>
                    <div class="input-group" title="Fecha">
                        <span class="input-group-addon"><i class="fa fa-hashtag"></i></span>
                        <input type="number" min="1"  name="input_cantidad" id="input_cantidad" placeholder="Cantidad de Productos" class="form-control"  required/>
                    </div>
                </div>
                <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <label for="input_fecha">Fecha</label>
                    <div class="input-group" title="Fecha">
                        <span class="input-group-addon"><i class="fa fa-calendar-alt"></i></span>
                        <input type="date"   name="input_fecha" id="input_fecha" placeholder="Elija la fecha de la orden" class="form-control"  required/>
                    </div>
                </div>
                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" id="div_btn_submit_formulario">
                    <button  class="negrita btn btn-md btn-primary"  type="button" onclick="ver_componentes_necesarios()"><i class="fa fa-search"></i> Ver Componentes Necesarios</button>
                    <button class="negrita btn btn-md btn-success"   type="submit"><i class="fa fa-save"></i> Confirmar Orden</button>
                </div>
                <div id="div_tbl_detalles" class="col-lg-12 col-sm-12 col-md-12 col-xs-12" style="height: 250px;overflow-y: auto;display: none;">
                    <table style="font-size: 12px;" id="tbl_detalles" class="table table-striped  table-condensed table-hover">
                        <thead style="background-color:#A9D0F5;">
                            <tr>
                                <th>Componente</th>
                                <th># Unidades Necesarias</th>
                                <th># Unidades Disponibles</th> 
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </form>
		</div>
	</div>
    <!-- div que contiene la clase row -->

    <!-- modal que se utiliza para actualizar la fecha de una detemrinada orden de ensamblado --> 
    <div class="modal fade" id="modal_actualizar_fecha" data-backdrop='static' data-keyboard='false' role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" onclick="cerrar_modal_actualizar_fecha()">&times;</button>
                    <div class="modal-title">
                        <span class="alert alert-info">
                            <img width="30px" src="../public/imagenes/calendar.png" >
                            <span  style="font-size: 15px;"><b>Actualizar Fecha</b></span>
                        </span> 
                    </div>
                </div>
                <div class="modal-body">
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label for="input_fecha_actualizar">Fecha</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar-alt"></i></span>
                            <input type="hidden" id="input_id_actualizar_fecha_orden" name="input_id_actualizar_fecha_orden">
                            <input type="date"   name="input_fecha_actualizar" id="input_fecha_actualizar"  class="form-control"  required/>
                        </div>
                    </div>
                    <p>&nbsp;</p>
                </div>
                <div class="modal-footer">
                    <div class="btn-group">
                        <button type="button" onclick="actualizar_fecha()"   class="btn  btn-primary btn-md"><i class="fa fa-save"></i><b> Corregir Fecha</b></button>
                        <button type="button" onclick="cerrar_modal_actualizar_fecha()"   class="btn  btn-danger  btn-md" ><i class="fa fa-times-circle"></i><b> Cerrar</b></button>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
<!-- final fiv que contiene la clase container  -->
<?php
}// final if donde entra cuando el usuario si es tipo administrador
	require "footer.php"; ?>
	<script src="js/ordenes_ensamblado_tecnico.js"></script>
</body>
</html>
<?php 
 }// final else donde entra cuando la session id si esta definida 
ob_end_flush();
?>