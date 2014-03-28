<div id="camion" class="large-12 columns camion" style="">
    {foreach from=$accesorios item=accesorio}  
    	{if $accesorio->id == -1}
    		<a href="#modal-unidad-gps" 
	           id="unidad-gps" 
	           class="point" data-rel="popup" 
	           data-position-to="window" 
	           data-transition="none" 
	           style="top: {$accesorio->posicionY}%;left: {$accesorio->posicionX}%;"></a>
    	{else}
	        <a href="#modal-accesorio-{$accesorio->id}" 
	           id="accesorio-{$accesorio->id}" 
	           class="point" data-rel="popup" 
	           data-position-to="window" 
	           data-transition="none" 
	           style="top: {$accesorio->posicionY}%;left: {$accesorio->posicionX}%;"></a>            
    	{/if}
    {/foreach} 
    <img src="{$smarty.const.SMARTY_IMG_URI}/camion.png">

</div>