
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="modal_title">Administrar Usuarios</h4>
        </div>
        <div class="modal-body">
            <form role="form">
                <div class="form-group">
                    <label>Codigo Usuario</label>
                    <input class="form-control" placeholder="Ingrese el codigo del usuario">
                    <p class="help-block"></p>
                </div>
                <div class="form-group">
                    <label>Nombres</label>
                    <input class="form-control" placeholder="Ingrese los nombres del usuario">
                </div>
                <div class="form-group">
                    <label>Apellidos</label>
                    <input class="form-control" placeholder="Ingrese los apellidos del usuario">
                </div>
                <div class="form-group">
                    <label>Telefono</label>
                    <input class="form-control" placeholder="Ingrese el telefono">
                </div>
                <label>Correo Electronico</label>
                <div class="form-group input-group"> 
                    <span class="input-group-addon">@</span>
                    <input type="text" class="form-control" placeholder="Correo Electronico">                    
                </div>
                <label>Contraseña</label>
                <div class="form-group input-group">
                    <span class="input-group-addon">@</span>
                    <input class="form-control" placeholder="Password" name="password" type="password" value="">
                </div>
                <div class="form-group">
                    <label>Rol</label>
                    <select class="form-control">
                        <option>Admin</option>
                        <option>Simulador</option>
                        <option>Vendedor</option>
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
            <button type="button" class="btn btn-primary" sref="guardarUsuario">Guardar</button>
        </div>
    </div>   
</div>