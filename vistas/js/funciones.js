// cargar elementos generales 
function cargar_elementos_generales()
{
	alertify.set('notifier','position', 'top-center');
	alertify.defaults.transition = "slide";
	alertify.defaults.theme.ok = "btn btn-primary";
	alertify.defaults.theme.cancel = "btn btn-danger";
	alertify.defaults.theme.input = "form-control";
	$('body').tooltip({ selector: '[data-toggle="tooltip"]' });
	alertify.set('notifier','delay', 6);
}

// función para tomar el valor de la variable por get
function obetener_url(name)
{
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
    results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

// función donde se vuelve a la interfaz anterior
function volver()
{
	window.history.back();
}

// función donde se refrescan las sesiones
function refrescar_sesiones()
{
	$.post("../controlador/refrescar_sesiones.php", function(r){});
}

refrescar_sesiones();
setInterval('refrescar_sesiones()',600000);// llama la función cada diez minutos
cargar_elementos_generales();
