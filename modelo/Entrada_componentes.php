<?php 
require_once "../config/conexion.php";
Class Entrada_componentes
{
	public function __construct()
	{

	}

	// función donde se actualiza el la información de un detemrinado componente cuando se ha registrado el ingreso de mercancia
	public static function actualizar_info_componente($id_componente,$cantidad,$total,$precio)
	{
		if(self::verificar_ingreso_primera_vez($id_componente)==1)
		{
			$sql = " UPDATE componentes SET num_unidades='$cantidad',precio_unidad='$precio',precio_total='$total' WHERE id='$id_componente'  ";
			if(ejecutar_consulta($sql)==1)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			$sql_info_componente        = " SELECT num_unidades,precio_total FROM componentes WHERE id='$id_componente' ";
			$result_sql_info_componente = ejecutar_consulta_retornar_array($sql_info_componente);
			$cantidad_2                 = $result_sql_info_componente['num_unidades'] + $cantidad;
			$precio_total_2             = $result_sql_info_componente['precio_total'] + $total;
			$precio_unidad_2            = $precio_total_2 / $cantidad_2;
			$sql = " UPDATE componentes SET num_unidades='$cantidad_2',precio_unidad='$precio_unidad_2',precio_total='$precio_total_2' WHERE id='$id_componente'  ";
			if(ejecutar_consulta($sql)==1)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
	}

	// función donde se calcula el subtotal de un determinado componente
	public static function calcular_subtotal($cantidad,$precio)
	{
		return  $cantidad * $precio;
	}

	// función donde se editan los datos de una determinada entrada de componentes
	public function editar($id_registro,$proveedor,$fecha,$observacion,$id_accion,$id_usuario,$fecha_hora_actual)
	{
		$sql = "UPDATE ingreso_componentes SET proveedor='$proveedor',fecha='$fecha',observacion='$observacion' WHERE  id='$id_registro' ";
		if(ejecutar_consulta($sql)==1)
		{
			$sql_trazabilidad = " INSERT INTO trazabilidad(id_usuario,id_registro,id_accion,fecha_hora) VALUES('$id_usuario','$id_registro','$id_accion','$fecha_hora_actual') ";
			return ejecutar_consulta($sql_trazabilidad);
		}
		else
		{
			return 0;
		}

	}

	// función donde se inserta la entrada de unos determinados componentes
	public function insertar($proveedor,$fecha,$observacion,$input_id_componente,$input_cantidad,$input_precio_compra,$id_accion,$id_usuario,$fecha_hora,$total_compra)
	{
		$sql="INSERT INTO ingreso_componentes(proveedor,fecha,observacion,total_compra,id_empleado) VALUES ('$proveedor','$fecha','$observacion','$total_compra','$id_usuario')";
		$id_ingreso_new = ejecutar_consulta_retornar_id_registro($sql);
		
		$num_elementos=0;
		$sw=true;
		while ( ($num_elementos < count($input_id_componente)) && ( $sw==true )  )
		{
			$sub_total = self::calcular_subtotal($input_cantidad[$num_elementos],$input_precio_compra[$num_elementos]);
			$sql_detalle = "INSERT INTO detalle_ingreso_componentes(id_ingreso_componentes,id_componente,cantidad,precio_unidad,sub_total) VALUES ('$id_ingreso_new', '$input_id_componente[$num_elementos]','$input_cantidad[$num_elementos]','$input_precio_compra[$num_elementos]','$sub_total')";
			ejecutar_consulta($sql_detalle) or $sw = false;
			if($sw==true)
			{
				$sw = self::actualizar_info_componente($input_id_componente[$num_elementos],$input_cantidad[$num_elementos],$sub_total,$input_precio_compra[$num_elementos]);
			}
			$num_elementos=$num_elementos + 1;
		}

		if( $sw==true )
		{
			$sql_trazabilidad = " INSERT INTO trazabilidad(id_usuario,id_registro,id_accion,fecha_hora) VALUES('$id_usuario','$id_ingreso_new','$id_accion','$fecha_hora') ";
			return ejecutar_consulta($sql_trazabilidad);
		}	
		else
		{
			return 0;
		}

	}

	// función donde se verifica el ingreso del componente por primera vez 
	public static function verificar_ingreso_primera_vez($id_componente)
	{
		$sql    = " SELECT COUNT(id) AS cuenta FROM detalle_ingreso_componentes WHERE id_componente='$id_componente' LIMIT 2 ";
		$result =  ejecutar_consulta_retornar_array($sql);
		return $result['cuenta'];
	}

	// función donde se listan las entradas de componentes
	public function listar()
	{
		$sql = "SELECT ingreso_componentes.id,CONCAT(usuarios.nombre,' ',usuarios.apellidos) AS empleado,ingreso_componentes.proveedor,ingreso_componentes.fecha,ingreso_componentes.total_compra  FROM ingreso_componentes INNER JOIN usuarios ON ingreso_componentes.id_empleado=usuarios.id ";
		return ejecutar_consulta($sql);
	}

	// función donde se listan las entradas de componentes
	public function listar_2($id)
	{
		$sql = "SELECT * FROM ingreso_componentes WHERE id_empleado='$id' ";
		return ejecutar_consulta($sql);
	}

	// función donde se listan las entradas de componentes de una determinada fecha
	public function listar_consulta_fecha($fecha)
	{
		$sql = "SELECT * FROM ingreso_componentes WHERE fecha='$fecha' ";
		return ejecutar_consulta($sql);
	}

	// función donde se listan los detalles de una determinada entrada 
	public function listar_detalles_entrada($id)
	{
		$sql = " SELECT componentes.nombre,detalle_ingreso_componentes.cantidad,detalle_ingreso_componentes.precio_unidad,detalle_ingreso_componentes.sub_total FROM detalle_ingreso_componentes INNER JOIN componentes ON detalle_ingreso_componentes.id_componente=componentes.id WHERE detalle_ingreso_componentes.id_ingreso_componentes='$id'  ";
		return ejecutar_consulta($sql);
	}

	public function traer_info_entrada($id_registro)
	{
		$sql    = "SELECT * FROM ingreso_componentes WHERE id='$id_registro' ";
		return  ejecutar_consulta_retornar_array($sql);
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
	
}