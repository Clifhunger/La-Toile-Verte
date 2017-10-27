(function($){

    /* Quand je clique sur l'icône hamburger je rajoute une classe au body */
    $('#header__icon').click(function(e){
        e.preventDefault();
        $('nav').toggleClass('reduit');
        $('header').toggleClass('rotation');
    });

    /* Je veux pouvoir masquer le menu si on clique sur le cache */
    $('#site-cache').click(function(e){
        $('nav').removeClass('reduit');
        $('header').removeClass('rotation');
    })

})(jQuery);