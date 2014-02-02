{include file='head.tpl' jsIncludes=["jquery-mobile", "tablesorter"] pageTitle="TSO Cotizador"} 
{include file='header.tpl'} 

<div id="usuarios">
    <h1>Usuarios</h1>
    <table id="tabla-usuarios" border="1" class="tablesorter">
        <tr>
            <th>ID</th>
            <th>Código</th>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Rol</th>
            <th>Fecha de Creación</th>
            <th>Activo</th>
            <th></th>
            <th></th>
        </tr>
        {foreach from=$usuarios item=usuario}
            <tr>
                <td>{$usuario->id}</td>
                <td>{$usuario->codigo}</td>
                <td>{$usuario->nombres} {$usuario->apellidos}</td>
                <td>{$usuario->correo}</td>
                <td>{$usuario->rol}</td>
                <td>{$usuario->fechaCreacion}</td>
                <td>
                    {if $usuario->estaActivo == 1}
                        <img src="{$smarty.const.SMARTY_IMG_URI}/icon-green-ball-16.png"/>
                    {else}
                        <img src="{$smarty.const.SMARTY_IMG_URI}/icon-red-ball-16.png"/>
                    {/if}
                </td>
                <td><img id="editar-usuario-{$usuario->id}" class="editar-usuario" src="{$smarty.const.SMARTY_IMG_URI}/icon-edit-16.png"/></td>
                <td><img id="eliminar-usuario-{$usuario->id}" class="eliminar-usuario" src="{$smarty.const.SMARTY_IMG_URI}/icon-delete-16.png"/></td>
            </tr>            
        {/foreach}
    </table>
</div>
<div id="accesorios">
    <h1>Accesorios</h1>
    <table id="tabla-accesorios" border="1" class="tablesorter">
        <tr>
            <th>Codigo</th>
            <th>Nombre</th>
            <th>Codigo Instalación</th>
            <th>Precio Instalación</th>
            <th>Precio Accesorio</th>
            <th></th>
            <th></th>
        </tr>
        {foreach from=$accesorios item=accesorio}
            <tr>
                <td>{$accesorio->codAccesorio}</td>
                <td>{$accesorio->nombre}</td>
                <td>{$accesorio->codInstalacion}</td>
                <td>${$accesorio->precioInstalacion|number_format:2}</td>
                <td>${$accesorio->precioAccesorio|number_format:2}</td>
                <td><img id="editar-accesorio-{$usuario->id}" class="editar-accesorio" src="{$smarty.const.SMARTY_IMG_URI}/icon-edit-16.png"/></td>
                <td><img id="eliminar-accesorio-{$usuario->id}" class="eliminar-accesorio" src="{$smarty.const.SMARTY_IMG_URI}/icon-delete-16.png"/></td>
            </tr>            
        {/foreach}
    </table>
</div>



{include file='footer.tpl'}