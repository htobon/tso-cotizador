<!DOCTYPE HTML>
<html lang="es">
    <head>
        <title>{$pageTitle|default:'TSO Mobile'}</title>
        <meta charset="utf-8" />
   		<meta name="viewport" content="width=device-width, initial-scale=1.0" /> 
        <!-- Main Stylesheets and javascripts -->
        
        <link rel="stylesheet" type="text/css" href="{$smarty.const.SMARTY_LIBS_URI}/Foundation-5.0.3/css/foundation.css" />
        <link rel="stylesheet" type="text/css" href="{$smarty.const.SMARTY_CSS_URI}/main.css" /> 
        <script type="text/javascript" src="{$smarty.const.SMARTY_ROOT_URI}/JS/main.js"></script>
        <!-- Custom Stylesheets and Javascripts -->
        {if $jsIncludes}
            {foreach from=$jsIncludes item=jsToInclude}
                {if $jsToInclude == "jquery"}
                    <script type="text/javascript" src="{$smarty.const.SMARTY_LIBS_URI}/JQuery/jquery-1.10.2.min.js"></script>   
                {elseif $jsToInclude == "modernizr"}
                    <!-- Reference: JavaScript library that detects HTML5 and CSS3 features in the user’s browser. -->
                    <script type="text/javascript" src="{$smarty.const.SMARTY_LIBS_URI}/Foundation-5.0.3/js/modernizr.js"></script> 
                {elseif $jsToInclude == "reveal"} 
                    <link rel="stylesheet" type="text/css" href="{$smarty.const.SMARTY_LIBS_URI}/reveal/reveal.css" /> 
                    <script type="text/javascript" src="{$smarty.const.SMARTY_LIBS_URI}/reveal/jquery-1.4.4.min.js"></script> 
                    <script type="text/javascript" src="{$smarty.const.SMARTY_LIBS_URI}/reveal/jquery.reveal.js"></script> 

                {/if}
            {/foreach}
        {/if}

        
        
    </head>
    
    <body>
        
    