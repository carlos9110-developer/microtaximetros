<?php
if (strlen(session_id()) < 1)
{
  session_start();
}
date_default_timezone_set("America/Bogota");
$fecha_hora_actual =  date('Y-m-d H:i:s'); // formato fecha colombiana
require_once "../modelo/Entrada_componentes.php";
$obj_entrada_componentes =new Entrada_componentes();

if(isset($_GET['op']))
{
	switch ($_GET['op'])
	{
		case 'listar':
			$rspta= $obj_entrada_componentes->listar();
			//Vamos a declarar un array
			$data= Array();
			while ($reg=$rspta->fetch_object())
			{
				$data[]=array(
				"0"=>$reg->id,
				"1"=>$reg->empleado,
				"2"=>$reg->proveedor,
				"3"=>$obj_entrada_componentes->fecha_formato_espanol($reg->fecha),
				"4"=>'$ '.number_format($reg->total_compra,0, ",", "."),
				"5"=>'<button onclick="abrir_actualizar_entrada('.$reg->id.')" data-html="true" title="<b>Ver Detalles Entrada</b>" data-toggle="tooltip" data-placement="bottom" type="button" class="btn btn-warning"><i class="fas fa-edit"></i></button>'
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

		case 'listar_2':
			$rspta= $obj_entrada_componentes->listar_2($_SESSION['id']);
			//Vamos a declarar un array
			$data= Array();
			while ($reg=$rspta->fetch_object())
			{
				$data[]=array(
				"0"=>$reg->id,
				"1"=>$reg->proveedor,
				"2"=>$obj_entrada_componentes->fecha_formato_espanol($reg->fecha),
				"3"=>'<button onclick="abrir_actualizar_entrada('.$reg->id.')" data-html="true" title="<b>Ver Detalles Entrada</b>" data-toggle="tooltip" data-placement="bottom" type="button" class="btn btn-warning"><i class="fas fa-edit"></i></button>'
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

		case 'listar_consulta_fecha':
			$rspta= $obj_entrada_componentes->listar_consulta_fecha($_REQUEST['fecha']);
			//Vamos a declarar un array
			$data= Array();
			while ($reg=$rspta->fetch_object())
			{
				$data[]=array(
				"0"=>$reg->id,
				"1"=>$reg->proveedor,
				"2"=>$obj_entrada_componentes->fecha_formato_espanol($reg->fecha),
				"3"=>'$ '.number_format($reg->total_compra,0, ",", "."),
				"4"=>'<button onclick="abrir_actualizar_entrada('.$reg->id.')" data-html="true" title="<b>Ver Detalles Entrada</b>" data-toggle="tooltip" data-placement="bottom" type="button" class="btn btn-warning"><i class="fas fa-edit"></i></button>'
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
		// case donde se listan los detalles de una determinada entrada
		case 'listar_detalles_entrada':
			$id_registro = trim($_POST['id']);
			$id_registro = limpiar_cadena($id_registro);
			$rspta = $obj_entrada_componentes->listar_detalles_entrada($id_registro);
			$total=0;
			echo 
			'	<thead style="background-color:#A9D0F5;">
                    <th>Opciones</th>
                    <th>Componente</th>
                    <th>Cantidad</th>
                    <th>Precio Unidad</th>
                    <th>Subtotal</th>
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
						<td>'.number_format($reg->precio_unidad,0, ",", ".").'</td>
						<td>'.number_format($reg->sub_total,0, ",", ".").'</td>
					</tr>
				';
				$total=$total+$reg->sub_total;
			}
			echo 
			'
				<tfoot>

                    <tr>
                        <th>TOTAL</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>
                          <h4 id="h4_total_compra">$ '.number_format($total,0, ",", ".").'</h4>
                          <input type="hidden" name="input_total_compra" id="input_total_compra">
                        </th>
                    </tr>  
                </tfoot>
            ';
		break;

		// case donde se registra la entrada de componentes
		case 'registro_entrada_componentes':
			$id_registro = trim($_POST['id_registro_entrada_componentes']);
			$proveedor   = trim($_POST['proveedor']);
			$fecha       = trim($_POST['fecha']);
			$observacion = trim($_POST['observacion']);
			$total_compra= trim($_POST['input_total_compra']);

			$id_registro = limpiar_cadena($id_registro);
			$proveedor   = limpiar_cadena($proveedor);
			$fecha       = limpiar_cadena($fecha);
			$observacion = limpiar_cadena($observacion);
			$total_compra= limpiar_cadena($total_compra);

			if($id_registro=='vacio')
			{
				echo $obj_entrada_componentes->insertar($proveedor,$fecha,$observacion,$_POST["input_id_componente"],$_POST["input_cantidad"],$_POST["input_precio_compra"],7,$_SESSION['id'],$fecha_hora_actual,$total_compra);
			}
			else
			{
				echo $obj_entrada_componentes->editar($id_registro,$proveedor,$fecha,$observacion,8,$_SESSION['id'],$fecha_hora_actual);	
			}
		break;

		// case donde se traen los datos de una detemrinada entrada
		case 'traer_datos_entrada':
			$id_registro = trim($_POST['id']);
			$id_registro = limpiar_cadena($id_registro);
			echo json_encode($obj_entrada_componentes->traer_info_entrada($id_registro));
		break;
	}
}