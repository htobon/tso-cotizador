{include file='head.tpl' jsIncludes=["reveal"] pageTitle="TSO Cotizador"} 
{include file='header.tpl'} 
<div class="row">
    <div class="small-9 small-centered columns"><a href=""><img src="{$smarty.const.SMARTY_IMG_URI}/tsoSimulator.png"></a></div>
</div>
<div class="row">
    {include file='sections/camion.tpl'}    
</div>
{foreach from=$accesorios item=accesorio}    
    <div id='accesorio-{$accesorio->id}' class="reveal-modal" data-reveal>
        <h2>{$accesorio->nombre}</h2> 
        <img class="accesorio-img" src="{$smarty.const.SMARTY_IMG_URI}/accesorios/{$accesorio->codAccesorio}.jpg"/><p class="descripcion">{$accesorio->descripcion}</p>

        <a class="close-reveal-modal">&#215;</a>
    </div>
{/foreach}

{include file='footer.tpl'}



