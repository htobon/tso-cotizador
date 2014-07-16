
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="modal_title">Administrar Unidades GPS</h4>
        </div>
        <div class="modal-body">
            <form role="form">
                <div class="alert alert-danger alert-dismissable hidden" id="msj_error">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>                    
                </div>
                <div class="form-group">
                    <label>Codigo Unidad GPS</label>
                    <input class="form-control" placeholder="Ingrese el codigo de la unidad GPS" id="codigo_unidad_gps">
                    <p class="help-block"></p>
                </div>                
                <div class="form-group">
                    <label>Codigo Instalación</label>
                    <input class="form-control" placeholder="Ingrese el codigo de la Instalación" id="codigo_instalacion_unidad_gps">
                    <p class="help-block"></p>
                </div>                
                <div class="form-group">
                    <label>Nombre Unidad GPS</label>
                    <input class="form-control" placeholder="Ingrese el nombre de la Unidad Gps" id="nombre_unidad_gps">
                    <p class="help-block"></p>
                </div>                
                <div class="form-group">
                    <label>Descripción</label>
                    <input class="form-control" placeholder="Ingrese la Descripción" id="descripcion_unidad_gps">
                    <p class="help-block"></p>
                </div>            
                <label>Precio Unidad</label>
                <div class="form-group input-group">
                    <span class="input-group-addon">$</span>
                    <input type="text" class="form-control" placeholder="Ingrese el precio de la unidad" id="precio_unidad_gps" maxlength="14">
                </div>
                <label>Precio Instalacion</label>
                <div class="form-group input-group">
                    <span class="input-group-addon">$</span>
                    <input type="text" class="form-control" placeholder="Ingrese el precio de Instalación" id="precio_instalacion_unidad_gps" maxlength="14">
                </div>
                <div class="form-group">
                    <label>Adjuntar Imagen</label>
                    <input type="file" class="filestyle">
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary" sref="guardarUnidadGps" id="btn_guardar_unidad_gps" >Guardar</button>
        </div>
    </div>   
</div>