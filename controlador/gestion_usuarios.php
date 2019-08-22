<?php
if (strlen(session_id()) < 1)
{
  session_start();
}
date_default_timezone_set("America/Bogota");
$fecha_hora_actual =  date('Y-m-d H:i:s'); // formato fecha colombiana
require_once "../modelo/Gestion_usuarios.php";
$obj_estion_usuarios =new Gestion_usuarios();
if(isset($_GET['op']))
{
	switch ($_GET['op'])
	{
		/* **** L **** L **** L **** L **** L **** L **** L **** L **** L **** L **** L **** L **** L **** L **** L **** L **** L **** L **** L **** L */
		case 'listar':
			$rspta= $obj_estion_usuarios->listar();
			//Vamos a declarar un array
			$data= Array();
			while ($reg=$rspta->fetch_object())
			{
				$data[]=array(
				"0"=>$reg->id,
				"1"=>$reg->nombre_completo.'<br/>cc '.$reg->cedula,
				"2"=>$reg->tipo,
				"3"=>($reg->estado=='1') ? 'Activo' : 'Inactivo',
				"4"=>'<div class="btn-group">
						<button onclick="abrir_actualizar_usuario('.$reg->id.')" data-html="true" title="<b>Actualizar Información</b>" data-toggle="tooltip" data-placement="bottom" type="button" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></button>'.
						(( $reg->estado=='0' ) ? '<button onclick="activar_desactivar_usuario('.$reg->id.',1)" data-html="true" title="<b>Activar Usuario</b>" data-toggle="tooltip" data-placement="bottom"  class="btn btn-sm btn-success"><i class="fas fa-paper-plane"></i></button>' : '<button data-html="true" title="<b>Desactivar Usuario</b>" data-toggle="tooltip" data-placement="bottom" onclick="activar_desactivar_usuario('.$reg->id.',0)" class="btn btn-sm btn-danger"><i class="fas fa-paper-plane"></i></button>').'
						<button data-html="true" title="<b>Restablecer Contraseña</b>" data-toggle="tooltip" data-placement="bottom" onclick="abrir_restablecer_contrasena('.$reg->id.')" class="btn btn-sm btn-default"><i class="fas fa-sync-alt"></i></button> 
					</div>'
				);
			}
			$results = array(
				"sEcho"=>1, //Información para el datatables
				"iTotalRecords"=>count($data), //enviamos el total registros al datatable
				"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
				"aaData"=>$data
			);
			echo  json_encode($results);
		break;
	}
}
else if(isset($_POST['op']))
{
	switch ($_POST['op'])
	{
		/* **** A **** A **** A **** A **** A **** A **** A **** A **** A **** A **** A **** A **** A **** A **** A **** A **** A **** A **** A **** A */
		//case donde se activa o desactiva un determinado usuario
		case 'activar_desactivar_usuario':
			$guia_accion = "";
			if($_POST['guia']=='1')
			{
				$guia_accion = 4;
			}
			else
			{
				$guia_accion = 3;
			}
			echo $obj_estion_usuarios->activar_desactivar_usuario($_POST['id'],$_POST['guia'],$fecha_hora_actual,$guia_accion,$_SESSION['id']);
		break;

		// case donde se actualizan los datos de un determinado usuario
		case 'actualizar_datos_usuario':
			$cedula      = trim($_POST['input_cedula_edit']);
			$nombre      = trim($_POST['input_nombre_edit']);
			$apellidos   = trim($_POST['input_apellidos_edit']);
			$cedula 	 = limpiar_cadena($cedula);
			$nombre 	 = limpiar_cadena($nombre);
			$apellidos 	 = limpiar_cadena($apellidos);

			if($obj_estion_usuarios->actualizacion_datos($_SESSION['id'],$cedula,$nombre,$apellidos,17,$fecha_hora_actual))
			{
				$_SESSION['nombre'] = $nombre.' '.$apellidos;
				echo $_SESSION['nombre'];
			}
			else
			{
				echo 0;
			}
		break;

		
		/* **** C **** C **** C **** C **** C **** C **** C **** C **** C **** C **** C **** C **** C **** C **** C **** C **** C **** C **** C **** C */
		// case donde se cambia la clave personal de un determinado usuario
		case 'cambiar_clave_personal':
			if( $_POST['input_clave_actu_2']==$_POST['input_clave_actu_3'] )
			{
				if( $obj_estion_usuarios->confirmar_clave_antigua($_POST['input_clave_actu_1'],$_SESSION['id']) )
				{
					echo $obj_estion_usuarios->cambiar_clave_personal($_SESSION['id'],$_POST['input_clave_actu_2'],20,$fecha_hora_actual);
				}
				else
				{
					echo 4;
				}
			}
			else 
			{
				echo 2;
			}
		break;

		// case donde se carga el select tipo_usuario
		case 'cargar_select_tipo_usuario':
			$respuesta =  $obj_estion_usuarios->traer_tipo_usuario();
			$json      =  array();
			while($reg=$respuesta->fetch_object())
			{
				$json[]	=	array(
					'tipo'=>$reg->tipo
				);
			}
			// aca se convierte el json a un formato de string para poder enviarlo
			echo json_encode($json);
		break;

		/* **** R **** R **** R **** R **** R **** R **** R **** R **** R **** R **** R **** R **** R **** R **** R **** R **** R **** R **** R **** R */
		// case donde se registran o editan registros de usuarios
		case 'registrar_editar_usuarios':
			$id_registro = trim($_POST['id_registro_usuarios']);
			$cedula      = trim($_POST['input_cedula']);
			$nombre      = trim($_POST['input_nombre']);
			$apellidos   = trim($_POST['input_apellidos']);
			$tipo_usuario= trim($_POST['select_tipo_usuario']);

			$id_registro = limpiar_cadena($id_registro);
			$cedula 	 = limpiar_cadena($cedula);
			$nombre 	 = limpiar_cadena($nombre);
			$apellidos 	 = limpiar_cadena($apellidos);
			$tipo_usuario= limpiar_cadena($tipo_usuario);

			if($id_registro=='vacio')
			{
				$clave   =   hash("SHA256",$cedula);
				echo $obj_estion_usuarios->insertar($cedula,$nombre,$apellidos,$tipo_usuario,$clave,$fecha_hora_actual,1,$_SESSION['id']);
			}
			else
			{
				echo $obj_estion_usuarios->editar($cedula,$nombre,$apellidos,$tipo_usuario,$fecha_hora_actual,2,$_SESSION['id'],$id_registro);
			}
		break;

		// case donde se restablece una determinada clave
		case 'restablecer_clave':
			$id_registro = trim($_POST['id']);
			$id_registro = limpiar_cadena($id_registro);
			echo $obj_estion_usuarios->restablecer_clave($id_registro,$_SESSION['id'],13,$fecha_hora_actual);
		break;

		/* **** T **** T **** T **** T **** T **** T **** T **** T **** T **** T **** T **** T **** T **** T **** T **** T **** T **** T **** T **** T */
		// case donde se traen los datos de un determinado usuario
		case 'traer_datos_usuario':
			$id = limpiar_cadena($_POST['id']);
			echo  json_encode( $obj_estion_usuarios->mostrar($id) ); 
		break;

		// case donde se traen los datos del usuario cuando es el mismo usurio el que los va editar
		case 'traer_datos_usuario_2':
			echo  json_encode( $obj_estion_usuarios->mostrar($_SESSION['id']) );
		break;
	}
}