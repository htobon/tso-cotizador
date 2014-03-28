<div id="header" data-role="header" style="overflow:hidden;" data-fullscreen="true" data-position="fixed" >
  <h1>TSO Cotizador</h1>
  {if !isset($ocultarLogout)}
    {if !isset($ocultarReturn)}      
      <a href="{$smarty.const.SMARTY_ROOT_URI}" data-ajax="false" class="ui-btn ui-icon-home ui-btn-icon-notext ui-corner-all ui-btn-left"></a>
    {/if} 
    
    <a data-ajax="false" href="/logout.php" class="ui-btn-right">Desconectarme</a>
  {/if}

</div><!-- /header -->
