$(window).scroll(function() {
    //	If on top fade the bouton out, else fade it in
    if ($(window).scrollTop() == 0)
        $('#returnOnTop').fadeOut();
    else
        $('#returnOnTop').fadeIn();
});
