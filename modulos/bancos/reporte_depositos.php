<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

    include('../../conexion.php');
    include('../../redireccionar.php');
    $id = $_GET['id'];
   /* $query = "select ad.cont_numero_asiento numero_asiento, ad.cont_fecha fecha_asiento, 
            ad.cont_descripcion descripcion_asiento, ad.cont_valor_total, dad.cont_detalle_descripcion descripcion_detalle, 
            dad.cont_valor valor_detalle, dad.cont_tipo tipo_detalle, pc.cont_nombre nombre_cuenta, pc.cont_codigo codigo_cuenta
            from cont_asientos_diarios ad 
            inner join cont_detalle_asiento_diario dad on ad.cont_id_asientos = dad.cont_id_asiento_diario 
            inner join cont_plan_de_cuentas pc on dad.cont_id_codigo_cuenta = pc.cont_id_cuenta
            where ad.cont_id_asientos='$id'";*/
    
     $query = "select dep.*, dia.*,ban.*,cli.*,plan.* from ban_deposito_bancario dep 
            inner join ban_banco_diario dia on dep.dep_numdeposito= dia.bandia_diario 
            inner join banco ban on dep.dep_bancoid = ban.ban_id
            inner join cliente cli on dep.dep_clienteid=cli.id
            inner join cont_plan_de_cuentas plan on dia.bandia_id_codigo_cuenta=plan.cont_id_cuenta
            where dep.dep_id='$id'";
    $result = $link->query($query);
    $num_registros = $result->num_rows;
    $total_debe = $total_haber = 0;
    
    if($num_registros > 0){
        while($row = $result->fetch_assoc()){
            $deposito_numero = $row['dep_numdeposito'];
            $fecha = $row['dep_fecha'];
            $descripcion = utf8_encode($row['dep_descripcion']);
            $banco = utf8_encode($row['ban_nombre'].' '.$row['ban_tipo'].' '.$row['ban_numero_cuenta']);
            $cliente = utf8_encode($row['nombre']);
            $detalle_descripcion[] = utf8_encode($row['bandia_detalle_descripcion']);
            $detalle_valor[] = $row['bandia_valor'];
            $detalle_tipo[] = $row['bandia_tipo'];
            $cuenta_nombre[] = utf8_encode($row['cont_nombre']);
            $cuenta_codigo[] = $row['cont_codigo'];
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
            
            <h3 class="titulo">Depósito N° '.$deposito_numero.'</h3>
            <h3 class="titulo">Depósito N° '.$deposito_numero.'</h3>
            <p style="text-align:right"><span class="labels">Fecha:</span><span class="dato">'.$fecha.'</span></p>
            <p><span class="labels">Descripción:</span><span class="dato">'.$descripcion.'</span></p>
            <p><span class="labels">Banco:</span><span class="dato">'.$banco.'</span></p>
            <p><span class="labels">Cliente:</span><span class="dato">'.$cliente.'</span></p>
            <table>
                <thead>
                <tr>
                    <th style="width:12%;">Código Cta.</th>
                    <th style="width:20%;">Nombre Cta.</th>
                    <th style="width:46%;">Descripción</th>
                    <th style="width:11%;">Debe</th>
                    <th style="width:11%;">Haber</th>
                </tr>
                </thead>
                <tbody>';
                for($i=0;$i<count($detalle_descripcion);$i++){			
                $html.='<tr>
                    <td class="codigo">'.$cuenta_codigo[$i].'</td>
                    <td class="nombre">'.$cuenta_nombre[$i].'</td>
                    <td class="descripcion">'.$detalle_descripcion[$i].'</td>';
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
                $html.='</tbody>';
                $html.='<tfoot><tr>
                        <td colspan="3">Total:</td>
                        <td>'.number_format($total_debe,2).'</td>
                        <td>'.number_format($total_haber,2).'</td>
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