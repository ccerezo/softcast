$(document).ready(function() {

      $('#nombre_banco').on('change', function(){
      selectedOption = $('option:selected', this);
      $('input[name=tipo_cuenta]').val( selectedOption.data('tipo') );
      $('input[name=numero_cuenta]').val( selectedOption.data('numero-cuenta') );
      $('input[name=nom_banco]').val( selectedOption.data('nombre') );
      $('input[name=ti_cuenta]').val( selectedOption.data('tipo') );
      $('input[name=num_cuenta]').val( selectedOption.data('numero-cuenta') );
      if(selectedOption=='')
        $('input[name=codigo_banco]').val('');
     });
            
                
    $('#rangofecha').daterangepicker ({
                format: 'YYYY-MM-DD',
                locale: {
                applyLabel: 'Aplicar',
                cancelLabel: 'Cancelar',
                fromLabel: 'Desde',
                toLabel: 'Hasta',
                customRangeLabel: 'Custom',
                daysOfWeek: ['Do', 'Lu', 'Ma', 'Mie', 'Jue', 'Vie','Sa'],
                monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                firstDay: 1
               }
    });
                  
       $('#consulta_librobancos')
        .find('[name="rangofecha"]')
        .selectpicker()
        .change(function(e) {
         // revalidate the language when it is changed
        $('#consulta_librobancos').formValidation('revalidateField', 'rangofecha');
         })
          .end()
      .formValidation({
        framework: 'bootstrap',
         excluded: ':disabled',
        fields: {
             nombre_banco: {
                validators: {
                    notEmpty: {
                        message: 'Ingrese el Nombre'
                    }
                }
            },
             rangofecha: {
                validators: {
                    notEmpty: {
                        message: 'Ingrese la Fecha'
                    }
                }
            }
        }
      })        
                  
    .on('success.form.fv', function(e) {
         e.preventDefault();
              var id = $("#nombre_banco").val();
           var fechainicio = $("#rangofecha").val().substring(0,10);
           var fechafin = $("#rangofecha").val().substring(13,23);
    consultar_librobancos(id,fechainicio,fechafin);
    });          
});

function consultar_librobancos(id,inicio,fin){ 
    var loc = window.location;
    var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
    var url = loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + loc.hash).length - pathName.length));
    url = "reporte_consulta_librobancos.php?id="+id+"&inicio="+inicio+"&fin="+fin;
    var w = 850;
    var h = 550;
    
    var x = screen.width/2 - (w/2);
    var y = screen.height/2 - (h/2);
    
    var WindowObject = window.open(url,"Consulta Libro Bancos","width="+w+",height="+h+",scrollbars=yes,top="+y+", left="+x+"") 
    WindowObject.document.title = "Consulta Libro Bancos";
}