var tabla;
function listar_entrada_componentes()
{
	var fecha_consulta = $('#fecha_consulta').val();
	tabla=$('#tbl_entrada_componentes').dataTable(
	{
		"aProcessing": true,//Activamos el procesamiento del datatables
	    "aServerSide": true,//Paginación y filtrado realizados por el servidor
		"language": { "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json" },
		"responsive":true,
	    dom: 'Bfrtip',//Definimos los elementos del control de tabla
	    buttons: [
					{
						text:      '<i style="color : green;" class="fas fa-file-excel fa-3x"></i>',
						extend:    'excelHtml5',
						titleAttr: 'Reporte Excel',
						title: 'Reporte Entrada Componentes Fecha '+fecha_consulta
					}
		        ],
		"columnDefs":
		[
			{
				"targets": [ 0 ], // pone invisibles las columnas que no se necesitan ver en el momento
				"visible": false,
				"searchable": false
			},
			{
				"targets": ['_all'], // pone invisibles las columnas que no se necesitan ver en el momento
				"orderable":false
			},
			{"className": "dt-center", "targets": "_all"}
		],
		"ajax":
				{
					url: '../controlador/entrada_componentes.php?op=listar_consulta_fecha',
					data:{fecha: fecha_consulta},
					type : "get",
					dataType : "json",
					
					error: function(e){
						console.log(e.responseText);	
					}
				},
		"bDestroy": true,
		"iDisplayLength": 5,//Paginación
	    "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
	}).DataTable();
}
listar_entrada_componentes();

// función donde se muestra el formulario para actualizar la información de una determinada entrada
function abrir_actualizar_entrada(id)
{
	$('#div_contenido_tabla').hide();
	$('#div_contenido_formulario').show();
	$('#id_titulo_formulario').text('Información Entrada');
	traer_datos_entrada(id);
	listar_detalles_entrada(id);
}

function cancelar_form()
{
	$('#div_contenido_tabla').show();
	$('#div_contenido_formulario').hide();
}

// función donde se traen los datos de un determinado componente para der editados
function traer_datos_entrada(id)
{
	$.post("../controlador/entrada_componentes.php", { id:id, op:'traer_datos_entrada' }, function(data, status)
    {
		var datos = JSON.parse(data);
		$('#id_registro_entrada_componentes').val(id);
		$('#proveedor').val(datos.proveedor);
		$('#fecha').val(datos.fecha);
		$('#observacion').val(datos.observacion);
	});
}

// función donde se listan los detalles de una determinada entrada
function listar_detalles_entrada(id)
{
	$.post("../controlador/entrada_componentes.php", { id:id, op:'listar_detalles_entrada' }, function(r)
    {
    	$('#tbl_detalles').show();
		$('#tbl_detalles').html(r);
	});
}