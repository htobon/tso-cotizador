{include file='head.tpl' jsIncludes=["jquery"] pageTitle="TSO Cotizador"}

{include file='header.tpl'} 

<div class="row">
    <div class="small-9 small-centered columns"><a href=""><img src="{$smarty.const.SMARTY_IMG_URI}/logoSmall.png"></a></div>
</div>
<div class="row">
    <div class="large-12 columns pagination-centered">
        
        <a href="{$smarty.const.SMARTY_ROOT_URI}/sections/simulador/simulador.php"><img src="{$smarty.const.SMARTY_IMG_URI}/b1.png"></a>
        <a href="{$smarty.const.SMARTY_ROOT_URI}/sections/cotizador/cotizador.php"><img src="{$smarty.const.SMARTY_IMG_URI}/b2.png"></a>
        {if $usuario->rol == "Admin"}
        <p>Botón Admin</p>
        {/if}
        <a href=""><img src="{$smarty.const.SMARTY_IMG_URI}/b3.png"></a>
        <h5>Ya inició Sesión {$usuario->nombres}!</h5>
    </div>
</div>

{include file='footer.tpl'}