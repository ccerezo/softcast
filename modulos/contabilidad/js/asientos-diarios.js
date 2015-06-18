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
    
    $.ajax({
        url: "procesar.php",
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
                //$("#codigo2").append("<option value="+response[i]['id_cuenta']+" title='"+response[i]["codigo"]+"' data-nombre='"+response[i]["nombre"]+"'>"+response[i]["codigo"]+" - "+response[i]["nombre"]+"</option>");
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
    
    $("#imprimir_asiento").hide();
    
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
            $('#descripcion_fila_'+codigo).val($("#descripcion_asiento").val());
            $('#debe_fila_'+codigo).focus();
        }
            
    });
        
    var t = $('#registro_asientos_diarios').DataTable({
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
        $("#registro_asientos_diarios tbody tr").not(':visible').first().each(function() {
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
        cambiar_numero_asiento();
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
        
        if(!(isNaN(dinero))) {
            console.log(dinero);
            dinero = parseFloat(dinero);
            
            if(dinero > 0 ){
                if(id === id_debe){
                    $("#"+id_haber).val("$ 0.00");
                    $("#"+id_haber).attr('disabled', true);
                    //var siguiente_fila = fila++;
                    //$("#codigo"+siguiente_fila).focus();
                    sumar("registro_asientos_diarios", 3);
                    comparar();
                }
                if(id === id_haber){
                    $("#"+id_debe).val("$ 0.00");
                    $("#"+id_debe).attr('disabled', true);
                    sumar("registro_asientos_diarios", 4);
                    comparar();
                }    
            }else{
                if(id === id_debe){
                    $("#"+id_haber).attr('disabled', false);
                    sumar("registro_asientos_diarios", 3);
                }
                if(id === id_haber){
                    $("#"+id_debe).attr('disabled', false);
                    sumar("registro_asientos_diarios", 4);
                }
            }     
        }else{
            console.log(dinero);
        }
    });
    $("#imprimir_asiento").click(function(){
        var id = $("#identificador_asiento").val();
        ver_asiento(id);
    });
    
    /**************************************************************************/
    /************* ANTES DEL CERRAR EL MODAL RECARGO PAGINA O NO **************/
    /**************************************************************************/
    $('#myModal').on('hidden.bs.modal', function (e) {
        var id = $("#identificador_asiento").val();
        if(id!==""){
            location.reload();
        }
        
    });
    $("#registro_asiento").submit(function(){
        var datos = JSON.stringify($( "#registro_asiento" ).serializeArray());
        var num_diario = $('#numero_diario').val();
        var total_debe = $("#total_debe").maskMoney('unmasked')[0];
        var total_haber = $("#total_haber").maskMoney('unmasked')[0];
        
        if(total_debe === total_haber){
            var total = total_debe;
        }
            $.ajax({
                url: "procesar.php",
                type: "post",
                data: {
                    orden: "ingresar_asiento_diario",
                    numero: num_diario,
                    total: total,
                    datos: datos
                },
                beforeSend : function(){
                    $('.loading_icon').show();
                },
                success: function(data){
                    var nuevaFila="";
                    //$('#myModal').modal('hide');
                    if(data!=='fallo'){
                        var response = JSON.parse(data);
                        console.log(response);
                        bootbox.alert({
                            title: "Información",
                            message: "El asiento diario. Se agregó Correctamente!",
                            buttons: {
                                'ok': {
                                    label: 'Aceptar',
                                    className: 'btn-primary'
                                }
                            },
                            callback: function() {
                                $(".btn_agregar_asiento").hide();
                                $("#imprimir_asiento").show();
                                $("input").attr('disabled', true);
                                $("textarea").attr('disabled', true);
                                $(".selectpicker").attr('disabled', true);
                                $("#identificador_asiento").val(response[0]["id_asiento"]);
                                //location.reload();
                               
                            }
                        });
                    }else{
                        bootbox.alert({
                            title: "Información",
                            message: "El asiento diario. No se agregó Revise los datos y vuelva a intentar!",
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
            orden: "consultar_asientos"
        },
        success: function(data){
            var nuevaFila='';
            if(data==='vacio'){
                //$("#numero_diario").val(1);
                cambiar_numero_asiento();
            }else{
                var response = JSON.parse(data);
                $("#tabla_asientos tbody").remove();
                nuevaFila+="<tbody>";
                for(var i=0; i<response.length; i++){
                    nuevaFila+="<tr id='asiento"+response[i]["id_asiento"]+"'>"; 
                    nuevaFila+="<td>"+response[i]["num_asiento"]+"</td>";
                    nuevaFila+="<td>"+response[i]["fecha"]+"</td>";
                    nuevaFila+="<td>"+response[i]["descripcion"]+"</td>";
                    nuevaFila+="<td><span class='simbolo_dolar'>$</span> <span class='dinero'>"+response[i]["valor"]+"</span></td>";
                    nuevaFila+="<td><a style='cursor:pointer;' onclick='ver_asiento("+response[i]["id_asiento"]+")' title='Ver'><span class='glyphicon glyphicon-zoom-in'></span></a>\n\
                                    <a style='cursor:pointer;' onclick='eliminar_asiento("+response[i]["id_asiento"]+")' title='Eliminar'><span class='glyphicon glyphicon-trash'></span></a>\n\
                                    <a style='cursor:pointer;' onclick='editar_asiento("+response[i]["id_asiento"]+")' title='Editar'><span class='glyphicon glyphicon-pencil'></span></a></td>";
                    nuevaFila+="</tr>";
                }
                nuevaFila+="</tbody>";
                
                $("#tabla_asientos").append(nuevaFila);
                cambiar_numero_asiento();
            }
            $('#tabla_asientos').DataTable({
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
                    { sWidth: '65%' },
                    { sWidth: '10%' },
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
        $(".btn_agregar_asiento").attr("disabled", false);
    }else{
        $(".btn_agregar_asiento").attr("disabled", true);
    }
        
}

function ver_asiento(id){ 
    var loc = window.location;
    var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
    var url = loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + loc.hash).length - pathName.length));
    url = url+"reporte-asiento-diario.php?id="+id;
    var w = 850;
    var h = 550;
    
    var x = screen.width/2 - (w/2);
    var y = screen.height/2 - (h/2);
    
    var WindowObject = window.open(url,"Asiento de Diario","width="+w+",height="+h+",scrollbars=yes,top="+y+", left="+x+"") 
    WindowObject.document.title = "Asiento de Diario";
}
function crear_numero_asiento(caracteres, numero){
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

function cambiar_numero_asiento(){
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
                orden: 'ultimo_diario_mes',
                mes: aux2
            },
            success: function (data) {
                if(data!=='0'){
                    var caracteres = parseInt(data.substring(11,15));
                    caracteres = (""+caracteres).length;
                    var numero = parseInt(data.substring(11,15)) +1;
                    var aux4 = crear_numero_asiento(caracteres, numero);
                    
                    if(aux3!==aux4){
                        texto=texto.replace(aux3, aux4);
                        if(texto.length===15)
                            $("#numero_diario").val(texto);
                        else
                            $("#numero_diario").val("AD-"+texto);
                    }
                }else{
                    texto=texto.replace(aux3, "0001");
                    if(texto.length===15)
                        $("#numero_diario").val(texto);
                    else
                        $("#numero_diario").val("AD-"+texto);
                }
                
            }
        });
}