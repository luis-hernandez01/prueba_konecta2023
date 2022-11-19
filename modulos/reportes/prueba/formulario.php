<script type="text/javascript">
    $(document).ready(function()  {
        
      /*  $("#formulario").submit(function() {
            $.ajaxSetup({async: false});
            $.post(page_root + "validar", $("#formulario").values(), function(data) {
                var r = jQuery.parseJSON(data);
                if (r.error == true)
                {
                    for (ind in r.bad_fields)
                    {
                        $("#" + r.bad_fields[ind]).addClass("error");
                    }
                    alert(r.msg);
                } else {
                    return true;
                }
            });

            return false;
        });
      */  
    });
</script>


<div class="col-md-12 grid-margin stretch-card">
              <div class="card position-relative">
                <div class="card-body">

    <form id="formulario" method="post"  target="_blank" 
        action="<?php echo PAGE_ROOT . "mostrar" ?>" class="form-horizontal" style="margin:auto;width:85%">
    <div class="box-body">
        <table style="width:100%">
 

            <tr> 
                <td class="tdi">Formato</td>
                <td class="tdc">:</td>
                <td class="tdd">
                    <select id="formato" name="formato">
                        <option value="PDF">Documento PDF</option>
                        <option value="XLS">Documento de Excel</option>
                        <option value="DOC">Documento de Word</option>
                        <option value="HTML">Visualizar en el navegador Web</option>
                    </select>
                </td>            
            </tr>
        </table>

       <div class="box-footer">
            <button type="submit" value="Mostrar" class="btn btn-success btn-icon-text" style="float:right;"><i class="ti-printer btn-icon-append"></i> Mostrar</button>
        </div>
    </div>
    </form>


                </div>
              </div>
            </div>