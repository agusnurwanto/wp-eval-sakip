<?php
global $wpdb;

if (!defined('WPINC')) {
    die;
}
$tahun_anggaran = isset($_GET['tahun']) ? intval($_GET['tahun']) : 0;

$input = shortcode_atts(array(
    'tahun' => $tahun_anggaran,
    'id_skpd' => 0,
    'periode' => 0
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
    FROM esakip_pengaturan_upload_dokumen
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

    .help-rhk-pemda .dashicons {
        text-decoration: none;
        vertical-align: text-bottom !important;
        font-size: 23px !important;
    }
</style>
<div class="container-md">
    <div id="cetak" title="Rencana Aksi Pemda" style="padding: 5px;">
        <div style="padding: 10px;margin:0 0 3rem 0;">
            <h1 style="margin-top: 20px;" class="text-center">Rencana Aksi <?php echo $periode['nama_jadwal'] . ' ' . $periode['tahun_anggaran'] . ' - ' . $tahun_periode . ''; ?><br>Pemerintah Daerah<br> Tahun Anggaran <?php echo $input['tahun']; ?></h1 style="margin-top: 20px;">
            <div class="text-center" style="margin-bottom: 25px;">
                <div id="action" class="action-section hide-excel"></div>
            </div>
            <div class="wrap-table">
                <table cellpadding="2" cellspacing="0" class="table_dokumen_rencana_aksi_pemda table table-bordered">
                    <thead style="background: #ffc491;">
                        <tr>
                            <th class="atas kiri bawah kanan text-center" style="width: 85px;">No</th>
                            <th class="atas kiri bawah kanan text-center">SASARAN STRATEGIS</th>
                            <th class="atas kiri bawah kanan text-center">INDIKATOR KINERJA</th>
                            <th class="atas kiri bawah kanan text-center">NAMA PERANGKAT DAERAH</th>
                            <th class="atas kiri bawah kanan text-center">RENCANA AKSI PERANGKAT DAERAH</th>
                            <th class="atas kiri bawah kanan text-center">PROGRAM PERANGKAT DAERAH</th>
                            <th class="atas kiri bawah kanan text-center" style="width: 200px;">RENCANA PAGU</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Modal Tahun -->
<div class="modal fade" id="tahunModal" tabindex="-1" role="dialog" aria-labelledby="tahunModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tahunModalLabel">Pilih Tahun Anggaran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="tahunForm">
                    <div class="form-group">
                        <label for="tahunAnggaran">Tahun Anggaran:</label>
                        <select class="form-control" id="tahunAnggaran" name="tahunAnggaran">
                            <?php echo $tahun; ?>
                        </select>
                        <input type="hidden" id="idDokumen" value="">
                    </div>
                    <button type="submit" class="btn btn-primary" onclick="submit_tahun_renja_rkt(); return false">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal Renaksi -->
<div class="modal fade" id="modal-renaksi-pemda" role="dialog" data-backdrop="static" aria-hidden="true">'
    <div class="modal-dialog" style="max-width: 1500px;" role="document">
        <div class="modal-content">
            <div class="modal-header bgpanel-theme">
                <h4 style="margin: 0;" class="modal-title">Data Rencana Aksi</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span><i class="dashicons dashicons-dismiss"></i></span></button>
            </div>
            <div class="modal-body">
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-level-1" role="tabpanel" aria-labelledby="nav-level-1-tab"></div>
                    <div class="tab-pane fade" id="nav-level-2" role="tabpanel" aria-labelledby="nav-level-2-tab"></div>
                    <div class="tab-pane fade" id="nav-level-3" role="tabpanel" aria-labelledby="nav-level-3-tab"></div>
                    <div class="tab-pane fade" id="nav-level-4" role="tabpanel" aria-labelledby="nav-level-4-tab"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal crud -->
<div class="modal fade" id="modal-crud" data-backdrop="static"  role="dialog" aria-labelledby="modal-crud-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>
<!-- Modal edit renaksi pemda -->
<div class="modal fade mt-5" id="modal-edit-renaksi" tabindex="-1" role="dialog" aria-labelledby="modal-edit-renaksiLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-edit-renaksiLabel">Edit Rencana Aksi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <input type="hidden" value="" id="id_data">
                    <div class="form-group">
                        <label for="sasaran_strategis">SASARAN STRATEGIS</label>
                        <input type="text" id="sasaran_strategis" class="form-control" disabled>
                    </div>
                    <div class="form-group">
                        <label for="indikator_kinerja">INDIKATOR KINERJA</label>
                        <input type="text" id="indikator_kinerja" class="form-control" disabled>
                    </div>
                    <div class="form-group">
                        <label for="id_skpd">PERANGKAT DAERAH</label>
                        <select class="form-control" multiple name="id_skpd" id="id_skpd"></select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary" onclick="submit_edit_renaksi(); return false">Simpan</button>
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

    getTableRencanaAksiPemda();

    jQuery("#tambah-rencana-aksi").on('click', function(){
        getTable();
    });
    jQuery('#skpd').select2({
        'width': '100%'
    });
});

function getTableRencanaAksiPemda(no_loading=false) {
    if(no_loading == false){
        jQuery('#wrap-loading').show();
    }
    jQuery.ajax({
        url: esakip.url,
        type: 'POST',
        data: {
            action: 'get_table_rencana_aksi_pemda_baru',
            api_key: esakip.api_key,
            tahun_anggaran: '<?php echo $input['tahun'] ?>',
            id_jadwal: '<?php echo $input['periode'] ?>'
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

function getTable() {
    jQuery("#wrap-loading").show();
    jQuery.ajax({
        url: esakip.url,
        type: "post",
        data: {
            action: "get_data_renaksi_pemda_baru",
            api_key: esakip.api_key,
            tahun_anggaran: '<?php echo $input["tahun"] ?>',
            id_jadwal: '<?php echo $input["periode"] ?>'
        },
        dataType: "json",
        success: function (res) {
            jQuery("#wrap-loading").hide();
            let html = `
            <table class="table" id="getTable">
                <thead>
                    <tr class="table-secondary">
                        <th class="text-center" style="width:40px;">No</th>
                        <th class="text-center" style="width:300px;">Sasaran Strategis</th>
                        <th class="text-center" style="width:300px;">Indikator Kinerja</th>
                        <th class="text-center" style="width:300px;">Perangkat Daerah</th>
                        <th class="text-center" style="width:300px;">Rencana Aksi Perangkat Daerah</th>
                        <th class="text-center" style="width:200px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>`;

            res.data.forEach((item, index) => {
                let group = {};
                let totalRow = 0;

                (item.renaksi_opd || []).forEach(function(row) {
                    if (!group[row.id_skpd]) {
                        group[row.id_skpd] = {
                            nama_skpd: row.nama_skpd,
                            id_skpd_label: row.id_skpd_label,
                            renaksi: []
                        };
                    }
                    group[row.id_skpd].renaksi.push(row.label);
                    totalRow++;
                });

                let rowIndex = 0;
                for (let id in group) {
                    let g = group[id];
                    let jmlRenaksi = g.renaksi.length;

                    for (let i = 0; i < jmlRenaksi; i++) {
                        html += '<tr>';

                        if (rowIndex === 0) {
                            let label_sasaran = (item.id_iku && item.ik_label_sasaran) 
                                ? item.ik_label_sasaran 
                                : (item.label_sasaran || '');

                            let label_indikator = (item.id_iku && item.ik_label_indikator) 
                                ? item.ik_label_indikator 
                                : (item.label_indikator || '');

                            html += `<td rowspan="${totalRow}">${index + 1}</td>`;
                            html += `<td rowspan="${totalRow}">${label_sasaran}</td>`;
                            html += `<td rowspan="${totalRow}">${label_indikator}</td>`;
                        }

                        if (i === 0) {
                            html += `<td rowspan="${jmlRenaksi}">${g.nama_skpd}</td>`;
                        }

                        html += `<td>${g.renaksi[i]}</td>`;

                        if (rowIndex === 0) {
                            html += `<td class="text-center" rowspan="${totalRow}">
                                <a href="javascript:void(0)" onclick="edit_rencana_aksi(${item.id}, 1)" data-id="${item.id}" class="btn btn-sm btn-primary edit-kegiatan-utama" title="Edit">
                                    <i class="dashicons dashicons-edit"></i>
                                </a>
                            </td>`;
                        }

                        html += '</tr>';
                        rowIndex++;
                    }

                }
            });

            html += '</tbody></table>';
            jQuery("#nav-level-1").html(html);
            jQuery('#modal-renaksi-pemda').modal('show');
        }
    });
}


function edit_rencana_aksi(id){
    jQuery('#wrap-loading').show();
    jQuery.ajax({
        url: esakip.url,
        type: 'POST',
        data: {
            action: 'get_edit_data_renaksi_pemda_baru',
            api_key: esakip.api_key,
            id: id,
            tahun_anggaran: '<?php echo $input['tahun'] ?>',
            id_jadwal: '<?php echo $input['periode'] ?>'
        },
        dataType: 'json',
        success: function(response) {
            jQuery('#wrap-loading').hide();
            if (response.status === 'success') {
                let label_sasaran = (response.data.id_iku && response.data.ik_label_sasaran) 
                    ? response.data.ik_label_sasaran 
                    : (response.data.label_sasaran || '');

                let label_indikator = (response.data.id_iku && response.data.ik_label_indikator) 
                    ? response.data.ik_label_indikator 
                    : (response.data.label_indikator || '');

                jQuery('#sasaran_strategis').val(label_sasaran);
                jQuery("#indikator_kinerja").val(label_indikator);

                let selected_skpd = response.data.skpd.map(skpd => skpd.id_skpd);
                jQuery('#id_skpd').empty();

                response.data.all_skpd.forEach(function(skpd) {
                    jQuery('#id_skpd').append(
                        jQuery('<option>', {
                            value: skpd.id_skpd,
                            text: skpd.nama_skpd,
                            selected: selected_skpd.includes(skpd.id_skpd)
                        })
                    );
                });

                jQuery("#id_skpd").val(selected_skpd).trigger('change');

                jQuery('#id_data').val(response.data.id);
                jQuery("#modal-edit-renaksiLabel").show();
                jQuery('#modal-edit-renaksi').modal('show');

                jQuery('#id_skpd').select2({
                    width: '100%',
                    placeholder: 'Pilih Perangkat Daerah',
                    allowClear: true
                });
            } else {
                alert(response.message);
            }
        },
        error: function(xhr, status, error) {
            jQuery('#wrap-loading').hide();
            console.error(xhr.responseText);
            alert('Terjadi kesalahan saat memuat data!');
        }
    });
}

function submit_edit_renaksi() {
    if (confirm('Apakah anda yakin untuk menyimpan data ini?')) {
        var id_data = jQuery('#id_data').val();
        var id_skpd = jQuery('#id_skpd').val();
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: "post",
            data: {
                action: "submit_edit_renaksi_pemda",
                api_key: esakip.api_key,
                id: id_data,
                id_skpd: id_skpd
            },
            dataType: "json",
            success: function(response) {
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    jQuery('#modal-edit-renaksi').modal('hide');
                    alert(response.message);
                    getTable();
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                alert('Terjadi kesalahan saat mengirim data!');
                jQuery('#wrap-loading').hide();
            }
        });
    }
}

</script>
