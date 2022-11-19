<link rel="stylesheet" href="<?php echo WEB_ROOT ?>css/treeview/jquery.treeview.css" type="text/css"/> 
<script type="text/javascript" src="<?php echo WEB_ROOT ?>js/jquery/jquery.treeview.min.js"></script>
<script type="text/javascript" src="<?php echo WEB_ROOT ?>plantilla/vendors/ace/src-min/ace.js"></script>

<script type="text/javascript">
   var editor;
    $(document).ready(function() {


        $("#arbol").treeview({
            collapsed: true
        });
    });
    function obtener(url,parent) {
      $("#url").val(url);
      $("#parent").val(parent);
      $("#form").submit();      
    }

$(function(){
    $("#formulario").submit(function(){
        var data = $("#formulario").serialize();
        $.ajax({
                url:page_root + "guardar",
                type: "POST",
                dataType: "JSON",
                data: data,
        beforeSend: function(xhr){
               xhr.setRequestHeader("Authorization",TOKEN_GLOBAL); 
               msg_cargando(true);
            },
        success: function(data){            
            var r = data;
            if (r.error == true)
            {
                for (ind in r.bad_fields)
                {
                    $("#" + r.bad_fields[ind]).addClass("error");
                }
                msg_cargando(false);
                msg($(".respuesta"),r.msg,"error");
                document.getElementById("formulario").reset();
            } else
            {
                msg_cargando(false);
                msg($(".respuesta"),r.msg,"exito");
            }
        },
        error: function(msg) {
               msg_cargando(false);
               msg($(".respuesta"),"Erro desconocido.","error");
            }
        });
        return false;
    })

 })
</script>
<form id="form" method="POST" accept-charset="utf-8">
    <input type="hidden" name="url" id="url" value="" required="">
    <input type="hidden" name="parent" id="parent" value="" required="">
</form>
<div class="container-fluid">

    <div class="row">
        <div class="col-md-3">

            <div class="card">
                <div class="card-body" id="arbol">                  
                  <?php
                    listarMenu(NULL);
                  ?>                  
                </div>
            </div>

        </div>

        <div class="col-md-9">

            <div class="card">
                <div class="card-body">  
                 <h5 class="card-title">Archivo : <?php echo $_POST['url'] ?></h5>  
                  <div id="editor" style="min-height: 800px">
                    <form id="formulario" method="POST" accept-charset="utf-8">
                       <input type="hidden" name="url" id="url" value="<?php echo $_POST['url'] ?>" required="true">
                       <textarea name="html" id="html" style="min-height: 5px;display: none"></textarea>
                       <textarea id="ace_php" name="ace_php" class="ace-editor w-100"><?php echo file_get_contents($_POST['url']); ?></textarea>

                       <div class="form-inline">
                       <div class="form-group mb-2">
                       <label for="staticEmail2" class="sr-only">Email</label>
                       <input type="text" readonly class="form-control-plaintext" id="staticEmail2" value="email@example.com">
                       </div>
                       <div class="form-group mx-sm-3 mb-2">
                       <label for="inputPassword2" class="sr-only">Password</label>
                       <input type="password" class="form-control" id="inputPassword2" placeholder="Password">
                       </div>
                       <button type="submit" class="btn btn-primary mb-2">Confirm identity</button>
                       </div>

                    </form>
                  </div>                 
                </div>
            </div>

        </div>
    </div>

</div>






<?php

function listarMenu($padre) {
    global $db;

    if ($padre == NULL) {
        $sql = "select * from admin_menu where padre is null and (id<>2 and id<>45 and id<>300 and id<>36 and id<>32 and id<>33 and id<>330) order by orden";
    } else {
        $sql = "select * from admin_menu where padre = '" . $padre . "' and (id<>2 and id<>45 and id<>300 and id<>36 and id<>32 and id<>33 and id<>330) order by orden";
    }

    $rs = $db->query($sql);

    echo("<ul>");
    while ($rw = $db->fetch_assoc($rs)) {
        $ruta = $rw['ruta'];
        $id_padre ='menu_'.$rw["menu"];
        $sp="";
        if ($padre) {
          $sp = "parent='menu_$padre'";  
        }
        $class="class='closed'";
        if ($_POST['parent']==$id_padre) {
            $stp='border: 1px solid #ddd; background: #ddd;';
            $class=" class='opened' ";
        }

        echo("<li id='$id_padre' $sp>");
        echo("<i class='ti-folder' style='color: #009688;'></i> ");
        echo("<label   for='menu_" . $rw["menu"] . "'>" . $rw["nombre"] . "</label>");

        $rs2 = $db->query("select * from admin_accion where 1 AND menu='" . $rw["menu"] . "' order by accion");

        if ($db->num_rows($rs2) > 0) {
            echo ("<ul>");
            $archivos = array();
            while ($rw2 = $db->fetch_assoc($rs2)) {
               $url = $ruta.'/'.$rw2['archivo'];
               $archivos[$url] = $rw2['archivo'];
            }
            foreach ($archivos as $k => $v) {
                $url= '"'.$k.'"';
                $parent= '"'.$id_padre.'"';
                $id = str_replace("/", "_", $k);
                $id = explode(".",$id,2);
                $stp="";
                $class="class='closed'";
                if ($_POST['url']==$k) {
                    $stp='border: 1px solid #ddd; background: #ddd;';
                    $class=" class='opened' ";
                }
                echo("<li onclick='obtener($url,$parent)' style='cursor: pointer; $stp' id='$id[0]' parent='$id_padre'>");
                  echo("<i class='ti-file'></i> ");
                  echo("<label style='cursor: pointer'>" .$v. "</label>");
                echo("</li>");
            }

            echo("</ul>");
        }

        $hijos = $db->select_one("SELECT COUNT(*) FROM admin_menu WHERE padre='$rw[menu]'");
        if ($hijos > 0) {
            listarMenu($rw["menu"]);
        }
        echo("</li>");
    }
    echo("</ul>");
}
?>

<script type="text/javascript">
    $(document).ready(function() {
        if ($('#ace_php').length) {
             $(function() {
                editor = ace.edit("ace_php");
                editor.setTheme("ace/theme/monokai");
                editor.getSession().setMode("ace/mode/php");
                document.getElementById('ace_php');
                var code = editor.getSession().getValue();
                $("#html").val(code);
                 editor.getSession().on('change', function () {
                     var code = editor.getSession().getValue();
                     $("#html").val(code);
                 });
             });
          }
       

     <?php if ($_POST) {
        $id = explode(".",$_POST['url'],2);
        $id = str_replace("/", "_",$id[0]); 
     ?>
         activar_li("<?php echo $id  ?>","<?php echo $_POST['parent'] ?>");
     <?php } ?>
    });

    function activar_li(id) {

         var input = document.getElementById(id);
         try {

              var one = 1;
                $("#"+id+" div").each(function(){
                  if (one==1) {
                    $(this).addClass("fabio");
                    one=2;
                  }
                });

              var parent = input.getAttribute('parent'); // TOMO EL ATRIBUTO
              activar_li(parent);


         }catch(err) {
             
         }

        
    }
    
    $(document).ready(function() {
        setTimeout(function() {
            $(".fabio").trigger("click");                
        }, 1000);
        
    });

</script>
<style type="text/css" media="screen">
    .ace_editor{
        min-height: 800px
    }
    .collapsable {
      border-left: 3px solid #FF5722  !important;
    }

</style>
