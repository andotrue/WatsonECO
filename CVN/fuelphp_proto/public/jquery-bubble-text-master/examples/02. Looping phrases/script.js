$(document).ready(function() {

    var phrases = [
        'This is how bubbleText works.',
        'Animating each letter in a friendly way',
        'Thanks for seeing it :)',
        'It really matters to me ...',
        'Regards,',
        'Guedes, Washington L.',
    ];
    var len = phrases.length;
    var index = 0;

    var ctrl = bubbleText({
        element: $('#bubble'),
        newText: phrases[index++],
        letterSpeed: 70,
        repeat: Infinity,
        timeBetweenRepeat: 1000,
        callback: function() {
            this.newText = phrases[index++ % len];
        },
    });

});
