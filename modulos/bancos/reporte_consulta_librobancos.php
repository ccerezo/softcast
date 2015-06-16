<?php
    include('../../conexion.php');
    include('../../redireccionar.php');
    $id = $_GET['id'];
    $fechainicio = $_GET['inicio'];
    $fechafin = $_GET['fin'];

$query_dep_anteriores = "SELECT ban . * , dep . * , cli . *FROM (banco ban, ban_deposito_bancario dep, cliente cli)WHERE ban.ban_id =  '$id'
AND dep.dep_fecha
BETWEEN DATE(  '2010-01-01' ) 
AND DATE_ADD('$fechainicio', INTERVAL -1 DAY)
AND ban.ban_id = dep.dep_bancoid
AND dep.dep_clienteid=cli.id ORDER BY dep.dep_fecha";

    $result_dep_ante = $link->query($query_dep_anteriores);
    $num_registros_dep_ante = $result_dep_ante->num_rows;
    
    
    
$query_egre_anteriores = "SELECT ban . * , egre . * , pro . *FROM (banco ban, ban_egreso_bancario egre, pag_maestro_proveedor pro)WHERE ban.ban_id =  '$id'
AND egre.egre_fecha
BETWEEN DATE(  '2010-01-01' ) 
AND DATE_ADD('$fechainicio', INTERVAL -1 DAY)
AND ban.ban_id = egre.egre_bancoid
And egre.egre_proveedorid=pro.ID";

    $result_egre_ante = $link->query($query_egre_anteriores);
    $num_registros_egre_ante = $result_egre_ante->num_rows;
   
if($num_registros_dep_ante > 0 || $num_registros_egre_ante > 0){
    $sum_ante[0]=0;
    $k=1;
    while($row = $result_dep_ante->fetch_assoc()){
            
       $sum_ante[$k]=$sum_ante[$k-1]+$row['dep_valor'];
       $k++;
    }
    $tamano_dep= count($sum_ante);
    $rest_ante[0]=$sum_ante[$tamano_dep-1];
    $j=1;
    while($row = $result_egre_ante->fetch_assoc()){
        $rest_ante[$j]=$rest_ante[$j-1]-$row['egre_valor'];
        $j++;
    }
   $saldo=number_format($rest_ante[count($rest_ante)-1],2);
}else{
    $saldo=number_format(0,2);
} 
   
$query = "SELECT ban . * , dep . * , cli . *FROM (banco ban, ban_deposito_bancario dep, cliente cli)WHERE ban.ban_id =  '$id'
AND dep.dep_fecha
BETWEEN DATE(  '$fechainicio' ) 
AND DATE(  '$fechafin' ) 
AND ban.ban_id = dep.dep_bancoid
AND dep.dep_clienteid=cli.id ORDER BY dep.dep_fecha";

    $result = $link->query($query);
    $num_registros = $result->num_rows;
    
$query2 = "SELECT ban . * , egre . * , pro . *FROM (banco ban, ban_egreso_bancario egre, pag_maestro_proveedor pro)WHERE ban.ban_id =  '$id'
AND egre.egre_fecha
BETWEEN DATE(  '$fechainicio' ) 
AND DATE(  '$fechafin' ) 
AND ban.ban_id = egre.egre_bancoid
And egre.egre_proveedorid=pro.ID";

    $result2 = $link->query($query2);
    $num_registros2 = $result2->num_rows;
    
   $total_debe = $total_haber = 0;
   $suma_saldo=0;
    if($num_registros > 0 || $num_registros2 > 0){
        $sum[0]=$saldo;
        $k=1;
        while($row = $result->fetch_assoc()){
            $nombrebanco=$row['ban_nombre'];
            $tipocuenta=$row['ban_tipo'];
            $numerocuenta=$row['ban_numero_cuenta'];
            $detalle_fecha[]=$row['dep_fecha'];
            $detalle_doct[]=$row['dep_numdeposito'];
            $detalle_benef[]=$row['nombre'];
            $detalle_descripcion[]=$row['dep_descripcion'];
            $detalle_valor[]=$row['dep_valor'];
            $sum[$k]=$sum[$k-1]+$row['dep_valor'];
            $k++;
        }
       $tamano_dep= count($sum);
       $rest[0]=$sum[$tamano_dep-1];
       $j=1;
        while($row = $result2->fetch_assoc()){
            $nombrebanco=$row['ban_nombre'];
            $tipocuenta=$row['ban_tipo'];
            $numerocuenta=$row['ban_numero_cuenta'];
            $detalle_fecha[]=$row['egre_fecha'];
            $detalle_doct[]=$row['egre_numegreso'];
            $detalle_benef[]=$row['NOMBRE'];
            $detalle_descripcion[]=$row['egre_descripcion'];
            $detalle_valor[]=$row['egre_valor'];
             $rest[$j]=$rest[$j-1]-$row['egre_valor'];
            $j++;
        }
    $tamano=count($detalle_doct);
        
$html='';
$html.='<html>
	<head>
            <title>Libro Bancos</title>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
            <link rel="stylesheet" href="css/reporte-diario.css" />
	</head>
	<body>
            <h3 class="titulo">Empresa</h3>
            <p class="titulo" style="margin-top:-10px;">'.$nombrebanco.'</p>
            <p class="titulo" style="margin-top:-10px;">'.$tipocuenta.' # '.$numerocuenta.'</p>
            <p class="titulo" style="margin-top:-10px;">Libro Bancos </p>
            <p class="titulo" style="margin-top:-10px;">'.$fechainicio.' '.$fechafin.' </p>
                <table style="font-size:10px;">
                <thead>
                <tr>
                    <th style="width:9%;">Fecha</th>
                    <th style="width:13%;">Doc.</th>
                    <th style="width:20%;">Beneficiario</th>
                    <th style="width:25%;">Concepto</th>
                    <th style="width:10%;">Debito</th>
                    <th style="width:10%;">Credito</th>
                    <th style="width:10%;">Saldo</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                <td></td>
                <td></td>
                <td></td>
                <td>Saldo Inicial</td>
                <td></td>
                <td></td>
                <td align="right">'.$saldo.'</td>
                </tr>';
                $j=0; //
               for($i=0;$i<count($detalle_descripcion);$i++){
                $tipo=substr($detalle_doct[$i],0,2);
                $html.='<tr>
                    <td class="codigo">'.$detalle_fecha[$i].'</td>
                    <td class="nombre">'.$detalle_doct[$i].'</td>
                    <td class="descripcion">'.$detalle_benef[$i].'</td>
                    <td class="descripcion">'.$detalle_descripcion[$i].'</td>';
                    if($tipo=='DE'){
                        $total_debe = $total_debe + $detalle_valor[$i];
                        $html.='<td class="dinero">'.number_format($detalle_valor[$i],2).'</td>
                        <td></td><td class="" align="right">'.$sum[$i+1].'</td>';
                    }else{
                        if($tipo=='EG'){
                            $total_haber = $total_haber + $detalle_valor[$i];
                            $html.='<td></td>
                                   <td class="dinero">'.number_format($detalle_valor[$i],2).'</td>
                                  <td class="" align="right">'.$rest[$j+1].'</td>';
                                  $j++;
                    }
                   }
                  $html.='</tr>';
                }
                $html.='</tbody>';
                $html.='<tfoot><tr>
                        <td colspan="4">Total:</td>
                        <td >'.number_format($total_debe,2).'</td>
                        <td>'.number_format($total_haber,2).'</td>
                            <td align="right">'.number_format($rest[count($rest)-1],2).'</td>
            </table>
            <table class="auditoria">
                <tr>
                    <td>Elaborado por</td>
                    <td>Revisado por</td>
                    <td>Aprobado por</td>
                </tr>
            </table>

	</body>
</html>';
}else{
$html='';
$html.='  <html>
	<head>
            <title>Libro Bancos</title>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
            <link rel="stylesheet" href="css/reporte-diario.css" />
	</head>
	<body>
    <h1>No existen registros</h1>
    </body>
</html>';
}
//echo $html;
require_once("../../../dyansoft/dompdf/dompdf_config.inc.php");
	
    //$html = utf8_decode($html);
    $pdf = new DOMPDF();
    $pdf -> load_html($html);
    //$paper_size = array(0,0,315,470);
    //$pdf->set_paper(DEFAULT_PDF_PAPER_SIZE, 'a4');
    ini_set("memory_limit","32M");
    $pdf -> render();
   header('Content-type: application/pdf'); //ponemos la cabecera para PDF
   
    echo $pdf->output('Asiento Diario.pdf'); //Y con ésto se manda a imprimir el contenido del pdf
    //$pdf -> stream('Asiento Diario.pdf');
    

?>