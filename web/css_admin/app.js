(function($){

    /* Quand je clique sur l'icône hamburger je rajoute une classe au body */
    $('#header__icon').click(function(e){
        e.preventDefault();
        $('nav').toggleClass('reduit');
        $('header').toggleClass('rotation');
        $('div.content-wrapper').toggleClass('grand');
    });

    /* Je veux pouvoir masquer le menu si on clique sur le cache */
    $('#site-cache').click(function(e){
        $('nav').removeClass('reduit');
        $('header').removeClass('rotation');
        $('div.content-wrapper').removeClass('grand');
    })

    $("#liste_articles").hide();

    $("#modify").click(function hideAndShow(){
        if ($("#liste_articles").is(':visible')){
            $("#liste_articles").hide();
        } else {
            $("#liste_articles").show();
        }
    });

})(jQuery);