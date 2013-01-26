$(document).ready(function() {

    // Make rel="external" links open in a new window.
    $('a[rel="external"]').attr('target', '_blank');

    // Ligtboxes.
    $('.imageBox a').fancybox({
        "openEffect": 'elastic',
        "closeEffect": 'elastic',
        "helpers": {
            "title": {
                "type": 'inside'
            }
        }
    });
});
