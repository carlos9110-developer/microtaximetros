var tabla;
var cont=0;// va llevando los id de los input que se agregan en las columnas
var detalles=0;//lleva la cuenta real de los detalles que hay
function listar()
{
	tabla=$('#tbl_productos').dataTable(
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
						title: 'Reporte Productos'
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
					url: '../controlador/productos.php?op=listar',
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
	$.post("../controlador/productos.php", {  op:'obtener_valor_inventario' }, function(r)
    {
		$('#id_span_valor_total').text(r);
	});
}

$("#form_registro_productos").on("submit",function(e)
{
    registrar_productos(e);  
});




// función donde se abre el formulario para registrar un nuevo producto
function abrir_modal_registro_productos()
{
	$('#div_contenido_tabla').hide();
	$('#div_contenido_formulario').show();
	$('#id_titulo_formulario').text('Registro Producto');
	$('#div_btn_submit_formulario').html('<button class="negrita btn btn-success" type="submit" id="btn_guardar"><i class="fa fa-save"></i> Guardar</button><button  id="btn_cerrar_formulario" class="negrita btn btn-danger" onclick="cerrar_modal_registro_productos()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>');
	$('#btn_abrir_registro_componentes').show();
	limpiar_form();
	listar_componentes();
}

// función donde se abre el modal para actualizar la información de un determinado producto
function abrir_actualizar_producto(id)
{
	$('#div_contenido_tabla').hide();
	$('#div_contenido_formulario').show();
	$('#id_titulo_formulario').text('Actualizar Información Producto');
	$('#div_btn_submit_formulario').html('<button class="negrita btn btn-warning" type="submit" id="btn_guardar"><i class="fa fa-edit"></i> Actualizar</button><button  id="btn_cerrar_formulario" class="negrita btn btn-danger" onclick="cerrar_modal_registro_productos()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>');
	$('#btn_abrir_registro_componentes').hide();
	traer_datos_producto(id);
	listar_componentes_producto(id);
}

// función donde se traen los datos de un detemrinado producto
function traer_datos_producto(id)
{
	$.post("../controlador/productos.php", { id:id, op:'traer_datos_producto' }, function(data, status)
    {
		var datos = JSON.parse(data);
		$('#id_registro_productos').val(id);
		$('#nombre').val(datos.nombre);
		$('#referencia').val(datos.referencia);
	});
}

// función donde se cierra el formulario para registrar productos
function cerrar_modal_registro_productos()
{
	$('#div_contenido_tabla').show();
	$('#div_contenido_formulario').hide();
}

// función donde se limpia el formulario
function limpiar_form()
{
	$('#id_registro_productos').val('vacio');
	$('#nombre').val('');
	$('#referencia').val('');
    cont=0;
    detalles=0;
    $(".filas").remove();
    evaluar();
}

// función donde se evalua para saber si se muestra el botón o no de guardar
function evaluar()
{
  	if (detalles>0)
    {
      $("#btn_guardar").show()
      $('#tbl_detalles').show();
    }
    else
    {
      $("#btn_guardar").hide(); 
      $('#tbl_detalles').hide();
      cont=0;
    }
}

// función donde se cargan los componentes registrados actualmente con su respectiva información
function listar_componentes()
{
	tabla=$('#tbl_componentes').dataTable(
	{
		"aProcessing": true,//Activamos el procesamiento del datatables
	    "aServerSide": true,//Paginación y filtrado realizados por el servidor
		"language": { "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json" },
		"responsive":true,
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
					url: '../controlador/componentes.php?op=listar_componentes_modulo_entrada_componentes',
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

// función donde se registra o edita un detemrinado product


// función donde se abre el modal y se carga la tabla con todos los componentes
function abrir_agregar_componente()
{
	$('#modal_detalles_producto').modal('show');
}

// función donde se cierra el modal que contiene todos los componentes para ser agregados
function cerrar_modal_detalles_producto()
{
	$('#modal_detalles_producto').modal('hide');
}

function agregar_detalle(id_componente,componente)
{
	var confirmacion = 1; // si se encuentra el artículo esta pasara a cero
	var array 	     = document.getElementsByName("input_id_componente[]");
	for (var i = 0; i < array.length; i++)
	{
		var guia = array[i];
		if(guia.value == id_componente)
		{
			confirmacion = 0;
			i = array.length;
		}
	}
	if(confirmacion==1)
	{
		var cantidad=1;
	    if (id_componente!="")
	    {
	    	var fila='<tr class="filas" id="fila_'+cont+'">'+
	    	'<td><button type="button" class="btn btn-danger" onclick="eliminar_componente('+cont+')">X</button></td>'+
	    	'<td><input  type="hidden" name="input_id_componente[]" value="'+id_componente+'">'+componente+'</td>'+
	    	'<td><input  type="number" min="1" name="input_cantidad[]" id="input_cantidad[]" value="'+cantidad+'"></td>'+
	    	'</tr>';
	    	cont++;
	    	detalles=detalles+1;
	    	$('#tbl_detalles').append(fila);
	    	evaluar();
	    	toastr.success("Componente Agregado");
	    }
	    else
	    {
	    	toastr.error("Error al ingresar el detalle, revisar los datos del componente","Error");
	    }
	}
	else
	{
		toastr.error("Este componente ya fue cargado entre los componentes del producto actual","Error");
	}
}

// función donde se registran los productos
function registrar_productos(e)
{
	e.preventDefault(); //No se activará la acción predeterminada del evento
    var datos_formulario = new FormData($("#form_registro_productos")[0]);
    $.ajax(
    {
        url: "../controlador/productos.php",
        type: "POST",
        data: datos_formulario,
        contentType: false,
        processData: false,
        beforeSend: function()
        {
        	$('#div_btn_submit_formulario').html('<button  class="btn btn-primary btn-lg" type="button"><i class="fa fa-spinner fa-spin"></i> <b>Validando Información, Espere un Momento Por Favor</b></button>');
        },
        success: function(respuesta_registro)
        {
        	if( respuesta_registro==1 )
        	{
        		if( $('#id_registro_productos').val()=='vacio' )
        		{
        			toastr.success('Producto registrado con exito','Producto Registrado');
        		}
        		else
        		{
        			toastr.warning('Información producto actualizada exitosamente','Información Actualizada');
        		}
        		cerrar_modal_registro_productos();
        		listar();
        	}
        	else if(respuesta_registro == 0 )
			{
				toastr.error('Se encontro un producto con el mismo nombre', 'Error');
				if( $('#id_registro_productos').val()=='vacio' )
	    		{
	    			$('#div_btn_submit_formulario').html('<button class="negrita btn btn-success" type="submit" id="btn_guardar"><i class="fa fa-save"></i> Guardar</button><button  id="btn_cerrar_formulario" class="negrita btn btn-danger" onclick="cerrar_modal_registro_productos()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>');
	    		}
	    		else
	    		{
	    			$('#div_btn_submit_formulario').html('<button class="negrita btn btn-warning" type="submit" id="btn_guardar"><i class="fa fa-edit"></i> Actualizar</button><button  id="btn_cerrar_formulario" class="negrita btn btn-danger" onclick="cerrar_modal_registro_productos()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>');
	    		}
			}
			else
			{
				toastr.error('Se presentraron problemas en el servidor al realizar la acción, por favor intentelo de nuevo', 'Error');
				if( $('#id_registro_entrada_componentes').val()=='vacio' )
	    		{
	    			$('#div_btn_submit_formulario').html('<button class="negrita btn btn-success" type="submit" id="btn_guardar"><i class="fa fa-save"></i> Guardar</button><button  id="btn_cerrar_formulario" class="negrita btn btn-danger" onclick="cerrar_modal_registro_productos()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>');
	    		}
	    		else
	    		{
	    			$('#div_btn_submit_formulario').html('<button class="negrita btn btn-warning" type="submit" id="btn_guardar"><i class="fa fa-edit"></i> Actualizar</button><button  id="btn_cerrar_formulario" class="negrita btn btn-danger" onclick="cerrar_modal_registro_productos()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>');
	    		}
			}	
		}
	});
}

// función donde se listan los componentes de un determinado producto
function listar_componentes_producto(id)
{
	$.post("../controlador/productos.php", { id:id, op:'listar_componentes_producto' }, function(r)
    {
    	$('#tbl_detalles').show();
		$('#tbl_detalles').html(r);
	});
}

// función donde se elimina un determinado detalle
function eliminar_componente(indice)
{
	$("#fila_"+indice).remove();
  	detalles=detalles-1;
  	evaluar();
}

// función donde según la guia se abre el modal para desactivar o activar un determinado usuario
function activar_desactivar_producto(id,guia)
{
	if(guia=='0')
	{
		$('#modal_desactivar_producto').modal('show');
		$('#btn_desactivar_producto').val(id);
	}
	else
	{
		$('#modal_activar_producto').modal('show');
		$('#btn_activar_producto').val(id);		
	}
}

// función donde se cierra el modal para desactuivar un determinado producto
function cerrar_modal_desactivar_producto()
{
	$('#modal_desactivar_producto').modal('hide');
}

// función donde se cierra el modal para activar un detemrinado producto
function cerrar_modal_activar_producto()
{
	$('#modal_activar_producto').modal('hide');
}


// función donde se desactiva un determinado producto
function desactivar_producto(id)
{
	$.post("../controlador/productos.php", {id:id, op:'activar_desactivar_producto', guia:'0'}, function(r)
	{
		if( r==1 )
		{
			toastr.success('', 'Producto Desactivado');
			tabla.ajax.reload();
			cerrar_modal_desactivar_producto();
		}
		else
		{
			toastr.error('Se presento un problema en el servidor al realizar la acción, por favor intentelo de nuevo ', 'Error');
		}
	});
}

// función donde se actuiva un determinado producto
function activar_producto(id)
{
	$.post("../controlador/productos.php", {id:id, op:'activar_desactivar_producto', guia:'1'}, function(r)
	{
		if( r==1 )
		{
			toastr.success('', 'Producto Activado');
			tabla.ajax.reload();
			cerrar_modal_activar_producto();
		}
		else
		{
			toastr.error('Se presento un problema en el servidor al realizar la acción, por favor intentelo de nuevo ', 'Error');
		}
	});
}