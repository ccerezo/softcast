 $(function() {
//var that= $(modalNuevoBanco);
    $("button#btn_agregar_nuevo_banco").click(function(){
       // var that= $(modalNuevoBanco);
               $.ajax({
                   type: "POST",
                //   context: $(modalNuevoBanco),
            url: "procesar_bancos.php",
            data: $('#infoNuevoBanco').serialize(),
                success: function(msg){
                        // $("#thanks").html(msg)
               //$( that ).dialog( "close" );
                    // that.modal("hide");
                        alert("Banco Ingresado");
                     //  $( "#modalNuevoBanco").remove;
                       //  $( "#modalNuevoBanco" ).dialog( "close" );
                      //  $("#modalNuevoBanco").modal('hide');
                       //$("#alerta").modal('show');
                        window.location.href="bancos.php";
                       
                 },
            error: function(){
                alert("failure");
                }
                
                
                  });
                  
                  
                  
                  
    });
    
    
  /*  
    $("#btn_agregar_nuevo").submit(function(){
       
      
               $.ajax({
                   type: "POST",
                
            url: "procesar_bancos.php",
            data: $('#NuevoBanco').serialize(),
            
                success: function(msg){
                        
                        alert("Banco Ingresado");
                    
                        window.location.href="bancos.php";
                       
                 },
            error: function(){
                alert("failure");
                }
                
                
                  });
                  
                  
                  
                  
    });*/
});