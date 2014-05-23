{include file='head.tpl' jsIncludes=["jquery","jquery-mobile"] pageTitle="TSO Cotizador"}

{include file='header.tpl'} 

<div id="menu-principal" class="ui-body-a ui-body ui-corner-all">
    
    <div id="nav-bar" class="center_content"> 
        <h4>{$mensajeCotizacion}</h4>
        <a data-ajax="false" target="_blank" rel="external" href="#" data-icon="star">
            <img src="{$smarty.const.SMARTY_ROOT_URI}/images/thumb_pdf.png" alt="Ver PDF">
        </a>  
    </div><!-- /navbar -->      
</div>

{include file='footer.tpl'}

