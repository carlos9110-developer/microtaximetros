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
	<li class="active"><i class="fa fa-fax"></i><b> Registro Productos</b></li>
</ol>	
<?php
if( $_SESSION['tipo']=='Administrador' )
{ ?>
<!-- div que contiene la clase container-fluid -->
<div  class="container">
	<div class="row">
        
        <div class="col-lg-12 col-md-12 col-sm-12" id="div_contenido_tabla" >
            <div class="text-left">
                <button style="font-weight: bold; margin-bottom: 10px;"  data-html="true" title="<b>Valor Total Inventario</b>" data-toggle="tooltip" data-placement="bottom" type="button" class="btn btn-default">
                    $ <span id="id_span_valor_total"></span>
                </button>
            </div>
            <div class="text-right" style="margin-top: -25px;">
                <button style="font-weight: bold;" onclick="abrir_modal_registro_productos()"  data-html="true" title="<b>Presione Para Registrar Un Producto</b>" data-toggle="tooltip" data-placement="bottom" type="button" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Registrar Producto
                </button>
            </div>
            <div class="table-responsive">
                <table id="tbl_productos" style="width: 99%; text-align: center;" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Referencia</th>
                            <th># Unidades</th>
                            <th>Precio Unidad</th>
                            <th>Valor Total</th>
                            <th>Estado</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 13px;">
                    </tbody>
                </table>
            </div>  
        </div>

	    <div class="col-lg-12 col-md-12 col-sm-12" id="div_contenido_formulario" style="display: none;"  >
			<form  method="post" name="form_registro_productos"  id="form_registro_productos" >
                <input type="hidden" id="op" name="op" value="registro_productos">
                <input type="hidden" id="id_registro_productos" name="id_registro_productos" value="vacio">
                <div  style="margin-bottom: 30px;"> 
                	<span class="label label-default" style="font-size: 16px;" id="id_titulo_formulario"></span> 
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label for="nombre">Nombre</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-fax"></i></span>
                        <input type="text" name="nombre" id="nombre" placeholder="Digite el nombre del producto" class="form-control"   required/>
                    </div>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label for="referencia">Referencia</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-registered"></i></span>
                        <input type="text" name="referencia" id="referencia" placeholder="Digite la referencia" class="form-control"   required/>
                    </div>
                </div>

                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <button id="btn_abrir_registro_componentes"  style="font-weight: bold;" title="Agregar Componente" onclick="abrir_agregar_componente()"  type="button" class="btn btn-primary btn-lg"><i class="fa fa-plus"></i> Agregar Componente</button>
                </div>
                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12" style="height: 250px;overflow-y: auto;">
                    <table style="font-size: 12px" id="tbl_detalles" class="table table-striped  table-condensed table-hover">
                        <thead style="background-color:#A9D0F5;">
                            <th>Opciones</th>
                            <th>Componente</th>
                            <th>Cantidad</th>
                        </thead>
                        <tfoot style="background-color:#A9D0F5;">
                            <th>Opciones</th>
                            <th>Componente</th>
                            <th>Cantidad</th>  
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
    <div id="modal_detalles_producto" class="modal fade" tabindex="-1" data-backdrop='static' data-keyboard='false' role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="margin-bottom: 35px;">
                    <button type="button" class="close" onclick="cerrar_modal_detalles_producto()">&times;</button>
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
                    <button onclick="cerrar_modal_detalles_producto()" style="font-weight: bold;" class="btn btn-danger btn-md">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Final modal donde se desactiva un determinado usuario -->

    <!-- modal donde se desactiva un determinado producto  -->
    <div id="modal_desactivar_producto" class="modal fade" tabindex="-1" data-backdrop='static' data-keyboard='false' role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header" style="margin-bottom: 35px;">
                    <button type="button" class="close" onclick="cerrar_modal_desactivar_producto()">&times;</button>
                    <div class="modal-title">
                        <span class="alert alert-danger">
                            <img width="32px" src="../public/imagenes/desactivar.png" >
                            <span  style="font-size: 18px;"><b>Desactivar Producto</b></span>
                        </span> 
                    </div>
                </div>
                <div class="modal-body">
                    <p class="text-danger text-center" style="font-size: 18px;">
                        Si esta seguro de desactivar el producto presione el botón <b>ACEPTAR</b>
                    </p>
                    <div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12   text-center" >
                        <button onclick="desactivar_producto(this.value)" id="btn_desactivar_producto"  data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Presione para desactivar el producto</b>" class="btn btn-default btn-md" type="button" ><b>ACEPTAR</b></button>
                        <button onclick="cerrar_modal_desactivar_producto()" data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Presione para cancelar la acción</b>" class="btn btn-default btn-md" type="button" ><b>CANCELAR</b></button>
                    </div>
                    <p>&nbsp;</p>
                </div>
                <!-- final div que contiene la clase modal-body -->
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
    <!--Final modal donde se desactiva un determinado producto -->

    <!-- modal donde se activa un determinado prodcuto  -->
    <div id="modal_activar_producto" class="modal fade" tabindex="-1" data-backdrop='static' data-keyboard='false' role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header" style="margin-bottom: 35px;">
                    <button type="button" class="close" onclick="cerrar_modal_activar_producto()">&times;</button>
                    <div class="modal-title">
                        <span class="alert alert-success">
                            <img width="32px" src="../public/imagenes/checked.png" >
                            <span  style="font-size: 18px;"><b>Activar Producto</b></span>
                        </span> 
                    </div>
                </div>
                <div class="modal-body">
                    <p class="text-success text-center" style="font-size: 18px;">
                        Si esta seguro de activar el producto presione el botón <b>ACEPTAR</b>
                    </p>
                    <div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12   text-center" >
                        <button onclick="activar_producto(this.value)" id="btn_activar_producto"  data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Presione para activar el producto</b>" class="btn btn-default btn-md" type="button" ><b>ACEPTAR</b></button>
                        <button onclick="cerrar_modal_activar_producto()" data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Presione para cancelar la acción</b>" class="btn btn-default btn-md" type="button" ><b>CANCELAR</b></button>
                    </div>
                    <p>&nbsp;</p>
                </div>
                <!-- final div que contiene la clase modal-body -->
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
    <!--Final modal donde se activa un detemrinado producto -->

</div>
<!-- final fiv que contiene la clase container  -->
<?php
}// final if donde entra cuando el usuario si es tipo administrador
	require "footer.php"; ?>
	<script src="js/productos.js"></script>
</body>
</html>
<?php 
 }// final else donde entra cuando la session id si esta definida 
ob_end_flush();
?>