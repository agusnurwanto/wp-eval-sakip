jQuery(document).ready(function () {
    var loading =
        "" +
        '<div id="wrap-loading">' +
            '<div class="lds-hourglass"></div>' +
            '<div id="persen-loading"></div>' +
            '<div id="pesan-loading"></div>' +
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
    
    jQuery("#generate_user_esakip_pegawai_simpeg").on("click", function () {
        if (!confirm("Apakah anda yakin akan menggenerate user SIMPEG!")) {
             return;
        }
        window.total_pegawai_simpeg = 0;
        get_data_total_pegawai_simpeg()
        .then(function(){
            if(total_pegawai_simpeg == 0){
                return alert('OPD Belum Termapping!, \n Harap Mapping Perangkat Daerah SIPD-SIMPEG Di Menu Pengaturan Perangkat Daerah!');
            }

            const pass = prompt(
                "Masukan password default untuk User yang akan dibuat"
            )

            const update_pass = confirm(
                "Apakah anda mau mereset password user existing juga?"
            )

            const interval = 100;
            const hasil_interval = Array.from({ 
                    length: total_pegawai_simpeg / interval + 1 }
                    , (v, i) => i * interval);
            const total_persen = total_pegawai_simpeg + interval;
            let total_cek = total_pegawai_simpeg;

            jQuery('#wrap-loading').show();
		    let last = hasil_interval.length-1;

            jQuery('#persen-loading').attr('persen', 0);
			jQuery('#persen-loading').html('0%');

            hasil_interval.reduce(function(sequence, nextData){
                return sequence.then(function(current_data){
                    return new Promise(function(resolve_reduce, reject_reduce){
                        ajax_generate_user_simpeg({
                            pass: pass,
                            update_pass: update_pass,
                            mulai: current_data,
                            limit: interval
                        })
                        .then(function(){
                            return resolve_reduce(nextData);
                        });
                        var c_persen = +jQuery('#persen-loading').attr('persen');
                        if((total_cek - interval) >= interval){
                            c_persen = c_persen + interval;
                            total_cek = total_cek - interval;
                        }else{
                            c_persen = c_persen + total_cek;
                            total_cek = total_cek - total_cek;
                        }
                        jQuery('#persen-loading').attr('persen', c_persen);
                        jQuery('#persen-loading').html(((c_persen/total_pegawai_simpeg)*100).toFixed(2)+'%');
                    })
                    .catch(function(e){
                        console.log(e);
                        return Promise.resolve(nextData);
                    });
                })
                .catch(function(e){
                    console.log(e);
                    return Promise.resolve(nextData);
                });
            }, Promise.resolve(hasil_interval[last]))
            .then(function(data_last){
                alert('Berhasil Generate Akun User Simpeg.');
                jQuery('#wrap-loading').hide();
                jQuery('#persen-loading').html('0%');
            })
            .catch(function(err){
                console.log('err', err);
                alert('Ada kesalahan sistem!');
                jQuery('#wrap-loading').hide();
                jQuery('#persen-loading').html('0%');
            });
        });
    });

    function ajax_generate_user_simpeg(options){
		return new Promise(function(resolve, reject){
            jQuery.ajax({
                url: ajaxurl,
                type: "POST",
                data: {
                    action: "generate_user_esakip_pegawai_simpeg",
                    api_key: esakip.api_key,
                    pass: options.pass,
                    update_pass: options.update_pass,
                    mulai: options.mulai,
                    limit: options.limit,
                },
                dataType: "json",
                success: function (data) {
                    resolve();
                },
                error: function (e) {
                    console.log('error', e);
		            resolve();
                },
            });
		});
	}

    function get_data_total_pegawai_simpeg() {
        return new Promise(function(resolve, reject){
            jQuery('#wrap-loading').show();
            jQuery.ajax({
                url: ajaxurl,
                type: "POST",
                data: {
                    action: "get_data_total_pegawai_simpeg",
                    api_key: esakip.api_key
                },
                dataType: "json",
                success: function (data) {
                    jQuery('#wrap-loading').show();
                    console.log(data.message);
                    total_pegawai_simpeg = data.total_pegawai_simpeg;
                    resolve();
                },
                error: function (e) {
                    jQuery('#wrap-loading').show();
                    console.log(e);
                    console.log(data.message);
                    resolve();
                },
            });
        });
    };

    function pesan_loading(pesan, loading=false){
        if(loading){
            pesan = '<div style="padding: 20px;">LOADING...</div>'+pesan;
        }
        jQuery('#pesan-loading').html(pesan);
        console.log(pesan);
    }

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
