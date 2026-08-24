<?php
if (!defined('WPINC')) {
    die;
}

$input = shortcode_atts(array(
    'tahun_anggaran' => ''
), $atts);

if (empty($input['tahun_anggaran'])) {
    die('Parameter tidak lengkap!');
}

global $wpdb;

$nama_pemda = get_option(ESAKIP_NAMA_PEMDA);

?>

<div class="container-fluid mt-4">

    <div class="text-center mb-4">
        <h1 class="dashboard-title">MONITOR REALISASI INDIKATOR RENCANA HASIL KERJA</h1>
        <h1><?php echo strtoupper($nama_pemda); ?></h1>
        <h1>TAHUN <?php echo $input['tahun_anggaran']; ?></h1>
    </div>

    <!-- TW SELECTOR -->
    <div class="row mb-4">
        <div class="col-md-3">
            <label for="select-triwulan"><strong>Pilih Triwulan:</strong></label>
            <select id="select-triwulan" class="form-control">
                <option value="1">Triwulan 1</option>
                <option value="2">Triwulan 2</option>
                <option value="3">Triwulan 3</option>
                <option value="4">Triwulan 4</option>
            </select>
        </div>
    </div>

    <!-- CONTENT (hidden until loaded) -->
    <div id="monitor-rhk-content" style="display:none;">

        <!-- SUMMARY -->
        <div class="row mb-4">

            <!-- TAGGING PEGAWAI -->
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">

                        <h6 class="text-muted">
                            TOTAL RHK SUDAH DITAGGINGKAN PEGAWAI
                        </h6>

                        <h3 class="mb-2">
                            <span id="summary-tagged">0</span>
                            <small class="text-muted">/ <span id="summary-total-rhk">0</span></small>
                        </h3>

                        <div class="progress" style="height:20px;">
                            <div
                                id="summary-tagging-bar"
                                class="progress-bar bg-info"
                                style="width:0%">
                                0%
                            </div>
                        </div>

                    </div>
                </div>
            </div>


            <!-- REALISASI -->
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">

                        <h6 class="text-muted">
                            TOTAL INDIKATOR RHK SUDAH INPUT REALISASI
                            <span class="badge badge-secondary" id="summary-tw-badge"></span>
                        </h6>

                        <h3 class="mb-2">
                            <span id="summary-filled">0</span>
                            <small class="text-muted">/ <span id="summary-total-indikator">0</span></small>
                        </h3>

                        <div class="progress" style="height:20px;">
                            <div
                                id="summary-realisasi-bar"
                                class="progress-bar bg-success"
                                style="width:0%">
                                0%
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        <!-- TABLE OPD -->
        <div class="card mt-4 shadow-sm">
            <div class="card-header">
                <strong>Progress Input Realisasi per OPD</strong>
            </div>

            <div class="card-body">

                <table id="table-monitor-rhk" class="table table-bordered table-striped mb-0" style="width:100%;">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center">Perangkat Daerah</th>
                            <th class="text-center" width="8%">RHK</th>
                            <th class="text-center" width="17%">Tagging Pegawai</th>
                            <th class="text-center" width="8%">Indikator</th>
                            <th class="text-center" width="30%">Progress Realisasi</th>
                        </tr>
                    </thead>

                    <tbody>
                    </tbody>

                </table>

            </div>
        </div>

    </div>

</div>

<script>
    var tahun_anggaran = '<?php echo $input['tahun_anggaran']; ?>';

    function getProgressBarClass(pct) {
        if (pct >= 75) return 'bg-success';
        if (pct >= 50) return 'bg-info';
        if (pct >= 25) return 'bg-warning';
        return 'bg-danger';
    }

    function loadData(triwulan) {
        jQuery('#wrap-loading').show();
        jQuery('#monitor-rhk-content').hide();

        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_data_monitoring_indikator_rhk',
                api_key: esakip.api_key,
                tahun_anggaran: tahun_anggaran,
                triwulan: triwulan
            },
            dataType: 'json',
            success: function(res) {
                if (!res.status) {
                    alert(res.message || 'Gagal memuat data.');
                    jQuery('#wrap-loading').hide();
                    return;
                }

                var data = res.data;
                var s = data.summary;

                // Update TW selector to match server response
                jQuery('#select-triwulan').val(data.selected_triwulan);
                jQuery('#summary-tw-badge').text('TW ' + data.selected_triwulan);

                // Summary - Tagging
                var taggingPct = s.rencana_aksi_total > 0
                    ? Math.round((s.tagged_rencana_aksi / s.rencana_aksi_total) * 100)
                    : 0;
                jQuery('#summary-tagged').text(s.tagged_rencana_aksi);
                jQuery('#summary-total-rhk').text(s.rencana_aksi_total);
                jQuery('#summary-tagging-bar')
                    .css('width', taggingPct + '%')
                    .text(taggingPct + '%')
                    .removeClass('bg-success bg-info bg-warning bg-danger')
                    .addClass(getProgressBarClass(taggingPct));

                // Summary - Realisasi
                var realisasiPct = s.indikator_total > 0
                    ? Math.round((s.filled_indikator / s.indikator_total) * 100)
                    : 0;
                jQuery('#summary-filled').text(s.filled_indikator);
                jQuery('#summary-total-indikator').text(s.indikator_total);
                jQuery('#summary-realisasi-bar')
                    .css('width', realisasiPct + '%')
                    .text(realisasiPct + '%')
                    .removeClass('bg-success bg-info bg-warning bg-danger')
                    .addClass(getProgressBarClass(realisasiPct));

                // DataTable
                if (jQuery.fn.DataTable.isDataTable('#table-monitor-rhk')) {
                    jQuery('#table-monitor-rhk').DataTable().destroy();
                }

                var tbody = '';
                jQuery.each(data.details, function(i, row) {
                    var barClass = getProgressBarClass(row.realisasi_pct);
                    var taggingBarClass = getProgressBarClass(row.tagging_pct);

                    tbody += '<tr>';
                    tbody += '<td>' + row.nama_skpd + '</td>';
                    tbody += '<td class="text-center">' + row.tagged_rhk + ' / ' + row.total_rhk + '</td>';
                    tbody += '<td>';
                    tbody += '<div class="progress" style="height:20px;">';
                    tbody += '<div class="progress-bar ' + taggingBarClass + '" style="width:' + row.tagging_pct + '%">' + row.tagging_pct + '%</div>';
                    tbody += '</div>';
                    tbody += '</td>';
                    tbody += '<td class="text-center">' + row.filled_indikator + ' / ' + row.total_indikator + '</td>';
                    tbody += '<td>';
                    tbody += '<div class="progress" style="height:20px;">';
                    tbody += '<div class="progress-bar ' + barClass + '" style="width:' + row.realisasi_pct + '%">' + row.realisasi_pct + '%</div>';
                    tbody += '</div>';
                    tbody += '</td>';
                    tbody += '</tr>';
                });

                jQuery('#table-monitor-rhk tbody').html(tbody);

                jQuery('#table-monitor-rhk').DataTable({
                    paging: true,
                    searching: true,
                    ordering: true,
                    info: true,
                    pageLength: -1,
                    lengthMenu: [[25, 50, 100, -1], [25, 50, 100, "Semua"]],
                    order: [[4, 'desc']],
                    language: {
                        search: "Cari:",
                        lengthMenu: "Tampilkan _MENU_ data",
                        info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                        paginate: {
                            first: "Pertama",
                            last: "Terakhir",
                            next: "Selanjutnya",
                            previous: "Sebelumnya"
                        },
                        zeroRecords: "Tidak ada data ditemukan",
                        emptyTable: "Tidak ada data tersedia"
                    },
                    columnDefs: [
                        { orderable: false, targets: [0] }
                    ]
                });

                jQuery('#wrap-loading').hide();
                jQuery('#monitor-rhk-content').show();
            },
            error: function() {
                alert('Terjadi kesalahan saat memuat data.');
                jQuery('#wrap-loading').hide();
            }
        });
    }

    jQuery(document).ready(function() {
        loadData('');

        jQuery('#select-triwulan').on('change', function() {
            loadData(jQuery(this).val());
        });
    });
</script>
