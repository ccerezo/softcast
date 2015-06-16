$(document).ready(function(){

	var orden = "";
	$('#registrar_empresa').click(function(){
		$('#usuario').hide();
		$('#login').hide();
		$('#empresa').show();
		$('#empresa').addClass('animated bounceInDown');
	});

	$('#sesion').click(function(){
		$('#empresa').hide();
		$('#usuario').hide();
		$('#login').show();
		$('#login').addClass('animated bounceInDown');
	});

	$('#registrar_user').click(function(){
		$('#empresa').hide();
		$('#login').hide();
		$('#usuario').show();
		$('#usuario').addClass('animated bounceInDown');
	});	



	$(".btn_enviar").click(function() {

		var nombre = $('#nombre').val();
		var apellido = $('#apellido').val();
		var usuario = $('#usuario').val();
		var clave = $('#clave_registrar').val();

		if(nombre.length == 0 || /^\s+$/.test(nombre)){
			$('#nombre').focus();
			alertify.alert("<b>Complete todos los Campos</b>", function () {
						           
			});
		}
		else
			if(apellido.length == 0 || /^\s+$/.test(apellido)){
				$('#apellido').focus();
				alertify.alert("<b>Complete el campo APELLIDO</b>", function () {
						           
				});
			}
			else
				if(usuario.length == 0 || /^\s+$/.test(usuario)){
					$('#usuario').focus();
					alertify.alert("<b>Complete el campo USUARIO</b>", function () {
						           
					});
				}
				else
					if(clave.length == 0 || /^\s+$/.test(clave)){
						$('#clave_registrar').focus(); 
						alertify.alert("<b>Complete el campo CLAVE</b>", function () {
						           
						});
					}
					else{

						 $.ajax({
						 	url: "procesar.php",
						    type: "POST",
						    data:{
						    		orden: 'registrar_usuario',
				                    nombre : nombre,
				                    apellido : apellido,
				                    usuario : usuario,
				                    clave : clave
				            },
						    success: function(data){
						     console.log(data);
						     if(data=='duplicado'){
						     	$('#usuario').val("");
								$('#clave_registrar').val("");
								$('#usuario').focus();
								alertify.alert("<b>El Usuario ya exite!</b>", function () {
						           		
				      			});
						     }else{
							     	$('#nombre').val("");
									$('#apellido').val("");
									$('#usuario').val("");
									$('#clave_registrar').val("");
									//un alert
				     			 	alertify.alert("<b>Registro Existoso</b>", function () {
						           		$('#registrar_usuario').removeClass('ver');
										$('#registrar_usuario').addClass('ocultar');
				      				});
				     			}
						    }
				  		});
		 			}
	});

	// VALIDAR LOGIN DE USUARIO
	$("#login").validate({
		submitHandler: function(){
			var usuario = $('#usuario_sesion').val();
			var clave = $('#clave_sesion').val();
			
			$.ajax({
				url: "procesar.php",
				type: "post",
				data: {
					orden: "iniciar_sesion",
					usuario: usuario,
					clave: clave
				},
				success: function(data){
					console.log(data);
					var response = JSON.parse(data);
					console.log(response);
					if(response["estado"] == "404"){
						$("#usuario_sesion").val("");
						$("#clave_sesion").val("");
						$('.cargando').css('display', 'none');
						alert("Credenciales Incorrectas");
					}	
					else{
						$("#usuario_sesion").val("");
						$("#clave_sesion").val("");
						window.location.href = "http://localhost/dyansoft/sistema.php";
					}			
				}
			});
		}
	});
	

	
});


function consulta_clave(){
						
	  	  //obtenemos el texto introducido en el campo de búsqueda
	  	  consulta = $("#clave_maestra").val();
	  	 
	  	  $.ajax({
	  	  	  type: "POST",
	  	  	  url: "procesar.php",
	  	  	  data: {
	  	  	  		b:consulta, 
	  	  	  		orden: 'usuario_maestro'
	  	  	  },
	  	  	  dataType: "html",
	  	  	  beforeSend: function(){
	  	  	  	  //imagen de carga
	  	  	  	  $("#resultado").html("<p align='center'><img src='images/loader.gif' /></p>");
	  	  	  },
	  	  	  error: function(){
	  	  	  	  alert("error petición ajax");
	  	  	  },
	  	  	  success: function(data){    
	  	  	  	if(data=='ok'){
	  	  	  		$("#nombre_nuevo").prop('disabled', false);
	  	  	  		$("#apellido_nuevo").prop('disabled', false);
	  	  	  		$("#usuario_nuevo").prop('disabled', false);
	  	  	  		$("#clave_nuevo").prop('disabled', false);
	  	  	  		
	  	  	  	}
	  	  	  	else{
		  	  	  	alert("Ud no posee la clave maestra!");
		  	  	  	
				}
								
	  	  	  }
	  	  	});
	  
}
