<?php
if (strlen(session_id()) < 1)
{
  session_start();
}
date_default_timezone_set("America/Bogota");
$fecha_hora_actual =  date('Y-m-d H:i:s'); // formato fecha colombiana
require_once "../modelo/Productos.php";
$obj_productos =new Productos();

if(isset($_GET['op']))
{
	switch ($_GET['op'])
	{
		case 'listar':
			$rspta= $obj_productos->listar();
			//Vamos a declarar un array
			$data= Array();
			while ($reg=$rspta->fetch_object())
			{
				$data[]=array(
				"0"=>$reg->id,
				"1"=>$reg->nombre,
				"2"=>$reg->referencia,
				"3"=>number_format($reg->num_unidades,2, ",", "."),
				"4"=>'$ '.number_format($reg->precio_unidad,2, ",", "."),
				"5"=>'$ '.number_format($reg->precio_total,2, ",", "."),
				"6"=>($reg->estado=='1') ? 'Activo' : 'Inactivo',
				"7"=>'<div class="btn-group">
					<button onclick="abrir_actualizar_producto('.$reg->id.')" data-html="true" title="<b>Ver Componentes Producto</b>" data-toggle="tooltip" data-placement="top" type="button" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></button>'.
					(( $reg->estado=='0' ) ? '<button onclick="activar_desactivar_producto('.$reg->id.',1)" data-html="true" title="<b>Activar Producto</b>" data-toggle="tooltip" data-placement="top"  class="btn btn-sm btn-success"><i class="fas fa-paper-plane"></i></button>' : '<button data-html="true" title="<b>Desactivar Producto</b>" data-toggle="tooltip" data-placement="top" onclick="activar_desactivar_producto('.$reg->id.',0)" class="btn btn-sm btn-danger"><i class="fas fa-paper-plane"></i></button>').' 
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
else if($_POST['op'])
{
	switch($_POST['op'])
	{
		// case donde se activa o desactiva un determinado producto
		case 'activar_desactivar_producto':
			$guia_accion = "";
			if($_POST['guia']=='1')
			{
				$guia_accion = 14;
			}
			else
			{
				$guia_accion = 15;
			}
			echo $obj_productos->activar_desactivar_producto($_POST['id'],$_POST['guia'],$fecha_hora_actual,$guia_accion,$_SESSION['id']);
		break;

		// case donde se carga el select productos
		case 'cargar_select_productos':
			$respuesta =  $obj_productos->listar_productos_para_orden();
			$json      =  array();
			while($reg=$respuesta->fetch_object())
			{
				$json[]	=	array(
					'id'=>$reg->id,
					'nombre'=>$reg->nombre
				);
			}
			// aca se convierte el json a un formato de string para poder enviarlo
			echo json_encode($json);
		break;

		// case donde se listan los componentes de un determinado producto
		case 'listar_componentes_producto':
			$id_registro = trim($_POST['id']);
			$id_registro = limpiar_cadena($id_registro);
			$rspta = $obj_productos->listar_componentes_producto($id_registro);
			echo 
			'	<thead style="background-color:#A9D0F5;">
                    <th>Opciones</th>
                    <th>Componente</th>
                    <th>Cantidad</th>
                </thead>
	    	';
			while ($reg = $rspta->fetch_object())
			{
				echo 
				'
					<tr class="filas">
						<td></td>
						<td>'.$reg->nombre.'</td>
						<td>'.$reg->cantidad_componente.'</td>
					</tr>
				';
			}
			echo 
			'
				<tfoot style="background-color:#A9D0F5;">
                    <th>Opciones</th>
                    <th>Componente</th>
                    <th>Cantidad</th>  
                </tfoot>
            ';
		break;

		case 'registro_productos':
			$id_registro = trim($_POST['id_registro_productos']);
			$nombre      = trim($_POST['nombre']);
			$referencia  = trim($_POST['referencia']);
			
			$id_registro = limpiar_cadena($id_registro);
			$nombre      = limpiar_cadena($nombre);
			$referencia  = limpiar_cadena($referencia);
			if($id_registro=='vacio')
			{
				echo $obj_productos->insertar($nombre,$referencia,$_POST["input_id_componente"],$_POST["input_cantidad"],9,$_SESSION['id'],$fecha_hora_actual);
			}
			else
			{
				echo $obj_productos->editar($id_registro,$nombre,$referencia,10,$_SESSION['id'],$fecha_hora_actual);	
			}
		break;

		// case donde se trae la información de un determinado producto
		case 'traer_datos_producto':
			$id_registro = trim($_POST['id']);
			$id_registro = limpiar_cadena($id_registro);
			echo json_encode($obj_productos->traer_datos_producto($id_registro));
		break;

		// case donde se obtiene el valor total que hay en el inventario
		case 'obtener_valor_inventario':
			echo $obj_productos->obtener_valor_inventario();
		break;
	}
}