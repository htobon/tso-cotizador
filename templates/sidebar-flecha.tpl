{if $direccion=="derecha"}
  <div id="sidebar-next" class="sidebar-right">
{/if}
{if $direccion=="izquierda"}
  <div id="sidebar-next" class="sidebar-left">
{/if}
    <a href="{$link}" data-transition="flip" class="ui-btn ui-shadow ui-corner-all 
       {if $direccion=="derecha"}ui-btn-icon-left ui-icon-carat-r{/if}
       {if $direccion=="izquierda"}ui-btn-icon-right ui-icon-carat-l{/if}
       "></a>
</div>