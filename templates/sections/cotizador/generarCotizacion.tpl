{include file='head.tpl' jsIncludes=["jquery","jquery-mobile"] pageTitle="TSO Cotizador"}

{include file='header.tpl'} 

<script type="text/javascript" src="{$smarty.const.SMARTY_ROOT_URI}/js/main.js"></script>
<script type="text/javascript" src="{$smarty.const.SMARTY_ROOT_URI}/js/cotizador.js"></script>

<div id="menu-principal" class="ui-body-a ui-body ui-corner-all">

    <div id="nav-bar" class="center_content"> 
        <h4>{$mensajeCotizacion}</h4>
        <p><b>{$mensajeCorreosEnviados}</b></p>
        {if !$error }
            <a data-ajax="false" target="_blank" rel="external" href="{$smarty.const.SMARTY_ROOT_URI}{$nombreCotizacion}" data-icon="star" class="getpdf" >
                <img src="{$smarty.const.SMARTY_ROOT_URI}/images/thumb_pdf.png" alt="Ver PDF">
            </a>  
        {/if} 
    </div><!-- /navbar -->      
</div>

{include file='footer.tpl'}

