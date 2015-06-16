<?php 
    include('../../conexion.php');
    $query = "SELECT * FROM cont_plan_de_cuentas";
    $result = $link->query($query);
    static $indice = 0;
    $tabla = plan_de_cuentas($result);
    
$html='';
$html.='<html>
	<head>
            <title>Contabilidad - Plan de Cuentas</title>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
            <link rel="stylesheet" href="css/pdf-reporte.css" />
	</head>
	<body>
            <section class="container">
                    <h3>Plan de Cuentas</h3>
                    '.$tabla.'
            </section>
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
    echo $pdf->output(); //Y con ésto se manda a imprimir el contenido del pdf
    //$pdf -> stream('Plan de Cuentas.pdf');
    
function plan_de_cuentas($result){
    $tabla = "";
            $elements["masters"] = $elements["hijos"] = array();

            while($element=$result->fetch_assoc()){

                if($element['cont_id_cuenta_padre'] == $element['cont_id_cuenta']){
                    array_push($elements["masters"], $element);
                }else{
                    array_push($elements["hijos"], $element);
                }
            }
            $masters = $elements["masters"];
            $hijos = $elements["hijos"];
            
            $padre = 1;
            $entra  = 0;
            $tabla.= "<table border='1'>";
            $tabla.= "<thead>";
            $tabla.= "<tr>";
            $tabla.= "<th style='width:30%;'>Código</th><th style='width:70%;'>Nombre</th>";
            $tabla.= "</tr>";
            $tabla.= "</thead>";
            $tabla.="<tbody>";
            foreach($masters as $master){
                $GLOBALS['indice'] = $GLOBALS['indice'] + 1;
                $tmp = $GLOBALS['indice'];
                if($entra == 1){
                    $nuevo_padre = $GLOBALS['indice'];
                }else
                    $nuevo_padre = $padre;

                $tabla.="<tr>";
                $tabla.="<td>".$master['cont_codigo']."</td>";
                $tabla.="<td>".$master['cont_nombre']."</td>";
                $tabla.="</tr>";

                $tabla.=crear_arbol($hijos, $master["cont_id_cuenta"],$GLOBALS['indice'], $nuevo_padre);
                
                if($GLOBALS['indice'] - $tmp > 1){
                    $entra = 1;
                }
            }
            $tabla.="</tbody>";
            $tabla.="</table>";
            return $tabla;
}
function crear_arbol($rows = array(), $parent_id = 0, $indice_hijo, $padre){
        $html = "";
        if(!empty($rows))
        {
            foreach($rows as $row)
            {
                if($row["cont_id_cuenta_padre"] == $parent_id)
                {
                    $GLOBALS['indice'] = $GLOBALS['indice'] + 1;
                    $tmp = $GLOBALS['indice'];
                    if ($GLOBALS['indice'] - $padre > 1){
                        $nuevo_padre = $GLOBALS['indice'];
                    } else {
                        $nuevo_padre = $padre + 1;
                    }

                    $html.="<tr>";
                    $html.="<td>".$row['cont_codigo']."</td>";
                    $html.="<td>".utf8_encode($row["cont_nombre"])."</td>";
                    $html.= crear_arbol($rows, $row["cont_id_cuenta"], $GLOBALS['indice'], $nuevo_padre);
                    $html.="</tr>";

                }
            }

        }
        return $html;
    }

?>