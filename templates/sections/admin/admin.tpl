{include file='head.tpl' jsIncludes=["jquery", "tablesorter"] pageTitle="TSO Cotizador"} 
{include file='header.tpl'} 

<div id="usuarios">
    <h1>Usuarios</h1>

    <table id="myTable" border="1" class="tablesorter">
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
                <td>[E]</td>
                <td>[R]</td>
            </tr>            
        {/foreach}
    </table>


</div>



{include file='footer.tpl'}