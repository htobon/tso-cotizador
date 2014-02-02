{include file='head.tpl' jsIncludes=["jquery"] pageTitle="TSO Cotizador"}

{include file='header.tpl'} 

<div class="row">
    <div class="small-9 small-centered columns"><a href=""><img src="{$smarty.const.SMARTY_IMG_URI}/logoSmall.png"></a></div>
</div>
<div class="row">
    <div id="menu" class="large-12 columns pagination-centered">
        
        <div class="btn"><a href="{$smarty.const.SMARTY_ROOT_URI}/sections/simulador/simulador.php">S</a></div>
        <div class="btn"><a href="{$smarty.const.SMARTY_ROOT_URI}/sections/cotizador/cotizador.php">Q</a></div>
        {if $usuario->rol == "Admin"}
        	<div class="btn"><a href="{$smarty.const.SMARTY_ROOT_URI}/sections/admin/admin.php">A</a></div>
        {/if}
        <div class="btn"><a href=""><img src="{$smarty.const.SMARTY_IMG_URI}/crmSmall.png"></a></div>

        <h5>Ya inició Sesión {$usuario->nombres}!</h5>
    </div>
</div>

{include file='footer.tpl'}