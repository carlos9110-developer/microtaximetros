var tabla;
$('#select_productos').select2();
function listar()
{
	tabla=$('#tbl_ordenes_ensamblado').dataTable(
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
						title: 'Reporte Ordenes Ensamblado'
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
					url: '../controlador/ordenes_ensamblado.php?op=listar',
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
$("#form_registro_orden_ensamblado").on("submit",function(e)
{
    registro_orden_ensamblado(e);  
});


// función donde se abre el modal para actualizar la fecha una determinada orden de ensamblado
function abrir_actualizar_fecha(id)
{
	$('#modal_actualizar_fecha').modal('show');
	$('#input_id_actualizar_fecha_orden').val(id);
	$.post("../controlador/ordenes_ensamblado.php", {op:'traer_fecha_orden', id_orden:id }, function(r)
	{
		$('#input_fecha_actualizar').val(r);
	});
}

// función donde se cierra el modal
function cerrar_modal_actualizar_fecha()
{
	$('#modal_actualizar_fecha').modal('hide');
}

// función donde se abre una determinado orden de ensamblado
function abrir_modal_registro_orden_ensamblado()
{
	cargar_productos();
	$('#div_contenido_tabla').hide();
	$('#div_contenido_formulario').show();
}

//función donde se carga el select con los productos seleccionados
function cargar_productos()
{
	var options = '<option value="">Seleccione el producto</option>';
	$.post("../controlador/productos.php", {op:'cargar_select_productos' }, function(r)
	{
		var res_json = JSON.parse(r);
		res_json.forEach(elemento =>{
			options+= `
				<option value=${elemento.id}>${elemento.nombre}</option>
			`
		});
	    $('#select_productos').html(options);
	});
}

// función donde se ven los componentes ncessarios para un prodcuto
function ver_componentes_necesarios()
{
	if(  $('#select_productos').val()!='' && $('#input_cantidad').val()!=''   )
	{
		$('#div_tbl_detalles').show();
		$.post("../controlador/ordenes_ensamblado.php", {op:'traer_componentes_necesarios', id_producto:$('#select_productos').val(), cantidad:$('#input_cantidad').val() }, function(r)
		{
			$('#tbl_detalles').html(r);
		});
	}
	else
	{
		toastr.error('Error debe elejir el producto y digitar la cantidad', 'Error');
		$('#div_tbl_detalles').hide();
		$('#tbl_detalles').html('');
	}
}

// función donde se cierra el formulario para registrar las ordenes de ensamblado
function cancelar_form()
{
	$('#div_contenido_tabla').show();
	$('#div_contenido_formulario').hide();
	$('#div_tbl_detalles').hide();
	$('#tbl_detalles').html('');
	limpiar_form();
}

// función donde se limpia el formulario para registrar las ordenes de ensamblado
function limpiar_form()
{
	$('#input_cantidad').val('');
	$('#input_fecha').val('');
}

// función donde se registra una determinada orden de ensamblado
function registro_orden_ensamblado(e)
{
	e.preventDefault(); //No se activará la acción predeterminada del evento
    var datos_formulario = new FormData($("#form_registro_orden_ensamblado")[0]);
    $.ajax(
    {
        url: "../controlador/ordenes_ensamblado.php",
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
        	//alert(respuesta_registro);
        	if( respuesta_registro==1 )
        	{
        		toastr.success('Orden de ensamblado registrada con exito','Orden Registrada');
        		cancelar_form();
        		listar();
        	}
        	else if(respuesta_registro == 0 )
			{
				toastr.error('Se  presentraron problemas en el servidor al realizar la acción, por favor intentelo de nuevo', 'Error');	    		
			}
			else
			{
				toastr.error('Se  presentraron problemas en el servidor al realizar la acción, por favor intentelo de nuevo', 'Error');
			}	
			$('#div_btn_submit_formulario').html('<button  class="negrita btn btn-md btn-primary"  type="button" onclick="ver_componentes_necesarios()"><i class="fa fa-search"></i> Ver Componentes Necesarios</button><button class="negrita btn btn-md btn-success"  type="submit"><i class="fa fa-save"></i> Confirmar Orden</button>');
		}
	});
}

// función donde se actualiza la fecha de una determinada orden
function actualizar_fecha()
{
	if(  $('#input_fecha_actualizar').val()!='' )
	{
		$.post("../controlador/ordenes_ensamblado.php", {op:'actualizar_fecha_orden', id_orden: $('#input_id_actualizar_fecha_orden').val(), fecha_nueva:$('#input_fecha_actualizar').val() }, function(r)
		{
			if(r==1)
			{
				toastr.success('Fecha orden actualizada correctamente','Fecha Corregida');
				cerrar_modal_actualizar_fecha();
				listar();
			}
			else
			{
				toastr.error('Error al actualizar la fecha, por favor intentelo de nuevo', 'Error');
			}
		});
	}
	else
	{
		toastr.error('Debe seleccionar la fecha de la orden que se va registrar', 'Error');
	}
}

// función donde se cierra el modal para marcar una detemrinada orden como finalizada
function cerrar_modal_confirmacion_registro_orden()
{
	$('#modal_confirmacion_registro_orden').modal('hide');
}

// función donde se abre el modal para confirmar si se quiere pasar una detemrinda orden como registrada
function abrir_marcar_registro_orden(id)
{
	$('#modal_confirmacion_registro_orden').modal('show');
	$('#btn_marcar_registro_orden').val(id);
}


// función donde se marca una detemrinada orden como rgistrada 
function marcar_registro_orden(id)
{
	$('#div_btn_modal_confirmacion_registro_orden').html('<button  class="btn btn-primary btn-md" type="button"><i class="fa fa-spinner fa-spin"></i> <b>Espere un Momento Por Favor</b></button>');
	$.post("../controlador/ordenes_ensamblado.php", {op:'marcar_orden_como_finalizada', id_orden:id  }, function(r)
	{
		//alert(r);
		if(r==1)
		{
			toastr.success('La orden se ha marcado como finalizada exitosamente','Orden Finalizada');
			cerrar_modal_confirmacion_registro_orden();
			listar();
		}
		else if(r==4)
		{
			toastr.error('No se puede marcar la orden como finalizada por que todavia quedan faltando unidades de componentes en el inventario', 'Error');
		}
		else if(r==3)
		{
			toastr.error('No fue posible actualizar el inventario, por favor intentelo de nuevo', 'Error');
		}
		else if(r==0)
		{
			toastr.error('Se presento un problema en el servidor, por favor intentelo de nuevo', 'Error');
		}
		else
		{
			toastr.error('Se presento un problema en el servidor, por favor intentelo de nuevo', 'Error');
		}
		$('#div_btn_modal_confirmacion_registro_orden').html('<button value='+id+' onclick="marcar_registro_orden(this.value)" id="btn_marcar_registro_orden"  data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Presione para poner orden como finilizada</b>" class="btn btn-default btn-md" type="button" ><b>ACEPTAR</b></button><button onclick="cerrar_modal_confirmacion_registro_orden()" data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Presione para cancelar la acción</b>" class="btn btn-default btn-md" type="button" ><b>CANCELAR</b></button>');
	});
}
