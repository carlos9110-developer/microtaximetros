<?php
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
	<li class="active"><i class="fa fa-cogs"></i><b> Inventario Componentes</b></li>
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
	    	<div class="text-right" style="margin-top: -35px;">
		        <button style="font-weight: bold;" onclick="abrir_modal_registro_componentes()"  data-html="true" title="<b>Registrar Componente</b>" data-toggle="tooltip" data-placement="bottom" type="button" class="btn btn-primary">
		            <i class="fas fa-plus"></i> Registrar Componente
		        </button>
		    </div>
	   		<div class="table-responsive">
	   			<form action="../controlador/componentes.php" method="post" target="_blank" id="form-excel">
					<input type="hidden" value="reporte_excel"  name="op" />
					<button style="color:green;" type="submit"  title="Descargar reporte en excel" class="btn btn-default btn-lg "><i class="fa fa-file-excel"></i></button>
				</form>
	            <table id="tbl_componentes" style="width: 99%; text-align: center;" class="table table-striped table-bordered">
	                <thead>
	                    <tr>
	                        <th>ID</th>
	                        <th>Nombre</th>
	                        <th>Referencia</th>
	                        <th># Unidades</th>
	                        <th>Precio Unidad</th>
	                        <th>Valor Total</th>
	                        <th></th>
	                    </tr>
	                </thead>
	                <tbody style="font-size: 13px;">
	                </tbody>
	            </table>
	        </div> 	
	    </div>

	    <div class="col-lg-6 col-md-6 col-sm-12" id="div_contenido_formulario" style="display: none;" >
			<form class="form-horizontal" method="post" name="form_registro_componentes"  id="form_registro_componentes" >
                <input type="hidden" id="op" name="op" value="registrar_editar_componentes">
                <input type="hidden" id="id_registro_componentes" name="id_registro_componentes" value="vacio">
                <div  style="margin-bottom: 30px;"> 
                	<span class="label label-default" style="font-size: 16px;" id="id_titulo_formulario"></span> 
                </div>
                <div class="form-group row">
                    <label for="input_nombre" class="col-sm-3 text-right control-label col-form-label">Nombre</label>
                    <div class="col-sm-9">
                    	<div class="input-group">
                        	<span class="input-group-addon"><i class="fa fa-cog"></i></span>
                            <input required type="text" name="input_nombre" class="form-control" id="input_nombre" placeholder="Digite el nombre">
                    	</div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="input_referencia" class="col-sm-3 text-right control-label col-form-label">Referencia</label>
                    <div class="col-sm-9">
                    	<div class="input-group">
                        	<span class="input-group-addon"><i class="fa fa-registered"></i></span>
                        	<input required type="text" class="form-control" name="input_referencia" id="input_referencia" placeholder="Digite la referencia">
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="input_cantidad_minima" class="col-sm-3 text-right control-label col-form-label">Cantidad Mínima</label>
                    <div class="col-sm-9">
                    	<div class="input-group">
                        	<span class="input-group-addon"><i class="fa fa-hashtag"></i></span>
                            <input  required type="number" name="input_cantidad_minima" class="form-control" id="input_cantidad_minima" placeholder="Digite la cantidad mínima">
                    	</div>
                    </div>
                </div>
                <div class="text-center" id="div_btn_submit_form_registro_componentes">
                    <button  title="Guardar Información"  type="submit" class="negrita btn btn-success"><i class="fa fa-save"></i> Guardar</button>
                    <button title="Cerrar Formulario"  onclick="cerrar_modal_registro_componentes()" type="button" class="negrita btn btn-danger"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                </div>
            </form>
		</div>

	</div>
</div>
<!-- final fiv que contiene la clase container  -->
<?php
}// final if donde entra cuando el usuario si es tipo administrador
	require "footer.php"; ?>
	<script src="js/componentes.js"></script>
</body>
</html>
<?php 
 }// final else donde entra cuando la session id si esta definida 
ob_end_flush();
?>