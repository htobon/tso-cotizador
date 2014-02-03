{include file='head.tpl' jsIncludes=["jquery-mobile"] pageTitle="TSO Cotizador"}
<!-- script type="text/javascript" src="{$smarty.const.SMARTY_ROOT_URI}/js/simulador.js"></script -->
{include file='header.tpl'} 
<!-- div class="row">
    <div class="small-5 small-centered columns"><a href="/"><img src="{$smarty.const.SMARTY_IMG_URI}/tsoSimulator.png"></a></div>
</div-->
<div class="row">
    <a name="prueba" id="prueba" href="#test-aqui">TEST</a>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <br><br><b<br><br><br>r><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <br><br><br><br><br><br><br><br><br><br><br><br><br>
    <br>   <br><br><br><br><br><br><br><br><br><br><br><br>
    <div id="test-aqui" name="test-aqui">
        <h1>TEST!</h1>
    </div>
</div>
    <!--
{foreach from=$accesorios item=accesorio}    
    <div id='modal-accesorio-{$accesorio->id}'>
        <h2>{$accesorio->nombre}</h2> 
        <img class="accesorio-img" src="{$smarty.const.SMARTY_IMG_URI}/accesorios/{$accesorio->codAccesorio}.jpg"/><p class="descripcion">{$accesorio->descripcion}</p>

        <a class="close-reveal-modal">&#215;</a>
    </div>
{/foreach}
    -->

{include file='footer.tpl'}



