$(document).ready(function() {

    var $element = $('#bubble');
    var newText = 'In this example, bubbleText takes the inner html of $element (initially empty), and animates it into this new text. The effect is the phrase being displayed letter by letter, in place.';

    bubbleText({
        element: $element,
        newText: newText,
        speed: 3000,
        repeat: Infinity,
    });

});
