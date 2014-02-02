<div class="large-12 columns camion" style="">  
    {foreach from=$accesorios item=accesorio}  
        <a href="#" id="accesorio-{$accesorio->id}" data-reveal-id='accesorio-{$accesorio->id}' title='{$accesorio->nombre}' class="point" style="top: {$accesorio->posicionY}%;left: {$accesorio->posicionX}%;" data-reveal></a>            
    {/foreach} 
    <img src="{$smarty.const.SMARTY_IMG_URI}/camion.jpg">

</div>