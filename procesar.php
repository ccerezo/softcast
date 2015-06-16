<?php 
	include('conexion.php');
	// Realizar una consulta MySQL
	if(isset($_POST['orden'])){$orden = $_POST['orden'];}

	switch( $orden )
	{

		case 'usuario_maestro':
		
		if(isset($_POST['b'])){$maestra = $_POST['b'];}
		

		$query1 = "SELECT * FROM usuario WHERE clave_maestra = '$maestra'";

		$duplicado = mysql_query($query1) or die('Consulta fallida: ' . mysql_error());

		$contar = mysql_num_rows($duplicado);
			
		if($contar > 0){
				echo 'ok';
		}else{
			echo 'error';
		}
		mysql_close($link);

		break;

		case 'registrar_usuario':
		
		if(isset($_POST['nombre'])){$nombre = $_POST['nombre'];}
		if(isset($_POST['apellido'])){$apellido = $_POST['apellido'];}
		if(isset($_POST['usuario'])){$usuario = $_POST['usuario'];}
		if(isset($_POST['clave'])){$clave_registro = $_POST['clave'];}

		$query1 = "SELECT * FROM usuario WHERE usuario = '$usuario'";

		$duplicado = mysql_query($query1) or die('Consulta fallida: ' . mysql_error());

		$contar = mysql_num_rows($duplicado);
			
		if($contar > 0){
				echo 'duplicado';
		}else{

			$query = "INSERT INTO usuario (nombre, apellido, usuario, clave) VALUES ('$nombre', '$apellido', '$usuario', '$clave_registro')";
			
			$result = mysql_query($query) or die('Consulta fallida: ' . mysql_error());
			echo 'ok php';
		}
		mysql_close($link);

		break;

		case 'iniciar_sesion':

		if(isset($_POST['usuario'])){$usuario = $_POST['usuario'];}
		if(isset($_POST['clave'])){$clave_sesion = $_POST['clave'];}

		$query = "SELECT pu.id_usuario id_usuario, pu.nombre nombre, pu.apellido,
                            pa.adm_id adm_id, pa.adm_rol_id rol_id, pa.adm_modulo_id modulo_id
                            FROM pers_usuario pu 
                            inner join pers_administracion pa on pu.id_usuario = pa.adm_user_id
                            WHERE usuario='$usuario' and clave='$clave_sesion'";
		$result = $link->query($query) or die('Consulta fallida: ' . $link->error());

		if (!$result) {
                    echo 'No se pudo ejecutar la consulta: ' . $link->error();
                    exit;
		}else{
			if($result->num_rows==0)
				$data = array("estado"=>"404");
			else{
				while($row = $result->fetch_assoc()){
					$user = $row["nombre"].' '. $row["apellido"];
                                        $id_user = $row["id_usuario"];
                                        $modulo[] = $row["modulo_id"];
				}
				$data = array("estado"=>"200");
				session_start();
				$_SESSION['usuario'] = $user;
                                $_SESSION['id_usuario'] = $id_user;
                                $_SESSION['id_modulo'] = $modulo;
			}
			echo json_encode($data);
		}
		$link->close();
		break;

		
	}
	
?>
