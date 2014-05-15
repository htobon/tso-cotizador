<div id="menu-principal" class="ui-body-a ui-body ui-corner-all">

    <div id="nav-bar"> 
        <h3>Menú de navegación</h3>
        <a data-ajax="false" href="{$smarty.const.SMARTY_ROOT_URI}/sections/simulador/simulador.php" data-icon="grid"><img src="{$smarty.const.SMARTY_ROOT_URI}/images/b1.png" alt="Simulador"></a>
            {if $usuario->rol != "Simulador"}
            <a data-ajax="false" href="{$smarty.const.SMARTY_ROOT_URI}/sections/cotizador/cotizador.php" data-icon="star"><img src="{$smarty.const.SMARTY_ROOT_URI}/images/b2.png" alt="Cotizador"></a>
            {/if}
        <a data-ajax="false" href="{$smarty.const.SMARTY_ROOT_URI}/sections/cotizador/generarCotizacion.php" data-icon="star"><img src="{$smarty.const.SMARTY_ROOT_URI}/images/b3.png" alt="SalesForce"></a> 
    </div><!-- /navbar -->
    {if $usuario->rol == "Admin"}
        <a data-ajax="false" href="{$smarty.const.SMARTY_ROOT_URI}/sections/admin/admin.php" data-icon="gear">Admin</a>
    {/if}
</div><!-- /container -->