$("#js-rotating").Morphext({
    // The [in] animation type. Refer to Animate.css for a list of available animations.
    animation: "pulse ",
    // An array of phrases to rotate are created based on this separator. Change it if you wish to separate the phrases differently (e.g. So Simple | Very Doge | Much Wow | Such Cool).
    separator: "/",
    // The delay between the changing of each phrase in milliseconds.
    speed: 2000,
    complete: function () {
        // Called after the entrance animation is executed.
    }
});

$("#js-rotating-2").Morphext({
    // The [in] animation type. Refer to Animate.css for a list of available animations.
    animation: "pulse",
    // An array of phrases to rotate are created based on this separator. Change it if you wish to separate the phrases differently (e.g. So Simple | Very Doge | Much Wow | Such Cool).
    separator: "/",
    // The delay between the changing of each phrase in milliseconds.
    speed: 2000,
    complete: function () {
        // Called after the entrance animation is executed.
    }
});


$('input[type=text], input[type=password], input[type=email], input[type=url], input[type=tel], input[type=number], input[type=search], input[type=date], input[type=time], textarea').each(function (element, i) {
    if ((element.value !== undefined && element.value.length > 0) || $(this).attr('placeholder') !== null) {
        $(this).siblings('label').addClass('active');
    }
    else {
        $(this).siblings('label').removeClass('active');
    }
});







$('.alert').alert();


