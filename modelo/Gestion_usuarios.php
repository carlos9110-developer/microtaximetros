<?php 
require_once "../config/conexion.php";
Class Gestion_usuarios
{
	public function __construct()
	{

	}

	/* **** A **** A **** A **** A **** A **** A **** A **** A **** A **** A **** A **** A **** A **** A **** A **** A **** A **** A **** A **** A */
	// función donde se activa o desactiva un determinado usuario
	public  function activar_desactivar_usuario($id,$guia,$fecha_hora_actual,$id_accion,$id_usuario)
	{
		$sql="UPDATE usuarios SET estado='$guia' WHERE id='$id'";
		if(ejecutar_consulta($sql)==1)
		{
			$sql_2 = " INSERT INTO trazabilidad(id_usuario,id_registro,id_accion,fecha_hora) VALUES('$id_usuario','$id','$id_accion','$fecha_hora_actual')";
			return ejecutar_consulta($sql_2);
		}
		else
		{
			return 0;
		}
	}

	// fucnión donde se actualizan los datos de un determinado usuario
	public function actualizacion_datos($id,$cedula,$nombre,$apellidos,$id_accion,$fecha_hora_actual)
	{
		$sql = " UPDATE usuarios SET cedula='$cedula',nombre='$nombre',apellidos='$apellidos' WHERE id='$id'   ";
		if(ejecutar_consulta($sql))
		{
			$sql_2 = " INSERT INTO trazabilidad(id_usuario,id_registro,id_accion,fecha_hora) VALUES('$id','$id','$id_accion','$fecha_hora_actual')";
			return ejecutar_consulta($sql_2);
		}
		else
		{
			return 0;
		}
	}

	// función donde se cambia la clave de un detemrinado usuario
	public function cambiar_clave_personal($id_usuario,$clave,$id_accion,$fecha_hora_actual)
	{
		$clave_hash = hash("SHA256",$clave);
		$sql        = " UPDATE usuarios SET clave='$clave_hash'  WHERE id='$id_usuario' ";
		if(ejecutar_consulta($sql))
		{
			$sql_2 = " INSERT INTO trazabilidad(id_usuario,id_registro,id_accion,fecha_hora) VALUES('$id_usuario','$id_usuario','$id_accion','$fecha_hora_actual')";
			return ejecutar_consulta($sql_2);
		}
		else 
		{
			return 0;
		}

	}

	// función donde se cambia la clave antigua de un detemrinado usuario+
	public function confirmar_clave_antigua($clave_antigua,$id)
	{
		$clave_hash = hash("SHA256",$clave_antigua);
		$sql = " SELECT COUNT(id) AS cuenta FROM usuarios WHERE id='$id' AND clave='$clave_hash'  ";
		$result = ejecutar_consulta_retornar_array($sql);
		if( $result['cuenta']==1 )
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	// función donde se edita la información de un determinado usuario
	public function editar($cedula,$nombre,$apellidos,$tipo_usuario,$fecha_hora_actual,$id_accion,$id_usuario,$id_registro)
	{
		$sql = " UPDATE usuarios SET cedula='$cedula',nombre='$nombre',apellidos='$apellidos',tipo='$tipo_usuario' WHERE id='$id_registro' ";
		if( ejecutar_consulta($sql)==1 )
		{
			$sql_2 = " INSERT INTO trazabilidad(id_usuario,id_registro,id_accion,fecha_hora) VALUES('$id_usuario','$id_registro','$id_accion','$fecha_hora_actual')";
			return ejecutar_consulta($sql_2);
		}
		else
		{
			return 0;
		}

	}

	/* **** I **** I **** I **** I **** I **** I **** I **** I **** I **** I **** I **** I **** I **** I **** I **** I **** I **** I **** I **** I */
	// función donde se insertan registros de usuarios
	public function insertar($cedula,$nombre,$apellidos,$tipo_usuario,$clave,$fecha_hora_actual,$id_accion,$id_usuario)
	{
		$sql         = " INSERT INTO usuarios(cedula,nombre,apellidos,tipo,clave) VALUES('$cedula','$nombre','$apellidos','$tipo_usuario','$clave') ";
		$id_registro = ejecutar_consulta_retornar_id_registro($sql);
		if( $id_registro > 0 )
		{
			$sql_2 = " INSERT INTO trazabilidad(id_usuario,id_registro,id_accion,fecha_hora) VALUES('$id_usuario','$id_registro','$id_accion','$fecha_hora_actual')";
			return ejecutar_consulta($sql_2);
		}
		else
		{
			return 0;
		}
	}

	/* **** L **** L **** L **** L **** L **** L **** L **** L **** L **** L **** L **** L **** L **** L **** L **** L **** L **** L **** L **** L */
	// función donde se listan los usuarios que estan registrados
	public  function listar()
	{
		$sql = " SELECT id,cedula,CONCAT(nombre,' ',apellidos) AS nombre_completo,tipo,clave,estado FROM usuarios WHERE cedula!='1088008382' ";
		return ejecutar_consulta($sql);
	}

	// función donde se procesa el login
	public function login($usuario,$clave)
	{
		$sql    = "SELECT COUNT(id) AS cuenta,id,tipo,CONCAT(nombre,' ',apellidos) AS nombre_completo FROM usuarios WHERE cedula='$usuario' AND clave='$clave' AND estado='1'   ";
		return ejecutar_consulta_retornar_array($sql);
	}

	// función donde se traen los datos de un determinado usuario
	public function mostrar($id)
	{
		$sql = "SELECT cedula,nombre,apellidos,tipo FROM usuarios WHERE id='$id' ";
		return ejecutar_consulta_retornar_array($sql);
	}

	// función donde se restablece la contraseña de un determinado usuario
	public function restablecer_clave($id,$id_usuario,$id_accion,$fecha_hora_actual)
	{

		$sql    = "SELECT cedula FROM usuarios WHERE id='$id' ";
		$result = ejecutar_consulta_retornar_array($sql);
		$clave  = hash("SHA256",$result['cedula']);  
		$sql_2  = "UPDATE usuarios SET clave='$clave' WHERE id='$id' ";
		if( ejecutar_consulta($sql_2)==1 )
		{
			$sql_3 = " INSERT INTO trazabilidad(id_usuario,id_registro,id_accion,fecha_hora) VALUES('$id_usuario','$id','$id_accion','$fecha_hora_actual')";
			return ejecutar_consulta($sql_3);
		}
		else
		{
			return 0;
		}
	}

	/* **** T **** T **** T **** T **** T **** T **** T **** T **** T **** T **** T **** T **** T **** T **** T **** T **** T **** T **** T **** T */
	// función donde se trean todos los tipos de usuarios cargados en la tabla
	public function traer_tipo_usuario()
	{
		$sql = "SELECT id,tipo FROM  tipo_usuario ";
		return ejecutar_consulta($sql);
	}
}