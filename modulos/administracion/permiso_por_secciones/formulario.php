<link rel="stylesheet" href="<?php echo WEB_ROOT ?>css/treeview/jquery.treeview.css" type="text/css"/> 
<script type="text/javascript" src="<?php echo WEB_ROOT ?>js/jquery/jquery.treeview.min.js"></script>
<script type="text/javascript">
    

    

     function verificar()
    {
        var o = $("#arbol input:checkbox");

        for (i = o.length - 1; i >= -1; i--)
        {
            var obj = o.get(i);
            var parent = $(obj).parent();
            var marcados = parent.find("input:checkbox:checked");
            
            if (marcados.length == 0)
            {
                obj.checked = false;
            }
            else
            {
                obj.checked = true;

            }
        }
    }

</script>


<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">


<ul class="nav nav-pills mb-3" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home"
       role="tab" aria-controls="pills-home" aria-selected="true">Permisos Generales</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile"
       role="tab" aria-controls="pills-profile" aria-selected="false">Permisos Por Usuario</a>
  </li>
</ul>
<div class="tab-content">
  <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
       aria-labelledby="pills-home-tab"> 
       <?php  include_once('permisos_general.php');  ?>
  </div>
  <div class="tab-pane fade" id="pills-profile" role="tabpanel"
       aria-labelledby="pills-profile-tab"> <?php  include_once('permisos_usuario.php');  ?>
  </div>

</div>


         
                </div>
            </div>
        </div>
    </div>
</div>   


