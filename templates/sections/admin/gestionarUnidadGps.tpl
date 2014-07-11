
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="modal_title">Administrar Unidades GPS</h4>
        </div>
        <div class="modal-body">
            <form role="form">
                <div class="form-group">
                    <label>Codigo Unidad GPS</label>
                    <input class="form-control" placeholder="Ingrese el codigo de la unidad GPS">
                    <p class="help-block"></p>
                </div>                
                <div class="form-group">
                    <label>Codigo Instalación</label>
                    <input class="form-control" placeholder="Ingrese el codigo de la Instalación">
                    <p class="help-block"></p>
                </div>                
                <div class="form-group">
                    <label>Nombre Unidad GPS</label>
                    <input class="form-control" placeholder="Ingrese el nombre de la Unidad Gps">
                    <p class="help-block"></p>
                </div>                
                <div class="form-group">
                    <label>Descripción</label>
                    <input class="form-control" placeholder="Ingrese la Descripción">
                    <p class="help-block"></p>
                </div>                
                <div class="form-group">
                    <label>Precio Unidad</label>
                    <input class="form-control" placeholder="Ingrese el precio de la unidad">
                    <p class="help-block"></p>
                </div>                
                <div class="form-group">
                    <label>Precio Instalacion</label>
                    <input class="form-control" placeholder="Ingrese el precio de Instalación">
                    <p class="help-block"></p>
                </div>  
                <div class="form-group">
                    <label>Adjuntar Imagen</label>
                    <input type="file" class="filestyle">
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary" sref="guardarUnidadGps">Guardar</button>
        </div>
    </div>   
</div>