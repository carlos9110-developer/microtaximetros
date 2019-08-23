$('[data-toggle="tooltip"]').tooltip();
$(".preloader").fadeOut();

$("#form_login").on('submit',function(e)
{
	e.preventDefault();
    logina=$("#usuario").val();
    clavea=$("#clave").val();
    $.post("../controlador/login.php", {login:logina,clave:clavea,op:'login'}, function(data)
    {
        //alert(data);
        if(data==0)
        {
            toastr.error('Usuario o contraseña incorrectos ', 'Error');
        }		
        else
        {	
           $(location).attr('href',data);       
        }
    });
});