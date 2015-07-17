<?php 
	include('../../conexion.php');
	// Realizar una consulta MySQL

	include('../../redireccionar.php');

	
?>
<html>
    <head>
        <title>Bancos - Transferencias Recibidas</title>
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
        
        <script src="js/transferencias-recibidas.js"></script>
        
    </head>
    <body>
		
	<section class="container-fluid cabecera">
			
            <?php require('../../menu_principal.php'); ?>

	</section>

	<section class="container-fluid cabecera_oculta"></section>

            <section class="container contenido_cuentas">
		<h4 class="titulo_ventana">Transferencias Recibidas</h4>
                <button type="button" class="btn btn-primary glyphicon glyphicon-file" id="agregar" data-id='nuevo1' data-toggle="modal" data-target="#myModal" title="Agregar"></button>
                <div class="area_pantalla">
		<table id="tabla_tr" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>T.R. N°</th>
                            <th>Fecha</th>
                            <th>Banco</th>
                            <th>Cuenta</th>
                            <th>Tipo</th>
                            <th>Descripción</th>
                            <th>Valor</th>
                            <th>Acción</th>
                        </tr>                
                    </thead>
                    <tbody>
                    </tbody>
		</table>
                </div>
            </section>
        <input type="hidden" name="identificador_bancario" id="identificador_bancario" value="">
        <div class="modal fade bs-example-modal-lg agregar" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
		<div class="modal-content">
                    <form action="#" enctype="multipart/form-data" method="post" id="form_registro_bancario">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Transferencia Recibida: <span id="numero_diario"></span></h4>
                    </div>
                    <div class="modal-body">
                        <div id="seccion1">
                        <div id="panel_bancos" class="col-md-4">
                            <div class="panel panel-default mi_panel">
                            <div class="panel-heading">
                                <h4 class="panel-title">Datos del Banco</h4>
                            </div>
                            <div class="panel-body">
                                <div class="form-group col-md-12">
                                    <label class="col-md-3">Nombre:</label>
                                    <div class="col-md-9">
                                        <select id="banco" name="banco" class="selectpicker_banco" data-live-search="true"></select>
                                        <button type="button" class="btn btn-primary glyphicon glyphicon-plus btn-xs" data-id='nuevo1' data-toggle="modal" data-target="#myModal1" title="Agregar Cliente"></button>
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    <label class="col-md-3">Tipo:</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control dato_cliente" id="tipo_cuenta" name="tipo_cuenta" required>
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    <label class="col-md-3">Número:</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control dato_cliente" id="numero_cuenta" name="numero_cuenta" required>
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    <label class="col-md-3">Código:</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control dato_cliente" id="codigo_banco" name="codigo_banco" required>
                                    </div>
                                </div>
                                <input type="hidden" id="id_banco" value="">
                            </div>
                        </div>
                        </div>
                        <div id="panel_clientes" class="col-md-4">
                        <div class="panel panel-default mi_panel">
                            <div class="panel-heading">
                                <h4 class="panel-title">Datos del Cliente</h4>
                            </div>
                            <div class="panel-body">
                                <div class="form-group col-md-12">
                                    <label class="col-md-3">RUC/CI:</label>
                                    <div class="col-md-9">
                                        <select id="selecciona_cliente" name="selecciona_cliente" class="selectpicker_cliente" data-live-search="true"></select>
                                        <button type="button" class="btn btn-primary glyphicon glyphicon-plus btn-xs" data-id='nuevo1' data-toggle="modal" data-target="#myModal1" title="Agregar Cliente"></button>
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    <label class="col-md-3">Nombre:</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control dato_cliente" id="nombre_cliente" name="nombre_cliente" required>
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    <label class="col-md-3">Dirección:</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control dato_cliente" id="direccion_cliente" name="direccion_cliente" required>
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    <label class="col-md-3">Teléfono:</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control dato_cliente" id="telefono_cliente" name="telefono_cliente" required>
                                    </div>
                                </div>
                                <input type="hidden" id="id_cliente" value="">
                            </div>
                        </div>
                        </div>
                        
                        <div id="panel_desposito" class="col-md-4">
                        <div class="panel panel-default mi_panel">
                            <div class="panel-heading">
                                <h4 class="panel-title">Datos de la Transferencia Recibida</h4>
                            </div>
                            <div class="panel-body">
                                
                                <div class="form-group col-md-6">
                                    <label class="col-md-12">Fecha:</label>
                                    <div class="input-group date col-md-12" id="datetimepicker1">
                                        <input type='text' class="form-control dato_empresa" id='fecha_asiento' name='fecha_asiento' />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="col-md-12">Valor:</label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control dato_cliente valor" id="valor_deposito" name="valor_desposito" required>
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    <label class="col-md-12">Descripción:</label>
                                    <div class="col-md-12">
                                        <textarea id="descripcion_bancaria" name="descripcion_bancaria" class="form-control" rows="2" required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                        </div>
                        <div id="seccion2">
                        <button type="button" id="addRow" class="btn btn-default btn-xs">Agregar Fila</button>
                        <table id="registro_bancario" class="table table-striped" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Cuenta</th>
                                    <th>Nombre Cuenta</th>
                                    <th>Descripción</th>
                                    <th>Debe</th>
                                    <th>Haber</th>
                                </tr>                
                            </thead>
                            <tbody>
                                <tr>
                                    <td><select id="codigo1" name="codigo1" class="selectpicker" data-live-search="true"></select></td>
                                    <td><input type="text" id="nombre_fila_1" name="nombre_fila_1" class="form-control campo_txt"></td>
                                    <td><input type="text" id="descripcion_fila_1" name="descripcion_fila_1" class="form-control campo_txt"></td>
                                    <td><input type="text" id="debe_fila_1" name="debe_fila_1" class="form-control valor" required></td>
                                    <td><input type="text" id="haber_fila_1" name="haber_fila_1" class="form-control valor" required></td>
                                </tr>
                                <tr>
                                    <td><select id="codigo2" name="codigo2" class="selectpicker" data-live-search="true"></select></td>
                                    <td><input type="text" id="nombre_fila_2" name="nombre_fila_2" class="form-control campo_txt"></td>
                                    <td><input type="text" id="descripcion_fila_2" name="descripcion_fila_2" class="form-control campo_txt"></td>
                                    <td><input type="text" id="debe_fila_2" name="debe_fila_2" class="form-control valor"></td>
                                    <td><input type="text" id="haber_fila_2" name="haber_fila_2" class="form-control valor"></td>
                                </tr>
                                <tr>
                                    <td><select id="codigo3" name="codigo3" class="selectpicker" data-live-search="true"></select></td>
                                    <td><input type="text" id="nombre_fila_3" name="nombre_fila_3" class="form-control campo_txt"></td>
                                    <td><input type="text" id="descripcion_fila_3" name="descripcion_fila_3" class="form-control campo_txt"></td>
                                    <td><input type="text" id="debe_fila_3" name="debe_fila_3" class="form-control valor"></td>
                                    <td><input type="text" id="haber_fila_3" name="haber_fila_3" class="form-control valor"></td>
                                </tr>
                                <tr>
                                    <td><select id="codigo4" name="codigo4" class="selectpicker" data-live-search="true"></select></td>
                                    <td><input type="text" id="nombre_fila_4" name="nombre_fila_4" class="form-control campo_txt"></td>
                                    <td><input type="text" id="descripcion_fila_4" name="descripcion_fila_4" class="form-control campo_txt"></td>
                                    <td><input type="text" id="debe_fila_4" name="debe_fila_4" class="form-control valor"></td>
                                    <td><input type="text" id="haber_fila_4" name="haber_fila_4" class="form-control valor"></td>
                                </tr>
                                <tr>
                                    <td><select id="codigo5" name="codigo5" class="selectpicker" data-live-search="true"></select></td>
                                    <td><input type="text" id="nombre_fila_5" name="nombre_fila_5" class="form-control campo_txt"></td>
                                    <td><input type="text" id="descripcion_fila_5" name="descripcion_fila_5" class="form-control campo_txt"></td>
                                    <td><input type="text" id="debe_fila_5" name="debe_fila_5" class="form-control valor"></td>
                                    <td><input type="text" id="haber_fila_5" name="haber_fila_5" class="form-control valor"></td>
                                </tr>
                                <tr>
                                    <td><select id="codigo6" name="codigo6" class="selectpicker" data-live-search="true"></select></td>
                                    <td><input type="text" id="nombre_fila_6" name="nombre_fila_6" class="form-control campo_txt"></td>
                                    <td><input type="text" id="descripcion_fila_6" name="descripcion_fila_6" class="form-control campo_txt"></td>
                                    <td><input type="text" id="debe_fila_6" name="debe_fila_6" class="form-control valor"></td>
                                    <td><input type="text" id="haber_fila_6" name="haber_fila_6" class="form-control valor"></td>
                                </tr>
                                <tr style="display: none;">
                                    <td><select id="codigo7" name="codigo7" class="selectpicker" data-live-search="true"></select></td>
                                    <td><input type="text" id="nombre_fila_7" name="nombre_fila_7" class="form-control campo_txt"></td>
                                    <td><input type="text" id="descripcion_fila_7" name="descripcion_fila_7" class="form-control campo_txt"></td>
                                    <td><input type="text" id="debe_fila_7" name="debe_fila_7" class="form-control valor"></td>
                                    <td><input type="text" id="haber_fila_7" name="haber_fila_7" class="form-control valor"></td>
                                </tr>
                                <tr style="display: none;">
                                    <td><select id="codigo8" name="codigo8" class="selectpicker" data-live-search="true"></select></td>
                                    <td><input type="text" id="nombre_fila_8" name="nombre_fila_8" class="form-control campo_txt"></td>
                                    <td><input type="text" id="descripcion_fila_8" name="descripcion_fila_8" class="form-control campo_txt"></td>
                                    <td><input type="text" id="debe_fila_8" name="debe_fila_8" class="form-control valor"></td>
                                    <td><input type="text" id="haber_fila_8" name="haber_fila_8" class="form-control valor"></td>
                                </tr>
                                <tr style="display: none;">
                                    <td><select id="codigo9" name="codigo9" class="selectpicker" data-live-search="true"></select></td>
                                    <td><input type="text" id="nombre_fila_9" name="nombre_fila_9" class="form-control campo_txt"></td>
                                    <td><input type="text" id="descripcion_fila_9" name="descripcion_fila_9" class="form-control campo_txt"></td>
                                    <td><input type="text" id="debe_fila_9" name="debe_fila_9" class="form-control valor"></td>
                                    <td><input type="text" id="haber_fila_9" name="haber_fila_9" class="form-control valor"></td>
                                </tr>
                                <tr style="display: none;">
                                    <td><select id="codigo10" name="codigo10" class="selectpicker" data-live-search="true"></select></td>
                                    <td><input type="text" id="nombre_fila_10" name="nombre_fila_10" class="form-control campo_txt"></td>
                                    <td><input type="text" id="descripcion_fila_10" name="descripcion_fila_10" class="form-control campo_txt"></td>
                                    <td><input type="text" id="debe_fila_10" name="debe_fila_10" class="form-control valor"></td>
                                    <td><input type="text" id="haber_fila_10" name="haber_fila_10" class="form-control valor"></td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" style="padding-right: 10px !important;border: 1px solid #ccc">Total:</td>
                                    <td><input type="text" id="total_debe" class="form-control valor" disabled></td>
                                    <td><input type="text" id="total_haber" class="form-control valor" disabled></td>
                                </tr>
                            </tfoot>
                        </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn_agregar" disabled="disable">Agregar</button>
                        <button type="button" class="btn btn-primary" id="imprimir">Imprimir</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal" id="btn_cancelar">Cerrar</button>
                    </div>
                    </form>
		</div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
        
    </body>
</html>
