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
          AND status!=0
    ", $input['id_jadwal']),
    ARRAY_A
);

if (empty($jadwal)) {
    die(print_r($wpdb->last_query . '[id_jadwal= ' . $input['id_jadwal']));
}
$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);

$skpd = $wpdb->get_row(
    $wpdb->prepare("
    SELECT 
        kode_skpd,
        nama_skpd
    FROM esakip_data_unit
    WHERE id_skpd=%d
      AND tahun_anggaran=%d
      AND active = 1
", $id_skpd, $tahun_anggaran_sakip),
    ARRAY_A
);
?>
<style>
    .wrap-table {
        overflow: auto;
        max-height: 100vh;
        width: 100%;
    }
</style>

<body>
    <div class="container-md">
        <div style="padding: 10px;margin:0 0 3rem 0;">
            <h1 class="text-center" style="margin:3rem;">Pengisian LKE SAKIP<br><?php echo $skpd['kode_skpd'] . ' ' . $skpd['nama_skpd'] ?><br><?php echo $jadwal['nama_jadwal']; ?> (<?php echo $jadwal['tahun_anggaran']; ?>)</h1>
            <div class="wrap-table">
                <table id="table_pengisian_sakip" cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center" rowspan="2" colspan="4" style="vertical-align: middle;">Komponen/Sub Komponen</th>
                            <th class="text-center" rowspan="2" style="vertical-align: middle;">Bobot</th>
                            <th class="text-center" colspan="3">Penilaian PD/Perangkat Daerah</th>
                            <th class="text-center" rowspan="2" style="vertical-align: middle;">Bukti Dukung</th>
                            <th class="text-center" rowspan="2" style="vertical-align: middle;">Keterangan OPD</th>
                            <th class="text-center" colspan="3">Penilaian Evaluator</th>
                            <th class="text-center" rowspan="2" style="vertical-align: middle;">Keterangan Penilai</th>
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
    </div>

    <script>
        jQuery(document).ready(function() {
            get_table_pengisian_sakip();
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
                        jQuery('#table_pengisian_sakip tbody').html(response.data);
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
    </script>
</body>