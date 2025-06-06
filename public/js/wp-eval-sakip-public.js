jQuery(document).ready(function () {
    var loading = ""
        +'<div id="wrap-loading">'
            +'<div class="lds-hourglass"></div>'
            +'<div id="persen-loading"></div>'
            +'<div id="pesan-loading"></div>'
        +"</div>";
    if (jQuery("#wrap-loading").length == 0) {
        jQuery("body").prepend(loading);
    }

    jQuery("body").on("click", ".esakip-header-tahun", function () {
        var id = jQuery(this).attr("data-id");
        if (jQuery(this).hasClass("active")) {
            jQuery(this).removeClass("active");
            jQuery('.esakip-body-tahun[data-id="' + id + '"]').removeClass(
                "active"
            );
        } else {
            jQuery(this).addClass("active");
            jQuery('.esakip-body-tahun[data-id="' + id + '"]').addClass(
                "active"
            );
        }
    });
});

function setToFixed(v) {
    return (v % 1 ? v.toFixed(2) : v);
}

function pesan_loading(pesan, loading=false){
    if(loading){
        pesan = '<div style="padding: 20px;">LOADING...</div>'+pesan;
    }
    jQuery('#pesan-loading').html(pesan);
    console.log(pesan);
}

function Export2Word(element, filename = ''){
    var preHtml = "<html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:w='urn:schemas-microsoft-com:office:word' xmlns='http://www.w3.org/TR/REC-html40'><head><meta charset='utf-8'><title>Export HTML To Doc</title></head><body>";
    var postHtml = "</body></html>";
    var html = preHtml+element+postHtml;

    var blob = new Blob(['\ufeff', html], {
        type: 'application/msword'
    });
    
    // Specify link url
    var url = 'data:application/vnd.ms-word;charset=utf-8,' + encodeURIComponent(html);
    
    // Specify file name
    filename = filename?filename+'.doc':'document.doc';
    
    // Create download link element
    var downloadLink = document.createElement("a");

    document.body.appendChild(downloadLink);
    
    if(navigator.msSaveOrOpenBlob ){
        navigator.msSaveOrOpenBlob(blob, filename);
    }else{
        // Create a link to the file
        downloadLink.href = url;
        
        // Setting the file name
        downloadLink.download = filename;
        
        //triggering the function
        downloadLink.click();
    }
    
    document.body.removeChild(downloadLink);
}

function run_download_word_sakip(element, judul) {
	var download_word = '<a id="word_sakip" onclick="return false;" href="#" class="btn btn-primary" style="margin-left: 10px;" ><span class="dashicons dashicons-media-document"></span>Download Word</a>';
    jQuery('#action-sakip').append(download_word);
    jQuery("#word_sakip").on("click", function () {
        Export2Word(element, judul);
    });
}

function run_download_excel_sakip(type) {
    var body = '<a id="excel" onclick="return false;" href="#" class="btn btn-success"><span class="dashicons dashicons-media-spreadsheet"></span>Download Excel</a>';
    var download_excel = '<div id="action-sakip" class="hide-print text-white">' + body + "</div>";
    jQuery(".action-section").append(download_excel);

    var style = "";

	style = jQuery(".cetak").attr("style");
	if (typeof style == "undefined") {
		style = "";
	}
	jQuery(".cetak").attr(
		"style",
		style +
		" font-family:'Open Sans',-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif; padding:0; margin:0; font-size:13px;"
	);

	jQuery(".bawah").map(function (i, b) {
		style = jQuery(b).attr("style");
		if (typeof style == "undefined") {
			style = "";
		}
		jQuery(b).attr("style", style + " border-bottom:1px solid #000;");
	});

	jQuery(".kiri").map(function (i, b) {
		style = jQuery(b).attr("style");
		if (typeof style == "undefined") {
			style = "";
		}
		jQuery(b).attr("style", style + " border-left:1px solid #000;");
	});

	jQuery(".kanan").map(function (i, b) {
		style = jQuery(b).attr("style");
		if (typeof style == "undefined") {
			style = "";
		}
		jQuery(b).attr("style", style + " border-right:1px solid #000;");
	});

	jQuery(".atas").map(function (i, b) {
		style = jQuery(b).attr("style");
		if (typeof style == "undefined") {
			style = "";
		}
		jQuery(b).attr("style", style + " border-top:1px solid #000;");
	});
    
	jQuery(".text_tengah").map(function (i, b) {
		style = jQuery(b).attr("style");
		if (typeof style == "undefined") {
			style = "";
		}
		jQuery(b).attr("style", style + " text-align: center;");
	});

	jQuery(".text_kiri").map(function (i, b) {
		style = jQuery(b).attr("style");
		if (typeof style == "undefined") {
			style = "";
		}
		jQuery(b).attr("style", style + " text-align: left;");
	});

	jQuery(".text_kanan").map(function (i, b) {
		style = jQuery(b).attr("style");
		if (typeof style == "undefined") {
			style = "";
		}
		jQuery(b).attr("style", style + " text-align: right;");
	});

	jQuery(".text_block").map(function (i, b) {
		style = jQuery(b).attr("style");
		if (typeof style == "undefined") {
			style = "";
		}
		jQuery(b).attr("style", style + " font-weight: bold;");
	});

	jQuery(".text_15").map(function (i, b) {
		style = jQuery(b).attr("style");
		if (typeof style == "undefined") {
			style = "";
		}
		jQuery(b).attr("style", style + " font-size: 15px;");
	});

	jQuery(".text_20").map(function (i, b) {
		style = jQuery(b).attr("style");
		if (typeof style == "undefined") {
			style = "";
		}
		jQuery(b).attr("style", style + " font-size: 20px;");
	});

    var td = document.getElementsByTagName("td");
    for (var i = 0; i < td.length; i++) {
        var style = td[i].getAttribute("style") || "";
        td[i].setAttribute("style", style + "; mso-number-format:\\@;");
    }

    jQuery("#excel").on("click", function () {
        var name = "Laporan";
        var title = jQuery("#cetak").attr("title");
        if (title) {
            name = title;
        }

        jQuery("a").removeAttr("href");

        var cek_hide_excel = jQuery("#cetak .hide-excel");
        if (cek_hide_excel.length >= 1) {
            cek_hide_excel.remove();
            setTimeout(function () {
                alert(
                    "Ada beberapa fungsi yang tidak bekerja setelah melakukan download Excel. Mohon refresh halaman ini!"
                );
                location.reload();
            }, 5000);
        }

        tableHtmlToExcel("cetak", name);
    });
}

function tableHtmlToExcel(tableID, filename = "") {
    var downloadLink;
    var dataType = "application/vnd.ms-excel";
    var tableSelect = document.getElementById(tableID);
    var tableHTML = tableSelect.outerHTML
        .replace(/ /g, "%20")
        .replace(/#/g, "%23");

    filename = filename ? filename + ".xls" : "excel_data.xls";

    downloadLink = document.createElement("a");

    document.body.appendChild(downloadLink);

    if (navigator.msSaveOrOpenBlob) {
        var blob = new Blob(["\ufeff", tableHTML], {
            type: dataType,
        });
        navigator.msSaveOrOpenBlob(blob, filename);
    } else {
        downloadLink.href = "data:" + dataType + ", " + tableHTML;

        downloadLink.download = filename;

        downloadLink.click();
    }
}

function penjadwalanHitungMundur(dataHitungMundur = {}) {
    let jenis = dataHitungMundur["jenisJadwal"] || "Jenis Jadwal";
    let nama = dataHitungMundur["namaJadwal"] || "Penjadwalan";
    let mulaiJadwal = dataHitungMundur["mulaiJadwal"] || "2022-08-12 16:00:00";
    let selesaiJadwal =
        dataHitungMundur["selesaiJadwal"] || "2022-09-12 16:00:00";
    let thisTimeZone = dataHitungMundur["thisTimeZone"] || "Asia/Jakarta";

    if (!thisTimeZone.includes("Asia/")) {
        console.log("Pengaturan timezone salah");
        console.log(
            "Pilih salah satu kota di zona waktu yang sama dengan anda, antara lain: 'Jakarta','Makasar','Jayapura'"
        );
        return;
    }

    var jadwal =
        '<div id="penjadwalanHitungMundur">' +
        '<label id="titles"><span class="dashicons dashicons-clock"></span>&nbsp;' +
        jenis +
        "</span> | " +
        nama +
        "</label>" +
        '<div id="days" style="margin-left:10px">0 <span>Hari</span></div>' +
        '<div id="hours">00 <span>Jam</span></div>' +
        '<div id="minutes">00 <span>Menit</span></div>' +
        '<div id="seconds">00 <span>Detik</span></div>' +
        "</div>";

    jQuery("body").prepend(jadwal);

    function makeTimer() {
        let endTime = new Date(selesaiJadwal);
        endTime = Date.parse(endTime) / 1000;

        let now = new Date();
        now =
            Date.parse(
                new Date(
                    now.toLocaleString("en-US", { timeZone: thisTimeZone })
                )
            ) / 1000;

        let timeLeft = endTime - now;

        let days = Math.floor(timeLeft / 86400);
        let hours = Math.floor((timeLeft % 86400) / 3600);
        let minutes = Math.floor((timeLeft % 3600) / 60);
        let seconds = Math.floor(timeLeft % 60);

        if (hours < 10) hours = "0" + hours;
        if (minutes < 10) minutes = "0" + minutes;
        if (seconds < 10) seconds = "0" + seconds;

        jQuery("#days").html(days + "<span>Hari</span>");
        jQuery("#hours").html(hours + "<span>Jam</span>");
        jQuery("#minutes").html(minutes + "<span>Menit</span>");
        jQuery("#seconds").html(seconds + "<span>Detik</span>");

        if (timeLeft < 0) {
            clearInterval(wpsakipTimer);
            jQuery("#days").html("0 <span>Hari</span>");
            jQuery("#hours").html("00 <span>Jam</span>");
            jQuery("#minutes").html("00 <span>Menit</span>");
            jQuery("#seconds").html("00 <span>Detik</span>");
        }
    }

    let mulaiJadwalTime = new Date(mulaiJadwal).getTime();
    let now = new Date().toLocaleString("en-US", { timeZone: thisTimeZone });
    now = new Date(now).getTime();

    if (now > mulaiJadwalTime) {
        var wpsakipTimer = setInterval(makeTimer, 1000);
    } else {
        jQuery("#days").html("0 <span>Hari</span>");
        jQuery("#hours").html("00 <span>Jam</span>");
        jQuery("#minutes").html("00 <span>Menit</span>");
        jQuery("#seconds").html("00 <span>Detik</span>");
    }
}

function changeUrl(option) {
    var key = option.key;
    var value = option.value;
    var _url = option.url;
    var url_object = new URL(_url);
    var value_asli = url_object.searchParams.get(key);
    var _and = "&";
    if (_url.indexOf("?") == -1) {
        _url += "?";
        _and = "";
    }

    if (_url.indexOf(key) != -1) {
        _url = _url.replace(
            _and + key + "=" + value_asli,
            _and + key + "=" + value
        );
    } else {
        _url += _and + key + "=" + value;
    }
    return _url;
}

function formatRupiah(angka, prefix) {
    var cek_minus = false;
    if (!angka || angka == "" || angka == 0) {
        angka = "0";
    } else if (angka < 0) {
        angka = angka * -1;
        cek_minus = true;
    }
    try {
        if (typeof angka == "number") {
            angka = Math.round(angka * 100) / 100;
            angka += "";
            angka = angka.replace(/\./g, ",").toString();
        }
        angka += "";
        number_string = angka;
    } catch (e) {
        console.log("angka", e, angka);
        var number_string = "0";
    }
    var split = number_string.split(","),
        sisa = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    // tambahkan titik jika yang di input sudah menjadi angka ribuan
    if (ribuan) {
        separator = sisa ? "." : "";
        rupiah += separator + ribuan.join(".");
    }

    rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
    if (cek_minus) {
        return (
            "-" + (prefix == undefined ? rupiah : rupiah ? "Rp. " + rupiah : "")
        );
    } else {
        return prefix == undefined ? rupiah : rupiah ? "Rp. " + rupiah : "";
    }
}

function formatTanggalIndonesia(dateString) {
    // Konversi string ke objek Date
    let date = new Date(dateString);

    // Pastikan tanggal valid
    if (isNaN(date)) {
        return "Tanggal tidak valid";
    }

    // Array nama bulan dalam bahasa Indonesia
    const bulanIndonesia = [
        "Januari", "Februari", "Maret", "April", "Mei", "Juni",
        "Juli", "Agustus", "September", "Oktober", "November", "Desember"
    ];

    // Ambil hari, bulan, dan tahun
    let tanggal = date.getDate();
    let bulan = bulanIndonesia[date.getMonth()];
    let tahun = date.getFullYear();

    // Format akhir: DD MMMM YYYY
    return `${tanggal} ${bulan} ${tahun}`;
}

function getFormData($form) {
    let unindexed_array = $form.serializeArray();
    let indexed_array = {};

    jQuery.map(unindexed_array, function (n, i) {
		var nama_baru = n['name'].split('[');
            if(nama_baru.length > 1){
                nama_baru = nama_baru[0];
                if(!indexed_array[nama_baru]){
                    indexed_array[nama_baru] = [];
                }
                indexed_array[nama_baru].push(n['value']);
            }else{
				indexed_array[n['name']] = n['value'];
            }
    });

    return indexed_array;
}

function style_css_download_excel_sakip(type) {
    var style = "";

	style = jQuery(".cetak").attr("style");
	if (typeof style == "undefined") {
		style = "";
	}
	jQuery(".cetak").attr(
		"style",
		style +
		" font-family:'Open Sans',-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif; padding:0; margin:0; font-size:13px;"
	);

	jQuery(".bawah").map(function (i, b) {
		style = jQuery(b).attr("style");
		if (typeof style == "undefined") {
			style = "";
		}
		jQuery(b).attr("style", style + " border-bottom:1px solid #000;");
	});

	jQuery(".kiri").map(function (i, b) {
		style = jQuery(b).attr("style");
		if (typeof style == "undefined") {
			style = "";
		}
		jQuery(b).attr("style", style + " border-left:1px solid #000;");
	});

	jQuery(".kanan").map(function (i, b) {
		style = jQuery(b).attr("style");
		if (typeof style == "undefined") {
			style = "";
		}
		jQuery(b).attr("style", style + " border-right:1px solid #000;");
	});

	jQuery(".atas").map(function (i, b) {
		style = jQuery(b).attr("style");
		if (typeof style == "undefined") {
			style = "";
		}
		jQuery(b).attr("style", style + " border-top:1px solid #000;");
	});
    
	jQuery(".text_tengah").map(function (i, b) {
		style = jQuery(b).attr("style");
		if (typeof style == "undefined") {
			style = "";
		}
		jQuery(b).attr("style", style + " text-align: center;");
	});

	jQuery(".text_kiri").map(function (i, b) {
		style = jQuery(b).attr("style");
		if (typeof style == "undefined") {
			style = "";
		}
		jQuery(b).attr("style", style + " text-align: left;");
	});

	jQuery(".text_kanan").map(function (i, b) {
		style = jQuery(b).attr("style");
		if (typeof style == "undefined") {
			style = "";
		}
		jQuery(b).attr("style", style + " text-align: right;");
	});

	jQuery(".text_block").map(function (i, b) {
		style = jQuery(b).attr("style");
		if (typeof style == "undefined") {
			style = "";
		}
		jQuery(b).attr("style", style + " font-weight: bold;");
	});

	jQuery(".text_15").map(function (i, b) {
		style = jQuery(b).attr("style");
		if (typeof style == "undefined") {
			style = "";
		}
		jQuery(b).attr("style", style + " font-size: 15px;");
	});

	jQuery(".text_20").map(function (i, b) {
		style = jQuery(b).attr("style");
		if (typeof style == "undefined") {
			style = "";
		}
		jQuery(b).attr("style", style + " font-size: 20px;");
	});

    var td = document.getElementsByTagName("td");
    for (var i = 0; i < td.length; i++) {
        var style = td[i].getAttribute("style") || "";
        td[i].setAttribute("style", style + "; mso-number-format:\\@;");
    }
}

function validateForm(fields) {
	const formData = {};

	for (const [name, message] of Object.entries(fields)) {
		const $field = jQuery(`[name="${name}"]`);

		if ($field.is(':radio')) {
			const checkedValue = jQuery(`[name="${name}"]:checked`).val();
			if (!checkedValue) {
				return { error: message };
			}
			formData[name] = checkedValue;
		} else if ($field.is(':checkbox')) {
			const isChecked = $field.is(':checked');
			if (!isChecked) {
				return { error: message };
			}
			formData[name] = isChecked;
		} else if ($field.is('select') || $field.is('textarea') || $field.is(':input')) {
			const value = $field.val().trim();
			if (value === '') {
				return { error: message };
			}
			formData[name] = value;
		}
	}

	return { error: null, data: formData };
}

function get_link_login(that){
    var id = jQuery(that).attr('id');
    var url = jQuery(that).attr('title');
    if(id == ''){
        return alert('Nama atau ID unik auto login tidak boleh kosong!');
    }
    jQuery("#wrap-loading").show();
    jQuery.ajax({
        url: esakip.url,
        type: "post",
        data: {
            "action": "coba_auto_login",
            "api_key": esakip.api_key,
            "id": id,
            "url": url
        },
        dataType: "json",
        success: function (data) {
            jQuery("#wrap-loading").hide();
            if(data.status == 'success'){
                window.open(data.url_login, '_blank');
            }else{
                alert(data.message);
            }
        },
        error: function (e) {
            console.log(e);
        }
    });
}