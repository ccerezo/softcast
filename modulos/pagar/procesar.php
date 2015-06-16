<?php 
    //include('../../conexion.php');
    require_once 'PAG_Maestroproveedor.php';

    $objData = new PAG_Maestroproveedor();
    $tipo = "";

    // Realizar una consulta MySQL
    if( isset($_POST['orden']) AND $_REQUEST['orden'] != '' )
    {
        $orden = $_POST['orden'];
        // response Array
        $response = array("orden" => $orden, "success" => 0);
    }else
        echo "Access Denied";
    
    static $indice = 0;

    switch( $orden )
    {
        case 'guardar':
            $objData->id = (isset($_REQUEST['id']) AND $_REQUEST['id'] != '')? $_REQUEST['id'] : "";
            $objData->p_natural_juridica = (isset($_REQUEST['natural_juridica']) AND $_REQUEST['natural_juridica'] != '')? $_REQUEST['natural_juridica'] : "";
            $objData->contabilidad = (isset($_REQUEST['contabilidad']) AND $_REQUEST['contabilidad'] != '')? $_REQUEST['contabilidad'] : "";
            $objData->tipo_proveedor = (isset($_REQUEST['tipo_proveedor']) AND $_REQUEST['tipo_proveedor'] != '')? $_REQUEST['tipo_proveedor'] : "";
            $objData->codigo = (isset($_REQUEST['codigo_cuenta']) AND $_REQUEST['codigo_cuenta'] != '')? $_REQUEST['codigo_cuenta'] : "";
            $objData->nombre = utf8_decode( (isset($_REQUEST['nombre']) AND $_REQUEST['nombre'] != '')? $_REQUEST['nombre'] : "" );
            $objData->ruc_cedula = (isset($_REQUEST['ruc_cedula']) AND $_REQUEST['ruc_cedula'] != '')? $_REQUEST['ruc_cedula'] : "";
            $objData->direccion = utf8_decode( (isset($_REQUEST['direccion']) AND $_REQUEST['direccion'] != '')? $_REQUEST['direccion'] : "" );
            $objData->email = (isset($_REQUEST['email']) AND $_REQUEST['email'] != '')? $_REQUEST['email'] : "";
            $objData->telefono = (isset($_REQUEST['telefono']) AND $_REQUEST['telefono'] != '')? $_REQUEST['telefono'] : "";
            $objData->autorizacion_sri = (isset($_REQUEST['autorizacion_sri']) AND $_REQUEST['autorizacion_sri'] != '')? $_REQUEST['autorizacion_sri'] : "";
            $objData->cod_retencioniva = (isset($_REQUEST['retencion_iva']) AND $_REQUEST['retencion_iva'] != '')? $_REQUEST['retencion_iva'] : "";
            $objData->cod_retencionfuente = (isset($_REQUEST['retencion_fuente']) AND $_REQUEST['retencion_fuente'] != '')? $_REQUEST['retencion_fuente'] : "";

            $resultVal = $objData->validarCampos();
            if( $resultVal['estado'] )
            {
                $result = $objData->guardar();
                if ( $result ) $response["success"] = 1;
                else {
                    $response["error"] = 1;
                    $response["error_msg"] = "Error al guardar los datos.";
                }
            }else $response["mensajes_error"] = $resultVal['response'];
            echo json_encode($response);
            break;
        case 'obtenerdata':
            $response = $objData->obtenerData();
            echo json_encode($response);
            break;
        case 'obtenerdata_plancuentas':
            $response = $objData->obtenerdata_plancuentas();
            echo json_encode($response);
            break;
        case 'obtener_x_codigo':
            $response = $objData->obtener_x_codigo($_REQUEST['codigo']);
            echo json_encode($response);
            break;
        case 'eliminar':
            $result = $objData->eliminar($_REQUEST['codigo']);
            if ( $result ) $response["success"] = 1;
            else {
                $response["error"] = 1;
                $response["error_msg"] = "Error al eliminar el dato.";
            }
            echo json_encode($response);
            break;
        default:
            echo "Invalid Request";
            break;
    }

?>
