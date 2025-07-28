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
<div class="container-md" style="font-family:\'Times New Roman\', serif;">
    <form id="form-kuesioner">
        <div>
            <h3 class="text-center mb-3">
                LEMBAR HASIL EVALUASI KELEMBAGAAN<br>
                PERANGKAT DAERAH ' . strtoupper($nama_pemda) . ' TAHUN ' . $input['tahun'] . '<br>' . $skpd['nama_skpd'] . '
            </h3>
            <div style="width:100%;max-width:1200px;margin:20px auto;">
                <canvas id="marksChart"></canvas>
            </div>
        </div>';

foreach ($data_kuesioner as $kuesioner) {
    $html .= '<h3><strong>' . $kuesioner['nama_kuesioner'] . '</strong></h3>';

    $detail_all = $wpdb->get_results($wpdb->prepare("
        SELECT * 
        FROM esakip_kuesioner_menpan_detail
        WHERE id_kuesioner = %d AND active = 1
        ORDER BY nomor_urut ASC, tipe_jawaban ASC
    ", $kuesioner['id']), ARRAY_A);

    $pertanyaan_group = [];
    foreach ($detail_all as $detail) {
        $pertanyaan_group[$detail['id_unik']][] = $detail;
    }

    $html .= '<table class="table table-bordered" style="width:100%; margin-bottom:30px;">
        <thead>
            <tr>
                <th style="width:30px;">No</th>
                <th class="text-center">Pertanyaan</th>';

    if ($kuesioner['tipe_soal'] == 1) {
        $html .= '
                <th colspan="2" class="text-center">Hasil Awal</th>
                <th colspan="2" class="text-center">Hasil Akhir</th>
            </tr>
            <tr>
                <th></th><th></th>
                <th style="width:80px;">Jawaban</th>
                <th style="width:60px;">Skor</th>
                <th style="width:100px;">Jawaban</th>
                <th style="width:60px;">Skor</th>';
    } else {
        $html .= '<th class="text-center">Hasil Awal</th><th class="text-center">Hasil Akhir</th>';
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
                    SELECT id_detail, jawaban, nilai
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
            <td class="text-center">' . $no++ . '</td>
            <td>' . $pertanyaan . '</td>';

        if ($kuesioner['tipe_soal'] == 1) {
            $html .= '
            <td class="text-center">' . $jawaban_dipilih . '</td>
            <td class="text-center">' . number_format($nilai_dipilih, 2) . '</td>
            <td class="text-center">BELUM DINILAI</td>
            <td class="text-center">0</td>';
        } else {
            $html .= '<td>' . $jawaban_dipilih . '</td><td></td>';
        }

        $html .= '</tr>';
    }

    if ($kuesioner['tipe_soal'] == 1) {
        $nilai_format = number_format($total_nilai_awal, 3);
        $explode = explode('.', $nilai_format);
        $desimal = isset($explode[1]) ? $explode[1] : '000';

        $nilai_bulat = $nilai_format;
        if (isset($desimal[2]) && $desimal[2] == '0') {
            $nilai_bulat = number_format(round($total_nilai_awal), 3);
        }

        // Dapatkan total bobot
        $get_total_bobot_kuesioner_detail = $wpdb->get_var(
            $wpdb->prepare("
                SELECT 
                    SUM(bobot)
                FROM esakip_kuesioner_menpan_detail
                WHERE id_kuesioner = %d
                  AND active = 1
                  AND tipe_jawaban = 1
            ", $kuesioner['id'])
        );

        if ($get_total_bobot_kuesioner_detail !== null) {
            if ($get_total_bobot_kuesioner_detail >= 500 && $get_total_bobot_kuesioner_detail < 600) {
                $total_bobot_kuesioner_detail = 500;
            } elseif ($get_total_bobot_kuesioner_detail < 100) {
                $total_bobot_kuesioner_detail = round($get_total_bobot_kuesioner_detail * 2) / 2;
            } else {
                $total_bobot_kuesioner_detail = round($get_total_bobot_kuesioner_detail);
            }
        } else {
            $total_bobot_kuesioner_detail = 1; // fallback untuk hindari div 0
        }

        // Hitung nilai untuk chart
        $persentase = ($total_bobot_kuesioner_detail != 0) 
            ? round(($nilai_bulat / $total_bobot_kuesioner_detail) * 100, 2)
            : 0;

        $html .= '<tr>
            <td class="text-center" colspan="6"><strong>
                Nilai ' . $kuesioner['nama_kuesioner'] . ' ' . $nilai_bulat . '<br>
                Nilai Akhir ' . $kuesioner['nama_kuesioner'] . ' 0.000</strong>
            </td>
        </tr>';

        $chart_labels[] = $kuesioner['nama_kuesioner'] . ' ' . $persentase;
        $chart_values[] = $persentase;
    }

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
                        <span class="value" id="total-nilai-kuesioner">0.00</span>
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
                        jQuery('#total-nilai-kuesioner').text(response.data.total_nilai_formatted);
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