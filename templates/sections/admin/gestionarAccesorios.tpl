
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="modal_title">Administrar de Accesorios</h4>
        </div>
        <div class="modal-body">
            <div class="alert alert-danger alert-dismissable hidden" id="msj_error">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>                    
            </div>

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
                                <input class="form-control" placeholder="Ingrese el codigo del accesorio" id="codigo_accesorio">
                                <p class="help-block"></p>
                            </div>                
                            <div class="form-group">
                                <label>Nombre Accesorio</label>
                                <input class="form-control" placeholder="Ingrese el nombre del accesorio" id="nombre_accesorio">
                                <p class="help-block"></p>
                            </div> 
                            <div class="form-group">
                                <label>Descripcion</label>
                                <textarea class="form-control" rows="3" id="descripcion_accesorio"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Beneficios</label>
                                <textarea class="form-control" rows="3" id="beneficios_accesorio"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Aplicacion</label>
                                <textarea class="form-control" rows="3" id="aplicacion_accesorio"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <label>Precio Unitario</label>
                            <div class="form-group input-group">
                                <span class="input-group-addon">$</span>
                                <input type="text" class="form-control" id="precio_accesorio">
                            </div>
                            <label>Precio Instalación</label>
                            <div class="form-group input-group">
                                <span class="input-group-addon">$</span>
                                <input type="text" class="form-control" id="precio_instalacion_accesorio">
                            </div>
                            <label>Precio Mensualidad</label>
                            <div class="form-group input-group">
                                <span class="input-group-addon">$</span>
                                <input type="text" class="form-control" id="precio_mesualidad_accesorio">
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
                        <div id="planes"></div>
                    </div>

                </div>
                <div class="tab-pane fade" id="restriciones_unidades">                        
                    <div class="form-group">
                        <label>Selecciones los Unidades GPS para el Accesorio</label>
                        <div id="unidades"></div>                        
                    </div>

                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary" sref="guardarAccesorio" id="btn_guardar_accesorio">Guardar</button>
        </div>
    </div>   
</div>