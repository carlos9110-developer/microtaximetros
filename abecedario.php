/* **** A **** A **** A **** A **** A **** A **** A **** A **** A **** A **** A **** A **** A **** A **** A **** A **** A **** A **** A **** A */
/* **** B **** B **** B **** B **** B **** B **** B **** B **** B **** B **** B **** B **** B **** B **** B **** B **** B **** B **** B **** B */
/* **** C **** C **** C **** C **** C **** C **** C **** C **** C **** C **** C **** C **** C **** C **** C **** C **** C **** C **** C **** C */
/* **** D **** D **** D **** D **** D **** D **** D **** D **** D **** D **** D **** D **** D **** D **** D **** D **** D **** D **** D **** D */
/* **** E **** E **** E **** E **** E **** E **** E **** E **** E **** E **** E **** E **** E **** E **** E **** E **** E **** E **** E **** E */
/* **** F **** F **** F **** F **** F **** F **** F **** F **** F **** F **** F **** F **** F **** F **** F **** F **** F **** F **** F **** F */
/* **** G **** G **** G **** G **** G **** G **** G **** G **** G **** G **** G **** G **** G **** G **** G **** G **** G **** G **** G **** G */
/* **** H **** H **** H **** H **** H **** H **** H **** H **** H **** H **** H **** H **** H **** H **** H **** H **** H **** H **** H **** H */
/* **** I **** I **** I **** I **** I **** I **** I **** I **** I **** I **** I **** I **** I **** I **** I **** I **** I **** I **** I **** I */
/* **** J **** J **** J **** J **** J **** J **** J **** J **** J **** J **** J **** J **** J **** J **** J **** J **** J **** J **** J **** J */
/* **** K **** K **** K **** K **** K **** K **** K **** K **** K **** K **** K **** K **** K **** K **** K **** K **** K **** K **** K **** K */
/* **** L **** L **** L **** L **** L **** L **** L **** L **** L **** L **** L **** L **** L **** L **** L **** L **** L **** L **** L **** L */
/* **** M **** M **** M **** M **** M **** M **** M **** M **** M **** M **** M **** M **** M **** M **** M **** M **** M **** M **** M **** M */
/* **** N **** N **** N **** N **** N **** N **** N **** N **** N **** N **** N **** N **** N **** N **** N **** N **** N **** N **** N **** N */
/* **** O **** O **** O **** O **** O **** O **** O **** O **** O **** O **** O **** O **** O **** O **** O **** O **** O **** O **** O **** O */
/* **** P **** P **** P **** P **** P **** P **** P **** P **** P **** P **** P **** P **** P **** P **** P **** P **** P **** P **** P **** P */
/* **** Q **** Q **** Q **** Q **** Q **** Q **** Q **** Q **** Q **** Q **** Q **** Q **** Q **** Q **** Q **** Q **** Q **** Q **** Q **** Q */
/* **** R **** R **** R **** R **** R **** R **** R **** R **** R **** R **** R **** R **** R **** R **** R **** R **** R **** R **** R **** R */
/* **** S **** S **** S **** S **** S **** S **** S **** S **** S **** S **** S **** S **** S **** S **** S **** S **** S **** S **** S **** S */
/* **** T **** T **** T **** T **** T **** T **** T **** T **** T **** T **** T **** T **** T **** T **** T **** T **** T **** T **** T **** T */
/* **** U **** U **** U **** U **** U **** U **** U **** U **** U **** U **** U **** U **** U **** U **** U **** U **** U **** U **** U **** U */
/* **** V **** V **** V **** V **** V **** V **** V **** V **** V **** V **** V **** V **** V **** V **** V **** V **** V **** V **** V **** V */
/* **** W **** W **** W **** W **** W **** W **** W **** W **** W **** W **** W **** W **** W **** W **** W **** W **** W **** W **** W **** W */
/* **** X **** X **** X **** X **** X **** X **** X **** X **** X **** X **** X **** X **** X **** X **** X **** X **** X **** X **** X **** X */
/* **** Y **** Y **** Y **** Y **** Y **** Y **** Y **** Y **** Y **** Y **** Y **** Y **** Y **** Y **** Y **** Y **** Y **** Y **** Y **** Y */
/* **** Z **** Z **** Z **** Z **** Z **** Z **** Z **** Z **** Z **** Z **** Z **** Z **** Z **** Z **** Z **** Z **** Z **** Z **** Z **** Z */





// función donde se listan los usuarios 
	public static function listar($tipo,$guia)
	{
		$rspta=Usuario_modelo::listar($tipo,$guia);
 		//Vamos a declarar un array
 		$data= Array();
 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
				"0"=>$reg->id,
 				"1"=>$reg->cedula. "<br><div style='width:195px;'>
							<div class='btn-group'>
								<a onclick='abrir_actu_corto(".$reg->id.")'  class='btn btn-default btn-xs' title='Editar cedula o lugar de trabajo' data-toggle='tooltip' data-placement='right'><span class='fa fa-credit-card'></span></a>
								<a onclick='abrir_actu_usuario(".$reg->id.")' class='btn btn-warning btn-xs' title='Actualizar información usuario' data-toggle='tooltip' data-placement='right'><span class='fa fa-edit'></span></a>
								<a onclick='eliminar_usuario(".$reg->id.")'  class='btn btn-danger btn-xs' title='Eliminar registro' data-toggle='tooltip' data-placement='right'><span class='fa fa-trash'></span></a>
								<a onclick='abrir_cambiar_usuario(".$reg->id.")'  class='btn btn-default btn-xs' title='Cambiar tipo de usuario' data-toggle='tooltip' data-placement='right'><span class='fa fa-exchange-alt'></span></a>
								<a style='background: darkgrey;' onclick='ver_referidos(".$reg->id.")'  class='btn btn-xs' title='Ver referidos usuario' data-toggle='tooltip' data-placement='right'><span class='fa fa-users'></span></a>
								<a onclick='abrir_registro_solicitudes(".$reg->id.",".$reg->perfil.")'  class='btn btn-info btn-xs' title='Registrar solicitud' data-toggle='tooltip' data-placement='right'><span class='fa fa-exclamation-circle'></span></a>
								<a onclick='ver_solicitudes(".$reg->id.")'  class='btn btn-primary btn-xs' title='Ver solicitudes' data-toggle='tooltip' data-placement='right'><span class='fa fa-eye'></span></a>
								<a onclick='ver_observacion(".$reg->id.",".$reg->perfil.")' type='button' class='btn btn-default btn-xs' title='Ver observaciones' data-toggle='tooltip' data-placement='right'><span class='fa fa-search'></span>
								</a>
							</div></div>",
 				"2"=>$reg->nombres." ".$reg->apellidos."<div style='width:150px;'></div>",
 				"3"=>($reg->mes_cumple!="")? $reg->dia_cumple." de ".$reg->mes_cumple :'',
 				"4"=>$reg->municipio,
 				"5"=>$reg->barrio,
 				"6"=>$reg->direccion."<div style='width:150px;'></div>",
 				"7"=>$reg->ocupacion,
 				"8"=>$reg->telefono,
 				"9"=>$reg->celular,
 				"10"=>$reg->correo,
 				"11"=>($reg->hijos_menores!='')? $reg->hijos_menores:'',
				"12"=>$reg->genero,
 				"13"=>$reg->observacion,
 				"14"=>$reg->municipio_trabajo,
 				"15"=>$reg->nivel_trabajo
 				);
 		}
 		$results = array(
 			"sEcho"=>1, //Información para el datatables
 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
 			"aaData"=>$data);
 		return json_encode($results);
	}