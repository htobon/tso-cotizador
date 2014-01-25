{include file='head.tpl' jsIncludes=["jquery","foundation","alert"] pageTitle="TSO Cotizador"}

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
                <button type="submit" name="enviar">Entrar</button>
            </fieldset>
        </form>
    </div>
</div> 

{include file='footer.tpl'}



