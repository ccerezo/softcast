<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

    include('../../conexion.php');
    include('../../redireccionar.php');
    //$id = $_GET['id'];
    $inicio = '2015-05-10';//$_GET['inicio'];
    $fin = '2015-06-24';//$_GET['fin'];
    $query = "select t.numero numero, t.descripcion descripcion, t.fecha fecha,
            dad.cont_detalle_descripcion descripcion_detalle, dad.cont_valor valor_detalle, 
            dad.cont_tipo tipo_detalle, pc.cont_nombre nombre_cuenta, pc.cont_codigo codigo_cuenta
            from
            (select ad.cont_numero_asiento numero, ad.cont_descripcion descripcion, ad.cont_fecha fecha
            from cont_asientos_diarios ad
            union
            select de.dep_numdeposito numero, de.dep_descripcion descripcion, de.dep_fecha fecha
            from ban_deposito_bancario de) t
            inner join cont_detalle_asiento_diario dad on t.numero = dad.cont_num_asiento_detalle
            inner join cont_plan_de_cuentas pc on dad.cont_id_codigo_cuenta = pc.cont_id_cuenta 
            where t.fecha>='$inicio' and t.fecha<='$fin'";
    
    $result = $link->query($query);
    $num_registros = $result->num_rows;
    $total_debe = $total_haber = 0;
    
    if($num_registros > 0){
        while($row = $result->fetch_assoc()){
            $asiento_numero[] = $row['numero'];
            $asiento_fecha[] = $row['fecha'];
            $asiento_descripcion[] = utf8_encode($row['descripcion']);
            $detalle_valor[] = $row['valor_detalle'];
            $detalle_tipo[] = $row['tipo_detalle'];
            $cuenta_nombre[] = utf8_encode($row['nombre_cuenta']);
            $cuenta_codigo[] = $row['codigo_cuenta'];
        }
    }
        
$html='';
$html.='<html>
	<head>
            <title>Contabilidad - Asiento de Diario</title>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
            <link rel="stylesheet" href="css/reporte-diario.css" />
	</head>
	<body>
            
            <h3 class="titulo">Libro Diario</h3>
            <h3 class="titulo">Nombre de la Empresa</h3>
            <p> Desde el '.$inicio.' Hasta el '.$fin.'</p>
            
            <table>
                <thead>
                <tr>
                    <th style="width:5%;">N°</th>
                    <th style="width:13%;">Fecha</th>
                    <th style="width:60%;">Descripción</th>
                    <th style="width:11%;">Debe</th>
                    <th style="width:11%;">Haber</th>
                </tr>
                </thead>
                <tbody>';
                $aux='';$j=0;
                for($i = 0; $i < count($asiento_descripcion); $i++){
                    if($aux===$asiento_numero[$i]){
                        $html.='<tr>
                        <td class="codigo"></td>
                        <td class="nombre"></td>
                        <td class="nombre_cuenta">'.$cuenta_nombre[$i].'</td>';
                        if($detalle_tipo[$i]=='d'){
                            $total_debe = $total_debe + $detalle_valor[$i];
                            $html.='<td class="dinero">'.number_format($detalle_valor[$i],2).'</td>
                            <td></td>';
                        }else{
                            if($detalle_tipo[$i]=='h'){
                                $total_haber = $total_haber + $detalle_valor[$i];
                                $html.='<td></td>
                                       <td class="dinero">'.number_format($detalle_valor[$i],2).'</td>';
                            }
                        }
                      $html.='</tr>';
                    }else{
                        $html.='<tr>
                        <td class="codigo">'.(++$j).'</td>
                        <td class="nombre">'.$asiento_fecha[$i].'</td>
                        <td class="descripcion">'.$asiento_numero[$i].'<br>'.$asiento_descripcion[$i].'</td>
                        <td class="dinero"></td>
                        <td></td>
                        </tr><tr>
                        <td class="codigo"></td>
                        <td class="nombre"></td>
                        <td class="nombre_cuenta">'.$cuenta_nombre[$i].'</td>';
                        if($detalle_tipo[$i]=='d'){
                            $total_debe = $total_debe + $detalle_valor[$i];
                            $html.='<td class="dinero">'.number_format($detalle_valor[$i],2).'</td>
                            <td></td>';
                        }else{
                            if($detalle_tipo[$i]=='h'){
                                $total_haber = $total_haber + $detalle_valor[$i];
                                $html.='<td></td>
                                       <td class="dinero">'.number_format($detalle_valor[$i],2).'</td>';
                            }
                        }
                      $html.='</tr>';
                    }
                  
                  $aux = $asiento_numero[$i];
                }
                
                $html.='</tbody>';
                $html.='<tfoot><tr>
                        <td colspan="3">Total:</td>
                        <td>'.number_format($total_debe,2).'</td>
                        <td>'.number_format($total_haber,2).'</td>
            </table>
            
	</body>
</html>';
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