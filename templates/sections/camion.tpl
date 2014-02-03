<div class="large-12 columns camion" style="">
    {foreach from=$accesorios item=accesorio}  
        <a href="#test" id="accesorio-{$accesorio->id}" class="point" style="top: {$accesorio->posicionY}%;left: {$accesorio->posicionX}%;"></a>            
    {/foreach} 
    <img src="{$smarty.const.SMARTY_IMG_URI}/camion.jpg">

</div>