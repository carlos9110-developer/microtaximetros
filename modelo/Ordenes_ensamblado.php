<?php 
require_once "../config/conexion.php";
Class Ordenes_ensamblado
{
	public function __construct()
	{

	}

	// función donde se actualiza la fecha de una determinada orden
	public function actualizar_fecha_orden($id_orden,$fecha_nueva,$id_accion,$id_usuario,$fecha_hora_actual)
	{
		$sql = " UPDATE ordenes_ensamblado SET fecha='$fecha_nueva' WHERE id='$id_orden' ";
		if(ejecutar_consulta($sql))
		{
			$sql_trazabilidad = " INSERT INTO trazabilidad(id_usuario,id_registro,id_accion,fecha_hora) VALUES('$id_usuario','$id_orden','$id_accion','$fecha_hora_actual') ";
			return ejecutar_consulta($sql_trazabilidad);
		}
		else
		{
			return 0;
		}
	}

	// función donde se actualiza el la información de un detemrinado producto cuando se ha registrado una orden de ensamblado
	public static function actualizar_info_producto($id_producto,$cantidad,$total,$precio)
	{
		if(self::verificar_ingreso_primera_vez($id_producto)==1)
		{
			$sql = " UPDATE productos SET num_unidades='$cantidad',precio_unidad='$precio',precio_total='$total' WHERE id='$id_producto'  ";
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
			$sql_info_producto          = " SELECT num_unidades,precio_total FROM productos WHERE id='$id_producto' ";
			$result_sql_info_producto   = ejecutar_consulta_retornar_array($sql_info_producto);
			$cantidad_2                 = $result_sql_info_producto['num_unidades'] + $cantidad;
			$precio_total_2             = $result_sql_info_producto['precio_total'] + $total;
			$precio_unidad_2            = $precio_total_2 / $cantidad_2;
			$sql = " UPDATE productos SET num_unidades='$cantidad_2',precio_unidad='$precio_unidad_2',precio_total='$precio_total_2' WHERE id='$id_producto'  ";
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

	// función donde se actualiza el inventario de componentes
	public static function actualizar_info_componentes($id_producto,$cantidad)
	{
		$sw = true;
		$sql = " SELECT componentes.id AS id_componente,componentes.num_unidades,componentes.precio_unidad,componentes.precio_total,componentes_productos.cantidad_componente AS cantidad_necesaria  FROM componentes_productos INNER JOIN componentes ON componentes_productos.id_componente=componentes.id  WHERE componentes_productos.id_producto='$id_producto'  ";
		$rspta= ejecutar_consulta($sql);
		while ($reg=$rspta->fetch_object())
		{
			$id_componente                 = $reg->id_componente;
			$cantidad_componente_necesaria = $reg->cantidad_necesaria * $cantidad;
			$unidades_componente_total     = $reg->num_unidades - $cantidad_componente_necesaria;
			$valor_a_restar                = $cantidad_componente_necesaria * $reg->precio_unidad;
			$valor_total                   = $reg->precio_total - $valor_a_restar;
			if( $unidades_componente_total > 0 )
			{
				$precio_unidad = $valor_total / $unidades_componente_total;
			}
			else if($unidades_componente_total == 0)
			{
				$precio_unidad = 0;
			}
			$sql_detalle   = " UPDATE componentes SET num_unidades='$unidades_componente_total',precio_unidad='$precio_unidad',precio_total='$valor_total' WHERE id='$id_componente' ";
			ejecutar_consulta($sql_detalle) or $sw = false;
		}
		return $sw;
	}


	public function listar()
	{
		$sql = " SELECT ordenes_ensamblado.id,CONCAT(usuarios.nombre,' ',usuarios.apellidos) AS tecnico,productos.nombre AS producto,ordenes_ensamblado.cantidad,ordenes_ensamblado.fecha,ordenes_ensamblado.valor_unidad,ordenes_ensamblado.valor_total,ordenes_ensamblado.estado FROM ordenes_ensamblado LEFT JOIN usuarios ON ordenes_ensamblado.id_tecnico=usuarios.id LEFT JOIN productos ON ordenes_ensamblado.id_producto=productos.id  ";
		return ejecutar_consulta($sql);
	}

	// función donde se traen las ordenes de ensamblado de una determinada fecha
	public function listar_fecha($fecha)
	{
		$sql = " SELECT ordenes_ensamblado.id,CONCAT(usuarios.nombre,' ',usuarios.apellidos) AS tecnico,productos.nombre AS producto,ordenes_ensamblado.cantidad,ordenes_ensamblado.fecha,ordenes_ensamblado.valor_unidad,ordenes_ensamblado.valor_total,ordenes_ensamblado.estado FROM ordenes_ensamblado LEFT JOIN usuarios ON ordenes_ensamblado.id_tecnico=usuarios.id LEFT JOIN productos ON ordenes_ensamblado.id_producto=productos.id WHERE ordenes_ensamblado.fecha='$fecha'  ";
		return ejecutar_consulta($sql);
	}

	// función donde se listan las ordenes de un determinado tecnico
	public function listar_tecnico($id)
	{
		$sql = " SELECT ordenes_ensamblado.id,productos.nombre AS producto,ordenes_ensamblado.cantidad,ordenes_ensamblado.fecha FROM ordenes_ensamblado LEFT JOIN productos ON ordenes_ensamblado.id_producto=productos.id WHERE  ordenes_ensamblado.id_tecnico='$id' ";
		return ejecutar_consulta($sql);
	}

	// función donde se listan las ordenes que estan  en estado pendiente
	public function listar_ordenes_pendientes()
	{
		$sql = " SELECT ordenes_ensamblado.id,CONCAT(usuarios.nombre,' ',usuarios.apellidos) AS tecnico,productos.nombre AS producto,ordenes_ensamblado.cantidad,ordenes_ensamblado.fecha,ordenes_ensamblado.valor_unidad,ordenes_ensamblado.valor_total,ordenes_ensamblado.estado FROM ordenes_ensamblado LEFT JOIN usuarios ON ordenes_ensamblado.id_tecnico=usuarios.id LEFT JOIN productos ON ordenes_ensamblado.id_producto=productos.id WHERE ordenes_ensamblado.estado='Pendiente'  ";
		return ejecutar_consulta($sql);
	}

	// función donde se traen los componentes necesarios para un determinado producto
	public function listar_componentes_producto($id_producto)
	{
		$sql = " SELECT  componentes_productos.cantidad_componente AS cantidad_necesaria,componentes.num_unidades,componentes.nombre FROM componentes_productos INNER JOIN componentes ON componentes_productos.id_componente=componentes.id WHERE componentes_productos.id_producto='$id_producto'  ";
		return ejecutar_consulta($sql);
	}

	// función donde se consulta se hacen las operaciones para consultar si la cantidad de componentes necesarios para un producto es suficiente
	public static function cumple_cantidad_componentes($id_producto,$cantidad)
	{
		$sw = true;
		$sql = " SELECT componentes.num_unidades,componentes_productos.cantidad_componente AS cantidad_necesaria  FROM componentes_productos INNER JOIN componentes ON componentes_productos.id_componente=componentes.id  WHERE componentes_productos.id_producto='$id_producto'  ";
		$rspta = ejecutar_consulta($sql);
		while ( $reg=$rspta->fetch_object() )
		{
			$cantidad_componente    = $reg->cantidad_necesaria * $cantidad;
			if($cantidad_componente > $reg->num_unidades )
			{
				$sw = false;
			}
		}
		return $sw;
	}

	// función donde se registra una determinada orden de ensamblado
	public function registro_orden_ensamblado($id_producto,$cantidad,$fecha,$id_accion,$id_usuario,$fecha_hora_actual)
	{
		$precio_unidad_producto = self::precio_producccion_producto($id_producto);
		$precion_total_producto = $precio_unidad_producto * $cantidad;
		if(  self::cumple_cantidad_componentes($id_producto,$cantidad) )
		{
			$sql = "INSERT INTO ordenes_ensamblado(id_tecnico,id_producto,cantidad,valor_unidad,valor_total,fecha,estado) VALUES('$id_usuario','$id_producto','$cantidad','$precio_unidad_producto','$precion_total_producto','$fecha','Finalizada') ";
			$id_ingreso_new = ejecutar_consulta_retornar_id_registro($sql);
			$sw = true;
			if(  $id_ingreso_new != 0 )
			{
				$sw  = self::actualizar_info_componentes($id_producto,$cantidad);
				if(  $sw==true )
				{
					if( self::actualizar_info_producto($id_producto,$cantidad,$precion_total_producto,$precio_unidad_producto) )
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
					return 0;
				}
			}
			else
			{
				return 0;
			}
		}
		else
		{
			$sql = "INSERT INTO ordenes_ensamblado(id_tecnico,id_producto,cantidad,valor_unidad,valor_total,fecha,estado) VALUES('$id_usuario','$id_producto','$cantidad','$precio_unidad_producto','$precion_total_producto','$fecha','Pendiente') ";
			$id_ingreso_new = ejecutar_consulta_retornar_id_registro($sql);
			if(  $id_ingreso_new != 0 )
			{
				if( self::actualizar_info_producto($id_producto,$cantidad,$precion_total_producto,$precio_unidad_producto) )
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
				return 0;
			}
		}
	}

	// función donde se retorna el precio de producción de un determinado producto
	public static function precio_producccion_producto($id_producto)
	{
		$total = 0;
		$sql   = " SELECT componentes.precio_unidad,componentes_productos.cantidad_componente AS cantidad_necesaria  FROM componentes_productos INNER JOIN componentes ON componentes_productos.id_componente=componentes.id  WHERE componentes_productos.id_producto='$id_producto'  ";
		$rspta = ejecutar_consulta($sql);
		while ($reg=$rspta->fetch_object())
		{
			$total = $total + ($reg->precio_unidad * $reg->cantidad_necesaria);
		}
		return $total;
	}

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

	// función donde se trae la fecha de una determinada orden
	public function traer_fecha_orden($id_orden)
	{
		$sql = " SELECT fecha FROM  ordenes_ensamblado WHERE id='$id_orden' ";
		$result = ejecutar_consulta_retornar_array($sql);
		return $result['fecha'];
	}

	// función donde se pone una detemrinada orden como finalizada
	public function marcar_orden_como_finalizada($id_orden,$id_accion,$id_usuario,$fecha_hora_actual)
	{
		$sql_info_orden    = "SELECT  id_producto,cantidad FROM ordenes_ensamblado WHERE id='$id_orden' ";
		$array_info_orden  = ejecutar_consulta_retornar_array($sql_info_orden);
		if(  self::cumple_cantidad_componentes($array_info_orden['id_producto'],$array_info_orden['cantidad']) )
		{	
			if(self::actualizar_info_componentes($array_info_orden['id_producto'],$array_info_orden['cantidad']))
			{
				$sql_actualizar_orden = "UPDATE ordenes_ensamblado SET estado='Finalizada' WHERE id='$id_orden' ";
				if( ejecutar_consulta($sql_actualizar_orden) )
				{
					$sql_trazabilidad = " INSERT INTO trazabilidad(id_usuario,id_registro,id_accion,fecha_hora) VALUES('$id_usuario','$id_orden','$id_accion','$fecha_hora_actual') ";
					return ejecutar_consulta($sql_trazabilidad);
				}
				else
				{
					return 0;
				}
			}
			else
			{
				return 3;
			}
		}
		else 
		{
			return 4;
		}
	}

	// función donde se trae el número de ordenes pendientes
	public function traer_numero_ordenes_pendientes()
	{
		$sql    = "SELECT COUNT(id) AS cuenta FROM ordenes_ensamblado WHERE estado='Pendiente'   ";
		$result = ejecutar_consulta_retornar_array($sql);
		return $result['cuenta'];
	}

	// función donde se verifica el ingreso del componente por primera vez 
	public static function verificar_ingreso_primera_vez($id_producto)
	{
		$sql    = " SELECT COUNT(id) AS cuenta FROM ordenes_ensamblado WHERE id_producto='$id_producto' LIMIT 2 ";
		$result =  ejecutar_consulta_retornar_array($sql);
		return $result['cuenta'];
	}


}