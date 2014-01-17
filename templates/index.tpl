{include file='head.tpl' jsIncludes=["jquery"] pageTitle="TSO Cotizador"}

{include file='header.tpl'} 
    <div class="row">
      <div class="large-12 columns"> 
        <form>
            {foreach from=$infos item=info}
                <p>{$info["name"]}</p>
                
            {/foreach}
		  <fieldset>
		    <legend>Iniciar sesion</legend> 
		    <label>Usuario:</label> <input type="text" placeholder="Nombre de usuario"><br>
		    <label>Contraseña:</label> <input type="password" placeholder="Contraseña"><br>
		    <a href="#" class="button [radius round]">Entrar</a>
		  </fieldset>
		</form>
      </div>
    </div> 
{include file='footer.tpl'}



