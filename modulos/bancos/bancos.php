<?php 
	include('../../conexion.php');
	// Realizar una consulta MySQL

	include('../../redireccionar.php');

	
?>
<html>
    <head>
        <title>Bancos</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <link rel="stylesheet" href="../../css/bootstrap.css" />
        <link rel="stylesheet" href="../../css/bootstrap-select.css" />
        <link rel="stylesheet" href="../../css/bootstrap-datepicker.css" />
        <link rel="stylesheet" href="../../css/jquery.dataTables.css"/>
        <link rel="stylesheet" href="../../css/jquery.dataTables.min.css"/>
        <link rel="stylesheet" href="../../css/fm.scrollator.jquery.css"/>
        <link rel="stylesheet" href="../../css/style.css"/>

        <link rel="stylesheet" href="css/movimiento_bancario.css" />

        <script src="../../js/jquery-1.9.1.js"></script>
        <script src="../../js/jquery.dataTables.min.js"></script>
        <script src="../../js/moment.min.js"></script>
        <script src="../../js/jquery.validate.js"></script>		
        <script src="../../js/jquery.maskMoney.min.js"></script>
        <script src="../../js/bootstrap.js"></script>
        <script src="../../js/bootstrap-datepicker.js" charset="UTF-8"></script>
        <script src="../../js/bootbox.min.js"></script>
        <script src="../../js/fm.scrollator.jquery.js"></script>
        
        <script src="js/bancos.js"></script>
        
    </head>
    <body>
		
	<section class="container-fluid cabecera">
			
            <?php require('../../menu_principal.php'); ?>

	</section>

	<section class="container-fluid cabecera_oculta"></section>

            <section class="container contenido_cuentas">
		<h4 class="titulo_ventana">Listado de Bancos</h4>
                <button type="button" class="btn btn-primary glyphicon glyphicon-file" id="agregar" data-id='nuevo1' data-toggle="modal" data-target="#myModal" title="Agregar"></button>
                <button type="button" class="btn btn-primary">Modificar</button>
                <div class="area_pantalla">
		<table id="tabla_bancos" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Banco</th>
                            <th>Cuenta</th>
                            <th>Tipo</th>
                            <th>Saldo</th>
                            <th>Cta. Contable</th>
                            <th>Acción</th>
                        </tr>                
                    </thead>
                    <tbody>
                    </tbody>
		</table>
                </div>
            </section>
        
        <div class="modal fade bs-example-modal-lg agregar" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
		<div class="modal-content">
                    <form action="#" enctype="multipart/form-data" method="post" id="form_registro_bancario">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Crear Banco</h4>
                    </div>
                    <div class="modal-body">
                        <div id="panel_bancos">
                            <div class="panel panel-default mi_panel">
                            <div class="panel-heading">
                                <h4 class="panel-title">Datos del Banco</h4>
                            </div>
                            <div class="panel-body">
                                <div class="campo col-md-6">
                                    <label class="col-md-12">Código:</label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control dato_cliente" id="codigo_banco" name="codigo_banco" disabled required>
                                    </div>
                                </div>
                                <div class="campo col-md-6">
                                    <label class="col-md-12">Fecha:</label>
                                    <div class="input-group date col-md-12" id="fecha_banco">
                                        <input type='text' class="form-control dato_empresa" id='fecha' name='fecha' />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="campo col-md-12">
                                    <label class="col-md-12">Nombre:</label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control dato_cliente" id="nombre_banco" name="nombre_banco" required>
                                    </div>
                                </div>
                                <div class="campo col-md-12">
                                    <label class="col-md-12">Número de Cuenta:</label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control dato_cliente" id="numero_cuenta" name="numero_cuenta" required>
                                    </div>
                                </div>
                                <div class="campo col-md-12">
                                    <label class="col-md-12">Tipo de Cuenta:</label>
                                    <div class="col-md-12">
                                        <label class="radio-inline"><input type="radio" name="tipo_cuenta" value="Ahorros" checked>Ahorros</label>
                                        <label class="radio-inline"><input type="radio" name="tipo_cuenta" value="Corriente">Corriente</label>
                                    </div>
                                </div>
                                <div class="campo col-md-12">
                                    <label class="col-md-12">Saldo:</label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control valor dato_cliente" id="saldo" name="saldo" required>
                                    </div>
                                </div>
                                <div class="campo col-md-12">
                                    <label class="col-md-12">Nombre de la Cuenta Contable:</label>
                                    <div class="col-md-12">
                                        <select id="banco" name="banco" class="selectpicker" data-live-search="true"></select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn_agregar" >Agregar</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal" id="btn_cancelar">Cerrar</button>
                </div>
                </form>
		</div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
        
    </body>
</html>
