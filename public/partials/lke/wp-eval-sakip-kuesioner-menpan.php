<?php
global $wpdb;

if (!defined('WPINC')) {
    die;
}

$input = shortcode_atts(array(
    'tahun' => '',
), $atts);

$id_skpd = !empty($_GET['id_skpd']) ? intval($_GET['id_skpd']) : 0;

$skpd = $wpdb->get_row($wpdb->prepare("
    SELECT 
        nama_skpd 
    FROM esakip_data_unit 
    WHERE id_skpd=%d 
        AND tahun_anggaran=%d 
        AND active = 1
", $id_skpd, $input['tahun']), ARRAY_A);

$idtahun = $wpdb->get_results(
    "
        SELECT DISTINCT 
            tahun_anggaran 
        FROM esakip_data_unit        
        ORDER BY tahun_anggaran DESC",
    ARRAY_A
);
$tahun = '<option value="0">Pilih Tahun</option>';

foreach ($idtahun as $val) {
    if($val['tahun_anggaran'] == $input['tahun']){
        continue;
    }
    $selected = '';
    if($val['tahun_anggaran'] == $input['tahun']-1){
        $selected = 'selected';
    }
    $tahun .= '<option value="'. $val['tahun_anggaran']. '" '. $selected .'>'. $val['tahun_anggaran'] .'</option>';
}
$current_user = wp_get_current_user();
$user_roles = $current_user->roles;
$is_admin = in_array('administrator', $user_roles) || in_array('admin_panrb', $user_roles) || in_array("admin_bappeda", $current_user->roles) || in_array("administrator", $current_user->roles);

$nama_pemda = get_option(ESAKIP_NAMA_PEMDA);

$data_kuesioner = $wpdb->get_results(
    $wpdb->prepare("
        SELECT 
            * 
        FROM esakip_kuesioner_menpan
        WHERE tahun_anggaran = %d 
            AND active = 1
        ORDER BY nomor_urut ASC
    ", $input['tahun']),
    ARRAY_A
);

$chart_labels = [];
$chart_values = [];

$html = '
<div class="container-md" style="font-family:\'Times New Roman\', serif; padding: 0 20px;">
    <form id="form-kuesioner">
        <div>
            <p style="text-align: center !important; text-transform: uppercase">
                <b>LEMBAR HASIL EVALUASI KELEMBAGAAN<br>PERANGKAT DAERAH ' . strtoupper($nama_pemda) . ' TAHUN ' . $input['tahun'] . '<br>' . $skpd['nama_skpd'] . '
                </b>
            </p>
            <div class="d-flex justify-content-center" style="width: 100%; margin: 20px 0;">
                <div style="max-width: 1880px; width: 100%;">
                    <canvas id="marksChart"></canvas>
                </div>
            </div>';

$total_chart_value = 0;
$jumlah_kuesioner = 0;

//untuk menampilkan data peringkat
foreach ($data_kuesioner as $kuesioner_temp) {
    if ($kuesioner_temp['tipe_soal'] != 1) continue;

    $detail_all_temp = $wpdb->get_results($wpdb->prepare("
        SELECT 
            * 
        FROM esakip_kuesioner_menpan_detail
        WHERE id_kuesioner = %d 
            AND active = 1
        ORDER BY nomor_urut ASC, tipe_jawaban ASC
    ", $kuesioner_temp['id']), ARRAY_A);

    $pertanyaan_group_temp = [];
    foreach ($detail_all_temp as $detail) {
        $pertanyaan_group_temp[$detail['id_unik']][] = $detail;
    }

    $total_nilai_awal = 0;
    foreach ($pertanyaan_group_temp as $group) {
        foreach ($group as $detail) {
            $data_penilaian = $wpdb->get_row(
                $wpdb->prepare("
                    SELECT 
                        nilai
                    FROM esakip_penilaian_kuesioner_menpan
                    WHERE tahun_anggaran = %d
                      AND id_skpd = %d
                      AND id_detail = %d
                      AND id_unik = %d
                      AND active = 1
                    LIMIT 1
                ", $input['tahun'], $id_skpd, $detail['id'], $detail['id_unik']),
                ARRAY_A
            );
            if ($data_penilaian) {
                $total_nilai_awal += floatval($data_penilaian['nilai']);
                break;
            }
        }
    }

    $get_total_bobot_kuesioner_detail = $wpdb->get_var(
        $wpdb->prepare("
            SELECT 
                SUM(bobot)
            FROM esakip_kuesioner_menpan_detail
            WHERE id_kuesioner = %d
              AND active = 1
              AND tipe_jawaban = 1
        ", $kuesioner_temp['id'])
    );

    $persentase = ($get_total_bobot_kuesioner_detail != 0)
        ? round(($total_nilai_awal / $get_total_bobot_kuesioner_detail) * 100, 2)
        : 0;

    $chart_labels[] = $kuesioner_temp['nama_kuesioner'] . ' ' . $persentase;
    $chart_values[] = round($persentase);
}

$total_chart_value = array_sum($chart_values);
$jumlah_kuesioner = count($chart_values);
$nilai_komposit = ($jumlah_kuesioner > 0) ? round($total_chart_value / $jumlah_kuesioner, 2) : 0;

$peringkat = ($nilai_komposit >= 80) ? 'P-5' : 'P-4';
$peringkat_provinsi = '';

$narasi = ($nilai_komposit >= 80)
    ? 'Mencerminkan bahwa dari sisi struktur dan proses, organisasi dinilai tergolong sangat efektif. Struktur dan proses organisasi yang ada dinilai mempunyai kemampuan sangat tinggi untuk mengakomodir kebutuhan internal organisasi dan sangat mampu beradaptasi terhadap dinamika perubahan lingkungan eksternal organisasi.'
    : 'Mencerminkan bahwa dari sisi struktur dan proses, organisasi dinilai tergolong sangat efektif. Struktur dan proses organisasi yang ada dinilai mempunyai kemampuan sangat tinggi untuk mengakomodir kebutuhan internal organisasi dan sangat mampu beradaptasi terhadap dinamika perubahan lingkungan eksternal organisasi.';

$kondisi_struktur_proses = ($nilai_komposit >= 80) ? 'Sangat Efektif' : 'Efektif';
$kemampuan_akomodasi = ($nilai_komposit >= 80) ? 'Sangat Tinggi' : 'Tinggi';
$kekurangan = ($nilai_komposit >= 80) ? '-' : 'Lemah';

$html .= '
<div class="mt-4 mb-4">
    <table class="table table-bordered" style="width:100%; padding-left:20px; padding-right:20px;">
        <tr>
            <td colspan="2" class="text-center" style="border:1px solid black;"><strong>Peringkat Komposit : ' . $nilai_komposit . ' / ' . $peringkat . '</strong></td>
        </tr>
        <tr>
            <td style="border:1px solid black;" colspan="2" class="text-center"><strong>Peringkat Provinsi : ' . $peringkat_provinsi . '</strong></td>
        </tr>
        <tr>
            <td style="border:1px solid black;" colspan="2" style="text-align:justify;" class="text-center">' . $narasi . '</td>
        </tr>
        <tr>
            <td style="border:1px solid black;" style="width:60%;">Kondisi Dimensi Struktur dan Proses</td>
            <td style="border:1px solid black;">' . $kondisi_struktur_proses . '</td>
        </tr>
        <tr>
            <td style="border:1px solid black;">Kemampuan akomodasi kebutuhan internal dan adaptasi lingkungan eksternal</td>
            <td style="border:1px solid black;">' . $kemampuan_akomodasi . '</td>
        </tr>
        <tr>
            <td style="border:1px solid black;">Kekurangan</td>
            <td style="border:1px solid black;">' . $kekurangan . '</td>
        </tr>
    </table>
</div>
</div>';

// untuk menampilkan data kuesioner
foreach ($data_kuesioner as $kuesioner) {
    $html .= '<h3><strong>' . $kuesioner['nama_kuesioner'] . '</strong></h3>';

    $detail_all = $wpdb->get_results($wpdb->prepare("
        SELECT 
            * 
        FROM esakip_kuesioner_menpan_detail
        WHERE id_kuesioner = %d 
            AND active = 1
        ORDER BY nomor_urut ASC, tipe_jawaban ASC
    ", $kuesioner['id']), ARRAY_A);

    $pertanyaan_group = [];
    foreach ($detail_all as $detail) {
        $pertanyaan_group[$detail['id_unik']][] = $detail;
    }

    $html .= '<table class="table table-bordered" style="width:100%; margin-bottom:30px; padding-left:20px; padding-right:20px;">
        <thead>
            <tr>
                <th style="border:1px solid black;" width:30px;">No</th>
                <th style="border:1px solid black;" class="text-center">Pertanyaan</th>';

    if ($kuesioner['tipe_soal'] == 1) {
        $html .= '
                <th style="border:1px solid black;" colspan="2" class="text-center">Hasil Awal</th>
                <th style="border:1px solid black;" colspan="2" class="text-center">Hasil Akhir</th>
            </tr>
            <tr>
                <th style="border:1px solid black;"></th><th style="border:1px solid black;"></th>
                <th style="border:1px solid black;" width:80px;" class="text-center">Jawaban</th>
                <th style="border:1px solid black;" width:60px;" class="text-center">Skor</th>
                <th style="border:1px solid black;" width:100px;" class="text-center">Jawaban</th>
                <th style="border:1px solid black;" width:60px;" class="text-center">Skor</th>';
    } else {
        $html .= '
                <th style="border:1px solid black;" class="text-center">Hasil Awal</th>
                <th style="border:1px solid black;" class="text-center">Hasil Akhir</th>';
    }

    $html .= '</tr></thead><tbody>';

    $total_nilai_awal = 0;
    $no = 1;

    foreach ($pertanyaan_group as $group) {
        $pertanyaan = $group[0]['pertanyaan'];
        $jawaban_dipilih = '-';
        $nilai_dipilih = 0;

        foreach ($group as $detail) {
            $data_penilaian = $wpdb->get_row(
                $wpdb->prepare("
                    SELECT 
                        id_detail, 
                        jawaban, 
                        nilai
                    FROM esakip_penilaian_kuesioner_menpan
                    WHERE tahun_anggaran = %d
                      AND id_skpd = %d
                      AND id_detail = %d
                      AND id_unik = %d
                      AND active = 1
                    LIMIT 1
                ", $input['tahun'], $id_skpd, $detail['id'], $detail['id_unik']),
                ARRAY_A
            );

            if ($data_penilaian) {
                if ($kuesioner['tipe_soal'] == 1) {
                    switch ($detail['tipe_jawaban']) {
                        case 1: $jawaban_dipilih = 'STS'; break;
                        case 2: $jawaban_dipilih = 'TS'; break;
                        case 3: $jawaban_dipilih = 'S'; break;
                        case 4: $jawaban_dipilih = 'SS'; break;
                        case 5: $jawaban_dipilih = $data_penilaian['jawaban']; break;
                        default: $jawaban_dipilih = '-'; break;
                    }

                    $nilai_dipilih = floatval($data_penilaian['nilai']);
                    $total_nilai_awal += $nilai_dipilih;
                } else {
                    $jawaban_dipilih = $data_penilaian['jawaban'];
                }
                break;
            }
        }

        $html .= '<tr>
            <td style="border:1px solid black;" class="text-center">' . $no++ . '</td>
            <td style="border:1px solid black;">' . $pertanyaan . '</td>';

        if ($kuesioner['tipe_soal'] == 1) {
            $html .= '
            <td style="border:1px solid black;" class="text-center"><b>' . $jawaban_dipilih . '</td>
            <td style="border:1px solid black;" class="text-center">' . number_format($nilai_dipilih, 2) . '</td>
            <td style="border:1px solid black;" class="text-center"><b>BELUM DINILAI</td>
            <td style="border:1px solid black;" class="text-center">0</td>';
        } else {
            $html .= '
            <td style="border:1px solid black;">' . $jawaban_dipilih . '</td>
            <td style="border:1px solid black;"></td>';
        }

        $html .= '</tr>';
    }
    $html .= '<tr>
        <td class="text-center" colspan="6"  style="border:1px solid black;"><strong>
            Nilai ' . $kuesioner['nama_kuesioner'] . ' ' . number_format($total_nilai_awal, 3) . '</strong>
        </td>
    </tr>';
    $html .= '<tr>
        <td class="text-center" colspan="6"  style="border:1px solid black;"><strong>
            Nilai Akhir ' . $kuesioner['nama_kuesioner'] . ' 0.000</strong>
        </td>
    </tr>';

    $html .= '</tbody></table>';
}

$html .= '</div></form></div>';

$html .= '
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js" integrity="sha512-ElRFoEQdI5Ht6kZvyzXhYG9NqjtkmlkfYk0wr6wHxU9JEHakS7UJZNeml5ALk+8IKlU6jDgMabC3vkumRokgJA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    var marksCanvas = document.getElementById("marksChart");

    Chart.defaults.font.family = "Times New Roman";
    Chart.defaults.font.size = 15;
    Chart.defaults.color = "black";

    var marksData = {
        labels: ' . json_encode(array_values($chart_labels)) . ',
        datasets: [
            {
                label: "Nilai Awal",
                data: ' . json_encode(array_values($chart_values)) . ',
                fill: false,
                //backgroundColor: "rgba(54, 162, 235, 0.2)",
                borderColor: "rgb(54, 162, 235)",
                pointBackgroundColor: "rgb(54, 162, 235)",
                pointBorderColor: "#fff",
                pointHoverBackgroundColor: "#fff",
                pointHoverBorderColor: "rgb(54, 162, 235)"
            }
        ]
    };

    var chartOptions = {
        plugins: {
            title: {
                display: true,
                align: "start",
                text: "Struktur Organisasi dan Proses"
            },
            legend: {
                align: "start"
            }
        },
        scales: {
            r: {
                pointLabels: {
                    font: {
                        size: 15
                    }
                },
                suggestedMin: 0,
                suggestedMax: 100
            }
        }
    };

    var radarChart = new Chart(marksCanvas, {
        type: "radar",
        data: marksData,
        options: chartOptions
    });
</script>';


$laporan_kuesioner = $this->functions->modifyGetParameter(false, 'laporan', 'kuesioner');

$laporan = false;
if(!empty($_GET['laporan'])){
    $laporan = $_GET['laporan'];
}
?>
<style>
    body, table, td, th, input, textarea, select, label {
        font-size: 20px !important;
    }

    .form-check {
        display: flex;
        align-items: flex-start;
        margin-bottom: 10px;
    }

    .form-check-input {
        transform: scale(1.4);
        margin-top: 6px;
    }


    .form-check-label {
        font-size: 20px;
        line-height: 1.4;
    }

    .wrap-table {
        overflow: auto;
        max-height: 100vh;
        width: 100%;
    }

    .transparent-button {
        width: 100%;
    }

    .btn-action-group {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .btn-action-group .btn {
        margin: 0 5px;
    }

    .align-middle {
        vertical-align: middle !important;
    }

    .info-section {
        display: flex;
        justify-content: space-between;
        max-width: 400px;
        width: 100%;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        margin: 30px auto; 
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
    #action-sakip {
        padding: 5px 0;
        text-align: center;
    }

    #action-sakip a {
        text-decoration: none;
    }
</style>

<?php if(empty($laporan)): ?>
    <div class="container-md">
        <form id="form-kuesioner">
            <div style="padding: 10px;margin:0 0 3rem 0;">
                <h1 class="text-center mb-3">Pengisian Kuesioner Menpan<br><?php echo $skpd['nama_skpd'] ?><br>Tahun <?php echo $input['tahun']; ?></h1>
                <div class="action-section" style="display: flex; justify-content: center; align-items: center; margin-bottom: 20px;">
                    <div id="action-sakip" class="hide-print">
                        <div id="action-sakip" class="hide-print">
                            <a href="<?php echo $laporan_kuesioner; ?>" target="_blank" class="btn btn-success">Laporan Kuesioner</a>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="info-section">
                        <span class="label">Total Nilai:</span> 
                        <span class="value"><?php echo $nilai_komposit; ?></span>
                    </div>
                </div>
                <div class="wrap-table">
                    <table id="table_kuesioner_menpan" class="table table-bordered" style="width:100%;">
                        <thead>
                            <tr>
                                <th class="text-center" colspan="4" style="vertical-align: middle; width: 30%">Kuesioner/Nama Kuesioner</th>
                                <th class="text-center" style="vertical-align: middle; width:30%">Jawaban</th>
                                <th class="text-center" style="vertical-align: middle; width:5%">Nilai</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-kuesioner-menpan">
                            <tr>
                                <td colspan="6" class="text-center"></td>
                            </tr>
                        </tbody>
                    </table>
                    <div style="text-align: right; margin: 10px 20px 0px 0px;">
                        <button type="button" class="btn btn-primary" style="font-size: 24px; min-width: 200px;" onclick="submit_kuesioner(); return false;">Submit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
<?php endif; ?>
<?php if($laporan == 'kuesioner'): ?>   
    <?php echo $html; ?>
<?php endif; ?>
<script>
    jQuery(document).ready(function() {
        <?php if(empty($laporan)): ?>
            get_table_kuesioner();
        <?php endif; ?>
    });
    <?php if(empty($laporan)): ?>
        function get_table_kuesioner() {
            jQuery('#wrap-loading').show();
            jQuery.ajax({
                url: esakip.url,
                type: 'POST',
                data: {
                    action: 'get_table_penilaian_kuesioner_menpan',
                    api_key: esakip.api_key,
                    tahun_anggaran: <?php echo $input['tahun']; ?>,
                    id_skpd: <?php echo $id_skpd; ?>
                },
                dataType: 'json',
                success: function(response) {
                    jQuery('#wrap-loading').hide();
                    if (response.status === 'success') {
                        jQuery('#tbody-kuesioner-menpan').html(response.data.html);
                        // jQuery('#total-nilai-kuesioner').text(response.data.total_nilai_formatted);
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr) {
                    jQuery('#wrap-loading').hide();
                    alert('Gagal memuat data!');
                    console.error(xhr.responseText);
                }
            });
        }

       function submit_kuesioner() {
            jQuery('#wrap-loading').show();
            const data_jawaban = {};

            // Pilihan Ganda
            jQuery('input[type="radio"]:checked').each(function () {
                const id_detail = jQuery(this).data('id');
                const id_unik = jQuery(this).data('id_unik');
                data_jawaban[id_detail] = {
                    jawaban: jQuery(this).val(),
                    nilai: jQuery(this).data('nilai') || 0,
                    id_unik: id_unik || null
                };
            });

            // Esai
            jQuery('textarea[data-id]').each(function () {
                const id_detail = jQuery(this).data('id');
                const id_unik = jQuery(this).data('id_unik');
                const nilai_input = jQuery(`input[type="number"][data-id="${id_detail}"]`).val() || 0;
                data_jawaban[id_detail] = {
                    jawaban: jQuery(this).val(),
                    nilai: parseFloat(nilai_input),
                    id_unik: id_unik || null
                };
            });

            jQuery.ajax({
                url: esakip.url,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'tambah_penilaian_kuesioner_menpan',
                    api_key: esakip.api_key,
                    tahun_anggaran: <?php echo $input['tahun']; ?>,
                    id_skpd: <?php echo $id_skpd; ?>,
                    data_jawaban: data_jawaban
                },
                success: function (response) {
                    jQuery('#wrap-loading').hide();
                    alert(response.message);
                    location.reload();
                },
                error: function (xhr) {
                    jQuery('#wrap-loading').hide();
                    alert('Terjadi kesalahan jaringan!');
                    console.error(xhr.responseText);
                }
            });
        }
    <?php endif; ?>
</script>