$(document).on('pageinit', function()
{
    $(".point").on("tap", function(event) {
        /*
         * Aquí se valida que el accesorio ya se encuentre seleccionado.
         * De ser así simplemente se deselecciona.
         * Si por el contrario no estaba seleccionado, entonces se valida
         * que el GPS que se seleccionó le sirva a este accesorio.
         * De no ser así enotnces se le debe indicar al usuario.
         * Si por el contrario, ningún GPS se ha seleccionado, entonces el
         * accesorio podrá ser seleccionado.
         */
        //alert($(event.target).attr("id"));
    });
}
);


