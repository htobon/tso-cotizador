<!DOCTYPE HTML>
<html>
    <head>
        <title>{$pageTitle|default:'TSO Mobile'}</title>
        
        <!-- Main Stylesheets and javascripts -->
        <link rel="stylesheet" type="text/css" href="{$smarty.const.SMARTY_CSS_URI}/main.css" />
        
        <!-- Custom Stylesheets and Javascripts -->
        {if $jsIncludes}
            {foreach from=$jsIncludes item=jsToInclude}
                {if $jsToInclude == "jquery"}
                    <script src="{$smarty.const.SMARTY_LIBS_URI}/JQuery/jquery-1.10.2.min.js"></script>                
                {/if}
            {/foreach}
        {/if}
        
    </head>
    
    <body>
        
    