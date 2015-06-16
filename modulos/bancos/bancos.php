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
	<!-- Estilos -->
	<link rel="stylesheet" href="css/bootstrap.css" />
	<link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/jquery.dataTables.css"/>
    <link rel="stylesheet" href="css/formValidation.css"/>
    <link rel="stylesheet" href="css/bootstrap-select.css"/>
    <link rel="stylesheet" href="css/bootstrap-select.min.css"/>
  
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
<style type="text/css">
#loginForm .selectContainer .form-control-feedback {
    right: -15px;
}
.area_pantalla{
    overflow-x:hidden;
}
div.DTTT { margin-bottom: 0.5em; float: right; }
div.dataTables_wrapper { clear: both; }
#example th {
  font-size: 13px !important;
  border: 1px solid #ccc !important;
  border-radius: 4px;
    padding: 5px 10px;
  border-bottom: 1px solid #111;
}
#example td {
  font-size: 13px !important;
  border: 1px solid #ccc !important;
  border-radius: 4px;
  vertical-align: middle;

    padding: 3px 8px;
  border-bottom: 1px solid #111;
}
#example_length{
    display:none;
}
table.dataTable.no-footer {
   border-bottom: none; 
}
.table-bordered {
   border:none; 
}
#example_filter{
  float: left;
  text-align: left;
  margin-left: -25%;
  margin-top: -40px;
}
.modal-footer{
    padding-bottom:0px;
}
.modal-body{
    padding-bottom:0px;
}
</style>
</head>
<body>
	<section class="container-fluid cabecera" style="z-index: 1;">
	<?php require('../../menu_principal.php'); ?>
	</section>
		<section class="container-fluid cabecera_oculta"></section>
		<section class="container contenido_cuentas">
<?php 
/** Para obtener el siguiente Código de Banco */
$query2 = "select * from banco ORDER BY ban_id DESC LIMIT 1";
$result2 = $link->query($query2);  
while($data=$result2->fetch_assoc()){
        $codigo_ultimo_banco=$data['ban_codigo'];
        $num= explode("b", $codigo_ultimo_banco);   
        $cod=$num[1]+1;
 
}
$num_results = $result2->num_rows; 
if ($num_results <1){ 
        $cod=1;
}
?>	
       	<h4 class="titulo_ventana">Bancos</h4>
        <div class="area_pantalla">
			<button  title="Agregar Banco" type="button" class="btn btn-primary" data-cod-banco='<?php echo "b$cod" ?>' data-id='nuevo1' data-toggle="modal" id="modal_agregar"><i class='icon-plus icon-white'></i></button>
        <table  id="example" class="table table-bordered">
			<thead>
            	<tr>
		
                    <th >C&oacute;digo Banco</th>
					<th >Nombre de Banco</th>
                    <th># Cuenta</th>
                    <th >Tipo</th>
                    <th >#Cta Contable</th>
           			<th>Acción</th>
				</tr>
                </thead>
                <tbody>
<?php
/** ********************************** TABLA PRINCIPAL DE BANCOS           **************************************/
$sql="SELECT b.*,c.* from banco b INNER JOIN cont_plan_de_cuentas c ON c.cont_codigo=b.ban_cod_cuenta_contable";
$res = $link->query($sql);
while($data=$res->fetch_assoc())
{
    $codigo=$data["ban_cod_cuenta_contable"];
    $act_delete = ' <a title="Eliminar" class="" style="color:#337ab7;background-color: transparent;border: none;" href="#" onclick=""><span class="glyphicon glyphicon-trash"></span></a>';
    $act_update = " <button style='color:#337ab7;background-color: transparent;border: none;' class='btn_update' data-cod='$data[ban_codigo]'data-nom='$data[ban_nombre]'"
            . "data-numcuenta='$data[ban_numero_cuenta]' data-tipo='$data[ban_tipo]' data-nombrebanco='$data[cont_id_cuenta]'"
            . "data-codcuecontable='$data[ban_cod_cuenta_contable]'><span class='glyphicon glyphicon-pencil'></span></button>";
    echo"
    <tr id='row_$codigo'>
    
    <td>$data[ban_codigo]</td>
    <td>$data[ban_nombre]</td>
    <td>$data[ban_numero_cuenta]</td>
    <td>$data[ban_tipo]</td>
    <td>$data[ban_cod_cuenta_contable]</td>
        <td style='white-space:nowrap'>$act_update $act_delete</td>
    </tr>
    ";
 }
 echo"</tbody>
        </table>
     ";
?>	
                </tbody>
		</table>
		</div>
       <!-- COMIENZA EL FORM -->
       <form id="NuevoBanco" name="NuevoBanco" method="post" class="form-horizontal" style="display: none;">
       <input  type="hidden" id="proceso" name="proceso" value="ingresar_banco" />
             
       <!-- INPUT CODIGO BANCO-->
       <div class="form-group box_banco">
            <label class="col-xs-3 control-label">Codigo</label>
            <div class="col-xs-5">
                <input value="<?php echo "b$cod" ?>" onkeydown="return false;" type="text" class="form-control" name="codigo_banco" id="codigo_banco" />
            </div>
        </div>
       <!-- INPUT NOMBRE BANCO-->
       <div class="form-group box_banco">
            <label class="col-xs-3 control-label">Nombre</label>
            <div class="col-xs-5">
                <input type="text" class="form-control" name="n_banco" id="n_banco" />
            </div>
        </div>
       <!-- INPUT # DE CUENTA-->
        <div class="form-group box_banco">
            <label class="col-xs-3 control-label"># de Cuenta</label>
            <div class="col-xs-5">
                <input type="text" class="form-control" name="numero_cuenta" id="numero_cuenta" />
            </div>
        </div>
        <!-- RADIO TIPO CUENTA-->
        <div class="form-group box_banco">
            <label class="col-xs-3 control-label">Tipo</label>
            <div class="col-xs-5">
                <div class="radio">
                    <label>
                        <input type="radio"  name="tipo_cuenta" id="ahorro" value="ahorro" /> Ahorro
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio"   name="tipo_cuenta" id="corriente" value="corriente" /> Corriente
                    </label>
                </div>
               
            </div>
        </div>
       <!-- SELECT NOMBRE BANCO-->
       <div class="form-group">
            <label class="col-xs-3 control-label">* Nombre Banco</label>
            <div class="col-xs-5 selectContainer">
            <select name="nombre_banco" id="nombre_banco" class="selectpicker form-control" data-live-search="true" data-width="350px">
            <option value=" " >Seleccione una Opci&oacute;n</option>
<?php
/** LLENA EL SELECT CON DATOS DE LA TABLA PLAN DE CUENTAS */ 
$query="SELECT t1.*,t2.*
FROM cont_plan_de_cuentas t1
LEFT JOIN banco t2 ON t2.ban_cod_cuenta_contable = t1.cont_codigo
WHERE t2.ban_cod_cuenta_contable IS NULL and t1.cont_codigo LIKE '1.1.01.02.%'";

$result = $link->query($query);
echo 'ok php';
while($data=$result->fetch_assoc())
{
echo"<option value='$data[cont_id_cuenta]' data-codigo-cuenta-contable='$data[cont_codigo]' >$data[cont_nombre]</option>";
}
?>         </select>
           </div>
       </div>
        <!-- INPUT #CODIGO DE CUENTA CONTABLE-->
         <div class="form-group box_banco">
            <label class="col-xs-3 control-label">#Cod Cta Contable</label>
            <div class="col-xs-5">
                <input onkeydown="return false;" type="text" class="form-control" name="cod_cuenta_contable" id="cod_cuenta_contable" />
            </div>
        </div>
        <!-- MODAL FOOTER-->
        <div class="modal-footer">
         <div class="form-group">
            <div class="col-xs-5 col-xs-offset-3">
                <input type="submit" class="btn btn btn-primary" id="btn_agregar_nuevo" name="btn_agregar_nuevo" value="Guardar" />
                <button type="button" class="btn btn-default" data-dismiss="modal" id="btn_cancelar">Cancelar</button>
            </div>
        </div>
        </div>

   </form>
      

    <!-- COMIENZA EL FORM DE EDITAR BANCO-->
    <form id="EditarBanco" name="EditarBanco" method="post" class="form-horizontal" style="display: none;">
    <input  type="hidden" id="proceso" name="proceso" value="editar_banco" />
    
   <!-- INPUT CODIGO BANCO-->
   <div class="form-group box_banco">
        <label class="col-xs-3 control-label">Codigo</label>
        <div class="col-xs-5">
            <input value="<?php echo "b$cod" ?>" onkeydown="return false" type="text" class="form-control" name="codigo_banco" id="codigo_banco" />
        </div>
    </div>
   <!-- INPUT NOMBRE BANCO-->
   <div class="form-group box_banco">
        <label class="col-xs-3 control-label">Nombre</label>
        <div class="col-xs-5">
            <input type="text" class="form-control" name="editar_n_banco" id="editar_n_banco" />
        </div>
    </div>
   <!-- INPUT # DE CUENTA-->
    <div class="form-group box_banco">
        <label class="col-xs-3 control-label"># de Cuenta</label>
        <div class="col-xs-5">
            <input type="text" class="form-control" name="editar_numero_cuenta" id="editar_numero_cuenta" />
        </div>
    </div>
    <!-- RADIO TIPO CUENTA-->
    <div class="form-group box_banco">
        <label class="col-xs-3 control-label">Tipo</label>
        <div class="col-xs-5">
            <div class="radio">
                <label>
                    <input onclick="editarahorro();" type="radio"  name="editar_tipo_cuenta" id="editar_ahorro" value="ahorro" /> Ahorro
                </label>
            </div>
            <div class="radio">
                <label>
                    <input onclick="editarcorriente();" type="radio"   name="editar_tipo_cuenta" id="editar_corriente" value="corriente" /> Corriente
                </label>
            </div>
           
        </div>
    </div>
    <!-- SELECT NOMBRE BANCO-->
    <div class="form-group">
        <label class="col-xs-3 control-label">* Nombre Banco</label>
        <div class="col-xs-5 selectContainer">
        <select name="editar_nombre_banco" id="editar_nombre_banco" class="selectpicker form-control" data-live-search="true" data-width="350px">
        <option value=" " >Seleccione una Opci&oacute;n</option>
        
<?php

/** LLENA EL SELECT CON DATOS DE LA TABLA PLAN DE CUENTAS */ 

/** CHEQUEAR ESTE QUERY LUEGO PARA QUE NO EDITE SI YA SE ESTA USANDO ESA CUENTA EN ALGUN DEPOSITO O EGRESO*/
$query3="SELECT t1.*,t2.*
FROM cont_plan_de_cuentas t1
LEFT JOIN banco t2 ON t2.ban_cod_cuenta_contable = t1.cont_codigo
WHERE t2.ban_cod_cuenta_contable IS NULL and t1.cont_codigo LIKE '1.1.01.02.%'";

$result3 = $link->query($query3);
echo 'ok php';

while($data=$result3->fetch_assoc())
{
echo"<option value='$data[cont_id_cuenta]' data-codigo-cuenta-contable='$data[cont_codigo]' >$data[cont_nombre]</option>";
}

?>      </select>
        </div>
   </div>
    <!-- INPUT #CODIGO DE CUENTA CONTABLE-->
    <div class="form-group box_banco">
        <label class="col-xs-3 control-label">#Cod Cta Contable</label>
        <div class="col-xs-5">
            <input onkeydown="return false" type="text" class="form-control" name="editar_cod_cuenta_contable" id="editar_cod_cuenta_contable" />
        </div>
    </div>
    <!-- MODAL FOOTER-->
    <div class="modal-footer">
     <div class="form-group">
        <div class="col-xs-5 col-xs-offset-3">
            <input type="submit" class="btn btn btn-primary" id="btn_editar_banco" name="btn_editar_banco" value="Editar" />
            <button type="button" class="btn btn-default" data-dismiss="modal" id="btn_cancelar">Cancelar</button>
        </div>
     </div>
    </div>
    </form>

    <!-- MENSAJE DE EXITO -->
    <div class="form-group" id="estado_transaccion" style="display: none;text-align:center">
          <button type="button" class="btn btn-primary" data-dismiss="modal"  >OK</button>
    </div>
                    

</section>
</body>
</html>
<script type="text/javascript">
$(function() {
     $('#nombre_banco').on('change', function(){
          selectedOption = $('option:selected', this);
          $('input[name=cod_cuenta_contable]').val( selectedOption.data('codigo-cuenta-contable') );
          if(selectedOption=='')
            $('input[name=codigo_banco]').val('');
     });
     
     $('#editar_nombre_banco').on('change', function(){
          selectedOption = $('option:selected', this);
            $('input[name=editar_cod_cuenta_contable]').val( selectedOption.data('codigo-cuenta-contable') );
     });
});
function editarahorro(){
      $('input[name=editar_tipo_cuenta]').val('ahorro');
}
function editarcorriente(){
      $('input[name=editar_tipo_cuenta]').val('corriente');
}
</script> 
<script>
/** VALIDA EL FORMULARIO*/
$(document).ready(function() {
    /******************** REGLAS PARA VALIDAR EL FORMULARIO DE NUEVO BANCO*********************/
    $('#NuevoBanco')
    .find('[name="nombre_banco"]')
            .selectpicker()
            .change(function(e) {
                // revalidate the language when it is changed
                $('#NuevoBanco').formValidation('revalidateField', 'nombre_banco');
                  $('#NuevoBanco').formValidation('revalidateField', 'numero_cuenta');
                    $('#NuevoBanco').formValidation('revalidateField', 'tipo_cuenta');
            })
            .end()
    
    .formValidation({
        framework: 'bootstrap',
         excluded: ':disabled',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            n_banco: {
                validators: {
                    notEmpty: {
                        message: 'Ingrese el Nombre del Banco'
                    }
                }
            },
            numero_cuenta: {
                validators: {
                    notEmpty: {
                        message: 'Ingrese el # de Cuenta'
                    }
                }
            },
            tipo_cuenta: {
                validators: {
                    notEmpty: {
                        message: 'Ingrese el Tipo de Cuenta'
                    }
                }
            },
            
             nombre_banco: {
                validators: {
                    notEmpty: {
                        message: 'Ingrese el Nombre'
                    }
                }
            }
        }
    })
    /************** CUANDO ES EXITOSO EL INGRESO DE LOS CAMPOS EN NUEVO BANCO *********/
        .on('success.form.fv', function(e) {
            // Save the form data via an Ajax request
            e.preventDefault();
              // The url and method might be different in your application
            $.ajax({
                url: 'procesar_bancos.php',
                method: 'POST',
                data:  $('#NuevoBanco').serialize()
            }).success(function(data) {

                console.log(data);
            $(".modal-dialog").addClass('hide');
            // Show the dialog
            bootbox
                .dialog({
                    title: 'Banco Ingresado exitosamente!',
                    message: $('#estado_transaccion'),
                    show: false // We will show it manually later
                })
                .on('shown.bs.modal', function() {
                    $('#estado_transaccion')
                        .show()                             // Show the login form
                        //.formValidation('resetForm'); // Reset form
                })
                .on('hide.bs.modal', function(e) {
                    // Bootbox will remove the modal (including the body which contains the login form)
                    // after hiding the modal
                    // Therefor, we need to backup the form
                   $('#estado_transaccion').hide().appendTo('body');
                    location.reload();
                })
                .modal('show');
            })
        });
        
        /************************ ABRE EL MODAL DE NUEVO BANCO*****************/
        $('#modal_agregar').on('click', function() {
              // Show the dialog
            bootbox
                .dialog({
                    title: 'Ingresar Banco',
                    message: $('#NuevoBanco'),
                    show: false // We will show it manually later
                })
                .on('shown.bs.modal', function() {
                    $('#NuevoBanco')
                        .show()                             // Show the login form
                        .formValidation('resetForm'); // Reset form
                })
                .on('hide.bs.modal', function(e) {
                    // Bootbox will remove the modal (including the body which contains the login form)
                    // after hiding the modal
                    // Therefor, we need to backup the form
                   $('#NuevoBanco').hide().appendTo('body');
                   // location.reload();
                })
                .modal('show');
        });
        
  /******************************************************************************************** */      
  /** REGLAS PARA VALIDAR EL MODAL DE EDICION DE BANCP
  */    
    $('#EditarBanco')
    .formValidation({
        framework: 'bootstrap',
         excluded: ':disabled',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            editar_n_banco: {
                validators: {
                    notEmpty: {
                        message: 'Ingrese el Nombre del Banco'
                    }
                }
            },
            editar_numero_cuenta: {
                validators: {
                    notEmpty: {
                        message: 'Ingrese el # de Cuenta'
                    }
                }
            },
            editar_tipo_cuenta: {
                validators: {
                    notEmpty: {
                        message: 'Ingrese el Tipo de Cuenta'
                    }
                }
            },
            
             editar_nombre_banco: {
                validators: {
                    notEmpty: {
                        message: 'Ingrese el Nombre'
                    }
                }
            }
        }
    })
 
    /************** CUANDO ES EXITOSO LA EDICION DE LOS CAMPOS EN EDICION BANCO *********/
            .on('success.form.fv', function(e) {
            // Save the form data via an Ajax request
            e.preventDefault();

              // The url and method might be different in your application
            $.ajax({
                url: 'procesar_bancos.php',
                method: 'POST',
                data:  $('#EditarBanco').serialize()
            }).success(function(data) {
                console.log(data);
                
            $(".modal-dialog").addClass('hide');
            // Show the dialog
            bootbox
                .dialog({
                    title: 'Banco Editado exitosamente!',
                    message: $('#estado_transaccion'),
                    show: false // We will show it manually later
                })
                .on('shown.bs.modal', function() {
                    $('#estado_transaccion')
                        .show()                             // Show the login form
                        //.formValidation('resetForm'); // Reset form
                })
                .on('hide.bs.modal', function(e) {
                    // Bootbox will remove the modal (including the body which contains the login form)
                    // after hiding the modal
                    // Therefor, we need to backup the form
                   $('#estado_transaccion').hide().appendTo('body');
                    location.reload();
                })
                .modal('show');
            })
        });
    
    /********************** ABRE EL MODAL DE EDICION DE BANCOS *****************************/
        
        $('.btn_update').on('click', function() {
        // Se obtiene los datos via data-atributo que se encuentran el boton de cada fila de la tabla bancos
        var cod_banco = $(this).attr('data-cod');
        var nom_banco = $(this).attr('data-nom');
        var num_cuenta = $(this).attr('data-numcuenta');
        var tipo = $(this).attr('data-tipo');
        var nombrebanco = $(this).attr('data-nombrebanco');
        var cod_cue_cont = $(this).attr('data-codcuecontable');
      
       var opt = document.createElement('option');
       var  select = document.getElementById('editar_nombre_banco');
       opt.value = nombrebanco;
       opt.innerHTML = nom_banco;
       select.appendChild(opt);
       $('#EditarBanco')
         
        .find('[name="codigo_banco"]').val(cod_banco).end()
        .find('[name="editar_n_banco"]').val(nom_banco).end()
        .find('[name="editar_numero_cuenta"]').val(num_cuenta).end()
        .find('[name="editar_tipo_cuenta"]').val(tipo).end()
        .find('[name="editar_cod_cuenta_contable"]').val(cod_cue_cont).end()
        $('select[name=editar_nombre_banco]').val(nombrebanco).end()
        $('select[name=editar_nombre_banco]').selectpicker('refresh');
        if(tipo==='ahorro')
            $('#editar_ahorro').prop('checked',true);
        if(tipo==='corriente')
            $('#editar_corriente').prop('checked',true);
     
            // Show the dialog
            bootbox
                .dialog({
                    title: 'Editar Banco',
                    message: $('#EditarBanco'),
                    show: false // We will show it manually later
                })
                .on('shown.bs.modal', function() {
                    $('#EditarBanco')
                        .show()                             // Show the login form
                        .formValidation('resetForm'); // Reset form
                })
                .on('hide.bs.modal', function(e) {
                    // Bootbox will remove the modal (including the body which contains the login form)
                    // after hiding the modal
                    // Therefor, we need to backup the form
                    $('#EditarBanco').hide().appendTo('body');
                })
                .modal('show');
        });
});
</script>
<script>
/** DATATABLE SCRIPT*/
$(document).ready(function() {
  var table=  $('#example').dataTable({
        "scrollCollapse": true,
        "paging":         true,
        "oLanguage": {
			"sLengthMenu": "_MENU_ registros por página",
            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty":  "Mostrando registros del 0 al 0 de un total de 0 registros",
"sInfoFiltered": "(filtrado de un total de _TOTAL_ registros)",
            "sSearch":         "Buscar:",
            "oPaginate": {
        
        "sNext":     "Siguiente",
        "sPrevious": "Anterior"
    }
			},
            "aoColumns": [
                    { sWidth: '15%' },
                    { sWidth: '29%' },
                    { sWidth: '15%' },
                    { sWidth: '15%' },
                    { sWidth: '20%' },
                    { sWidth: '6%' }]
    });
    /******************* Para imprimir en PDF Y EXCEL********************************/
    var tt = new $.fn.dataTable.TableTools( table );
    $( tt.fnContainer() ).insertBefore('div.dataTables_wrapper');
});
</script>
