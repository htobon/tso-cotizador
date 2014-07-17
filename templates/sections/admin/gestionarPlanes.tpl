<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="modal_title">Administrar de Planes</h4>
        </div>
        <div class="modal-body">
            <form role="form">
                <div class="alert alert-danger alert-dismissable hidden" id="msj_error">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>                    
                </div>
                <div class="form-group">
                    <label>Codigo Plan</label>
                    <input class="form-control" placeholder="Ingrese el codigo del plan" id="codigo_plan">
                    <p class="help-block"></p>
                </div>                
                <div class="form-group">
                    <label>Nombre Plan</label>
                    <input class="form-control" placeholder="Ingrese el nombre del plan" id="nombre_plan">
                    <p class="help-block"></p>
                </div> 
                <label>Precio Plan</label>
                <div class="form-group input-group">
                    <span class="input-group-addon">$</span>
                    <input type="text" class="form-control" placeholder="Ingrese el precio del plan" id="precio_plan" maxlength="14">
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary" sref="guardarPlan" id="btn_guardar_plan">Guardar</button>
        </div>
    </div>   
</div>