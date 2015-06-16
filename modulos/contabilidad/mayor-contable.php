<?php 
	include('../../conexion.php');
	// Realizar una consulta MySQL

	include('../../redireccionar.php');

	
?>
<html>
    <head>
        <title>Contabilidad - Mayor Contable</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <link rel="stylesheet" href="../../css/bootstrap.css" />
        <link rel="stylesheet" href="../../css/bootstrap-select.css" />
        <link rel="stylesheet" href="../../css/bootstrap-datepicker.css" />
        <link rel="stylesheet" href="../../css/style.css"/>

        <link rel="stylesheet" href="css/asiento-diario.css" />

        <script src="../../js/jquery-1.9.1.js"></script>
        <script src="../../js/moment.min.js"></script>
        <script src="../../js/jquery.validate.js"></script>		
        <script src="../../js/bootstrap.js"></script>
        
        <script src="../../js/bootstrap-datepicker.js" charset="UTF-8"></script>
        <script src="../../js/bootbox.min.js"></script>
        
        <script src="js/mayor-contable.js"></script>
        
    </head>
    <body>
		
	<section class="container-fluid cabecera">
			
            <?php require('../../menu_principal.php'); ?>

	</section>

	<section class="container-fluid cabecera_oculta"></section>

            <section class="container contenido_cuentas">
		<h4 class="titulo_ventana">Mayor Contable</h4>
                <div class="container">
                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-lg-6 parametros">
                            <h4 class="titulo_parametros">Generar Reporte</h4>
                        <form id="reporte_mayor_contable" action="#" method="post">
                            <div class="row">
                                <div class="col-md-1"></div>
                                <div class="form-group col-md-10">
                                    <label class="col-md-4">Desde:</label>
                                    <div class="col-md-8" style="padding-left: 0px;">
                                        <select id="codigo1" name="codigo1" class="selectpicker" data-live-search="true"></select>
                                    </div>
                                </div>
                                <div class="col-md-1"></div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-1"></div>
                                <div class="form-group col-md-10">
                                    <label class="col-md-4">Hasta:</label>
                                    <div class="col-md-8" style="padding-left: 0px;">
                                        <select id="codigo2" name="codigo2" class="selectpicker" data-live-search="true"></select>
                                    </div>
                                </div>
                                <div class="col-md-1"></div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-1"></div>
                                <div class="form-group col-md-10">
                                    <label class="col-md-4">Fecha Inicial:</label>
                                    <div class="input-group date col-md-8" id="datetimepicker1">
                                        <input type='text' class="form-control" id='fecha_reporte1' name='fecha_asiento' />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                    </div>
                                </div>
                                <div class="col-md-1"></div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-1"></div>
                                <div class="form-group col-md-10">
                                    <label class="col-md-4">Fecha Final:</label>
                                    <div class="input-group date col-md-8" id="datetimepicker2">
                                        <input type='text' class="form-control" id='fecha_reporte2' name='fecha_asiento' />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                    </div>
                                </div>
                                <div class="col-md-1"></div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary" >Consultar</button>
                        </form>
                        </div>
                        <div class="col-md-3"></div>
                    </div>
                </div>
            </section>
        
    </body>
</html>
