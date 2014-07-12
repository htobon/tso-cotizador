
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="modal_title">Administrar de Accesorios</h4>
        </div>
        <div class="modal-body">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#informacion_general" data-toggle="tab">Informacion General</a></li>
                <li><a href="#restriciones_planes" data-toggle="tab">Restricciones Planes</a></li>
                <li><a href="#restriciones_unidades" data-toggle="tab">Restricciones Unidades GPS</a></li>
            </ul>
            <div class="tab-content">

                <div class="tab-pane fade in active" id="informacion_general">
                    <!--h4>Informacion General</h4-->                       

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Codigo Accesorio</label>
                                <input class="form-control" placeholder="Ingrese el codigo del cliente">
                                <p class="help-block"></p>
                            </div>                
                            <div class="form-group">
                                <label>Nombre Accesorio</label>
                                <input class="form-control" placeholder="Ingrese el nit del cliente">
                                <p class="help-block"></p>
                            </div> 
                            <div class="form-group">
                                <label>Descripcion</label>
                                <textarea class="form-control" rows="3"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Beneficios</label>
                                <textarea class="form-control" rows="3"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Aplicacion</label>
                                <textarea class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <label>Precio Unitario</label>
                            <div class="form-group input-group">
                                <span class="input-group-addon">$</span>
                                <input type="text" class="form-control">
                                <span class="input-group-addon">.00</span>
                            </div>
                            <label>Precio Instalación</label>
                            <div class="form-group input-group">
                                <span class="input-group-addon">$</span>
                                <input type="text" class="form-control">
                                <span class="input-group-addon">.00</span>
                            </div>
                            <label>Precio Mensualidad</label>
                            <div class="form-group input-group">
                                <span class="input-group-addon">$</span>
                                <input type="text" class="form-control">
                                <span class="input-group-addon">.00</span>
                            </div>
                            <div class="form-group">
                                <label>Adjuntar Imagen</label>
                                <input type="file" class="filestyle">
                            </div>
                        </div>
                    </div>

                    <p> Todos lo campos con (*) son obligatorios.</p>
                </div>
                <div class="tab-pane fade" id="restriciones_planes">
                    <!--h4>Restricciones Planes</h4-->
                    <div class="form-group">
                        <label>Selecciones los planes para el Accesorio</label>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">Plan 1
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">Plan 2
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">Plan 3
                            </label>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="restriciones_unidades">                        
                    <div class="form-group">
                        <label>Selecciones los Unidades GPS para el Accesorio</label>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">Unidades GPS 1
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">Unidades GPS 2
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">Unidades GPS 3
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary" sref="guardarCliente">Guardar</button>
        </div>
    </div>   
</div>