<!DOCTYPE HTML>
<html lang="es">
    <head>
        <title>{$pageTitle|default:'TSO Mobile'}</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1"> 
        <!-- Main Stylesheets and javascripts -->

       
               
        <!-- Custom Stylesheets and Javascripts -->
        {if $jsIncludes}
            {foreach from=$jsIncludes item=jsToInclude}
                {if $jsToInclude == "jquery"}
                    <script type="text/javascript" src="{$smarty.const.SMARTY_LIBS_URI}/JQuery/jquery-1.10.2.min.js"></script>   
                {elseif $jsToInclude == "jquery-mobile"}
                    <link rel="stylesheet" type="text/css" href="{$smarty.const.SMARTY_LIBS_URI}/JQuery-mobile/jquery.mobile-1.4.0.min.css" />
                    <script type="text/javascript" src="{$smarty.const.SMARTY_LIBS_URI}/JQuery/jquery-1.10.2.min.js"></script>
                    <script type="text/javascript" src="{$smarty.const.SMARTY_LIBS_URI}/JQuery-mobile/jquery.mobile-1.4.0.min.js"></script>
                {elseif $jsToInclude == "foundation"} 
                    <script src="{$smarty.const.SMARTY_LIBS_URI}/Foundation-5.0.3/js/foundation/foundation.js"></script>    
                {elseif $jsToInclude == "modernizr"}
                    <!-- Reference: JavaScript library that detects HTML5 and CSS3 features in the user’s browser. -->
                    <script type="text/javascript" src="{$smarty.const.SMARTY_LIBS_URI}/Foundation-5.0.3/js/modernizr.js"></script> 
                {elseif $jsToInclude == "reveal"} 
                    <link rel="stylesheet" type="text/css" href="{$smarty.const.SMARTY_LIBS_URI}/reveal/reveal.css" /> 
                    <script type="text/javascript" src="{$smarty.const.SMARTY_LIBS_URI}/reveal/jquery-1.4.4.min.js"></script> 
                    <script type="text/javascript" src="{$smarty.const.SMARTY_LIBS_URI}/reveal/jquery.reveal.js"></script> 
                {elseif $jsToInclude == "alert"} 
                    <script src="{$smarty.const.SMARTY_LIBS_URI}/Foundation-5.0.3/js/foundation/foundation.alert.js"></script>
                {elseif $jsToInclude == "tablesorter"} 
                    <script src="{$smarty.const.SMARTY_LIBS_URI}/JQuery-tablesorter-2.0.5/jquery.tablesorter.min.js"></script>  
                {/if}
            {/foreach}
        {/if}
        
        <script type="text/javascript" src="{$smarty.const.SMARTY_ROOT_URI}/js/main.js"></script>

    </head>

    <body>

