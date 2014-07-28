{include file='sections/admin/template/header.tpl'}

<div ui-view id="contenido"></div>
 

<div class="modal fade" id="loading" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="height: 100%;">
      <div id="spiner" class="loading"></div>
</div>

{include file='sections/admin/template/footer.tpl'}
