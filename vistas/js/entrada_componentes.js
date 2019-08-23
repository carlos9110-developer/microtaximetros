var tabla;
var cont=0;// va llevando los id de los input que se agregan en las columnas
var detalles=0;//lleva la cuenta real de los detalles que hay

function listar_entrada_componentes()
{
	tabla=$('#tbl_entrada_componentes').dataTable(
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
						title: 'Reporte Entrada Componentes'
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
					url: '../controlador/entrada_componentes.php?op=listar',
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
listar_entrada_componentes();
$("#form_registro_entrada_componentes").on("submit",function(e)
{
    registrar_entrada_componentes(e);  
});

// función donde se abre el modal para registrar la entrada de unos determinados compoenetes
function abrir_modal_registro_entrada_componentes()
{
	$('#div_contenido_tabla').hide();
	$('#div_contenido_formulario').show();
	$('#id_titulo_formulario').text('Registro Entrada Componentes');
	$('#div_btn_submit_formulario').html('<button class="negrita btn btn-success" type="submit" id="btn_guardar"><i class="fa fa-save"></i> Guardar</button><button  id="btn_cerrar_formulario" class="negrita btn btn-danger" onclick="cerrar_modal_registro_entrada_componentes()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>');
	$('#btn_abrir_registro_componentes').show();
	limpiar_form();
	listar_componentes();
}

// función donde se muestra el formulario para actualizar la información de una determinada entrada
function abrir_actualizar_entrada(id)
{
	$('#div_contenido_tabla').hide();
	$('#div_contenido_formulario').show();
	$('#id_titulo_formulario').text('Actualizar Información Entrada');
	$('#div_btn_submit_formulario').html('<button  class="negrita btn btn-warning" type="submit" id="btn_guardar"><i class="fa fa-edit"></i> Actualizar</button><button  id="btn_cerrar_formulario" class="negrita btn btn-danger" onclick="cerrar_modal_registro_entrada_componentes()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>');
	$('#btn_abrir_registro_componentes').hide();
	traer_datos_entrada(id);
	listar_detalles_entrada(id);
}

// función donde se traen los datos de un determinado componente para der editados
function traer_datos_entrada(id)
{
	$.post("../controlador/entrada_componentes.php", { id:id, op:'traer_datos_entrada' }, function(data, status)
    {
		var datos = JSON.parse(data);
		$('#id_registro_entrada_componentes').val(id);
		$('#proveedor').val(datos.proveedor);
		$('#fecha').val(datos.fecha);
		$('#observacion').val(datos.observacion);
	});
}

// función donde se listan los detalles de una determinada entrada
function listar_detalles_entrada(id)
{
	$.post("../controlador/entrada_componentes.php", { id:id, op:'listar_detalles_entrada' }, function(r)
    {
    	$('#tbl_detalles').show();
		$('#tbl_detalles').html(r);
	});
}

function cerrar_modal_registro_entrada_componentes()
{
	$('#div_contenido_tabla').show();
	$('#div_contenido_formulario').hide();
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
	    var precio_compra=1;
	    if (id_componente!="")
	    {
	    	var sub_total	=	cantidad*precio_compra;
	    	var fila='<tr class="filas" id="fila_'+cont+'">'+
	    	'<td><button type="button" class="btn btn-danger" onclick="eliminar_componente('+cont+')">X</button></td>'+
	    	'<td><input  type="hidden" name="input_id_componente[]" value="'+id_componente+'">'+componente+'</td>'+
	    	'<td><input  type="number" min="1" name="input_cantidad[]" id="input_cantidad[]" value="'+cantidad+'"></td>'+
	    	'<td><input  type="number" min="1" step="any" name="input_precio_compra[]" id="input_precio_compra[]" value="'+precio_compra+'"></td>'+
	    	'<td><span   name="input_subtotal" id="input_subtotal_'+cont+'">'+sub_total+'</span></td>'+
	    	'<td><button type="button" onclick="modificar_subtotales()" class="btn btn-info"><i class="fa fa-sync-alt"></i></button></td>'+
	    	'</tr>';
	    	cont++;
	    	detalles=detalles+1;
	    	$('#tbl_detalles').append(fila);
	    	modificar_subtotales();
	    	toastr.success("Componente Agregado");
	    }
	    else
	    {
	    	toastr.error("Error al ingresar el detalle, revisar los datos del componente","Error");
	    }
	}
	else
	{
		toastr.error("Este componente ya fue cargado en el registro de entrada actual","Error");
	}
}

// función donde se limpia el formulario
function limpiar_form()
{
	$('#id_registro_entrada_componentes').val('vacio');
	$('#proveedor').val('');
	$('#fecha').val('');
	$('#observacion').val(''); 
    cont=0;
    detalles=0;
    $(".filas").remove();
    evaluar();
}

// función donde se registra la entrada de un determinado componente
function registrar_entrada_componentes(e)
{
	modificar_subtotales();
	e.preventDefault(); //No se activará la acción predeterminada del evento
    var datos_formulario = new FormData($("#form_registro_entrada_componentes")[0]);
    $.ajax(
    {
        url: "../controlador/entrada_componentes.php",
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
        		if( $('#id_registro_entrada_componentes').val()=='vacio' )
        		{
        			toastr.success('Entrada componentes registrada con exito','Entrada Registrada');
        		}
        		else
        		{
        			toastr.warning('Información entrada componentes actualizada exitosamente','Información Actualizada');
        		}
        		cerrar_modal_registro_entrada_componentes();
        		listar_entrada_componentes();
        	}
        	else if(respuesta_registro == 0 )
			{
				toastr.error('Se  presentraron problemas en el servidor al realizar la acción, por favor intentelo de nuevo', 'Error');
				if( $('#id_registro_entrada_componentes').val()=='vacio' )
	    		{
	    			$('#div_btn_submit_formulario').html('<button  class="negrita btn btn-success" type="submit" id="btn_guardar"><i class="fa fa-save"></i> Guardar</button><button  id="btn_cerrar_formulario" class="negrita btn btn-danger" onclick="cerrar_modal_registro_entrada_componentes()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>');
	    		}
	    		else
	    		{
	    			$('#div_btn_submit_formulario').html('<button  class="negrita btn btn-warning" type="submit" id="btn_guardar"><i class="fa fa-edit"></i> Actualizar</button><button  id="btn_cerrar_formulario" class="negrita btn btn-danger" onclick="cerrar_modal_registro_entrada_componentes()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>');
	    		}
			}
			else
			{
				toastr.error('Se presentraron problemas en el servidor al realizar la acción, por favor intentelo de nuevo', 'Error');
				if( $('#id_registro_entrada_componentes').val()=='vacio' )
	    		{
	    			$('#div_btn_submit_formulario').html('<button  class="negrita btn btn-success" type="submit" id="btn_guardar"><i class="fa fa-save"></i> Guardar</button><button  id="btn_cerrar_formulario" class="negrita btn btn-danger" onclick="cerrar_modal_registro_entrada_componentes()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>');
	    		}
	    		else
	    		{
	    			$('#div_btn_submit_formulario').html('<button  class="negrita btn btn-warning" type="submit" id="btn_guardar"><i class="fa fa-edit"></i> Actualizar</button><button  id="btn_cerrar_formulario" class="negrita btn btn-danger" onclick="cerrar_modal_registro_entrada_componentes()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>');
	    		}
			}	
		}
	});
}

// función donde se modifican los subtotales de la tabla
function modificar_subtotales()
{
	var cant = document.getElementsByName("input_cantidad[]");// se estan almacenando todas las cantidades que hay en un array
    var prec = document.getElementsByName("input_precio_compra[]");// se estan almacenando todos los precios compra en un array
    var sub = document.getElementsByName("input_subtotal");
    // el ciclo recorre todas las filas de la tabla donde se cargan los productos a comprar
    for (var i = 0; i < cant.length; i++)
    {
		var inpC=cant[i];
		var inpP=prec[i];
		var inpS=sub[i];
		inpS.value=inpC.value * inpP.value;
		document.getElementsByName("input_subtotal")[i].innerHTML = '$ '+formatNumber.new(inpS.value); // se hace con esta forma por que los id no van a coincidir con la variable i en cambio por el name el busca todos los span que tengan el name subtotal y ya el indicador o número va incrementando de 0 hasta el final 
    }
    calcular_totales();
}

// function donde se actualiza el total del ingreso actual
function calcular_totales()
{
  	var sub = document.getElementsByName("input_subtotal");// se almacenan todos los subtotales en un array llamado sub
  	var total = 0.0;

  	for (var i = 0; i < sub.length; i++)
  	{
		total += document.getElementsByName("input_subtotal")[i].value;
	}
	$("#h4_total_compra").html("$ " + formatNumber.new(total));
    $("#input_total_compra").val(total);
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

// función donde se abre el modal y se carga la tabla con todos los componentes
function abrir_agregar_componente()
{
	$('#modal_detalles_entrada').modal('show');
}

// función donde se cierra el modal que contiene todos los componentes para ser agregados
function cerrar_modal_detalles_entrada()
{
	$('#modal_detalles_entrada').modal('hide');
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

// función donde se elimina un determinado detalle
function eliminar_componente(indice)
{
	$("#fila_"+indice).remove();
  	calcular_totales();
  	detalles=detalles-1;
  	evaluar();
}


var formatNumber={
 separador: ".", // separador para los miles
 sepDecimal: ',', // separador para los decimales
 formatear:function (num){
 num +='';
 var splitStr = num.split('.');
 var splitLeft = splitStr[0];
 var splitRight = splitStr.length > 1 ? this.sepDecimal + splitStr[1] : '';
 var regx = /(\d+)(\d{3})/;
 while (regx.test(splitLeft)) {
 splitLeft = splitLeft.replace(regx, '$1' + this.separador + '$2');
 }
 return this.simbol + splitLeft +splitRight;
 },
 new:function(num, simbol){
 this.simbol = simbol ||'';
 return this.formatear(num);
 }
}