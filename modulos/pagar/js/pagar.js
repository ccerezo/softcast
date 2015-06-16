var Pagar = 
{
	listData : null,
	objData : null,

	init : function()
	{
		Pagar.LoadPlanCuentas();

		$('#modal_maestro_prov').on('show.bs.modal', function (e) {
			$('#title_ventana').html('Nuevo Provedor');
  			$('#registro_cuenta').trigger("reset"); // LImpiar el Formulario
  			$("#retencion_iva option[value=-1]").attr("selected",true);
			$("#retencion_fuente option[value=-1]").attr("selected",true);
  			$.each( ['codigo_cuenta','nombre', 'ruc_cedula', 'direccion', 'email', 
				'telefono', 'autorizacion_sri', 'retencion_iva', 'retencion_fuente'], function( index, value ){
  				Pagar.addDeleteStyle($("#"+value),'delete');
  				Pagar.updateMensajeError( $("#"+value) );
			});
		});

		// Definimos la funciòn de los botones del formulario
		$("#btn_save").click(function(){
        	Pagar.guardar();
   		});

		$( "input[name=tipo_proveedor]" ).change(function() {
  			var _elementCodigo = $("#codigo_cuenta");
  			( $(this).val() == 'bienes' ) ? _elementCodigo.val('PB1') : _elementCodigo.val('PS1');
		});

		Pagar.LoadData();
	},

	LoadData : function()
	{
		$.ajax({                
	        url: "procesar.php",
	        type: 'POST',              
	        data: { orden: 'obtenerdata'},
	        success: function( datos ){
	        	Pagar.listData = jQuery.parseJSON(datos);
	        	Pagar.LoadTable();
	        }
    	});	
	},

	LoadTable : function()
	{
		var contenido;

		$.each( Pagar.listData, function(cont, data){
			var _id = data.ID;
			contenido = "<tr>";
			contenido += "<td>"+Pagar.FormatoHtml(data.TIPO)+"</td>";
			contenido += "<td>"+Pagar.FormatoHtml(data.CODIGO)+"</td>";
			contenido += "<td>"+Pagar.FormatoHtml(data.NOMBRE)+"</td>";
			//contenido += "<td>"+data.NOMBRE+"</td>";
			contenido += "<td>"+Pagar.FormatoHtml(data.RUC)+"</td>";
			contenido += "<td>"+Pagar.FormatoHtml(data.DIRECCION)+"</td>";
			contenido += "<td style='text-align:center;'>";
			contenido += "<a style='cursor:pointer' onclick=Pagar.editar("+_id+") ><img src='../../images/page_edit.png' title='Editar'></a>&nbsp;&nbsp;";
			contenido += "<a style='cursor:pointer' onclick=Pagar.eliminar("+_id+") ><img src='../../images/cross.png' title='Eliminar'></a>";
			contenido += "</td>";
			contenido += "</tr>";
			$(contenido).appendTo("#dataTable_data tbody");
		});

		$('#dataTable_data').dataTable({
	        "scrollY":        "60%",
	        "scrollCollapse": false,
	        "paging":         true,
	        "oLanguage": {
				"sLengthMenu": "_MENU_ registros por pagina",
	            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
	            "sInfoEmpty":  "Mostrando registros del 0 al 0 de un total de 0 registros",
				"sInfoFiltered": "(filtrado de un total de _TOTAL_ registros)",
	            "sSearch":         "Buscar:",
	            "oPaginate": {
	        		"sNext":     "Siguiente",
	        		"sPrevious": "Anterior"
	   			}
			}
    	});

		$('#dataTable_data_length').html('');
	},

	FormatoHtml : function( cadena )
	{
		return cadena.toString().replace(/&/g, "&#38;").replace(/</g, "&#60;").replace(/>/g, "&#62;");
	},

	guardar : function()
	{
		var _estado = true;
			var _mensaje = '';
			if( !Pagar.validateForm(['codigo_cuenta','nombre', 'ruc_cedula', 'direccion', 'email',
				'telefono', 'autorizacion_sri', 'retencion_iva', 'retencion_fuente']) )
			{
				//alert("Error, verifique los campos en rojo.");
				Pagar.showAlert("<strong>Error,</strong> verifique los campos en rojo.","alert-error-msn", false);
				_estado = false;
			}else{
				$.ajax({                
			        url: "procesar.php",
			        type: 'POST',              
			        data: $("#registro_cuenta").serialize(),
			        success: function(data_response){
			          	var obj = jQuery.parseJSON(data_response);
			          	var success = parseInt(obj.success);
			          	if( success == 1 )
			          	{
			          		//alert("El Maestro Proveedor se guardo exitosamente.");
			          		//$('#dataTable_data').DataTable().destroy();
			          		//$('#dataTable_data tbody').html('');
			          		$('#modal_maestro_prov').modal('hide');
			          		Pagar.showAlert("Los datos se guardaron exitosamente.","alert-sucess-msn", true);
			          	}
		          		if( success == 0 )
		          		{
		          			//console.error(obj);
		          			$.each( obj.mensajes_error, function( key, value ) {
								Pagar.updateMensajeError( $("#"+key), value);
								Pagar.addDeleteStyle( $("#"+key),'add' );
							});
							Pagar.showAlert("<strong>Error al guardar los datos,</strong> verifique los campos en rojo","alert-error-msn", false);
		          		}
			        }
		    	});
			}
	},

	editar : function( codigo )
	{
		$('#modal_maestro_prov').modal('show');
		$('#title_ventana').html('Editar Provedor');
		Pagar.LoadDataForm(codigo);

	},

	eliminar : function( id )
	{
		Pagar.showAlertDelete('<strong>Esta seguro</strong> de eliminar el Proveedor.', 'alert-warning-msn', true, id);
	},

	LoadDataForm: function( id )
	{
		$.ajax({                
	        url: "procesar.php",
	        type: 'POST',              
	        data: { orden: 'obtener_x_codigo', codigo: id},
	        success: function( dato ){
	        	Pagar.objData = jQuery.parseJSON(dato);
	        	Pagar.LoadForm();
	        }
    	});
	},

	LoadForm : function()
	{
		if( Pagar.objData != null )
		{
			$("input[name=id]").val(Pagar.objData.ID);
			$("input[name=natural_juridica][value="+Pagar.objData.PERS_NATURAL_JURIDICA+"]").prop("checked",true);
			$("input[name=contabilidad][value="+Pagar.objData.CONTABILIDAD+"]").prop("checked",true);
			$("input[name=tipo_proveedor][value="+Pagar.objData.TIPO+"]").prop("checked",true);
			$("input[name=codigo_cuenta]").val(Pagar.objData.CODIGO);
			$("input[name=nombre]").val(Pagar.objData.NOMBRE);
			$("input[name=ruc_cedula]").val(Pagar.objData.RUC);
			$("input[name=direccion]").val(Pagar.objData.DIRECCION);
			$("input[name=email]").val(Pagar.objData.EMAIL);
			$("input[name=telefono]").val(Pagar.objData.TELEFONO);
			$("input[name=autorizacion_sri]").val(Pagar.objData.AUTORIZACION_SRI);
			$("#retencion_iva option[value="+ Pagar.objData.COD_RETENCION_IVA +"]").attr("selected",true);
			$("#retencion_fuente option[value="+ Pagar.objData.COD_RETENCION_FUENTE +"]").attr("selected",true);
		}
	},

	validateForm : function( dataId )
	{
		var _estado = true;
		var _cont = 0;
		dataId = ( dataId === undefined || dataId == null ) ? [] : dataId;

		$.each( dataId, function( index, value ){
  			var _elementForm = $("#"+value);
  			if( !Pagar.evaluarExpresion(_elementForm) ) _cont++;
		});
		if( _cont != 0 ) _estado = false;
		return _estado;
	},

	evaluarExpresion : function( element )
	{
		var _estado = true;
		var _value = element.val();
		var _expReg = element.attr('data-reg');
		if( !(_expReg == undefined || _expReg == '') ) _expReg = new RegExp(_expReg);
		var _resulExpReg = ( _expReg == undefined || _expReg == '' ) ? false : !_value.match(_expReg);
		if( _value == '' || _value == '-1' || _resulExpReg )
		{
			Pagar.addDeleteStyle(element,'add');
			_estado = false;
		}else Pagar.addDeleteStyle(element,'delete');
		
		return _estado;
	},

	addDeleteStyle : function( element, type )
	{
		var _parent = element.parent().parent();
		if( type == 'add' )
		{
			_parent.addClass('has-error');
			element.tooltip();
		}
		else{
			element.tooltip('destroy');
			_parent.removeClass('has-error');
		}
	},

	updateMensajeError : function( element, mensaje )
	{
		var _mensajeDef = element.attr('default-msn');
		var _mensaje = ( mensaje == undefined ) ? _mensajeDef : mensaje;
		element.attr('title',_mensaje);
	},

	showAlert: function( title, classAlerta, isreload )
	{
        var dialog = new BootstrapDialog({
            message: function(dialogRef){
            	var $element = $('<div class="'+classAlerta+'"></div>');
                var $message = $('<p>'+title+'</p>');
                var $contenedorButton = $('<div style="text-align:right;"></div>');
                var $button = $('<button class="btn btn-primary">OK</button>');
                $button.on('click', {dialogRef: dialogRef}, function(event){
                    event.data.dialogRef.close();
                    if( isreload ) location.reload();
                });
                $element.append($message);
                $contenedorButton.append($button);
                $element.append($contenedorButton);
                return $element;
            },
            closable: false
        });
        dialog.realize();
        dialog.setSize(BootstrapDialog.SIZE_SMALL);
        dialog.getModalHeader().hide();
        dialog.getModalFooter().hide();
        //dialog.getModalBody().css('background-color', '#0088cc');
        //dialog.getModalBody().css('color', '#fff');
        dialog.open();
	},

	showAlertDelete: function( title, classAlerta, isreload, id )
	{
        var dialog = new BootstrapDialog({
            message: function(dialogRef){
            	var $element = $('<div class="'+classAlerta+'"></div>');
                var $message = $('<p>'+title+'</p>');
                var $contenedorButton = $('<div style="text-align:right;"></div>');
                var $button = $('<button class="btn btn-primary" style="margin-right: 10px;">Si</button>');
                $button.on('click', {dialogRef: dialogRef}, function(event){
                	event.data.dialogRef.close();
                    $.ajax({                
				        url: "procesar.php",
				        type: 'POST',              
				        data: { orden: 'eliminar', codigo: id},
				        success: function( msg ){
				        	var obj = jQuery.parseJSON(msg);
						    var success = parseInt(obj.success);
						    if( success == 1 )
						    	Pagar.showAlert("El Proveedor se elimino exitosamente.","alert-sucess-msn", true);
					        if( success == 0 ) 
					        	Pagar.showAlert("<strong>Error </strong> al eliminar los datos.","alert-error-msn", false);
				        }
			    	});
                });
                var $buttonNo = $('<button class="btn btn-primary">No</button>');
                $buttonNo.on('click', {dialogRef: dialogRef}, function(event){
                    event.data.dialogRef.close();
                });
                $element.append($message);
                $contenedorButton.append($button);
                $contenedorButton.append($buttonNo);
                $element.append($contenedorButton);
                return $element;
            },
            closable: false
        });
        dialog.realize();
        dialog.setSize(BootstrapDialog.SIZE_SMALL);
        dialog.getModalHeader().hide();
        dialog.getModalFooter().hide();
        dialog.open();
	},

	LoadPlanCuentas : function()
	{
		$.ajax({                
	        url: "procesar.php",
	        type: 'POST',              
	        data: { orden: 'obtenerdata_plancuentas' },
	        success: function( dato ){
	        	var elments = jQuery.parseJSON(dato);
	        	$.each( elments, function(cont, data){
					Pagar.AddOption( data, 'retencion_iva' );
					Pagar.AddOption( data, 'retencion_fuente' );
				});
	        }
    	});
	},

	AddOption : function( data, idElement )
	{
		var contenido;
		contenido = "<option value='"+ data.cont_id_cuenta +"'>";
		contenido += Pagar.FormatoHtml(data.cont_nombre);
		contenido += "</option>";
		$(contenido).appendTo("#" + idElement);
	},
}

$(document).ready(function(){
    
    var altura_body = $("body").outerHeight(true);
    var altura_cabecera = $(".cabecera").outerHeight(true) + 55;
    var altura_area = altura_body - altura_cabecera;
    
    //$('.contenido_cuentas').height(altura_area);
    var altura_titulo = $(".titulo_ventana").outerHeight(true);
    //$('.area_pantalla').height(altura_area - altura_titulo - 100);
    
    var $scrollable_div2 = $('.area_pantalla');
    //$scrollable_div2.scrollator();
    Pagar.init();
  	//$('[data-toggle="tooltip"]').tooltip()
})