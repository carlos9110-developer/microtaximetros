var tbl_componentes;
function listar()
{
	tbl_componentes=$('#tbl_componentes').dataTable(
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
					url: '../controlador/componentes.php?op=listar',
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

$("#form_registro_componentes").on("submit",function(e)
{
    registrar_editar_componentes(e);  
});

// función donde se trae el valor total de los componentes que hay la base de datos
function obtener_valor_inventario()
{
	$.post("../controlador/componentes.php", {  op:'obtener_valor_inventario' }, function(r)
    {
		$('#id_span_valor_total').text(r);
	});
}

// función donde se muestra el formulario para registrar la información de un determinado componente
function abrir_modal_registro_componentes()
{
	$('#div_contenido_tabla').hide();
	$('#div_contenido_formulario').show();
	$('#id_titulo_formulario').text("Registro Componente");
}

// función  donde se muestra el formulario para editar la información de un componente
function abrir_actualizar_componente(id)
{
	$('#div_contenido_tabla').hide();
	$('#div_contenido_formulario').show();
	$('#id_titulo_formulario').text("Actualizar Información Componente");
	$('#div_btn_submit_form_registro_componentes').html('<button  title="Guardar Información"  type="submit" class="negrita btn btn-warning"><i class="fa fa-edit"></i> Actualizar</button><button title="Cerrar Formulario"  onclick="cerrar_modal_registro_componentes()" type="button" class="negrita btn btn-danger"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>');
	traer_datos_componente(id);
}

// función donde se traen los datos de un determinado componente para der editados
function traer_datos_componente(id)
{
	$.post("../controlador/componentes.php", { id:id, op:'traer_datos_componente' }, function(data, status)
    {
		var datos = JSON.parse(data);
		$('#id_registro_componentes').val(id);
		$('#input_nombre').val(datos.nombre);
		$('#input_referencia').val(datos.referencia);
		$('#input_cantidad_minima').val(datos.cantidad_minima);
	});
}

// función donde se cierra el formulario para registrar la información de un determinado componente
function cerrar_modal_registro_componentes()
{
	$('#div_contenido_tabla').show();
	$('#div_contenido_formulario').hide();
	limpiar_form();
}

// función donde se limpian los campos del formulario
function limpiar_form()
{
	$('#id_registro_componentes').val('vacio');
	$('#input_nombre').val('');
	$('#input_referencia').val('');
	$('#input_cantidad_minima').val('');
	$('#div_btn_submit_form_registro_componentes').html('<button  title="Guardar Información"  type="submit" class="negrita btn btn-success"><i class="fa fa-save"></i> Guardar</button><button title="Cerrar Formulario"  onclick="cerrar_modal_registro_componentes()" type="button" class="negrita btn btn-danger"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>');
}

// función donde se edita o registra un componente
function registrar_editar_componentes(e)
{
	e.preventDefault(); //No se activará la acción predeterminada del evento
    var datos_formulario = new FormData($("#form_registro_componentes")[0]);
    $.ajax(
    {
        url: "../controlador/componentes.php",
        type: "POST",
        data: datos_formulario,
        contentType: false,
        processData: false,
        beforeSend: function()
        {
        	$('#div_btn_submit_form_registro_componentes').html('<button  class="btn btn-primary btn-lg" type="button"><i class="fa fa-spinner fa-spin"></i> <b>Validando Información, Espere un Momento Por Favor</b></button>');
        },
        success: function(respuesta_registro)
        {
        	if( respuesta_registro==1 )
        	{
        		if( $('#id_registro_componentes').val()=='vacio' )
        		{
        			toastr.success('Componente registrado exitosamente','Registro Exitoso');
        		}
        		else
        		{
        			toastr.warning('Información componente actualizada exitosamente','Información Actualizada');
        		}
        		cerrar_modal_registro_componentes();
        		tbl_componentes.ajax.reload();
        	}
        	else if(respuesta_registro == 0 )
			{
				toastr.error('Se encontró un componente con el mismo nombre ', 'Error');
			}
			else
			{
				toastr.error('Se presentraron problemas en el servidor al realizar la acción, por favor intentelo de nuevo', 'Error');
			}

			if( $('#id_registro_componentes').val()=='vacio' )
    		{
    			$('#div_btn_submit_form_registro_componentes').html('<button  title="Guardar Información"  type="submit" class="negrita btn btn-success"><i class="fa fa-save"></i> Guardar</button><button title="Cerrar Formulario"  onclick="cerrar_modal_registro_componentes()" type="button" class="negrita btn btn-danger"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>');
    		}
    		else
    		{
    			$('#div_btn_submit_form_registro_componentes').html('<button  title="Guardar Información"  type="submit" class="negrita btn btn-warning"><i class="fa fa-edit"></i> Actualizar</button><button title="Cerrar Formulario"  onclick="cerrar_modal_registro_componentes()" type="button" class="negrita btn btn-danger"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>');
    		}	
		}
	});
}