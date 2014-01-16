<!DOCTYPE HTML>
<html>
    <head>
        <title>{$pageTitle|default:'TSO Mobile'}</title>
        
        <!-- Main Stylesheets and javascripts -->
        <link rel="stylesheet" type="text/css" href="{$smarty.const.SMARTY_CSS_URI}/main.css" />
        <link rel="stylesheet" type="text/css" href="{$smarty.const.SMARTY_LIBS_URI}/Foundation-5.0.3/css/foundation.css" />
   		
        
        <!-- Custom Stylesheets and Javascripts -->
        {if $jsIncludes}
            {foreach from=$jsIncludes item=jsToInclude}
                {if $jsToInclude == "jquery"}
                    <script type="text/javascript" src="{$smarty.const.SMARTY_LIBS_URI}/JQuery/jquery-1.10.2.min.js"></script>  
                {elseif $jsToInclude == "modernizr"}
                    <!-- Reference: JavaScript library that detects HTML5 and CSS3 features in the user’s browser. -->
                    <script type="text/javascript" src="{$smarty.const.SMARTY_LIBS_URI}/Foundation-5.0.3/js/modernizr.js"></script> 
                {/if}
            {/foreach}
        {/if}
        
    </head>
    
    <body>
        
    