var tbl_usuarios;
$('#select_tipo_usuario').select2();
function listar()
{
	tbl_usuarios=$('#tbl_usuarios').dataTable(
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
						title: 'Reporte Usuarios'
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
					url: '../controlador/gestion_usuarios.php?op=listar',
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

$("#form_registro_usuarios").on("submit",function(e)
{
    registrar_editar_usuarios(e);  
});

// función donde se abre el modal para actualizar los datos de un determinado usuario
function abrir_actualizar_usuario(id)
{
	$('#div_contenido_formulario').show();
    $('#div_contenido_tabla').hide();
    $('#div_btn_submit_form_registro_usuarios').html('<button   title="Guardar Información"  type="submit" class="negrita btn btn-warning"><i class="fa fa-edit"></i> Actualizar</button><button title="Cerrar Formulario"  onclick="cerrar_modal_registro_usuarios()" type="button" class="negrita btn btn-danger"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>');
    $('#id_titulo_formulario').text('Actualizar Información Usuario');
    traer_datos_usuario(id);
}

//función donde se abre el modal para registrar un determinado usuario
function abrir_modal_registro_usuarios()
{
    $('#div_contenido_formulario').show();
    $('#div_contenido_tabla').hide();
    $('#id_titulo_formulario').text('Registro Usuario');
    cargar_select_tipo_usuario();
}


function cargar_select_tipo_usuario()
{
	var options = '<option value="">Seleccione el tipo de usuario</option>';
	$.post("../controlador/gestion_usuarios.php", {op:'cargar_select_tipo_usuario' }, function(r)
	{
		var res_json = JSON.parse(r);
		res_json.forEach(elemento => {
			options+= `
				<option value=${elemento.tipo}>${elemento.tipo}</option>
			`
		});
	    $('#select_tipo_usuario').html(options);
	});
}

function cerrar_modal_registro_usuarios()
{
    $('#div_contenido_formulario').hide();
    $('#div_contenido_tabla').show();
    limpiar_campos_formulario();
}

function limpiar_campos_formulario()
{
	$('#id_registro_usuarios').val('vacio');
	$('#input_cedula').val('');
	$('#input_nombre').val('');
	$('#input_apellidos').val('');
	$('#select_tipo_usuario').html('');
	$('#div_btn_submit_form_registro_usuarios').html('<button  title="Guardar Información"  type="submit" class="negrita btn btn-success"><i class="fa fa-save"></i> Guardar</button><button title="Cerrar Formulario"  onclick="cerrar_modal_registro_usuarios()" type="button" class="negrita btn btn-danger"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>');
}

// función donde se procesa el formulario y se manda por ajax donde sus datos son guardados
function registrar_editar_usuarios(e)
{
	e.preventDefault(); //No se activará la acción predeterminada del evento
    var datos_formulario = new FormData($("#form_registro_usuarios")[0]);
    $.ajax(
    {
        url: "../controlador/gestion_usuarios.php",
        type: "POST",
        data: datos_formulario,
        contentType: false,
        processData: false,
        beforeSend: function()
        {
        	$('#div_btn_submit_form_registro_usuarios').html('<button  class="btn btn-primary btn-lg" type="button"><i class="fa fa-spinner fa-spin"></i> <b>Validando Información, Espere un Momento Por Favor</b></button>');
        },
        success: function(respuesta_registro)
        {
        	if( respuesta_registro==1 )
        	{
        		if( $('#id_registro_usuarios').val()=='vacio' )
        		{
        			toastr.success('Usuario registrado con exito, recuerde que el usuario y la clave para entrar al sistema es la cédula registrada ', 'Registro Exitoso');
        		}
        		else
        		{
        			toastr.warning('Información usuario actualizada con exito, recuerde que el usuario para ingresar a la plataforma es la cédula registrada ', 'Información Actualizada');
        		}
        		cerrar_modal_registro_usuarios();
        		tbl_usuarios.ajax.reload();
        	}
        	else if(respuesta_registro == 0 )
			{
				toastr.error('Se encontró un registro con la misma cédula ', 'Error');
			}
			else
			{
				toastr.error('Se presentraron problemas en el servidor al realizar la acción, por favor intentelo de nuevo', 'Error');
			}
			if( $('#id_registro_usuarios').val()=='vacio' )
    		{
    			$('#div_btn_submit_form_registro_usuarios').html('<button  title="Guardar Información"  type="submit" class="negrita btn btn-success"><i class="fa fa-save"></i> Guardar</button><button title="Cerrar Formulario"  onclick="cerrar_modal_registro_usuarios()" type="button" class="negrita btn btn-danger"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>');
    		}
    		else
    		{
    			$('#div_btn_submit_form_registro_usuarios').html('<button  title="Guardar Información"  type="submit" class="negrita btn btn-warning"><i class="fa fa-edit"></i> Actualizar</button><button title="Cerrar Formulario"  onclick="cerrar_modal_registro_usuarios()" type="button" class="negrita btn btn-danger"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>');
    		}	
		}
	});
}


// función donde se traen los datos de un determinado usuario para ser editados
function traer_datos_usuario(id)
{
	var options = '<option>Seleccione el tipo de usuario</option>';
	$.post("../controlador/gestion_usuarios.php", { id:id, op:'traer_datos_usuario' }, function(data, status)
    {
		var datos = JSON.parse(data);
		$('#id_registro_usuarios').val(id);
		$('#input_cedula').val(datos.cedula);
		$('#input_nombre').val(datos.nombre);
		$('#input_apellidos').val(datos.apellidos);
		$.post("../controlador/gestion_usuarios.php", {op:'cargar_select_tipo_usuario' }, function(r)
		{
			var res_json = JSON.parse(r);
			res_json.forEach(elemento => {
				options+= `
					<option value=${elemento.tipo}>${elemento.tipo}</option>
				`
			});
		    $('#select_tipo_usuario').html(options);
		    $('#select_tipo_usuario').val(datos.tipo);
		});
	});
}

// función donde según la guia se abre el modal para desactivar o activar un determinado usuario
function activar_desactivar_usuario(id,guia)
{
	if(guia=='0')
	{
		$('#modal_desactivar_usuario').modal('show');
		$('#btn_desactivar_usuario').val(id);
	}
	else
	{
		$('#modal_activar_usuario').modal('show');
		$('#btn_activar_usuario').val(id);		
	}
}

// función donde se cierra el modal para desactivar un determinado usuario
function cerrar_modal_desactivar_usuario()
{
	$('#modal_desactivar_usuario').modal('hide');
}

// función donde se cierra el modal para activar un determinado usuario
function cerrar_modal_activar_usuario()
{
	$('#modal_activar_usuario').modal('hide');
}

// función donde se activa un determinado usuario
function activar_usuario(id)
{
	$.post("../controlador/gestion_usuarios.php", {id:id, op:'activar_desactivar_usuario', guia:'1'}, function(r)
	{
		if( r==1 )
		{
			toastr.success('', 'Usuario Activado');
			tbl_usuarios.ajax.reload();
			cerrar_modal_activar_usuario();
		}
		else
		{
			toastr.error('Se presento un problema en el servidor al realizar la acción, por favor intentelo de nuevo ', 'Error');
		}
	});
}

/* **** D **** D **** D **** D **** D **** D **** D **** D **** D **** D **** D **** D **** D **** D **** D **** D **** D **** D **** D **** D */
// función donde se desactiva un determinado usuario
function desactivar_usuario(id)
{
	$.post("../controlador/gestion_usuarios.php", {id:id, op:'activar_desactivar_usuario', guia:'0'}, function(r)
	{
		if( r==1 )
		{
			toastr.success('', 'Usuario Desactivado');
			tbl_usuarios.ajax.reload();
			cerrar_modal_desactivar_usuario();
		}
		else
		{
			toastr.error('Se presento un problema en el servidor al realizar la acción, por favor intentelo de nuevo ', 'Error');
		}
	});
}

// función donde se restablece una determinaa contraseña
function abrir_restablecer_contrasena(id)
{
	$('#modal_restablecer_clave').modal('show');
	$('#btn_restablecer_clave').val(id);
}

// función donde se cierra el modal para restablecer contraseñas
function cerrar_modal_restablecer_clave()
{
	$('#modal_restablecer_clave').modal('hide');
}

// función donde se restablece la contraseña
function restablecer_clave(id)
{
	$.post("../controlador/gestion_usuarios.php", {id:id, op:'restablecer_clave'}, function(r)
	{
		if( r==1 )
		{
			toastr.info('Contraseña restablecida con exito, recuerde que la nueva contraseña para entrar al sistema es la cédula registrada del usuario', 'Contraseña Restablecida');
			tbl_usuarios.ajax.reload();
			cerrar_modal_restablecer_clave();
		}
		else
		{
			toastr.error('Se presento un problema en el servidor al realizar la acción, por favor intentelo de nuevo ', 'Error');
		}
	});
}