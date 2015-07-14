	
$(function() {
    $("#valor").maskMoney({prefix:'$ ', allowNegative: true, thousands:',', decimal:'.', affixesStay: true});
    
    
    var arr = [ "debe_1", "debe_2", "debe_3", "debe_4", "debe_5","debe_6","debe_7",
       "debe_8","debe_9","debe_10","debe_11","debe_12","debe_13","debe_14","debe_15",
       "debe_16","debe_17","debe_18","debe_19","debe_20","debe_21","debe_22","debe_23"
       ,"debe_24","debe_25","debe_26","debe_27","debe_28","debe_29","debe_30","debe_31"
       ,"debe_32","debe_33","debe_34","debe_35","debe_36","debe_37","debe_38","debe_39"
       ,"debe_40","haber_1", "haber_2", "haber_3", "haber_4", "haber_5","haber_6","haber_7",
       "haber_8","haber_9","haber_10","haber_11","haber_12","haber_13","haber_14","haber_15",
       "haber_16","haber_17","haber_18","haber_19","haber_20","haber_21","haber_22","haber_23"
       ,"haber_24","haber_25","haber_26","haber_27","haber_28","haber_29","haber_30","haber_31"
       ,"haber_32","haber_33","haber_34","haber_35","haber_36","haber_37","haber_38","haber_39"
       ,"haber_40"];
      jQuery.each( arr, function( i, val ) {
$("#"+val).maskMoney({prefix:'$ ', allowNegative: true, thousands:',', decimal:'.', affixesStay: true});
  
           //$('#hidden_'+val).val($("#"+val).maskMoney('unmasked')[0]);
          
         
  // Will stop running after "nombre_cuenta_40"
  return ( val !== "haber_40" );
});
   
  /*  
     $('#NuevoDeposito').on('show.bs.modal', function () {
$('.modal-content').css('height',$( window ).height()*0.8);
});*/
    
   
    
     $('#nombre_banco').on('change', function(){
          selectedOption = $('option:selected', this);
     
            $('input[name=codigo_cuenta_contable]').val( selectedOption.data('codigo-cuenta-contable') );
            $('input[name=codigo_banco]').val(selectedOption.data('codigo-banco') );
            $('input[name=numero_cuenta]').val( selectedOption.data('numero-cuenta') );
            $('input[name=tipo_cuenta]').val( selectedOption.data('tipo-cuenta') );
            
            
            if(selectedOption=='')
                 $('input[name=codigo_banco]').val('');
     });
     
     
     $('#nombre_cliente').on('change', function(){
          selectedOption = $('option:selected', this);
     
          
            $('input[name=codigo_cliente]').val(selectedOption.data('id') );
            
            
            if(selectedOption=='')
                 $('input[name=codigo_cliente]').val('');
     });
     
      /***********CREAR FUNCIOIN PARA MEJORAR ESTA PARTE***************************************************************/
    
      var arr = [ "nombre_cuenta_1", "nombre_cuenta_2", "nombre_cuenta_3", "nombre_cuenta_4", "nombre_cuenta_5","nombre_cuenta_6","nombre_cuenta_7",
       "nombre_cuenta_8","nombre_cuenta_9","nombre_cuenta_10","nombre_cuenta_11","nombre_cuenta_12","nombre_cuenta_13","nombre_cuenta_14","nombre_cuenta_15",
       "nombre_cuenta_16","nombre_cuenta_17","nombre_cuenta_18","nombre_cuenta_19","nombre_cuenta_20","nombre_cuenta_21","nombre_cuenta_22","nombre_cuenta_23"
       ,"nombre_cuenta_24","nombre_cuenta_25","nombre_cuenta_26","nombre_cuenta_27","nombre_cuenta_28","nombre_cuenta_29","nombre_cuenta_30","nombre_cuenta_31"
       ,"nombre_cuenta_32","nombre_cuenta_33","nombre_cuenta_34","nombre_cuenta_35","nombre_cuenta_36","nombre_cuenta_37","nombre_cuenta_38","nombre_cuenta_39"
       ,"nombre_cuenta_40"];
      jQuery.each( arr, function( i, val ) {

$("#"+val).on('change', function(){
    /*alert(val);
     alert(i);*/
          selectedOption = $('option:selected', this);
     j=i+1;
     
          
            $('input[name=cod_cta_contable_'+j+']').val(selectedOption.data('nombre') );
             $('input[name=codigo_cta_contable_'+j+']').val(selectedOption.data('codigo-cuenta-contable') );
             var descripcion=$('input[name=dep_descripcion]').val();
             $('input[name=descripcion_'+j+']').val(descripcion);
            
            if(selectedOption==''){
                 $('input[name=cod_cta_contable_'+j+']').val('');
                 $('input[name=codigo_cta_contable_'+j+']').val('');
                    $('input[name=descripcion_'+j+']').val('');
            }
     });
 
  // Will stop running after "nombre_cuenta_40"
  return ( val !== "nombre_cuenta_40" );
});
      
   
     /**************************************************************************/
     
     $('#editar_nombre_banco').on('change', function(){
          selectedOption = $('option:selected', this);
              $('input[name=editar_cod_cuenta_contable]').val( selectedOption.data('codigo-cuenta-contable') );
            
           
     });
     
      $('#myModal').on('hidden.bs.modal', function (e) {
      /* var id = $("#identificador_asiento").val();
        if(id!==""){*/
            location.reload();
     //   }
     });
     
     
     
     
         $('#NuevoDeposito')
    
  
    
    .formValidation({
        framework: 'bootstrap',
         excluded: ':disabled',
        icon: {
          /*  valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',*/
            validating: 'glyphicon glyphicon-refresh'
        },
       
        fields: {
             fecha: {
                validators: {
                    notEmpty: {
                        message: 'Ingrese la Fecha'
                    }
                }
            },
            nombre_banco: {
                validators: {
                    notEmpty: {
                        message: 'Ingrese el Nombre del Banco'
                    }
                }
            },
             
            nombre_cliente: {
                validators: {
                    notEmpty: {
                        message: 'Ingrese el Nombre del Cliente'
                    }
                }
            },
              dep_descripcion: {
                validators: {
                    notEmpty: {
                        
                        message: 'Ingrese la Descripci&oacute;n'
                    }
                }
            }
        }
    })
    .on('change', 'input[name="fecha"]', function(e) {
          
           
           $('#NuevoDeposito').formValidation('revalidateField', 'fecha');
        })
        
         .on('change', 'input[id="descripcion"],input[id="descripcion_1"]', function(e) {
          
           
           $('#NuevoDeposito').formValidation('revalidateField', 'descripcion_1');
        })
        .on('click', 'input[id="descripcion"],input[id="descripcion_1"]', function(e) {
          
           
           $('#NuevoDeposito').formValidation('revalidateField', 'descripcion_1');
        })
        
    
        .on('success.form.fv', function(e) {
            
             e.preventDefault();
               var datos = JSON.stringify($( "#NuevoDeposito" ).serializeArray());
        console.log( datos );
              var VisibleRows = $('#banco_diario tbody tr:visible').length;
          var i=1;
         var cont_lleno=0;
         var array_escritos = [];
         var string_escritos='';
          for(i=1;i<=VisibleRows;i++)
          {
            if ($("#nombre_cuenta_"+i).val().length > 0 && $("#cod_cta_contable_"+i).val().length > 0&& $("#descripcion_"+i).val().length > 0 && $("#debe_"+i).val().length > 0 && $("#haber_"+i).val().length > 0)
             {
                
                cont_lleno++;
                array_escritos.push(i);
            
                }
            
          }
    

          length=array_escritos.length;
         
       $("#filas_diario").val(length);  
           
         $.ajax({
                url: "procesar_bancos.php",
                type: "post",
                data: {
                    proceso: "ingresar_trans_enviada",
                    filas: length,
                    datos: datos
                },
                 beforeSend : function(){
                },
                success: function(data){
                    var nuevaFila="";
                    if(data!=='fallo'){
                        var response = JSON.parse(data);
                        console.log(response);
                  
                        bootbox.alert({
                            title: "Informaci&oacute;n",
                            message: "La Transferencia Enviada se agreg&oacute; Correctamente! ",
                            buttons: {
                                'ok': {
                                    label: 'Aceptar',
                                    className: 'btn-primary'
                                }
                            },
                            callback: function() {
                               $("#btn_agregar_nuevo").hide();
                               $("#imprimir_deposito").show();

                               $("input").attr('disabled', true);
                              
                               $(".selectpicker").attr('disabled', true);
                                   $("#identificador_deposito").val(response[0]["id_enviado"]);
                            }
                        });
                    }else{
                        bootbox.alert({
                            title: "Información",
                            message: "La Transferencia Enviada no se agreg&oacute; Revise los datos y vuelva a intentar!",
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
   
        });
        
       
        
        
        
        
        
        $('.btn_update').on('click', function() {

        });
        
        
            var table=$('#example').dataTable({
      //  "scrollY":        "70%",
        "scrollCollapse": true,
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
    /** PARA MOSTRAR LA IMPRESION DE LA TABLA DEPOSITOS EN EXCEL, PDF, ETC*/
    var tt = new $.fn.dataTable.TableTools( table );
 
    $( tt.fnContainer() ).insertBefore('#example_wrapper');
    
   var t = $('#banco_diario').DataTable({
        
        "scrollCollapse": true,
        "paging":         false,
        "bSort" : false,
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
			},
            "bAutoWidth": false, // Disable the auto width calculation
              "columns": [
     { "width": "10%" },

    { "width": "18%" },
      { "width": "52%" },
        { "width": "10%" },
          { "width": "10%" }
  ],
  
        
    });
    
   
    
    counter=6;
     $('#test').hide();
    /** Agrega una fila nueva en la tabla bancos_diario*/
     $('#addRow').on( 'click', function () {
           ++counter;
      // alert(counter);
       $('#row_'+counter).show();
       if(counter>=40){
        $('#addRow').hide();
    
        }
        else
        $('#addRow').show();
        
        if(counter<=6)
    $('#test').hide();
    else
    $('#test').show();
    
    
       
        
    } );
/** Elimina una fila nueva en la tabla bancos_diario*/
      $('#test').click( function () {
        
   
   var rowCount = $('#banco_diario tbody tr').length;
  var numOfVisibleRows = $('#banco_diario tbody tr:visible').length;
 
 
 /** Esconde la fila y resetea sus campos*/
  $('#row_'+counter).hide();
  $('input[name=nombre_banco_'+counter+']').val('');
  $('input[name=cod_cta_contable_'+counter+']').val('');
 
  $('input[name=nombre_banco_'+counter+']').val('');
  $('input[name=descripcion_'+counter+']').val('');
  $('input[name=debe_'+counter+']').val('');
  $('input[name=haber_'+counter+']').val('');
  $('input[name=debe_'+counter+']').prop('disabled', false);
$('input[name=haber_'+counter+']').prop('disabled', false);
            
   counter--;

   if(counter<=6)
    $('#test').hide();
    else
    $('#test').show();
    if(counter>=40)
    {
        $('#addRow').hide();
     }
        else
        $('#addRow').show();
  
   
   totalsuma();
    
    } );
    
    $("#fecha").datepicker();
     
     
     
     
     
     

/** El datepicker lo escribe en idioma español*/
 $.datepicker.regional['es'] = {
 closeText: 'Cerrar',
 prevText: '<Ant',
 nextText: 'Sig>',
 currentText: 'Hoy',
 monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
 monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
 dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Ssbado'],
 dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sab'],
 dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
 weekHeader: 'Sm',
 dateFormat: 'yy-mm-dd',
 firstDay: 1,
 isRTL: false,
 showMonthAfterYear: false,
 yearSuffix: ''
 };
 $.datepicker.setDefaults($.datepicker.regional['es']);
 
 
 


$("#fecha").on("change", function (e) {
       // alert('hola');
    });





    
    
     

$(document).on('click', '.modal-backdrop', function (event) {
    bootbox.hideAll()
});

/***** DA CONFLICTOS CON EL BOOTSTRAP SELECT***/
   /* (function($){
        $(window).load(function(){
            
            $("#NuevoDeposito").mCustomScrollbar({
    theme:"dark"
});
        });
    })(jQuery);
    */
   /* $("NuevoDeposito").on( "shown", function () {
                $("#NuevoDeposito").mCustomScrollbar({
                      theme:"dark"
                });
            });*/
            
          
            
             $('.selectpicker_diario').selectpicker({
                    width: '120px',
                    title: 'Codigo'
                });
  
  
  $("#imprimir_deposito").click(function(){
        var id = $("#identificador_deposito").val();
        ver_deposito(id);
    });              
                
  

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
    var texto =$("#numero_deposito").val();
    var aux1="";
    var aux2 = $("#fecha").val().substring(0,7);
    var aux3="";
    if(texto===""){
        texto = $("#fecha").val().substring(0,7);
        texto = "TE-"+texto+"-0000";
        aux3 = "0000";
    }else{
        aux1 = $("#numero_deposito").val().substring(3,10);
        texto = texto.replace(aux1, aux2);
        aux3 = $("#numero_deposito").val().substring(11,15);
    }   
      console.log(aux2+aux3+" "+texto+" "+aux1);
        $.ajax({
            url: "procesar_bancos.php",
            type: "post",
            data:{
                proceso: 'ultimo_diario_mes_trans_env',
                mes: aux2
            },
            success: function (data) {
              console.log(data);
                if(data!=='0'){
                    var caracteres = parseInt(data.substring(11,15));
                    caracteres = (""+caracteres).length;
                    var numero = parseInt(data.substring(11,15)) +1;
                    var aux4 = crear_numero_asiento(caracteres, numero);
                    
                    if(aux3!==aux4){
                        texto=texto.replace(aux3, aux4);
                        $("#numero_deposito").val(texto);
                    }
                }else{
                    texto=texto.replace(aux3, "0001");
                    $("#numero_deposito").val(texto);
                }
                
            }
        })
}

cambiar_numero_asiento();
$("#fecha").on("change", function (e) {
        cambiar_numero_asiento();
    });
   
	$('#btn_prueba').on('click',function(){
		  var VisibleRows = $('#banco_diario tbody tr:visible').length;
          var i=1;
         var cont_lleno=0;
         var array_escritos = [];
         var string_escritos='';
          for(i=1;i<=VisibleRows;i++)
          {
            if ($("#nombre_cuenta_"+i).val().length > 0 && $("#cod_cta_contable_"+i).val().length > 0&& $("#descripcion_"+i).val().length > 0 && $("#debe_"+i).val().length > 0 && $("#haber_"+i).val().length > 0)
             {
                
                cont_lleno++;
                array_escritos.push(i);
            
                }
            
          }
    
$("#filas_diario").val(array_escritos);
          length=array_escritos.length;
          
          alert(VisibleRows);
           alert(cont_lleno);
           alert(array_escritos);
           alert(length);
           
        for(var k=0;k<length;k++){
           var num1 = $('#debe_'+array_escritos[k]).maskMoney('unmasked')[0]; alert('type: '+ typeof(num1) + ', value: ' + num1);
           $('#hidden_debe_'+array_escritos[k]).val(num1);
           var num2 = $('#haber_'+array_escritos[k]).maskMoney('unmasked')[0]; alert('type: '+ typeof(num2) + ', value: ' + num2);
           $('#hidden_haber_'+array_escritos[k]).val(num2);
        }
		});
	

//Fin del document ready



});

  function ver_deposito(id){ 
    var loc = window.location;
    var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
    var url = loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + loc.hash).length - pathName.length));
    url = "reporte_trans_enviadas.php?id="+id;
    var w = 850;
    var h = 550;
    
    var x = screen.width/2 - (w/2);
    var y = screen.height/2 - (h/2);
    
    var WindowObject = window.open(url,"Reporte Transferencia Enviada","width="+w+",height="+h+",scrollbars=yes,top="+y+", left="+x+"") 
    WindowObject.document.title = "Reporte Transferencia Enviada";
}
            function unmaskdebe(campo){
                
                 var num1 = $(campo).maskMoney('unmasked')[0]; alert('type: '+ typeof(num1) + ', value: ' + num1);
                
                
            }
 function valor_campo(valor){
    
    document.getElementById('debe_1').value = valor;
    document.getElementById('haber_1').value = '$ 0.00';
    document.getElementById('haber_1').disabled = true; 
     document.getElementById('hidden_haber_1').disabled = true;
                  var val_unmask = $(debe_1).maskMoney('unmasked')[0]; 
    $('#hidden_debe_1').val(val_unmask); 
    var this_unmask = $('#debe_1').maskMoney('unmasked')[0]; 
    $('#hidden_valor').val(this_unmask);
    
    
    
}


/** DESHABILITA EL DEBE O EL HABER RESPECTIVAMENTE*/
var disableFieldHaber = function (ee) {
 // var state = ee.value.length > 0;
  var state = ee.value !=  "$ 0.00";
  var x=ee.id;
 // alert(x);
  var res = x.split("_");
  var num=res[1];
  //alert(num);
  document.getElementById("haber_"+num).value="$ 0.00";
  document.getElementById("haber_"+num).disabled = state;
  document.getElementById("hidden_haber_"+num).value="0";
    document.getElementById("hidden_haber_"+num).disabled = state;
  
    
                
};


var disableFieldDebe = function (ee) {
  //var state = ee.value.length > 0;
  var state = ee.value !=  "$ 0.00";
  var x=ee.id;
//  alert(x);
  var res = x.split("_");
  var num=res[1];
  //alert(num);
  document.getElementById("debe_"+num).value="$ 0.00";
  
  document.getElementById("debe_"+num).disabled = state;
  document.getElementById("hidden_debe_"+num).value="0";
    document.getElementById("hidden_debe_"+num).disabled = state;
};



/** SUMA EL DEBE Y EL HABER Y MUESTRA LA DIFERENCIA */
function totalsuma(campo)
{
                 var val_unmask = $(campo).maskMoney('unmasked')[0]; 
               //  alert(campo.id);
           $('#hidden_'+campo.id).val(val_unmask);
           
    
    var array_debe=[];
    var arr = [ "debe_1", "debe_2", "debe_3", "debe_4", "debe_5","debe_6","debe_7",
       "debe_8","debe_9","debe_10","debe_11","debe_12","debe_13","debe_14","debe_15",
       "debe_16","debe_17","debe_18","debe_19","debe_20","debe_21","debe_22","debe_23"
       ,"debe_24","debe_25","debe_26","debe_27","debe_28","debe_29","debe_30","debe_31"
       ,"debe_32","debe_33","debe_34","debe_35","debe_36","debe_37","debe_38","debe_39"
       ,"debe_40"];
      jQuery.each( arr, function( i, val ) {
         array_debe[i] = $('#'+val).maskMoney('unmasked')[0];
        return ( val !== "debe_40" );
    });
        
       var array_haber=[];
    var arr = [ "haber_1", "haber_2", "haber_3", "haber_4", "haber_5","haber_6","haber_7",
       "haber_8","haber_9","haber_10","haber_11","haber_12","haber_13","haber_14","haber_15",
       "haber_16","haber_17","haber_18","haber_19","haber_20","haber_21","haber_22","haber_23"
       ,"haber_24","haber_25","haber_26","haber_27","haber_28","haber_29","haber_30","haber_31"
       ,"haber_32","haber_33","haber_34","haber_35","haber_36","haber_37","haber_38","haber_39"
       ,"haber_40"];
      jQuery.each( arr, function( i, val ) {
         array_haber[i] = $('#'+val).maskMoney('unmasked')[0];
        return ( val !== "haber_40" );
    });   
        
        
    /*alert(array_debe);
    alert(array_haber);*/
    var newTotaldebe=0;
      for(var i=0;i<40;i++)
         newTotaldebe+=array_debe[i];
         
         var debe_redondeado=new Number(newTotaldebe+'').toFixed(parseInt(2));
        $("#totaldebe").empty();
        $("#totaldebe").text(debe_redondeado);
        
        
         var newTotalhaber=0;
      for(var i=0;i<40;i++)
         newTotalhaber+=array_haber[i]; 
          var haber_redondeado=new Number(newTotalhaber+'').toFixed(parseInt(2));
         $("#totalhaber").empty();
        $("#totalhaber").text(haber_redondeado);
        
        
        
        
        var totdeb= $('#totaldebe').text();
        var tothab= $('#totalhaber').text();
        var dif=parseFloat(totdeb)-parseFloat(tothab);
         var dif_redondeado=new Number(dif+'').toFixed(parseInt(2));
        $("#diferencia").empty();
        $("#diferencia").text(dif_redondeado);
        $("#input_diferencia").val(dif_redondeado);
       var d=document.getElementById("diferencia");
         if(dif==0){
            d.style.color = "black";
            document.getElementById("btn_agregar_nuevo").disabled = false;
            }
        if(dif>0){
            d.style.color = "green";
             $("#diferencia").text('+'+dif_redondeado);
             document.getElementById("btn_agregar_nuevo").disabled = true;
            }
        if(dif<0){
            d.style.color = "red";
            document.getElementById("btn_agregar_nuevo").disabled = true;
        }
}