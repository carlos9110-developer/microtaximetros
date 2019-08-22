<?php
if (strlen(session_id()) < 1)
{
  session_start();
}
date_default_timezone_set("America/Bogota");
$fecha_hora_actual =  date('Y-m-d H:i:s'); // formato fecha colombiana
require_once "../modelo/Ordenes_ensamblado.php";
$obj_ordenes_ensamblado =new Ordenes_ensamblado();
if(isset($_GET['op']))
{
	switch ($_GET['op'])
	{
		case 'listar':
			$rspta= $obj_ordenes_ensamblado->listar();
			//Vamos a declarar un array
			$data= Array();
			while ($reg=$rspta->fetch_object())
			{
				$data[]=array(
				"0"=>$reg->id,
				"1"=>$reg->tecnico,
				"2"=>$reg->producto,
				"3"=>$reg->cantidad,
				"4"=>'$ '.number_format($reg->valor_unidad,2, ",", "."),
				"5"=>'$ '.number_format($reg->valor_total,2, ",", "."),
				"6"=>$obj_ordenes_ensamblado->fecha_formato_espanol($reg->fecha),
				"7"=>$reg->estado,
				"8"=>'<div class="btn-group" >
					<button onclick="abrir_actualizar_fecha('.$reg->id.')" data-html="true" title="<b>Corregir Fecha</b>" data-toggle="tooltip" data-placement="bottom" type="button" class="btn btn-sm btn-info">
						<i class="fas fa-calendar-alt"></i>
					</button>'.
					( ($reg->estado=='Pendiente') ? '<button onclick="abrir_marcar_registro_orden('.$reg->id.')" data-html="true" title="<b>Marcar Orden Como Finalizada Para Actualizar Inventario</b>" data-toggle="tooltip" data-placement="bottom" type="button" class="btn btn-success btn-sm"><i class="fas fa-hand-pointer"></i></button>' : '' ).'
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

		case 'listar_fecha':
			$rspta= $obj_ordenes_ensamblado->listar_fecha($_REQUEST['fecha']);
			//Vamos a declarar un array
			$data= Array();
			while ($reg=$rspta->fetch_object())
			{
				$data[]=array(
				"0"=>$reg->id,
				"1"=>$reg->tecnico,
				"2"=>$reg->producto,
				"3"=>$reg->cantidad,
				"4"=>'$ '.number_format($reg->valor_unidad,2, ",", "."),
				"5"=>'$ '.number_format($reg->valor_total,2, ",", "."),
				"6"=>$obj_ordenes_ensamblado->fecha_formato_espanol($reg->fecha)
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

		case 'listar_tecnico':
			$rspta= $obj_ordenes_ensamblado->listar_tecnico($_SESSION['id']);
			//Vamos a declarar un array
			$data= Array();
			while ($reg=$rspta->fetch_object())
			{
				$data[]=array(
				"0"=>$reg->id,
				"1"=>$reg->producto,
				"2"=>$reg->cantidad,
				"3"=>$obj_ordenes_ensamblado->fecha_formato_espanol($reg->fecha),
				"4"=>'<button onclick="abrir_actualizar_fecha('.$reg->id.')" data-html="true" title="<b>Corregir Fecha</b>" data-toggle="tooltip" data-placement="bottom" type="button" class="btn btn-sm btn-info">
						<i class="fas fa-calendar-alt"></i>
					</button>'
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

		// case donde se listan las ordenes que estan en estado pendiente
		case 'listar_ordenes_pendientes':
			$rspta= $obj_ordenes_ensamblado->listar_ordenes_pendientes();
			//Vamos a declarar un array
			$data= Array();
			while ($reg=$rspta->fetch_object())
			{
				$data[]=array(
				"0"=>$reg->id,
				"1"=>$reg->tecnico,
				"2"=>$reg->producto,
				"3"=>$reg->cantidad,
				"4"=>$obj_ordenes_ensamblado->fecha_formato_espanol($reg->fecha)
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
		// case donde se actualiza la fecha de una detemrinada orden
		case 'actualizar_fecha_orden':
			$id_orden    = trim($_POST['id_orden']);
			$fecha_nueva = trim($_POST['fecha_nueva']);
			$id_orden    = limpiar_cadena($id_orden);
			$fecha_nueva = limpiar_cadena($fecha_nueva);
			echo $obj_ordenes_ensamblado->actualizar_fecha_orden($id_orden,$fecha_nueva,18,$_SESSION['id'],$fecha_hora_actual);
		break;

		// cas donde se marca una detemrinada ordén como finalizada
		case 'marcar_orden_como_finalizada':
			$id_orden    = trim($_POST['id_orden']);
			$id_orden    = limpiar_cadena($id_orden);
			echo $obj_ordenes_ensamblado->marcar_orden_como_finalizada($id_orden,19,$_SESSION['id'],$fecha_hora_actual);
		break;

		// case donde se registran las ordenes de ensamblado
		case 'registro_orden_ensamblado':
			$id_producto = trim($_POST['select_productos']);
			$cantidad    = trim($_POST['input_cantidad']);
			$fecha       = trim($_POST['input_fecha']);
			$id_producto = limpiar_cadena($id_producto);
			$cantidad    = limpiar_cadena($cantidad);
			$fecha       = limpiar_cadena($fecha);
			echo $obj_ordenes_ensamblado->registro_orden_ensamblado($id_producto,$cantidad,$fecha,16,$_SESSION['id'],$fecha_hora_actual);
		break;

		// Case donde se traen los componentes para una determinada orden de ensamblado 
		case 'traer_componentes_necesarios':
			$id_producto = trim($_POST['id_producto']);
			$cantidad    = trim($_POST['cantidad']);
			$id_producto = limpiar_cadena($id_producto);
			$cantidad    = limpiar_cadena($cantidad);

			$rspta       = $obj_ordenes_ensamblado->listar_componentes_producto($id_producto);
			$clase = "";
			echo 
			'	<thead style="background-color:#A9D0F5;">
                    <tr>
                        <th style="text-align: center;">Componente</th>
                        <th style="text-align: center;"># Unidades Necesarias</th>
                        <th style="text-align: center;"># Unidades Disponibles</th> 
                    </tr>
                </thead>
	    	';
	    	while ($reg = $rspta->fetch_object())
			{
				$cantitad_necesaria_total = ($reg->cantidad_necesaria * $cantidad);
				( $cantitad_necesaria_total > $reg->num_unidades ) ? $clase = 'text-danger' : $clase = 'text-success'; 
				echo 
				'
					<tr style="text-align: center;">
						<td>'.$reg->nombre.'</td>
						<td><span class="text-info negrita">'.$cantitad_necesaria_total.'</span></td>
						<td><span class="negrita '.$clase.' ">  '.$reg->num_unidades.'</span></td>
					</tr>
				';
			}
		break;

		// case donde se trae la fecha de una determinada orden
		case 'traer_fecha_orden':
			$id_orden = trim($_POST['id_orden']);
			$id_orden = limpiar_cadena($id_orden);
			echo $obj_ordenes_ensamblado->traer_fecha_orden($id_orden);
		break;

		// case donde se trae el total de ordenes pendientes
		case 'traer_numero_ordenes_pendientes':
			echo $obj_ordenes_ensamblado->traer_numero_ordenes_pendientes();
		break;
	}
}