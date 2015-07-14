<?php
    include('../../conexion.php');
    include('../../redireccionar.php');
    $id = $_GET['id'];
    $fechainicio = $_GET['inicio'];
    $fechafin = $_GET['fin'];

$query_banco="SELECT ban_saldo FROM banco where ban_id='$id'";
$result_banco=$link->query($query_banco);
while($row = $result_banco->fetch_assoc()){
            
       $saldo_inicio=$row['ban_saldo'];
       
    }


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


$query_nota_debito_anteriores = "SELECT ban . * , deb . * , pro . *FROM (banco ban, ban_nota_debito deb, pag_maestro_proveedor pro)WHERE ban.ban_id =  '$id'
AND deb.deb_fecha
BETWEEN DATE(  '2010-01-01' ) 
AND DATE_ADD('$fechainicio', INTERVAL -1 DAY)
AND ban.ban_id = deb.deb_bancoid
And deb.deb_proveedorid=pro.ID";

$result_debito_ante = $link->query($query_nota_debito_anteriores);
    $num_registros_debito_ante = $result_debito_ante->num_rows;
 

$query_nota_credito_anteriores = "SELECT ban . * , cre . * , cli . *FROM (banco ban, ban_nota_credito cre, cliente cli)WHERE ban.ban_id =  '$id'
AND cre.cre_fecha
BETWEEN DATE(  '2010-01-01' ) 
AND DATE_ADD('$fechainicio', INTERVAL -1 DAY)
AND ban.ban_id = cre.cre_bancoid
And cre.cre_clienteid=cli.id";

$result_credito_ante = $link->query($query_nota_credito_anteriores);
    $num_registros_credito_ante = $result_credito_ante->num_rows;

$query_trans_rec_anteriores = "SELECT ban . * , rec . * , cli . *FROM (banco ban, ban_transferencia_recibida rec, cliente cli)WHERE ban.ban_id =  '$id'
AND rec.tra_rec_fecha
BETWEEN DATE(  '2010-01-01' ) 
AND DATE_ADD('$fechainicio', INTERVAL -1 DAY)
AND ban.ban_id = rec.tra_rec_bancoid
And rec.tra_rec_clienteid=cli.id";

$result_tra_rec_ante = $link->query($query_trans_rec_anteriores);
    $num_registros_tra_rec_ante = $result_tra_rec_ante->num_rows;

$query_trans_env_anteriores = "SELECT ban . * , env . * , pro . *FROM (banco ban, ban_transferencia_enviada env, pag_maestro_proveedor pro)WHERE ban.ban_id =  '$id'
AND env.tra_env_fecha
BETWEEN DATE(  '2010-01-01' ) 
AND DATE_ADD('$fechainicio', INTERVAL -1 DAY)
AND ban.ban_id = env.tra_env_bancoid
And env.tra_env_proveedorid=pro.ID";

$result_tra_env_ante = $link->query($query_trans_env_anteriores);
    $num_registros_tra_env_ante = $result_tra_env_ante->num_rows;

   
if($num_registros_dep_ante > 0 || $num_registros_egre_ante > 0 || $num_registros_debito_ante > 0 || $num_registros_credito_ante > 0 || $num_registros_tra_rec_ante > 0 || $num_registros_tra_env_ante > 0  ){
    $sum_ante[0]=$saldo_inicio;
   /* $k=1;
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
    }*/
    $k=1;
    $suma_depositos[0]=0;
 
    while($row = $result_dep_ante->fetch_assoc()){
        $suma_depositos[$k]=$suma_depositos[$k-1]+$row['dep_valor'];
        $k++;
    }
$tamano_depositos= count($suma_depositos);

    $j=1;
    $suma_egresos[0]=0;
 
    while($row = $result_egre_ante->fetch_assoc()){
        $suma_egresos[$k]=$suma_egresos[$k-1]+$row['egre_valor'];
        $j++;
    }
$tamano_egresos= count($suma_egresos);

    $l=1;
    $suma_debitos[0]=0;
    while($row = $result_debito_ante->fetch_assoc()){
        $suma_debitos[$l]=$suma_debitos[$l-1]+$row['deb_valor'];
        $l++;
    }
$tamano_debitos= count($suma_debitos);

    $m=1;
    $suma_creditos[0]=0;
    while($row = $result_credito_ante->fetch_assoc()){
        $suma_creditos[$m]=$suma_creditos[$m-1]+$row['cre_valor'];
        $m++;
    }
    $tamano_creditos= count($suma_creditos);

    $n=1;
    $suma_tra_recibidas[0]=0;
    while($row = $result_tra_rec_ante->fetch_assoc()){
        $suma_tra_recibidas[$n]=$suma_tra_recibidas[$n-1]+$row['tra_rec_valor'];
        $n++;
    }
    $tamano_tra_recibidas= count($suma_tra_recibidas);

  $o=1;
    $suma_tra_enviadas[0]=0;
    while($row = $result_tra_env_ante->fetch_assoc()){
        $suma_tra_enviadas[$o]=$suma_tra_enviadas[$o-1]+$row['tra_env_valor'];
        $o++;
    }
    $tamano_tra_enviadas= count($suma_tra_enviadas);

$total_depositos=$suma_depositos[$tamano_depositos-1];
$total_egresos=$suma_egresos[$tamano_egresos-1];
$total_debitos=$suma_debitos[$tamano_debitos-1];
$total_creditos=$suma_creditos[$tamano_creditos-1];
$total_tra_recibidas=$suma_tra_recibidas[$tamano_tra_recibidas-1];
$total_tra_enviadas=$suma_tra_enviadas[$tamano_tra_enviadas-1];

$total=$total_depositos-$total_egresos+$total_creditos-$total_debitos+$total_tra_recibidas-$total_tra_enviadas+$saldo_inicio;

   $saldo=number_format($total,2);



}else{
    $saldo=$saldo_inicio;

} 
 


/** 
(SELECT ban.ban_nombre AS banco,ban.ban_tipo AS tipo, ban.ban_numero_cuenta AS CUENTA, dep.dep_numdeposito AS transaccion,dep.dep_fecha AS date,dep.dep_descripcion AS descripcion,dep.dep_valor AS valor , cli.nombre AS persona FROM (banco ban, ban_deposito_bancario dep, cliente cli)WHERE ban.ban_id =  '199'
AND dep.dep_fecha
BETWEEN DATE(  '2010-01-01' ) 
AND DATE(  '2015-07-01' ) 
AND ban.ban_id = dep.dep_bancoid
AND dep.dep_clienteid=cli.id) UNION  
(SELECT ban.ban_nombre AS banco,ban.ban_tipo AS tipo, ban.ban_numero_cuenta AS CUENTA, egre.egre_numegreso AS transaccion,egre.egre_fecha AS date,egre.egre_descripcion AS descripcion,egre.egre_valor AS valor , pro.NOMBRE AS persona FROM (banco ban, ban_egreso_bancario egre, pag_maestro_proveedor pro)WHERE ban.ban_id =  '199'
AND egre.egre_fecha
BETWEEN DATE(  '2010-01-01' ) 
AND DATE(  '2015-07-01' ) 
AND ban.ban_id = egre.egre_bancoid
And egre.egre_proveedorid=pro.ID ) order by date DESC
**/

/** 
(SELECT ban.ban_nombre AS banco,ban.ban_tipo AS tipo, ban.ban_numero_cuenta AS CUENTA, dep.dep_numdeposito AS transaccion,dep.dep_fecha AS date,dep.dep_descripcion AS descripcion,dep.dep_valor AS valor , cli.nombre AS persona FROM (banco ban, ban_deposito_bancario dep, cliente cli)WHERE ban.ban_id =  '199'
AND dep.dep_fecha
BETWEEN DATE(  '2010-01-01' ) 
AND DATE(  '2015-07-01' ) 
AND ban.ban_id = dep.dep_bancoid
AND dep.dep_clienteid=cli.id) UNION  
(SELECT ban.ban_nombre AS banco,ban.ban_tipo AS tipo, ban.ban_numero_cuenta AS CUENTA, egre.egre_numegreso AS transaccion,egre.egre_fecha AS date,egre.egre_descripcion AS descripcion,egre.egre_valor AS valor , pro.NOMBRE AS persona FROM (banco ban, ban_egreso_bancario egre, pag_maestro_proveedor pro)WHERE ban.ban_id =  '199'
AND egre.egre_fecha
BETWEEN DATE(  '2010-01-01' ) 
AND DATE(  '2015-07-01' ) 
AND ban.ban_id = egre.egre_bancoid
And egre.egre_proveedorid=pro.ID ) UNION
(SELECT ban.ban_nombre AS banco,ban.ban_tipo AS tipo, ban.ban_numero_cuenta AS CUENTA, deb.deb_numdebito AS transaccion,deb.deb_fecha AS date,deb.deb_descripcion AS descripcion,deb.deb_valor AS valor , pro.NOMBRE AS persona FROM (banco ban, ban_nota_debito deb, pag_maestro_proveedor pro)WHERE ban.ban_id =  '199'
AND deb.deb_fecha
BETWEEN DATE(  '2010-01-01' ) 
AND DATE(  '2015-07-01' ) 
AND ban.ban_id = deb.deb_bancoid
AND deb.deb_proveedorid=pro.ID) UNION
(SELECT ban.ban_nombre AS banco,ban.ban_tipo AS tipo, ban.ban_numero_cuenta AS CUENTA, cre.cre_numcredito AS transaccion,cre.cre_fecha AS date,cre.cre_descripcion AS descripcion,cre.cre_valor AS valor , cli.nombre AS persona FROM (banco ban, ban_nota_credito cre, cliente cli)WHERE ban.ban_id =  '199'
AND cre.cre_fecha
BETWEEN DATE(  '2010-01-01' ) 
AND DATE(  '2015-07-01' ) 
AND ban.ban_id = cre.cre_bancoid
AND cre.cre_clienteid=cli.id) order by date ASC
**/

/** 

(SELECT ban.ban_nombre AS banco,ban.ban_tipo AS tipo, ban.ban_numero_cuenta AS cuenta, dep.dep_numdeposito AS transaccion,dep.dep_fecha AS date,dep.dep_descripcion AS descripcion,dep.dep_valor AS valor , cli.nombre AS persona FROM (banco ban, ban_deposito_bancario dep, cliente cli)WHERE ban.ban_id =  '199'
AND dep.dep_fecha
BETWEEN DATE(  '2010-01-01' ) 
AND DATE(  '2015-07-01' ) 
AND ban.ban_id = dep.dep_bancoid
AND dep.dep_clienteid=cli.id) UNION  
(SELECT ban.ban_nombre AS banco,ban.ban_tipo AS tipo, ban.ban_numero_cuenta AS cuenta, egre.egre_numegreso AS transaccion,egre.egre_fecha AS date,egre.egre_descripcion AS descripcion,egre.egre_valor AS valor , pro.NOMBRE AS persona FROM (banco ban, ban_egreso_bancario egre, pag_maestro_proveedor pro)WHERE ban.ban_id =  '199'
AND egre.egre_fecha
BETWEEN DATE(  '2010-01-01' ) 
AND DATE(  '2015-07-01' ) 
AND ban.ban_id = egre.egre_bancoid
And egre.egre_proveedorid=pro.ID ) UNION
(SELECT ban.ban_nombre AS banco,ban.ban_tipo AS tipo, ban.ban_numero_cuenta AS CUENTA, deb.deb_numdebito AS transaccion,deb.deb_fecha AS date,deb.deb_descripcion AS descripcion,deb.deb_valor AS valor , pro.NOMBRE AS persona FROM (banco ban, ban_nota_debito deb, pag_maestro_proveedor pro)WHERE ban.ban_id =  '199'
AND deb.deb_fecha
BETWEEN DATE(  '2010-01-01' ) 
AND DATE(  '2015-07-01' ) 
AND ban.ban_id = deb.deb_bancoid
AND deb.deb_proveedorid=pro.ID) UNION
(SELECT ban.ban_nombre AS banco,ban.ban_tipo AS tipo, ban.ban_numero_cuenta AS CUENTA, cre.cre_numcredito AS transaccion,cre.cre_fecha AS date,cre.cre_descripcion AS descripcion,cre.cre_valor AS valor , cli.nombre AS persona FROM (banco ban, ban_nota_credito cre, cliente cli)WHERE ban.ban_id =  '199'
AND cre.cre_fecha
BETWEEN DATE(  '2010-01-01' ) 
AND DATE(  '2015-07-01' ) 
AND ban.ban_id = cre.cre_bancoid
AND cre.cre_clienteid=cli.id) UNION 
(SELECT ban.ban_nombre AS banco,ban.ban_tipo AS tipo, ban.ban_numero_cuenta AS cuenta, rec.tra_rec_numtransferencia AS transaccion,rec.tra_rec_fecha AS date,rec.tra_rec_descripcion AS descripcion,rec.tra_rec_valor AS valor , cli.nombre AS persona FROM (banco ban, ban_transferencia_recibida rec, cliente cli)WHERE ban.ban_id =  '199'
AND rec.tra_rec_fecha
BETWEEN DATE(  '2010-01-01' ) 
AND DATE(  '2015-07-01' ) 
AND ban.ban_id = rec.tra_rec_bancoid
AND rec.tra_rec_clienteid=cli.id) UNION 
(SELECT ban.ban_nombre AS banco,ban.ban_tipo AS tipo, ban.ban_numero_cuenta AS cuenta, env.tra_env_numtransferencia AS transaccion,env.tra_env_fecha AS date,env.tra_env_descripcion AS descripcion,env.tra_env_valor AS valor , pro.NOMBRE AS persona FROM (banco ban, ban_transferencia_enviada env, pag_maestro_proveedor pro)WHERE ban.ban_id =  '199'
AND env.tra_env_fecha
BETWEEN DATE(  '2010-01-01' ) 
AND DATE(  '2015-07-01' ) 
AND ban.ban_id = env.tra_env_bancoid
AND env.tra_env_proveedorid=pro.ID) order by date ASC
**/
/*
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


*/
$query="(SELECT ban.ban_nombre AS banco,ban.ban_tipo AS tipo, ban.ban_numero_cuenta AS cuenta , dep.dep_numdeposito AS transaccion,dep.dep_fecha AS date,dep.dep_descripcion AS descripcion,dep.dep_valor AS valor , cli.nombre AS persona FROM (banco ban, ban_deposito_bancario dep, cliente cli)WHERE ban.ban_id =  '$id'
AND dep.dep_fecha
BETWEEN DATE(  '$fechainicio' ) 
AND DATE(  '$fechafin' ) 
AND ban.ban_id = dep.dep_bancoid
AND dep.dep_clienteid=cli.id) UNION  
(SELECT ban.ban_nombre AS banco,ban.ban_tipo AS tipo, ban.ban_numero_cuenta AS cuenta , egre.egre_numegreso AS transaccion,egre.egre_fecha AS date,egre.egre_descripcion AS descripcion,egre.egre_valor AS valor , pro.NOMBRE AS persona FROM (banco ban, ban_egreso_bancario egre, pag_maestro_proveedor pro)WHERE ban.ban_id =  '$id'
AND egre.egre_fecha
BETWEEN DATE(  '$fechainicio' ) 
AND DATE(  '$fechafin' ) 
AND ban.ban_id = egre.egre_bancoid
And egre.egre_proveedorid=pro.ID ) UNION
(SELECT ban.ban_nombre AS banco,ban.ban_tipo AS tipo, ban.ban_numero_cuenta AS cuenta, deb.deb_numdebito AS transaccion,deb.deb_fecha AS date,deb.deb_descripcion AS descripcion,deb.deb_valor AS valor , pro.NOMBRE AS persona FROM (banco ban, ban_nota_debito deb, pag_maestro_proveedor pro)WHERE ban.ban_id =  '$id'
AND deb.deb_fecha
BETWEEN DATE(  '$fechainicio' ) 
AND DATE(  '$fechafin' ) 
AND ban.ban_id = deb.deb_bancoid
AND deb.deb_proveedorid=pro.ID) UNION
(SELECT ban.ban_nombre AS banco,ban.ban_tipo AS tipo, ban.ban_numero_cuenta AS cuenta, cre.cre_numcredito AS transaccion,cre.cre_fecha AS date,cre.cre_descripcion AS descripcion,cre.cre_valor AS valor , cli.nombre AS persona FROM (banco ban, ban_nota_credito cre, cliente cli)WHERE ban.ban_id =  '$id'
AND cre.cre_fecha
BETWEEN DATE(  '$fechainicio' ) 
AND DATE(  '$fechafin' ) 
AND ban.ban_id = cre.cre_bancoid
AND cre.cre_clienteid=cli.id) UNION
(SELECT ban.ban_nombre AS banco,ban.ban_tipo AS tipo, ban.ban_numero_cuenta AS cuenta, rec.tra_rec_numtransferencia AS transaccion,rec.tra_rec_fecha AS date,rec.tra_rec_descripcion AS descripcion,rec.tra_rec_valor AS valor , cli.nombre AS persona FROM (banco ban, ban_transferencia_recibida rec, cliente cli)WHERE ban.ban_id =  '199'
AND rec.tra_rec_fecha
BETWEEN DATE(  '$fechainicio' ) 
AND DATE(  '$fechafin' ) 
AND ban.ban_id = rec.tra_rec_bancoid
AND rec.tra_rec_clienteid=cli.id) UNION 
(SELECT ban.ban_nombre AS banco,ban.ban_tipo AS tipo, ban.ban_numero_cuenta AS cuenta, env.tra_env_numtransferencia AS transaccion,env.tra_env_fecha AS date,env.tra_env_descripcion AS descripcion,env.tra_env_valor AS valor , pro.NOMBRE AS persona FROM (banco ban, ban_transferencia_enviada env, pag_maestro_proveedor pro)WHERE ban.ban_id =  '199'
AND env.tra_env_fecha
BETWEEN DATE(  '$fechainicio' ) 
AND DATE(  '$fechafin' ) 
AND ban.ban_id = env.tra_env_bancoid
AND env.tra_env_proveedorid=pro.ID) order by date ASC";

$result = $link->query($query);
    $num_registros = $result->num_rows;

  $total_debe = $total_haber = 0;
   $suma_saldo=0;
    if($num_registros > 0 ){
        $sum[0]=$saldo;
        $k=1;
        while($row = $result->fetch_assoc()){
            $nombrebanco=$row['banco'];
            $tipocuenta=$row['tipo'];
            $numerocuenta=$row['cuenta'];
            $detalle_fecha[]=$row['date'];
            $detalle_doct[]=$row['transaccion'];
            $detalle_benef[]=$row['persona'];
            $detalle_descripcion[]=$row['descripcion'];
            $detalle_valor[]=$row['valor'];

               $tipo=substr($row['transaccion'],0,2);
               if($tipo=="DE"){
                    $sum[$k]=$sum[$k-1]+$row['valor'];
               }
               if($tipo=="EG"){
                $sum[$k]=$sum[$k-1]-$row['valor'];
               }
                if($tipo=="ND"){
                    $sum[$k]=$sum[$k-1]-$row['valor'];
               }
               if($tipo=="NC"){
                    $sum[$k]=$sum[$k-1]+$row['valor'];
               }
               if($tipo=="TR"){
                    $sum[$k]=$sum[$k-1]+$row['valor'];
               }
               if($tipo=="TE"){
                    $sum[$k]=$sum[$k-1]-$row['valor'];
               }
            
            $k++;
        }
     /*  $tamano_dep= count($sum);
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
        }*/

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
            <p class="titulo" style="margin-top:-10px;">'.$nombrebanco.$sum[0].$sum[1].'</p>
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
                        $html.='<td class="dinero">'.number_format($detalle_valor[$i],2).'</td>
                        <td></td><td class="" align="right">'.$sum[$i+1].'</td>';
                     }
                        if($tipo=='EG'){
                            $html.='<td></td>
                                   <td class="dinero">'.number_format($detalle_valor[$i],2).'</td>
                                  <td class="" align="right">'.$sum[$i+1].'</td>';
                        }
                       if($tipo=='NC'){
                        $html.='<td class="dinero">'.number_format($detalle_valor[$i],2).'</td>
                        <td></td><td class="" align="right">'.$sum[$i+1].'</td>';
                     }
                        if($tipo=='ND'){
                            $html.='<td></td>
                                   <td class="dinero">'.number_format($detalle_valor[$i],2).'</td>
                                  <td class="" align="right">'.$sum[$i+1].'</td>';
                        }
                          if($tipo=='TR'){
                        $html.='<td class="dinero">'.number_format($detalle_valor[$i],2).'</td>
                        <td></td><td class="" align="right">'.$sum[$i+1].'</td>';
                     }
                        if($tipo=='TE'){
                            $html.='<td></td>
                                   <td class="dinero">'.number_format($detalle_valor[$i],2).'</td>
                                  <td class="" align="right">'.$sum[$i+1].'</td>';
                        }
                   /* if($tipo=='DE'){
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
                   }*/
                  $html.='</tr>';
                }
                $html.='</tbody>';
                $html.='<tfoot><tr>
                        <td colspan="4">Total:</td>
                        <td >'.number_format($total_debe,2).'</td>
                        <td>'.number_format($total_haber,2).'</td>
                            <td align="right"></td>
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

} 
else{
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