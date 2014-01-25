{include file='head.tpl' jsIncludes=["jquery"] pageTitle="TSO Cotizador"}

{include file='header.tpl'} 
	<div class="row">
	  <div class="small-9 small-centered columns"><a href=""><img src="{$smarty.const.SMARTY_IMG_URI}/tsoSimulator.png"></a></div>
	</div>
    <div class="row">
      <div class="large-12 columns camion" style=""> 
        {foreach from=$accesorios item=accesorio} 
          <div id='accesorio-{$accesorio["id"]}' class="point" title='{$accesorio["nombre"]}'></div> 
        {/foreach} 
        <img src="{$smarty.const.SMARTY_IMG_URI}/camion.jpg">

      </div>
    </div> 
    
        
{include file='footer.tpl'}



