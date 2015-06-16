$.expr[':'].icontains = function(obj, index, meta, stack){
        return (obj.textContent || obj.innerText || jQuery(obj).text() || '').toLowerCase().indexOf(meta[3].toLowerCase()) >= 0;
    };
    
$(document).ready(function(){
    
    var altura_body = $("body").outerHeight(true);
    var altura_cabecera = $(".cabecera").outerHeight(true) + 55;
    var altura_area = altura_body - altura_cabecera;
    
    $('.contenido_cuentas').height(altura_area);
    var altura_titulo = $(".titulo_ventana").outerHeight(true);
    $('.area_pantalla').height(altura_area - altura_titulo - 100);
    
    var $scrollable_div2 = $('.area_pantalla');
    $scrollable_div2.scrollator();
    
    $('#codigo_error').hide();
    $('#codigo_error2').hide();
    $('#codigo_cuenta').mask('9?.9.99.99.9999');
 
    /****************************************************************/
    /******** EXPANDIR O RECOGER PLAN DE CUENTA CON BOTONES ********/
    $("#colapsar").click(function(){
        $( ".tree tr" ).removeClass('resaltar'); 
        $( ".tree tr:first" ).addClass('resaltar'); 
        $('.tree').treegrid('collapseAll');
    });
    $("#expandir").click(function(){
        $('.tree').treegrid('expandAll');
    });
    /****************************************************************/
    /*** RECORRER PLAN DE CUENTAS CON TECLA CURSOR ARRIBA - ABAJO ***/
    $(document.documentElement).keydown(function (event) {
        var $currElement = $(".resaltar");
        // handle cursor keys
        if (event.keyCode == 40) {
            
            if(!$currElement.next().length){
	  	//alert("No element found!");
		return false;	
            }
            do{
                $currElement = $currElement.next();
            }while(!$currElement.is (':visible'));
            
            $(".tree tr").removeClass('resaltar');
            $currElement.addClass('resaltar').focus();            
            
        } else if (event.keyCode == 38) {
            if(!$currElement.prev().length){
	  	//alert("No element found!");
		return false;	
            }
            do{
                $currElement = $currElement.prev();
            }while(!$currElement.is (':visible'));
            
            $(".tree tr").removeClass('resaltar');
            $currElement.addClass('resaltar');
            $currElement.focus();
        }                       
    });
    
    /****************************************************************/
    /************** BUSCAR CUENTA DE LA VENTANA MODAL ***************/
    
    $('#form_buscar_cuenta').submit(function(){
        $('#myModal2').modal('hide');
        var buscar = $("#buscar_cuenta").val();
        
        $('.tree tr').removeClass('resaltar');
            if(jQuery.trim(buscar) !== ''){
               $(".tree tr:icontains('" + buscar + "')").addClass('resaltar').focus();
            }
        return false;
    });
    
    /****************************************************************/
    /************ EXPORTAR PLAN DE CUENTAS A EXCEL O PDF ************/
    $("#excel").click(function(e) {
        $("#datos_a_enviar").val( $("<div>").append( $(".tree").eq(0).clone()).html());
        $("#exportar-excel").submit();
    });
    $("#pdf").click(function(e) {
        $("#datos_pdf_enviar").val("");
        $("#exportar-pdf").submit();
    });
    
    /****************************************************************/
    /*********** VALIDAR CODIGO DE LA CUENTA A INGRESAR ************/
    $("#codigo_cuenta").blur(function(){

        var codigo = $('#codigo_cuenta').val();
        console.log(codigo);
        $.ajax({
            url: "procesar.php",
            type: "post",
            data: {
                orden: "verificar_cuenta",
                codigo: codigo
            },
            success: function(data){
                console.log(data);
                if(data==='existe'){
                    $('#codigo_error2').hide();
                    $('#codigo_error').show();
                    $('#ya_existe').text(codigo);
                    $('#codigo_cuenta').val("");
                    $('#codigo_cuenta').focus();
                }
                if(data==='invalido'){
                    $('#codigo_error').hide();
                    $('#codigo_error2').show();
                    $('#codigo_novalido').text(codigo);
                    $('#codigo_cuenta').val("");
                    $('#codigo_cuenta').focus();
                }
                if(data==='valido'){
                    $('#codigo_error').hide();
                    $('#codigo_error2').hide();
                    $('#ya_existe').text();
                }
            }
        });

    });
    /****************************************************************/
    /********** REGISTRADO DATOS DEL FORMULARIO A LA CUENTA *********/
    $("#registro_cuenta").submit(function(){
        var codigo = $('#codigo_cuenta').val();
        var nombre = $('#nombre_cuenta').val();
			
            $.ajax({
                url: "procesar.php",
                type: "post",
                data: {
                    orden: "ingresar_cuenta",
                    codigo: codigo,
                    nombre: nombre
                },
                beforeSend : function(){
                    $('.loading_icon').show();
                },
                success: function(data){
                    console.log(data);
                    $('#nombre_cuenta').val("");
                    $('#codigo_cuenta').val("");
                    $('#myModal').modal('hide');
                    if(data==='ok'){
                        $(".tree").remove();
                        bootbox.alert({
                            title: "Información",
                            message: "La cuenta <b>" +nombre+"</b> .Se agregó Correctamente!",
                            buttons: {
                                'ok': {
                                    label: 'Aceptar',
                                    className: 'btn-primary'
                                }
                            },
                            callback: function() {
                                setEvents(codigo);
                            }
                        });
                        
                    }
                }
            });
	  	return false;
    });
    /****************************************************************/
    /************* ELIMINAR CUENTA DEL PLAN CONTABLE ****************/
    $("#eliminar_cuenta").click(function() {
        var codigo = $(".tree .resaltar").find("td").eq(0).text();
        var nombre = $(".tree .resaltar").find("td").eq(1).text();
        console.log(codigo);
        
        bootbox.confirm({
            title: "Información",
            message: "Esta Seguro que desea Eliminar la cuenta <b>" +nombre+"</b>?",
            buttons: {
                'cancel': {
                    label: 'Cancelar',
                    className: 'btn-default'
                },
                'confirm': {
                    label: 'Aceptar',
                    className: 'btn-danger'
                }
            },
            callback: function(result) {
                if(result){
                    $.ajax({
                        url: "procesar.php",
                        type: "post",
                        data: {
                            orden: "eliminar_cuenta",
                            codigo: codigo
                        },
                        success: function(data){
                            console.log(data);
                            if(data=='si'){
                                $(".tree").remove();
                                bootbox.alert({
                                    title: "Información",
                                    message: "La cuenta <b>" +nombre+"</b> .Se Eliminó Correctamente!",
                                    buttons: {
                                        'ok': {
                                            label: 'Aceptar',
                                            className: 'btn-primary'
                                        }
                                    },
                                    callback: function() {
                                        setEvents();
                                    }
                                });
                            }else{
                                if(data=='no_h'){
                                    bootbox.alert({
                                        title: "Información",
                                        message: "No se puede Eliminar la Cuenta <b>" +nombre+"</b> .Posee subcuentas!",
                                        buttons: {
                                            'ok': {
                                                label: 'Aceptar',
                                                className: 'btn-primary'
                                            }
                                        }
                                    });
                                }else{
                                     bootbox.alert({
                                        title: "Información",
                                        message: "No se Eliminó Ninguna Cuenta",
                                        buttons: {
                                            'ok': {
                                                label: 'Aceptar',
                                                className: 'btn-primary'
                                            }
                                        }
                                    });
                                }
                            }
                            
                        }
                    }); 
                }
            }
        });
    });
    
});

jQuery(function($) {
 
    setEvents();
 
});

function setEvents(codigo){
    $.ajax({
        url: "procesar.php",
        type: "POST",
        data:{
            orden: "consultar_plan"
        },
        beforeSend : function(){
                    $('.loading_icon').show();
        },
        success: function(data){

            if(data=='vacio'){
            }
            else{
                //console.log(data);
                $('.loading_icon').hide();
                $(".area_pantalla").append(data);
                
            }
        },complete: function(data){
            $('.tree').treegrid({
                expanderExpandedClass: 'glyphicon glyphicon-minus',
                expanderCollapsedClass: 'glyphicon glyphicon-plus'
            });
            
            $('.tree tr').click(function(){
                $(".tree tr").removeClass('resaltar');
                $(this).addClass('resaltar');
            });
            
            if(typeof(codigo)!=='undefined'){
                
                $('.tree tr').removeClass('resaltar');
                if(jQuery.trim(codigo) !== ''){
                    $(".tree tr:icontains('" + codigo + "')").addClass('resaltar').focus();
                }
            }else{
               $( ".tree tr:first" ).addClass('resaltar'); 
            }
            
        }
    });
}