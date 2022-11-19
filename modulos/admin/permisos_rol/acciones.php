<?php

require_once("php/clase_base.php");

class rol extends clase_base {

    function cargar() {
        $sql = "select menu from admin_permiso_menu where rol='$_POST[rol]'";
        $result1 = $this->db->select_all($sql);

        $sql = "select accion from admin_permiso_accion where rol='$_POST[rol]'";
        $result2 = $this->db->select_all($sql);

        $result = array_merge($result1, $result2);

        echo json_encode($result);
    }

    function guardar() {
        $rol = $_POST['rol'];
        unset($_POST['rol']);

        $this->db->query("delete from admin_permiso_menu where rol='$rol'");
        if (is_array($_POST['menu'])) {
            foreach ($_POST['menu'] as $k => $v) {
                if ($v == "S") {
                    $row = array();
                    $row['rol'] = $rol;
                    $row['menu'] = $k;
                    $this->db->insert("admin_permiso_menu", $row);
                    echo $this->db->error();
                }
            }
        }

        $this->db->query("delete from admin_permiso_accion where rol='$rol'");
        if (is_array($_POST['accion'])) {
            foreach ($_POST['accion'] as $k => $v) {
                if ($v == "S") {
                    $row = array();
                    $row['rol'] = $rol;
                    $row['accion'] = $k;
                    $this->db->insert("admin_permiso_accion", $row);
                    echo $this->db->error();
                }
            }
        }


        $r = array();
        $r['error'] = false;
        $r['msg'] = "Datos guardados con exito";

        echo json_encode($r);
    }

}

$r = new rol();
$accion = ACCION;
$r->$accion();
?>