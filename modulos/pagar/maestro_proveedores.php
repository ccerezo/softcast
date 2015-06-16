<?php 
    include('../../conexion.php');
    // Realizar una consulta MySQL
    include('../../redireccionar.php');
?>
<html>
	<head>
        <title>Cuentas por Pagar - Proveedores</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <link rel="stylesheet" href="../../css/bootstrap.css" />
        <!--<link rel="stylesheet" href="../../css/animate.min.css"/>-->
        <!--<link rel="stylesheet" href="../../css/alertify.core.css"/>-->
        <!--<link rel="stylesheet" href="../../css/alertify.default.css"/>-->
        <link rel="stylesheet" href="../../css/jquery.dataTables.css"/>
        <link rel="stylesheet" href="../../css/jquery.dataTables.min.css"/>
        <!--<link rel="stylesheet" href="/css/bootstrap.min.css"/>
        <link rel="stylesheet" href="/css/dataTables.bootstrap.css"/>-->
        <!--<link rel="stylesheet" href="../../css/fm.scrollator.jquery.css"/>-->
        <link rel="stylesheet" href="../../css/style.css"/>
        <link rel="stylesheet" href="css/style.css" />

        <script src="../../js/jquery-1.9.1.js"></script>
        <script src="../../js/jquery-ui.js"></script>
        <script src="../../js/jquery.validate.js"></script>
        <!--<script src="../../js/alertify.js"></script>-->
        <script src="../../js/jquery.dataTables.js"></script>
        <script src="../../js/bootstrap.js"></script>
        <!--<script src="../../js/fm.scrollator.jquery.js"></script>-->
        <script src="../../js/jquery.maskedinput.js"></script>
        <!--<script src="../../js/jquery-validate.bootstrap-tooltip.js"></script>-->
        <!--<link rel="stylesheet" href="../../css/bootstrap.min.css" />
        <link rel="stylesheet" href="../../css/bootstrap-dialog.min.css" />-->
        <script src="../../js/bootstrap-dialog.min.js"></script>
        <script src="js/pagar.js"></script>
        <style type="text/css">
            .form-group {
                margin-bottom: 10px;
            }
            .modal-body {
                padding: 0px;
            }
            .modal-footer {
                padding: 5px;
                text-align: center;
            }
            .btn {
                padding: 4px 8px;
            }
            .alert-sucess-msn
            {
                -webkit-border-radius: 7px;
                -moz-border-radius: 7px;
                border-radius: 7px;
                padding: 15px;
                color: #468847;
                background-color: #dff0d8;
                border-color: #d6e9c6;
            }
            .alert-error-msn
            {
                -webkit-border-radius: 7px;
                -moz-border-radius: 7px;
                border-radius: 7px;
                padding: 15px;
                color: #b94a48;
                background-color: #f2dede;
                border-color: #ebccd1;
            }
            .alert-warning-msn
            {
                -webkit-border-radius: 7px;
                -moz-border-radius: 7px;
                border-radius: 7px;
                padding: 15px;
                color: #c09853;
                background-color: #fcf8e3;
                border-color: #faebcc;
            }
        </style>
	</head>
	<body>
		<section class="container-fluid cabecera">
            <?php require('../../menu_principal.php'); ?>
        </section>
        <section class="container-fluid cabecera_oculta"></section>
        <section class="container contenido_cuentas">
            <h4 class="titulo_ventana">Maestro de Proveedores</h4>
            <button type="button" class="btn btn-primary" data-id='nuevo1' data-toggle="modal" data-target="#modal_maestro_prov">Nuevo</button>
            <!--<button type="button" class="btn btn-primary">Modificar</button>
            <button type="button" class="btn btn-primary">Eliminar</button>-->
            <div class="area_pantalla">
                <!--<div class="loading_icon"><img src="../../images/710.gif"></div>-->
                <!--<div class="table-responsive">-->
                    <!--<table class="table table-striped table-hover" cellspacing="0" width="100%" id="dataTable_data">
                    --><table  id="dataTable_data" class="display table table-striped" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Tipo Proveedor</th>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Cédula</th>
                                <th>Dirección</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                <!--</div>-->
            </div>
            <div class="modal fade agregar" id="modal_maestro_prov" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog">
                    <div class="modal-content">
            		    <form action="#" class="form-horizontal" role="form" enctype="multipart/form-data" method="post" id="registro_cuenta">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="title_ventana">Nuevo Provedor</h4>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label class="control-label col-sm-5" for="natural_juridica">Persona Natural o Juridica:</label>
                                    <div class="radio col-sm-6">
                                        <label class="col-sm-3">
                                            <input type="radio" name="natural_juridica" value="S" checked>
                                            Si
                                        </label>
                                        <label>
                                            <input type="radio" name="natural_juridica" value="N">
                                            No
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-5" for="contabilidad">Lleva contabilidad:</label>
                                    <div class="radio col-sm-6">
                                        <label class="col-sm-3">
                                            <input type="radio" name="contabilidad" value="S" checked>
                                            Si
                                        </label>
                                        <label>
                                            <input type="radio" name="contabilidad" value="N">
                                            No
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-5" for="tipo_proveedor">Tipo de Proveedor:</label>
                                    <div class="radio col-sm-6">
                                        <label class="col-sm-4">
                                            <input type="radio" name="tipo_proveedor" value="bienes" checked>
                                            Bienes
                                        </label>
                                        <label>
                                            <input type="radio" name="tipo_proveedor" value="servicios">
                                            Servicios
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-5" for="codigo_cuenta">Código:</label>
                                    <div class="col-sm-6"> 
                                    <input type="text" class="form-control" id="codigo_cuenta" name="codigo_cuenta" value="PB1" placeholder="Código de la Cuenta" title="Error" default-msn="Error" disabled>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-5" for="nombre">Nombre:</label>
                                    <div class="col-sm-6">
                                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre del Proveedor" title="Ingrese Proveedor" default-msn="Ingrese Proveedor">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-5" for="ruc_cedula">Cédula/Ruc:</label>
                                    <div class="col-sm-6">
                                    <input type="text" class="form-control required" id="ruc_cedula" name="ruc_cedula" data-reg="^[0-9]{10,}$" placeholder="Example: 0123456789 / 0123456789001" title="Ingrese Ruc o Cédula. Solo se permiten Números" default-msn="Ingrese Ruc o Cédula. Solo se permiten Números">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-5" for="direccion">Dirección:</label>
                                    <div class="col-sm-6">
                                    <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Dirección Domiciliaria" title="Ingrese dirección domiciliaria" default-msn="Ingrese dirección domiciliaria">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-5" for="email">Email:</label>
                                    <div class="col-sm-6">
                                    <input type="text" class="form-control" id="email" name="email" data-reg="^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$" placeholder="Correo electronico" title="Ingrese correo electronico" default-msn="Ingrese correo electronico">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-5" for="direccion">Teléfono:</label>
                                    <div class="col-sm-6">
                                    <input type="text" class="form-control" id="telefono" name="telefono" data-reg="^[0-9]{7,}$" placeholder="Teléfono" title="Ingrese teléfono. Solo se permiten Números" default-msn="Ingrese teléfono. Solo se permiten Números">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-5" for="autorizacion_sri">Autorización del SRI:</label>
                                    <div class="col-sm-6">
                                    <input type="text" class="form-control" id="autorizacion_sri" name="autorizacion_sri" data-reg="^[0-9]{4,}$" placeholder="Autorización del SRI" title="Ingrese autorización del sri (4 o mas Números)." default-msn="Ingrese autorización del sri (4 o mas Números).">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-5" for="retencion_iva">Retención IVA:</label>
                                    <div class="col-sm-6">
                                    <select class="form-control" id="retencion_iva" name="retencion_iva" title="Ingrese retención IVA" default-msn="Ingrese retención IVA">
                                        <option value="-1">Seleccionar</option>
                                    </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-5" for="retencion_fuente">Retención fuente:</label>
                                    <div class="col-sm-6">
                                    <select class="form-control" id="retencion_fuente" name="retencion_fuente" title="Ingrese retención fuente" default-msn="Ingrese retención fuente">
                                        <option value="-1">Seleccionar</option>
                                    </select>
                                    </div>
                                </div>
                                <!--<a href="#" data-toggle="tooltip" title="Some tooltip text!">Hover over me</a>
                                <button type="button" class="btn btn-default" data-toggle="tooltip" data-placement="right" title="Tooltip on rightsss">Tooltip on right</button>
                                -->
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary btn_agregar_cuenta" id="btn_save">Guardar</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal" id="btn_cancelar">Cancelar</button>
                            </div>
                            <input name="id" id="id" type="hidden" value="" class="form-control">
                            <input name="orden" type="hidden" value="guardar" class="form-control">
			            </form>
		            </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
        </section>
        <div class="form-group modal-sm" id="alerta" style="display: none;text-align:center">
            <br />
            <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
        </div>
	</body>
</html>