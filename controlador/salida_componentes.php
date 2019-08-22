<?php
if (strlen(session_id()) < 1)
{
  session_start();
}
date_default_timezone_set("America/Bogota");
$fecha_hora_actual =  date('Y-m-d H:i:s'); // formato fecha colombiana
require_once "../modelo/Salida_componentes.php";
$obj_salida_componentes =new Salida_componentes();
if(isset($_GET['op']))
{
	switch ($_GET['op'])
	{
		case 'listar':
			$rspta= $obj_salida_componentes->listar();
			//Vamos a declarar un array
			$data= Array();
			while ($reg=$rspta->fetch_object())
			{
				$data[]=array(
				"0"=>$reg->id,
				"1"=>$reg->tecnico,
				"2"=>$obj_salida_componentes->fecha_formato_espanol($reg->fecha),
				"3"=>$reg->observacion,
				"4"=>'<button onclick="abrir_actualizar_salida('.$reg->id.')" data-html="true" title="<b>Ver Detalles Salida</b>" data-toggle="tooltip" data-placement="bottom" type="button" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></button>'
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

		case 'listar_fecha_consulta':
			$rspta= $obj_salida_componentes->listar_fecha_consulta($_REQUEST['fecha']);
			//Vamos a declarar un array
			$data= Array();
			while ($reg=$rspta->fetch_object())
			{
				$data[]=array(
				"0"=>$reg->id,
				"1"=>$reg->tecnico,
				"2"=>$obj_salida_componentes->fecha_formato_espanol($reg->fecha),
				"3"=>$reg->observacion,
				"4"=>'<button onclick="abrir_actualizar_salida('.$reg->id.')" data-html="true" title="<b>Ver Detalles Salida</b>" data-toggle="tooltip" data-placement="bottom" type="button" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></button>'
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

		// case donde se listan solo las salidas de un determinado tecnicó
		case 'listar_tecnicos':
			$rspta= $obj_salida_componentes->listar_tecnicos($_SESSION['id']);
			//Vamos a declarar un array
			$data= Array();
			while ($reg=$rspta->fetch_object())
			{
				$data[]=array(
				"0"=>$reg->id,
				"1"=>$obj_salida_componentes->fecha_formato_espanol($reg->fecha),
				"2"=>$reg->observacion,
				"3"=>'<button onclick="abrir_actualizar_salida('.$reg->id.')" data-html="true" title="<b>Ver Detalles Salida</b>" data-toggle="tooltip" data-placement="bottom" type="button" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></button>'
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
	switch ($_POST['op'])
	{
		case 'registro_salida_componentes':
			$id_registro = trim($_POST['id_registro_salida_componentes']);
			$fecha       = trim($_POST['fecha']);
			$observacion = trim($_POST['observacion']);
			$id_registro = limpiar_cadena($id_registro);
			$fecha       = limpiar_cadena($fecha);
			$observacion = limpiar_cadena($observacion);
			if( $id_registro=='vacio' )
			{
				echo $obj_salida_componentes->insertar($fecha,$observacion,$_POST['input_id_componente'],$_POST['input_cantidad'],11,$_SESSION['id'],$fecha_hora_actual);
			}
			else
			{
			    echo $obj_salida_componentes->editar($id_registro,$fecha,$observacion,12,$_SESSION['id'],$fecha_hora_actual);
			}


		break;

		// case donde se listan los componentes de una determinada salida
		case 'listar_componentes_salida':
			$id_registro = trim($_POST['id']);
			$id_registro = limpiar_cadena($id_registro);
			$rspta       = $obj_salida_componentes->listar_componentes_salida($id_registro);
			echo 
			'	<thead style="background-color:#A9D0F5;">
                    <tr>
                        <th>Opciones</th>
                        <th>Componente</th>
                        <th>Cantidad</th>
                    </tr>
                </thead>
	    	';
			while ($reg = $rspta->fetch_object())
			{
				echo 
				'
					<tr class="filas">
						<td></td>
						<td>'.$reg->nombre.'</td>
						<td>'.$reg->cantidad.'</td>
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

		// case donde se traen los datos de una detemrinada salida 
		case 'traer_datos_salida':
			$id_registro = trim($_POST['id']);
			$id_registro = limpiar_cadena($id_registro);
			echo json_encode($obj_salida_componentes->traer_datos_salida($id_registro));
		break;
	}
}
