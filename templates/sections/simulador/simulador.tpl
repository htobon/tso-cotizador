{include file='head.tpl' jsIncludes=["jquery-mobile"] pageTitle="TSO Cotizador"}
<script type="text/javascript" src="{$smarty.const.SMARTY_ROOT_URI}/js/simulador.js"></script>
{include file='header.tpl'} 
 
<div class="row">
    {include file='sections/camion.tpl'}    
</div>
{foreach from=$accesorios item=accesorio}    
    <div id='modal-accesorio-{$accesorio->id}' class="modal-accesorio" data-role="popup">
        <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
        <h2>{$accesorio->nombre}</h2> 
        <img class="accesorio-img" src="{$smarty.const.SMARTY_IMG_URI}/accesorios/{$accesorio->codAccesorio}.jpg"/><p class="descripcion">{$accesorio->descripcion}</p>
    </div>
{/foreach}

{include file='footer.tpl'}


