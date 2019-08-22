<?php 
require_once "../config/conexion.php";
Class Productos
{
	public function __construct()
	{

	}

	// función donde se activa o desactiva un determinado producto
	public function activar_desactivar_producto($id,$guia,$fecha_hora_actual,$id_accion,$id_usuario)
	{
		$sql="UPDATE productos SET estado='$guia' WHERE id='$id'";
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

	// función donde se edita la información de un determinado producto
	public function editar($id_registro,$nombre,$referencia,$id_accion,$id_usuario,$fecha_hora_actual)
	{
		$sql = " UPDATE productos SET nombre='$nombre',referencia='$referencia' WHERE id='$id_registro' ";
		if( ejecutar_consulta($sql)==1 )
		{
			$sql_trazabilidad = " INSERT INTO trazabilidad(id_usuario,id_registro,id_accion,fecha_hora) VALUES('$id_usuario','$id_registro','$id_accion','$fecha_hora_actual') ";
			return ejecutar_consulta($sql_trazabilidad);
		}
		else
		{
			return 0;
		}
	}

	// función donde se realiza el registro de un determinado producto
	public function insertar($nombre,$referencia,$input_id_componente,$input_cantidad,$id_accion,$id_usuario,$fecha_hora_actual)
	{
		$sql="INSERT INTO productos(nombre,referencia) VALUES ('$nombre','$referencia')";
		$id_ingreso_new = ejecutar_consulta_retornar_id_registro($sql);
		$num_elementos=0;
		$sw=true;
		while ( ($num_elementos < count($input_id_componente)) && ( $sw==true )  )
		{
			$sql_detalle = "INSERT INTO componentes_productos(id_producto,id_componente,cantidad_componente) VALUES ('$id_ingreso_new', '$input_id_componente[$num_elementos]','$input_cantidad[$num_elementos]')";
			ejecutar_consulta($sql_detalle) or $sw = false;
			$num_elementos=$num_elementos + 1;
		}
		if( $sw==true )
		{
			$sql_trazabilidad = " INSERT INTO trazabilidad(id_usuario,id_registro,id_accion,fecha_hora) VALUES('$id_usuario','$id_ingreso_new','$id_accion','$fecha_hora_actual') ";
			return ejecutar_consulta($sql_trazabilidad);
		}	
		else
		{
			return 0;
		}
	}

	// función donde se listan todos los productos registrados en la base de datos
	public function listar()
	{
		$sql = "SELECT * FROM productos";
		return ejecutar_consulta($sql);
	}

	// función donde se listan los productos para realizar una determinada ordén
	public function listar_productos_para_orden()
	{
		$sql = "SELECT * FROM productos WHERE estado='1' ";
		return ejecutar_consulta($sql);
	}

	// función donde se listan los cómponentes de un determinado producto
	public function listar_componentes_producto($id)
	{
		$sql = " SELECT componentes.nombre,componentes_productos.cantidad_componente FROM componentes_productos INNER JOIN componentes ON componentes_productos.id_componente=componentes.id WHERE componentes_productos.id_producto='$id'  ";
		return ejecutar_consulta($sql);
	}

	// función donde se trae el valor total del inventario
	public function obtener_valor_inventario()
	{
		$sql    = " SELECT  SUM(precio_total) AS valor_total FROM productos ";
		$result = ejecutar_consulta_retornar_array($sql);
		return number_format($result['valor_total'],2, ",", ".");
	}

	// función donde se trae la información de un determinado producto
	public function traer_datos_producto($id)
	{
		$sql = "SELECT * FROM productos WHERE id='$id' ";
		return ejecutar_consulta_retornar_array($sql);
	}
}