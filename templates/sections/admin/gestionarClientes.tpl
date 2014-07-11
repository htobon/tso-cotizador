
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="modal_title">Administrar de Clientes</h4>
        </div>
        <div class="modal-body">
            <form role="form">
                <div class="form-group">
                    <label>Codigo Cliente</label>
                    <input class="form-control" placeholder="Ingrese el codigo del cliente">
                    <p class="help-block"></p>
                </div>                
                <div class="form-group">
                    <label>Nit</label>
                    <input class="form-control" placeholder="Ingrese el nit del cliente">
                    <p class="help-block"></p>
                </div>  
                <div class="form-group">
                    <label>Nombre Cliente</label>
                    <input class="form-control" placeholder="Ingrese el nombre del cliente">
                    <p class="help-block"></p>
                </div>  
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary" sref="guardarCliente">Guardar</button>
        </div>
    </div>   
</div>