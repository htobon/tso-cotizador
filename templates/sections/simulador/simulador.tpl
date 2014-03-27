{include file='head.tpl' jsIncludes=["jquery-mobile"] pageTitle="TSO Cotizador"}
<script type="text/javascript" src="{$smarty.const.SMARTY_ROOT_URI}/js/simulador.js"></script>
{include file='header.tpl'} 
 
<div class="row">
    {include file='sections/camion.tpl'}    
</div>
{foreach from=$accesorios item=accesorio}    
    <div id='modal-accesorio-{$accesorio->id}' class="modal-accesorio" data-role="popup">
        <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a> 
       	<div class="titulo"><h2>{$accesorio->nombre|upper}</h2> </div>
       	<div class="imagen"><img class="accesorio-img" src="{$smarty.const.SMARTY_IMG_URI}/accesorios/{$accesorio->image}.jpg"/></div>
       	<div class="contenido"> 
       		<div data-role="collapsible-set" data-content-theme="a" data-iconpos="right" id="set">
			    <div data-role="collapsible" id="set1" data-collapsed="false">
			        <h3>Descripción</h3>
			        <p class="descripcion">{$accesorio->descripcion}</p>
			    </div>
			    <div data-role="collapsible" id="set2" data-collapsed="true">
			        <h3>Beneficios</h3>
			        <p class="descripcion">{$accesorio->beneficios}</p>
			    </div>
			    <div data-role="collapsible" id="set3" data-collapsed="true">
			        <h3>Aplicación</h3>
			        <p class="aplicacion">{$accesorio->aplicacion}</p>
		        	<div class="imagenes-aplicacion" >
				        {if !empty( $accesorio->imagen_aplicacion_uno ) }
				        	<img class="accesorio-img-app" src="{$smarty.const.SMARTY_IMG_URI}/accesorios/aplicacion/{$accesorio->imagen_aplicacion_uno}.jpg"/>
			        	{/if}
				        	
				        {if !empty( $accesorio->imagen_aplicacion_dos ) }
				        	<img class="accesorio-img-app" src="{$smarty.const.SMARTY_IMG_URI}/accesorios/aplicacion/{$accesorio->imagen_aplicacion_dos}.jpg"/>
			        	{/if}
		        	</div>
			    </div>
			</div>
       	</div>
       	<div class="clear"></div>
	  </div>
{/foreach}

{include file='footer.tpl'}


