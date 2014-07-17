
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="modal_title">Administrar Duraciones Contrato</h4>
        </div>
        <div class="modal-body">
            <form role="form">
                <div class="alert alert-danger alert-dismissable hidden" id="msj_error">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>                    
                </div>               
                <div class="form-group">
                    <label>Cantidad Meses</label>
                    <input class="form-control" placeholder="Ingrese cantidad de meses" id="cantidad_meses" type="number" maxlength="3">
                    <p class="help-block"></p>
                </div> 
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary" sref="guardarDuracionContrato" id="btn_guardar_mes">Guardar</button>
        </div>
    </div>   
</div>