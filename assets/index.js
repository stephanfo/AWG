require('./bootstrap/css/bootstrap.min.css');
require('./lightbox/css/lightbox.css');
require('./thumbnail-gallery/thumbnail-gallery.css');
require('./photos.lifeinpix/css/style.css');

// require jQuery normally
const $ = require('jquery');

// create global $ and jQuery variables
global.$ = global.jQuery = $;

require('./bootstrap/js/bootstrap.min.js');
require('./lightbox/js/lightbox.js');

$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
});
