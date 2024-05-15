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

function run_download_excel_sakip(type) {
    var body =
        '<a id="excel" onclick="return false;" href="#" class="btn btn-success"><span class="dashicons dashicons-media-spreadsheet"></span>Download Excel</a>';
    var download_excel =
        '<div id="action-sipd" class="hide-print">' + body + "</div>";
    jQuery(".action-section").append(download_excel);

    jQuery(".cetak").css({
        "font-family":
            "'Open Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif",
        padding: "0",
        margin: "0",
        "font-size": "13px",
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
    nama = (dataHitungMundur['namaJadwal'] == '') ? 'Penjadwalan' : dataHitungMundur['namaJadwal'];
    mulaiJadwal = (dataHitungMundur['mulaiJadwal'] == '') ? '2022-08-12 16:00:00' : dataHitungMundur['mulaiJadwal'];
    selesaiJadwal = (dataHitungMundur['selesaiJadwal'] == '') ? '2022-09-12 16:00:00' : dataHitungMundur['selesaiJadwal'];
    thisTimeZone = (dataHitungMundur['thisTimeZone'] == '') ? 'Asia/Jakarta' : dataHitungMundur['thisTimeZone'];

    cekTimeZone = thisTimeZone.includes('Asia/');

    if(cekTimeZone == false){
        console.log('Pengaturan timezone salah')
        console.log('Pilih salah satu kota di zona waktu yang sama dengan anda, antara lain:  \'Jakarta\',\'Makasar\',\'Jayapura\'')
    }

    var jadwal = '<div id="penjadwalanHitungMundur">'
        +'<label id="titles"><span class="dashicons dashicons-clock"></span>&nbsp;'+nama+'</label>'
        +'<div id="days" style="margin-left:10px">0 <span>Hari</span></div>'
        +'<div id="hours">00 <span>Jam</span></div>'
        +'<div id="minutes">00 <span>Menit</span></div>'
        +'<div id="seconds">00 <span>Detik</span></div>'
        +'</div>';

    jQuery('body').prepend(jadwal);

    function makeTimer() {
        var endTime = new Date(selesaiJadwal);          
            endTime = (Date.parse(endTime) / 1000);

            var now = new Date();

            now = new Date(now.toLocaleString('en-US', {timeZone : thisTimeZone,}),);

            now = (Date.parse(now) / 1000);

            var timeLeft = endTime - now;

            var days = Math.floor(timeLeft / 86400); 
            var hours = Math.floor((timeLeft - (days * 86400)) / 3600);
            var minutes = Math.floor((timeLeft - (days * 86400) - (hours * 3600 )) / 60);
            var seconds = Math.floor((timeLeft - (days * 86400) - (hours * 3600) - (minutes * 60)));
    
            if (hours < "10") { hours = "0" + hours; }
            if (minutes < "10") { minutes = "0" + minutes; }
            if (seconds < "10") { seconds = "0" + seconds; }

            jQuery("#days").html(days + "<span>Hari</span>");
            jQuery("#hours").html(hours + "<span>Jam</span>");
            jQuery("#minutes").html(minutes + "<span>Menit</span>");
            jQuery("#seconds").html(seconds + "<span>Detik</span>");
            
            if (timeLeft < 0) {
                clearInterval(wpsipdTimer);
                jQuery("#days").html("0 <span>Hari</span>");
                jQuery("#hours").html("00 <span>Jam</span>");
                jQuery("#minutes").html("00 <span>Menit</span>");
                jQuery("#seconds").html("00 <span>Detik</span>");
            }

    }

    var mulaiJadwal = new Date(mulaiJadwal);
    mulaiJadwal = Date.parse(mulaiJadwal);

    var now = new Date();
    now = new Date(now.toLocaleString('en-US', {timeZone : thisTimeZone,}),);
    now = Date.parse(now);
    if(now > mulaiJadwal ){
        var wpsipdTimer = setInterval(function() { makeTimer(); }, 1000);
    }else{
        jQuery("#days").html("0 <span>Hari</span>");
        jQuery("#hours").html("00 <span>Jam</span>");
        jQuery("#minutes").html("00 <span>Menit</span>");
        jQuery("#seconds").html("00 <span>Detik</span>");
    }
}