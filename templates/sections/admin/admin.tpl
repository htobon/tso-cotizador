{include file='head.tpl' jsIncludes=["jquery", "tablesorter"] pageTitle="TSO Cotizador"} 
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
                <td>[icono-Editar]-{$usuario->id}</td>
                <td>[icono-Remover]-{$usuario->id}</td>
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
                <td>[icono-Editar]-{$accesorio->id}</td>
                <td>[icono-Remover]-{$accesorio->id}</td>
            </tr>            
        {/foreach}
    </table>
</div>



{include file='footer.tpl'}