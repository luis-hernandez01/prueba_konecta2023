<?php

require_once("validation.php");

class formulario_basico {

    protected $tabla, $clave_primaria, $secuencia, $auto_incremental;
    protected $sql, $sql_fila, $campo_ordenar;
    protected $db;

    public function __construct($tabla, $clave_primaria, $auto_incremental = true, $campo_ordenar = "") {
        $this->tabla = $tabla;
        $this->clave_primaria = $clave_primaria;
        $this->db = $GLOBALS['db'];
        $this->secuencia = $tabla . "_" . $clave_primaria . "_seq";
        $this->auto_incremental = $auto_incremental;
        $this->campo_ordenar = $campo_ordenar;
    }

    function setSQLFila($sql) {
        $this->sql_fila = $sql;
    }


    function get_btn($id)
    {
        $id = "'".$id."'";
        $btn = '<ul class="nav align-items-center">
                                    <li class="mr-4 d-sm-inline d-none">
                                        <a href="#" title="" data-toggle="tooltip" data-original-title="Editar" onclick="f.modificar('.$id.')" class="accion-modificar">
                                            <svg viewBox="0 0 24 24" width="24" height="24" stroke="#1565c0" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                        </a>
                                    </li>

                                    <li class="mr-4 d-sm-inline d-none">
                                        <a href="#" title="" data-toggle="tooltip" data-original-title="Eliminar" onclick="f.eliminar('.$id.')" class="accion-eliminar">
                                            <svg viewBox="0 0 24 24" width="24" height="24" stroke="#dc0606" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                        </a>
                                    </li>

                                    <li class="d-sm-inline d-none ">
                                        <a href="#" class="accion-mostrar" title="" data-toggle="tooltip" data-original-title="Ver" onclick="f.mostrar('.$id.')">
                                            <svg viewBox="0 0 24 24" width="24" height="24" stroke="#00c851" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                        </a>
                                    </li>
                                </ul>';
            return $btn;
    }

    protected function getSQL() {
        return "SELECT * FROM " . $this->tabla;
    }

    protected function fila($agregar = false) {
        $this->sql = $this->getSQL();
        if ($this->sql_fila != "") {
            $sql = $this->sql_fila;
        } else {
            $sql = "SELECT * FROM ($this->sql) AS t WHERE $this->clave_primaria='%s' ";
        }

        $id = "";
        if (($agregar == true && $this->auto_incremental == false) || $agregar == false) {
            $id = $_POST[$this->clave_primaria];
        } else {
            $id = $this->db->last_insert_id($this->secuencia);
        }

        $sql = sprintf($sql, $id);
        $rw = $this->db->select_row($sql);
        $rw['id'] = encriptar_id($rw['id'],TOKEN);
        $rw['btn']=$this->get_btn($rw['id']);
        return $rw;
    }

    function validar() {
        //True: Indica que SI se superó la validación
        return true;
    }

    function listar() {
        $sql = $this->getSQL();

        $result['total'] = $this->db->count_rows($sql);

        $offset = ($_GET['page'] - 1) * $_GET['recordpage'];
        $limit = $_GET['recordpage'];

        $rs = $this->db->select_limit($sql, $limit, $offset);
        $result['rows'] = array();
        $num = $offset + 1;
        while ($rw = $this->db->fetch_assoc($rs)) {
            $rw['_NUM_'] = $num++;
            $rw['id'] = encriptar_id($rw['id'],TOKEN);
            $rw['btn']=$this->get_btn($rw['id']);
            $result['rows'][] = $rw;
        }
        //$result['rows'] = $this->db->fetch_all($rs);
        echo json_encode($result);
    }

    function agregar() {
        if ($this->validar() == false)
            exit(0); //No seguir si no se supera la validación

        if ($this->auto_incremental == true)
            unset($_POST[$this->clave_primaria]);

        $sql = $this->db->make_insert($this->tabla, $_POST);
        @$this->db->query($sql);

        $r = array();
        if ($this->db->error()) {
            $r['error'] = true;
            $r['msg'] = $this->db->error();
        } else {
            $r['error'] = false;
            $r['msg'] = "Registro agregado con éxito";
            $r['row'] = $this->fila(true);
        }

         //BITACORA
         $tipo=1;
         $nuevos=$_POST;
         $mensaje=$r['msg'];
         $viejos=false;
         insertar_bitacora($tipo,$nuevos,$mensaje,$viejos);

        echo json_encode($r);
    }

    function asignar() {
        $pk = $this->clave_primaria;
        $sql = "select * from $this->tabla where $pk='$_GET[id]'";
        $rw = $this->db->select_row($sql);
        $rw['id'] = encriptar_id($rw['id'],TOKEN);
        echo json_encode($rw);
    }

    function modificar() {
        if ($this->validar() == false)
            exit(0); //No seguir si no se supera la validación

        $pk = $this->clave_primaria;
        $viejos= $this->db->select_row("SELECT * from $this->tabla where $pk='$_POST[$pk]'");
        $sql = $this->db->make_update($this->tabla, $_POST) . " where $pk='$_POST[$pk]'";
        @$this->db->query($sql);

        $r = array();
        if ($this->db->error()) {
            $r['error'] = true;
            $r['msg'] = $this->db->error();
        } else {
            $r['error'] = false;
            $r['msg'] = "Registro modificado con éxito.";

            $r['row'] = $this->fila(false);
            //$r['row']=$this->db->select_row("select * from general.persona where identifica='$_POST[identifica]'");	
        }

         //BITACORA
         $tipo=3;
         $nuevos=$_POST;
         $mensaje=$r['msg'];
         insertar_bitacora($tipo,$nuevos,$mensaje,$viejos);

        echo json_encode($r);
    }

    function eliminar() {
        $pk = $this->clave_primaria;
        $sql = "delete from $this->tabla where $pk='$_POST[$pk]'";
        $viejos= $this->db->select_row("SELECT * from $this->tabla where $pk='$_POST[$pk]'");
        $query = $this->db->query($sql);

        $r = array();
        if ($this->db->error()) {
            $r['error'] = true;
            $r['msg'] = $this->db->error();
        } else {
            $r['error'] = false;
            $r['msg'] = "Registro eliminado con éxito.";
        }

         //BITACORA
         $tipo=2;
         $nuevos=false;
         $mensaje=$r['msg'];         
         insertar_bitacora($tipo,$nuevos,$mensaje,$viejos);

        echo json_encode($r);
    }

}

?>