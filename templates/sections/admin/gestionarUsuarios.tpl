
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="modal_title">Administrar Usuarios</h4>
        </div>
        <div class="modal-body">
            <form role="form" id="form-usuarios">
                <div class="alert alert-danger alert-dismissable hidden" id="msj_error">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>                    
                </div>
                <div class="form-group">
                    <label>Codigo Usuario *</label>
                    <input class="form-control" placeholder="Ingrese el codigo del usuario" id="codigo_usuario" value="">
                    <p class="help-block"></p>
                </div>
                <div class="form-group">
                    <label>Nombres *</label>
                    <input class="form-control" placeholder="Ingrese los nombres del usuario" id="nombres_usuario">
                </div>
                <div class="form-group">
                    <label>Apellidos *</label>
                    <input class="form-control" placeholder="Ingrese los apellidos del usuario" id="apellidos_usuario">
                </div>
                <div class="form-group">
                    <label>Telefono *</label>
                    <input class="form-control" placeholder="Ingrese el telefono" id="telefono_usuario">
                </div>
                <label>Correo Electronico *</label>
                <div class="form-group input-group"> 
                    <span class="input-group-addon">@</span>
                    <input data-inputmask-mask="email" type="text" class="form-control" placeholder="Correo Electronico" id="email_usuario">                    
                </div>
                <label>Contraseña *</label>
                <div class="form-group input-group">
                    <span class="input-group-addon">@</span>
                    <input class="form-control" placeholder="Password" name="password" type="password" value="" id="clave_usuario">
                </div>
                <div class="form-group">
                    <label>Rol</label>
                    <select class="form-control" id="rol_usuario">
                        <option value="Admin">Admin</option>
                        <option value="Simulador">Simulador</option>
                        <option value="Vendedor">Vendedor</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Adjuntar Firma Digital</label>
                    <input type="file" class="filestyle">
                </div>
            </form>            
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary" sref="guardarUsuario" id="btn_guardar_usuario" rel='' >Guardar</button>
        </div>
    </div>   
</div>