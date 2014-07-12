{include file='sections/admin/template/header.tpl'}

<div ui-view id="contenido"></div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <di id="spiner"></di>
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

{include file='sections/admin/template/footer.tpl'}
