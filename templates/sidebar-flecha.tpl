{if $direccion=="derecha"}
  <div id="sidebar-next" class="sidebar-right">
{/if}
{if $direccion=="izquierda"}
  <div id="sidebar-next" class="sidebar-left">
{/if}
    <a href="{$link}" data-transition="flip" class="ui-btn ui-shadow ui-corner-all ui-btn-icon-right 
       {if $direccion=="derecha"}ui-icon-carat-r{/if}
       {if $direccion=="izquierda"}ui-icon-carat-l{/if}
       "></a>
</div>