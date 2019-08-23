<!--=====================================
MENU
======================================-->
<nav class="navbar navbar-fixed-left navbar-minimal animate" role="navigation">
	<div class="navbar-toggler animate">
		<span class="menu-icon"></span>
	</div>
	<ul class="navbar-menu animate">
		<li class="nombre_en_menu">
			<a href="#about-us" class="animate hover_nombre_menu">
				<span class="class_name_user desc animate  nombre_usuario_plantilla"><?php echo $_SESSION['nombre']; ?></span>
			</a>
		</li>
		<?php 
		if($_SESSION['tipo']=='Administrador')
		{ ?>
		<li>
			<a href="inicio_admin" class="animate">
				<span class="desc animate"><b> Inicio </b></span>
				<span class="fa fa-home"></span>
			</a>	
		</li>
		<li>
			<a href="gestion_usuarios" class="animate">
				<span class="desc animate"><b> Gestión Usuarios </b></span>
				<span class="fa fa-users-cog"></span>
			</a>
		</li>
		<li>
			<a href="componentes" class="animate">
				<span class="desc animate"><b> Inventario Componentes </b></span>
				<span class="fa fa-cogs"></span>
			</a>
		</li>
		<li>
			<a href="entrada_componentes" class="animate">
				<span class="desc animate"><b> Entrada Componentes </b></span>
				<span class="fa fa-dolly"></span>
			</a>
		</li>
		<li>
			<a href="productos" class="animate">
				<span class="desc animate"><b> Registro Productos </b></span>
				<span class="fa fa-fax"></span>
			</a>
		</li>
		<li>
			<a href="salida_componentes" class="animate">
				<span class="desc animate"><b> Salida Componentes </b></span>
				<span class="fa fa-shipping-fast"></span>
			</a>
		</li>
		<li>
			<a href="ordenes_ensamblado" class="animate">
				<span class="desc animate"><b> Ordenes Ensamblado </b></span>
				<span class="fa fa-file-invoice"></span>
			</a>
		</li>
		<?php } 
		else if($_SESSION['tipo']=='Técnico')
		{ ?>
			<li>
				<a href="inicio_tecnico" class="animate">
					<span class="desc animate"><b> Inicio </b></span>
					<span class="fa fa-home"></span>
				</a>	
			</li>
			<li>
				<a href="entrada_componentes_tecnico" class="animate">
					<span class="desc animate"><b> Entrada Componentes </b></span>
					<span class="fa fa-dolly"></span>
				</a>
			</li>
			<li>
				<a href="salida_componentes_tecnico" class="animate">
					<span class="desc animate"><b> Salida Componentes </b></span>
					<span class="fa fa-shipping-fast"></span>
				</a>
			</li>
			<li>
				<a href="ordenes_ensamblado_tecnico" class="animate">
					<span class="desc animate"><b> Ordenes Ensamblado </b></span>
					<span class="fa fa-file-invoice"></span>
				</a>
			</li>
	<?php	}
		else if($_SESSION['tipo']=='Secretario')
		{ ?>
			<li>
				<a href="inicio_secretario" class="animate">
					<span class="desc animate"><b> Inicio </b></span>
					<span class="fa fa-home"></span>
				</a>	
			</li>
			<li>
				<a href="entrada_componentes_secretario" class="animate">
					<span class="desc animate"><b> Entrada Componentes </b></span>
					<span class="fa fa-dolly"></span>
				</a>
			</li>
			<li>
				<a href="consulta_entrada_componentes" class="animate">
					<span class="desc animate"><b> Consulta Entrada Componentes </b></span>
					<span class="fa fa-search"></span>
				</a>
			</li>
			<li>
				<a href="consulta_salida_componentes" class="animate">
					<span class="desc animate"><b> Consulta Salida Componentes </b></span>
					<span class="fa fa-shipping-fast"></span>
				</a>
			</li>
			<li>
				<a href="consulta_ordenes_ensamblado" class="animate">
					<span class="desc animate"><b> Consulta Ordenes Ensamblado </b></span>
					<span class="fa fa-file-invoice"></span>
				</a>
			</li>
			<li>
				<a href="componentes_secretario" class="animate">
					<span class="desc animate"><b> Inventario Componentes </b></span>
					<span class="fa fa-cogs"></span>
				</a>
			</li>
			
	<?php
		} 
	?>
	</ul>
</nav>
<!--=====================================
MENU
======================================-->

<nav class="navbar navbar-default menu2">
  	<div class="container-fluid">
    	<div class="navbar-header menu_info">
     	 	<a class="navbar-brand nombre_en_menu2" href="#"><span class="class_name_user nombre_usuario_plantilla"><?php echo $_SESSION['nombre']; ?></span></a>
     	 	<?php
 	 			echo 
     	 		'
     	 			<button data-html="true" onClick="abrir_actualizar_datos_usuario('.$_SESSION['id'].')" type="button" class="btn-actualizar_datos" data-toggle="tooltip" title="<b>Actualizar Datos</b>" data-placement="bottom">
		     	 		<i class="fa fa-edit"></i>
		     	 	</button>
		     	 	<button data-html="true" onClick="abrir_cambiar_clave()" type="button" class="btn-cambio-clave" data-toggle="tooltip" title="<b>Cambiar Clave</b>" data-placement="bottom">
		     	 		<i class="fa fa-key"></i>
		     	 	</button>
     	 		';
     	 	 ?>
     	 	<button onclick="cerrar_sesion()" type="button" class="btn-salir" data-toggle="tooltip" title="Cerrar sesión" data-placement="bottom">
     	 		<i class="fa fa-power-off"></i>
     	 	</button>
    	</div>
  	</div>
</nav>

<!-- aca empiezan los modales que se utilizan en este archivo -->


<!-- modal para cambiar clave de todo tipo de usuarios -->	  
<div class="modal fade" id="modal_cambiar_clave" data-backdrop='static' data-keyboard='false' role="dialog">
	<div class="modal-dialog modal-sm">
	    <div class="modal-content">
			<div class="modal-header">
			    <button type="button" class="close" onclick="cerrar_cambiar_clave()">&times;</button>
			    <div class="modal-title">
		        	<span class="alert alert-info">
		        		<img width="30px" src="../public/imagenes/padlock.png" >
		 				<span  style="font-size: 17px;"><b>Cambiar Contraseña</b></span>
		        	</span>	
		        </div>
			</div>
			<div class="modal-body">
					<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label for="input_clave_actu_1">Contraseña Actual</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                            <input type="password"   name="input_clave_actu_1" id="input_clave_actu_1"  class="form-control"  required/>
                        </div>
                    </div>
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label for="input_clave_actu_2">Nueva Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                            <input type="password"   name="input_clave_actu_2" id="input_clave_actu_2"  class="form-control"  required/>
                        </div>
                    </div>
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label for="input_clave_actu_3">Confirmar Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                            <input type="password"   name="input_clave_actu_3" id="input_clave_actu_3"  class="form-control"  required/>
                        </div>
                    </div>
                    <p>&nbsp;</p>
			</div>
			<div class="modal-footer">
				<div class="btn-group" id="div_btn_submit_formulario_actualizar_clave_usuario">
					<button type="button" onclick="actualizar_clave_personal()"  class="btn  btn-primary btn-md"><i class="fa fa-key"></i><b> Actualizar Clave</b></button>
					<button type="button" onclick="cerrar_cambiar_clave()"  class="btn btn-danger btn-md" ><i class="fa fa-times-circle"></i><b> Cerrar</b></button>
				</div>
			</div>
	    </div>
	</div>
</div>
<!-- final modal para cambiar clave de todo tipo de usuarios -->


<!-- modal que se utiliza para actulizar los datos de la tabla usuarios --> 
<div class="modal fade" id="modal_actualizar_datos_usuario" data-backdrop='static' data-keyboard='false' role="dialog">
	<div class="modal-dialog modal-sm">
	    <div class="modal-content">
			<div class="modal-header">
			    <button type="button" class="close" onclick="cerrar_modal_actualizar_datos_usuario()">&times;</button>
			    <div class="modal-title">
		        	<span class="alert alert-warning">
		        		<img width="30px" src="../public/imagenes/edit_datos.png" >
		 				<span  style="font-size: 15px;"><b>Actualizar Datos</b></span>
		        	</span>	
		        </div>
			</div>
			<div class="modal-body">
					<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label for="input_cedula_edit">Cédula</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar-alt"></i></span>
                            <input type="text"   name="input_cedula_edit" id="input_cedula_edit"  class="form-control"  required/>
                        </div>
                    </div>
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label for="input_nombre_edit">Nombre</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar-alt"></i></span>
                            <input type="text"   name="input_nombre_edit" id="input_nombre_edit"  class="form-control"  required/>
                        </div>
                    </div>
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label for="input_apellidos_edit">Apellidos</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar-alt"></i></span>
                            <input type="text"   name="input_apellidos_edit" id="input_apellidos_edit"  class="form-control"  required/>
                        </div>
                    </div>
				<p>&nbsp;</p>
			</div>
			<div class="modal-footer">
				<div class="btn-group" id="div_btn_submit_formulario_actualizar_datos_edit">
					<button type="button" onclick="actualizar_datos_usuarios()"   class="btn  btn-warning btn-md"><i class="fa fa-edit"></i><b> Actualizar</b></button>
					<button type="button" onclick="cerrar_modal_actualizar_datos_usuario()"  class="btn btn-danger btn-md" ><i class="fa fa-times-circle"></i><b> Cerrar</b></button>	
				</div>
			</div>
	    </div>
	</div>
</div>
<!-- Final modal que se utiliza para actualizar los datos de la tabla usuarios --> 
