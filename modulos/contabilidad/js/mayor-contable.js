/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function(){
    
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
            $('.selectpicker').append($('<option value=""></option>').attr("selected", "selected").text('Codigo')); 
            for (var i=0; i < response.length; i++){
                $(".selectpicker").append("<option value="+response[i]['id_cuenta']+" data-nombre='"+response[i]["codigo"]+" - "+response[i]["nombre"]+"'>"+response[i]["codigo"]+" - "+response[i]["nombre"]+"</option>");
            } 
        },complete: function(){
            // añade un script a la página y luego ejecuta la función especificada
            $.getScript('../../js/bootstrap-select.js', function() {
                $('.selectpicker').selectpicker({
                    title: 'Codigo',
                    width: '295px'
                });
            });
        }
    });

    $('.selectpicker').on('change', function(){
        var codigo = $(this).attr('id');
        codigo = codigo.substring(6);
        console.log(codigo);
        selectedOption = $('option:selected', this);
          
        $('#nombre_fila_'+codigo).val(selectedOption.data('nombre'));    
        $('#descripcion_fila_'+codigo).val($("#descripcion_asiento").val());
        $('#debe_fila_'+codigo).focus();
        if(selectedOption===''){
            $('#nombre_fila_'+codigo).val('');
            $('#descripcion_fila_'+codigo).val('');
        }
            
    });
        
    
    $('#datetimepicker1').datetimepicker({
        defaultDate: new Date(),
        format: 'YYYY-MM-DD',
        locale: 'es'    
    });
    
    $('#datetimepicker2').datetimepicker({
        defaultDate: new Date(),
        format: 'YYYY-MM-DD',
        locale: 'es'    
    });
    
    $("#reporte_mayor_contable").submit(function(){
        
        var cuenta1 = $('#codigo1').val();
        var cuenta2 = $('#codigo2').val()
        var inicio = $('#fecha_reporte1').val()
        var fin = $('#fecha_reporte2').val();
        console.log(cuenta1);
        console.log(cuenta2);
        console.log(inicio);
        console.log(fin);
        var loc = window.location;
        var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
        var url = loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + loc.hash).length - pathName.length));
        url = url+"reporte-mayor-contable.php?desde="+cuenta1+"&inicio="+inicio+"&fin="+fin;
        var w = 850;
        var h = 550;

        var x = screen.width/2 - (w/2);
        var y = screen.height/2 - (h/2);

        var WindowObject = window.open(url,"Mayor Contable","width="+w+",height="+h+",scrollbars=yes,top="+y+", left="+x+"") 
        WindowObject.document.title = "Mayor Contable";
            return false;
        });
    
});

function ver_mayor_contable(id){ 
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