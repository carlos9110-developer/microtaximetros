<?php 
require_once "../config/conexion.php";
Class Salida_componentes
{
	public function __construct()
	{

	}

	// función donde se actualiza el inventario de componentes
	public static function actualizar_info_componente($id_componente,$cantidad)
	{
		$sql    	 = " SELECT num_unidades,precio_unidad FROM componentes WHERE id='$id_componente' ";
		$result 	 = ejecutar_consulta_retornar_array($sql);
		$num_unidades= $result['num_unidades'] - $cantidad;
		$total  	 = $num_unidades * $result['precio_unidad'];
		$sql_2       = " UPDATE componentes SET num_unidades='$num_unidades',precio_total='$total' WHERE id='$id_componente'  ";
		if(ejecutar_consulta($sql_2))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	// función donde se edita el registro de una salida de componentes
	public function editar($id_registro,$fecha,$observacion,$id_accion,$id_usuario,$fecha_hora_actual)
	{
		$sql = " UPDATE salida_componentes SET fecha='$fecha',observacion='$observacion' WHERE id='$id_registro' ";
		if(ejecutar_consulta($sql))
		{
			$sql_trazabilidad = " INSERT INTO trazabilidad(id_usuario,id_registro,id_accion,fecha_hora) VALUES('$id_usuario','$id_registro','$id_accion','$fecha_hora_actual') ";
			return ejecutar_consulta($sql_trazabilidad);
		}
		else
		{
			return 0;
		}
	}

	
	// función donde se inserta la salida de componentes
	public function insertar($fecha,$observacion,$input_id_componente,$input_cantidad,$id_accion,$id_usuario,$fecha_hora_actual)
	{
		$sw = true;
		$num_elementos = 0;
		$mensaje_error = "";
		while ( ($num_elementos < count($input_id_componente)) && ( $sw==true )  )
		{
			if( $input_cantidad[$num_elementos] > self::cantidad_unidades_componente($input_id_componente[$num_elementos])  )
			{
				$mensaje_error  ="Faltan <b>".self::unidades_restantes($input_cantidad[$num_elementos],self::cantidad_unidades_componente($input_id_componente[$num_elementos]))."</b> unidades para el componente <b>".self::traer_nombre_componente($input_id_componente[$num_elementos])."</b> ";
				$sw = false;
			}
			$num_elementos = $num_elementos + 1;
		}

		if($sw==true)
		{
			$sql = " INSERT INTO salida_componentes(fecha,observacion,id_tecnico) VALUES('$fecha','$observacion','$id_usuario') ";
			$id_ingreso_new = ejecutar_consulta_retornar_id_registro($sql);
			$num_elementos = 0;
			while ( ($num_elementos < count($input_id_componente)) && ( $sw==true )  )
			{
				$sql_detalle = "INSERT INTO detalle_salida_componentes(id_salida,id_componente,cantidad) VALUES ('$id_ingreso_new','$input_id_componente[$num_elementos]','$input_cantidad[$num_elementos]')";
				ejecutar_consulta($sql_detalle) or $sw = false;
				if($sw==true)
				{
					$sw = self::actualizar_info_componente($input_id_componente[$num_elementos],$input_cantidad[$num_elementos]);
				}
				$num_elementos=$num_elementos + 1;
			}

			if($sw==true)
			{
				$sql_trazabilidad = " INSERT INTO trazabilidad(id_usuario,id_registro,id_accion,fecha_hora) VALUES('$id_usuario','$id_ingreso_new','$id_accion','$fecha_hora_actual') ";
				return ejecutar_consulta($sql_trazabilidad);
			}
			else
			{
				return 0;
			}
		}
		else
		{
			return $mensaje_error;
		}
	}


	// función donde se retorna el nombre de un determinado componente
	public static function traer_nombre_componente($id)
	{
		$sql = " SELECT nombre FROM componentes WHERE id='$id' ";
		$result = ejecutar_consulta_retornar_array($sql);
		return $result['nombre'];
	}

	// función donde se retorna el número de unidades que se necesitan para poder registrar la salida de un componente
	public static function unidades_restantes($elemento_1,$elemento_2)
	{
		return $elemento_1 - $elemento_2;
	}

	// función donde se trae la cantidad de unidades que tiene un determinado componente
	public static function cantidad_unidades_componente($id)
	{
		$sql = " SELECT num_unidades FROM componentes WHERE id='$id' ";
		$result = ejecutar_consulta_retornar_array($sql);
		return $result['num_unidades'];
	}

	// función donde se convierte la fecha a formato español
	public  function fecha_formato_espanol($date)
	{
		$diaa = explode("-", $date, 3);
	    $year = $diaa[0];
	    $month = (string)(int)$diaa[1];
	    $day = (string)(int)$diaa[2];

	    $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado");
	    $tomadia = $dias[intval((date("w",mktime(0,0,0,$month,$day,$year))))];
	    $meses = array("", "enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre");

	    return $tomadia.", ".$day." de ".$meses[$month]." de ".$year;
	}

	// función donde se listan todas las salidas
	public function listar()
	{
		$sql = " SELECT salida_componentes.id,salida_componentes.fecha,salida_componentes.observacion,CONCAT(usuarios.nombre,' ',usuarios.apellidos) AS tecnico FROM salida_componentes LEFT JOIN usuarios ON salida_componentes.id_tecnico=usuarios.id ";
		return ejecutar_consulta($sql);
	}

	// función donde se listan las salidas de una detemrinada fecha
	public function listar_fecha_consulta($fecha)
	{
		$sql = " SELECT salida_componentes.id,salida_componentes.fecha,salida_componentes.observacion,CONCAT(usuarios.nombre,' ',usuarios.apellidos) AS tecnico FROM salida_componentes LEFT JOIN usuarios ON salida_componentes.id_tecnico=usuarios.id WHERE salida_componentes.fecha='$fecha'  ";
		return ejecutar_consulta($sql);
	}

	// función donde se traen los dalos de una determinada salida
	public function traer_datos_salida($id)
	{
		$sql = " SELECT * FROM salida_componentes WHERE id='$id' ";
		return ejecutar_consulta_retornar_array($sql);
	}

	// función donde se listan los cómponentes de una determinada salida
	public function listar_componentes_salida($id)
	{
		$sql = " SELECT componentes.nombre,detalle_salida_componentes.cantidad FROM detalle_salida_componentes INNER JOIN componentes ON detalle_salida_componentes.id_componente=componentes.id WHERE detalle_salida_componentes.id_salida='$id'  ";
		return ejecutar_consulta($sql);
	}

	// función donde se listan las salidas que son registradas por un determinado tecnico
	public function listar_tecnicos($id_tecnico)
	{
		$sql = "SELECT * FROM salida_componentes WHERE id_tecnico='$id_tecnico'  ";
		return ejecutar_consulta($sql);	
	}

}