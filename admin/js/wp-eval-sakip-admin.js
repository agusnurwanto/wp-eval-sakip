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
    if (jQuery("#esakip_load_ajax_carbon").length >= 1) {
        jQuery("#wrap-loading").show();
        jQuery.ajax({
            url: ajaxurl,
            type: "post",
            data: {
                action: "esakip_load_ajax_carbon",
                api_key: esakip.api_key,
                type: jQuery("#esakip_load_ajax_carbon").attr("data-type"),
            },
            dataType: "json",
            success: function (data) {
                jQuery("#wrap-loading").hide();
                if (data.status == "success") {
                    jQuery("#esakip_load_ajax_carbon").html(data.message);
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
    jQuery("#generate_user_esakip").on("click", function () {
        if (confirm("Apakah anda yakin akan menggenerate user SIPD!")) {
            jQuery("#wrap-loading").show();
            jQuery.ajax({
                url: ajaxurl,
                type: "POST",
                data: {
                    action: "generate_user_esakip",
                    api_key: esakip.api_key,
                    pass: prompt(
                        "Masukan password default untuk User yang akan dibuat"
                    ),
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

    jQuery("body").on("click", ".esakip-header-tahun", function () {
        var tahun = jQuery(this).attr("tahun");
        if (jQuery(this).hasClass("active")) {
            jQuery(this).removeClass("active");
            jQuery('.esakip-body-tahun[tahun="' + tahun + '"]').removeClass(
                "active"
            );
        } else {
            jQuery(this).addClass("active");
            jQuery('.esakip-body-tahun[tahun="' + tahun + '"]').addClass(
                "active"
            );
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

function get_data_unit_wpsipd() {
    jQuery("#wrap-loading").show();
    jQuery.ajax({
        url: ajaxurl,
        type: "post",
        dataType: "json",
        data: {
            action: "get_data_unit_wpsipd",
            server: jQuery(
                'input[name="carbon_fields_compact_input[_crb_url_server_sakip]"]'
            ).val(),
            api_key: jQuery(
                'input[name="carbon_fields_compact_input[_crb_apikey_wpsipd]"]'
            ).val(),
            tahun_anggaran: jQuery(
                'input[name="carbon_fields_compact_input[_crb_tahun_wpsipd]"]'
            ).val(),
        },
        success: function (data) {
            jQuery("#wrap-loading").hide();
            console.log(data.message);
            if (data.status == "success") {
                alert("Data berhasil disinkron");
            } else {
                alert(data.message);
            }
        },
        error: function (e) {
            console.log(e);
            return alert(e);
        },
    });
}
