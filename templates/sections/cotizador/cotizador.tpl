{include file='head.tpl' jsIncludes=["jquery-mobile"] pageTitle="TSO Cotizador"}

{* Asignando header como variable sin el boton return *}
{include file='header.tpl' assign="header" ocultarReturn="1"}

{* Asignando footer como variable *}
{include file='footer.tpl' assign="footer"}
<script>
  var gpsIncompatibles = {$gpsIncompatibles|@json_encode};
  var accesoriosIncompatibles = {$accesoriosIncompatibles|@json_encode};
</script>
<script type="text/javascript" src="{$smarty.const.SMARTY_ROOT_URI}/js/cotizador.js"></script>


<!-- Selección de Accesorios en el Camión -->
<div id="seleccion-accesorios" data-role="page" class="con">  
  {$header}  
  <div class="row content" data-role="content">
    {include file='sections/camion.tpl'}    
    {include file='sidebar-flecha.tpl' direccion="derecha" link="#seleccion-adicionales"}
    <div id='modal-accesorio-7' class="modal-accesorio" data-role="popup">
      <form>
        <div class="row">
          <fieldset data-role="controlgroup"> 
            <legend>Seleccione una unidad GPS:</legend>
            {foreach from=$arregloGps item=gps}
              <input type="radio" name="losgps" value="{$gps->id}" id="gps-{$gps->id}">
              <label for="gps-{$gps->id}">{$gps->nombre} ({$gps->precioUnidad})</label>
            {/foreach}
          </fieldset>
        </div>
      </form>
    </div>    
  </div>   
  {$footer}
</div>


<!-- div id="mypanel" data-role="panel" data-position="right" data-display="overlay" data-fullscreen="true">
  <div id="accesorios-contenido" class="ui-body-a ui-body ui-corner-all">
    <fieldset data-role="controlgroup">
      <legend>Accesorios Seleccionados:</legend>
{foreach from=$accesorios item=accesorio}
  <input type="checkbox" name="checkbox-accesorios" id="checkbox-accesorio-{$accesorio->id}" disabled="disabled">
  <label for="checkbox-accesorio-{$accesorio->id}">{$accesorio->nombre}</label>  
{/foreach}
</fieldset>
</div>
<a href="#" class="ui-btn ui-icon-delete ui-btn-icon-left" data-rel="close">Cerrar</a>
</div --><!-- /panel -->


<!-- Selección de datos adicionales (Planes, tipo de contrato, etc.). -->
<div id="seleccion-adicionales" data-role="page">
  {$header}  
  {include file='sidebar-flecha.tpl' direccion="izquierda" link="#seleccion-accesorios"}  
  <div class="row">
    <br>
    <br>
    <h1>Datos Adicionales</h1>
    <p>
      Para este paso la interfaz gráfica mostrará dos tablas. La primera contendrá los diferentes planes que ofrece la empresa, opcionalmente se puede agregar una descripción detallada del plan que se despliega al hacer clic en el ícono correspondiente.
      La segunda tabla que muestra la interfaz permite seleccionar el tipo de contrato (Compra o Comodato). Finalmente se selecciona la cantidad de vehículos y se presiona el botón siguiente para conocer la cotización.
    </p>  
  </div>
  {$footer}
</div>
