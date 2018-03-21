<?php
class DB extends Core{
    private $conn = null;

    function __construct(){
        $this->initDB();
    }

    private function connectPDO(){
        $this->conn = new PDO('mysql:host='.DBC::Host.';port=3306;dbname='.DBC::DBName, DBC::Username, DBC::Password);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getConn(){
        if($this->conn == null){
            $this->initDB();
        }
        return $this->conn;
    }

    /**
    * Obtiene los parametros de conexion establecidos en la instalación de la aplicación para conectar a la base del sistema mediante el driver configurado
    * e inicializa la base de la aplicación
    */
    protected function initDB(){
        try{

            $this->connectPDO();

        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
}
