{include file='head.tpl' jsIncludes=["jquery-mobile"] pageTitle="TSO Cotizador"}
<script>
    $(document).on('pageinit', function()
    {
        $("#test-link").on("tap", function() {
            $("#test-aqui").popup("open", {
                overlayTheme: "a",
                theme: "a",
                transition: "slideup"
            });
        });
    }
    );
</script>
<div class="row">
    <a id="test-link">TEST</a>

    <div data-role="popup" id="test-aqui" >
        <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
        <h1>TEST!</h1>
    </div>
</div>



