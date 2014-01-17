{include file='head.tpl' jsIncludes=["jquery"] pageTitle="TSO Cotizador"}

{include file='header.tpl'} 
	<div class="row">
	  <div class="small-9 small-centered columns"><a href=""><img src="{$smarty.const.SMARTY_IMG_URI}/tsoSimulator.png"></a></div>
	</div>
    <div class="row">
      <div class="large-12 columns camion" style="">
      	<div id="audio-cabina" class="point"></div> 
        <img src="{$smarty.const.SMARTY_IMG_URI}/camion.jpg">

      </div>
    </div> 
    {foreach from=$accesorios item=accesorio}
        <p>{$accesorio["id"]} - {$accesorio["nombre"]}</p>
    {/foreach}
        
{include file='footer.tpl'}



