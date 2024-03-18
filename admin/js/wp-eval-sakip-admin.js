jQuery(document).ready(function () {
  jQuery("#generate_user_sipd_merah").on("click", function () {
    if (confirm("Apakah anda yakin akan menggenerate user SIPD!")) {
      jQuery("#wrap-loading").show();
      jQuery.ajax({
        url: ajaxurl,
        type: "post",
        data: {
          action: "generate_user_sipd_merah",
          api_key: wpsipd.api_key,
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
