<?php 
	include('../../conexion.php');
	// Realizar una consulta MySQL
	include('../../redireccionar.php');
?>
<html>
<head>
	<title>Consulta Libro Bancos</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<!-- Estilos -->
     <link href="http://netdna.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="css/bootstrap.css" />
	<link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/jquery.dataTables.css"/>
    <link rel="stylesheet" href="css/formValidation.css"/>
    <link rel="stylesheet" href="css/bootstrap-select.css"/>
    <link rel="stylesheet" href="css/bootstrap-select.min.css"/>
    <link rel="stylesheet" type="text/css" media="all" href="css/daterangepicker-bs3.css" />
	<!-- Scripts-->
    <script src="js/jquery-1.9.1.js"></script>
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/formValidation.js"></script>
    <script type="text/javascript" src="js/framework/bootstrap.js"></script>
	<script src="js/bootstrap-select.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/bancos.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootbox.min.js"></script>
    <script src="js/dataTables.tableTools.js"></script>
    <script src="js/dataTables.bootstrap.js"></script>
    <script type="text/javascript" src="js/moment.js"></script>
    <script type="text/javascript" src="js/daterangepicker.js"></script>
    <script type="text/javascript" src="js/consulta_librobancos.js"></script>
<style type="text/css">
#loginForm .selectContainer .form-control-feedback {
    right: -15px;
}
.area_pantalla{
    overflow-x:hidden;
}
div.DTTT { margin-bottom: 0.5em; float: right; }
div.dataTables_wrapper { clear: both; }
</style>
</head>
<body>
	<section class="container-fluid cabecera" style="z-index: 1;">
	<?php require('../../menu_principal.php'); ?>
	</section>
		<section class="container-fluid cabecera_oculta"></section>
		<section class="container contenido_cuentas">
<h4 class="titulo_ventana" style="text-align: center;">Consulta Libro Bancos</h4>
        <div class="area_pantalla">
       
  
  
   <input  type="hidden" id="proceso" name="proceso" value="consulta_librobancos" />
  <!-- COMIENZA EL FORM -->
       <form id="consulta_librobancos" name="consulta_librobancos" method="post" class="form-horizontal" >
        <!-- SELECT NOMBRE BANCO-->
       <div class="form-group">
            <div class="rowContainer">
            <label class="col-xs-1 control-label">Banco</label>
            <div class="col-xs-2 selectContainer">
            <select name="nombre_banco" id="nombre_banco" class="selectpicker form-control" data-live-search="true" data-width="200px">
            <option value=" " >Seleccione una Opci&oacute;n</option>
<?php
/** LLENA EL SELECT CON DATOS DE LA TABLA PLAN DE CUENTAS */ 
$query="SELECT * from banco";

$result = $link->query($query);
echo 'ok php';
while($data=$result->fetch_assoc())
{
echo"<option value='$data[ban_id]' data-tipo='$data[ban_tipo]' data-numero-cuenta='$data[ban_numero_cuenta]' data-nombre='$data[ban_nombre]'>$data[ban_codigo] | $data[ban_nombre] | Cta. $data[ban_tipo] | #$data[ban_numero_cuenta]</option>";
}
?>         </select>
           </div>
       </div>
       
      <div class="rowContainer">
            <label class="col-xs-2 control-label">Tipo</label>
            <div class="col-xs-2">
                <input onkeydown="return false;" type="text" class="form-control" name="tipo_cuenta" id="tipo_cuenta" />
            </div>
        </div>
       <div class="rowContainer">
            <label class="col-xs-2 control-label"># de Cuenta</label>
            <div class="col-xs-2">
                <input onkeydown="return false;" type="text" class="form-control" name="numero_cuenta" id="numero_cuenta" />
            </div>
        </div>
       </div>
         <div class="form-group">
                  <label class="col-xs-2 col-xs-offset-2   control-label">Rango Fechas:</label>   
                  <div class="col-xs-2">
                   <div class="input-prepend input-group">
                       <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span><input onkeydown="return false;" type="text" style="width: 200px" name="rangofecha" id="rangofecha" class="form-control" value="" /> 
                   </div>
                  </div>
         </div>
         <div class="form-group">
        <div class="col-xs-2 col-xs-offset-5">
            <input type="submit" class="btn btn btn-primary" id="btn_aceptar" name="btn_aceptar" value="Aceptar" />
           
        </div>
     </div>
       </form>
        </div>
</section>
</body>
</html>
