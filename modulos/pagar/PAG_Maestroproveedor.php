<?php
class PAG_Maestroproveedor 
{
    public $id = '';
    public $p_natural_juridica = 'S';
    public $contabilidad = 'S';
    public $tipo_proveedor = '';
    public $codigo = "";
    public $nombre = "";
    public $ruc_cedula = "";
    public $direccion = "";
    public $email = "";
    public $telefono = "";
    public $autorizacion_sri = "";
    public $cod_retencioniva = "";
    public $cod_retencionfuente = "";
    public $estado = "-1";

    public $link = null;

    function __construct(){}

    function __destruct(){}

    /**
     * Guarda los datos del Usuario.
     */
    public function guardar()
    {
        include($_SERVER['DOCUMENT_ROOT'].'/softcast/conexion.php');
        $fecha = date("Y-m-d");
        $hora = date("H:i:s");
        $result = false;
        $idData = '';

        $this->codigo = ( strcmp($this->tipo_proveedor, "bienes") == 0 ) ? "PB1" : "PS1";

        try
        {
            $this->id = (strcmp($this->id, "") == 0)? null : $this->id;
            $smtp = "CALL SP_PAGO_MAESTROPROV_INGRESAR_ACTUALIZAR( '$this->id', '$this->tipo_proveedor',
                '$this->codigo', '$this->nombre', '$this->ruc_cedula', '$this->direccion',
                '$this->telefono', '$this->autorizacion_sri', '$this->cod_retencioniva',
                '$this->cod_retencionfuente', '1', '$fecha', '$hora', '$this->p_natural_juridica',
                '$this->contabilidad','$this->email' )";
            $result = $link->query($smtp);
        }catch(Exception $e){
            $result = false;
        }

        if($result) return true;
        else return false;
    }

    /**
     * Obtiene todos los datos del usuario
     */
    public function obtenerData( $filtro = 0 )
    {
        include($_SERVER['DOCUMENT_ROOT'].'/softcast/conexion.php');

        $datos = array();
        
        $query = "SELECT * FROM PAG_MAESTRO_PROVEEDOR WHERE ESTADO != -1";
        $result = $link->query($query);
        while($data=$result->fetch_assoc())
        {
            $data['NOMBRE'] = utf8_encode($data['NOMBRE']);
            $data['DIRECCION'] = utf8_encode($data['DIRECCION']);
            array_push($datos, $data);
        }
        return $datos;
    }

    /**
     * Obtiene todos los datos del usuario
     */
    public function obtenerdata_plancuentas()
    {
        include($_SERVER['DOCUMENT_ROOT'].'/softcast/conexion.php');

        $datos = array();
        
        $query = "SELECT * FROM cont_plan_de_cuentas";
        $result = $link->query($query);
        while($data=$result->fetch_assoc())
        {
            $data['cont_nombre'] = utf8_encode($data['cont_nombre']);
            array_push($datos, $data);
        }
        return $datos;
    }

    public function obtener_x_codigo( $codigo )
    {
        include($_SERVER['DOCUMENT_ROOT'].'/softcast/conexion.php');

        $row = null;
        $query = "SELECT *
                FROM PAG_MAESTRO_PROVEEDOR 
                WHERE ID = '$codigo';";
        $result = $link->query($query);

        if( $data=$result->fetch_assoc() )
        {
            $data['NOMBRE'] = utf8_encode($data['NOMBRE']);
            $data['DIRECCION'] = utf8_encode($data['DIRECCION']);
            $row = $data;
        }
        return $row;
    }

    public function eliminar( $codigo )
    {
        include($_SERVER['DOCUMENT_ROOT'].'/softcast/conexion.php');

        $query = "CALL SP_PAGO_MAESTROPROV_ELIMINAR('$codigo')";
        $result = $link->query($query);
        return $result;
    }

    public function validarCampos()
    {
        include($_SERVER['DOCUMENT_ROOT'].'/softcast/conexion.php');

        $this->id = (strcmp($this->id, "") == 0)? null : $this->id;

        $row = NULL;
        $response = array();
        $estado = TRUE;

        // Valida RUC
        $smtp = "CALL SP_PAGO_MAESTROPROV_EXISTE_RUC( '$this->id', '$this->ruc_cedula')";
        $result = $link->query($smtp);
        $data=$result->fetch_assoc();
        if( $data['N'] != 0 )
        {
            $estado = FALSE;
            $response['ruc_cedula'] = 'La cédula o Ruc ya existe';
        }

        //Validacion pàra los otros a futuro

        return array("estado"=>$estado, "response"=>$response);
    }
}
?>