<?php

class Base {

    protected $db;
    protected $dbsql;
    protected $usuario_activo;

    public function __construct() {
        $this->db = $GLOBALS['db'];
        $this->dbsql = $GLOBALS['dbsql'];
    }

}

//Para compactibilidad con formularios antiguos
class clase_base extends Base { 
    
}

?>