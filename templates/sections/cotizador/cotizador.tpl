{include file='head.tpl' jsIncludes=["jquery-mobile"] pageTitle="TSO Cotizador"}
<script type="text/javascript" src="{$smarty.const.SMARTY_ROOT_URI}/js/cotizador.js"></script> 
{include file='header.tpl'} 
 

<div class="row">
    {include file='sections/camion.tpl'}    
</div> 
<div id='accesorio-7' class="reveal-modal" data-reveal>

    <form>
        <div class="row">
            <label>Seleccione una unidad GPS:  </label>
            {foreach from=$arregloGps item=gps}
                <input type="radio" name="losgps" value="{$gps->id}" id="gps-{$gps->id}"><label for="gps-{$gps->id}">{$gps->nombre} ({$gps->precioUnidad} )</label><br>
            {/foreach} 
        </div>
    </form>
    <a class="close-reveal-modal">&#215;</a>
</div>



{include file='footer.tpl'}



