{include file='head.tpl' jsIncludes=["jquery","jquery-mobile"] pageTitle="TSO Cotizador"}

{include file='header.tpl'}

<div class="row">
    <div class="small-9 small-centered columns"><a href=""><img src="{$smarty.const.SMARTY_IMG_URI}/logoSmall.png"></a></div>
</div>
<div class="row">
    <div class="large-12 columns"> 
        {if isset($error)}
            <div data-alert class="alert-box error radius">
               {$error} 
              <a href="#" class="close">&times;</a>
            </div>
            
        {/if}
        <form method="post" action="/pages/index.php">
            <fieldset>
                <legend>Iniciar sesión</legend> 
                <label>Email:</label> <input type="text" name="correo" placeholder="Correo"><br>
                <label>Contraseña:</label> <input type="password" name="password" placeholder="Contraseña"><br>
                
                <button data-ajax="false" type="submit" name="enviar" class="ui-shadow ui-btn ui-corner-all">Entrar</button>

                <input type="button" data-theme="b" name="enviar" id="submit" value="Entrar">
            </fieldset>
        </form>
    </div>
</div> 

{include file='footer.tpl'}



