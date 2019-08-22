<?php 
require_once "../config/conexion.php";
Class Componentes
{
	public function __construct()
	{

	}

	/* **** E **** E **** E **** E **** E **** E **** E **** E **** E **** E **** E **** E **** E **** E **** E **** E **** E **** E **** E **** E */
	public function editar($nombre,$referencia,$fecha_hora_actual,$id_accion,$id_usuario,$id_registro,$cantidad_minima)
	{
		$sql = " UPDATE componentes SET nombre='$nombre',referencia='$referencia',cantidad_minima='$cantidad_minima' WHERE id='$id_registro' ";
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
	public function insertar($nombre,$referencia,$fecha_hora_actual,$id_accion,$id_usuario,$cantidad_minima)
	{
		$sql         = " INSERT INTO componentes(nombre,referencia,cantidad_minima) VALUES('$nombre','$referencia','$cantidad_minima') ";
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
	public function listar()
	{
		$sql = " SELECT * FROM componentes ";
		return ejecutar_consulta($sql);
	}

	// función donde se listan los componentes que tienen las unidades agotadas
	public function listar_componentes_agotados()
	{
		$sql = " SELECT * FROM componentes  WHERE num_unidades < cantidad_minima OR num_unidades='0' ";
		return ejecutar_consulta($sql);
	}

	// función donde se trae el valor total del inventario
	public function obtener_valor_inventario()
	{
		$sql    = " SELECT  SUM(precio_total) AS valor_total FROM componentes ";
		$result = ejecutar_consulta_retornar_array($sql);
		return number_format($result['valor_total'],2, ",", ".");
	}

	/* **** T **** T **** T **** T **** T **** T **** T **** T **** T **** T **** T **** T **** T **** T **** T **** T **** T **** T **** T **** T */
	public function traer_datos_componente($id_registro)
	{
		$sql = "SELECT * FROM componentes WHERE id='$id_registro' ";
		return ejecutar_consulta_retornar_array($sql);
	}

	// función donde se trae el número de componentes agotados
	public function traer_numero_componentes_agotados()
	{
		$sql    = "SELECT COUNT(id) AS cuenta FROM componentes WHERE num_unidades < cantidad_minima OR num_unidades='0'   ";
		$result = ejecutar_consulta_retornar_array($sql);
		return $result['cuenta'];
	}

	}