<?php
global $wpdb;

if (!defined('WPINC')) {
    die;
}

$input = shortcode_atts(array(
    'tahun' => '2024',
    'periode' => '',
), $atts);

$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);

$periode = $wpdb->get_row(
    $wpdb->prepare("
    SELECT 
        *
    FROM esakip_data_jadwal
    WHERE id=%d
      AND status = 1
", $input['periode']),
    ARRAY_A
);
if (!empty($periode['tahun_selesai_anggaran']) && $periode['tahun_selesai_anggaran'] > 1) {
    $tahun_periode = $periode['tahun_selesai_anggaran'];
} else {
    $tahun_periode = $periode['tahun_anggaran'] + $periode['lama_pelaksanaan'];
}

$data_id_jadwal_rpjmd = $wpdb->get_row(
    $wpdb->prepare("
    SELECT 
        id_jadwal_rpjmd
    FROM esakip_pengaturan_rencana_aksi
    WHERE tahun_anggaran =%d
    AND active=1
", $input['tahun']), ARRAY_A);

if(empty($data_id_jadwal_rpjmd['id_jadwal_rpjmd'])){
    $id_jadwal_rpjmd = 0;
}else{
    $id_jadwal_rpjmd = $data_id_jadwal_rpjmd['id_jadwal_rpjmd'];
}
$cek_id_jadwal_rpjmd = empty($data_id_jadwal_rpjmd['id_jadwal_rpjmd']) ? 0 : 1;

$idtahun = $wpdb->get_results(
    "
		SELECT DISTINCT 
			tahun_anggaran 
		FROM esakip_data_unit        
        ORDER BY tahun_anggaran DESC",
    ARRAY_A
);
$tahun = "<option value='-1'>Pilih Tahun</option>";

foreach ($idtahun as $val) {
    $selected = '';
    if (!empty($input['tahun_anggaran']) && $val['tahun_anggaran'] == $input['tahun_anggaran']) {
        $selected = 'selected';
    }
    $tahun .= "<option value='$val[tahun_anggaran]' $selected>$val[tahun_anggaran]</option>";
}
?>
<style type="text/css">
    .wrap-table {
        overflow: auto;
        max-height: 100vh;
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
    
    a.btn{
        text-decoration: none !important;
    }

    thead th {
        vertical-align: middle !important;
        font-size: small;
        text-align: center;
    }

    #modal-renaksi-pemda thead th {
        font-size: medium !important;
    }

    #modal-renaksi-pemda .modal-body {
        max-height: 70vh;
        overflow-y: auto;
    }
    .table_dokumen_rencana_aksi_pemda {
        font-family:'Open Sans',-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif; 
        border-collapse: collapse; 
        width: 2900px; 
        table-layout: fixed; 
        overflow-wrap: break-word; 
        font-size: 90%;
    }
    .table_dokumen_rencana_aksi_pemda thead {
        position: sticky;
        top: -6px;
    }
    .table_dokumen_rencana_aksi_pemda .badge {
        white-space: normal;
        line-height: 1.3;
    }
</style>
<div class="container-md">
    <div id="cetak" style="padding: 5px; overflow: auto; max-height: 80vh;">
        <div style="padding: 10px;margin:0 0 3rem 0;">
            <h1 style="margin-top: 20px;" class="text-center">Rencana Aksi <?php echo $periode['nama_jadwal'] . ' ' . $periode['tahun_anggaran'] . ' - ' . $tahun_periode . ''; ?><br>Pemerintah Daerah<br> Tahun Anggaran <?php echo $input['tahun']; ?></h1 style="margin-top: 20px;">
            <div class="text-center" style="margin-bottom: 25px;">
            <div id="action" class="action-section hide-excel"></div>
            <div class="wrap-table">
                <table id="table_dokumen_rencana_aksi_pemda" cellpadding="2" cellspacing="0" contenteditable="false">
                    <thead style="background: #ffc491;">
                        <tr>
                            <th class="text-center" rowspan="2" style="width: 85px;">No</th>
                            <th class="text-center" rowspan="2" style="width: 200px;">KEGIATAN UTAMA</th>
                            <th class="text-center" rowspan="2" style="width: 200px;">INDIKATOR KEGIATAN UTAMA</th>
                            <th class="text-center" rowspan="2" style="width: 200px;">RENCANA AKSI</th>
                            <th class="text-center" colspan="2" style="width: 200px;">OUTCOME/OUTPUT</th>
                            <th class="text-center" rowspan="2" style="width: 140px;">TARGET</th>
                            <th class="text-center" rowspan="2" style="width: 200px;">URAIAN KEGIATAN RENCANA AKSI</th>
                            <th class="text-center" colspan="5" style="width: 400px;">TARGET KEGIATAN PER TRIWULAN</th>
                            <th class="text-center" rowspan="2" style="width: 140px;">JUMLAH ANGGARAN</th>
                            <th class="text-center" rowspan="2" style="width: 140px;">NAMA PERANGKAT DAERAH</th>
                            <th class="text-center" rowspan="2" style="width: 140px;">MITRA BIDANG</th>
                        </tr>
                        <tr>
                            <th class="text-center" style="width: 200px;">INDIKATOR</th>
                            <th class="text-center" style="width: 100px;">SATUAN</th>
                            <th>TW-I</th>
                            <th>TW-II</th>
                            <th>TW-III</th>
                            <th>TW-IV</th>
                            <th>AKHIR</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
jQuery(document).ready(function() {
    run_download_excel_sakip();
    jQuery('#action-sakip').prepend('<a style="margin-right: 10px;" id="tambah-rencana-aksi" onclick="return false;" href="#" class="btn btn-primary hide-print"><i class="dashicons dashicons-plus"></i> Tambah Data</a>');

    let id_jadwal = <?php echo $cek_id_jadwal_rpjmd; ?>;
    if(id_jadwal == 0){
        alert("Jadwal RENSTRA untuk data Pokin belum disetting.\nSetting Jadwal RENSTRA ada di admin dashboard di menu Monev Rencana Aksi -> Monev Rencana Aksi Setting")
    }

    getTablePengisianRencanaAksiPemda();
    window.id_jadwal = <?php echo $id_jadwal_rpjmd; ?>;
});

function getTablePengisianRencanaAksiPemda(no_loading=false) {
    if(no_loading == false){
        jQuery('#wrap-loading').show();
    }
    jQuery.ajax({
        url: esakip.url,
        type: 'POST',
        data: {
            action: 'get_table_input_rencana_aksi_pemda',
            api_key: esakip.api_key,
            tahun_anggaran: '<?php echo $input['tahun'] ?>'
        },
        dataType: 'json',
        success: function(response) {
            if(no_loading == false){
                jQuery('#wrap-loading').hide();
            }
            console.log(response);
            if (response.status === 'success') {
                jQuery('.table_dokumen_rencana_aksi_pemda tbody').html(response.data);
            } else {
                alert(response.message);
            }
        },
        error: function(xhr, status, error) {
            jQuery('#wrap-loading').hide();
            console.error(xhr.responseText);
            alert('Terjadi kesalahan saat memuat data Rencana Aksi!');
        }
    });
}
</script>