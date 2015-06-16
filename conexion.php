<?php
    $link = new mysqli('localhost', 'root', '', 'dyansoft');
    if ($link->connect_error) {
    die('Error de Conexión (' . $link->connect_errno . ') ' . $link->connect_error);
}

    /*$link = mysql_connect('localhost', 'root', '')
    or die('No se pudo conectar: ' . mysql_error());
    //echo 'Connected successfully';
    mysql_select_db('dyansoft') or die('No se pudo seleccionar la base de datos');*/
?>