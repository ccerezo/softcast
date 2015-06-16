<?php 
	include('../../conexion.php');

 
$proceso=$_REQUEST["proceso"];
	


switch( $proceso )
{
	   	case 'insertar_banco':
        
        $nombre=$_REQUEST["nuevo_banco"];
        
          $query2 = "select * from banco ORDER BY ban_id DESC LIMIT 1";
				$result2 = mysql_query($query2) or die('Consulta fallida: ' . mysql_error());
		
        
      
        
            while($data=mysql_fetch_array($result2)){
   //echo"<option value='$data[ban_id]'>$data[ban_nombre]</option>";
   $codigo_ultimo_banco=$data['ban_codigo'];
   $num= explode("b", $codigo_ultimo_banco);
   
   $cod=$num[1]+1;
  
}
  $num_results = mysql_num_rows($result2); 
if ($num_results <1){ 
 $cod=1;
}

       $query = "INSERT INTO banco (ban_codigo,ban_nombre,ban_estado) VALUES ('b$cod', '$nombre','A')";
			
			$result = $link->query($query) or die('Consulta fallida: ' . mysql_error());
			echo 'ok php';
       

        break;
        
        
        case 'ingresar_banco':
        
        $cod_banco=$_REQUEST["codigo_banco"];
          $n_banco=$_REQUEST["n_banco"];
            
            //$nom_banco=$_REQUEST["nombre_banco"];
        $num_cuenta=$_REQUEST["numero_cuenta"];
        $tipo_cuenta=$_REQUEST["tipo_cuenta"];
        $cod_cta_contable=$_REQUEST["cod_cuenta_contable"];
        
       
       //$query = "INSERT INTO banco (ban_codigo,ban_nombre,ban_estado,ban_numero_cuenta,ban_tipo,ban_cod_cuenta_contable) VALUES ('$cod_banco', '$nom_banco','A','$num_cuenta','$tipo_cuenta','$cod_cta_contable')";
$query=" INSERT INTO banco (ban_codigo,ban_nombre,ban_estado,ban_numero_cuenta,ban_tipo,ban_cod_cuenta_contable)
 VALUES ('$cod_banco', '$n_banco','A','$num_cuenta','$tipo_cuenta','$cod_cta_contable') ON DUPLICATE KEY UPDATE    
ban_codigo=VALUES(ban_codigo), ban_nombre=VALUES(ban_nombre),ban_estado=VALUES(ban_estado),ban_numero_cuenta=VALUES(ban_numero_cuenta),
ban_tipo=VALUES(ban_tipo)";	
		
        
        	$result = $link->query($query); //or die('Consulta fallida: ' . mysql_error());
			echo 'ok php';
       

        break;
        
         case 'editar_banco':
        
        $cod_banco=$_REQUEST["codigo_banco"];
          $n_banco=$_REQUEST["editar_n_banco"];
            
            //$nom_banco=$_REQUEST["nombre_banco"];
        $num_cuenta=$_REQUEST["editar_numero_cuenta"];
        $tipo_cuenta=$_REQUEST["editar_tipo_cuenta"];
        $cod_cta_contable=$_REQUEST["editar_cod_cuenta_contable"];
      //  print_r($_REQUEST);
     
       //$query = "INSERT INTO banco (ban_codigo,ban_nombre,ban_estado,ban_numero_cuenta,ban_tipo,ban_cod_cuenta_contable) VALUES ('$cod_banco', '$nom_banco','A','$num_cuenta','$tipo_cuenta','$cod_cta_contable')";
$query=" INSERT INTO banco (ban_codigo,ban_nombre,ban_estado,ban_numero_cuenta,ban_tipo,ban_cod_cuenta_contable)
 VALUES ('$cod_banco', '$n_banco','A','$num_cuenta','$tipo_cuenta','$cod_cta_contable') ON DUPLICATE KEY UPDATE    
ban_codigo=VALUES(ban_codigo), ban_nombre=VALUES(ban_nombre),ban_estado=VALUES(ban_estado),ban_numero_cuenta=VALUES(ban_numero_cuenta),
ban_tipo=VALUES(ban_tipo)";	
		
        
        	$result = $link->query($query); //or die('Consulta fallida: ' . mysql_error());
			echo 'ok php';
       

        break;
        
        case 'ingresar_deposito':
       
       if(isset($_REQUEST['filas'])){
                $filas = $_REQUEST['filas'];
            }
   
     if(isset($_REQUEST['datos'])){
                $parametros = json_decode($_REQUEST['datos'],true);
                
                $i=0;
                foreach($parametros as $array){
                    if($i==0){
                        $deposito = $array["value"];
                    }
                        
                    if($i==1)
                        $fecha = utf8_decode($array["value"]);
                        if($i==2)
                        $banco_id = utf8_decode($array["value"]);
                        if($i==3)
                        $transaccion = utf8_decode($array["value"]);
                    if($i==4)
                        $valor_dep = utf8_decode($array["value"]);
                      if($i==5)
                        $valor_dep_unmask = utf8_decode($array["value"]);
                    if($i==6)
                        $cliente_id = utf8_decode($array["value"]);
                    if($i==7)
                        $descripcion_dep = utf8_decode($array["value"]);
                    if($i==8)
                        $diferencia = utf8_decode($array["value"]);
                    if($i==9 && $array["value"]!=NULL)
                        $cuenta[] = $array["value"];
                    if($i==10 && $array["value"]!=NULL)
                        $codigo_cuenta[] = $array["value"];
                    if($i==11 && $array["value"]!=NULL)
                        $nombre_cuenta[] = utf8_decode($array["value"]);
                    if($i==12 && $array["value"]!=NULL)
                        $descripcion_cuenta[] = utf8_decode($array["value"]);
                     if($i==13 && $array["value"]!=NULL)
                        $valor_mask[] = utf8_decode($array["value"]);
                    if($i==14 && $array["value"]!=NULL){
                        $valor[] =  str_replace(",","",$array["value"]);
                        $tipo[] = substr($array["name"],7,1);
                    }
                        
                    if($i === 14)
                        $i=9;
                    else
                        $i++;
                }
                
                 // echo json_encode($deposito.' '.$banco_id.' '.$cliente_id.' '.$descripcion_dep.' '.$fecha.' '.$transaccion.' '.$valor_dep_unmask.' '.$cuenta[0].$cuenta[1].$descripcion_cuenta[0].$descripcion_cuenta[1].$valor[0].$valor[1].$tipo[0].$tipo[1]);
       
          
              $link->query("SET AUTOCOMMIT=0;"); //Para InnoDB, sirve para mantener la transaccion abierta
                //Inicio de transacción
                $link->query("BEGIN;");
                
                $sql = "INSERT INTO ban_deposito_bancario (dep_numdeposito, dep_bancoid, dep_clienteid, dep_descripcion,dep_fecha,dep_numtransaccion,dep_valor) ";
                $sql .= "values ('$deposito', '$banco_id', '$cliente_id', '$descripcion_dep','$fecha','$transaccion','$valor_dep_unmask')";
                $result = $link->query($sql);

                if(!$result){
                    echo "fallo";//"Error en la Transacción 1: ".$link->error;
                    $link->query("ROLLBACK;");           //Terminar la transaccion si hay error
                    exit();
                }
        
              $id_diario = mysqli_insert_id($link);
                
                for($i = 0; $i < count($cuenta); $i++){
                    $sql1 = "INSERT INTO ban_banco_diario(bandia_diario, bandia_id_codigo_cuenta, bandia_detalle_descripcion, bandia_valor, bandia_tipo)";
                    $sql1 .= "values ('$deposito', '$cuenta[$i]', '$descripcion_cuenta[$i]', '$valor[$i]', '$tipo[$i]')"; 
                    $result = $link->query($sql1);
                    if(!$result){
                        echo "fallo";
                        $link->query("ROLLBACK;");    
                        exit();
                    }
                }

               if ($result) {
                    $link->query("COMMIT");      //Terminar la transaccion
                    $query = "SELECT * FROM ban_deposito_bancario WHERE dep_id = '$id_diario'";
                    $result_in = $link->query($query);
                    $num_registros = $result_in->num_rows;
                    if($num_registros > 0){
                        while($row = $result_in->fetch_assoc()){
                            $asientos_contables[] = array(  'id_deposito' => $row['dep_id']);
                        }
                        echo json_encode($asientos_contables);
                    }else{
                        echo "fallo";
                    }
                }  
               $link->query("END;");  
           }
         
          break;
          
          
            case 'ingresar_egreso':
       
       if(isset($_REQUEST['filas'])){
                $filas = $_REQUEST['filas'];
            }
   
     if(isset($_REQUEST['datos'])){
                $parametros = json_decode($_REQUEST['datos'],true);
                
                $i=0;
                foreach($parametros as $array){
                    if($i==0){
                        $egreso = $array["value"];
                    }
                        
                    if($i==1)
                        $fecha = utf8_decode($array["value"]);
                        if($i==2)
                        $banco_id = utf8_decode($array["value"]);
                        if($i==3)
                        $num_cheque = utf8_decode($array["value"]);
                    if($i==4)
                        $valor_egre = utf8_decode($array["value"]);
                      if($i==5)
                        $valor_egre_unmask = utf8_decode($array["value"]);
                      if($i==6)
                        $beneficiario = utf8_decode($array["value"]);
                    if($i==7)
                        $nom_proveedor = utf8_decode($array["value"]);
                    if($i==8)
                        $descripcion_egre = utf8_decode($array["value"]);
                    if($i==9)
                        $diferencia = utf8_decode($array["value"]);
                    if($i==10 && $array["value"]!=NULL)
                        $cuenta[] = $array["value"];
                    if($i==11 && $array["value"]!=NULL)
                        $codigo_cuenta[] = $array["value"];
                    if($i==12 && $array["value"]!=NULL)
                        $nombre_cuenta[] = utf8_decode($array["value"]);
                    if($i==13 && $array["value"]!=NULL)
                        $descripcion_cuenta[] = utf8_decode($array["value"]);
                     if($i==14 && $array["value"]!=NULL)
                        $valor_mask[] = utf8_decode($array["value"]);
                    if($i==15 && $array["value"]!=NULL){
                        $valor[] =  str_replace(",","",$array["value"]);
                        $tipo[] = substr($array["name"],7,1);
                    }
                        
                    if($i === 15)
                        $i=10;
                    else
                        $i++;
                }
                
                 // echo json_encode($deposito.' '.$banco_id.' '.$cliente_id.' '.$descripcion_dep.' '.$fecha.' '.$transaccion.' '.$valor_dep_unmask.' '.$cuenta[0].$cuenta[1].$descripcion_cuenta[0].$descripcion_cuenta[1].$valor[0].$valor[1].$tipo[0].$tipo[1]);
       
          
              $link->query("SET AUTOCOMMIT=0;"); //Para InnoDB, sirve para mantener la transaccion abierta
                //Inicio de transacción
                $link->query("BEGIN;");
                
                $sql = "INSERT INTO ban_egreso_bancario (egre_numegreso, egre_bancoid, egre_proveedorid, egre_descripcion,egre_fecha,egre_chequeid,egre_valor) ";
                $sql .= "values ('$egreso', '$banco_id', '$nom_proveedor', '$descripcion_egre','$fecha','$num_cheque','$valor_egre_unmask')";
                $result = $link->query($sql);

                if(!$result){
                    echo "fallo";//"Error en la Transacción 1: ".$link->error;
                    $link->query("ROLLBACK;");           //Terminar la transaccion si hay error
                    exit();
                }
        
              $id_diario = mysqli_insert_id($link);
                
                for($i = 0; $i < count($cuenta); $i++){
                    $sql1 = "INSERT INTO ban_banco_diario(bandia_diario, bandia_id_codigo_cuenta, bandia_detalle_descripcion, bandia_valor, bandia_tipo)";
                    $sql1 .= "values ('$egreso', '$cuenta[$i]', '$descripcion_cuenta[$i]', '$valor[$i]', '$tipo[$i]')"; 
                    $result = $link->query($sql1);
                    if(!$result){
                        echo "fallo";
                        $link->query("ROLLBACK;");    
                        exit();
                    }
                }

               if ($result) {
                    $link->query("COMMIT");      //Terminar la transaccion
                    $query = "SELECT * FROM ban_egreso_bancario WHERE egre_id = '$id_diario'";
                    $result_in = $link->query($query);
                    $num_registros = $result_in->num_rows;
                    if($num_registros > 0){
                        while($row = $result_in->fetch_assoc()){
                            $asientos_contables[] = array(  'id_egreso' => $row['egre_id']);
                        }
                        echo json_encode($asientos_contables);
                    }else{
                        echo "fallo";
                    }
                }  
               $link->query("END;");  
           }
         
          break;
          
          
          
          
          
        case 'ultimo_diario_mes':
            $fecha = $_POST['mes'];
            $query = "SELECT * FROM ban_deposito_bancario WHERE dep_numdeposito like '%DE-".$fecha."%' ORDER BY dep_id DESC LIMIT 1";
            $result = $link->query($query);
            $num_registros = $result->num_rows;
            if($num_registros > 0){
                while($row = $result->fetch_assoc()){
                    $ultimo_asiento = $row['dep_numdeposito'];
                }
                echo $ultimo_asiento;
            }else{
                echo '0';
            }
        break;
        
        case 'ultimo_diario_mes_egreso':
            $fecha = $_POST['mes'];
            $query = "SELECT * FROM ban_egreso_bancario WHERE egre_numegreso like '%EG-".$fecha."%' ORDER BY egre_id DESC LIMIT 1";
            $result = $link->query($query);
            $num_registros = $result->num_rows;
            if($num_registros > 0){
                while($row = $result->fetch_assoc()){
                    $ultimo_asiento = $row['egre_numegreso'];
                }
                echo $ultimo_asiento;
            }else{
                echo '0';
            }
        break;
        case 'ultimo_cheque_banco':
            $bancoid = $_POST['banco'];
            $query = "SELECT * FROM ban_cheque WHERE che_bancoid=$bancoid ORDER BY che_id DESC LIMIT 1";
            $result = $link->query($query);
            if(!$result){
                        echo "";
                           
                        exit();
                    }
            
            $num_registros = $result->num_rows;
            if($num_registros > 0){
                while($row = $result->fetch_assoc()){
                    $ultimo_cheque = $row['che_numero_cheque'];
                }
                $ultimo=$ultimo_cheque+1;
                echo $ultimo;
            }else{
                echo '1';
            }
            
            
            
        break;
        /*case 'consulta_librobancos':
          
                 if(isset($_REQUEST['datos'])){
                $parametros = json_decode($_REQUEST['datos'],true);
                
                $i=0;
                foreach($parametros as $array){
                    if($i==0){
                        $id_banco = $array["value"];
                    }
                        
                    if($i==1)
                        $tipo_cuenta = utf8_decode($array["value"]);
                    if($i==2)
                        $numero_cuenta = utf8_decode($array["value"]);
                    if($i==3)
                        $rangofecha = utf8_decode($array["value"]);
                    
                        
                  
                }
                
                 // echo json_encode($deposito.' '.$banco_id.' '.$cliente_id.' '.$descripcion_dep.' '.$fecha.' '.$transaccion.' '.$valor_dep_unmask.' '.$cuenta[0].$cuenta[1].$descripcion_cuenta[0].$descripcion_cuenta[1].$valor[0].$valor[1].$tipo[0].$tipo[1]);
       
          
              $link->query("SET AUTOCOMMIT=0;"); //Para InnoDB, sirve para mantener la transaccion abierta
                //Inicio de transacción
                $link->query("BEGIN;");
                
                $sql = "INSERT INTO ban_deposito_bancario (dep_numdeposito, dep_bancoid, dep_clienteid, dep_descripcion,dep_fecha,dep_numtransaccion,dep_valor) ";
                $sql .= "values ('$deposito', '$banco_id', '$cliente_id', '$descripcion_dep','$fecha','$transaccion','$valor_dep_unmask')";
                $result = $link->query($sql);

                if(!$result){
                    echo "fallo";//"Error en la Transacción 1: ".$link->error;
                    $link->query("ROLLBACK;");           //Terminar la transaccion si hay error
                    exit();
                }
        
              $id_diario = mysqli_insert_id($link);
                
                for($i = 0; $i < count($cuenta); $i++){
                    $sql1 = "INSERT INTO ban_banco_diario(bandia_diario, bandia_id_codigo_cuenta, bandia_detalle_descripcion, bandia_valor, bandia_tipo)";
                    $sql1 .= "values ('$deposito', '$cuenta[$i]', '$descripcion_cuenta[$i]', '$valor[$i]', '$tipo[$i]')"; 
                    $result = $link->query($sql1);
                    if(!$result){
                        echo "fallo";
                        $link->query("ROLLBACK;");    
                        exit();
                    }
                }

               if ($result) {
                    $link->query("COMMIT");      //Terminar la transaccion
                    $query = "SELECT * FROM ban_deposito_bancario WHERE dep_id = '$id_diario'";
                    $result_in = $link->query($query);
                    $num_registros = $result_in->num_rows;
                    if($num_registros > 0){
                        while($row = $result_in->fetch_assoc()){
                            $asientos_contables[] = array(  'id_deposito' => $row['dep_id']);
                        }
                        echo json_encode($asientos_contables);
                    }else{
                        echo "fallo";
                    }
                }  
               $link->query("END;");  
           }
            
        break;*/
}


?>