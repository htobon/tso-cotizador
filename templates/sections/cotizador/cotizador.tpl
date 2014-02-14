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


<form>
<!-- Selección de Accesorios en el Camión -->
  <div id="seleccion-accesorios" data-role="page" class="container">  
    {$header}  
    <div class="row content" data-role="content">
      {include file='sections/camion.tpl'}    
      {include file='sidebar-flecha.tpl' direccion="derecha" link="#adicionales"}
      <div id='modal-accesorio-7' class="modal-accesorio" data-role="popup">
        <div class="row">
          <fieldset data-role="controlgroup"> 
            <legend>Seleccione una unidad GPS:</legend>
            {foreach from=$arregloGps item=gps}
              <input type="radio" name="gps" value="{$gps->id}" id="gps-{$gps->id}">
              <label for="gps-{$gps->id}">{$gps->nombre} ({$gps->precioUnidad})</label>
            {/foreach}
          </fieldset>
        </div>
      </div>
    </div> 
    <div id="mypanel" data-role="panel" data-position="right" data-display="overlay" data-fullscreen="true">
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
    </div><!-- /panel -->
    {$footer}
  </div>


<!-- Selección de datos adicionales (Planes, tipo de contrato, etc.). -->
<div id="adicionales" data-role="page" class="container">
  {$header}  
  {include file='sidebar-flecha.tpl' direccion="izquierda" link="#seleccion-accesorios"}  
  <div class="row content" data-role="content">
    <div id="planes-servicio" class="seccion">
      <br>
      <br>
      <h1>Planes de servicio</h1>
      <fieldset data-role="controlgroup" data-mini="true">
        {foreach from=$planes item=plan}
          <input type="radio" name="planes" value="{$plan->id}" id="plan-{$plan->id}">
          <label for="plan-{$plan->id}">{$plan->nombre} ({$plan->precio})</label> 
        {/foreach}
      </fieldset>
    </div>

    <div id="descuentos" class="seccion">
      <br>
      <br>
      <h1>Descuentos</h1>
      <fieldset data-role="controlgroup" data-mini="true">
        {foreach from=$descuentos item=descuento}
          <input type="radio" name="descuentos" value="{$descuento->id}" id="descuento-{$descuento->id}">
          <label for="descuento-{$descuento->id}">{$descuento->cantidadMin} - {$descuento->cantidadMax} ({$descuento->descuento}%)</label> 
        {/foreach}
      </fieldset>
    </div>

    <div id="contratos" class="seccion">
      <br>
      <br>
      <h1>Tipo de contrato</h1>
      <fieldset data-role="controlgroup" data-mini="true">
        <input type="radio" name="contratos" value="1" id="contrato-1">
        <label for="contrato-1">Compra</label> 
        <input type="radio" name="contratos" value="2" id="contrato-2">
        <label for="contrato-2">Comodato</label>
      </fieldset>
    </div>

    <div id="duraciones" class="seccion">
      <br>
      <br>
      <h1>Número de meses</h1>
      <fieldset data-role="controlgroup" data-mini="true">
        {foreach from=$duraciones item=duracion}
          <input type="radio" name="duraciones" value="{$duracion->id}" id="duracion-{$duracion->id}">
          <label for="duracion-{$duracion->id}">{$duracion->cantidadMeses} Meses</label> 
        {/foreach}
      </fieldset>
    </div>

  </div> 

  {$footer}
</div>

</form>
