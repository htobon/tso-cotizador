{include file='head.tpl' jsIncludes=["jquery-mobile"] pageTitle="TSO Cotizador"}
<script type="text/javascript" src="{$smarty.const.SMARTY_ROOT_URI}/js/simulador.js"></script>
{include file='header.tpl'} 
<div id="simulador">
  <div class="row">
    {include file='sections/camion.tpl'}    
  </div>
  {foreach from=$accesorios item=accesorio}    
    {if $accesorio->id == -1}
    <div id='modal-unidad-gps' class="modal-accesorio" data-role="popup" data-position-to="#simulador">
    {else}
    <div id='modal-accesorio-{$accesorio->id}' class="modal-accesorio" data-role="popup">
    {/if}
      <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a> 
      <div class="titulo"><h2>{$accesorio->nombre|upper}</h2> </div>
      <div class="imagen"><img class="accesorio-img" src="{$smarty.const.SMARTY_IMG_URI}/accesorios/{$accesorio->image}.jpg"/></div>
      <div class="contenido"> 
        <div id="acordeon" data-role="collapsible-set" data-content-theme="a" data-iconpos="right" data-mini="true">
          <div id="acordeon-descripcion" data-role="collapsible" data-collapsed="false">
            <h3>Descripción</h3>
            <p class="descripcion">{$accesorio->descripcion}</p>
          </div>
          <div id="acordeon-beneficios" data-role="collapsible" data-collapsed="true">
            <h3>Beneficios</h3>
            <p class="descripcion">{$accesorio->beneficios}</p>
          </div>
          <div id="acordeon-aplicacion" data-role="collapsible" data-collapsed="true">
            <h3>Aplicación</h3>
            <p class="aplicacion">{$accesorio->aplicacion}</p>
            <div class="imagenes-aplicacion" >
              {if !empty( $accesorio->imagen_aplicacion_uno ) }
                <a href="{$smarty.const.SMARTY_IMG_URI}/accesorios/aplicacion/{$accesorio->imagen_aplicacion_uno}.jpg" target="_blank">
                  <img class="accesorio-img-app" src="{$smarty.const.SMARTY_IMG_URI}/accesorios/aplicacion/{$accesorio->imagen_aplicacion_uno}.jpg"/>
                </a>
              {/if} 
              {if !empty( $accesorio->imagen_aplicacion_dos ) }
              <a href="{$smarty.const.SMARTY_IMG_URI}/accesorios/aplicacion/{$accesorio->imagen_aplicacion_dos}.jpg" target="_blank">
                <img class="accesorio-img-app" src="{$smarty.const.SMARTY_IMG_URI}/accesorios/aplicacion/{$accesorio->imagen_aplicacion_dos}.jpg"/>
               </a>
              {/if}
            </div>
          </div>
        </div>
      </div>
      <div class="clear"></div>
    </div>
  {/foreach}
</div>

{include file='footer.tpl'}


