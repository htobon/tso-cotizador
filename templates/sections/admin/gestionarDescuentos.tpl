
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="modal_title">Administrar de Descuentos</h4>
        </div>
        <div class="modal-body">
            <form role="form">
                <div class="alert alert-danger alert-dismissable hidden" id="msj_error">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>                    
                </div>
                <div class="form-group col-md-6">
                    <label>Cantidad Minima</label>
                    <input class="form-control" placeholder="Ingrese la cantidad minima" id="cantidad_minima">
                    <p class="help-block"></p>
                </div>                
                <div class="form-group col-md-6">
                    <label>Cantidad Máxima</label>
                    <input class="form-control" placeholder="Ingrese la cantidad maxima" id="cantidad_maxima">
                    <p class="help-block"></p>
                </div>  
                <label>Descuento</label>
                <div class="form-group input-group">
                    <input type="text" class="form-control" placeholder="Ingrese el descuento" id="descuento" maxlength="2" >
                    <span class="input-group-addon">%</span>
                </div>  
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary" sref="guardarDescuento" id="btn_guardar_descuento">Guardar</button>
        </div>
    </div>   
</div>