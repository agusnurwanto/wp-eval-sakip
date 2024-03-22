jQuery(document).ready(function () {
  if (jQuery("#load_ajax_carbon").length >= 1) {
    jQuery("#wrap-loading").show();
    jQuery.ajax({
      url: ajaxurl,
      type: "post",
      data: {
        action: "load_ajax_carbon",
        api_key: esakip.api_key,
        type: jQuery("#load_ajax_carbon").attr("data-type"),
      },
      dataType: "json",
      success: function (data) {
        jQuery("#wrap-loading").hide();
        if (data.status == "success") {
          jQuery("#load_ajax_carbon").html(data.message);
        } else {
          return alert(data.message);
        }
      },
      error: function (e) {
        console.log(e);
        return alert(data.message);
      },
    });
  }
  jQuery("#generate_user").on("click", function () {
    if (confirm("Apakah anda yakin akan menggenerate user SIPD!")) {
      jQuery("#wrap-loading").show();
      jQuery.ajax({
        url: ajaxurl,
        type: "POST",
        data: {
          action: "generate_user_sipd_merah",
          api_key: esakip.api_key,
          pass: prompt("Masukan password default untuk User yang akan dibuat"),
          update_pass: confirm(
            "Apakah anda mau mereset password user existing juga?"
          ),
        },
        dataType: "json",
        success: function (data) {
          jQuery("#wrap-loading").hide();
          return alert(data.message);
        },
        error: function (e) {
          console.log(e);
          return alert(data.message);
        },
      });
    }
  });
  jQuery(".accordion").each(function () {
    var $accordion = jQuery(this);
    var $header = $accordion.find(".header-tahun");
    var $body = $accordion.find(".body-tahun");

    // Ketika header di-klik
    $header.on("click", function () {
      // Toggle kelas 'active' pada header dan body
      $header.toggleClass("active");
      $body.toggleClass("active");

      // Tutup semua body accordion kecuali yang saat ini di-klik
      jQuery(".accordion")
        .not($accordion)
        .find(".header-tahun")
        .removeClass("active");
      jQuery(".accordion")
        .not($accordion)
        .find(".body-tahun")
        .removeClass("active");
    });
  });
});

function sql_migrate_esakip() {
  jQuery("#wrap-loading").show();
  jQuery.ajax({
    url: ajaxurl,
    type: "POST",
    data: {
      action: "sql_migrate_esakip",
    },
    dataType: "json",
    success: function (data) {
      jQuery("#wrap-loading").hide();
      return alert(data.message);
    },
    error: function (e) {
      console.log(e);
      return alert(data.message);
    },
  });
}
