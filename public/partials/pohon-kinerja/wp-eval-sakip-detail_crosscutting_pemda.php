<?php
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

$input = shortcode_atts(array(
    'tahun_anggaran' => '2024',
    'periode' => '',
), $atts);

global $wpdb;
$nama_pemda = get_option(ESAKIP_NAMA_PEMDA);
$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);

$data_all = [
    'data' => array()
];

if (!empty($_GET) && !empty($_GET['id_tujuan'])) {
    $id_tujuan = $_GET['id_tujuan'];
} else {
    die("ID Tujuan Kosong !");
}

$nama_crosscutting = $wpdb->get_var(
    $wpdb->prepare("
        SELECT 
            nama_crosscutting
        FROM esakip_rpd_tujuan
        WHERE id_unik=%s
          AND active = 1
", $id_tujuan)
);

$option_opd = '<option value="" selected disabled>Pilih Perangkat Daerah</option>';

$all_skpd = $wpdb->get_results(
    $wpdb->prepare("
        SELECT 
            nama_skpd, 
            id_skpd
        FROM esakip_data_unit 
        WHERE active=1 
          AND tahun_anggaran=%d
          AND is_skpd=1 
        ORDER BY kode_skpd ASC
    ", $tahun_anggaran_sakip),
    ARRAY_A
);
if (!empty($all_skpd)) {
    foreach ($all_skpd as $skpd) {
        $option_opd .= '<option value="' . $skpd['id_skpd'] . '">' . $skpd['nama_skpd'] . '</option>';
    }
} else {
    $option_opd .= '<option value="" selected disabled>Kosong!</option>';
}
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
if (empty($periode)) {
    die('<h1 class="text-center">Jadwal periode RPJMD/RPD tidak ditemukan!</h1>');
}

if (!empty($periode['tahun_selesai_anggaran']) && $periode['tahun_selesai_anggaran'] > 1) {
    $tahun_periode = $periode['tahun_selesai_anggaran'];
} else {
    $tahun_periode = $periode['tahun_anggaran'] + $periode['lama_pelaksanaan'];
}

// crosscutting level 1
$crosscutting_level_1 = $wpdb->get_results($wpdb->prepare("
	SELECT 
		* 
	FROM esakip_croscutting 
	WHERE parent=0 
	  AND level=1 
	  AND active=1 
	  AND id_jadwal=%d 
	  AND id_unik_tujuan=%s 
	ORDER BY id
", $input['periode'], $id_tujuan), ARRAY_A);
if (!empty($crosscutting_level_1)) {
    foreach ($crosscutting_level_1 as $level_1) {
        if (empty($data_all['data'][trim($level_1['label'])])) {
            $data_all['data'][trim($level_1['label'])] = [
                'id' => $level_1['id'],
                'label' => $level_1['label'],
                'level' => $level_1['level'],
                'indikator' => array(),
                'data' => array()
            ];
        }

        // indikator crosscutting level 1
        $indikator_crosscutting_level_1 = $wpdb->get_results($wpdb->prepare("
			SELECT 
				* 
			FROM esakip_croscutting 
			WHERE parent=%d 
			  AND level=1 
			  AND active=1 
			  AND id_jadwal=%d 
			  AND id_unik_tujuan=%s 
			ORDER BY id
		", $level_1['id'], $input['periode'], $id_tujuan), ARRAY_A);
        if (!empty($indikator_crosscutting_level_1)) {
            foreach ($indikator_crosscutting_level_1 as $indikator_level_1) {
                if (!empty($indikator_level_1['label_id_skpd'])) {
                    if (empty($data_all['data'][trim($level_1['label'])]['indikator'][(trim($indikator_level_1['label_id_skpd']))])) {
                        $data_all['data'][trim($level_1['label'])]['indikator'][(trim($indikator_level_1['label_id_skpd']))] = [
                            'id' => $indikator_level_1['id'],
                            'parent' => $indikator_level_1['parent'],
                            'label_id_skpd' => $indikator_level_1['label_id_skpd'],
                            'level' => $indikator_level_1['level']
                        ];
                    }
                }
            }
        }

        // crosscutting level 2 
        $crosscutting_level_2 = $wpdb->get_results($wpdb->prepare("
			SELECT 
				* 
			FROM esakip_croscutting 
			WHERE parent=%d 
			  AND level=2
			  AND active=1 
			  AND id_jadwal=%d 
			  AND id_unik_tujuan=%s
			ORDER by id
		", $level_1['id'], $input['periode'], $id_tujuan), ARRAY_A);
        if (!empty($crosscutting_level_2)) {
            foreach ($crosscutting_level_2 as $level_2) {
                if (empty($data_all['data'][trim($level_1['label'])]['data'][trim($level_2['label'])])) {
                    $data_all['data'][trim($level_1['label'])]['data'][trim($level_2['label'])] = [
                        'id' => $level_2['id'],
                        'label' => $level_2['label'],
                        'level' => $level_2['level'],
                        'indikator' => array(),
                        'data' => array()
                    ];
                }

                // indikator crosscutting level 2
                $indikator_crosscutting_level_2 = $wpdb->get_results($wpdb->prepare("
					SELECT 
						* 
					FROM esakip_croscutting 
					WHERE parent=%d 
					  AND level=2 
					  AND active=1 
					  AND id_jadwal=%d 
					  AND id_unik_tujuan=%s
					ORDER BY id
				", $level_2['id'], $input['periode'],$id_tujuan), ARRAY_A);
                if (!empty($indikator_crosscutting_level_2)) {
                    foreach ($indikator_crosscutting_level_2 as $indikator_level_2) {
                        if (!empty($indikator_level_2['label_id_skpd'])) {
                            if (empty($data_all['data'][trim($level_1['label'])]['data'][trim($level_2['label'])]['indikator'][(trim($indikator_level_2['label_id_skpd']))])) {
                                $data_all['data'][trim($level_1['label'])]['data'][trim($level_2['label'])]['indikator'][(trim($indikator_level_2['label_id_skpd']))] = [
                                    'id' => $indikator_level_2['id'],
                                    'parent' => $indikator_level_2['parent'],
                                    'label_id_skpd' => $indikator_level_2['label_id_skpd'],
                                    'label_nama_skpd' => $indikator_level_2['label'],
                                    'level' => $indikator_level_2['level']
                                ];
                            }
                        }
                    }
                }

                // crosscutting level 3
                $crosscutting_level_3 = $wpdb->get_results($wpdb->prepare("
					SELECT 
						* 
					FROM esakip_croscutting 
					WHERE parent=%d 
					  AND level=3 
					  AND active=1 
					  AND id_jadwal=%d 
					  AND id_unik_tujuan=%s 
					ORDER by id
				", $level_2['id'], $input['periode'], $id_tujuan), ARRAY_A);
                if (!empty($crosscutting_level_3)) {
                    foreach ($crosscutting_level_3 as $level_3) {
                        if (empty($data_all['data'][trim($level_1['label'])]['data'][trim($level_2['label'])]['data'][trim($level_3['label'])])) {
                            $data_all['data'][trim($level_1['label'])]['data'][trim($level_2['label'])]['data'][trim($level_3['label'])] = [
                                'id' => $level_3['id'],
                                'label' => $level_3['label'],
                                'level' => $level_3['level'],
                                'indikator' => array(),
                                'data' => array()
                            ];
                        }

                        // indikator crosscutting level 3
                        $indikator_crosscutting_level_3 = $wpdb->get_results($wpdb->prepare("
							SELECT 
								* 
							FROM esakip_croscutting 
							WHERE parent=%d 
							  AND level=3 
							  AND active=1 
							  AND id_jadwal=%d
							  AND id_unik_tujuan=%s
							ORDER BY id
						", $level_3['id'], $input['periode'], $id_tujuan), ARRAY_A);
                        if (!empty($indikator_crosscutting_level_3)) {
                            foreach ($indikator_crosscutting_level_3 as $indikator_level_3) {
                                if (!empty($indikator_level_3['label_id_skpd'])) {
                                    if (empty($data_all['data'][trim($level_1['label'])]['data'][trim($level_2['label'])]['data'][trim($level_3['label'])]['indikator'][(trim($indikator_level_3['label_id_skpd']))])) {
                                        $data_all['data'][trim($level_1['label'])]['data'][trim($level_2['label'])]['data'][trim($level_3['label'])]['indikator'][(trim($indikator_level_3['label_id_skpd']))] = [
                                            'id' => $indikator_level_3['id'],
                                            'parent' => $indikator_level_3['parent'],
                                            'label_id_skpd' => $indikator_level_3['label_id_skpd'],
                                            'label_nama_skpd' => $indikator_level_3['label'],
                                            'level' => $indikator_level_3['level']
                                        ];
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}


$view_crosscutting = $this->functions->generatePage(array(
    'nama_page' => 'View Crosscutting Pemerintah Daerah',
    'content' => '[view_crosscutting_pemda]',
    'show_header' => 1,
    'post_status' => 'private'
));
$html = '';
foreach ($data_all['data'] as $key1 => $level_1) {
    $indikator = array();
    $html .= '
	<tr>
		<td class="level1"><a href="' . $view_crosscutting['url'] . '&id=' . $level_1['id'] . '&id_jadwal=' . $input['periode'] . '&id_tujuan=' . $id_tujuan . '" target="_blank">' . $level_1['label'] . '</a></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
	</tr>';
    foreach (array_values($level_1['data']) as $key2 => $level_2) {
        $indikator = array();
        foreach ($level_2['indikator'] as $indikatorlevel2) {
            $indikator[] = $indikatorlevel2['label_nama_skpd'];
        }
        $html .= '
		<tr>
			<td></td>
			<td class="level2">' . $level_2['label'] . '</td>
			<td class="indikator">' . implode("<hr>", $indikator) . '</td>
			<td></td>
			<td></td>
		</tr>';
        foreach (array_values($level_2['data']) as $key3 => $level_3) {
            $indikator = array();
            foreach ($level_3['indikator'] as $indikatorlevel3) {
                $indikator[] = $indikatorlevel3['label_nama_skpd'];
            }
            $html .= '
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td class="level3">' . $level_3['label'] . '</td>
				<td class="indikator">' . implode("<hr>", $indikator) . '</td>
			</tr>';
        }
    }
}

$current_user = wp_get_current_user();
$user_roles = $current_user->roles;
$is_admin_panrb = in_array('admin_panrb', $user_roles);
?>

<style type="text/css">
    .level1 {
        background: #efd655;
        text-decoration: underline;
    }

    .level2 {
        background: #fe7373;
    }

    .level3 {
        background: #57b2ec;
    }

    .level4 {
        background: #c979e3;
    }

    .indikator {
        background: #b5d9ea;
    }

    #modal-crosscutting .modal-body {
        max-height: 70vh;
        overflow-y: auto;
    }

    .table-responsive {
        display: block;
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .table {
        width: 100%;
        max-width: 100%;
        margin-bottom: 1rem;
        background-color: transparent;
    }
</style>
<h3 style="text-align: center; margin-top: 10px; font-weight: bold;">Penyusunan Crosscutting<br><?php echo $nama_pemda; ?><br><?php echo $nama_crosscutting; ?><br><?php echo $periode['nama_jadwal'] . ' (' . $periode['tahun_anggaran'] . ' - ' . $tahun_periode . ')'; ?></h3><br>
<?php if (!$is_admin_panrb) : ?>
    <div id="action" style="text-align: center; margin-top:30px; margin-bottom: 30px;">
        <a style="margin-left: 10px;" id="tambah-crosscutting" onclick="return false;" href="#" class="btn btn-success">Tambah Data</a>
    </div>
<?php endif; ?>
<div id="cetak" title="Penyusunan Crosscutting Pemerintah Daerah" style="padding: 5px; overflow: auto; height: 100vh;">
    <table>
        <thead>
            <tr>
                <th width="20%">Level 1</th>
                <th width="20%">Level 2</th>
                <th width="20%">Perangkat Daerah</th>
                <th width="20%">Level 3</th>
                <th width="20%">Perangkat Daerah</th>
            </tr>
        </thead>
        <tbody>
            <?php echo $html; ?>
        </tbody>
    </table>

    <div class="hide-print" id="catatan_dokumentasi" style="max-width: 1200px; margin: auto;">
        <h4 style="margin: 30px 0 10px; font-weight: bold;">Catatan Dokumentasi:</h4>
        <ul>
            <li>Crosscutting bisa dilihat ketika data terisi minimal sampai dengan level ke-2.</li>
        </ul>
    </div>
</div>

<div class="modal fade" id="modal-crosscutting" role="dialog" data-backdrop="static" aria-hidden="true">'
    <div class="modal-dialog" style="max-width: 1200px;" role="document">
        <div class="modal-content">
            <div class="modal-header bgpanel-theme">
                <h4 style="margin: 0;" class="modal-title">Data Crosscutting Pemerintah Daerah</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span><i class="dashicons dashicons-dismiss"></i></span></button>
            </div>
            <div class="modal-body">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link" id="nav-level-1-tab" data-toggle="tab" href="#nav-level-1" role="tab" aria-controls="nav-level-1" aria-selected="false">Level 1</a>
                        <a class="nav-item nav-link" id="nav-level-2-tab" data-toggle="tab" href="#nav-level-2" role="tab" aria-controls="nav-level-2" aria-selected="false">Level 2</a>
                        <a class="nav-item nav-link" id="nav-level-3-tab" data-toggle="tab" href="#nav-level-3" role="tab" aria-controls="nav-level-3" aria-selected="false">Level 3</a>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-level-1" role="tabpanel" aria-labelledby="nav-level-1-tab"></div>
                    <div class="tab-pane fade" id="nav-level-2" role="tabpanel" aria-labelledby="nav-level-2-tab"></div>
                    <div class="tab-pane fade" id="nav-level-3" role="tabpanel" aria-labelledby="nav-level-3-tab"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal crud -->
<div class="modal fade" id="modal-crud" data-backdrop="static" role="dialog" aria-labelledby="modal-crud-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
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
<script type="text/javascript">
    jQuery(document).ready(function() {

        jQuery("#tambah-crosscutting").on('click', function() {
            crosscuttingLevel1().then(function() {
                jQuery("#crosscuttingLevel1").DataTable();
            });
        });

        jQuery(document).on('click', '#tambah-crosscutting-level1', function() {
            jQuery("#modal-crud").find('.modal-title').html('Tambah Crosscutting Pemerintah Daerah');
            jQuery("#modal-crud").find('.modal-body').html('' +
                '<form id="form-crosscutting">' +
                '<input type="hidden" name="parent" value="0">' +
                '<input type="hidden" name="level" value="1">' +
                '<div class="form-group">' +
                '<textarea class="form-control" name="label" placeholder="Tuliskan crosscutting level 1..."></textarea>' +
                '</div>' +
                '</form>');
            jQuery("#modal-crud").find('.modal-footer').html('' +
                '<button type="button" class="btn btn-danger" data-dismiss="modal">' +
                'Tutup' +
                '</button>' +
                '<button type="button" class="btn btn-success" id="simpan-data-crosscutting" data-action="create_crosscutting_pemda" data-view="crosscuttingLevel1">' +
                'Simpan' +
                '</button>');
            jQuery("#modal-crud").find('.modal-dialog').css('maxWidth', '');
            jQuery("#modal-crud").find('.modal-dialog').css('width', '');
            jQuery("#modal-crud").modal('show');
        })

        jQuery(document).on('click', '.edit-crosscutting-level1', function() {
            jQuery("#wrap-loading").show();
            jQuery.ajax({
                method: 'POST',
                url: esakip.url,
                data: {
                    "action": "edit_crosscutting_pemda",
                    "api_key": esakip.api_key,
                    'id': jQuery(this).data('id')
                },
                dataType: 'json',
                success: function(response) {
                    jQuery("#wrap-loading").hide();
                    jQuery("#modal-crud").find('.modal-title').html('Edit Crosscutting Pemerintah Daerah');
                    jQuery("#modal-crud").find('.modal-body').html(`` +
                        `<form id="form-crosscutting">` +
                        `<input type="hidden" name="id" value="${response.data.id}">` +
                        `<input type="hidden" name="parent" value="${response.data.parent}">` +
                        `<input type="hidden" name="level" value="${response.data.level}">` +
                        `<div class="form-group">` +
                        `<textarea class="form-control" name="label">${response.data.label}</textarea>` +
                        `</div>` +
                        `</form>`);
                    jQuery("#modal-crud").find(`.modal-footer`).html(`` +
                        `<button type="button" class="btn btn-danger" data-dismiss="modal">` +
                        `Tutup` +
                        `</button>` +
                        `<button type="button" class="btn btn-success" id="simpan-data-crosscutting" data-action="update_crosscutting_pemda" data-view="crosscuttingLevel1">` +
                        `Update` +
                        `</button>`);
                    jQuery("#modal-crud").find('.modal-dialog').css('maxWidth', '');
                    jQuery("#modal-crud").find('.modal-dialog').css('width', '');
                    jQuery("#modal-crud").modal('show');
                }
            });
        })

        jQuery(document).on('click', '.hapus-crosscutting-level1', function() {
            if (confirm(`Data akan dihapus?`)) {
                jQuery("#wrap-loading").show();
                jQuery.ajax({
                    method: 'POST',
                    url: esakip.url,
                    data: {
                        'action': 'delete_crosscutting_pemda',
                        'api_key': esakip.api_key,
                        'id': jQuery(this).data('id'),
                        'level': 1,
                    },
                    dataType: 'json',
                    success: function(response) {
                        jQuery("#wrap-loading").hide();
                        alert(response.message);
                        if (response.status) {
                            crosscuttingLevel1().then(function() {
                                jQuery("#crosscuttingLevel1").DataTable();
                            });
                        }
                    }
                })
            }
        });

        jQuery(document).on('click', '.hapus-skpd-crosscutting-level1', function() {
            if (confirm(`Data akan dihapus?`)) {
                jQuery("#wrap-loading").show();
                jQuery.ajax({
                    method: 'POST',
                    url: esakip.url,
                    data: {
                        'action': 'delete_indikator_crosscutting_pemda',
                        'api_key': esakip.api_key,
                        'id': jQuery(this).data('id')
                    },
                    dataType: 'json',
                    success: function(response) {
                        jQuery("#wrap-loading").hide();
                        alert(response.message);
                        if (response.status) {
                            crosscuttingLevel1().then(function() {
                                jQuery("#crosscuttingLevel1").DataTable();
                            });
                        }
                    }
                })
            }
        });

        jQuery(document).on('click', '.view-crosscutting-level2', function() {
            crosscuttingLevel2({
                'parent': jQuery(this).data('id')
            }).then(function() {
                jQuery("#crosscuttingLevel2").DataTable();
            });
        })

        jQuery(document).on('click', '#tambah-crosscutting-level2', function() {
            jQuery("#modal-crud").find('.modal-title').html('Tambah Crosscutting Pemerintah Daerah');
            jQuery("#modal-crud").find('.modal-body').html(`` +
                `<form id="form-crosscutting">` +
                `<input type="hidden" name="parent" value="${jQuery(this).data('parent')}">` +
                `<input type="hidden" name="level" value="2">` +
                `<div class="form-group">` +
                `<textarea class="form-control" name="label" placeholder="Tuliskan crosscutting level 2..."></textarea>` +
                `</div>` +
                `</form>`);
            jQuery("#modal-crud").find('.modal-footer').html('' +
                '<button type="button" class="btn btn-danger" data-dismiss="modal">' +
                'Tutup' +
                '</button>' +
                '<button type="button" class="btn btn-success" id="simpan-data-crosscutting" data-action="create_crosscutting_pemda" data-view="crosscuttingLevel2">' +
                'Simpan' +
                '</button>');
            jQuery("#modal-crud").find('.modal-dialog').css('maxWidth', '');
            jQuery("#modal-crud").find('.modal-dialog').css('width', '');
            jQuery("#modal-crud").modal('show');
        })

        jQuery(document).on('click', '.edit-crosscutting-level2', function() {
            jQuery("#wrap-loading").show();
            jQuery.ajax({
                method: 'POST',
                url: esakip.url,
                data: {
                    "action": "edit_crosscutting_pemda",
                    "api_key": esakip.api_key,
                    'id': jQuery(this).data('id')
                },
                dataType: 'json',
                success: function(response) {
                    jQuery("#wrap-loading").hide();
                    jQuery("#modal-crud").find('.modal-title').html('Edit Crosscutting Pemerintah Daerah');
                    jQuery("#modal-crud").find('.modal-body').html(`` +
                        `<form id="form-crosscutting">` +
                        `<input type="hidden" name="id" value="${response.data.id}">` +
                        `<input type="hidden" name="parent" value="${response.data.parent}">` +
                        `<input type="hidden" name="level" value="${response.data.level}">` +
                        `<div class="form-group">` +
                        `<textarea class="form-control" name="label">${response.data.label}</textarea>` +
                        `</div>` +
                        `</form>`);
                    jQuery("#modal-crud").find(`.modal-footer`).html(`` +
                        `<button type="button" class="btn btn-danger" data-dismiss="modal">` +
                        `Tutup` +
                        `</button>` +
                        `<button type="button" class="btn btn-success" id="simpan-data-crosscutting" data-action="update_crosscutting_pemda" data-view="crosscuttingLevel2">` +
                        `Update` +
                        `</button>`);
                    jQuery("#modal-crud").find('.modal-dialog').css('maxWidth', '');
                    jQuery("#modal-crud").find('.modal-dialog').css('width', '');
                    jQuery("#modal-crud").modal('show');
                }
            });
        })

        jQuery(document).on('click', '.hapus-crosscutting-level2', function() {
            let parent = jQuery(this).data('parent');
            if (confirm(`Data akan dihapus?`)) {
                jQuery("#wrap-loading").show();
                jQuery.ajax({
                    method: 'POST',
                    url: esakip.url,
                    data: {
                        'action': 'delete_crosscutting_pemda',
                        'api_key': esakip.api_key,
                        'id': jQuery(this).data('id'),
                        'level': 2,
                    },
                    dataType: 'json',
                    success: function(response) {
                        jQuery("#wrap-loading").hide();
                        alert(response.message);
                        if (response.status) {
                            crosscuttingLevel2({
                                'parent': parent
                            }).then(function() {
                                jQuery("#crosscuttingLevel2").DataTable();
                            });
                        }
                    }
                })
            }
        });

        jQuery(document).on('click', '.tambah-skpd-crosscutting-level2', function() {
            jQuery("#modal-crud").find('.modal-title').html('Tambah Perangkat Daerah');
            jQuery("#modal-crud").find('.modal-body').html(`` +
                `<form id="form-crosscutting">` +
                `<input type="hidden" name="parent_all" value="${jQuery(this).data('parent')}">` +
                `<input type="hidden" name="parent" value="${jQuery(this).data('id')}">` +
                `<input type="hidden" name="label" value="${jQuery(this).parent().parent().find('.label-level2').text()}">` +
                `<input type="hidden" name="level" value="2">` +
                `<div class="form-group">` +
                `<label for="indikator-label">${jQuery(this).parent().parent().find('.label-level2').text()}</label>` +
                `</div>` +
                `<select class="form-select" name="skpd-label" style="width:100%"><?php echo $option_opd; ?></select>` +
                `</form>`);
            jQuery("select[name*='skpd-label']").select2()
            jQuery("#modal-crud").find('.modal-footer').html(`` +
                `<button type="button" class="btn btn-danger" data-dismiss="modal">` +
                `Tutup` +
                `</button>` +
                `<button type="button" class="btn btn-success" id="simpan-data-crosscutting" data-action="create_indikator_crosscutting_pemda" data-view="crosscuttingLevel2">` +
                `Simpan` +
                `</button>`);
            jQuery("#modal-crud").find('.modal-dialog').css('maxWidth', '');
            jQuery("#modal-crud").find('.modal-dialog').css('width', '');
            jQuery("#modal-crud").modal('show');
        })

        jQuery(document).on('click', '.edit-skpd-crosscutting-level2', function() {
            jQuery("#wrap-loading").show();
            jQuery.ajax({
                method: 'POST',
                url: esakip.url,
                data: {
                    "action": "edit_indikator_crosscutting_pemda",
                    "api_key": esakip.api_key,
                    'id': jQuery(this).data('id')
                },
                dataType: 'json',
                success: function(response) {
                    jQuery("#wrap-loading").hide();
                    jQuery("#modal-crud").find('.modal-title').html('Edit Perangkat Daerah');
                    jQuery("#modal-crud").find('.modal-body').html(`` +
                        `<form id="form-crosscutting">` +
                        `<input type="hidden" name="id" value="${response.data.id}">` +
                        `<input type="hidden" name="parent_all" value="${response.data.parent_all}">` +
                        `<input type="hidden" name="parent" value="${response.data.parent}">` +
                        `<input type="hidden" name="level" value="${response.data.level}">` +
                        `<div class="form-group">` +
                        `<label for="indikator-label">${response.data.label}</label>` +
                        `</div>` +
                        `<select class="form-select" name="skpd-label" style="width:100%"><?php echo $option_opd; ?></select>` +
                        `</form>`);
                    jQuery("select[name*='skpd-label']").select2({
                        tags: true,
                        tokenSeparators: [',', ' ']
                    })

                    jQuery("#modal-crud").find(`.modal-footer`).html(`` +
                        `<button type="button" class="btn btn-danger" data-dismiss="modal">` +
                        `Tutup` +
                        `</button>` +
                        `<button type="button" class="btn btn-success" id="simpan-data-crosscutting" data-action="update_indikator_crosscutting_pemda" data-view="crosscuttingLevel2">` +
                        `Update` +
                        `</button>`);
                    jQuery("#modal-crud").find('.modal-dialog').css('maxWidth', '');
                    jQuery("#modal-crud").find('.modal-dialog').css('width', '');
                    jQuery("#modal-crud").modal('show');
                }
            });
        })

        jQuery(document).on('click', '.hapus-skpd-crosscutting-level2', function() {
            let parent = jQuery(this).data('parent');
            if (confirm(`Data akan dihapus?`)) {
                jQuery("#wrap-loading").show();
                jQuery.ajax({
                    method: 'POST',
                    url: esakip.url,
                    data: {
                        'action': 'delete_indikator_crosscutting_pemda',
                        'api_key': esakip.api_key,
                        'id': jQuery(this).data('id')
                    },
                    dataType: 'json',
                    success: function(response) {
                        jQuery("#wrap-loading").hide();
                        alert(response.message);
                        if (response.status) {
                            crosscuttingLevel2({
                                'parent': parent
                            }).then(function() {
                                jQuery("#crosscuttingLevel2").DataTable();
                            });
                        }
                    }
                })
            }
        });

        jQuery(document).on('click', '.view-crosscutting-level3', function() {
            crosscuttingLevel3({
                'parent': jQuery(this).data('id')
            }).then(function() {
                jQuery("#crosscuttingLevel3").DataTable();
            });
        })

        jQuery(document).on('click', '#tambah-crosscutting-level3', function() {
            jQuery("#modal-crud").find('.modal-title').html('Tambah Crosscutting Pemerintah Daerah');
            jQuery("#modal-crud").find('.modal-body').html(`` +
                `<form id="form-crosscutting">` +
                `<input type="hidden" name="parent" value="${jQuery(this).data('parent')}">` +
                `<input type="hidden" name="level" value="3">` +
                `<div class="form-group">` +
                `<textarea class="form-control" name="label" placeholder="Tuliskan crosscutting level 3..."></textarea>` +
                `</div>` +
                `</form>`);
            jQuery("#modal-crud").find('.modal-footer').html('' +
                '<button type="button" class="btn btn-danger" data-dismiss="modal">' +
                'Tutup' +
                '</button>' +
                '<button type="button" class="btn btn-success" id="simpan-data-crosscutting" data-action="create_crosscutting_pemda" data-view="crosscuttingLevel3">' +
                'Simpan' +
                '</button>');
            jQuery("#modal-crud").find('.modal-dialog').css('maxWidth', '');
            jQuery("#modal-crud").find('.modal-dialog').css('width', '');
            jQuery("#modal-crud").modal('show');
        })

        jQuery(document).on('click', '.edit-crosscutting-level3', function() {
            jQuery("#wrap-loading").show();
            jQuery.ajax({
                method: 'POST',
                url: esakip.url,
                data: {
                    "action": "edit_crosscutting_pemda",
                    "api_key": esakip.api_key,
                    'id': jQuery(this).data('id')
                },
                dataType: 'json',
                success: function(response) {
                    jQuery("#wrap-loading").hide();
                    jQuery("#modal-crud").find('.modal-title').html('Edit Crosscutting Pemerintah Daerah');
                    jQuery("#modal-crud").find('.modal-body').html(`` +
                        `<form id="form-crosscutting">` +
                        `<input type="hidden" name="id" value="${response.data.id}">` +
                        `<input type="hidden" name="parent" value="${response.data.parent}">` +
                        `<input type="hidden" name="level" value="${response.data.level}">` +
                        `<div class="form-group">` +
                        `<textarea class="form-control" name="label">${response.data.label}</textarea>` +
                        `</div>` +
                        `</form>`);
                    jQuery("#modal-crud").find(`.modal-footer`).html(`` +
                        `<button type="button" class="btn btn-danger" data-dismiss="modal">` +
                        `Tutup` +
                        `</button>` +
                        `<button type="button" class="btn btn-success" id="simpan-data-crosscutting" data-action="update_crosscutting_pemda" data-view="crosscuttingLevel3">` +
                        `Update` +
                        `</button>`);
                    jQuery("#modal-crud").find('.modal-dialog').css('maxWidth', '');
                    jQuery("#modal-crud").find('.modal-dialog').css('width', '');
                    jQuery("#modal-crud").modal('show');
                }
            });
        })

        jQuery(document).on('click', '.hapus-crosscutting-level3', function() {
            let parent = jQuery(this).data('parent');
            if (confirm(`Data akan dihapus?`)) {
                jQuery("#wrap-loading").show();
                jQuery.ajax({
                    method: 'POST',
                    url: esakip.url,
                    data: {
                        'action': 'delete_crosscutting_pemda',
                        'api_key': esakip.api_key,
                        'id': jQuery(this).data('id'),
                        'level': 3,
                    },
                    dataType: 'json',
                    success: function(response) {
                        jQuery("#wrap-loading").hide();
                        alert(response.message);
                        if (response.status) {
                            crosscuttingLevel3({
                                'parent': parent
                            }).then(function() {
                                jQuery("#crosscuttingLevel3").DataTable();
                            });
                        }
                    }
                })
            }
        });

        jQuery(document).on('click', '.tambah-skpd-crosscutting-level3', function() {
            jQuery("#modal-crud").find('.modal-title').html('Tambah Perangkat Daerah');
            jQuery("#modal-crud").find('.modal-body').html(`` +
                `<form id="form-crosscutting">` +
                `<input type="hidden" name="parent_all" value="${jQuery(this).data('parent')}">` +
                `<input type="hidden" name="parent" value="${jQuery(this).data('id')}">` +
                `<input type="hidden" name="label" value="${jQuery(this).parent().parent().find('.label-level3').text()}">` +
                `<input type="hidden" name="level" value="3">` +
                `<div class="form-group">` +
                `<label for="indikator-label">${jQuery(this).parent().parent().find('.label-level3').text()}</label>` +
                `<select class="form-select" name="skpd-label" style="width:100%"><?php echo $option_opd; ?></select>` +
                `</div>` +
                `</form>`);
            jQuery("select[name*='skpd-label']").select2()
            jQuery("#modal-crud").find('.modal-footer').html(`` +
                `<button type="button" class="btn btn-danger" data-dismiss="modal">` +
                `Tutup` +
                `</button>` +
                `<button type="button" class="btn btn-success" id="simpan-data-crosscutting" data-action="create_indikator_crosscutting_pemda" data-view="crosscuttingLevel3">` +
                `Simpan` +
                `</button>`);
            jQuery("#modal-crud").find('.modal-dialog').css('maxWidth', '');
            jQuery("#modal-crud").find('.modal-dialog').css('width', '');
            jQuery("#modal-crud").modal('show');
        })

        jQuery(document).on('click', '.edit-skpd-crosscutting-level3', function() {
            jQuery("#wrap-loading").show();
            jQuery.ajax({
                method: 'POST',
                url: esakip.url,
                data: {
                    "action": "edit_indikator_crosscutting_pemda",
                    "api_key": esakip.api_key,
                    'id': jQuery(this).data('id')
                },
                dataType: 'json',
                success: function(response) {
                    jQuery("#wrap-loading").hide();
                    jQuery("#modal-crud").find('.modal-title').html('Edit Perangkat Daerah');
                    jQuery("#modal-crud").find('.modal-body').html(`` +
                        `<form id="form-crosscutting">` +
                        `<input type="hidden" name="id" value="${response.data.id}">` +
                        `<input type="hidden" name="parent_all" value="${response.data.parent_all}">` +
                        `<input type="hidden" name="parent" value="${response.data.parent}">` +
                        `<input type="hidden" name="level" value="${response.data.level}">` +
                        `<div class="form-group">` +
                        `<label for="indikator-label">${response.data.label}</label>` +
                        `<select class="form-select" name="skpd-label" style="width:100%"><?php echo $option_opd; ?></select>` +
                        `</div>` +
                        `</form>`);
                    jQuery("select[name*='skpd-label']").select2({
                        tags: true,
                        tokenSeparators: [',', ' ']
                    })
                    jQuery("#modal-crud").find(`.modal-footer`).html(`` +
                        `<button type="button" class="btn btn-danger" data-dismiss="modal">` +
                        `Tutup` +
                        `</button>` +
                        `<button type="button" class="btn btn-success" id="simpan-data-crosscutting" data-action="update_indikator_crosscutting_pemda" data-view="crosscuttingLevel3">` +
                        `Update` +
                        `</button>`);
                    jQuery("#modal-crud").find('.modal-dialog').css('maxWidth', '');
                    jQuery("#modal-crud").find('.modal-dialog').css('width', '');
                    jQuery("#modal-crud").modal('show');
                }
            });
        })

        jQuery(document).on('click', '.hapus-skpd-crosscutting-level3', function() {
            let parent = jQuery(this).data('parent');
            if (confirm(`Data akan dihapus?`)) {
                jQuery("#wrap-loading").show();
                jQuery.ajax({
                    method: 'POST',
                    url: esakip.url,
                    data: {
                        'action': 'delete_indikator_crosscutting_pemda',
                        'api_key': esakip.api_key,
                        'id': jQuery(this).data('id')
                    },
                    dataType: 'json',
                    success: function(response) {
                        jQuery("#wrap-loading").hide();
                        alert(response.message);
                        if (response.status) {
                            crosscuttingLevel3({
                                'parent': parent
                            }).then(function() {
                                jQuery("#crosscuttingLevel3").DataTable();
                            });
                        }
                    }
                })
            }
        });

        jQuery(document).on('click', '#simpan-data-crosscutting', function() {
            jQuery('#wrap-loading').show();
            let modal = jQuery("#modal-crud");
            let action = jQuery(this).data('action');
            let view = jQuery(this).data('view');
            let form = getFormData(jQuery("#form-crosscutting"));
            form['id_jadwal'] = '<?php echo $input['periode']; ?>';
            form['id_tujuan'] = '<?php echo $id_tujuan; ?>';

            jQuery.ajax({
                method: 'POST',
                url: esakip.url,
                dataType: 'json',
                data: {
                    'action': action,
                    'api_key': esakip.api_key,
                    'data': JSON.stringify(form),
                },
                success: function(response) {
                    jQuery('#wrap-loading').hide();
                    alert(response.message);
                    if (response.status) {
                        runFunction(view, [form])
                        modal.modal('hide');
                    }
                }
            })
        });
    });

    function crosscuttingLevel1() {
        jQuery("#wrap-loading").show();
        return new Promise(function(resolve, reject) {
            jQuery.ajax({
                url: esakip.url,
                type: "post",
                data: {
                    "action": "get_data_crosscutting_pemda",
                    "level": 1,
                    "parent": 0,
                    "id_jadwal": '<?php echo $input['periode']; ?>',
                    "id_tujuan": '<?php echo $id_tujuan; ?>',
                    "api_key": esakip.api_key
                },
                dataType: "json",
                success: function(res) {
                    jQuery('#wrap-loading').hide();
                    let level1 = `` +
                        `<div style="margin-top:10px">` +
                        `<button type="button" class="btn btn-success mb-2" id="tambah-crosscutting-level1"><i class="dashicons dashicons-plus" style="margin-top: 2px;"></i>Tambah Data</button>` +
                        `</div>` +
                        `<table class="table" id="crosscuttingLevel1">` +
                        `<thead>` +
                        `<tr>` +
                        `<th class="text-center" style="width:20%">No</th>` +
                        `<th class="text-center" style="width:60%">Label Crosscutting Pemerintah Daerah</th>` +
                        `<th class="text-center" style="width:20%">Aksi</th>` +
                        `</tr>` +
                        `</thead>` +
                        `<tbody>`;
                    res.data.map(function(value, index) {
                        level1 += `` +
                            `<tr id="crosscuttingLevel1_${value.id}">` +
                            `<td class="text-center">${index+1}.</td>` +
                            `<td class="label-level1">${value.label}</td>` +
                            `<td class="text-center">` +
                            `<a href="javascript:void(0)" data-id="${value.id}" class="btn btn-sm btn-warning view-crosscutting-level2" title="Lihat crosscutting level 2"><i class="dashicons dashicons dashicons-menu-alt"></i></a> ` +
                            `<a href="javascript:void(0)" data-id="${value.id}" class="btn btn-sm btn-primary edit-crosscutting-level1" title="Edit"><i class="dashicons dashicons-edit"></i></a>&nbsp;` +
                            `<a href="javascript:void(0)" data-id="${value.id}" class="btn btn-sm btn-danger hapus-crosscutting-level1" title="Hapus"><i class="dashicons dashicons-trash"></i></a>` +
                            `</td>` +
                            `</tr>`;

                        let indikator = Object.values(value.indikator);
                        if (indikator.length > 0) {
                            indikator.map(function(indikator_value, indikator_index) {
                                level1 += `` +
                                    `<tr>` +
                                    `<td><span style="display:none">${index+1}</span></td>` +
                                    `<td>${index+1}.${indikator_index+1} ${indikator_value.label}</td>` +
                                    `<td class="text-center">` +
                                    `<a href="javascript:void(0)" data-id="${indikator_value.id}" class="btn btn-sm btn-primary edit-skpd-crosscutting-level1" title="Edit"><i class="dashicons dashicons-edit"></i></a> ` +
                                    `<a href="javascript:void(0)" data-id="${indikator_value.id}" class="btn btn-sm btn-danger hapus-skpd-crosscutting-level1" title="Hapus"><i class="dashicons dashicons-trash"></i></a>` +
                                    `</td>` +
                                    `</tr>`;
                            });
                        }
                    });
                    level1 += `<tbody>` +
                        `</table>`;

                    jQuery("#nav-level-1").html(level1);
                    jQuery('.nav-tabs a[href="#nav-level-1"]').tab('show');
                    jQuery('#modal-crosscutting').modal('show');
                    resolve();
                }
            });
        });
    }

    function crosscuttingLevel2(params) {
        jQuery("#wrap-loading").show();
        let parent = params.parent_all ?? params.parent;
        return new Promise(function(resolve, reject) {
            jQuery.ajax({
                url: esakip.url,
                type: "post",
                data: {
                    "action": "get_data_crosscutting_pemda",
                    "level": 2,
                    "parent": parent,
                    "id_jadwal": '<?php echo $input['periode']; ?>',
                    "id_tujuan": '<?php echo $id_tujuan; ?>',
                    "api_key": esakip.api_key
                },
                dataType: "json",
                success: function(res) {
                    jQuery('#wrap-loading').hide();
                    let level2 = `` +
                        `<div style="margin-top:10px">` +
                        `<button type="button" data-parent="${parent}" class="btn btn-success mb-2" id="tambah-crosscutting-level2"><i class="dashicons dashicons-plus" style="margin-top: 2px;"></i>Tambah Data</button>` +
                        `</div>` +
                        `<table class="table">` +
                        `<thead>`;
                    res.parent.map(function(value, index) {
                        if (value != null) {
                            level2 += `` +
                                `<tr>` +
                                `<th class="text-center" style="width: 160px;">Level ${(index+1)}</th>` +
                                `<th>${value}</th>` +
                                `</tr>`;
                        }
                    });
                    level2 += `</thead>` +
                        `</table>` +
                        `<table class="table" id="crosscuttingLevel2">` +
                        `<thead>` +
                        `<tr>` +
                        `<th class="text-center" style="width:20%">No</th>` +
                        `<th class="text-center" style="width:60%">Label Crosscutting Pemerintah Daerah</th>` +
                        `<th class="text-center" style="width:20%">Aksi</th>` +
                        `</tr>` +
                        `</thead>` +
                        `<tbody>`;
                    res.data.map(function(value, index) {
                        level2 += `` +
                            `<tr>` +
                            `<td class="text-center">${index+1}.</td>` +
                            `<td class="label-level2">${value.label}</td>` +
                            `<td class="text-center">` +
                            `<a href="javascript:void(0)" data-id="${value.id}" data-parent="${value.parent}" class="btn btn-sm btn-success tambah-skpd-crosscutting-level2" title="Tambah Perangkat Daerah"><i class="dashicons dashicons-plus"></i></a> ` +
                            `<a href="javascript:void(0)" data-id="${value.id}" class="btn btn-sm btn-warning view-crosscutting-level3" title="Lihat crosscutting level 3"><i class="dashicons dashicons dashicons-menu-alt"></i></a> ` +
                            `<a href="javascript:void(0)" data-id="${value.id}" data-parent="${value.parent}" class="btn btn-sm btn-primary edit-crosscutting-level2" title="Edit"><i class="dashicons dashicons-edit"></i></a>&nbsp;` +
                            `<a href="javascript:void(0)" data-id="${value.id}" data-parent="${value.parent}" class="btn btn-sm btn-danger hapus-crosscutting-level2" title="Hapus"><i class="dashicons dashicons-trash"></i></a>` +
                            `</td>` +
                            `</tr>`;

                        let indikator = Object.values(value.indikator);
                        if (indikator.length > 0) {
                            indikator.map(function(indikator_value, indikator_index) {
                                level2 += `` +
                                    `<tr>` +
                                    `<td><span style="display:none">${index+1}</span></td>` +
                                    `<td>${index+1}.${indikator_index+1} ${indikator_value.label_nama}</td>` +
                                    `<td class="text-center">` +
                                    `<a href="javascript:void(0)" data-id="${indikator_value.id}" class="btn btn-sm btn-primary edit-skpd-crosscutting-level2" title="Edit"><i class="dashicons dashicons-edit"></i></a> ` +
                                    `<a href="javascript:void(0)" data-id="${indikator_value.id}" data-parent="${value.parent}" class="btn btn-sm btn-danger hapus-skpd-crosscutting-level2" title="Hapus"><i class="dashicons dashicons-trash"></i></a>` +
                                    `</td>` +
                                    `</tr>`;
                            });
                        }
                    });
                    level2 += `<tbody>` +
                        `</table>`;

                    jQuery("#nav-level-2").html(level2);
                    jQuery('.nav-tabs a[href="#nav-level-2"]').tab('show');
                    jQuery('#modal-crosscutting').modal('show');
                    resolve();
                }
            });
        });
    }

    function crosscuttingLevel3(params) {
        jQuery("#wrap-loading").show();
        let parent = params.parent_all ?? params.parent;
        return new Promise(function(resolve, reject) {
            jQuery.ajax({
                url: esakip.url,
                type: "post",
                data: {
                    "action": "get_data_crosscutting_pemda",
                    "level": 3,
                    "parent": parent,
                    "id_jadwal": '<?php echo $input['periode']; ?>',
                    "id_tujuan": '<?php echo $id_tujuan; ?>',
                    "api_key": esakip.api_key
                },
                dataType: "json",
                success: function(res) {
                    jQuery('#wrap-loading').hide();
                    let level3 = `` +
                        `<div style="margin-top:10px">` +
                        `<button type="button" data-parent="${parent}" class="btn btn-success mb-2" id="tambah-crosscutting-level3"><i class="dashicons dashicons-plus" style="margin-top: 2px;"></i>Tambah Data</button>` +
                        `</div>` +
                        `<table class="table">` +
                        `<thead>`;
                    res.parent.map(function(value, index) {
                        if (value != null) {
                            level3 += `` +
                                `<tr>` +
                                `<th class="text-center" style="width: 160px;">Level ${(index+1)}</th>` +
                                `<th>${value}</th>` +
                                `</tr>`;
                        }
                    });
                    level3 += `</thead>` +
                        `</table>` +
                        `<table class="table" id="crosscuttingLevel3">` +
                        `<thead>` +
                        `<tr>` +
                        `<th class="text-center" style="width:20%">No</th>` +
                        `<th class="text-center" style="width:60%">Label Crosscutting Pemerintah Daerah</th>` +
                        `<th class="text-center" style="width:20%">Aksi</th>` +
                        `</tr>` +
                        `</thead>` +
                        `<tbody>`;
                    res.data.map(function(value, index) {
                        level3 += `` +
                            `<tr>` +
                            `<td class="text-center">${index+1}.</td>` +
                            `<td class="label-level3">${value.label}</td>` +
                            `<td class="text-center">` +
                            `<a href="javascript:void(0)" data-id="${value.id}" data-parent="${value.parent}" class="btn btn-sm btn-success tambah-skpd-crosscutting-level3" title="Tambah Perangkat Daerah"><i class="dashicons dashicons-plus"></i></a> ` +
                            `<a href="javascript:void(0)" data-id="${value.id}" data-parent="${value.parent}" class="btn btn-sm btn-primary edit-crosscutting-level3" title="Edit"><i class="dashicons dashicons-edit"></i></a>&nbsp;` +
                            `<a href="javascript:void(0)" data-id="${value.id}" data-parent="${value.parent}" class="btn btn-sm btn-danger hapus-crosscutting-level3" title="Hapus"><i class="dashicons dashicons-trash"></i></a>` +
                            `</td>` +
                            `</tr>`;

                        let indikator = Object.values(value.indikator);
                        if (indikator.length > 0) {
                            indikator.map(function(indikator_value, indikator_index) {
                                level3 += `` +
                                    `<tr>` +
                                    `<td><span style="display:none">${index+1}</span></td>` +
                                    `<td>${index+1}.${indikator_index+1} ${indikator_value.label_nama}</td>` +
                                    `<td class="text-center">` +
                                    `<a href="javascript:void(0)" data-id="${indikator_value.id}" class="btn btn-sm btn-primary edit-skpd-crosscutting-level3" title="Edit"><i class="dashicons dashicons-edit"></i></a> ` +
                                    `<a href="javascript:void(0)" data-id="${indikator_value.id}" data-parent="${value.parent}" class="btn btn-sm btn-danger hapus-skpd-crosscutting-level3" title="Hapus"><i class="dashicons dashicons-trash"></i></a>` +
                                    `</td>` +
                                    `</tr>`;
                            });
                        }
                    });
                    level3 += `<tbody>` +
                        `</table>`;

                    jQuery("#nav-level-3").html(level3);
                    jQuery('.nav-tabs a[href="#nav-level-3"]').tab('show');
                    jQuery('#modal-crosscutting').modal('show');
                    resolve();
                }
            });
        });
    }

    function runFunction(name, arguments) {
        var fn = window[name];
        if (typeof fn !== 'function')
            return;

        var run = fn.apply(window, arguments);
        run.then(function() {
            jQuery("#" + name).DataTable();
        });
    }
</script>