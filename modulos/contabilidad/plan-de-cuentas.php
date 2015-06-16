<?php 

    include('../../conexion.php');
    // Realizar una consulta MySQL

    include('../../redireccionar.php');

?>
<html>
	<head>
            <title>Contabilidad - Plan de Cuentas</title>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1" />


            <link rel="stylesheet" href="../../css/bootstrap.css" />
            <link rel="stylesheet" href="../../css/jquery.dataTables.css"/>
            <link rel="stylesheet" href="../../css/jquery.dataTables.min.css"/>
            <link rel="stylesheet" href="../../css/fm.scrollator.jquery.css"/>
            <link rel="stylesheet" href="../../css/style.css"/>

            <link rel="stylesheet" href="css/jquery.treegrid.css" />
            <link rel="stylesheet" href="css/style.css" />
            
            <script src="../../js/jquery-1.9.1.js"></script>
            <script src="../../js/jquery-ui.js"></script>
            <script src="../../js/jquery.validate.js"></script>
            <script src="../../js/jquery.dataTables.js"></script>
            <script src="../../js/bootstrap.min.js"></script>
            <script src="../../js/fm.scrollator.jquery.js"></script>
            <script src="../../js/jquery.maskedinput.js"></script>
            <script src="../../js/bootbox.min.js"></script>

            <script src="js/jquery.treegrid.js"></script>
            <script src="js/contabilidad.js"></script>

	</head>
	<body>
		
            <section class="container-fluid cabecera">

                    <?php require('../../menu_principal.php'); ?>

            </section>


            <section class="container-fluid cabecera_oculta"></section>

            <section class="container contenido_cuentas">
                <h4 class="titulo_ventana">Plan de Cuentas</h4>
                <section class="container-fluid">
                    <section class="row">
                        <div class="col-md-3">
                            <button type="button" class="btn btn-primary glyphicon glyphicon-file" data-id='nuevo1' data-toggle="modal" data-target="#myModal" title="Agregar"></button>
                            <button type="button" class="btn btn-primary glyphicon glyphicon-edit" title="Modificar"></button>
                            <button type="button" class="btn btn-primary glyphicon glyphicon-trash" id="eliminar_cuenta" title="Eliminar"></button>
                        </div>
                        
                        <div class="col-md-2"></div>
                        
                        <div class="col-md-2">
                            <button type="button" class="btn btn-primary glyphicon glyphicon-chevron-down" id="expandir" title="Expandir"></button>
                            <button type="button" class="btn btn-primary glyphicon glyphicon-chevron-up" id="colapsar" title="Contraer"></button>
                        </div>
                        
                        <div class="col-md-2"></div>
                        
                        <div class="col-md-3">
                            <!--<a href="pdf-plan-de-cuentas.php" target="_blank" type="button" class="btn btn-primary glyphicon glyphicon-print" title="Imprimir"></a>-->
                            <form action="pdf-plan-de-cuentas.php" method="post" id="exportar-pdf">
                                <button type="button" class="btn btn-primary glyphicon glyphicon-print" id="pdf" title="Imprimir"></button>
                                <input type="hidden" id="datos_pdf_enviar" name="datos_pdf_enviar" />
                            </form>
                            <form action="excel-plan-de-cuentas.php" method="post" id="exportar-excel">
                                <button type="button" class="btn btn-primary glyphicon glyphicon-list" id="excel" title="Exportar Excel"></button>
                                <input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
                            </form>
                            <button type="button" class="btn btn-primary glyphicon glyphicon-search" data-id='nuevo2' data-toggle="modal" data-target="#myModal2" title="Buscar"></button>
                        </div>
                    </section>
                </section>    
                
                
		<table class="table table-striped table-bordered cabecera_table">
                        <tr>
                            <th style="width:30%;">Código</th>
                            <th style="width:70%;">Nombre</th>
                        </tr>
                    </table>				
                <div class="area_pantalla">
                    <div class="loading_icon"><img src="../../images/710.gif"></div>
                        
                </div>
                
            <div class="modal fade agregar" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
		    <div class="modal-content">
		    	<form action="#" enctype="multipart/form-data" method="post" id="registro_cuenta">
                            <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title">Agregar Cuenta</h4>
                            </div>
                            <div class="modal-body">
                                
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Código:</label>
                                    <input type="text" class="form-control" id="codigo_cuenta" name="codigo_cuenta" placeholder="Código de la Cuenta" required>
                                    <label class="error" for="codigo" id="codigo_error">Ya existe ese número de cuenta. <span id="ya_existe"></span></label>
                                    <label class="error" for="codigo" id="codigo_error2">Código Inválido. <span id="codigo_novalido"></span> </label>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Nombre:</label>
                                    <input type="text" class="form-control" id="nombre_cuenta" name="nombre_cuenta" placeholder="Nombre de la Cuenta" required>
                                    
                                </div>
                            </div>
                            <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary btn_agregar_cuenta">Agregar</button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal" id="btn_cancelar">Cancelar</button>
                            </div>
			</form>
		    </div><!-- /.modal-content -->
		  </div><!-- /.modal-dialog -->
		</div><!-- /.modal -->
                
                <div class="modal fade agregar" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
		    <div class="modal-content">
		    	<form action="#" enctype="multipart/form-data" method="post" id="form_buscar_cuenta">
                        <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">Buscar Cuenta</h4>
                        </div>
                        <div class="modal-body">

                            <div class="form-group">
                                <input type="text" class="form-control" id="buscar_cuenta" name="buscar_cuenta" placeholder="Buscar..." required>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary btn_buscar_cuenta">Buscar</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal" id="btn_cancelar">Cancelar</button>
                        </div>
                        </form>
		    </div><!-- /.modal-content -->
		  </div><!-- /.modal-dialog -->
		</div><!-- /.modal -->
                
            </section>
		
		
	</body>
</html>