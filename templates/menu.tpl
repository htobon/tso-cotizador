<div data-role="navbar">
    <ul>
        <li><a href="{$smarty.const.SMARTY_ROOT_URI}/sections/simulador/simulador.php" data-ajax="false">Simulador</a></li>
        <li><a href="{$smarty.const.SMARTY_ROOT_URI}/sections/cotizador/cotizador.php" data-ajax="false">Cotizador</a></li>
        {if $usuario->rol == "Admin"}
        <li><a href="{$smarty.const.SMARTY_ROOT_URI}/sections/admin/admin.php" data-ajax="false">Admin</a></li>
        {/if}
    </ul>
</div><!-- /navbar -->