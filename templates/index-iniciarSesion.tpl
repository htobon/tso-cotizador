{include file='head.tpl' jsIncludes=["jquery","jquery-mobile"] pageTitle="TSO Cotizador"}

{include file='header.tpl'}

<div class="row">
    <div class="logo"><a href=""><img src="{$smarty.const.SMARTY_IMG_URI}/logoSmall.png"></a></div>
</div>
<div class="row">
    <div class="login"> 
        {if isset($error)}
            <div data-alert class="alert-box error radius">
               {$error} 
              <a href="#" class="close">&times;</a>
            </div>
            
        {/if}
        <form method="post" action="pages/index.php" data-ajax="false">
            <fieldset>
                <legend>Iniciar sesión</legend> 
                <label>Email:</label> <input type="text" name="correo" placeholder="Correo"><br>
                <label>Contraseña:</label> <input type="password" name="password" placeholder="Contraseña"><br>
                
                <button type="submit" name="enviar" class="ui-shadow ui-btn ui-corner-all" data-role="none">Entrar</button>


            </fieldset>
        </form>
    </div>
</div> 

{include file='footer.tpl'}



