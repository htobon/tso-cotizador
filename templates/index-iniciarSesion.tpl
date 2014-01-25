{include file='head.tpl' jsIncludes=["jquery"] pageTitle="TSO Cotizador"}

{include file='header.tpl'} 

<div class="row">
    <div class="small-9 small-centered columns"><a href=""><img src="{$smarty.const.SMARTY_IMG_URI}/logoSmall.png"></a></div>
</div>
<div class="row">
    <div class="large-12 columns"> 
        {if isset($error)}
            <p style="background-color:#f0c040; color:black;">{$error}</p>
        {/if}
        <form method="post" action="{$accion}">
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



