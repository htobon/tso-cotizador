{include file='head.tpl' jsIncludes=["jquery-mobile"] pageTitle="TSO Cotizador"}
<script type="text/javascript" src="{$smarty.const.SMARTY_ROOT_URI}/js/cotizador.js"></script> 
{include file='header.tpl'} 
 

<div class="row">
    {include file='sections/camion.tpl'}    
</div> 

<div id='modal-accesorio-7' class="modal-accesorio" data-role="popup">
    <form>
        <div class="row">
           <fieldset data-role="controlgroup"> 
            <legend>Seleccione una unidad GPS:  </legend>
            {foreach from=$arregloGps item=gps}
                <input type="radio" name="losgps" value="{$gps->id}" id="gps-{$gps->id}">
                <label for="gps-{$gps->id}">{$gps->nombre} ({$gps->precioUnidad} )</label>
            {/foreach} 
           </fieldset>  
        </div>
    </form>
</div>





{include file='footer.tpl'}



