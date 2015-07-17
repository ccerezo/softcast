/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function(){
    var altura_body = $("body").outerHeight(true);
    var altura_cabecera = $(".cabecera").outerHeight(true) + 30;
    var altura_area = altura_body - altura_cabecera;
    
    $('.contenido_cuentas').height(altura_area);
    var altura_titulo = $(".titulo_ventana").outerHeight(true);
    $('.area_pantalla').height(altura_area - altura_titulo -20);
    
    var $scrollable_div2 = $('.area_pantalla');
    $scrollable_div2.scrollator();
    
    $("#imprimir").hide();
    
    /**************************************************************************/
    /**************** CARGAR SELECT CON LAS CUENTAS CONTABLE ******************/
    /**************************************************************************/
    $.ajax({
        url: "../contabilidad/procesar.php",
        type: "POST",
        data:{
            orden: "consultar_cuentas_contables"
        },
        success: function(data){
            console.log(data);
            var response = JSON.parse(data);
            $('.selectpicker option').remove();
            $('.selectpicker').append($('<option value="" data-nombre=""></option>').attr("selected", "selected").text('Codigo')); 
            for (var i=0; i < response.length; i++){
                $(".selectpicker").append("<option value="+response[i]['id_cuenta']+" title='"+response[i]["codigo"]+"' data-nombre='"+response[i]["nombre"]+"'>"+response[i]["codigo"]+" - "+response[i]["nombre"]+"</option>");
            } 
        },complete: function(){
            // añade un script a la página y luego ejecuta la función especificada
            $.getScript('../../js/bootstrap-select.js', function() {
                $('.selectpicker').selectpicker({
                    width: '100px',
                    title: 'Codigo'
                });
            });
        }
    });
    
    /**************************************************************************/
    /********************* CARGAR SELECT CON LOS BANCOS ***********************/
    /**************************************************************************/
    $.ajax({
        url: "procesar.php",
        type: "POST",
        data:{
            orden: "consultar_bancos"
        },
        success: function(data){
            var response = JSON.parse(data);
            $('.selectpicker_banco option').remove();
            $('.selectpicker_banco').append($('<option value="" data-nombre=""></option>').attr("selected", "selected").text('Buscar banco')); 
            for (var i=0; i < response.length; i++){
                $(".selectpicker_banco").append("<option value="+response[i]['id_banco']+" title='"+response[i]["nombre"]+"' data-codigo='"+response[i]["codigo"]+"' data-tipo='"+response[i]["tipo_cuenta"]+"' data-numero='"+response[i]["num_cuenta"]+"' >"+response[i]["nombre"]+" - "+response[i]["tipo_cuenta"]+" - "+response[i]["num_cuenta"]+"</option>");
            } 
        },complete: function(){
            $.getScript('../../js/bootstrap-select.js', function() {
                $('.selectpicker_banco').selectpicker({
                    width: '135px',
                    noneResultsText: 'Banco No registrado',
                    title: 'Codigo'
                });

            });
        }
    });
    
    /**************************************************************************/
    /******************** CARGAR SELECT CON LOS CLIENTES **********************/
    /**************************************************************************/
    $.ajax({
        url: "procesar.php",
        type: "POST",
        data:{
            orden: "consultar_clientes"
        },
        success: function(data){
            var response = JSON.parse(data);
            $('.selectpicker_cliente option').remove();
            $('.selectpicker_cliente').append($('<option value="" data-nombre=""></option>').attr("selected", "selected").text('Buscar cliente')); 
            for (var i=0; i < response.length; i++){
                $(".selectpicker_cliente").append("<option value="+response[i]['id_cliente']+" title='"+response[i]["identificacion"]+"' data-nombre='"+response[i]["nombre"]+"' data-direccion='"+response[i]["direccion"]+"' data-telefono='"+response[i]["telefono"]+"' >"+response[i]["identificacion"]+" - "+response[i]["nombre"]+"</option>");
            } 
        },complete: function(){
            // añade un script a la página y luego ejecuta la función especificada
            $.getScript('../../js/bootstrap-select.js', function() {
                $('.selectpicker_cliente').selectpicker({
                    width: '135px',
                    noneResultsText: 'Cliente No registrado',
                    title: 'Codigo'
                });

            });  
        }
    });
    
    /**************************************************************************/
    /************ LLENAR DATOS DEL FORMULARIO DE LA PARTE CONTABLE ************/
    /**************************************************************************/
    
    $('.selectpicker').on('change', function(){
        var codigo = $(this).attr('id');
        codigo = codigo.substring(6);
        
        selectedOption = $('option:selected', this);
        
        if(selectedOption.data('nombre')===''){
            $('#nombre_fila_'+codigo).val('');
            $('#descripcion_fila_'+codigo).val('');
            $('#debe_fila_'+codigo).maskMoney('unmasked')[0];
            $('#haber_fila_'+codigo).maskMoney('unmasked')[0];
            $('#debe_fila_'+codigo).val('');
            $('#haber_fila_'+codigo).val('');
        }else{
            $('#nombre_fila_'+codigo).val(selectedOption.data('nombre'));    
            $('#descripcion_fila_'+codigo).val($("#descripcion_bancaria").val());
            
            var total_debe = $('#total_debe').maskMoney('unmasked')[0];
            if(total_debe === 0) 
                var valor_deposito = $('#valor_deposito').maskMoney('unmasked')[0];
            $('#debe_fila_'+codigo).maskMoney('mask',valor_deposito);
            $('#debe_fila_'+codigo).focus();
        }   
    });
    /**************************************************************************/
    /************ LLENAR DATOS DEL FORMULARIO DATOS DE UN CLIENTE *************/
    /**************************************************************************/
    $('.selectpicker_cliente').on('change', function(){
        
        selectedOption = $('option:selected', this);
        
        if(selectedOption.data('nombre')===''){
            $('#nombre_cliente').val('');
            $('#direccion').val('');
            $('#telefono').val('');
        }else{
            $("#id_cliente").val($(this).val());
            var nombre = selectedOption.data('nombre');
            $('#nombre_cliente').val(nombre); 
            var direccion = selectedOption.data('direccion');
            $('#direccion_cliente').val(direccion); 
            var telefono = selectedOption.data('telefono');
            $('#telefono_cliente').val(telefono); 

        }
            
    });
    
    /**************************************************************************/
    /************* LLENAR DATOS DEL FORMULARIO DATOS DE UN BANCO **************/
    /**************************************************************************/
    $('.selectpicker_banco').on('change', function(){
        
        selectedOption = $('option:selected', this);
        
        if(selectedOption.data('nombre')===''){
            $('#numero_cuenta').val('');
            $('#tipo_cuenta').val('');
            $('#codigo_banco').val('');
        }else{
            $("#id_banco").val($(this).val());
            var tipo = selectedOption.data('tipo');
            $('#tipo_cuenta').val(tipo); 
            var num_cuenta = selectedOption.data('numero');
            $('#numero_cuenta').val(num_cuenta); 
            var codigo = selectedOption.data('codigo');
            $('#codigo_banco').val(codigo); 

        }
            
    });
    
    var t = $('#registro_bancario').DataTable({
        "paging":   false,
        "ordering": false,
        "info":     false,
        "searching": false,
        "bAutoWidth": false,
        "aoColumns": [
            { sWidth: '10%' },
            { sWidth: '22%' },
            { sWidth: '48%' },
            { sWidth: '10%' },
            { sWidth: '10%' } ]
    });
    
    $('#addRow').on( 'click', function () {
        $("#registro_bancario tbody tr").not(':visible').first().each(function() {
            $(this).show();
        });
    });
    
    // Automatically add a first row of data
    $('#addRow').click();
   
    
    $('#datetimepicker1').datetimepicker({
        defaultDate: new Date(),
        format: 'YYYY-MM-DD',
        locale: 'es'    
    });
    
    $("#datetimepicker1").on("dp.change", function (e) {
        cambiar_numero();
    });
        
    $(".valor").maskMoney({
        prefix:'', 
        allowNegative: true,
        thousands:',',
        decimal:'.',
        allowZero:true
        //affixesStay: false
    });

     $("#total_debe").maskMoney({
        prefix:'', 
        allowNegative: true,
        thousands:',',
        decimal:'.',
        allowZero:true
        //affixesStay: false
    });
    $(".valor").blur(function(){
        var id =  $(this).attr("id");
        var dinero = $('#'+id).maskMoney('unmasked')[0];
        var fila = id.substring(id.length -1, id.length);
        var id_debe = "debe_fila_"+fila;
        var id_haber = "haber_fila_"+fila;
        
        detalle_diario = new Array();
        detalle_asiento_diario = new Array();
        
        $("#registro_bancario tbody tr:visible").not(':last').each(function() {
            
            var id_select = $(this).find(".selectpicker").attr("id");
            var id_cuenta = $("#"+id_select).val();
            if(id_cuenta!==""  && detalle_diario.indexOf(id_cuenta)=== -1){
                detalle_diario.push(id_cuenta);
                var descripcion = $(this).find('td:eq(2)').html();
                var id_descripcion = $(descripcion).attr("id");
                var valor = $(this).find('td:eq(3)').html();
                var id_valor = $(valor).attr("id");
                var dinero = $('#'+id_valor).maskMoney('unmasked')[0];
                if(dinero!==0){
                    var tipo = "d";
                }
                else{
                    valor = $(this).find('td:eq(4)').html();
                    id_valor = $(valor).attr("id");
                    dinero = $('#'+id_valor).maskMoney('unmasked')[0];
                    tipo = "h";
                }
                    
                detalle_asiento_diario.push({cuenta: id_cuenta, 
                                            descripcion_detalle: $("#"+id_descripcion).val(),
                                            valor_detalle: dinero,
                                            tipo_detalle: tipo
                                            });
            }
                
        });
            
        detalle_asiento_diario = JSON.stringify(detalle_asiento_diario);
        
        if(!(isNaN(dinero))) {
            dinero = parseFloat(dinero);
            
            if(dinero > 0 ){
                if(id === id_debe){
                    $("#"+id_haber).val("$ 0.00");
                    $("#"+id_haber).attr('disabled', true);
                    sumar("registro_bancario", 3);
                    comparar();
                }
                if(id === id_haber){
                    $("#"+id_debe).val("$ 0.00");
                    $("#"+id_debe).attr('disabled', true);
                    sumar("registro_bancario", 4);
                    comparar();
                }    
            }else{
                if(id === id_debe){
                    $("#"+id_haber).attr('disabled', false);
                    sumar("registro_bancario", 3);
                }
                if(id === id_haber){
                    $("#"+id_debe).attr('disabled', false);
                    sumar("registro_bancario", 4);
                }
            }     
        }else{
            console.log(dinero);
        }
    });
    $("#imprimir").click(function(){
        var id = $("#identificador_bancario").val();
        ver(id);
    });
    
    /**************************************************************************/
    /************* ANTES DEL CERRAR EL MODAL RECARGO PAGINA O NO **************/
    /**************************************************************************/
    $('#myModal').on('hidden.bs.modal', function (e) {
        var id = $("#identificador_bancario").val();
        if(id!==""){
            location.reload();
        }
        
    });
    /**************************************************************************/
    /******************** REGISTRAR DEPOSITO BANCARIO *************************/
    /**************************************************************************/
    $("#form_registro_bancario").submit(function(){
        var num_diario = $('#numero_diario').text();
        var banco = $('#id_banco').val();
        var cliente = $('#id_cliente').val();
        var descripcion = $('#descripcion_bancaria').val();
        var fecha = $('#fecha_asiento').val();
        var total_debe = $("#total_debe").maskMoney('unmasked')[0];
        var total_haber = $("#total_haber").maskMoney('unmasked')[0];
        
        if(total_debe === total_haber){
            var total = total_debe;
        }
        console.log(detalle_asiento_diario);
            $.ajax({
                url: "procesar.php",
                type: "post",
                data: {
                    orden: "ingresar_tr",
                    numero: num_diario,
                    descripcion: descripcion,
                    banco: banco,
                    cliente: cliente,
                    fecha: fecha,
                    total: total,
                    datos: detalle_asiento_diario
                },
                beforeSend : function(){
                    $('.loading_icon').show();
                },
                success: function(data){
                    console.log(data);
                    if(data!=='fallo'){
                        var response = JSON.parse(data);
                        console.log(response);
                        bootbox.alert({
                            title: "Información",
                            message: "La Transferencia Recibida. Se agregó Correctamente!",
                            buttons: {
                                'ok': {
                                    label: 'Aceptar',
                                    className: 'btn-primary'
                                }
                            },
                            callback: function() {
                                $(".btn_agregar").hide();
                                $("#imprimir").show();
                                $("input").attr('disabled', true);
                                $("textarea").attr('disabled', true);
                                $(".selectpicker").attr('disabled', true);
                                $("#identificador_bancario").val(response[0]["id_tr"]);
                                //location.reload();
                            }
                        });
                    }else{
                        bootbox.alert({
                            title: "Información",
                            message: "La Transferencia Recibida. No se Agregó Revise los datos y vuelva a intentar!",
                            buttons: {
                                'ok': {
                                    label: 'Aceptar',
                                    className: 'btn-danger'
                                }
                            },
                            callback: function() {
                               
                            }
                        });
                    }
                    
                }
            });
	return false;
    });
    
});

jQuery(function($) {
 
    setEvents();
 
});

function setEvents(){
    $.ajax({
        url: "procesar.php",
        type: "POST",
        data:{
            orden: "consultar_tr"
        },
        success: function(data){
            var nuevaFila='';
            if(data==='vacio'){
                //$("#numero_diario").val(1);
                cambiar_numero();
            }else{
                var response = JSON.parse(data);
                $("#tabla_tr tbody").remove();
                nuevaFila+="<tbody>";
                for(var i=0; i<response.length; i++){
                    nuevaFila+="<tr id='depositos"+response[i]["id_tr"]+"'>"; 
                    nuevaFila+="<td>"+response[i]["num_tr"]+"</td>";
                    nuevaFila+="<td>"+response[i]["fecha"]+"</td>";
                    nuevaFila+="<td>"+response[i]["nombre_banco"]+"</td>";
                    nuevaFila+="<td>"+response[i]["num_cuenta_banco"]+"</td>";
                    nuevaFila+="<td>"+response[i]["tipo_cuenta_banco"]+"</td>";
                    nuevaFila+="<td>"+response[i]["descripcion"]+"</td>";
                    nuevaFila+="<td><span class='simbolo_dolar'>$</span> <span class='dinero'>"+response[i]["valor"]+"</span></td>";
                    nuevaFila+="<td><a style='cursor:pointer;' onclick='ver("+response[i]["id_tr"]+")' title='Ver'><span class='glyphicon glyphicon-zoom-in'></span></a>\n\
                                    <a style='cursor:pointer;' onclick='eliminar("+response[i]["id_tr"]+")' title='Eliminar'><span class='glyphicon glyphicon-trash'></span></a>\n\
                                    <a style='cursor:pointer;' onclick='editar("+response[i]["id_tr"]+")' title='Editar'><span class='glyphicon glyphicon-pencil'></span></a></td>";
                    nuevaFila+="</tr>";
                }
                nuevaFila+="</tbody>";
                
                $("#tabla_tr").append(nuevaFila);
                cambiar_numero();
            }
            $('#tabla_tr').DataTable({
                "bLengthChange": false,
                "responsive": true,
                "language": {
                    "zeroRecords": "No existen registros",
                    "info": "Viendo página _PAGE_ de _PAGES_",
                    "infoEmpty": "No encontrado",
                    "search": "Buscar:",
                    "infoFiltered": "(filtrado total _MAX_ registros)",
                    "paginate": {
                                "first":      "Primera",
                                "last":       "Ultimo",
                                "next":       "Siguiente",
                                "previous":   "Anterior"
                            }
                },
                "pageLength": 15,
                "order": [[ 0, "desc" ]],
                "aoColumns": [
                    { sWidth: '11%' },
                    { sWidth: '8%' },
                    { sWidth: '13%' },
                    { sWidth: '8%' },
                    { sWidth: '5%' },
                    { sWidth: '40%' },
                    { sWidth: '9%' },
                    { sWidth: '6%' }]
            }); 
        }
    });
}

function sumar(tabla, columna) {
 
    var resultVal = 0.0; 
         
    $("#" + tabla + " tbody tr").not(':last').each(function() {
         
        var celdaValor = $(this).find('td:eq(' + columna + ')').html();
        var id= $(celdaValor).attr("id");

        var dinero = $("#"+id).maskMoney('unmasked')[0];

        if (dinero !== null){
            resultVal += parseFloat(dinero);
        }
                          
    });
    if(columna===3)
        $("#total_debe").maskMoney('mask',resultVal);
    else
        $("#total_haber").maskMoney('mask',resultVal);
}   
function comparar(){
    var total_debe = parseFloat($("#total_debe").maskMoney('unmasked')[0]);
    var total_haber = parseFloat($("#total_haber").maskMoney('unmasked')[0]);
    
    if(total_debe === total_haber){
        $(".btn_agregar").attr("disabled", false);
    }else{
        $(".btn_agregar").attr("disabled", true);
    }
        
}

function ver(id){ 
    var loc = window.location;
    var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
    var url = loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + loc.hash).length - pathName.length));
    url = url+"reporte-t-recibidas.php?id="+id;
    var w = 850;
    var h = 550;
    
    var x = screen.width/2 - (w/2);
    var y = screen.height/2 - (h/2);
    
    var WindowObject = window.open(url,"Transferencia Recibida","width="+w+",height="+h+",scrollbars=yes,top="+y+", left="+x+"") 
    WindowObject.document.title = "Transferencia Recibida";
}
function crear_numero(caracteres, numero){
    var auxiliar2="";
    switch(caracteres) {
        case 1:
            auxiliar2 = "000"+numero;
            break;
        case 2:
            auxiliar2 = "00"+numero;
            break;
        case 3:
            auxiliar2 = "0"+numero;
            break;
        case 4:
            auxiliar2 = ""+numero;
            break;
    }
    return auxiliar2;
}

function cambiar_numero(){
    var texto = $("#numero_diario").val();
    var aux1="";
    var aux2 = $("#fecha_asiento").val().substring(0,7);
    var aux3="";
    if(texto===""){
        texto = $("#fecha_asiento").val().substring(0,7);
        texto = texto+"-0000";
        aux3 = "0000";
    }else{
        aux1 = $("#numero_diario").val().substring(3,10);
        texto = texto.replace(aux1, aux2);
        aux3 = $("#numero_diario").val().substring(11,15);
    }   
        $.ajax({
            url: "procesar.php",
            type: "post",
            data:{
                orden: 'ultimo_diario_mes_trans_rec',
                mes: aux2
            },
            success: function (data) {
                if(data!=='0'){
                    var caracteres = parseInt(data.substring(11,15));
                    caracteres = (""+caracteres).length;
                    var numero = parseInt(data.substring(11,15)) +1;
                    var aux4 = crear_numero(caracteres, numero);
                    
                    if(aux3!==aux4){
                        texto=texto.replace(aux3, aux4);
                        if(texto.length===15)
                            $("#numero_diario").text(texto);
                        else
                            $("#numero_diario").text("TR-"+texto);
                    }
                }else{
                    texto=texto.replace(aux3, "0001");
                    if(texto.length===15)
                        $("#numero_diario").text(texto);
                    else
                        $("#numero_diario").text("TR-"+texto);
                }
                
            }
        });
}