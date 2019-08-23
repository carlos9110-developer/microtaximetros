var tabla;
function listar_salida_componentes()
{
	var fecha_consulta = $('#fecha_consulta').val();
	tabla=$('#tbl_salida_componentes').dataTable(
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
						title: 'Reporte Salida Componentes Fecha '+fecha_consulta
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
					url: '../controlador/salida_componentes.php?op=listar_fecha_consulta',
					type : "get",
					data:{fecha: fecha_consulta},
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
listar_salida_componentes();


// función donde se abre el modal para actualizar la información de una determinada salida 
function abrir_actualizar_salida(id)
{
	$('#div_contenido_tabla').hide();
	$('#div_contenido_formulario').show();
	$('#id_titulo_formulario').text('Información Salida');
	traer_datos_salida(id);
	listar_componentes_salida(id);
}

function cancelar_form()
{
	$('#div_contenido_tabla').show();
	$('#div_contenido_formulario').hide();
}

// función donde se traen los datos de una detemrinada salida
function traer_datos_salida(id)
{
	$.post("../controlador/salida_componentes.php", { id:id, op:'traer_datos_salida' }, function(data, status)
    {
		var datos = JSON.parse(data);
		$('#id_registro_salida_componentes').val(id);
		$('#fecha').val(datos.fecha);
		$('#observacion').val(datos.observacion);
	});
}

// función donde se listan los componentes de una determinada salida
function listar_componentes_salida(id)
{
	$.post("../controlador/salida_componentes.php", { id:id, op:'listar_componentes_salida' }, function(r)
    {
    	$('#tbl_detalles').show();
		$('#tbl_detalles').html(r);
	});
}