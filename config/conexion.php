<?php 
require_once "global.php";
$conexion = new mysqli(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);
mysqli_query( $conexion, 'SET NAMES "'.DB_ENCODE.'"');

//Si tenemos un posible error en la conexión lo mostramos
if (mysqli_connect_errno())
{
	printf("Falló conexión a la base de datos: %s\n",mysqli_connect_error());
	exit();
}

if (!function_exists('ejecutar_consulta'))
{
	// retorna un 1 cuando es correcta ó 0 cuando es incorrecta
	function ejecutar_consulta($sql)
	{
		global $conexion;
		$query = $conexion->query($sql);
		return $query;
	}

	// retorna un array con el resultado de la consulta
	function ejecutar_consulta_retornar_array($sql)
	{
		global $conexion;
		$query = $conexion->query($sql);		
		$row = $query->fetch_assoc();
		return $row;
	}

	// realiza un insert y retorna el id del nuevo registro
	function ejecutar_consulta_retornar_id_registro($sql)
	{
		global $conexion;
		$query = $conexion->query($sql);		
		return $conexion->insert_id;			
	}

	function limpiar_cadena($str)
	{
		global $conexion;
		$str = mysqli_real_escape_string($conexion,trim($str));
		return htmlspecialchars($str);
	}
}
?>