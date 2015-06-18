<?php 
    include('../../conexion.php');
    // Realizar una consulta MySQL
    if(isset($_POST['orden'])){$orden = $_POST['orden'];}
    
    static $indice = 0;

    switch( $orden )
    {
        case 'consultar_plan':
            $query = "SELECT * FROM cont_plan_de_cuentas";
            $result = $link->query($query);
            plan_de_cuentas($result);
        break;
        
        case 'verificar_cuenta':

            if(isset($_POST['codigo'])){$codigo = $_POST['codigo'];}
            $tamano = strlen($codigo);
            $query = "SELECT * FROM cont_plan_de_cuentas WHERE cont_codigo = '$codigo'";
            $result = $link->query($query);
            $filas = $result->num_rows;
            if($filas == 0){
                switch ($tamano) {
                    case 1://Primer Nivel
                        echo validar_cuenta($codigo,$link);
                    break;
                    
                    case 3://Segundo Nivel
                        $codigo_padre = substr($codigo, 0, 1);
                        echo validar_cuenta($codigo_padre,$link);
                    break;
                
                    case 6://Tercer Nivel
                        $codigo_padre = substr($codigo, 0, 3);
                        echo validar_cuenta($codigo_padre,$link);
                    break;
                
                    case 9://Cuarto Nivel
                        $codigo_padre = substr($codigo, 0, 6);
                        echo validar_cuenta($codigo_padre,$link);
                    break;
                    
                    case 14://Quinto Nivel
                        $codigo_padre = substr($codigo, 0, 9);
                        echo validar_cuenta($codigo_padre,$link);
                    break;
                    
                    default :
                        echo 'invalido';
                    break;
                }
                
            }  else {
                echo 'existe';
            }
        break;
        
        case 'ingresar_cuenta':

            if(isset($_POST['codigo'])){$codigo = $_POST['codigo'];}
            if(isset($_POST['nombre'])){$nombre = utf8_decode($_POST['nombre']);}

            /*Si falla imprimimos el error*/
            if (!($result = $link->query("CALL sp_cont_insertar_plan_de_cuentas('$codigo', '$nombre')"))) {
                echo "Falló la llamada: (" . $link->errno . ") " . $link->error;
            }else{
                echo 'ok';
            }
            /*E imprimimos el resultado para ver que el ejemplo ha funcionado*/
            //var_dump($result->fetch_assoc());

        break;
        
        case 'eliminar_cuenta':

            if(isset($_POST['codigo'])){$codigo = $_POST['codigo'];}

            /*Si falla imprimimos el error*/
            if (!($link->query("CALL sp_cont_eliminar_cuenta('$codigo', @elimina)"))) {
                echo "Falló la llamada: (" . $link->errno . ") " . $link->error;
            }else{
                $result = $link->query("SELECT @elimina") ;
                while($row = $result->fetch_assoc()){
                    $elimina = $row['@elimina'];
                }
                echo $elimina;
            }
           
        break;
/*******************************************************************************/
/*************************** ASIENTOS DE DIARIO ********************************/
/*******************************************************************************/
        
        case 'ultimo_diario_mes':
            $fecha = $_POST['mes'];
            $query = "SELECT * FROM cont_asientos_diarios WHERE cont_numero_asiento like '%".$fecha."%' ORDER BY cont_id_asientos DESC LIMIT 1";
            $result = $link->query($query);
            $num_registros = $result->num_rows;
            if($num_registros > 0){
                while($row = $result->fetch_assoc()){
                    $ultimo_asiento = $row['cont_numero_asiento'];
                }
                echo $ultimo_asiento;
            }else{
                echo '0';
            }
        break;
    
        case 'consultar_asientos':
            $query = "SELECT * FROM cont_asientos_diarios";
            $result = $link->query($query);
            $num_registros = $result->num_rows;
            if($num_registros > 0){
                while($row = $result->fetch_assoc()){
                    $asientos_contables[] = array(  'id_asiento' => $row['cont_id_asientos'],
                                                    'num_asiento' => $row['cont_numero_asiento'],
                                                    'fecha' => $row['cont_fecha'],
                                                    'descripcion' => utf8_encode($row['cont_descripcion']),
                                                    'valor' => $row['cont_valor_total']);
                }
                echo json_encode($asientos_contables);
            }else{
                echo 'vacio';
            }
            
        break;
    
        case 'consultar_cuentas_contables':
            $query = "SELECT * FROM cont_plan_de_cuentas where cont_catogoria = 'C'";
            $result = $link->query($query);
            $num_registros = $result->num_rows;
            if($num_registros > 0){
                while($row = $result->fetch_assoc()){
                    $cuentas_contables[] = array('id_cuenta' => $row['cont_id_cuenta'],'codigo' => $row['cont_codigo'], 'nombre' => utf8_encode($row['cont_nombre']));
                }
                echo json_encode($cuentas_contables);
            }
        break;
        
        case 'ingresar_asiento_diario':
            if(isset($_REQUEST['numero'])){
                $numero = $_REQUEST['numero'];
            }
            if(isset($_REQUEST['total'])){
                $total = $_REQUEST['total'];
            }
            if(isset($_REQUEST['datos'])){
                $parametros = json_decode($_REQUEST['datos'],true);
                
                $i=0;
                foreach($parametros as $array){
                    if($i==0){
                        $fecha = $array["value"];
                    }
                        
                    if($i==1)
                        $descripcion = utf8_decode($array["value"]);
                    if($i==2 && $array["value"]!=NULL)
                        $codigo[] = $array["value"];
                    if($i==3 && $array["value"]!=NULL)
                        $nombre[] = $array["value"];
                    if($i==4 && $array["value"]!=NULL)
                        $descripcion_detalle[] = utf8_decode($array["value"]);
                    if($i==5 && $array["value"]!=NULL){
                        $valor[] = str_replace(",","",$array["value"]);
                        $tipo[] = substr($array["name"],0,1);
                    }
                        
                    if($i === 5)
                        $i=2;
                    else
                        $i++;
                }
                $link->query("SET AUTOCOMMIT=0;"); //Para InnoDB, sirve para mantener la transaccion abierta
                //Inicio de transacción
                $link->query("BEGIN;");
                
                $sql = "INSERT INTO cont_asientos_diarios (cont_numero_asiento, cont_fecha, cont_descripcion, cont_valor_total) ";
                $sql .= "values ('$numero', '$fecha', '$descripcion', $total)";
                $result = $link->query($sql);

                if(!$result){
                    echo "fallo";//"Error en la Transacción 1: ".$link->error;
                    $link->query("ROLLBACK;");           //Terminar la transaccion si hay error
                    exit();
                }
        
                $id_asiento = mysqli_insert_id($link);
                
                for($i = 0; $i < count($codigo); $i++){
                    $sql1 = "INSERT INTO cont_detalle_asiento_diario(cont_num_asiento_detalle, cont_id_codigo_cuenta, cont_detalle_descripcion, cont_valor, cont_tipo)";
                    $sql1 .= "VALUES ('$numero', $codigo[$i], '$descripcion_detalle[$i]', $valor[$i], '$tipo[$i]')"; 
                    $result = $link->query($sql1);
                    if(!$result){
                        echo "fallo";//;"Error en la Transacción 2: ".$link->error;
                        $link->query("ROLLBACK;");    //Terminar la transaccion si hay error
                        exit();
                    }
                }

                if ($result) {
                    $link->query("COMMIT");      //Terminar la transaccion
                    $query = "SELECT * FROM cont_asientos_diarios WHERE cont_id_asientos = '$id_asiento'";
                    $result_in = $link->query($query);
                    $num_registros = $result_in->num_rows;
                    if($num_registros > 0){
                        while($row = $result_in->fetch_assoc()){
                            $asientos_contables[] = array(  'id_asiento' => $row['cont_id_asientos'],
                                                            'num_asiento' => $row['cont_numero_asiento'],
                                                            'fecha' => $row['cont_fecha'],
                                                            'descripcion' => utf8_encode($row['cont_descripcion']),
                                                            'valor' => $row['cont_valor_total']);
                        }
                        echo json_encode($asientos_contables);
                    }else{
                        echo "fallo";
                    }
                }  
                $link->query("END;");  
            }
                
        break;

}
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
            $tabla.= "<table class='table table-striped table-bordered tree'>";
            $tabla.="<tbody>";
            foreach($masters as $master){
                $GLOBALS['indice'] = $GLOBALS['indice'] + 1;
                $tmp = $GLOBALS['indice'];
                if($entra == 1){
                    $nuevo_padre = $GLOBALS['indice'];
                }else
                    $nuevo_padre = $padre;

                $tabla.="<tr class=treegrid-".$GLOBALS['indice']." tabindex='-1'>";
                $tabla.="<td style='width:30%;'>".$master['cont_codigo']."</td>";
                $tabla.="<td style='width:70%;'>".$master['cont_nombre']."</td>";
                $tabla.="</tr>";

                $tabla.=crear_arbol($hijos, $master["cont_id_cuenta"],$GLOBALS['indice'], $nuevo_padre);
                
                if($GLOBALS['indice'] - $tmp > 1){
                    $entra = 1;
                }
            }
            $tabla.="</tbody>";
            $tabla.="</table>";
            echo $tabla;
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

                    $html.="<tr class='treegrid-$GLOBALS[indice] treegrid-parent-$padre' tabindex='-1'>";
                    $html.="<td>".$row['cont_codigo']."</td>";
                    $html.="<td>".utf8_encode($row["cont_nombre"])."</td>";
                    $html.= crear_arbol($rows, $row["cont_id_cuenta"], $GLOBALS['indice'], $nuevo_padre);
                    $html.="</tr>";

                }
            }

        }
        return $html;
    }
   
    function validar_cuenta($codigo,$link){
        $query = "SELECT * FROM cont_plan_de_cuentas WHERE cont_codigo LIKE '$codigo%'";
        $result = $link->query($query);
        $filas = $result->num_rows;
        
        if($filas == 0){
            return 'invalido';
        }  else {
            return 'valido';
        }
    }
?>
