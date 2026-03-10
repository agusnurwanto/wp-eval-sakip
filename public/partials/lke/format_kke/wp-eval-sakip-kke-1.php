<?php
global $wpdb;

if (!defined('WPINC')) {
    die;
}
$input = shortcode_atts(array(
    'id' => '',
), $atts);

$id = !empty($_GET['id_kke']) ? $_GET['id_kke'] : 0;
$id_skpd = !empty($_GET['id_skpd']) ? $_GET['id_skpd'] : 0;
$tahun_anggaran = !empty($_GET['tahun_anggaran']) ? $_GET['tahun_anggaran'] : 0;

$skpd = $wpdb->get_row(
    $wpdb->prepare("
        SELECT nama_skpd
        FROM esakip_data_unit
        WHERE id_skpd = %d
          AND tahun_anggaran = %d
          AND active = 1
    ", $id_skpd, $tahun_anggaran),
    ARRAY_A
);

$data_id_jadwal = $wpdb->get_row(
    $wpdb->prepare("
        SELECT id_jadwal_wp_sipd
        FROM esakip_pengaturan_upload_dokumen
        WHERE tahun_anggaran = %d
          AND active = 1
    ", $tahun_anggaran),
    ARRAY_A
);

$id_jadwal_wpsipd = !empty($data_id_jadwal['id_jadwal_wp_sipd'])
    ? intval($data_id_jadwal['id_jadwal_wp_sipd'])
    : 0;
?>

<style>
    .card-evaluasi .card-header { 
        background-color: #f8f9fa; 
    }
    .table th, .table td {
        vertical-align: middle !important;
        text-align: center !important;
        padding: 0.65rem !important;
    }
    .table thead th {
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #495057;
    }
    .table tfoot td { 
        font-weight: 600; 
        background-color: #f8f9fa; 
    }
    .keterangan-list { 
        padding-left: 0; 
        font-size: 0.85rem; 
    }
    .keterangan-list li { 
        margin-bottom: 5px; 
    }

    @keyframes spin {
        0%   { 
            transform: rotate(0deg); 
        }
        100% { 
            transform: rotate(360deg); 
        }
    }
</style>

<div class="container-fluid py-4">
    <div class="card card-evaluasi shadow-sm">
        <div class="card-header text-center py-3">
            <h5 class="mb-1 font-weight-bold">
                KKE EVALUASI PERENCANAAN KINERJA <?php echo $skpd['nama_skpd']; ?> (Format 1)
            </h5>
            <p class="mb-0 text-muted" style="font-size:0.9rem;">
                Sub Komponen Kualitas: Ukuran Keberhasilan (Indikator Kinerja) telah Memenuhi Kriteria SMART-C
            </p>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="table_kke" class="table table-bordered table-hover" style="min-width:900px; font-size:0.85rem;">
                    <thead class="thead-light">
                        <tr>
                            <th colspan="9" class="th-pertanyaan-12">Untuk Pertanyaan No 12</th>
                            <th colspan="4" class="th-pertanyaan-17">Untuk Pertanyaan No 17</th>
                        </tr>
                        <tr>
                            <th rowspan="2">No.</th>
                            <th rowspan="2">Tujuan, Sasaran, Program, Kegiatan</th>
                            <th rowspan="2">Indikator Kinerja</th>
                            <th rowspan="2">Target</th>
                            <th colspan="5">Kinerja (Y=1/T=0)</th>
                            <th rowspan="2">Jml Nilai (a&amp;b)</th>
                            <th>Kriteria (Y=1/T=0)</th>
                            <th rowspan="2" style="width: 500px;">Ket.</th>
                            <th rowspan="2" style="width: 150px;">Aksi</th>
                        </tr>
                        <tr>
                            <th style="width: 100px;">Spesifik</th>
                            <th style="width: 100px;">Dapat Diukur</th>
                            <th style="width: 100px;">Dapat Dicapai</th>
                            <th style="width: 100px;">Relevan</th>
                            <th style="width: 100px;">Berbatas Waktu</th>
                            <th style="width: 100px;">Menantang (Continuous Improvement)</th>
                        </tr>
                        <tr>
                            <th>(1)</th>
                            <th>(2)</th>
                            <th>(3)</th>
                            <th></th>
                            <th>(4)</th>                            
                            <th>(5)</th>
                            <th>(6)</th>
                            <th>(7)</th>
                            <th>(8)</th>                            
                            <th>(9)</th>
                            <th>(10)</th>
                            <th>(11)</th>
                            <th>(12)</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                        <tr>
                            <td colspan="9" class="text-left">Jumlah Nilai (jumlah kolom 9)</td>
                            <td id="total_nilai">0</td>
                            <td rowspan="2" class="text-center"></td>
                            <td rowspan="2" class="text-center"></td>
                            <td rowspan="2" class="text-center"></td>
                        </tr>
                        <tr>
                            <td colspan="9" class="text-left">
                                Persentase Pencapaian Kinerja = K32/(jumlah IK x 5) x 100%
                            </td>
                            <td id="persentase_nilai">0%</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="mt-4">
                <h6 class="font-weight-bold">Keterangan:</h6>
                <ul class="keterangan-list">
                    <li><b>Kolom (1):</b> cukup jelas.</li>
                    <li><b>Kolom (2):</b> diisi dengan pernyataan sasaran/program/kegiatan.</li>
                    <li><b>Kolom (3):</b> diisi dengan pernyataan indikator kinerja.</li>
                    <li><b>Kolom (4)–(8):</b> nilai 1 jika memenuhi syarat SMART, 0 jika tidak.</li>
                    <li><b>Kolom (9):</b> penjumlahan kolom (4)–(8).</li>
                    <li><b>Kolom (10):</b> nilai 1 jika memenuhi syarat "menantang"/perbaikan berkelanjutan.</li>
                    <li><b>Kolom (11):</b> catatan untuk perbaikan.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- Modal mapping -->
<div class="modal fade" id="MappingModal" tabindex="-1" role="dialog" aria-labelledby="MappingModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 40%; margin-top: 50px;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="MappingModalLabel">Mapping Data KKE</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formMapping">
                    <input type="hidden" id="mapping_id" name="id">
                    <input type="hidden" id="mapping_tipe" name="tipe">
                    <input type="hidden" id="mapping_kode" name="kode">
                    <input type="hidden" id="mapping_kode_indikator" name="kode_indikator">

                    <div class="form-group">
                        <label for="mapping_data">Pilih Data Tujuan</label>
                        <select id="mapping_data" class="form-control" required>
                            <option value="">Pilih Data</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="submit_mapping_kke_format_1(); return false">Simpan Mapping</button>
            </div>
        </div>
    </div>
</div>
<script>
    jQuery(document).ready(function () {
        get_table_kke();
    });

    function get_table_kke() {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_table_kke_format_1',
                api_key: esakip.api_key,
                id_skpd: <?php echo $id_skpd; ?>,
                id: <?php echo $id; ?>,
                tahun_anggaran: <?php echo $tahun_anggaran; ?>,
                id_jadwal_wpsipd: <?php echo $id_jadwal_wpsipd; ?>
            },
            dataType: 'json',
            success: function (response) {
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    jQuery('#table_kke tbody').html(response.data.html);
                    hitung_total_nilai();
                } else {
                    alert(response.message);
                }
            },
            error: function (xhr) {
                jQuery('#wrap-loading').hide();
                console.error(xhr.responseText);
                alert('Terjadi kesalahan saat memuat tabel!');
            }
        });
    }

    function onSmartChange(param) {
        var param  = jQuery(param);
        var row = param.closest('tr');

        var smartFields = [
            'spesifik', 
            'ukur', 
            'capaian', 
            'relavan', 
            'batas_waktu'
            ];
        var total = 0;

        smartFields.forEach(function (field) {
            var val = row.find('select[data-field="' + field + '"]').val();
            if (val !== '') {
                total += parseInt(val);
            }
        });

        var $badge = row.find('.cell-nilai-total');
        $badge.text(total).attr('data-nilai', total);

        $badge.removeClass('badge-nilai-5 badge-nilai-4 badge-nilai-3 badge-nilai-low');
        if (total === 5) {
            $badge.addClass('badge-nilai-5');
        } else if (total === 4) {
            $badge.addClass('badge-nilai-4');
        } else if (total === 3) {
            $badge.addClass('badge-nilai-3');
        } else {
            $badge.addClass('badge-nilai-low');
        }

        // Hitung total & persentase
        hitung_total_nilai();
    }

    function hitung_total_nilai() {
        var total = 0;
        var jumlah_ik = 0;

        jQuery('#table_kke tbody tr').each(function () {
            var $badge = jQuery(this).find('.cell-nilai-total');
            if ($badge.length) {
                total += parseInt($badge.attr('data-nilai') || 0);
                jumlah_ik++;
            }
        });

        var persen = (jumlah_ik > 0) ? ((total / (jumlah_ik * 5)) * 100).toFixed(2) : 0;

        jQuery('#total_nilai').text(total);
        jQuery('#persentase_nilai').text(persen + '%');
    }

    function simpan_kke_row(id) {
        var row = jQuery('#table_kke tbody tr[data-rowid="' + id + '"]');
        if (!row.length) {
            alert('Baris tidak ditemukan!');
            return;
        }

        var get_val = function (field) {
            return row.find('select[data-field="' + field + '"]').val();
        };

        var ket = row.find('.input-ket').val();

        var $btn = jQuery('#btn-simpan-' + id);
        $btn.prop('disabled', true).html('<span class="dashicons dashicons-update" style="animation:spin 1s linear infinite;"></span>');

        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'simpan_kke_format_1',
                api_key: esakip.api_key,
                id: id,
                spesifik: get_val('spesifik'),
                ukur: get_val('ukur'),
                capaian: get_val('capaian'),
                relavan: get_val('relavan'),
                batas_waktu: get_val('batas_waktu'),
                menantang: get_val('menantang'),
                ket: ket
            },
            dataType: 'json',
            success: function(response) {
                $btn.prop('disabled', false).html('<span class="dashicons dashicons-yes"></span>');
                console.log(response);
                if (response.status === 'success') {
                    alert(response.message);
                    get_table_kke();
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                $btn.prop('disabled', false).html('<span class="dashicons dashicons-yes"></span>');
                console.error(xhr.responseText);
                alert('Terjadi kesalahan saat mengirim data!');
                jQuery('#wrap-loading').hide();
            }
        });
    }

    function mapping_kke_format_1(id, kode, kode_indikator, tipe) {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'mapping_kke_format_1',
                api_key: esakip.api_key,
                tahun_anggaran: <?php echo $tahun_anggaran; ?>,
                id_skpd: <?php echo $id_skpd; ?>,
                id: id,
                kode: kode,
                kode_indikator: kode_indikator,
                tipe: tipe
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    jQuery("#mapping_id").val(id);
                    jQuery("#mapping_tipe").val(tipe);
                    jQuery("#mapping_kode").val(kode);
                    jQuery("#mapping_kode_indikator").val(kode_indikator);

                    var select = jQuery('#mapping_data');
                    select.empty();

                    if (response.data && response.data.length > 0) {
                        jQuery.each(response.data, function(i, item) {
                            var option = jQuery('<option></option>')
                                .val(item.id)
                                .text(item.capaian_teks + ' (' + item.target_capaian_teks + ')');
                            select.append(option);
                        });
                        jQuery('#MappingModal').modal('show');
                    } else {
                        if (confirm("Data tujuan tidak tersedia. Apakah Anda ingin menghapus data ini?")) {
                                hapus_kke_format_1(id, 1);
                            }
                    }
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr) {
                jQuery('#wrap-loading').hide();
                console.error(xhr.responseText);
                alert('Terjadi kesalahan saat memuat data!');
            }
        });
    }

    function submit_mapping_kke_format_1() {
        var id = jQuery('#mapping_id').val();
        var kode = jQuery('#mapping_kode').val();
        var kode_indikator = jQuery('#mapping_kode_indikator').val();
        var tipe = jQuery('#mapping_tipe').val();
        var id_tujuan = jQuery('#mapping_data').val();

        if (!id_tujuan) {
            alert('Silakan pilih data terlebih dahulu!');
            return false;
        }

        jQuery('#wrap-loading').show();

        jQuery.ajax({
            method: 'POST',
            url: esakip.url,
            dataType: 'json',
            data: {
                action: 'submit_mapping_kke_format_1',
                api_key: esakip.api_key,
                tahun_anggaran: <?php echo $tahun_anggaran; ?>,
                id_skpd: <?php echo $id_skpd; ?>,
                id: id,
                kode: kode,
                kode_indikator: kode_indikator,
                tipe: tipe,
                id_tujuan: id_tujuan
            },
            success: function(response) {
                jQuery('#wrap-loading').hide();
                alert(response.message);
                if (response.status === 'success') {
                    jQuery('#MappingModal').modal('hide');
                    get_table_kke();
                }
            },
            error: function(xhr) {
                jQuery('#wrap-loading').hide();
                console.error(xhr.responseText);
                alert('Terjadi kesalahan saat mengirim data!');
            }
        });
    }

    function hapus_kke_format_1(id, tipe) {
        if (!confirm('Apakah Anda yakin ingin menghapus data ini?')) {
            return;
        }

        var id_data = id;

        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'hapus_kke_format_1',
                api_key: esakip.api_key,
                id: id_data
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    alert(response.message);
                    get_table_kke();
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                jQuery('#wrap-loading').hide();
                alert('Terjadi kesalahan saat mengirim data!');
            }
        });
    }
</script>