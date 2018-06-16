/** Abre bloco de execução e evita conflito com outros frameworks js **/
jQuery.noConflict();
(function ($){

    $(".submenu").hide();

    $(".menu").click(function() {
        if ( $(this).hasClass("menuSelecionado") ) {
            $(".menu").removeClass("menuSelecionado");
            $("#contentMenuInterno").empty();
        }
        else {
            $(".menu").removeClass("menuSelecionado");
            $("#contentMenuInterno").html( $( $(this).attr("href") ).html() );
            $(this).addClass("menuSelecionado");
        }

    });



/** Fecha bloco de execução **/
})(jQuery);

