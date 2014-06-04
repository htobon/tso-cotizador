{include file='head.tpl' jsIncludes=["jquery-mobile"] pageTitle="TSO Cotizador"}

{* Asignando header como variable sin el boton return *}
{include file='header.tpl' assign="header" }

{* Asignando footer como variable *}
{include file='footer.tpl' assign="footer"}
<script>
    var gpsIncompatiblesAccesorio = {$gpsIncompatiblesAccesorio|@json_encode};
    var accesoriosIncompatiblesGPS = {$accesoriosIncompatiblesGPS|@json_encode};
    var planesIncompatiblesAccesorio = {$planesIncompatiblesAccesorio|@json_encode};
    var accesoriosIncompatiblesPlanes = {$accesoriosIncompatiblesPlanes|@json_encode};
    var arregloGpsJSON = {$arregloGps|@json_encode};
    var planesJSON ={$planes|@json_encode};
    var duracionesJSON = {$duraciones|@json_encode};
    var descuentosJSON = {$descuentos|@json_encode};
    var accesoriosJSON = {$accesorios|@json_encode};

</script>
<script type="text/javascript" src="{$smarty.const.SMARTY_ROOT_URI}/js/main.js"></script>
<script type="text/javascript" src="{$smarty.const.SMARTY_ROOT_URI}/js/cotizador.js"></script>


<!--form name="cotizador" method="POST" action="../../../pages/sections/cotizador/generarCotizacion.php"-->
<!--form name="cotizador" data-ajax="false" method="POST" action="{$smarty.const.SMARTY_ROOT_URI}/sections/cotizador/generarCotizacion.php"-->
<form name="cotizador" data-ajax="false" method="POST" action="generarCotizacion.php">
    <!-- Selección de Accesorios en el Camión -->
    <div id="seleccion-accesorios" data-role="page" class="container">
        {$header}
        <div id="sidebar" class="sidebar"></div>
        <div class="row content" data-role="content">
            {include file='sections/camion.tpl'}
            <div id='modal-unidad-gps' class="modal-accesorio" data-role="popup">
                <div class="row">
                    <fieldset data-role="controlgroup"> 
                        <legend>Seleccione una unidad GPS:</legend>
                        {foreach from=$arregloGps item=gps}
                            <input type="radio" name="gps" value="{$gps->id}" id="gps-{$gps->id}">
                            <label for="gps-{$gps->id}">{$gps->nombre}</label>
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
            <div id="planes-servicio" class="seccion">
                <br> 
                <h1>Planes de servicio</h1>
                <select name="plan" id="plan" data-native-menu="false" data-mini="true">
                    <option value="-1" data-placeholder="true">Seleccione un Plan</option>
                    {foreach from=$planes item=plan}
                        <option value="{$plan->id}" id="plan-servicio-{$plan->id}" >{$plan->nombre} ({$plan->precio})</option>
                    {/foreach}
                </select>
            </div>

            <div id="cantidades" class="seccion">
                <br>
                <h1>Cantidad Accesorios</h1>
                <div id="accesorios-seleccionados">
                    <a href="#tabla-cantidad-accesorios" class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-mini" data-rel="popup" data-position-to="window" data-transition="pop">Definir Cantidades</a>          
                    <div id="tabla-cantidad-accesorios" data-role="popup" class="ui-content">
                        <a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right">Close</a>
                        <table>
                            <thead>
                                <tr>
                                    <th>Accesorio</th>
                                    <th>Cantidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id="unidad-gps">
                                    <th><label for="cantidad-unidad-gps">Unidad Satelital</label></th>
                                    <th><input id="cantidad-unidad-gps" name="cantidad-unidad-gps" type="number" data-clear-btn="false" data-mini="true" value=""></th>
                                </tr>
                                {foreach from=$accesorios item=accesorio}
                                    <tr id="accesorio-{$accesorio->id}" class="item">
                                        <th><label for="cantidad-accesorio-{$accesorio->id}">{$accesorio->nombre}</label></th>
                                        <!--th><input id="cantidad-accesorio-{$accesorio->id}" name="cantidad-accesorio-{$accesorio->id}" type="number" data-clear-btn="false" data-mini="true" value=""></th-->
                                        <th>
                                            <input id="cantidad-accesorio-{$accesorio->id}" name="accesorios[{$accesorio->id}][cantidad-accesorio]" type="number" data-clear-btn="false" data-mini="true" value="">
                                            <input id="" name="accesorios[{$accesorio->id}][id]" type="hidden" data-clear-btn="false" data-mini="true" value="{$accesorio->id}">
                                        </th>
                                    </tr>
                                {/foreach}
                                <tr>
                                    <td colspan="2">
                                        <a href="#" data-rel="back" class="ui-btn ui-shadow ui-btn-a">Guardar</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>

                <a href="#tabla-descuentos" class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-mini" data-rel="popup" data-position-to="window" data-transition="pop">Tabla</a>
                <div id="tabla-descuentos" data-role="popup" class="ui-content">
                    <a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right">Close</a>
                    <table border="1" >
                        <thead>
                            <tr>
                                <th>Rango</th>
                                <th>Descuento</th>
                            </tr>
                        </thead>
                        <tbody>
                            {foreach from=$descuentos item=descuento}
                                <tr>
                                    <th>{if $descuento->cantidadMax > 9999} {$descuento->cantidadMin-1} > {else}{$descuento->cantidadMin} - {$descuento->cantidadMax}{/if}</th>
                                    <th>{$descuento->descuento}%</th>
                                </tr>
                            {/foreach}
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="contratos" class="seccion">
                <br>
                <h1>Tipo de contrato</h1>
                <select id="contrato" name="contrato" data-native-menu="false" data-mini="true">
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


        </div> <!-- end content-->

        <div id="sidebar" class="sidebar">{include file='sidebar-flecha.tpl' direccion="derecha" link="#prev-cotizacion"}</div>
        {$footer}
    </div>

    <!-- Previsualización de la cotización dependiendo de la información seleccionada con anterioridad -->
    <div id="prev-cotizacion" data-role="page" class="container">
        {$header}  
        <div id="sidebar" class="sidebar">{include file='sidebar-flecha.tpl' direccion="izquierda" link="#adicionales"}</div>     
        <div class="row content" data-role="content">
            <h1>Condiciones de venta:</h1>
            <table border="0">
                <!-- Unidad GPS -->
                <tr>
                    <th>Unidad GPS</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Precio TOTAL</th>
                </tr>
                {foreach from=$arregloGps item=gps}
                    <tr id="gps-{$gps->id}" class="item">
                        <td>{$gps->nombre}</td>
                        <td class="cantidad">999</td>
                        <td class="precioUnitario">0</td>
                        <td class="precioTotal">0</td>
                    </tr>
                {/foreach}
                <!-- Accesorios -->
                <tr>
                    <th>Accesorios</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                {foreach from=$accesorios item=accesorio}
                    <tr id="accesorio-{$accesorio->id}" class="item">
                        <td>{$accesorio->nombre}</td>
                        <td class="cantidad"></td>
                        <td class="precioUnitario">0</td>
                        <td class="precioTotal">0</td>
                    </tr>
                {/foreach}
                <!-- Instalación de Accesorios y Unidad GPS-->
                <tr>
                    <th>Instalaciones</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>        
                {foreach from=$accesorios item=accesorio}
                    <tr id="instalacion-accesorio-{$accesorio->id}" class="item">
                        <td>Instalación {$accesorio->nombre}</td>
                        <td class="cantidad"></td>
                        <td class="precioUnitario">0</td>
                        <td class="precioTotal">0</td>
                    </tr>
                {/foreach}        
                {foreach from=$arregloGps item=gps}
                    <tr id="instalacion-gps-{$gps->id}" class="item">
                        <td>Instalación {$gps->nombre}</td>
                        <td class="cantidad">999</td>
                        <td class="precioUnitario">0</td>
                        <td class="precioTotal">0</td>
                    </tr>
                {/foreach}
                <!-- Tipo de plan -->
                <tr>
                    <th>Tipo de Plan</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                {foreach from=$planes item=plan}
                    <tr id="plan-{$plan->id}" class="item">
                        <td>{$plan->nombre}</td>
                        <td class="cantidad">999</td>
                        <td class="precioUnitario">0</td>
                        <td class="precioTotal">0</td>            
                    </tr>
                {/foreach}
                {foreach from=$accesorios item=accesorio}
                    <tr id="mensualidad-accesorio-{$accesorio->id}" class="item">
                        <td>Mensualidad {$accesorio->nombre}</td>
                        <td class="cantidad"></td>
                        <td class="precioUnitario">0</td>
                        <td class="precioTotal">0</td>
                    </tr>
                {/foreach}
                <!-- Número de Vehículos -->
                <tr id="numero-vehiculos">
                    <th>Número de vehículos</th>
                    <th class="item">25</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                <!-- Porcentaje de descuento -->
                <tr id="porcentaje-descuento">
                    <th>Porcentaje de descuento</th>
                    <th class="item">0%</th>
                    <th><input type="hidden" id="descuento" name="descuento" value=""/></th>
                    <th></th>
                </tr>

                <!-- Tipo de contrato -->
                <tr id="tipo-contrato">
                    <th>Tipo de Contrato</th>          
                    <th id="contrato-1" class="item">Comodato</th>
                    <th id="contrato-2" class="item">Compra</th>
                    <th></th>
                    <th></th>
                </tr>

                <!-- Duración del contrato -->
                <tr id="duracion">
                    <th>Duración del contrato</th>
                    <th class="cantidad">0 meses</th>
                    <th></th>
                    <th></th>
                </tr>             

                <!-- Valor del descuento -->
                <tr id="descuentos">
                    <th>Descuentos</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                {foreach from=$planes item=plan}
                    <tr id="descuento-plan-{$plan->id}" class="item">
                        <td>{$plan->nombre}</td>
                        <td class="cantidad">999</td>
                        <td class="precioUnitario">0</td>
                        <td class="precioTotal">0</td>            
                    </tr>
                {/foreach}
                {foreach from=$accesorios item=accesorio}
                    <tr id="descuento-mensualidad-accesorio-{$accesorio->id}" class="item">
                        <td>Mensualidad {$accesorio->nombre}</td>
                        <td class="cantidad"></td>
                        <td class="precioUnitario">0</td>
                        <td class="precioTotal">0</td>
                    </tr>
                {/foreach}
                <!-- TOTALES -->
                <tr id="totales">
                    <th>TOTALES</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                <tr id="total-unidad-gps" class="item">
                    <td>Total Unidad GPS</td>
                    <td class="cantidad">999</td>
                    <td class="precioUnitario">0</td>
                    <td class="precioTotal">0</td>            
                </tr>
                <tr id="total-accesorios" class="item">
                    <td>Total Accesorios</td>
                    <td class="cantidad">999</td>
                    <td class="precioUnitario">0</td>
                    <td class="precioTotal">0</td>            
                </tr>
                <tr id="total-instalaciones" class="item">
                    <td>Total Instalaciones</td>
                    <td class="cantidad">999</td>
                    <td class="precioUnitario">0</td>
                    <td class="precioTotal">0</td>            
                </tr>
                <br>
                <tr id="total-plan-sin-descuento" class="item">
                    <td>Valor Plan Sin Descuento</td>
                    <td class="cantidad">999</td>
                    <td class="precioUnitario">0</td>
                    <td class="precioTotal">0</td>            
                </tr>
                <tr id="total-plan-comodato-sin-descuento" class="item">
                    <td>Valor Plan COMODATO Sin Descuento</td>
                    <td class="cantidad">999</td>
                    <td class="precioUnitario">0</td>
                    <td class="precioTotal">0</td>            
                </tr>
                <tr id="total-descuento" class="item">
                    <td>Descuento</td>
                    <td class="cantidad">999</td>
                    <td class="precioUnitario">0</td>
                    <td class="precioTotal">0</td>            
                </tr>               
                <tr id="total-plan-mensual" class="item">
                    <td>Valor Plan Mensual</td>
                    <td class="cantidad"></td>
                    <td class="precioUnitario">0</td>
                    <td class="precioTotal">0</td>            
                </tr>
                <tr id="total-plan-comodato-mensual" class="item">
                    <td>Valor Plan COMODATO Mensual</td>
                    <td class="cantidad"></td>
                    <td class="precioUnitario">0</td>
                    <td class="precioTotal">0</td>            
                </tr>
            </table>
        </div>
        <div id="sidebar" class="sidebar">{include file='sidebar-flecha.tpl' direccion="derecha" link="#datos-cliente"}</div>
        {$footer}
    </div> 

    <div id="datos-cliente" data-role="page" class="container">
        {$header}  
        <div id="sidebar" class="sidebar">{include file='sidebar-flecha.tpl' direccion="izquierda" link="#prev-cotizacion"}</div>     
        <div class="row content" data-role="content">
            <h1>Datos de Contacto:</h1>
            <table id="tbldatos-cliente">
                <tr>
                    <td>Empresa</td>
                    <!--td><input type="text" id="empresa" name="empresa" placeholder="Empresa"></td-->
                    <td>
                        <input type="hidden" id="empresa" name="empresa">
                        <ul id="autocomplete" name="empresa" data-role="listview" data-inset="true" data-filter="true" data-filter-placeholder="Buscar Empresa" data-filter-theme="a"></ul>
                    </td>
                </tr>
                <tr>
                    <td>Nit</td>
                    <td><input type="text" id="nit" name="nit" placeholder="Nombre"></td>
                </tr>

                <tr>
                    <td>Nombre contacto</td>
                    <td><input type="text" id="nombre" name="nombre" placeholder="Nombre"></td>
                </tr>
                <tr>
                    <td>Correo</td>
                    <td><input type="text" id="correo" name="correo" placeholder="Correo"></td>
                </tr>
                <tr>
                    <td>Correo 2</td>
                    <td><input type="text" id="correo2" name="correo2" placeholder="Correo 2"></td>
                </tr>
                <tr><td colspan="2"><div id="msgError" style="text-align:center;"></div></td></tr>
                <tr>
                    <td>
                        <input type="submit" id="enviar" name="enviar" class="ui-btn" value="Generar Cotización"/>
                    </td>
                    <td>
                        <a href="#seleccion-accesorios" class="ui-btn">Volver</a>
                    </td>
                </tr>
            </table>
        </div>
        <div id="sidebar" class="sidebar"></div>  
        {$footer}
    </div>

</div>
</form>
