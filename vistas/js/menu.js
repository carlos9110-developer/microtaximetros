// bloque de codigo donde estan las funcionalidades de los elementos del menú en la vista
$(function()
{
     if (window.location == window.parent.location)
    {
            $('#fullscreen').html('<span class="glyphicon glyphicon-resize-small"></span>');
            $('#fullscreen').attr('href', 'http://bootsnipp.com/mouse0270/snippets/PbDb5');
            $('#fullscreen').attr('title', 'Back To Bootsnipp');
    } 
    $('#fullscreen').on('click', function(event)
    {
            event.preventDefault();
            window.parent.location =  $('#fullscreen').attr('href');
    });
    $('#fullscreen').tooltip();
    $('.navbar-toggler').on('click', function(event)
    {
		event.preventDefault();
		$(this).closest('.navbar-minimal').toggleClass('open');
	}); 
});
// bloque de codigo donde estan las funcionalidades de los elementos del menú en la vista

function cerrar_sesion()
{
    window.location.href = "login.php";
}

function abrir_actualizar_datos_usuario(id)
{
    $('#modal_actualizar_datos_usuario').modal('show');
    $.post("../controlador/gestion_usuarios.php", {  op:'traer_datos_usuario_2' }, function(data, status)
    {
        var datos = JSON.parse(data);
        $('#input_cedula_edit').val(datos.cedula);
        $('#input_nombre_edit').val(datos.nombre);
        $('#input_apellidos_edit').val(datos.apellidos);
    });
}


function cerrar_modal_actualizar_datos_usuario()
{
    $('#modal_actualizar_datos_usuario').modal('hide');
}

function actualizar_datos_usuarios()
{
    if( $('#input_cedula_edit')!='' &&  $('#input_nombre_edit')!=''  &&  $('#input_apellidos_edit')!='' )
    {
        $('#div_btn_submit_formulario_actualizar_datos_edit').html('<button  class="btn btn-primary btn-md" type="button"><i class="fa fa-spinner fa-spin"></i> <b>Validando Información</b></button>');
        $.post("../controlador/gestion_usuarios.php", {  op:'actualizar_datos_usuario', input_cedula_edit: $('#input_cedula_edit').val(),input_nombre_edit: $('#input_nombre_edit').val(),input_apellidos_edit: $('#input_apellidos_edit').val()  }, function(r)
        {
            if( r!=0 )
            {
                toastr.success('Datos actualizados correctamente, recuerde que el usuario para entrar a la base de datos, es la cédula registrada', 'Datos Actualizados');
                cerrar_modal_actualizar_datos_usuario();
                $('.nombre_usuario_plantilla').text(r);
            }
            else
            {
                toastr.error('Se encontró un registro con la misma cédula', 'Error');
            }
            $('#div_btn_submit_formulario_actualizar_datos_edit').html('<button type="button" onclick="actualizar_datos_usuarios()"  class="btn  btn-warning btn-md"><i class="fa fa-edit"></i><b> Actualizar</b></button><button type="button" onclick="cerrar_modal_actualizar_datos_usuario()"  class="btn btn-danger btn-md" ><i class="fa fa-times-circle"></i><b> Cerrar</b></button>');
        });
    }   
    else
    {
        toastr.error('Error debe digitar todos los campos', 'Error');
    }
}

// función donde se actualiza la clave de un determinado usuario
function actualizar_clave_personal()
{
    $.post("../controlador/gestion_usuarios.php", {  op:'cambiar_clave_personal', input_clave_actu_1: $('#input_clave_actu_1').val(),input_clave_actu_2: $('#input_clave_actu_2').val(),input_clave_actu_3: $('#input_clave_actu_3').val()  }, function(respuesta_registro)
    {
        if( respuesta_registro==1 )
        {
            toastr.success('Clave actualizada exisotamente', 'Contraseña Actualizada');
            cerrar_cambiar_clave();
        }
        else if( respuesta_registro==2)
        {
            toastr.error('Las contraseñas no coinciden', 'Error');
        }
        else if(respuesta_registro==4)
        {
            toastr.error('La contraseña actual, no coincide con la ingresada', 'Error');
        }
        else
        {
            toastr.error('Se presento un problema en el servidor al realizar la acción, por favor intentelo de nuevo', 'Error');
        }
        $('#div_btn_submit_formulario_actualizar_clave_usuario').html('<button type="button" onclick="actualizar_clave_personal()" class="btn  btn-primary btn-md"><i class="fa fa-key"></i><b> Actualizar Clave</b></button><button type="button" onclick="cerrar_cambiar_clave()"  class="btn btn-danger btn-md" ><i class="fa fa-times-circle"></i><b> Cerrar</b></button>');
    });
}

// función donde se abre el modal para cambiar la contraseña
function abrir_cambiar_clave()
{
    $('#modal_cambiar_clave').modal('show');
}

// función donde se cierra el modal para cambiar la contraseña
function cerrar_cambiar_clave()
{
    $('#modal_cambiar_clave').modal('hide');
    $('#input_clave_actu_1').val('');
    $('#input_clave_actu_2').val('');
    $('#input_clave_actu_3').val('');
}




