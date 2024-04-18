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
  
  jQuery('body').on('click', '.esakip-header-tahun', function(){
    var id = jQuery(this).attr('data-id');
    if(jQuery(this).hasClass('active')){
      jQuery(this).removeClass('active');
      jQuery('.esakip-body-tahun[data-id="'+id+'"]').removeClass('active');
    }else{
      jQuery(this).addClass('active');
      jQuery('.esakip-body-tahun[data-id="'+id+'"]').addClass('active');
    }
  });
});