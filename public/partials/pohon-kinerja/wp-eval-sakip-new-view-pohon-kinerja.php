<?php
// If this file is called directly, abort.
if (! defined('WPINC')) {
    die;
}

if (empty($_GET) || empty($_GET['id'])) {
    die("<h1 class='text-center'>Parameter Diperlukan!</h1>");
} else {
    $id_pohon_kinerja = $_GET['id'];
}

if (!empty($_GET['id_koneksi_pokin'])) {
    $id_koneksi_pokin = $_GET['id_koneksi_pokin'];
} else {
    $id_koneksi_pokin = null;
}
?>
<style>
    /*
        * On large screens (lg and up), make the sidebar sticky and scrollable.
        * 'vh-100' class could work, but 'height: 100vh' is more reliable for sticky.
        * On mobile, this class is not applied, so the content just stacks and collapses naturally.
    */
    @media (min-width: 992px) {
        #pk-sidebar-content {
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
        }
    }

    .google-visualization-orgchart-node {
        border-radius: 5px;
        border: 0;
        padding: 0;
        vertical-align: top;
    }

    #chart_div_pokin .google-visualization-orgchart-connrow-medium {
        height: 20px;
    }

    #chart_div_pokin .google-visualization-orgchart-linebottom {
        border-bottom: 4px solid #f84d4d;
    }

    #chart_div_pokin .google-visualization-orgchart-lineleft {
        border-left: 4px solid #f84d4d;
    }

    #chart_div_pokin .google-visualization-orgchart-lineright {
        border-right: 4px solid #f84d4d;
    }

    #chart_div_pokin .google-visualization-orgchart-linetop {
        border-top: 4px solid #f84d4d;
    }

    .base-label-class {
        color: #0d0909;
        font-size: 13px;
        font-weight: 600;
        padding: 10px;
        min-height: 80px;
        min-width: 200px;
    }

    .label1 {
        background: #efd655;
        border-radius: 5px 5px 0 0;
    }

    .indikator1 {
        color: #0d0909;
        font-size: 11px;
        font-weight: 600;
        font-style: italic;
        padding: 10px;
        min-height: 70px;
    }

    .label2 {
        background: #fe7373;
        border-radius: 5px 5px 0 0;
    }

    .indikator2 {
        color: #0d0909;
        font-size: 11px;
        font-weight: 600;
        font-style: italic;
        padding: 10px;
        min-height: 70px;
    }

    .label3 {
        background: #57b2ec;
        border-radius: 5px 5px 0 0;
    }

    .indikator3 {
        color: #0d0909;
        font-size: 11px;
        font-weight: 600;
        font-style: italic;
        padding: 10px;
        min-height: 70px;
    }

    .label4 {
        background: #c979e3;
        border-radius: 5px 5px 0 0;
    }

    .indikator4 {
        color: #0d0909;
        font-size: 11px;
        font-weight: 600;
        font-style: italic;
        padding: 10px;
        min-height: 70px;
    }

    .label5 {
        background: #28a745;
        border-radius: 5px 5px 0 0;
    }

    .indikator5 {
        color: #0d0909;
        font-size: 11px;
        font-weight: 600;
        font-style: italic;
        padding: 10px;
        min-height: 70px;
    }


    @page {
        size: landscape;
        margin: 1cm;
    }

    @media print {
        #cetak {
            max-width: auto !important;
            height: auto !important;
        }

        #pokin-chart-wrapper {
            max-width: auto !important;
            height: auto !important;
        }

        #pk-sidebar-col,
        .site-header,
        .site-footer {
            display: none;
        }
    }
</style>
<div id="pk-wrapper" class="container-fluid">
    <div class="row">

        <aside class="col-lg-3 bg-light border-right" id="pk-sidebar-col">

            <button class="btn btn-secondary d-lg-none w-100 my-3" type="button" data-toggle="collapse" data-target="#pk-sidebar-content" aria-expanded="false" aria-controls="pk-sidebar-content">
                <i class="dashicons dashicons-admin-generic"></i> Tampilkan Kontrol & Petunjuk
            </button>

            <div class="collapse d-lg-block pt-3" id="pk-sidebar-content">

                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-white">
                        <h6 class="mb-0">
                            <i class="dashicons dashicons-admin-settings mr-1"></i>
                            Kontrol Tampilan
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-0">
                            <button id="print-chart-button" class="btn btn-info w-100">
                                <i class="dashicons dashicons-printer"></i> Cetak Chart
                            </button>
                        </div>

                        <hr>

                        <div class="form-group">
                            <label for="searchPohon" class="font-weight-bold small">Cari Pohon Kinerja</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="searchPohon" placeholder="Ketik untuk mencari...">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button"><i class="dashicons dashicons-search"></i></button>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group">
                            <label class="font-weight-bold small">Tampilkan Pohon Sampai Level:</label>
                            <div id="level-radio-container" class="d-flex justify-content-between flex-wrap">
                                <span class="text-muted small">Memuat level...</span>
                            </div>
                        </div>

                        <!-- <div class="form-group">
                            <label class="font-weight-bold small">Opsi Tambahan:</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="checkCrosscutting">
                                <label class="form-check-label small" for="checkCrosscutting">
                                    Tampilkan Crosscutting
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="checkKoneksiPemda">
                                <label class="form-check-label small" for="checkKoneksiPemda">
                                    Tampilkan Koneksi <span class="namaPemda">Pemerintah Daerah</span>
                                </label>
                            </div>
                        </div> -->

                        <div class="form-group mb-0"> <label class="font-weight-bold small">Perbesar/Perkecil:</label>
                            <div class="zoom-slider-container d-flex align-items-center">
                                <span class="small">(-)</span>
                                <input type="range" class="custom-range mx-2" id="zoomSlider" min="10" max="150" value="100">
                                <span class="small">(+)</span>
                            </div>
                            <div class="text-center mt-1">
                                <span class="zoom-value small" id="zoomValue">100%</span>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-white" id="headingPetunjuk">
                        <h6 class="mb-0">
                            <a href="#" style="text-decoration: none;" class="text-dark d-block" data-toggle="collapse" data-target="#collapsePetunjuk" aria-expanded="true" aria-controls="collapsePetunjuk">
                                <i class="dashicons dashicons-info mr-1"></i>
                                Petunjuk & Legenda
                                <i class="dashicons dashicons-arrow-down-alt2 float-right"></i>
                            </a>
                        </h6>
                    </div>

                    <div id="collapsePetunjuk" class="collapse show" aria-labelledby="headingPetunjuk">

                        <div class="card-body pb-0">
                            <h6 class="small font-weight-bold">Petunjuk Penggunaan:</h6>
                            <ul class="list-unstyled small pl-2">
                                <li class="mb-1"><i class="dashicons dashicons-yes-alt text-success mr-1"></i> Klik 2x pada judul card untuk expand/collapse.</li>
                                <li class="mb-1"><i class="dashicons dashicons-yes-alt text-success mr-1"></i> Klik indikator untuk melihat detail.</li>
                                <li class="mb-1"><i class="dashicons dashicons-yes-alt text-success mr-1"></i> Pohon kinerja tampil jika data terisi min. level 2.</li>
                            </ul>
                        </div>

                        <hr class="my-2">

                        <div class="card-body pt-0">
                            <h6 class="small font-weight-bold">Legenda Warna:</h6>
                            <ul class="list-group list-group-flush small">
                                <li class="list-group-item d-flex align-items-center p-1">
                                    <span class="badge" style="background: #efd655; width: 24px;">&nbsp;</span>
                                    <span class="ml-2">Level 1</span>
                                </li>
                                <li class="list-group-item d-flex align-items-center p-1">
                                    <span class="badge" style="background: #fe7373; width: 24px;">&nbsp;</span>
                                    <span class="ml-2">Level 2</span>
                                </li>
                                <li class="list-group-item d-flex align-items-center p-1">
                                    <span class="badge" style="background: #57b2ec; width: 24px;">&nbsp;</span>
                                    <span class="ml-2">Level 3</span>
                                </li>
                                <li class="list-group-item d-flex align-items-center p-1">
                                    <span class="badge" style="background: #c979e3; width: 24px;">&nbsp;</span>
                                    <span class="ml-2">Level 4</span>
                                </li>
                                <li class="list-group-item d-flex align-items-center p-1">
                                    <span class="badge" style="background: #28a745; width: 24px;">&nbsp;</span>
                                    <span class="ml-2">Level 5</span>
                                </li>
                                <li class="list-group-item d-flex align-items-center p-1">
                                    <span class="badge" style="background: #b5d9ea; width: 24px;">&nbsp;</span>
                                    <span class="ml-2">Baris Indikator</span>
                                </li>
                                <li class="list-group-item d-flex align-items-center p-1">
                                    <span class="badge" style="background: #FDFFB6; width: 24px;">&nbsp;</span>
                                    <span class="ml-2">Koneksi Pemda</span>
                                </li>
                                <li class="list-group-item d-flex align-items-center p-1">
                                    <span class="badge" style="background: #FFC6FF; width: 24px;">&nbsp;</span>
                                    <span class="ml-2">Croscutting</span>
                                </li>
                                <li class="list-group-item d-flex align-items-center p-1">
                                    <span class="badge" style="background: #9BF6FF; width: 24px;">&nbsp;</span>
                                    <span class="ml-2">Croscutting (PD Lain)</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </aside>

        <!-- 
            MAIN CONTENT COLUMN
            - On large screens (lg), it's 9 columns wide.
            - On mobile (md and down), it stacks and becomes full-width.
        -->
        <main class="col-lg-9 pt-3" id="cetak" role="main">

            <!-- Main Content -->
            <div class="main-content-area bg-white p-4 rounded shadow-sm">
                <div class="text-center mb-4">
                    <h2 class="h3 font-weight-bold">Pohon Kinerja</h2>
                    <h3 class="h4 namaSkpd">Memuat....</h3>
                    <h4 class="h5 text-muted namaJadwal">Memuat....</h4>
                </div>

                <hr>

                <div id="koneksi-pokin-section"></div>

                <div id="pokin-chart-wrapper" style="width: 100%; height: 600px; overflow: auto; border: 1px solid #ccc;">
                    <div id="chart_div_pokin" style="width: 100%; height: 100%;">
                        <p style="text-align: center; padding-top: 50px;">Memuat data pohon kinerja...</p>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/panzoom/9.4.3/panzoom.min.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/printThis/1.15.0/printThis.min.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
    var gChart;
    var gDataTable;
    var gFullPokinData;
    var $chartContainer = jQuery('#chart_div_pokin');
    var $chartWrapper = jQuery('#pokin-chart-wrapper');

    console.log('Script dimuat. Menunggu Google Charts...');

    google.charts.load('current', {
        packages: ["orgchart"]
    });
    google.charts.setOnLoadCallback(initPohonKinerja);

    function get_data_pokin() {
        return jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'handle_view_pokin',
                id: <?php echo $id_pohon_kinerja; ?>,
                id_koneksi_pokin: <?php echo json_encode($id_koneksi_pokin); ?>,
            },
            dataType: 'json'
        });
    }

    async function initPohonKinerja() {
        gChart = new google.visualization.OrgChart($chartContainer[0]);

        try {
            const response = await get_data_pokin();

            if (response.status && response.data) {
                gFullPokinData = response.data;
                const data_unit = response.info.data_unit;
                const data_jadwal = response.info.data_jadwal;
                const data_koneksi = response.info.data_koneksi;

                let namaPemda = response.info.nama_pemda || 'N/A';
                let namaSkpd = data_unit.nama_skpd || 'N/A';
                let namaJadwal = `${data_jadwal.nama_jadwal} (${data_jadwal.tahun_anggaran} - ${data_jadwal.tahun_selesai_anggaran})` || 'N/A';

                jQuery('.namaPemda').text(namaPemda);
                jQuery('.namaSkpd').text(namaSkpd);
                jQuery('.namaJadwal').text(namaJadwal);

                let currentLevel = gFullPokinData.data.level;
                let maxLevel = 5;

                generateLevelRadios(currentLevel, maxLevel);

                if (data_koneksi) {
                    generateKoneksiPokinSection(data_koneksi);
                }

                jQuery(`#level${currentLevel}`).prop('checked', true);

                jQuery('#level-radio-container').on('change', 'input[name="levelRadio"]', function() {
                    redrawChart();
                });

                jQuery('#zoomSlider').on('input', function() {
                    let zoomValue = jQuery(this).val();
                    jQuery('#zoomValue').text(zoomValue + '%');

                    $chartContainer.css({
                        'display': 'flex',
                        'justify-content': 'center',
                        'transform': 'scale(' + (zoomValue / 100) + ')',
                        'transform-origin': 'top center'
                    });
                });

                jQuery('#searchPohon').next('.input-group-append').find('button').on('click', performSearch);
                jQuery('#searchPohon').on('keypress', function(e) {
                    if (e.which === 13) performSearch();
                });

                jQuery('#print-chart-button').on('click', function() {

                    jQuery('.main-content-area').printThis({
                        debug: false, // Set true untuk debugging
                        importCSS: true, // Impor CSS halaman Anda saat ini
                        importStyle: true, // Impor tag <style> halaman Anda
                        printContainer: true, // Perlu untuk #pokin-chart-wrapper
                        pageTitle: "Pohon Kinerja", // Judul dokumen cetak
                        removeInline: false, // Jangan hapus style inline (penting untuk chart)
                        printDelay: 333, // Waktu tunggu sebelum cetak (ms)
                        header: null, // Tidak ada header
                        footer: null, // Tidak ada footer
                        base: false, // 
                        formValues: true, //
                        canvas: true, // Penting jika chart menggunakan canvas
                        doctypeString: '<!DOCTYPE html>', //
                        removeScripts: false // Jangan hapus script
                    });
                });

                redrawChart();

            } else {
                console.error('AJAX Gagal (status false atau data null):', response.message);
                $chartContainer.html('<p>Gagal mengambil data: ' + (response.message) + '</p>');
            }

        } catch (error) {
            console.error("AJAX Error Fatal:", error.statusText, error);
            $chartContainer.html('<p style="color:red;">Error koneksi saat mengambil data chart.</p>');
        }
    }

    function redrawChart() {
        if (!gFullPokinData) {
            console.error('redrawChart() dipanggil tapi gFullPokinData kosong.');
            return;
        }

        gDataTable = new google.visualization.DataTable();
        gDataTable.addColumn('string', 'Name');
        gDataTable.addColumn('string', 'Manager');
        gDataTable.addColumn('string', 'ToolTip');

        // Dapatkan level target
        let maxLevelToExpand = parseInt(jQuery('input[name="levelRadio"]:checked').val() || 1);

        var chartRows = [];

        flattenTreeData(gFullPokinData, '', chartRows);

        if (chartRows.length > 0) {
            gDataTable.addRows(chartRows);
            $chartContainer.empty();

            google.visualization.events.addOneTimeListener(gChart, 'ready', function() {

                let numRows = gDataTable.getNumberOfRows();

                // close all nodes
                for (let i = 0; i < numRows; i++) {
                    gChart.collapse(i, true); // true = collapse
                }

                // open selected level
                for (let i = 0; i < numRows; i++) {
                    let nodeHtml = gDataTable.getFormattedValue(i, 0);
                    if (!nodeHtml) continue;

                    let shouldExpand = false;

                    for (let level_to_check = 1; level_to_check < maxLevelToExpand; level_to_check++) {

                        let target_class_1 = `label${level_to_check}"`;
                        let target_class_2 = `label${level_to_check} `;

                        if (nodeHtml.includes(target_class_1) || nodeHtml.includes(target_class_2)) {
                            shouldExpand = true;
                            break; // Stop inner loop
                        }
                    }

                    if (shouldExpand) {
                        gChart.collapse(i, false); // false = expand
                    }
                }
            });

            console.log('[redrawChart] Memanggil gChart.draw()...');
            gChart.draw(gDataTable, {
                'allowHtml': true,
                'allowCollapse': true,
                'size': 'small',
                'compactRows': true
            });

        } else {
            $chartContainer.html('<p>Tidak ada data untuk ditampilkan.</p>');
        }
    }

    function generateKoneksiPokinSection(data) {
        let $section = jQuery('#koneksi-pokin-section');
        $section.html(`<span class="text-muted">data koneksi ditemukan, memuat...</span>`);

        let pokinList = [];
        let currentNode = data;

        // format data flat array
        while (currentNode && currentNode.data) {
            pokinList.push(currentNode.data);
            currentNode = currentNode.parent; // Pindah ke parent-nya
        }

        // sorting berdasarkan level ascending
        if (pokinList.length > 0) {
            pokinList.sort((a, b) => parseInt(a.level) - parseInt(b.level));
        }

        // html
        let html = `
            <div class="pb-2 mb-3">
                <h5 class="text-center font-weight-bold mb-0">
                    Koneksi Pohon Kinerja Pemerintah Daerah
                </h5>
            </div>
        `;

        // Cek jika array-nya kosong
        if (pokinList.length === 0) {
            html += `<p class="text-muted text-center">Tidak ada koneksi Pokin Pemda yang ditemukan.</p>`;
        } else {
            html += '<table class="borderless-table mb-4">';

            pokinList.forEach(item => {
                html += `
                    <tr>
                        <th class="text-left" style="width : 100px;">Level ${item.level}</th>
                        <th style="width: 20px;">:</th>
                        <td class="text-left">${item.label}</td>
                    </tr>
                `;
            });

            html += '</table>';
        }

        $section.html(html);
    }

    function flattenTreeData(node, parentId, rowsArray) {
        var nodeData = node.data;
        if (!nodeData) return;

        var nodeId = String(nodeData.id);
        var nodeLevel = parseInt(nodeData.level);

        var classLabel = `label${nodeLevel}`;
        var classIndikator = `indikator${nodeLevel}`;

        var nodeHtml = `<div class="base-label-class ${classLabel}">${nodeData.label}</div>`;
        if (node.indikator && node.indikator.length > 0) {
            node.indikator.forEach(function(ind) {
                nodeHtml += `<div class="${classIndikator} item-rincian" data-id="${ind.id}">IK : ${ind.label_indikator_kinerja}</div>`;
            });
        }
        var tooltip = 'Pelaksana: ' + (nodeData.pelaksana || 'N/A');

        rowsArray.push([{
            'v': nodeId,
            'f': nodeHtml
        }, parentId, tooltip]);

        if (node.child && node.child.length > 0) {
            node.child.forEach(function(childNode) {
                flattenTreeData(childNode, nodeId, rowsArray);
            });
        }
    }

    function performSearch() {
        if (!gDataTable) {
            console.error('performSearch() gagal, gDataTable kosong.');
            return;
        }

        let searchText = jQuery('#searchPohon').val().trim().toLowerCase();
        if (!searchText) {
            gChart.setSelection([]);
            return;
        }

        let numRows = gDataTable.getNumberOfRows();
        let found = false;
        let firstMatchRow = null;

        for (let i = 0; i < numRows; i++) {
            let nodeHtml = gDataTable.getFormattedValue(i, 0);
            let nodeText = jQuery('<div>').html(nodeHtml).text().toLowerCase();
            let tooltipText = gDataTable.getValue(i, 2).toLowerCase();
            let searchableText = nodeText + ' ' + tooltipText;

            if (searchableText.includes(searchText)) {
                found = true;
                firstMatchRow = i;
                break;
            }
        }

        if (found) {
            gChart.setSelection([{
                row: firstMatchRow,
                column: null
            }]);
            setTimeout(function() {
                let selectedElement = $chartContainer.find('.google-visualization-orgchart-nodesel').get(0);
                if (selectedElement) {
                    selectedElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center',
                        inline: 'center'
                    });
                } else {
                    console.warn('[Search] Gagal scroll, elemen .google-visualization-orgchart-nodesel tidak ditemukan di DOM.');
                }
            }, 100);
        } else {
            console.log('[Search] Teks tidak ditemukan.');
            alert('Data tidak ditemukan.');
            gChart.setSelection([]);
        }
    }

    function generateLevelRadios(currentLevel, maxLevel) {
        let $container = jQuery('#level-radio-container');
        $container.empty();
        for (let i = currentLevel; i <= maxLevel; i++) {
            let radioHtml = `
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="levelRadio" id="level${i}" value="${i}">
                    <label class="form-check-label small" for="level${i}">Lvl ${i}</label>
                </div>`;
            $container.append(radioHtml);
        }
    }
</script>