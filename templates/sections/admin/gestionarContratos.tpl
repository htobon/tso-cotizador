
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="modal_title">Administrar Tipos Contratos</h4>
        </div>
        <div class="modal-body">
            <form role="form">
                <div class="form-group">
                    <label>Codigo Contrato</label>
                    <input class="form-control" placeholder="Ingrese el codigo del contrato" disabled="disabled">
                    <p class="help-block"></p>
                </div>                
                <div class="form-group">
                    <label>Nombre Contrato</label>
                    <input class="form-control" placeholder="Ingrese el nombre del contrato" disabled="disabled">
                    <p class="help-block"></p>
                </div>  
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary" sref="guardarContrato">Guardar</button>
        </div>
    </div>   
</div>