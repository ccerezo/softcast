
<!DOCTYPE html>
<html>
    <head>
	<title>Sistema SOFTCAST</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    	<meta name="viewport" content="width=device-width, initial-scale=1" />
    	
    	<link rel="stylesheet" href="css/bootstrap.css" />
    	<link rel="stylesheet" href="css/bootstrap.min.css" />
	<link rel="stylesheet" href="css/style.css" />

	<script src="js/jquery-1.9.1.js"></script>
	<script src="js/jquery.validate.js"></script>
	<script src="js/bootstrap.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/login.js"></script>

    </head>
	<body>
		<section class="container-fluid cabecera_oculta"></section>
		<section class="container-fluid cabecera bienvenido">Bienvenido al Sistema SOFTCAST</section>
		<!--<section class="container-fluid">
			<section class="row">

			<div class="col-md-1"></div>

			<div class="col-md-3 recuadro_izquierda">
				<div class="img_inicio">
					<a href="#" class="botones" id="registrar_empresa"><img src="images/empresa.png" > <span class="txt_botones">Registrar Empresa</span></a>
				</div>
				<hr>
				<form id="empresa" class="form_inicio">
				  	<div class="form-group">
				    	<label for="exampleInputEmail1">Nombre:</label>
				    	<input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre de la Empresa">
				  	</div>
				  	<div class="form-group">
				    	<label for="exampleInputEmail1">Propietario:</label>
				    	<input type="text" class="form-control" id="propietario" name="propietario" placeholder="Propietario">
				  	</div>
				  	<div class="form-group">
				    	<label for="exampleInputEmail1">R.U.C.:</label>
				    	<input type="text" class="form-control" id="ruc" name="ruc" placeholder="RUC">
				  	</div>
				  	<div class="form-group">
				    	<label for="exampleInputEmail1">Ciudad:</label>
				    	<input type="text" class="form-control" id="ciudad" name="ciudad" placeholder="Ciudad">
				  	</div>
				  	<div class="form-group">
				    	<label for="exampleInputEmail1">Dirección:</label>
				    	<input type="text" class="form-control" id="direccion" name="direccion" placeholder="Direccion">
				  	</div>
				  	<div class="form-group">
				    	<label for="exampleInputEmail1">Teléfono:</label>
				    	<input type="text" class="form-control" id="telefono" name="telefono" placeholder="Teléfono">
				  	</div>
				  	
				  	<button type="submit" class="btn btn-primary btn-block">Registrar</button>
				</form>
				
			</div>


			<div class="col-md-3 recuadro_centro">
				<div class="img_inicio">
					<a href="#" class="botones" id="sesion"><img src="images/sesion.png"> <span class="txt_botones">Iniciar Sesión</span></a>
				</div>

				<hr>
				<form id="login" class="form_inicio" method="post" action="#">
				  	<div class="form-group">
				    	<label for="exampleInputEmail1">Usuario:</label>
				    	<input type="text" class="form-control" id="usuario_sesion" name="usuario_sesion" placeholder="Usuario">
				  	</div>
				  	
				  	<div class="form-group">
				    	<label for="exampleInputPassword1">Password</label>
				    	<input type="password" class="form-control" id="clave_sesion" name="clave_sesion" placeholder="Password">
				  	</div>
				  	<button type="submit" id="btn_login" class="btn btn-primary btn-block">Login</button>
				</form>

				<img src="images/loader.gif" alt="" class="cargando" />

			</div>

			

			<div class="col-md-3 recuadro_derecha">
				<div class="img_inicio">
					<a href="#" class="botones" id="registrar_user"><img src="images/agregar_usuario.png" > <span class="txt_botones">Registrar Usuario</span></a>
				</div>
				
				<hr>

				<form id="usuario" class="form_inicio">

					<div class="form-group">
				    	<label for="exampleInputEmail1">Clave Maestra:</label>
				    	<input type="text" class="form-control" id="clave_maestra" name="clave_maestra" onchange="consulta_clave()" placeholder="Clave Maestra">
				  	</div>

					<div class="form-group">
				    	<label for="exampleInputEmail1">Nombre:</label>
				    	<input type="text" class="form-control" id="nombre_nuevo" name="nombre_nuevo" disabled placeholder="Nombre">
				  	</div>

				  	<div class="form-group">
				    	<label for="exampleInputEmail1">Apellido:</label>
				    	<input type="text" class="form-control" id="apellido_nuevo" name="apellido_nuevo" disabled placeholder="Apellido">
				  	</div>

				  	<div class="form-group">
				    	<label for="exampleInputEmail1">Usuario:</label>
				    	<input type="text" class="form-control" id="usuario_nuevo" name="usuario_nuevo" disabled placeholder="Usuario">
				  	</div>
				  	
				  	<div class="form-group">
				    	<label for="exampleInputPassword1">Password</label>
				    	<input type="password" class="form-control" id="clave_nuevo" name="clave_nuevo" disabled placeholder="Password">
				  	</div>
				  	<button type="submit" class="btn btn-primary btn-block">Registrar</button>
				</form>

			</div>

			<div class="col-md-1"></div>

			</section>
		</section>-->
		<section class="container">
			
			<div class="row">
			<div class="col-sm-6 col-md-4 col-md-offset-4">
				<div class="panel panel-default">
					<div class="panel-heading">
						<strong>Inicie Sesión para continuar</strong>
					</div>
                            <div class="panel-body">
                                <form id="login" class="form_inicio" method="post" action="#">
                                    <fieldset>
                                        <div class="row">
                                            <div class="center-block img_login">
                                                    <img class="profile-img" src="images/cs.jpg" alt="">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 col-md-10  col-md-offset-1 ">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                                <i class="glyphicon glyphicon-user"></i>
                                                        </span> 
                                                        <input type="text" class="form-control" id="usuario_sesion" name="usuario_sesion" placeholder="Usuario" autofocus>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                                <i class="glyphicon glyphicon-lock"></i>
                                                        </span>
                                                        <input type="password" class="form-control" id="clave_sesion" name="clave_sesion" placeholder="Password">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <button type="submit" id="btn_login" class="btn btn-primary btn-block">Login</button>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </form>
                            </div>
                            <div class="panel-footer ">
                                    (*) Campos Obligatorios.  
                            </div>
                </div>
			</div>
		</div>
			
		</section>
	</body>
</html>