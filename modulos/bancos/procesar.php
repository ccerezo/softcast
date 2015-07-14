<?php 
    include('../../conexion.php');
 
    $proceso=$_REQUEST["orden"];
	
    switch( $proceso ){
        
    /**************************************************************************/
    /************************* INGRESAR NUEVO BANCO ***************************/
    /**************************************************************************/
    case 'ingresar_banco':
        if(isset($_REQUEST['codigo'])){$codigo = $_REQUEST['codigo'];}
        if(isset($_REQUEST['nombre'])){$nombre = utf8_decode($_REQUEST['nombre']);}
        if(isset($_REQUEST['fecha'])){$fecha = $_REQUEST['fecha'];}
        if(isset($_REQUEST['numero'])){$numero = $_REQUEST['numero'];}
        if(isset($_REQUEST['saldo'])){$saldo = $_REQUEST['saldo'];}
        if(isset($_REQUEST['tipo'])){$tipo = $_REQUEST['tipo'];}
        if(isset($_REQUEST['id_cc'])){$id_cc = $_REQUEST['id_cc'];}

        $sql = "INSERT INTO banco (ban_codigo, ban_nombre, ban_numero_cuenta, ban_tipo, ban_saldo, ban_cod_cuenta_contable, fecha) ";
        $sql .= "VALUES ('$codigo', '$nombre', '$numero', '$tipo', '$saldo', '$id_cc', '$fecha')";
        $result = $link->query($sql);

        if(!$result){
            echo "fallo";//.$link->error;
        }else{
            echo mysqli_insert_id($link);
        }

    break;
        
    case 'editar_banco':

    break;

    /**************************************************************************/
    /******************** CONSULTAR LISTADO DE BANCOS *************************/
    /**************************************************************************/ 
    case 'consultar_bancos':

        $query = "SELECT * FROM banco ORDER BY ban_codigo ASC";
        $result = $link->query($query);
        $contar = $result->num_rows;

        if($contar==0){
          echo 'vacio';
        }else{
          while($row = $result->fetch_assoc()){
            $array[] = array('id_banco' => $row['ban_id'], 
                            'nombre' => utf8_encode($row['ban_nombre']), 
                            'num_cuenta' => utf8_encode($row['ban_numero_cuenta']), 
                            'tipo_cuenta' => $row['ban_tipo'],
                            'saldo' => $row['ban_saldo'],
                            'cta_contable' => $row['ban_cod_cuenta_contable'],
                            'codigo' => $row['ban_codigo']);
          }
          echo json_encode($array);
        }

    break;

    /**************************************************************************/
    /******************* CONSULTAR LISTADO DE CLIENTES ************************/
    /**************************************************************************/ 
    case 'consultar_clientes':

        $query = "SELECT * FROM cliente ORDER BY nombre ASC";
        $result = $link->query($query);
        $contar = $result->num_rows;

        if($contar==0){
          echo 'vacio';
        }else{
          while($row = $result->fetch_assoc()){
            $array[] = array('id_cliente' => $row['id_cliente'], 
                            'nombre' => utf8_encode($row['nombre']), 
                            'direccion' => utf8_encode($row['direccion']), 
                            'identificacion' => $row['identificacion'],
                            'telefono' => $row['telefono']);
          }
          echo json_encode($array);
        }

    break;

    /**************************************************************************/
    /******************** CONSULTAR DEPOSITO BANCARIO *************************/
    /**************************************************************************/ 
    case 'consultar_depositos':
        $query = "SELECT db.dep_id id_deposito, db.dep_numdeposito dep_numdeposito, db.dep_descripcion dep_descripcion,
                    db.dep_fecha dep_fecha, db.dep_valor dep_valor, b.ban_nombre ban_nombre, b.ban_numero_cuenta num_cuenta,
                    b.ban_tipo tipo_cuenta
                    FROM ban_deposito_bancario db
                    INNER JOIN banco b ON b.ban_id = db.dep_bancoid
                    WHERE db.dep_estado = 'activo'";
        $result = $link->query($query);
        $num_registros = $result->num_rows;
        if($num_registros > 0){
            while($row = $result->fetch_assoc()){
                $depositos_bancarios[] = array( 'id_deposito' => $row['id_deposito'],
                                                'num_deposito' => $row['dep_numdeposito'],
                                                'nombre_banco' => $row['ban_nombre'],
                                                'num_cuenta_banco' => $row['num_cuenta'],
                                                'tipo_cuenta_banco' => $row['tipo_cuenta'],
                                                'descripcion' => utf8_encode($row['dep_descripcion']),
                                                'fecha' => $row['dep_fecha'],
                                                'valor' => $row['dep_valor']);
            }
            echo json_encode($depositos_bancarios);
        }else{
            echo 'vacio';
        }
            
    break;
    
    /**************************************************************************/
    /******************** REGISTRAR DEPOSITO BANCARIO *************************/
    /**************************************************************************/
    case 'ingresar_deposito':

        if(isset($_REQUEST['numero'])){$numero = $_REQUEST['numero'];}
        if(isset($_REQUEST['descripcion'])){$descripcion = utf8_decode($_REQUEST['descripcion']);}
        if(isset($_REQUEST['fecha'])){$fecha = $_REQUEST['fecha'];}
        if(isset($_REQUEST['total'])){$total = $_REQUEST['total'];}
        if(isset($_REQUEST['banco'])){$banco = $_REQUEST['banco'];}
        if(isset($_REQUEST['cliente'])){$cliente = $_REQUEST['cliente'];}
        if(isset($_REQUEST['datos'])){
            $parametros = json_decode($_REQUEST['datos'],true);
        }

        $link->query("SET AUTOCOMMIT=0;"); //Para InnoDB, sirve para mantener la transaccion abierta
        //Inicio de transaccion
        $link->query("BEGIN;");

        $sql = "INSERT INTO ban_deposito_bancario (dep_numdeposito, dep_bancoid, dep_clienteid, dep_descripcion, dep_fecha,dep_valor)";
        $sql .= "VALUES ('$numero', '$banco', '$cliente', '$descripcion','$fecha','$total')";
        $result = $link->query($sql);

        if(!$result){
            echo "fallo";//"Error en la Transacci�n 1: ".$link->error;
            $link->query("ROLLBACK;");           //Terminar la transaccion si hay error
            exit();
        }

        $id_deposito = mysqli_insert_id($link);

        for($i = 0; $i < count($parametros); $i++){
            $cuenta = $parametros[$i]["cuenta"];
            $descripcion_detalle = utf8_decode($parametros[$i]["descripcion_detalle"]);
            $valor_detalle = $parametros[$i]["valor_detalle"];
            $tipo = $parametros[$i]["tipo_detalle"];
            $sql1 = "INSERT INTO cont_detalle_asiento_diario(cont_num_asiento_detalle, cont_id_codigo_cuenta, cont_detalle_descripcion, cont_valor, cont_tipo)";
            $sql1 .= "VALUES ('$numero', '$cuenta', '$descripcion_detalle', $valor_detalle, '$tipo')"; 
            $result = $link->query($sql1);
            if(!$result){
                echo "fallo";// Error en la Transacción 2: ".$link->error;
                $link->query("ROLLBACK;");    //Terminar la transaccion si hay error
                exit();
            }
        }
        if ($result) {
             $link->query("COMMIT");      //Terminar la transaccion
             $query = "SELECT * FROM ban_deposito_bancario WHERE dep_id = '$id_deposito'";
             $result_in = $link->query($query);
             $num_registros = $result_in->num_rows;
             if($num_registros > 0){
                 while($row = $result_in->fetch_assoc()){
                     $registro[] = array(  'id_deposito' => $row['dep_id']);
                 }
                 echo json_encode($registro);
             }else{
                 echo "fallo";
             }
        }  
        $link->query("END;");  

    break;
          
    /**************************************************************************/
    /******************** CONSULTAR EGRESOS BANCARIOS *************************/
    /**************************************************************************/ 
    case 'consultar_egresos':
        $query = "SELECT eb.egre_id id_egreso, eb.egre_numegreso egre_numegreso, eb.egre_descripcion descripcion,
                    eb.egre_fecha fecha, eb.egre_valor valor, b.ban_nombre ban_nombre, b.ban_numero_cuenta num_cuenta,
                    b.ban_tipo tipo_cuenta
                    FROM ban_egreso_bancario eb
                    INNER JOIN banco b ON b.ban_id = eb.egre_bancoid
                    WHERE eb.egre_estado = 'activo'";
        $result = $link->query($query);
        $num_registros = $result->num_rows;
        if($num_registros > 0){
            while($row = $result->fetch_assoc()){
                $egresos_bancarios[] = array( 'id_egreso' => $row['id_egreso'],
                                                'num_egreso' => $row['egre_numegreso'],
                                                'nombre_banco' => $row['ban_nombre'],
                                                'num_cuenta_banco' => $row['num_cuenta'],
                                                'tipo_cuenta_banco' => $row['tipo_cuenta'],
                                                'descripcion' => utf8_encode($row['descripcion']),
                                                'fecha' => $row['fecha'],
                                                'valor' => $row['valor']);
            }
            echo json_encode($egresos_bancarios);
        }else{
            echo 'vacio';
        }
            
    break;
    
    /**************************************************************************/
    /****************** CONSULTAR LISTADO DE PROVEEDORES **********************/
    /**************************************************************************/ 
    case 'consultar_proveedores':

        $query = "SELECT * FROM pag_maestro_proveedor ORDER BY nombre ASC";
        $result = $link->query($query);
        $contar = $result->num_rows;

        if($contar==0){
          echo 'vacio';
        }else{
          while($row = $result->fetch_assoc()){
            $array[] = array('id_proveedor' => $row['id'], 
                            'nombre' => utf8_encode($row['nombre']), 
                            'codigo' => utf8_encode($row['codigo']), 
                            'identificacion' => $row['ruc'],
                            'telefono' => $row['telefono']);
          }
          echo json_encode($array);
        }

    break;
    
    /**************************************************************************/
    /********************* REGISTRAR EGRESO BANCARIO **************************/
    /**************************************************************************/
    case 'ingresar_egreso':
   
        if(isset($_REQUEST['numero'])){$numero = $_REQUEST['numero'];}
        if(isset($_REQUEST['descripcion'])){$descripcion = utf8_decode($_REQUEST['descripcion']);}
        if(isset($_REQUEST['beneficiario'])){$beneficiario = utf8_decode($_REQUEST['beneficiario']);}
        if(isset($_REQUEST['fecha'])){$fecha = $_REQUEST['fecha'];}
        if(isset($_REQUEST['total'])){$total = $_REQUEST['total'];}
        if(isset($_REQUEST['banco'])){$banco = $_REQUEST['banco'];}
        if(isset($_REQUEST['proveedor'])){$proveedor = $_REQUEST['proveedor'];}
        if(isset($_REQUEST['datos'])){
            $parametros = json_decode($_REQUEST['datos'],true);
        }
        $link->query("SET AUTOCOMMIT=0;"); //Para InnoDB, sirve para mantener la transaccion abierta
            //Inicio de transaccion
        $link->query("BEGIN;");

        $sql = "INSERT INTO ban_egreso_bancario (egre_numegreso, egre_bancoid, egre_proveedorid, egre_beneficiario, egre_chequeid, egre_descripcion, egre_fecha, egre_valor)";
        $sql .= "VALUES ('$numero', '$banco', '$proveedor', '$beneficiario', '223', '$descripcion','$fecha','$total')";
        $result = $link->query($sql);

        if(!$result){
            echo "fallo";//"Error en la Transacci�n 1: ".$link->error;
            $link->query("ROLLBACK;");           //Terminar la transaccion si hay error
            exit();
        }

        $id_egreso = mysqli_insert_id($link);
                
        for($i = 0; $i < count($parametros); $i++){
            $cuenta = $parametros[$i]["cuenta"];
            $descripcion_detalle = utf8_decode($parametros[$i]["descripcion_detalle"]);
            $valor_detalle = $parametros[$i]["valor_detalle"];
            $tipo = $parametros[$i]["tipo_detalle"];
            $sql1 = "INSERT INTO cont_detalle_asiento_diario(cont_num_asiento_detalle, cont_id_codigo_cuenta, cont_detalle_descripcion, cont_valor, cont_tipo)";
            $sql1 .= "VALUES ('$numero', '$cuenta', '$descripcion_detalle', $valor_detalle, '$tipo')"; 
            $result = $link->query($sql1);
            if(!$result){
                echo "fallo";// Error en la Transacción 2: ".$link->error;
                $link->query("ROLLBACK;");    //Terminar la transaccion si hay error
                exit();
            }
        }
        if ($result) {
             $link->query("COMMIT");      //Terminar la transaccion
             $query = "SELECT * FROM ban_egreso_bancario WHERE egre_id = '$id_egreso'";
             $result_in = $link->query($query);
             $num_registros = $result_in->num_rows;
             if($num_registros > 0){
                 while($row = $result_in->fetch_assoc()){
                     $registro[] = array(  'id_egreso' => $row['egre_id']);
                 }
                 echo json_encode($registro);
             }else{
                 echo "fallo";
             }
        }  
        $link->query("END;");  
    break;
    
    /**************************************************************************/
    /********************* CONSULTAR NOTA DE CREDITO **************************/
    /**************************************************************************/ 
    case 'consultar_notas_credito':
        $query = "SELECT nc.cre_id id_credito, nc.cre_numcredito num_credito, nc.cre_descripcion descripcion,
                    nc.cre_fecha fecha, nc.cre_valor valor, b.ban_nombre ban_nombre, b.ban_numero_cuenta num_cuenta,
                    b.ban_tipo tipo_cuenta
                    FROM ban_nota_credito nc
                    INNER JOIN banco b ON b.ban_id = nc.cre_bancoid
                    WHERE nc.cre_estado = 'activo'";
        $result = $link->query($query);
        $num_registros = $result->num_rows;
        if($num_registros > 0){
            while($row = $result->fetch_assoc()){
                $creditos_bancarios[] = array( 'id_credito' => $row['id_credito'],
                                                'num_credito' => $row['num_credito'],
                                                'nombre_banco' => $row['ban_nombre'],
                                                'num_cuenta_banco' => $row['num_cuenta'],
                                                'tipo_cuenta_banco' => $row['tipo_cuenta'],
                                                'descripcion' => utf8_encode($row['descripcion']),
                                                'fecha' => $row['fecha'],
                                                'valor' => $row['valor']);
            }
            echo json_encode($creditos_bancarios);
        }else{
            echo 'vacio';
        }
            
    break;
    
    /**************************************************************************/
    /********************* REGISTRAR NOTA DE CREDITO **************************/
    /**************************************************************************/
    case 'ingresar_nc':

        if(isset($_REQUEST['numero'])){$numero = $_REQUEST['numero'];}
        if(isset($_REQUEST['descripcion'])){$descripcion = utf8_decode($_REQUEST['descripcion']);}
        if(isset($_REQUEST['fecha'])){$fecha = $_REQUEST['fecha'];}
        if(isset($_REQUEST['total'])){$total = $_REQUEST['total'];}
        if(isset($_REQUEST['banco'])){$banco = $_REQUEST['banco'];}
        if(isset($_REQUEST['cliente'])){$cliente = $_REQUEST['cliente'];}
        if(isset($_REQUEST['datos'])){
            $parametros = json_decode($_REQUEST['datos'],true);
        }

        $link->query("SET AUTOCOMMIT=0;"); //Para InnoDB, sirve para mantener la transaccion abierta
        //Inicio de transaccion
        $link->query("BEGIN;");

        $sql = "INSERT INTO ban_nota_credito (cre_numcredito, cre_bancoid, cre_clienteid, cre_descripcion, cre_fecha, cre_valor)";
        $sql .= "VALUES ('$numero', '$banco', '$cliente', '$descripcion','$fecha','$total')";
        $result = $link->query($sql);

        if(!$result){
            echo "fallo";//"Error en la Transacci�n 1: ".$link->error;
            $link->query("ROLLBACK;");           //Terminar la transaccion si hay error
            exit();
        }

        $id_nc = mysqli_insert_id($link);

        for($i = 0; $i < count($parametros); $i++){
            $cuenta = $parametros[$i]["cuenta"];
            $descripcion_detalle = utf8_decode($parametros[$i]["descripcion_detalle"]);
            $valor_detalle = $parametros[$i]["valor_detalle"];
            $tipo = $parametros[$i]["tipo_detalle"];
            $sql1 = "INSERT INTO cont_detalle_asiento_diario(cont_num_asiento_detalle, cont_id_codigo_cuenta, cont_detalle_descripcion, cont_valor, cont_tipo)";
            $sql1 .= "VALUES ('$numero', '$cuenta', '$descripcion_detalle', $valor_detalle, '$tipo')"; 
            $result = $link->query($sql1);
            if(!$result){
                echo "fallo";// Error en la Transacción 2: ".$link->error;
                $link->query("ROLLBACK;");    //Terminar la transaccion si hay error
                exit();
            }
        }
        if ($result) {
             $link->query("COMMIT");      //Terminar la transaccion
             $query = "SELECT * FROM ban_nota_credito WHERE  cre_id = '$id_nc'";
             $result_in = $link->query($query);
             $num_registros = $result_in->num_rows;
             if($num_registros > 0){
                 while($row = $result_in->fetch_assoc()){
                     $registro[] = array(  'id_nc' => $row['cre_id']);
                 }
                 echo json_encode($registro);
             }else{
                 echo "fallo";
             }
        }  
        $link->query("END;");  

    break;
    
    /**************************************************************************/
    /********************* CONSULTAR NOTA DE DEBITO ***************************/
    /**************************************************************************/ 
    case 'consultar_notas_debito':
        $query = "SELECT nd.deb_id id_debito, nd.deb_numdebito num_debito, nd.deb_descripcion descripcion,
                    nd.deb_fecha fecha, nd.deb_valor valor, b.ban_nombre ban_nombre, b.ban_numero_cuenta num_cuenta,
                    b.ban_tipo tipo_cuenta
                    FROM ban_nota_debito nd
                    INNER JOIN banco b ON b.ban_id = nd.deb_bancoid
                    WHERE nd.deb_estado = 'activo'";
        $result = $link->query($query);
        $num_registros = $result->num_rows;
        if($num_registros > 0){
            while($row = $result->fetch_assoc()){
                $debitos_bancarios[] = array( 'id_debito' => $row['id_debito'],
                                                'num_debito' => $row['num_debito'],
                                                'nombre_banco' => utf8_encode($row['ban_nombre']),
                                                'num_cuenta_banco' => $row['num_cuenta'],
                                                'tipo_cuenta_banco' => $row['tipo_cuenta'],
                                                'descripcion' => utf8_encode($row['descripcion']),
                                                'fecha' => $row['fecha'],
                                                'valor' => $row['valor']);
            }
            echo json_encode($debitos_bancarios);
        }else{
            echo 'vacio';
        }
            
    break;
    
    /**************************************************************************/
    /********************* REGISTRAR NOTA DE DEBITO ***************************/
    /**************************************************************************/
    case 'ingresar_nd':
   
        if(isset($_REQUEST['numero'])){$numero = $_REQUEST['numero'];}
        if(isset($_REQUEST['descripcion'])){$descripcion = utf8_decode($_REQUEST['descripcion']);}
        if(isset($_REQUEST['fecha'])){$fecha = $_REQUEST['fecha'];}
        if(isset($_REQUEST['total'])){$total = $_REQUEST['total'];}
        if(isset($_REQUEST['banco'])){$banco = $_REQUEST['banco'];}
        if(isset($_REQUEST['proveedor'])){$proveedor = $_REQUEST['proveedor'];}
        if(isset($_REQUEST['datos'])){
            $parametros = json_decode($_REQUEST['datos'],true);
        }
        $link->query("SET AUTOCOMMIT=0;"); //Para InnoDB, sirve para mantener la transaccion abierta
            //Inicio de transaccion
        $link->query("BEGIN;");

        $sql = "INSERT INTO ban_nota_debito (deb_numdebito, deb_bancoid, deb_proveedorid, deb_descripcion, deb_fecha, deb_valor)";
        $sql .= "VALUES ('$numero', '$banco', '$proveedor', '$descripcion','$fecha','$total')";
        $result = $link->query($sql);

        if(!$result){
            echo "fallo";//"Error en la Transacci�n 1: ".$link->error;
            $link->query("ROLLBACK;");           //Terminar la transaccion si hay error
            exit();
        }

        $id_debito = mysqli_insert_id($link);
                
        for($i = 0; $i < count($parametros); $i++){
            $cuenta = $parametros[$i]["cuenta"];
            $descripcion_detalle = utf8_decode($parametros[$i]["descripcion_detalle"]);
            $valor_detalle = $parametros[$i]["valor_detalle"];
            $tipo = $parametros[$i]["tipo_detalle"];
            $sql1 = "INSERT INTO cont_detalle_asiento_diario(cont_num_asiento_detalle, cont_id_codigo_cuenta, cont_detalle_descripcion, cont_valor, cont_tipo)";
            $sql1 .= "VALUES ('$numero', '$cuenta', '$descripcion_detalle', $valor_detalle, '$tipo')"; 
            $result = $link->query($sql1);
            if(!$result){
                echo "fallo";// Error en la Transacción 2: ".$link->error;
                $link->query("ROLLBACK;");    //Terminar la transaccion si hay error
                exit();
            }
        }
        if ($result) {
             $link->query("COMMIT");      //Terminar la transaccion
             $query = "SELECT * FROM ban_nota_debito WHERE deb_id = '$id_debito'";
             $result_in = $link->query($query);
             $num_registros = $result_in->num_rows;
             if($num_registros > 0){
                 while($row = $result_in->fetch_assoc()){
                     $registro[] = array(  'id_debito' => $row['deb_id']);
                 }
                 echo json_encode($registro);
             }else{
                 echo "fallo";
             }
        }  
        $link->query("END;");  
    break;
    
    case 'ingresar_trans_recibida':

 if(isset($_REQUEST['filas'])){
                $filas = $_REQUEST['filas'];
            }
   
     if(isset($_REQUEST['datos'])){
                $parametros = json_decode($_REQUEST['datos'],true);
                
                $i=0;
                foreach($parametros as $array){
                    if($i==0){
                        $recibida = $array["value"];
                    }
                        
                    if($i==1)
                        $fecha = utf8_decode($array["value"]);
                        if($i==2)
                        $banco_id = utf8_decode($array["value"]);
                       
                    if($i==3)
                        $valor_recibido= utf8_decode($array["value"]);
                      if($i==4)
                        $valor_recibido_unmask = utf8_decode($array["value"]);
                     
                    if($i==5)
                        $nom_cliente = utf8_decode($array["value"]);
                    if($i==6)
                        $descripcion_recibido = utf8_decode($array["value"]);
                    if($i==7)
                        $diferencia = utf8_decode($array["value"]);
                    if($i==8 && $array["value"]!=NULL)
                        $cuenta[] = $array["value"];
                    if($i==9 && $array["value"]!=NULL)
                        $codigo_cuenta[] = $array["value"];
                    if($i==10 && $array["value"]!=NULL)
                        $nombre_cuenta[] = utf8_decode($array["value"]);
                    if($i==11 && $array["value"]!=NULL)
                        $descripcion_cuenta[] = utf8_decode($array["value"]);
                     if($i==12 && $array["value"]!=NULL)
                        $valor_mask[] = utf8_decode($array["value"]);
                    if($i==13 && $array["value"]!=NULL){
                        $valor[] =  str_replace(",","",$array["value"]);
                        $tipo[] = substr($array["name"],7,1);
                    }
                        
                    if($i === 13)
                        $i=8;
                    else
                        $i++;
                }
                
                 // echo json_encode($deposito.' '.$banco_id.' '.$cliente_id.' '.$descripcion_dep.' '.$fecha.' '.$transaccion.' '.$valor_dep_unmask.' '.$cuenta[0].$cuenta[1].$descripcion_cuenta[0].$descripcion_cuenta[1].$valor[0].$valor[1].$tipo[0].$tipo[1]);
       
          
              $link->query("SET AUTOCOMMIT=0;"); //Para InnoDB, sirve para mantener la transaccion abierta
                //Inicio de transacci�n
                $link->query("BEGIN;");
                
                $sql = "INSERT INTO ban_transferencia_recibida (tra_rec_numtransferencia, tra_rec_bancoid, tra_rec_clienteid, tra_rec_descripcion,tra_rec_fecha,tra_rec_valor) ";
                $sql .= "values ('$recibida', '$banco_id', '$nom_cliente', '$descripcion_recibido','$fecha','$valor_recibido_unmask')";
                $result = $link->query($sql);

                if(!$result){
                    echo "fallo";//"Error en la Transacci�n 1: ".$link->error;
                    $link->query("ROLLBACK;");           //Terminar la transaccion si hay error
                    exit();
                }
        
              $id_diario = mysqli_insert_id($link);
                
                for($i = 0; $i < count($cuenta); $i++){
                  //  $sql1 = "INSERT INTO ban_banco_diario(bandia_diario, bandia_id_codigo_cuenta, bandia_detalle_descripcion, bandia_valor, bandia_tipo)";
                     $sql1 = "INSERT INTO cont_detalle_asiento_diario(cont_id_asiento_diario, cont_id_codigo_cuenta, cont_detalle_descripcion, cont_valor, cont_tipo)";
                    $sql1 .= "values ('$recibida', '$cuenta[$i]', '$descripcion_cuenta[$i]', '$valor[$i]', '$tipo[$i]')"; 
                    $result = $link->query($sql1);
                    if(!$result){
                        echo "fallo";
                        $link->query("ROLLBACK;");    
                        exit();
                    }
                }

               if ($result) {
                    $link->query("COMMIT");      //Terminar la transaccion
                    $query = "SELECT * FROM ban_transferencia_recibida WHERE tra_rec_id = '$id_diario'";
                    $result_in = $link->query($query);
                    $num_registros = $result_in->num_rows;
                    if($num_registros > 0){
                        while($row = $result_in->fetch_assoc()){
                            $asientos_contables[] = array(  'id_recibido' => $row['tra_rec_id']);
                        }
                        echo json_encode($asientos_contables);
                    }else{
                        echo "fallo";
                    }
                }  
               $link->query("END;");  
           }


          
          break;
          

    case 'ingresar_trans_enviada':

 if(isset($_REQUEST['filas'])){
                $filas = $_REQUEST['filas'];
            }
   
     if(isset($_REQUEST['datos'])){
                $parametros = json_decode($_REQUEST['datos'],true);
                
                $i=0;
                foreach($parametros as $array){
                    if($i==0){
                        $enviada = $array["value"];
                    }
                        
                    if($i==1)
                        $fecha = utf8_decode($array["value"]);
                        if($i==2)
                        $banco_id = utf8_decode($array["value"]);
                       
                    if($i==3)
                        $valor_enviado= utf8_decode($array["value"]);
                      if($i==4)
                        $valor_enviado_unmask = utf8_decode($array["value"]);
                     
                    if($i==5)
                        $nom_cliente = utf8_decode($array["value"]);
                    if($i==6)
                        $descripcion_enviado = utf8_decode($array["value"]);
                    if($i==7)
                        $diferencia = utf8_decode($array["value"]);
                    if($i==8 && $array["value"]!=NULL)
                        $cuenta[] = $array["value"];
                    if($i==9 && $array["value"]!=NULL)
                        $codigo_cuenta[] = $array["value"];
                    if($i==10 && $array["value"]!=NULL)
                        $nombre_cuenta[] = utf8_decode($array["value"]);
                    if($i==11 && $array["value"]!=NULL)
                        $descripcion_cuenta[] = utf8_decode($array["value"]);
                     if($i==12 && $array["value"]!=NULL)
                        $valor_mask[] = utf8_decode($array["value"]);
                    if($i==13 && $array["value"]!=NULL){
                        $valor[] =  str_replace(",","",$array["value"]);
                        $tipo[] = substr($array["name"],7,1);
                    }
                        
                    if($i === 13)
                        $i=8;
                    else
                        $i++;
                }
                
                 // echo json_encode($deposito.' '.$banco_id.' '.$cliente_id.' '.$descripcion_dep.' '.$fecha.' '.$transaccion.' '.$valor_dep_unmask.' '.$cuenta[0].$cuenta[1].$descripcion_cuenta[0].$descripcion_cuenta[1].$valor[0].$valor[1].$tipo[0].$tipo[1]);
       
          
              $link->query("SET AUTOCOMMIT=0;"); //Para InnoDB, sirve para mantener la transaccion abierta
                //Inicio de transacci�n
                $link->query("BEGIN;");
                
                $sql = "INSERT INTO ban_transferencia_enviada (tra_env_numtransferencia, tra_env_bancoid, tra_env_proveedorid, tra_env_descripcion,tra_env_fecha,tra_env_valor) ";
                $sql .= "values ('$enviada', '$banco_id', '$nom_cliente', '$descripcion_enviado','$fecha','$valor_enviado_unmask')";
                $result = $link->query($sql);

                if(!$result){
                    echo "fallo";//"Error en la Transacci�n 1: ".$link->error;
                    $link->query("ROLLBACK;");           //Terminar la transaccion si hay error
                    exit();
                }
        
              $id_diario = mysqli_insert_id($link);
                
                for($i = 0; $i < count($cuenta); $i++){
                  //  $sql1 = "INSERT INTO ban_banco_diario(bandia_diario, bandia_id_codigo_cuenta, bandia_detalle_descripcion, bandia_valor, bandia_tipo)";
                     $sql1 = "INSERT INTO cont_detalle_asiento_diario(cont_id_asiento_diario, cont_id_codigo_cuenta, cont_detalle_descripcion, cont_valor, cont_tipo)";
                    $sql1 .= "values ('$enviada', '$cuenta[$i]', '$descripcion_cuenta[$i]', '$valor[$i]', '$tipo[$i]')"; 
                    $result = $link->query($sql1);
                    if(!$result){
                        echo "fallo";
                        $link->query("ROLLBACK;");    
                        exit();
                    }
                }

               if ($result) {
                    $link->query("COMMIT");      //Terminar la transaccion
                    $query = "SELECT * FROM ban_transferencia_enviada WHERE tra_env_id = '$id_diario'";
                    $result_in = $link->query($query);
                    $num_registros = $result_in->num_rows;
                    if($num_registros > 0){
                        while($row = $result_in->fetch_assoc()){
                            $asientos_contables[] = array(  'id_enviado' => $row['tra_env_id']);
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

    case 'ultimo_diario_mes_debito':
            $fecha = $_POST['mes'];
            $query = "SELECT * FROM ban_nota_debito WHERE deb_numdebito like '%ND-".$fecha."%' ORDER BY deb_id DESC LIMIT 1";
            $result = $link->query($query);
            $num_registros = $result->num_rows;
            if($num_registros > 0){
                while($row = $result->fetch_assoc()){
                    $ultimo_asiento = $row['deb_numdebito'];
                }
                echo $ultimo_asiento;
            }else{
                echo '0';
            }
        break;

    case 'ultimo_diario_mes_credito':
        $fecha = $_POST['mes'];
        $query = "SELECT * FROM ban_nota_credito WHERE cre_numcredito like '%NC-".$fecha."%' ORDER BY cre_id DESC LIMIT 1";
        $result = $link->query($query);
        $num_registros = $result->num_rows;
        if($num_registros > 0){
            while($row = $result->fetch_assoc()){
                $ultimo_asiento = $row['cre_numcredito'];
            }
            echo $ultimo_asiento;
        }else{
            echo '0';
        }
    break;

 case 'ultimo_diario_mes_trans_rec':
            $fecha = $_POST['mes'];
            $query = "SELECT * FROM ban_transferencia_recibida WHERE tra_rec_numtransferencia like '%TR-".$fecha."%' ORDER BY tra_rec_id DESC LIMIT 1";
            $result = $link->query($query);
            $num_registros = $result->num_rows;
            if($num_registros > 0){
                while($row = $result->fetch_assoc()){
                    $ultimo_asiento = $row['tra_rec_numtransferencia'];
                }
                echo $ultimo_asiento;
            }else{
                echo '0';
            }
        break;

         case 'ultimo_diario_mes_trans_env':
            $fecha = $_POST['mes'];
            $query = "SELECT * FROM ban_transferencia_enviada WHERE tra_env_numtransferencia like '%TE-".$fecha."%' ORDER BY tra_env_id DESC LIMIT 1";
            $result = $link->query($query);
            $num_registros = $result->num_rows;
            if($num_registros > 0){
                while($row = $result->fetch_assoc()){
                    $ultimo_asiento = $row['tra_env_numtransferencia'];
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
}


?>