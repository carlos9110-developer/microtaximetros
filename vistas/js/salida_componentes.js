var tabla;
var cont=0;
var detalles=0;
function listar()
{
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
						title: 'Reporte Salida Componentes'
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
					url: '../controlador/salida_componentes.php?op=listar',
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
$("#form_registro_salida_componentes").on("submit",function(e)
{
    registrar_salida_componentes(e);  
});

// función donde se abre el formulario para registar una nueva salida de productos 
function abrir_modal_registro_salida_componentes()
{
	$('#div_contenido_tabla').hide();
	$('#div_contenido_formulario').show();
	$('#id_titulo_formulario').text('Registro Salida Componentes');
	$('#div_btn_submit_formulario').html('<button class="negrita btn btn-success" type="submit" id="btn_guardar"><i class="fa fa-save"></i> Guardar</button><button  id="btn_cerrar_formulario" class="negrita btn btn-danger" onclick="cerrar_modal_registro_salida_componentes()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>');
	$('#btn_abrir_registro_componentes').show();
	limpiar_form();
	listar_componentes();
}

// función donde se abre el modal para actualizar la información de una determinada salida 
function abrir_actualizar_salida(id)
{
	$('#div_contenido_tabla').hide();
	$('#div_contenido_formulario').show();
	$('#id_titulo_formulario').text('Actualizar Información Salida');
	$('#div_btn_submit_formulario').html('<button class="negrita btn btn-warning" type="submit" id="btn_guardar"><i class="fa fa-save"></i> Actualizar</button><button  id="btn_cerrar_formulario" class="negrita btn btn-danger" onclick="cerrar_modal_registro_salida_componentes()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>');
	$('#btn_abrir_registro_componentes').hide();
	traer_datos_salida(id);
	listar_componentes_salida(id);
}

// función donde se cierra el formulario para registrar la salida de componentes
function cerrar_modal_registro_salida_componentes()
{
	$('#div_contenido_tabla').show();
	$('#div_contenido_formulario').hide();
}

// función donde se limpia el formulario
function limpiar_form()
{
	$('#id_registro_salida_componentes').val('vacio');
	$('#fecha').val('');
	$('#observacion').val(''); 
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

// función donde se abre el modal y se carga la tabla con todos los componentes
function abrir_agregar_componente()
{
	$('#modal_detalles_salida').modal('show');
}

// función donde se cierra el modal que contiene todos los componentes para ser agregados
function cerrar_modal_detalles_salida()
{
	$('#modal_detalles_salida').modal('hide');
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
		toastr.error("Este componente ya fue cargado en el registro de salida actual","Error");
	}
}

// función donde se registra la salida de un determinado componente
function registrar_salida_componentes(e)
{
	e.preventDefault(); //No se activará la acción predeterminada del evento
    var datos_formulario = new FormData($("#form_registro_salida_componentes")[0]);
    $.ajax(
    {
        url: "../controlador/salida_componentes.php",
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
        		if( $('#id_registro_salida_componentes').val()=='vacio' )
        		{
        			toastr.success('Salida componentes registrada con exito','Salida Registrada');
        		}
        		else
        		{
        			toastr.warning('Información salida componentes actualizada exitosamente','Información Actualizada');
        		}
        		cerrar_modal_registro_salida_componentes();
        		listar();
        	}
        	else if(respuesta_registro == 0 )
			{
				toastr.error('Se  presentraron problemas en el servidor al realizar la acción, por favor intentelo de nuevo', 'Error');
				if( $('#id_registro_salida_componentes').val()=='vacio' )
	    		{
	    			$('#div_btn_submit_formulario').html('<button  class="negrita btn btn-success" type="submit" id="btn_guardar"><i class="fa fa-save"></i> Guardar</button><button  id="btn_cerrar_formulario" class="negrita btn btn-danger" onclick="cerrar_modal_registro_salida_componentes()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>');
	    		}
	    		else
	    		{
	    			$('#div_btn_submit_formulario').html('<button  class="negrita btn btn-warning" type="submit" id="btn_guardar"><i class="fa fa-edit"></i> Actualizar</button><button  id="btn_cerrar_formulario" class="negrita btn btn-danger" onclick="cerrar_modal_registro_salida_componentes()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>');
	    		}
			}
			else
			{
				toastr.error(respuesta_registro, 'Error');
				if( $('#id_registro_salida_componentes').val()=='vacio' )
	    		{
	    			$('#div_btn_submit_formulario').html('<button  class="negrita btn btn-success" type="submit" id="btn_guardar"><i class="fa fa-save"></i> Guardar</button><button  id="btn_cerrar_formulario" class="negrita btn btn-danger" onclick="cerrar_modal_registro_salida_componentes()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>');
	    		}
	    		else
	    		{
	    			$('#div_btn_submit_formulario').html('<button  class="negrita btn btn-warning" type="submit" id="btn_guardar"><i class="fa fa-edit"></i> Actualizar</button><button  id="btn_cerrar_formulario" class="negrita btn btn-danger" onclick="cerrar_modal_registro_salida_componentes()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>');
	    		}
			}	
		}
	});
}

function eliminar_componente(indice)
{
	$("#fila_"+indice).remove();
  	detalles=detalles-1;
  	evaluar();
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