{include file='head.tpl' jsIncludes=["jquery-mobile"] pageTitle="TSO Cotizador"}

{* Asignando header como variable sin el boton return *}
{include file='header.tpl' assign="header" ocultarReturn="1"}

{* Asignando footer como variable *}
{include file='footer.tpl' assign="footer"}
<script>
  var gpsIncompatiblesAccesorio = {$gpsIncompatiblesAccesorio|@json_encode};
  var accesoriosIncompatiblesGPS = {$accesoriosIncompatiblesGPS|@json_encode};
  var planesIncompatiblesAccesorio = {$planesIncompatiblesAccesorio|@json_encode};
  var accesoriosIncompatiblesPlanes = {$accesoriosIncompatiblesPlanes|@json_encode};
</script>
<script type="text/javascript" src="{$smarty.const.SMARTY_ROOT_URI}/js/main.js"></script>
<script type="text/javascript" src="{$smarty.const.SMARTY_ROOT_URI}/js/cotizador.js"></script>


<form>
  <!-- Selección de Accesorios en el Camión -->
  <div id="seleccion-accesorios" data-role="page" class="container">
    {$header}
    <div id="sidebar" class="sidebar"></div>
    <div class="row content" data-role="content">
      {include file='sections/camion.tpl'}     
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
    <div id="sidebar" class="sidebar">{include file='sidebar-flecha.tpl' direccion="derecha" link="#adicionales"}</div>

    <div id="accesorios-seleccionados" data-role="panel" data-position="right" data-display="overlay" data-fullscreen="true">
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
    <div id="sidebar" class="sidebar">{include file='sidebar-flecha.tpl' direccion="izquierda" link="#seleccion-accesorios"}</div> 

    <div class="row content" data-role="content">
      <!-- Esto representa toda una fila horizontal -->
      <div id="primer-fila" class="fila">
        <div id="planes-servicio" class="seccion">
          <br> 
          <h1>Planes de servicio</h1>
          <select name="plan" id="plan" data-native-menu="false" data-mini="true">
            <option value="-1" data-placeholder="true">Seleccione un Plan</option>
            {foreach from=$planes item=plan}
              <option value="{$plan->id}" id="plan-{$plan->id}" >{$plan->nombre} ({$plan->precio})</option>
            {/foreach}
          </select>          
        </div>
        <div id="descuentos" class="seccion">
          <br>
          <h1>Cantidad de Vehículos</h1>          
          <select name="descuento" id="descuento" data-native-menu="false" data-mini="true">
            <option value="-1" data-placeholder="true">Seleccione un rango</option>
            {foreach from=$descuentos item=descuento}
              {if $descuento->cantidadMax > 9999}
              {/if}
              <option value="{$descuento->id}">{$descuento->cantidadMin} - {$descuento->cantidadMax} vehiculos ({$descuento->descuento}%)</option>
            {/foreach}
          </select>
        </div>
      </div>

      <div id="segunda-fila" class="fila">
        <div id="contratos" class="seccion">
          <br>
          <h1>Tipo de contrato</h1>
          <select name="contrato" id="contrato" data-native-menu="false" data-mini="true">
            <option value="-1" data-placeholder="true">Seleccione un contrato</option>
            <option value="1">Comodato</option>
            <option value="2">Compra</option>
          </select>
        </div>

        <div id="duraciones" class="seccion">
          <br>
          <h1>Número de meses</h1>
          <select name="duracion" id="duracion" data-native-menu="false" data-mini="true">
            <option value="-1" data-placeholder="true">Seleccione un rango</option>
            {foreach from=$duraciones item=duracion}
              <option value="{$duracion->id}">{$duracion->cantidadMeses} Meses</option>
            {/foreach}
          </select>
        </div>
      </div> 
    </div> 
    <div id="sidebar" class="sidebar">{include file='sidebar-flecha.tpl' direccion="derecha" link="#prev-cotizacion"}</div>  
    {$footer}
  </div>

  <!-- Previsualización de la cotización dependiendo de la información seleccionada con anterioridad -->
  <div id="prev-cotizacion" data-role="page" class="container">
    {$header}  
    <div id="sidebar" class="sidebar">{include file='sidebar-flecha.tpl' direccion="izquierda" link="#adicionales"}</div>     
    <div class="row content" data-role="content">
      <h1>Condiciones de venta:</h1>
      <table border="1">
        <!-- Unidad GPS -->
        <tr>
          <td class="titulo">Unidad GPS</td>
          <td></td>
        </tr>
        {foreach from=$arregloGps item=gps}
          <tr id="gps-{$gps->id}" class="item">
            <td>{$gps->nombre}</td>
            <td>${$gps->precioUnidad|number_format:2}</td>
          </tr>
        {/foreach}
        <!-- Accesorios -->
        <tr>
          <td class="titulo">Accesorios</td>
          <td></td>
        </tr>
        {foreach from=$accesorios item=accesorio}
          <tr id="accesorio-{$accesorio->id}" class="item">
            <td>{$accesorio->nombre}</td>
            <td>${$accesorio->precioAccesorio|number_format:0}</td>
          </tr>
        {/foreach}
        <!-- Instalación de accesorios-->
        <tr>
          <td class="titulo">Instalaciones</td>
          <td></td>
        </tr>
        {foreach from=$accesorios item=accesorio}
          <tr id="instalacion-{$accesorio->id}" class="item">
            <td>Instalación {$accesorio->nombre}</td>
            <td>${$accesorio->precioInstalacion|number_format:0}</td>
          </tr>
        {/foreach}
      </table>
    </div>
    <div id="sidebar" class="sidebar"></div>  
    {$footer}
  </div> 

</div>

</form>
