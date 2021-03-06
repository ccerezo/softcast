<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

    include('../../conexion.php');
    include('../../redireccionar.php');
    $desde = $_GET['desde'];
    $inicio = $_GET['inicio'];
    $fin = $_GET['fin'];
    
    $query_saldo= "select sum(dad.cont_valor) valor_detalle, dad.cont_tipo tipo_detalle, pc.cont_nombre nombre_cuenta
        from cont_asientos_diarios ad 
        inner join cont_detalle_asiento_diario dad on ad.cont_id_asientos = dad.cont_id_asiento_diario 
        inner join cont_plan_de_cuentas pc on dad.cont_id_codigo_cuenta = pc.cont_id_cuenta      
        where dad.cont_id_codigo_cuenta='$desde' and ad.cont_fecha<'$inicio'
        group by dad.cont_tipo";
    
    $result_saldo = $link->query($query_saldo);
    $num_registros_saldo = $result_saldo->num_rows;
    $debe = $haber = 0;
    if($num_registros_saldo > 0){
        while($row_saldo = $result_saldo->fetch_assoc()){
            if($row_saldo['tipo_detalle']=='d'){
                $debe = $row_saldo['valor_detalle'];
            }else{
                if($row_saldo['tipo_detalle']=='h'){
                    $haber = $row_saldo['valor_detalle'];
                }
            }
            
        }
        $saldo = $debe - $haber;
    }  else {
        $saldo = 0;
    }
    $query = "select ad.cont_fecha fecha_asiento, ad.cont_numero_asiento numero_asiento, ad.cont_valor_total, dad.cont_detalle_descripcion descripcion_detalle, 
      dad.cont_valor valor_detalle, dad.cont_tipo tipo_detalle, pc.cont_nombre nombre_cuenta, pc.cont_codigo codigo_cuenta
      from cont_asientos_diarios ad 
      inner join cont_detalle_asiento_diario dad on ad.cont_id_asientos = dad.cont_id_asiento_diario 
      inner join cont_plan_de_cuentas pc on dad.cont_id_codigo_cuenta = pc.cont_id_cuenta
      where dad.cont_id_codigo_cuenta='$desde' and ad.cont_fecha>='$inicio' and ad.cont_fecha<='$fin'";
    
    $result = $link->query($query);
    $num_registros = $result->num_rows;
    $total_debe = $total_haber = 0;
    
    if($num_registros > 0){
        while($row = $result->fetch_assoc()){
            $test = explode("-",$row['fecha_asiento']); 
            $y = $test[0]; 
            $m = mes_texto($test[1]); 
            $d = $test[2]; 
            $asiento_fecha[] = "$d de $m del $y";
            $asiento_numero[] = $row['numero_asiento'];
            $detalle_descripcion[] = utf8_encode($row['descripcion_detalle']);
            $detalle_valor[] = $row['valor_detalle'];
            $detalle_tipo[] = $row['tipo_detalle'];
            $cuenta_nombre = utf8_encode($row['nombre_cuenta']);
            $cuenta_codigo = $row['codigo_cuenta'];
        }
        $inicio_test = explode("-",$inicio); 
        $inicio_y = $inicio_test[0];
        $inicio_m = mes_texto($inicio_test[1]);
        $inicio_d = $inicio_test[2];
        
        $inicio = "$inicio_d de $inicio_m del $inicio_y";
        
        $fin_test = explode("-",$fin); 
        $fin_y = $fin_test[0];
        $fin_m = mes_texto($fin_test[1]);
        $fin_d = $fin_test[2];
        
        $fin = "$fin_d de $fin_m del $fin_y";
    }
        
$html='';
$html.='<html>
	<head>
            <title>Contabilidad - Asiento de Diario</title>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
            <link rel="stylesheet" href="css/reporte-diario.css" />
	</head>
	<body>
            
            <h3 class="titulo">Mayor Contable</h3>
            <p> Desde el '.$inicio.' Hasta el '.$fin.'</p>
            <p>Cuenta: '.$cuenta_codigo.' - '.$cuenta_nombre.'</p>
            <table>
                <thead>
                <tr>
                    <th style="width:10%;">Fecha</th>
                    <th style="width:10%;">Doc. Contable</th>
                    <th style="width:10%;">Factura</th>
                    <th style="width:10%;">Beneficiario</th>
                    <th style="width:30%;">Descripción</th>
                    <th style="width:10%;">Debe</th>
                    <th style="width:10%;">Haber</th>
                    <th style="width:10%;">Saldo</th>
                </tr>
                </thead>
                <tbody>
                <tr><td colspan="8" style="text-align:right;">'.number_format($saldo,2).'</td></tr>';
                for($i=0;$i<count($detalle_descripcion);$i++){			
                $html.='<tr>
                    <td class="codigo">'.$asiento_fecha[$i].'</td>
                    <td class="nombre">'.$asiento_numero[$i].'</td>
                    <td class="descripcion">Fact °</td>
                    <td class="descripcion">Benefciario</td>
                    <td class="descripcion">'.$detalle_descripcion[$i].'</td>';
                    if($detalle_tipo[$i]=='d'){
                        $total_debe = $total_debe + $detalle_valor[$i];
                        $saldo = $saldo + $detalle_valor[$i];
                        $html.='<td class="dinero">'.number_format($detalle_valor[$i],2).'</td>
                        <td></td>
                        <td class="dinero">'.number_format($saldo,2).'</td>';
                    }else{
                        if($detalle_tipo[$i]=='h'){
                            $total_haber = $total_haber + $detalle_valor[$i];
                            $saldo = $saldo - $detalle_valor[$i];
                            $html.='<td></td>
                                   <td class="dinero">'.number_format($detalle_valor[$i],2).'</td>
                                    <td class="dinero">'.number_format($saldo,2).'</td>';
                        }
                    }
                  $html.='</tr>';
                }
                $html.='</tbody>';
                $html.='<tfoot><tr>
                        <td colspan="5">Total:</td>
                        <td>'.number_format($total_debe,2).'</td>
                        <td>'.number_format($total_haber,2).'</td>
                        <td>'.number_format($saldo,2).'</td>
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

function mes_texto($m) { 
    switch ($m) { 
        case 1: $month_text = "Enero"; break; 
        case 2: $month_text = "Febrero"; break; 
        case 3: $month_text = "Marzo"; break; 
        case 4: $month_text = "Abril"; break; 
        case 5: $month_text = "Mayo"; break; 
        case 6: $month_text = "Junio"; break; 
        case 7: $month_text = "Julio"; break; 
        case 8: $month_text = "Agosto"; break; 
        case 9: $month_text = "Septiembre"; break; 
        case 10: $month_text = "Octubre"; break; 
        case 11: $month_text = "Noviembre"; break; 
        case 12: $month_text = "Diciembre"; break; 
    } 
    return ($month_text); 
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
    //$pdf -> stream('Asiento Diario.pdf');*/
    

?>