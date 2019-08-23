var tbl_componentes;
function listar()
{
	tbl_componentes=$('#tbl_componentes').dataTable(
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
						title: 'Reporte Componentes'
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
					url: '../controlador/componentes.php?op=listar_secretario',
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
listar();
obtener_valor_inventario();

// función donde se trae el valor total de los componentes que hay la base de datos
function obtener_valor_inventario()
{
	$.post("../controlador/componentes.php", {  op:'obtener_valor_inventario' }, function(r)
    {
		$('#id_span_valor_total').text(r);
	});
}