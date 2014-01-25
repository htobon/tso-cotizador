{include file='head.tpl' jsIncludes=["reveal"] pageTitle="TSO Cotizador"} 
{include file='header.tpl'} 
  <div class="row">
	  <div class="small-9 small-centered columns"><a href=""><img src="{$smarty.const.SMARTY_IMG_URI}/tsoSimulator.png"></a></div>
	</div>
    <div class="row">
      <div class="large-12 columns camion" style=""> 
        {foreach $accesorios as $accesorio}  
           <a href="#" data-reveal-id='accesorio-{$accesorio->id}' class="point" style="top: {$accesorio->posicionY}%;left: {$accesorio->posicionX}%;" data-reveal></a>
           <div id='accesorio-{$accesorio->id}' class="reveal-modal" data-reveal>
              <h2>{$accesorio->nombre}</h2>
              <p class="lead">{$accesorio->descripcion}</p>
              <p>Im a cool paragraph that lives inside of an even cooler modal. Wins</p>
              <a class="close-reveal-modal">&#215;</a>
            </div>
        {/foreach} 
        <img src="{$smarty.const.SMARTY_IMG_URI}/camion.jpg">

      </div>
    </div>  
{include file='footer.tpl'}



