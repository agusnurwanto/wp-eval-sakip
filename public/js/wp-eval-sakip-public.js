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
