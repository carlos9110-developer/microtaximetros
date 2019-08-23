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
	<li class="active"><i class="fa fa-users"></i><b> Gestión Usuarios</b></li>
</ol>	
<?php
if( $_SESSION['tipo']=='Administrador' )
{ ?>
<!-- div que contiene la clase container-fluid -->
<div  class="container">
	<div class="row">

	    <div class="col-lg-12 col-md-12 col-sm-12" id="div_contenido_tabla">
	    	<span class="label label-default" style="font-size: 16px;">Listado Usuarios</span>
	    	<!--<h4 class="text-info" style="font-weight: bold;">Listado Usuarios</h4>-->
	    	<div class="text-right" style="margin-top: -25px;">
		        <button style="font-weight: bold;" onclick="abrir_modal_registro_usuarios()"  data-html="true" title="<b>Registrar Usuario</b>" data-toggle="tooltip" data-placement="bottom" type="button" class="btn btn-primary">
		            <i class="fas fa-plus"></i> Registrar Usuario
		        </button>
		    </div>
	   		<div class="table-responsive">
	            <table id="tbl_usuarios" style="width: 99%; text-align: center;" class="table table-striped table-bordered">
	                <thead>
	                    <tr>
	                        <th>ID</th>
	                        <th>Usuario</th>
	                        <th>Tipo Usuario</th>
	                        <th>Estado</th>
	                        <th></th>
	                    </tr>
	                </thead>
	                <tbody style="font-size: 13px;">
	                </tbody>
	            </table>
	        </div> 	
	    </div>

		<div class="col-lg-6 col-md-6 col-sm-12" id="div_contenido_formulario" style="display: none;">
			<form class="form-horizontal" method="post" name="form_registro_usuarios"  id="form_registro_usuarios" >
                <input type="hidden" id="op" name="op" value="registrar_editar_usuarios">
                <input type="hidden" id="id_registro_usuarios" name="id_registro_usuarios" value="vacio">
	            <div  style="margin-bottom: 30px;"> 
	            	<span class="label label-default" style="font-size: 16px;" id="id_titulo_formulario"></span> 
	            </div>
                <div class="form-group row">
                    <label for="input_cedula" class="col-sm-3 text-right control-label col-form-label">Cédula</label>
                    <div class="col-sm-9">
                    	<div class="input-group">
                        	<span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
                            <input  required type="number" name="input_cedula" class="form-control" id="input_cedula" placeholder="Digite la cédula">
                    	</div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="input_nombre" class="col-sm-3 text-right control-label col-form-label">Nombre</label>
                    <div class="col-sm-9">
                    	<div class="input-group">
                        	<span class="input-group-addon"><i class="fa fa-user"></i></span>
                        	<input  required type="text" class="form-control" name="input_nombre" id="input_nombre" placeholder="Digite el nombre">
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="input_apellidos" class="col-sm-3 text-right control-label col-form-label">Apellidos</label>
                    <div class="col-sm-9">
                    	<div class="input-group">
                        	<span class="input-group-addon"><i class="fa fa-user"></i></span>
                        	<input  required type="text" name="input_apellidos" class="form-control" id="input_apellidos" placeholder="Digite los apellidos">
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="select_tipo_usuario" class="col-sm-3 text-right control-label col-form-label">Tipo Usuario</label>
                    <div class="col-sm-9">
                    	<div class="input-group">
                        	<span class="input-group-addon"><i class="fa fa-chalkboard-teacher"></i></span>
                        	<select required class="select2 form-control custom-select" id="select_tipo_usuario" name="select_tipo_usuario" style="width: 100%; height:40px;"></select>
                        </div>
                    </div>
                </div>
                <div class="text-center" id="div_btn_submit_form_registro_usuarios">
                    <button  title="Guardar Información"  type="submit" class="negrita btn btn-success"><i class="fa fa-save"></i> Guardar</button>
                    <button title="Cerrar Formulario"  onclick="cerrar_modal_registro_usuarios()" type="button" class="negrita btn btn-danger"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                </div>
            </form>
		</div>


	</div>

	<!-- modal donde se confirma que se va restablecer la contraseña de una determinada persona -->
	<div id="modal_restablecer_clave" class="modal fade" tabindex="-1" data-backdrop='static' data-keyboard='false' role="dialog">
	  	<div class="modal-dialog">
		    <div class="modal-content">
		    	<div class="modal-header" style="margin-bottom: 35px;">
			        <button type="button" class="close" onclick="cerrar_modal_restablecer_clave()">&times;</button>
			    	<div class="modal-title">
			        	<span class="alert alert-info">
			        		<img width="32px" src="../public/imagenes/reset.png" >
	         				<span  style="font-size: 18px;"><b>Restablecer Contraseña</b></span>
			        	</span>	
			        </div>
			    </div>
		      	<div class="modal-body">
		      		<p class="text-info text-center" style="font-size: 18px;">
		      			Si esta seguro de restablecer la contraseña presione el botón <b>ACEPTAR</b>
		      		</p>
		      		<div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12   text-center" >
						<button onclick="restablecer_clave(this.value)" id="btn_restablecer_clave"  data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Presione para restablecer la contraseña</b>" class="btn btn-default btn-md" type="button" ><b>ACEPTAR</b></button>
						<button onclick="cerrar_modal_restablecer_clave()" data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Presione para cancelar la acción</b>" class="btn btn-default btn-md" type="button" ><b>CANCELAR</b></button>
				  	</div>
				  	<p>&nbsp;</p>
		      	</div>
		     	<!-- final div que contiene la clase modal-body -->
		      	<div class="modal-footer">
		     	</div>
		    </div>
		</div>
	</div>

	<!-- modal donde se desactiva un determinado usuario  -->
	<div id="modal_desactivar_usuario" class="modal fade" tabindex="-1" data-backdrop='static' data-keyboard='false' role="dialog">
	  	<div class="modal-dialog modal-sm">
		    <div class="modal-content">
		    	<div class="modal-header" style="margin-bottom: 35px;">
			        <button type="button" class="close" onclick="cerrar_modal_desactivar_usuario()">&times;</button>
			    	<div class="modal-title">
			        	<span class="alert alert-danger">
			        		<img width="32px" src="../public/imagenes/desactivar.png" >
	         				<span  style="font-size: 18px;"><b>Desactivar Usuario</b></span>
			        	</span>	
			        </div>
			    </div>
		      	<div class="modal-body">
		      		<p class="text-danger text-center" style="font-size: 18px;">
		      			Si esta seguro de desactivar el usuario presione el botón <b>ACEPTAR</b>
		      		</p>
		      		<div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12   text-center" >
						<button onclick="desactivar_usuario(this.value)" id="btn_desactivar_usuario"  data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Presione para desactivar el usuario</b>" class="btn btn-default btn-md" type="button" ><b>ACEPTAR</b></button>
						<button onclick="cerrar_modal_desactivar_usuario()" data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Presione para cancelar la acción</b>" class="btn btn-default btn-md" type="button" ><b>CANCELAR</b></button>
				  	</div>
				  	<p>&nbsp;</p>
		      	</div>
		     	<!-- final div que contiene la clase modal-body -->
		      	<div class="modal-footer">
		     	</div>
		    </div>
		</div>
	</div>
	<!--Final modal donde se desactiva un determinado usuario -->

	<!-- modal donde se activa un determinado usuario  -->
	<div id="modal_activar_usuario" class="modal fade" tabindex="-1" data-backdrop='static' data-keyboard='false' role="dialog">
	  	<div class="modal-dialog modal-sm">
		    <div class="modal-content">
		    	<div class="modal-header" style="margin-bottom: 35px;">
			        <button type="button" class="close" onclick="cerrar_modal_activar_usuario()">&times;</button>
			    	<div class="modal-title">
			        	<span class="alert alert-success">
			        		<img width="32px" src="../public/imagenes/checked.png" >
	         				<span  style="font-size: 18px;"><b>Activar Usuario</b></span>
			        	</span>	
			        </div>
			    </div>
		      	<div class="modal-body">
		      		<p class="text-success text-center" style="font-size: 18px;">
		      			Si esta seguro de activar el usuario presione el botón <b>ACEPTAR</b>
		      		</p>
		      		<div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12   text-center" >
						<button onclick="activar_usuario(this.value)" id="btn_activar_usuario"  data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Presione para activar el usuario</b>" class="btn btn-default btn-md" type="button" ><b>ACEPTAR</b></button>
						<button onclick="cerrar_modal_activar_usuario()" data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Presione para cancelar la acción</b>" class="btn btn-default btn-md" type="button" ><b>CANCELAR</b></button>
				  	</div>
				  	<p>&nbsp;</p>
		      	</div>
		     	<!-- final div que contiene la clase modal-body -->
		      	<div class="modal-footer">
		     	</div>
		    </div>
		</div>
	</div>
	<!--Final modal donde se activa un detemrinado usuario -->

</div>
<!-- final fiv que contiene la clase container  -->
<?php
}// final if donde entra cuando el usuario si es tipo administrador
	require "footer.php"; ?>
	<script src="js/gestion_usuarios.js"></script>
</body>
</html>
<?php 
 }// final else donde entra cuando la session id si esta definida 
ob_end_flush();
?>