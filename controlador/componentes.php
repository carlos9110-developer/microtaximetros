<?php
if (strlen(session_id()) < 1)
{
  session_start();
}
date_default_timezone_set("America/Bogota");
$fecha_hora_actual =  date('Y-m-d H:i:s'); // formato fecha colombiana
require_once "../modelo/Componentes.php";
$obj_componentes =new Componentes();
if(isset($_GET['op']))
{
	switch ($_GET['op'])
	{
		case 'listar':
			$rspta= $obj_componentes->listar();
			$clase_label = "";
			//Vamos a declarar un array
			$data= Array();
			while ($reg=$rspta->fetch_object())
			{
				($reg->num_unidades < $reg->cantidad_minima || $reg->num_unidades < 1 ) ? $clase_label = 'text-danger' : $clase_label = 'text-info' ;
				$data[]=array(
				"0"=>$reg->id,
				"1"=>$reg->nombre,
				"2"=>$reg->referencia,
				"3"=>'<span style"font-weight: bold;" class='.$clase_label.'>'.number_format($reg->num_unidades,0, ",", ".").'</span>',
				"4"=>'$ '.number_format($reg->precio_unidad,2, ",", "."),
				"5"=>'$ '.number_format($reg->precio_total,2, ",", "."),
				"6"=>'<button onclick="abrir_actualizar_componente('.$reg->id.')" data-html="true" title="<b>Actualizar Información</b>" data-toggle="tooltip" data-placement="bottom" type="button" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></button>'
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
		
		case 'listar_secretario':
			$rspta= $obj_componentes->listar();
			$clase_label = "";
			//Vamos a declarar un array
			$data= Array();
			while ($reg=$rspta->fetch_object())
			{
				($reg->num_unidades < $reg->cantidad_minima || $reg->num_unidades < 1 ) ? $clase_label = 'text-danger' : $clase_label = 'text-info' ;
				$data[]=array(
				"0"=>$reg->id,
				"1"=>$reg->nombre,
				"2"=>$reg->referencia,
				"3"=>'<span style"font-weight: bold;" class='.$clase_label.'>'.number_format($reg->num_unidades,0, ",", ".").'</span>',
				"4"=>'$ '.number_format($reg->precio_unidad,2, ",", "."),
				"5"=>'$ '.number_format($reg->precio_total,2, ",", ".")
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

		// case donde se listan los componentes agotados
		case 'listar_componentes_agotados':
			$rspta= $obj_componentes->listar_componentes_agotados();
			$data= Array();
			while ($reg=$rspta->fetch_object())
			{
				$data[]=array(
				"0"=>$reg->id,
				"1"=>$reg->nombre,
				"2"=>$reg->referencia,
				"3"=>number_format($reg->num_unidades,0, ",", "."),
				"4"=>number_format($reg->cantidad_minima,0, ",", "."),
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

		// case donde se listan los componentes cuando esta en el modulo de entrada componentes
		case 'listar_componentes_modulo_entrada_componentes':
			$rspta= $obj_componentes->listar();
			//Vamos a declarar un array
			$data= Array();
			while ($reg=$rspta->fetch_object())
			{
				$data[]=array(
				"0"=>$reg->id,
				"1"=>'<button class="btn btn-primary btn-sm" onclick="agregar_detalle('.$reg->id.',\''.$reg->nombre.'\')"><span class="fa fa-plus"></span></button>',
				"2"=>$reg->nombre,
				"3"=>$reg->referencia,
				"4"=>$reg->num_unidades
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
		// case donde se obtiene el valor total que hay en el inventario
		case 'obtener_valor_inventario':
			echo $obj_componentes->obtener_valor_inventario();
		break;
		// case donde se registra o edita un determinado componente
		case 'registrar_editar_componentes':
			$id_registro    = trim($_POST['id_registro_componentes']);
			$nombre         = trim($_POST['input_nombre']);
			$referencia     = trim($_POST['input_referencia']);
			$cantidad_minima= trim($_POST['input_cantidad_minima']);

			$id_registro      = limpiar_cadena($id_registro);
			$nombre           = limpiar_cadena($nombre);
			$referencia       = limpiar_cadena($referencia);
			$cantidad_minima  = limpiar_cadena($cantidad_minima);

			if( $id_registro=='vacio' )
			{
				echo $obj_componentes->insertar($nombre,$referencia,$fecha_hora_actual,5,$_SESSION['id'],$cantidad_minima);
			}
			else
			{
				echo $obj_componentes->editar($nombre,$referencia,$fecha_hora_actual,6,$_SESSION['id'],$id_registro,$cantidad_minima);
			}

		break;

		// case donde se trae la información de un componente para ser mostrada
		case 'traer_datos_componente':
			$id_registro = trim($_POST['id']);
			$id_registro = limpiar_cadena($id_registro);
			echo json_encode($obj_componentes->traer_datos_componente($id_registro));
		break;

		// case donde se trae el número de componentes agotados
		case 'traer_numero_componentes_agotados':
			echo $obj_componentes->traer_numero_componentes_agotados();
		break;
	}
}