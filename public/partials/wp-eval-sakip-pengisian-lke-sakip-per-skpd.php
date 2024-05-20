<?php
global $wpdb;

if (!defined('WPINC')) {
    die;
}

$input = shortcode_atts(array(
    'id_jadwal' => null,
), $atts);

if (!empty($_GET) && !empty($_GET['id_skpd'])) {
    $id_skpd = $_GET['id_skpd'];
} else {
    die("skpd kosong");
}

$jadwal = $wpdb->get_row(
    $wpdb->prepare("
    SELECT
        *
    FROM esakip_data_jadwal
    WHERE id=%d
      AND tipe='lke'
      AND status!=0
    ", $input['id_jadwal']),
    ARRAY_A
);

$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);

$skpd = $wpdb->get_row(
    $wpdb->prepare("
    SELECT 
        nama_skpd
    FROM esakip_data_unit
    WHERE id_skpd=%d
      AND tahun_anggaran=%d
      AND active = 1
", $id_skpd, $tahun_anggaran_sakip),
    ARRAY_A
);

if (!empty($jadwal)) {
    $tahun_anggaran = $jadwal['tahun_anggaran'];
    $jenis_jadwal = $jadwal['jenis_jadwal'];
    $nama_jadwal = $jadwal['nama_jadwal'];
    $mulai_jadwal = $jadwal['started_at'];
    $selesai_jadwal = $jadwal['end_at'];
    $lama_pelaksanaan = $jadwal['lama_pelaksanaan'];
} else {
    $tahun_anggaran = '2024';
    $jenis_jadwal = '-';
    $nama_jadwal = '-';
    $mulai_jadwal = '-';
    $selesai_jadwal = '-';
    $lama_pelaksanaan = 1;
}
$timezone = get_option('timezone_string');
?>
<style>
    .wrap-table {
        overflow: auto;
        max-height: 100vh;
        width: 100%;
    }

    input:disabled,
    select:disabled,
    textarea:disabled {
        background: #8080804f;
    }

    .action-section {
        display: flex;
        margin: 2rem 0;
        margin-left: 10px;
    }

    .container {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-bottom: 30px;
    }

    .info-section {
        display: flex;
        justify-content: space-between;
        max-width: 400px;
        width: 100%;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        border-left: 5px solid #007BFF;
    }

    .info-section .label {
        font-weight: bold;
        color: #555;
    }

    .info-section .value {
        color: #007BFF;
        font-weight: bold;
    }

    @media (max-width: 480px) {
        .container {
            flex-direction: column;
            align-items: center;
        }

        .info-section {
            max-width: 100%;
            margin-bottom: 20px;
        }
    }
</style>

<body>
    <div class="container-md" id="cetak" title="Pengisian LKE SAKIP (<?php echo $jadwal['tahun_anggaran']; ?>)">
        <div style="padding: 10px;margin:0 0 3rem 0;">
            <h1 class="text-center" style="margin:3rem;">Pengisian LKE SAKIP<br><?php echo $skpd['nama_skpd'] ?><br><?php echo $jadwal['nama_jadwal']; ?> (<?php echo $jadwal['tahun_anggaran']; ?>)</h1>
        </div>
        <div class="action-section">
        </div>
        <div class="container">
            <div class="info-section">
                <span class="label">Total Nilai Usulan:</span>
                <span class="value" id="nilaiUsulanTotal"></span>
            </div>
            <div class="info-section">
                <span class="label">Total Nilai Penetapan:</span>
                <span class="value" id="nilaiPenetapanTotal"></span>
            </div>
        </div>
        <div class="wrap-table">
            <table id="table_pengisian_sakip" cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center" rowspan="2" colspan="4" style="vertical-align: middle;">Komponen/Sub Komponen</th>
                        <th class="text-center" rowspan="2" style="vertical-align: middle;">Bobot</th>
                        <th class="text-center" colspan="3">Penilaian PD/Perangkat Daerah</th>
                        <th class="text-center" rowspan="2" style="vertical-align: middle; width: 240px;">Bukti Dukung</th>
                        <th class="text-center" rowspan="2" style="vertical-align: middle; width: 240px;">Keterangan OPD</th>
                        <th class="text-center" rowspan="2" style="vertical-align: middle; width: 240px;">Kerangka Logis OPD</th>
                        <th class="text-center" colspan="3">Penilaian Evaluator</th>
                        <th class="text-center" rowspan="2" style="vertical-align: middle; width: 240px;">Keterangan Evaluator</th>
                        <th class="text-center" rowspan="2" style="vertical-align: middle; width: 240px;">Kerangka Logis Evaluator</th>
                        <th class="text-center" rowspan="2" style="vertical-align: middle;">Aksi</th>
                    </tr>
                    <tr>
                        <th class="text-center">Jawaban</th>
                        <th class="text-center">Nilai</th>
                        <th class="text-center">%</th>
                        <th class="text-center">Jawaban</th>
                        <th class="text-center">Nilai</th>
                        <th class="text-center">%</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal tambah bukti dukung -->
    <div class="modal fade" id="tambahBuktiDukungModal" tabindex="-1" role="dialog" aria-labelledby="tambahBuktiDukungModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahBuktiDukungModalLabel">Tambah Bukti Dukung</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formTambahBuktiDukung">
                        <input type="hidden" value="" id="idPenilaian" name="idPenilaian">
                        <div class="form-group">
                            <label for="linkBuktiDukung">Link Bukti Dukung</label>
                            <input type="text" class="form-control" id="linkBuktiDukung" name="linkBuktiDukung">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" onclick="submitBuktiDukung(); return false">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        jQuery(document).ready(function() {
            get_table_pengisian_sakip();
            run_download_excel_sakip();

            let dataHitungMundur = {
                'jenisJadwal': <?php echo json_encode(ucwords($jenis_jadwal)); ?>,
                'namaJadwal': <?php echo json_encode(ucwords($nama_jadwal)); ?>,
                'mulaiJadwal': <?php echo json_encode($mulai_jadwal); ?>,
                'selesaiJadwal': <?php echo json_encode($selesai_jadwal); ?>,
                'thisTimeZone': <?php echo json_encode($timezone); ?>
            };
            penjadwalanHitungMundur(dataHitungMundur);
        })


        function get_table_pengisian_sakip() {
            jQuery('#wrap-loading').show();
            jQuery.ajax({
                url: esakip.url,
                type: 'POST',
                data: {
                    action: 'get_table_pengisian_lke',
                    api_key: esakip.api_key,
                    id_jadwal: <?php echo $input['id_jadwal']; ?>,
                    id_skpd: <?php echo $id_skpd; ?>,
                },
                dataType: 'json',
                success: function(response) {
                    jQuery('#wrap-loading').hide();
                    console.log(response);
                    if (response.status === 'success') {
                        jQuery('#table_pengisian_sakip tbody').html(response.data.tbody);
                        jQuery('#nilaiUsulanTotal').html(response.data.total_nilai);
                        jQuery('#nilaiPenetapanTotal').html(response.data.total_nilai_penetapan);
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    jQuery('#wrap-loading').hide();
                    console.error(xhr.responseText);
                    alert('Terjadi kesalahan saat memuat tabel!');
                }
            });
        }

        function simpanPerubahan(id) {
            let nilaiUsulan = parseFloat(jQuery('#opsiUsulan' + id).val());
            if (nilaiUsulan === '') {
                return alert("Nilai Usulan Tidak Boleh Kosong!");
            }
            let idJadwal = <?php echo $input['id_jadwal']; ?>;
            if (idJadwal == '') {
                return alert("Id Jadwal Tidak Boleh Kosong!");
            }
            let ketUsulan = jQuery('#keteranganUsulan' + id).val();
            if (ketUsulan == '') {
                return alert("Keterangan Usulan Tidak Boleh Kosong!");
            }
            let buktiUsulan = jQuery('#buktiDukung' + id).val();
            if (buktiUsulan == '') {
                return alert("Bukti Usulan Tidak Boleh Kosong!");
            }
            let idSkpd = <?php echo $id_skpd; ?>;
            if (idSkpd == '') {
                return alert("ID SKPD Usulan Tidak Boleh Kosong!");
            }
            let idKomponenPenilaian = id;
            if (idKomponenPenilaian == '') {
                return alert("ID Komponen Penilaian Tidak Boleh Kosong!");
            }

            if (![0, 0.25, 0.5, 0.75, 1].includes(nilaiUsulan)) {
                alert("Nilai yang dimasukkan tidak valid!");
                return;
            }

            jQuery('#wrap-loading').show();
            jQuery.ajax({
                url: esakip.url,
                type: 'POST',
                data: {
                    action: 'tambah_nilai_lke',
                    id_skpd: idSkpd,
                    id_komponen_penilaian: idKomponenPenilaian,
                    id_jadwal: idJadwal,
                    nilai_usulan: nilaiUsulan,
                    ket_usulan: ketUsulan,
                    bukti_usulan: buktiUsulan,
                    api_key: esakip.api_key
                },
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    jQuery('#wrap-loading').hide();
                    if (response.status === 'success') {
                        alert(response.message);
                        get_table_pengisian_sakip();
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    jQuery('#wrap-loading').hide();
                    console.error(xhr.responseText);
                    alert('Terjadi kesalahan saat mengirim data!');
                }
            });
        }

        function simpanPerubahanPenetapan(id) {
            let nilaiPenetapan = parseFloat(jQuery('#opsiPenetapan' + id).val());
            if (nilaiPenetapan === '') {
                return alert("Nilai Penetapan Tidak Boleh Kosong!");
            }
            let idJadwal = <?php echo $input['id_jadwal']; ?>;
            if (idJadwal == '') {
                return alert("Id Jadwal Tidak Boleh Kosong!");
            }
            let ketPenetapan = jQuery('#keteranganPenetapan' + id).val();
            if (ketPenetapan == '') {
                return alert("Keterangan Penetapan Tidak Boleh Kosong!");
            }
            let idSkpd = <?php echo $id_skpd; ?>;
            if (idSkpd == '') {
                return alert("ID SKPD Penetapan Tidak Boleh Kosong!");
            }
            let idKomponenPenilaian = id;
            if (idKomponenPenilaian == '') {
                return alert("ID Komponen Penilaian Penetapan Tidak Boleh Kosong!");
            }

            if (![0, 0.25, 0.5, 0.75, 1].includes(nilaiPenetapan)) {
                alert("Nilai yang dimasukkan tidak valid!");
                return;
            }

            jQuery('#wrap-loading').show();
            jQuery.ajax({
                url: esakip.url,
                type: 'POST',
                data: {
                    action: 'tambah_nilai_penetapan_lke',
                    id_skpd: idSkpd,
                    id_komponen_penilaian: idKomponenPenilaian,
                    id_jadwal: idJadwal,
                    nilai_penetapan: nilaiPenetapan,
                    ket_penetapan: ketPenetapan,
                    api_key: esakip.api_key
                },
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    jQuery('#wrap-loading').hide();
                    if (response.status === 'success') {
                        alert(response.message);
                        get_table_pengisian_sakip();
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    jQuery('#wrap-loading').hide();
                    console.error(xhr.responseText);
                    alert('Terjadi kesalahan saat mengirim data!');
                }
            });
        }

        function tambahBuktiDukung(id) {
            jQuery('#wrap-loading').show();
            jQuery('#idPenilaian').val(id);
            jQuery('#linkBuktiDukung').val('');
            jQuery('#tambahBuktiDukungModal').modal('show');
            jQuery('#wrap-loading').hide();
        }
    </script>
</body>