<script type="text/javascript">
    var grid;
    var f;

    $(document).ready(function(e) {
        grid = $.addGrid("#grid",
                {
                    url: page_root + 'listar',
                    height: 400,
                    dataType: 'json',
                    selectionMode: "single",
                    rowHeaderWidth: 1,
                    idName: "_id",
                    idField: "id",
                    recordPage: 50,
                    cols: [
                        {display: 'NO.', name: '_NUM_', width: 40, align: 'left'},
                        						{display: 'TIQUETE', name: 'tiquete', width: 150, align: 'left'}, 
						{display: 'ESTADO', name: 'id_estado', width: 150, align: 'left'}, 
                        {display: 'ACCIONES', name: 'btn', width: 130, align: 'left'}
                    ]}
        );
        f = new formulario(true,650);
    });
</script>

<!-- FORMULARIO -->

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                
                   <div style="width: 90%;margin:auto">       
                      
                        <div class="accordion custom-accordion">
                           
                           <div class="accordion-row">
                              <a href="#" class="accordion-header crud-header">
                                <span> <i class="ti-filter" menu-icon=""></i> Filtros</span>
                                <i class="accordion-status-icon close fa fa-chevron-down"></i>
                                <i class="accordion-status-icon open fa fa-chevron-up"></i>
                              </a>
                              <div class="accordion-body">
                                  
                                  <form class="div-form-busqueda" id="form-busqueda">
                                     <div style="width:100%; margin:auto">
                                        <table style="width:100%">
                                           
            <tr> 
                <td class="tdi">Persona</td>
                <td class="tdc">:</td>
                <td class="tdd">
                    <input type="text" id="bid_persona" name="desde_id_persona" maxlength="10" style="width:120px" placeholder="Desde"/> - 
                    <input type="text" id="bid_persona" name="hasta_id_persona" maxlength="10" style="width:120px" placeholder="Hasta"/>
                </td>            
            </tr>
            <tr> 
                <td class="tdi">Tiquete</td>
                <td class="tdc">:</td>
                <td class="tdd">
                    <input type="text" id="btiquete" name="tiquete" title="Tiquete" placeholder="Tiquete" maxlength="20" value="" />
                </td>            
            </tr>
            <tr> 
                <td class="tdi">Estado</td>
                <td class="tdc">:</td>
                <td class="tdd">
                    <input type="text" id="bid_estado" name="desde_id_estado" maxlength="10" style="width:120px" placeholder="Desde"/> - 
                    <input type="text" id="bid_estado" name="hasta_id_estado" maxlength="10" style="width:120px" placeholder="Hasta"/>
                </td>            
            </tr>
            <tr> 
                <td class="tdi">Fecha limite</td>
                <td class="tdc">:</td>
                <td class="tdd">
                    <input type="text" id="bfecha_limite" name="fecha_limite" title="Fecha limite" placeholder="Fecha limite" maxlength="10" value="" />
                </td>            
            </tr>
                                        </table>

                                       <div class="acciones"> 
                                       
                                       <button type="button" value="Buscar"  onclick="f.buscar()" class="btn btn-primary btn-icon-text" style="margin-left:10px;float:right;">
                                       <i class="ti-search"></i>  Buscar
                                       </button>
                                       
                                       </div>

                                     </div>
                                  </form>
                              </div>
                           </div>

                        </div>
                        
                        <div id="grid" class="grid"></div>  
                
                
                         <div class="btn-toolbar acciones" role="toolbar" aria-label="Toolbar with button groups" 
                            style="width: 10%; margin-left: 90%;margin-top: 10px; margin-bottom: 10px;">
                             <div class="btn-group" role="group" aria-label="Third group">
                             <button type="button" class="btn btn-block btn-success accion-agregar" name="accion" onclick="f.agregar()"  value="Agregar"> <i class="ti-plus"></i> Agregar</button>
                             </div>
                         </div>

                   </div>



                </div>
            </div>
        </div>
    </div>

</div>



<!-- FIN FORMULARIO -->

<!-- MODAL -->

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
     
          <form id="formulario">
            <table style="width:100%">
               
            <tr style="display:none"> 
                <td class="tdi">Id</td>
                <td class="tdc">:</td>
                <td class="tdd">
                    <input type="text" id="id" name="id" title="Id" placeholder="Id" maxlength="11" value=""  class="no-modificable"/>
                </td>            
            </tr>
            <tr> 
                <td class="tdi">Persona</td>
                <td class="tdc">:</td>
                <td class="tdd">
                    <input type="text" id="id_persona" name="id_persona" title="Persona" placeholder="Persona" maxlength="11" value="" />
                </td>            
            </tr>
            <tr> 
                <td class="tdi">Tiquete</td>
                <td class="tdc">:</td>
                <td class="tdd">
                    <input type="text" id="tiquete" name="tiquete" title="Tiquete" placeholder="Tiquete" maxlength="20" value="" />
                </td>            
            </tr>
            <tr> 
                <td class="tdi">Estado</td>
                <td class="tdc">:</td>
                <td class="tdd">
                    <input type="text" id="id_estado" name="id_estado" title="Estado" placeholder="Estado" maxlength="11" value="" />
                </td>            
            </tr>
            <tr> 
                <td class="tdi">Fecha limite</td>
                <td class="tdc">:</td>
                <td class="tdd">
                    <input type="text" id="fecha_limite" name="fecha_limite" title="Fecha limite" placeholder="Fecha limite" maxlength="10" value="" />
                </td>            
            </tr>
            <tr> 
                <td class="tdi">Feha publicado</td>
                <td class="tdc">:</td>
                <td class="tdd">
                    <input type="text" id="feha_publicado" name="feha_publicado" title="Feha publicado" placeholder="Feha publicado" maxlength="10" value="" />
                </td>            
            </tr>
            </table>
          </form>

      </div>

      <div class="modal-footer">
          <div class="dlg-acciones">

             <button type="button" data-dismiss="modal" class="btn btn-danger btn-icon-text" style="margin-left:10px;float:right;">
             <i class="icon-close"></i>  Cancelar</button>             
             <button type="button" id="btn_aceptar" class="btn btn-success btn-icon-text" 
             style="margin-left:10px;float:right;"><i class="icon-plus"></i> Aceptar</button>

          </div>
      </div>


    </div>
  </div>
</div>
<!-- FIN MODAL -->

