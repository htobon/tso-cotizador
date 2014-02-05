<div class="ui-body-a ui-body ui-corner-all">
    <h3>Menú de navegación</h3>
    <div data-role="navbar">
        <ul>
            <li><a data-ajax="false" href="{$smarty.const.SMARTY_ROOT_URI}/sections/simulador/simulador.php" data-icon="grid">Simulador</a></li>
            <li><a data-ajax="false" href="{$smarty.const.SMARTY_ROOT_URI}/sections/cotizador/cotizador.php" data-icon="star">Cotizador</a></li>
            {if $usuario->rol == "Admin"}
            <li><a data-ajax="false" href="{$smarty.const.SMARTY_ROOT_URI}/sections/admin/admin.php" data-icon="gear">Admin</a></li>
            {/if}
            
        </ul>
    </div><!-- /navbar -->
</div><!-- /container -->