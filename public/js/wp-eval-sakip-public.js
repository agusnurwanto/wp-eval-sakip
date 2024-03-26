jQuery(document).ready(function () {
  var loading =
    "" +
    '<div id="wrap-loading">' +
    '<div class="lds-hourglass"></div>' +
    '<div id="persen-loading"></div>' +
    "</div>";
  if (jQuery("#wrap-loading").length == 0) {
    jQuery("body").prepend(loading);
  }
});