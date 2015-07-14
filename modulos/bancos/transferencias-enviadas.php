<?php
    include('../../conexion.php');
    include('../../redireccionar.php');
    $fechaactual=date("Y-m-d");
$query="SELECT t1.*,t2.*
FROM cont_plan_de_cuentas t1
LEFT JOIN banco t2 ON t2.ban_cod_cuenta_contable = t1.cont_codigo where t1.cont_catogoria='C'";

$result = $link->query($query);
echo 'ok php';
$string_plandecuentas='';
while($data=$result->fetch_assoc())
   $string_plandecuentas.="<option value='$data[cont_id_cuenta]' title='$data[cont_codigo]' data-codigo-cuenta-contable='$data[cont_codigo]' data-nombre='$data[cont_nombre]' data-id='$data[cont_id_cuenta]' >$data[cont_codigo] | $data[cont_nombre]</option>";
?>
<html>
<head>
    <title>Transferencias Enviadas</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    
    <!-- Estilos -->
    <link rel="stylesheet" href="css/bootstrap.css" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/jquery.dataTables.css"/>
    <link rel="stylesheet" href="css/formValidation.css"/>
    <link rel="stylesheet" href="css/bootstrap-select.css"/>
    <link rel="stylesheet" href="css/bootstrap-select.min.css"/>
    <link rel="stylesheet" href="css/fm.scrollator.jquery.css"/>
    <link rel="stylesheet" href="css/jquery-ui.css"/>
    <link rel="stylesheet" href="js/jquery.mCustomScrollbar.css" />
    <link rel="stylesheet" href="css/movimiento_bancario.css" />
    <!-- Scripts-->
    <script src="js/jquery-1.9.1.js"></script>
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/formValidation.js"></script>
    <script type="text/javascript" src="js/language/es_CL.js"></script>
    <script type="text/javascript" src="js/framework/bootstrap.js"></script>
    <script src="js/bootstrap-select.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/bancos.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootbox.min.js"></script>
    <script src="js/alertify.min.js"></script>
    <script src="js/alertify.js"></script>
    <script src="js/fm.scrollator.jquery.js"></script>
    <script src="js/jquery-ui.js"></script>
    <script src="js/dataTables.tableTools.js"></script>
    <script src="js/dataTables.bootstrap.js"></script>
    <script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="js/jquery.maskMoney.js" type="text/javascript"></script>
    <script src="js/transferencias-enviadas.js" type="text/javascript"></script>
    <!--<script src="js/deposito_bancario.js" type="text/javascript"></script>-->

</head>
<body>
    <section class="container-fluid cabecera" style="z-index: 1;">
    <?php require('../../menu_principal.php'); ?>
    </section>
        <section class="container-fluid cabecera_oculta"></section>
        <section class="container contenido_cuentas">
        <h4 class="titulo_ventana">Transferencias Enviadas</h4>
        <div class="area_pantalla">
          <button  title="Agregar Banco" type="button" class="btn btn-primary" data-id='nuevo1' data-toggle="modal" data-target="#myModal" id="modal_agregar"><i class='icon-plus icon-white'></i></button>
       <table  id="example" class="table table-bordered">
            <thead>
                <tr>
                    <th >Trans Env N°</th>
                    <th >Fecha</th>
                    <th>Banco</th>
                    <th >Cod Banco</th>
                    <th ># Cta </th>
                    <th >Tipo</th>
                    <th >Descripci&oacute;n</th>
                    <th>&nbsp;</th>
                </tr>
                </thead>
                <tbody>
<?php
//$sql="SELECT * from banco";
$sql="SELECT b.*,c.*,rec.* from ban_transferencia_enviada rec INNER JOIN banco b ON b.ban_id=rec.tra_env_bancoid
INNER JOIN pag_maestro_proveedor c ON c.ID=rec.tra_env_proveedorid";
$res = $link->query($sql);
while($data=$res->fetch_assoc())
{
    $codigo=$data["tra_env_id"];
       $act_delete = ' <a title="Eliminar" class="" style="color:#337ab7;background-color: transparent;border: none;" href="#" onclick=""><span class="glyphicon glyphicon-trash"></span></a>';
       $act_print = ' <a title="Imprimir" class="" style="color:#337ab7;background-color: transparent;border: none;" href="#" onclick="ver_deposito('.$codigo.')"><span class="glyphicon glyphicon-zoom-in"></span></a>';
     echo"
    <tr id='row_$codigo'>
    <td>$data[tra_env_numtransferencia]</td>
    <td>$data[tra_env_fecha]</td>
    <td>$data[ban_nombre]</td>
    <td>$data[ban_codigo]</td>
    <td>$data[ban_numero_cuenta]</td>
    <td>$data[ban_tipo]</td>
    <td>$data[tra_env_descripcion]</td>
    <td style='white-space:nowrap'>$act_print $act_delete </td>
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
<!---------------------------------------------------- MODAL ------------------------------------------------------>                
       <div class="modal bs-example-modal-lg fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- COMIENZA EL FORM -->
            <input  type="hidden" id="proceso" name="proceso" value="ingresar_deposito" />
            <input  type="hidden" id="filas_diario" name="filas_diario"  />
             <input type="hidden" name="identificador_deposito" id="identificador_deposito" value=""/>
           <form id="NuevoDeposito" name="NuevoDeposito" method="post" class="form-horizontal">
           <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Agregar Transferencia Enviada</h4>
           </div>
           <div class="modal-body">
           <!-- INPUT DEPOSITO BANCARIO Y FECHA-->
             <div class="form-group box_banco " >
             <div class="rowContainer">
                 <label class="col-xs-2 control-label" style="margin-left:-30px;">Trans Rec # </label>
                <div class="col-xs-3">
                    <input data-fv-row=".rowContainer"  type="text" class="form-control input-sm" name="numero_deposito" id="numero_deposito" value="<?php //echo $cod; ?>"  onkeydown="return false" />
                </div>
             </div>
                        
            <div class="rowContainer">
                <label class="col-xs-3 control-label" style="margin-left:-75px;">Fecha</label>
                <div class="col-xs-3">
                <input data-fv-row=".rowContainer" type="text" class="form-control input-sm" name="fecha" id="fecha" value="<?php echo $fechaactual?>" />
            </div>
            </div>
            </div>
             <!-- INPUT NOMBRE BANCO Y CODIGO BANCO-->
            <div class="form-group box_banco">
            <div class="rowContainer">
            <label class="col-xs-1 control-label">Banco</label>
            <div class="col-xs-3">
             <select data-fv-row=".rowContainer" name="nombre_banco" id="nombre_banco" class="selectpicker form-control input-sm" data-live-search="true" data-width="200px">
            <option value=" " >Seleccione una Opci&oacute;n</option>
            <?php
            /** LLENA EL SELECT CON DATOS DE LA TABLA PLAN DE CUENTAS */ 
            $query="SELECT b.*,c.* from banco b INNER JOIN cont_plan_de_cuentas c ON c.cont_codigo=b.ban_cod_cuenta_contable";
            $result = $link->query($query);
            while($data=$result->fetch_assoc())
                echo"<option value='$data[ban_id]' data-codigo-banco='$data[ban_codigo]' data-tipo-cuenta='$data[ban_tipo]' data-numero-cuenta='$data[ban_numero_cuenta]' data-codigo-cuenta-contable='$data[ban_cod_cuenta_contable]' >$data[ban_codigo] | $data[ban_nombre] | Cta. $data[ban_tipo] | #$data[ban_numero_cuenta] </option>";
            ?>
          </select>
           </div>
          </div>
         
          <div class="rowContainer">    
          <label class="col-xs-2 control-label">Valor</label>
          <div class="col-xs-2">
          <input  onkeyup="valor_campo(this.value)" data-fv-row=".rowContainer"   type="text"    class="form-control input-sm" name="valor" id="valor" />
          </div>
          <input type="hidden" id="hidden_valor" name="hidden_valor"  />
          </div>
          </div>
          <!-- INPUT NOMBRE CLIENTE Y CODIGO CLIENTE -->
          <div class="form-group box_banco" >
          <div class="rowContainer">  
          <label class="col-xs-1 control-label">Proveedor</label>
          <div class="col-xs-3">
            <select data-fv-row=".rowContainer" name="nombre_cliente" id="nombre_cliente" class="selectpicker form-control input-sm" data-live-search="true" data-width="200px">
                 <option value=" " >Seleccione una Opci&oacute;n</option>
                     <?php
                    /** LLENA EL SELECT CON DATOS DE LA TABLA PLAN DE CUENTAS */ 
                    $query2 = "select * from pag_maestro_proveedor";
                    $result2 = $link->query($query2);
                    echo 'ok php';
                    while($data=$result2->fetch_assoc())
                         echo"<option value='$data[ID]' data-id='$data[ID]' data-ruc='$data[RUC]'>$data[CODIGO] | $data[NOMBRE]</option>";
                     ?>     
             </select>
          </div>
          </div>
          </div>
          <!-- INPUT DESCRIPCION-->
          <div class="form-group box_banco" style="margin-bottom: 4px;">
          <label class="col-xs-2 control-label" >Descripci&oacute;n</label>
          <div class="col-xs-10">
             <input onkeyup="document.getElementById('descripcion_1').value = this.value;" value=""  type="text" class="form-control input-sm" name="dep_descripcion" id="dep_descripcion" />
          </div>
          </div>
          <button style="padding: 2px;" type="button" class="btn btn-success" id="addRow" name="addRow" value="Agregar Fila"> <i class='icon-plus icon-white'></i></button>
          <button style="padding: 2px;" type="button"  class="btn btn btn-danger " id="test" name="test" value="Borrar"> <i class='icon-trash icon-white'></i></button>
          <!-- TABLA LIBRO DIARIO-->                 
        <table id="banco_diario" class="table table-striped" cellspacing="0">
        <thead>
        <tr>
            <th style="font-size: 14px;padding-top:0px;padding-bottom: 0px;text-align:center;">Cuenta</th>
            <th style="font-size: 14px;padding-top:0px;padding-bottom: 0px;text-align:center;">Nombre Cta</th>
            <th style="font-size: 14px;padding-top:0px;padding-bottom: 0px;text-align:center;">Descripci&oacute;n</th>
            <th style="font-size: 14px;padding-top:0px;padding-bottom: 0px;text-align:center;">Debe</th>
            <th style="font-size: 14px;padding-top:0px;padding-bottom: 0px;text-align:center;">Haber</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th colspan="3" style="font-size: 15px;padding-top:0px;padding-bottom: 0px;text-align:right;">Total</th>
            <th style="font-size: 15px;padding-top:0px;padding-bottom: 0px;text-align:center;"><label id="totaldebe"></label></th>
            <th style="font-size: 15px;padding-top:0px;padding-bottom: 0px;text-align:center;"><label id="totalhaber"></label></th>
        </tr>
        <tr>
           <th  colspan="3" style="font-size: 15px;padding-top:0px;padding-bottom: 0px;text-align:right">Diferencia</th>
           <th colspan="2"  style="font-size: 15px;padding-top:0px;padding-bottom: 0px;"><label id="diferencia" style="color: black;"></label>
           <div class="form-group box_banco">
           <input id="input_diferencia" name="input_diferencia" type="hidden" />
           </div>
           </th>
        </tr>
        </tfoot>
        <tbody id="diario_body">
        <!-- FILA N DE LA TABLA DE LIBRO DIARIO-->
        <?php for($i=1;$i<=40;$i++){ 
        if($i>6)
           $texto="style='display: none;'";
        else
            $texto='';
        ?>
        <tr id="row_<?php echo$i; ?>" <?php echo $texto; ?>>
         <td >
         <div class="form-group box_banco" >
                   <div class="rowContainer">  
                   <div class="col-xs-12">
                      <select  data-fv-row=".rowContainer" name="nombre_cuenta_<?php echo $i;?>" id="nombre_cuenta_<?php echo $i;?>" class="selectpicker form-control input-sm selectpicker_diario" data-live-search="true">
                        <option value="">Código</option>
                        <?php
                         echo $string_plandecuentas;
                         ?>           
                      </select>
                      <input type="hidden" name="codigo_cta_contable_<?php echo $i;?>" id="codigo_cuenta_contable_<?php echo $i;?>" value="" />
                     </div>
                    </div>
         </div>
         </td>
         <td style="  padding-left: 1px;padding-right:1px;">
            <input style="width: 100%;" onkeydown="return false" value=""  type="text" class="form-control input-sm" name="cod_cta_contable_<?php echo $i;?>" id="cod_cta_contable_<?php echo $i;?>" />
         </td>
         <td style="  padding-left: 1px;padding-right:1px; ">
            <div class="form-group box_banco" style="margin-bottom: 0px;width:100%;margin-left: 0px;margin-right:0px;">
                <input style="width: 100%;" value=""  type="text" class="form-control input-sm" name="descripcion_<?php echo $i;?>" id="descripcion_<?php echo $i;?>" />
            </div>
         </td>
         <td style="  padding-left: 1px;padding-right:1px;">
            <div class="form-group box_banco" style="margin-bottom: 0px;width:100%;margin-left: 0px;margin-right:0px;">
               <input style="width: 100%;" onchange='totalsuma(this)' onkeyup="disableFieldHaber(this)" value=""  type="text" class="form-control input-sm debe" name="debe_<?php echo $i;?>" id="debe_<?php echo $i;?>" />
                <input type="hidden" name="hidden_debe_<?php echo $i;?>" id="hidden_debe_<?php echo $i;?>" />                    
            </div>
         </td>
         <td style="  padding-left: 1px;padding-right:1px;">
            <div class="form-group box_banco" style="margin-bottom: 0px;width:100%;margin-left: 0px;margin-right:0px;">
                <input style="width: 100%;" onchange='totalsuma(this)' onkeyup="disableFieldDebe(this)" value="" type="text" class="form-control input-sm" name="haber_<?php echo $i;?>" id="haber_<?php echo $i;?>" />
                <input type="hidden" name="hidden_haber_<?php echo $i;?>" id="hidden_haber_<?php echo $i;?>" />             
            </div>
         </td>
       </tr>    
       <?php }?>
       </tbody>
       </table>
      </div>
        <!-- MODAL FOOTER-->
        <div class="modal-footer" style="margin-top: 10px;">
         <div class="form-group">
            <div class="col-xs-5 col-xs-offset-3">
                <input type="submit" class="btn btn btn-primary" id="btn_agregar_nuevo" name="btn_agregar_nuevo" value="Guardar" />
                <button type="button" class="btn btn-primary" id="imprimir_deposito" name="imprimir_deposito" style="display: none;">Imprimir</button>
                <button type="button" class="btn btn-default" data-dismiss="modal" id="btn_cancelar">Cancelar</button>
            </div>
        </div>
        </div>
        </form>
        <!--<input type="text"  id="btn_prueba" name="btn_prueba" value="Prueba" />--> 
        </div>
        </div>
        </div>
</section>
</body>
</html>
<?php   //include('js/deposito_bancario2.js'); ?>
