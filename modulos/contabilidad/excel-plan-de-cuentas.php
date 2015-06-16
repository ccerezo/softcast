<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

header("Content-type: application/vnd.ms-excel; name='excel'");
header("Content-Disposition: filename=Plan de Cuentas.xls");
header("Pragma: no-cache");
header("Expires: 0");
echo utf8_decode($_POST['datos_a_enviar']);

?>