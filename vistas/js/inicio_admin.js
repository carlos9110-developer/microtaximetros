var tabla;
function inicio()
{
	$.post("../controlador/componentes.php", { op:'traer_numero_componentes_agotados'}, function(r)
	{
		$('#btn_ver_componentes_agotados').val(r);
		$('#id_b_total_componentes_agodatos').text(r);
	});
	$.post("../controlador/ordenes_ensamblado.php", { op:'traer_numero_ordenes_pendientes'}, function(r_2)
	{
		$('#btn_ver_ordenes_pendientes').val(r_2);
		$('#id_b_total_ordenes_pendientes').text(r_2);
	});
}
inicio();
setInterval('inicio()',300000);// llama la función cada cinco minutos

// función donde se listan los componentes que estan agotados
function listar_componentes_agotados()
{
	tabla=$('#tbl_componentes_agotados').dataTable(
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
						title: 'Reporte Componentes Agotados'
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
					url: '../controlador/componentes.php?op=listar_componentes_agotados',
					type : "get",
					dataType : "json",
					error: function(e)
					{
						console.log(e.responseText);	
					}
				},
		"bDestroy": true,
		"iDisplayLength": 5,//Paginación
	    "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
	}).DataTable();
}

// función donde se listan las ordenes en estado pendiente
function listar_ordenes_pendientes()
{
	tabla=$('#tbl_ordenes_pendientes').dataTable(
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
						title: 'Reporte Ordenes Pendientes'
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
					url: '../controlador/ordenes_ensamblado.php?op=listar_ordenes_pendientes',
					type : "get",
					dataType : "json",
					error: function(e)
					{
						console.log(e.responseText);	
					}
				},
		"bDestroy": true,
		"iDisplayLength": 5,//Paginación
	    "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
	}).DataTable();
}



// Función  donde se abre el modal para ver los componentes que faltan
function ver_componentes_agotados(valor)
{
	if( valor > 0)
	{
		$('#modal_componentes_agotados').modal('show');
		listar_componentes_agotados();
	}
	else
	{
		toastr.error('No hay ningún componente con las unidades agotadas', 'Error');
	}
}

// función donde se cierra el modal para ver los componentes agotados
function cerrar_modal_componentes_agotados()
{
	$('#modal_componentes_agotados').modal('hide');
}

// función donde se muestran las ordenes de ensamblado que estan en estado pendient
function ver_ordenes_pendientes(valor)
{
	if( valor > 0)
	{
		$('#modal_ordenes_pendientes').modal('show');
		listar_ordenes_pendientes();
	}
	else
	{
		toastr.error('No hay ninguna orden en estado pendiente', 'Error');
	}
}

// función donde se cierra el modal para ver las ordenes pendientes
function cerrar_modal_ordenes_pendientes()
{
	$('#modal_ordenes_pendientes').modal('hide');
}