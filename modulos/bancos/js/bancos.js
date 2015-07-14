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
    
    /**************************************************************************/
    /**************** CARGAR SELECT CON LAS CUENTAS CONTABLE ******************/
    /**************************************************************************/
    $.ajax({
        url: "../contabilidad/procesar.php",
        type: "POST",
        data:{
            orden: "consultar_cuentas_contables_bancos"
        },
        success: function(data){
            var response = JSON.parse(data);
            $('.selectpicker option').remove();
            $('.selectpicker').append($('<option value="" data-nombre=""></option>').attr("selected", "selected").text('Codigo')); 
            for (var i=0; i < response.length; i++){
                $(".selectpicker").append("<option value="+response[i]['id_cuenta']+" title='"+response[i]["codigo"]+" - "+response[i]["nombre"]+"' data-nombre='"+response[i]["nombre"]+"'>"+response[i]["codigo"]+" - "+response[i]["nombre"]+"</option>");
            } 
        },complete: function(){
            // añade un script a la página y luego ejecuta la función especificada
            $.getScript('../../js/bootstrap-select.js', function() {
                $('.selectpicker').selectpicker({
                    width: '350px',
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
            
        }else{
            $('#nombre_fila_'+codigo).val(selectedOption.data('nombre'));    
            
        }   
    });
        
    $('#fecha_banco').datetimepicker({
        defaultDate: new Date(),
        format: 'YYYY-MM-DD',
        locale: 'es'    
    });
        
    $(".valor").maskMoney({
        prefix:'', 
        allowNegative: true,
        thousands:',',
        decimal:'.',
        allowZero:true
        //affixesStay: false
    });

    /**************************************************************************/
    /******************** REGISTRAR DEPOSITO BANCARIO *************************/
    /**************************************************************************/
    $("#form_registro_bancario").submit(function(){
        var codigo = $('#codigo_banco').val();
        var fecha = $('#fecha').val();
        var nombre = $('#nombre_banco').val();
        var numero = $('#numero_cuenta').val();
        var saldo = $("#saldo").maskMoney('unmasked')[0];
        var id_cuenta_contable = $('.selectpicker').val();
        console.log(id_cuenta_contable);
        if($("input[type='radio']").is(':checked')) {
            var tipo_cuenta = $("input[type='radio']:checked").val();
        }
        
        $.ajax({
            url: "procesar.php",
            type: "post",
            data: {
                orden: "ingresar_banco",
                codigo: codigo,
                fecha: fecha,
                nombre: nombre,
                numero: numero,
                saldo: saldo,
                tipo: tipo_cuenta,
                id_cc: id_cuenta_contable
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
                        message: "Se agregó Correctamente!",
                        buttons: {
                            'ok': {
                                label: 'Aceptar',
                                className: 'btn-primary'
                            }
                        },
                        callback: function() {
                            location.reload();
                        }
                    });
                }else{
                    bootbox.alert({
                        title: "Información",
                        message: "No se Agregó Revise los datos y vuelva a intentar!",
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
            orden: "consultar_bancos"
        },
        success: function(data){
            var nuevaFila='';
            if(data==='vacio'){
                $("#codigo_banco").val("b1");
            }else{
                var response = JSON.parse(data);
                var ultimo_codigo="";
                $("#tabla_bancos tbody").remove();
                nuevaFila+="<tbody>";
                for(var i=0; i<response.length; i++){
                    nuevaFila+="<tr id='banco"+response[i]["id_banco"]+"'>"; 
                    nuevaFila+="<td>"+response[i]["codigo"]+"</td>";
                    nuevaFila+="<td>"+response[i]["nombre"]+"</td>";
                    nuevaFila+="<td>"+response[i]["num_cuenta"]+"</td>";
                    nuevaFila+="<td>"+response[i]["tipo_cuenta"]+"</td>";
                    nuevaFila+="<td><span class='simbolo_dolar'>$</span> <span class='dinero'>"+response[i]["saldo"]+"</span></td>";
                    nuevaFila+="<td>"+response[i]["cta_contable"]+"</td>";
                    nuevaFila+="<td><a style='cursor:pointer;' onclick='ver("+response[i]["id_banco"]+")' title='Ver'><span class='glyphicon glyphicon-zoom-in'></span></a>\n\
                                    <a style='cursor:pointer;' onclick='eliminar("+response[i]["id_banco"]+")' title='Eliminar'><span class='glyphicon glyphicon-trash'></span></a>\n\
                                    <a style='cursor:pointer;' onclick='editar("+response[i]["id_banco"]+")' title='Editar'><span class='glyphicon glyphicon-pencil'></span></a></td>";
                    nuevaFila+="</tr>";
                    ultimo_codigo = response[i]["codigo"];
                }
                nuevaFila+="</tbody>";
                
                $("#tabla_bancos").append(nuevaFila);
                $("#codigo_banco").val(codigo_nuevo(ultimo_codigo));
                
            }
            $('#tabla_bancos').DataTable({
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
                    { sWidth: '10%' },
                    { sWidth: '20%' },
                    { sWidth: '15%' },
                    { sWidth: '15%' },
                    { sWidth: '15%' },
                    { sWidth: '15%' },
                    { sWidth: '10%' }]
            }); 
        }
    });
}

function ver(id){ 
    var loc = window.location;
    var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
    var url = loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + loc.hash).length - pathName.length));
    url = url+"reporte-depositos.php?id="+id;
    var w = 850;
    var h = 550;
    
    var x = screen.width/2 - (w/2);
    var y = screen.height/2 - (h/2);
    
    var WindowObject = window.open(url,"Deposito Bancario","width="+w+",height="+h+",scrollbars=yes,top="+y+", left="+x+"") 
    WindowObject.document.title = "Deposito Bancario";
}
function codigo_nuevo(ultimo_codigo){
    var numero = ultimo_codigo.substring(1);
    numero = parseInt(numero);
    numero++;
    
    return "b"+numero;
}
