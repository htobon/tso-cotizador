<div id="camion" class="large-12 columns camion" style="">
    {foreach from=$accesorios item=accesorio}  
        <a href="#modal-accesorio-{$accesorio->id}" 
           id="accesorio-{$accesorio->id}" 
           class="point" data-rel="popup" 
           data-position-to="window" 
           data-transition="none" 
           style="top: {$accesorio->posicionY}%;left: {$accesorio->posicionX}%;"></a>            
    {/foreach} 
    <img src="{$smarty.const.SMARTY_IMG_URI}/camion.jpg">

</div>