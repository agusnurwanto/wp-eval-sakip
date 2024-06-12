<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
  die;
}

$input = shortcode_atts(array(
    'tahun_anggaran' => '2024',
	'periode' => '',
), $atts);

global $wpdb;
$data_all = [
	'data' => array()
];

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
if(empty($periode)){
	die('<h1 class="text-center">Jadwal periode RPJMD/RPD tidak ditemukan!</h1>');
}

if(!empty($periode['tahun_selesai_anggaran']) && $periode['tahun_selesai_anggaran'] > 1){
    $tahun_periode = $periode['tahun_selesai_anggaran'];
}else{
    $tahun_periode = $periode['tahun_anggaran'] + $periode['lama_pelaksanaan'];
}

// pokin level 1
$pohon_kinerja_level_1 = $wpdb->get_results($wpdb->prepare("
	SELECT 
		* 
	FROM esakip_pohon_kinerja 
	WHERE parent=0 
		AND level=1 
		AND active=1 
		AND id_jadwal=%d 
	ORDER BY id
", $input['periode']), ARRAY_A);
if(!empty($pohon_kinerja_level_1)){
	foreach ($pohon_kinerja_level_1 as $level_1) {
		if(empty($data_all['data'][trim($level_1['label'])])){
			$data_all['data'][trim($level_1['label'])] = [
				'id' => $level_1['id'],
				'label' => $level_1['label'],
				'level' => $level_1['level'],
				'indikator' => array(),
				'data' => array()
			];
		}

		// indikator pokin level 1
		$indikator_pohon_kinerja_level_1 = $wpdb->get_results($wpdb->prepare("
			SELECT 
				* 
			FROM esakip_pohon_kinerja 
			WHERE parent=%d 
				AND level=1 
				AND active=1 
				AND id_jadwal=%d 
			ORDER BY id
		", $level_1['id'], $input['periode']), ARRAY_A);
		if(!empty($indikator_pohon_kinerja_level_1)){
			foreach ($indikator_pohon_kinerja_level_1 as $indikator_level_1) {
				if(!empty($indikator_level_1['label_indikator_kinerja'])){
					if(empty($data_all['data'][trim($level_1['label'])]['indikator'][(trim($indikator_level_1['label_indikator_kinerja']))])){
						$data_all['data'][trim($level_1['label'])]['indikator'][(trim($indikator_level_1['label_indikator_kinerja']))] = [
							'id' => $indikator_level_1['id'],
							'parent' => $indikator_level_1['parent'],
							'label_indikator_kinerja' => $indikator_level_1['label_indikator_kinerja'],
							'level' => $indikator_level_1['level']
						];
					}
				}
			}
		}

		// pokin level 2 
		$pohon_kinerja_level_2 = $wpdb->get_results($wpdb->prepare("
			SELECT 
				* 
			FROM esakip_pohon_kinerja 
			WHERE parent=%d 
				AND level=2
				AND active=1 
				AND id_jadwal=%d 
			ORDER by id
		", $level_1['id'], $input['periode']), ARRAY_A);
		if(!empty($pohon_kinerja_level_2)){
			foreach ($pohon_kinerja_level_2 as $level_2) {
				if(empty($data_all['data'][trim($level_1['label'])]['data'][trim($level_2['label'])])){
					$data_all['data'][trim($level_1['label'])]['data'][trim($level_2['label'])] = [
						'id' => $level_2['id'],
						'label' => $level_2['label'],
						'level' => $level_2['level'],
						'indikator' => array(),
						'data' => array()
					];
				}

				// indikator pokin level 2
				$indikator_pohon_kinerja_level_2 = $wpdb->get_results($wpdb->prepare("
					SELECT 
						* 
					FROM esakip_pohon_kinerja 
					WHERE parent=%d 
						AND level=2 
						AND active=1 
						AND id_jadwal=%d 
					ORDER BY id
				", $level_2['id'], $input['periode']), ARRAY_A);
				if(!empty($indikator_pohon_kinerja_level_2)){
					foreach ($indikator_pohon_kinerja_level_2 as $indikator_level_2) {
						if(!empty($indikator_level_2['label_indikator_kinerja'])){
							if(empty($data_all['data'][trim($level_1['label'])]['data'][trim($level_2['label'])]['indikator'][(trim($indikator_level_2['label_indikator_kinerja']))])){
								$data_all['data'][trim($level_1['label'])]['data'][trim($level_2['label'])]['indikator'][(trim($indikator_level_2['label_indikator_kinerja']))] = [
									'id' => $indikator_level_2['id'],
									'parent' => $indikator_level_2['parent'],
									'label_indikator_kinerja' => $indikator_level_2['label_indikator_kinerja'],
									'level' => $indikator_level_2['level']
								];
							}
						}
					}
				}

				// pokin level 3
				$pohon_kinerja_level_3 = $wpdb->get_results($wpdb->prepare("
					SELECT 
						* 
					FROM esakip_pohon_kinerja 
					WHERE parent=%d 
						AND level=3 
						AND active=1 
						AND id_jadwal=%d 
					ORDER by id
				", $level_2['id'], $input['periode']), ARRAY_A);
				if(!empty($pohon_kinerja_level_3)){
					foreach ($pohon_kinerja_level_3 as $level_3) {
						if(empty($data_all['data'][trim($level_1['label'])]['data'][trim($level_2['label'])]['data'][trim($level_3['label'])])){
							$data_all['data'][trim($level_1['label'])]['data'][trim($level_2['label'])]['data'][trim($level_3['label'])] = [
								'id' => $level_3['id'],
								'label' => $level_3['label'],
								'level' => $level_3['level'],
								'indikator' => array(),
								'data' => array()
							];
						}

						// indikator pokin level 3
						$indikator_pohon_kinerja_level_3 = $wpdb->get_results($wpdb->prepare("
							SELECT 
								* 
							FROM esakip_pohon_kinerja 
							WHERE parent=%d 
								AND level=3 
								AND active=1 
								AND id_jadwal=%d
							ORDER BY id
						", $level_3['id'], $input['periode']), ARRAY_A);
						if(!empty($indikator_pohon_kinerja_level_3)){
							foreach ($indikator_pohon_kinerja_level_3 as $indikator_level_3) {
								if(!empty($indikator_level_3['label_indikator_kinerja'])){
									if(empty($data_all['data'][trim($level_1['label'])]['data'][trim($level_2['label'])]['data'][trim($level_3['label'])]['indikator'][(trim($indikator_level_3['label_indikator_kinerja']))])){
										$data_all['data'][trim($level_1['label'])]['data'][trim($level_2['label'])]['data'][trim($level_3['label'])]['indikator'][(trim($indikator_level_3['label_indikator_kinerja']))] = [
											'id' => $indikator_level_3['id'],
											'parent' => $indikator_level_3['parent'],
											'label_indikator_kinerja' => $indikator_level_3['label_indikator_kinerja'],
											'level' => $indikator_level_3['level']
										];
									}
								}
							}
						}

						// pokin level 4
						$pohon_kinerja_level_4 = $wpdb->get_results($wpdb->prepare("
							SELECT 
								* 
							FROM esakip_pohon_kinerja 
							WHERE parent=%d 
								AND level=4
								AND active=1 
								AND id_jadwal=%d
							ORDER by id
						", $level_3['id'], $input['periode']), ARRAY_A);
						if(!empty($pohon_kinerja_level_4)){
							foreach ($pohon_kinerja_level_4 as $level_4) {
								if(empty($data_all['data'][trim($level_1['label'])]['data'][trim($level_2['label'])]['data'][trim($level_3['label'])]['data'][trim($level_4['label'])])){
									$data_all['data'][trim($level_1['label'])]['data'][trim($level_2['label'])]['data'][trim($level_3['label'])]['data'][trim($level_4['label'])] = [
										'id' => $level_4['id'],
										'label' => $level_4['label'],
										'level' => $level_4['level'],
										'indikator' => array()
									];
								}

								// indikator pokin level 4
								$indikator_pohon_kinerja_level_4 = $wpdb->get_results($wpdb->prepare("
									SELECT 
										* 
									FROM esakip_pohon_kinerja 
									WHERE parent=%d 
										AND level=4 
										AND active=1 
										AND id_jadwal=%d
									ORDER BY id
								", $level_4['id'], $input['periode']), ARRAY_A);
								if(!empty($indikator_pohon_kinerja_level_4)){
									foreach ($indikator_pohon_kinerja_level_4 as $indikator_level_4) {
										if(!empty($indikator_level_4['label_indikator_kinerja'])){
											if(empty($data_all['data'][trim($level_1['label'])]['data'][trim($level_2['label'])]['data'][trim($level_3['label'])]['data'][trim($level_4['label'])]['indikator'][(trim($indikator_level_4['label_indikator_kinerja']))])){
												$data_all['data'][trim($level_1['label'])]['data'][trim($level_2['label'])]['data'][trim($level_3['label'])]['data'][trim($level_4['label'])]['indikator'][(trim($indikator_level_4['label_indikator_kinerja']))] = [
													'id' => $indikator_level_4['id'],
													'parent' => $indikator_level_4['parent'],
													'label_indikator_kinerja' => $indikator_level_4['label_indikator_kinerja'],
													'level' => $indikator_level_4['level']
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
	}
}

// echo '<pre>'; print_r(array_values($data_all['data'])); echo '</pre>';die();

$view_kinerja = $this->functions->generatePage(array(
	'nama_page' => 'View Pohon Kinerja',
	'content' => '[view_pohon_kinerja]',
	'show_header' => 1,
	'post_status' => 'private'
));
$html = '';
foreach ($data_all['data'] as $key1 => $level_1) {
	$indikator=array();
	foreach ($level_1['indikator'] as $indikatorlevel1) {
		$indikator[]=$indikatorlevel1['label_indikator_kinerja'];
	}
	$html.='
	<tr>
		<td class="level1"><a href="'.$view_kinerja['url'].'&id='.$level_1['id'].'&id_jadwal='.$input['periode'].'" target="_blank">'.$level_1['label'].'</a></td>
		<td class="indikator">'.implode("</br>", $indikator).'</td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
	</tr>';
	foreach (array_values($level_1['data']) as $key2 => $level_2) {
		$indikator=array();
		foreach ($level_2['indikator'] as $indikatorlevel2) {
			$indikator[]=$indikatorlevel2['label_indikator_kinerja'];
		}
		$html.='
		<tr>
			<td></td>
			<td></td>
			<td class="level2">'.$level_2['label'].'</td>
			<td class="indikator">'.implode("</br>", $indikator).'</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>';
		foreach (array_values($level_2['data']) as $key3 => $level_3) {
			$indikator=array();
			foreach ($level_3['indikator'] as $indikatorlevel3) {
				$indikator[]=$indikatorlevel3['label_indikator_kinerja'];
			}
			$html.='
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td class="level3">'.$level_3['label'].'</td>
				<td class="indikator">'.implode("</br>", $indikator).'</td>
				<td></td>
				<td></td>
			</tr>';
			foreach (array_values($level_3['data']) as $key4 => $level_4) {
				$indikator=array();
				foreach ($level_4['indikator'] as $indikatorlevel4) {
					$indikator[]=$indikatorlevel4['label_indikator_kinerja'];
				}
				$html.='
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td class="level4">'.$level_4['label'].'</td>
					<td class="indikator">'.implode("</br>", $indikator).'</td>
				</tr>';
			}
		}
	}
}
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
</style>
<h3 style="text-align: center; margin-top: 10px; font-weight: bold;">Penyusunan Pohon Kinerja<br><?php echo $periode['nama_jadwal'] . ' (' . $periode['tahun_anggaran'] . ' - ' . $tahun_periode . ')'; ?></h3><br>
<div id="action" style="text-align: center; margin-top:30px; margin-bottom: 30px;">
		<a style="margin-left: 10px;" id="tambah-pohon-kinerja" onclick="return false;" href="#" class="btn btn-success">Tambah Data</a>
</div>
<div id="cetak" title="Penyusunan Pohon Kinerja" style="padding: 5px; overflow: auto; height: 100vh;">
	<table>
		<thead>
			<tr>
				<th>Level 1</th>
				<th>Indikator Kinerja</th>
				<th>Level 2</th>
				<th>Indikator Kinerja</th>
				<th>Level 3</th>
				<th>Indikator Kinerja</th>
				<th>Level 4</th>
				<th>Indikator Kinerja</th>
			</tr>
		</thead>
		<tbody>
			<?php echo $html; ?>
		</tbody>
	</table>
	
	<div class="hide-print" id="catatan_dokumentasi" style="max-width: 1200px; margin: auto;">
		<h4 style="margin: 30px 0 10px; font-weight: bold;">Catatan Dokumentasi:</h4>
		<ul>
			<li>Pohon kinerja bisa dilihat ketika data terisi minimal sampai dengan level ke-2.</li>
		</ul>
	</div>
</div>

<div class="modal fade" id="modal-pokin" role="dialog" data-backdrop="static" aria-hidden="true">'
    <div class="modal-dialog" style="max-width: 1200px;" role="document">
        <div class="modal-content">
            <div class="modal-header bgpanel-theme">
                <h4 style="margin: 0;" class="modal-title">Data Pohon Kinerja</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span><i class="dashicons dashicons-dismiss"></i></span></button>
            </div>
            <div class="modal-body">
            	<nav>
				  	<div class="nav nav-tabs" id="nav-tab" role="tablist">
					    <a class="nav-item nav-link" id="nav-level-1-tab" data-toggle="tab" href="#nav-level-1" role="tab" aria-controls="nav-level-1" aria-selected="false">Level 1</a>
					    <a class="nav-item nav-link" id="nav-level-2-tab" data-toggle="tab" href="#nav-level-2" role="tab" aria-controls="nav-level-2" aria-selected="false">Level 2</a>
					    <a class="nav-item nav-link" id="nav-level-3-tab" data-toggle="tab" href="#nav-level-3" role="tab" aria-controls="nav-level-3" aria-selected="false">Level 3</a>
					    <a class="nav-item nav-link" id="nav-level-4-tab" data-toggle="tab" href="#nav-level-4" role="tab" aria-controls="nav-level-4" aria-selected="false">Level 4</a>
				  	</div>
				</nav>
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
jQuery(document).ready(function(){

	jQuery("#tambah-pohon-kinerja").on('click', function(){
		pokinLevel1().then(function(){
			jQuery("#pokinLevel1").DataTable();
		});
	});

	jQuery(document).on('click', '#tambah-pokin-level1', function(){
		jQuery("#modal-crud").find('.modal-title').html('Tambah Pohon Kinerja');
		jQuery("#modal-crud").find('.modal-body').html(''
			+'<form id="form-pokin">'
				+'<input type="hidden" name="parent" value="0">'
				+'<input type="hidden" name="level" value="1">'
				+'<div class="form-group">'
						+'<textarea class="form-control" name="label" placeholder="Tuliskan pohon kinerja level 1..."></textarea>'
				+'</div>'
			+'</form>');
		jQuery("#modal-crud").find('.modal-footer').html(''
			+'<button type="button" class="btn btn-danger" data-dismiss="modal">'
				+'Tutup'
			+'</button>'
			+'<button type="button" class="btn btn-success" id="simpan-data-pokin" data-action="create_pokin" data-view="pokinLevel1">'
				+'Simpan'
			+'</button>');
		jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','');
		jQuery("#modal-crud").find('.modal-dialog').css('width','');
		jQuery("#modal-crud").modal('show');
	})

	jQuery(document).on('click', '.edit-pokin-level1', function(){
		jQuery("#wrap-loading").show();
		jQuery.ajax({
			method:'POST',
			url:esakip.url,
			data:{
		  		"action": "edit_pokin",
		  		"api_key": esakip.api_key,
		  		'id':jQuery(this).data('id')
			},
			dataType:'json',
			success:function(response){
				jQuery("#wrap-loading").hide();
				jQuery("#modal-crud").find('.modal-title').html('Edit Pohon Kinerja');
				jQuery("#modal-crud").find('.modal-body').html(``
					+`<form id="form-pokin">`
						+`<input type="hidden" name="id" value="${response.data.id}">`
						+`<input type="hidden" name="parent" value="${response.data.parent}">`
						+`<input type="hidden" name="level" value="${response.data.level}">`
						+`<div class="form-group">`
							+`<textarea class="form-control" name="label">${response.data.label}</textarea>`
						+`</div>`
					+`</form>`);
				jQuery("#modal-crud").find(`.modal-footer`).html(``
					+`<button type="button" class="btn btn-danger" data-dismiss="modal">`
						+`Tutup`
					+`</button>`
					+`<button type="button" class="btn btn-success" id="simpan-data-pokin" data-action="update_pokin" data-view="pokinLevel1">`
						+`Update`
					+`</button>`);
				jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','');
				jQuery("#modal-crud").find('.modal-dialog').css('width','');
				jQuery("#modal-crud").modal('show');
			}
		});
	})

	jQuery(document).on('click', '.hapus-pokin-level1', function(){
		if(confirm(`Data akan dihapus?`)){
			jQuery("#wrap-loading").show();
			jQuery.ajax({
				method:'POST',
				url:esakip.url,
				data:{
					'action': 'delete_pokin',
		      		'api_key': esakip.api_key,
					'id':jQuery(this).data('id'),
					'level':1,
				},
				dataType:'json',
				success:function(response){
					jQuery("#wrap-loading").hide();
					alert(response.message);
					if(response.status){
						pokinLevel1().then(function(){
							jQuery("#pokinLevel1").DataTable();
						});
					}
				}
			})
		}
	});

	jQuery(document).on('click', '.tambah-indikator-pokin-level1', function(){
		jQuery("#modal-crud").find('.modal-title').html('Tambah Indikator');
		jQuery("#modal-crud").find('.modal-body').html(``
			+`<form id="form-pokin">`
				+`<input type="hidden" name="parent" value="${jQuery(this).data('id')}">`
				+`<input type="hidden" name="label" value="${jQuery(this).parent().parent().find('.label-level1').text()}">`
				+`<input type="hidden" name="level" value="1">`
				+`<div class="form-group">`
					+`<label for="indikator-label">${jQuery(this).parent().parent().find('.label-level1').text()}</label>`
					+`<textarea class="form-control" name="indikator_label" placeholder="Tuliskan indikator..."></textarea>`
				+`</div>`
			+`</form>`);
		jQuery("#modal-crud").find('.modal-footer').html(``
			+`<button type="button" class="btn btn-danger" data-dismiss="modal">`
				+`Tutup`
			+`</button>`
			+`<button type="button" class="btn btn-success" id="simpan-data-pokin" data-action="create_indikator_pokin" data-view="pokinLevel1">`
				+`Simpan`
			+`</button>`);
		jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','');
		jQuery("#modal-crud").find('.modal-dialog').css('width','');
		jQuery("#modal-crud").modal('show');
	})

	jQuery(document).on('click', '.edit-indikator-pokin-level1', function(){
		jQuery("#wrap-loading").show();
		jQuery.ajax({
			method:'POST',
			url:esakip.url,
			data:{
	  			"action": "edit_indikator_pokin",
	  			"api_key": esakip.api_key,
	  			'id':jQuery(this).data('id')
			},
			dataType:'json',
			success:function(response){
				jQuery("#wrap-loading").hide();
				jQuery("#modal-crud").find('.modal-title').html('Edit Indikator');
				jQuery("#modal-crud").find('.modal-body').html(``
					+`<form id="form-pokin">`
						+`<input type="hidden" name="id" value="${response.data.id}">`
						+`<input type="hidden" name="parent" value="${response.data.parent}">`
						+`<input type="hidden" name="level" value="${response.data.level}">`
						+`<div class="form-group">`
							+`<label for="indikator-label">${response.data.label}</label>`
							+`<textarea class="form-control" name="indikator_label">${response.data.label_indikator_kinerja}</textarea>`
						+`</div>`
					+`</form>`);
				jQuery("#modal-crud").find(`.modal-footer`).html(``
					+`<button type="button" class="btn btn-danger" data-dismiss="modal">`
						+`Tutup`
					+`</button>`
					+`<button type="button" class="btn btn-success" id="simpan-data-pokin" data-action="update_indikator_pokin" data-view="pokinLevel1">`
						+`Update`
					+`</button>`);
				jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','');
				jQuery("#modal-crud").find('.modal-dialog').css('width','');
				jQuery("#modal-crud").modal('show');
			}
		});
	})

	jQuery(document).on('click', '.hapus-indikator-pokin-level1', function(){
		if(confirm(`Data akan dihapus?`)){
			jQuery("#wrap-loading").show();
			jQuery.ajax({
				method:'POST',
				url:esakip.url,
				data:{
					'action': 'delete_indikator_pokin',
		      		'api_key': esakip.api_key,
					'id':jQuery(this).data('id')
				},
				dataType:'json',
				success:function(response){
					jQuery("#wrap-loading").hide();
					alert(response.message);
					if(response.status){
						pokinLevel1().then(function(){
							jQuery("#pokinLevel1").DataTable();
						});
					}
				}
			})
		}
	});

	jQuery(document).on('click', '.view-pokin-level2', function(){
		pokinLevel2({
			'parent':jQuery(this).data('id')
		}).then(function(){
			jQuery("#pokinLevel2").DataTable();
		});
	})

	jQuery(document).on('click', '#tambah-pokin-level2', function(){
		jQuery("#modal-crud").find('.modal-title').html('Tambah Pohon Kinerja');
		jQuery("#modal-crud").find('.modal-body').html(``
			+`<form id="form-pokin">`				
				+`<input type="hidden" name="parent" value="${jQuery(this).data('parent')}">`
				+`<input type="hidden" name="level" value="2">`
				+`<div class="form-group">`
						+`<textarea class="form-control" name="label" placeholder="Tuliskan pohon kinerja level 2..."></textarea>`
				+`</div>`
			+`</form>`);
		jQuery("#modal-crud").find('.modal-footer').html(''
			+'<button type="button" class="btn btn-danger" data-dismiss="modal">'
				+'Tutup'
			+'</button>'
			+'<button type="button" class="btn btn-success" id="simpan-data-pokin" data-action="create_pokin" data-view="pokinLevel2">'
				+'Simpan'
			+'</button>');
		jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','');
		jQuery("#modal-crud").find('.modal-dialog').css('width','');
		jQuery("#modal-crud").modal('show');
	})

	jQuery(document).on('click', '.edit-pokin-level2', function(){
		jQuery("#wrap-loading").show();
		jQuery.ajax({
			method:'POST',
			url:esakip.url,
			data:{
		  		"action": "edit_pokin",
		  		"api_key": esakip.api_key,
		  		'id':jQuery(this).data('id')
			},
			dataType:'json',
			success:function(response){
				jQuery("#wrap-loading").hide();
				jQuery("#modal-crud").find('.modal-title').html('Edit Pohon Kinerja');
				jQuery("#modal-crud").find('.modal-body').html(``
					+`<form id="form-pokin">`
						+`<input type="hidden" name="id" value="${response.data.id}">`
						+`<input type="hidden" name="parent" value="${response.data.parent}">`
						+`<input type="hidden" name="level" value="${response.data.level}">`
						+`<div class="form-group">`
							+`<textarea class="form-control" name="label">${response.data.label}</textarea>`
						+`</div>`
					+`</form>`);
				jQuery("#modal-crud").find(`.modal-footer`).html(``
					+`<button type="button" class="btn btn-danger" data-dismiss="modal">`
						+`Tutup`
					+`</button>`
					+`<button type="button" class="btn btn-success" id="simpan-data-pokin" data-action="update_pokin" data-view="pokinLevel2">`
						+`Update`
					+`</button>`);
				jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','');
				jQuery("#modal-crud").find('.modal-dialog').css('width','');
				jQuery("#modal-crud").modal('show');
			}
		});
	})

	jQuery(document).on('click', '.hapus-pokin-level2', function(){
		let parent = jQuery(this).data('parent');
		if(confirm(`Data akan dihapus?`)){
			jQuery("#wrap-loading").show();
			jQuery.ajax({
				method:'POST',
				url:esakip.url,
				data:{
					'action': 'delete_pokin',
		      		'api_key': esakip.api_key,
					'id':jQuery(this).data('id'),
					'level':2,
				},
				dataType:'json',
				success:function(response){
					jQuery("#wrap-loading").hide();
					alert(response.message);
					if(response.status){
						pokinLevel2({
							'parent':parent
						}).then(function(){
							jQuery("#pokinLevel2").DataTable();
						});
					}
				}
			})
		}
	});

	jQuery(document).on('click', '.tambah-indikator-pokin-level2', function(){
		jQuery("#modal-crud").find('.modal-title').html('Tambah Indikator');
		jQuery("#modal-crud").find('.modal-body').html(``
			+`<form id="form-pokin">`
				+`<input type="hidden" name="parent_all" value="${jQuery(this).data('parent')}">`
				+`<input type="hidden" name="parent" value="${jQuery(this).data('id')}">`
				+`<input type="hidden" name="label" value="${jQuery(this).parent().parent().find('.label-level2').text()}">`
				+`<input type="hidden" name="level" value="2">`
				+`<div class="form-group">`
					+`<label for="indikator-label">${jQuery(this).parent().parent().find('.label-level2').text()}</label>`
					+`<textarea class="form-control" name="indikator_label" placeholder="Tuliskan indikator..."></textarea>`
				+`</div>`
			+`</form>`);
		jQuery("#modal-crud").find('.modal-footer').html(``
			+`<button type="button" class="btn btn-danger" data-dismiss="modal">`
				+`Tutup`
			+`</button>`
			+`<button type="button" class="btn btn-success" id="simpan-data-pokin" data-action="create_indikator_pokin" data-view="pokinLevel2">`
				+`Simpan`
			+`</button>`);
		jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','');
		jQuery("#modal-crud").find('.modal-dialog').css('width','');
		jQuery("#modal-crud").modal('show');
	})

	jQuery(document).on('click', '.edit-indikator-pokin-level2', function(){
		jQuery("#wrap-loading").show();
		jQuery.ajax({
			method:'POST',
			url:esakip.url,
			data:{
	  			"action": "edit_indikator_pokin",
	  			"api_key": esakip.api_key,
	  			'id':jQuery(this).data('id')
			},
			dataType:'json',
			success:function(response){
				jQuery("#wrap-loading").hide();
				jQuery("#modal-crud").find('.modal-title').html('Edit Indikator');
				jQuery("#modal-crud").find('.modal-body').html(``
					+`<form id="form-pokin">`
						+`<input type="hidden" name="id" value="${response.data.id}">`
						+`<input type="hidden" name="parent_all" value="${response.data.parent_all}">`
						+`<input type="hidden" name="parent" value="${response.data.parent}">`
						+`<input type="hidden" name="level" value="${response.data.level}">`
						+`<div class="form-group">`
							+`<label for="indikator-label">${response.data.label}</label>`
							+`<textarea class="form-control" name="indikator_label">${response.data.label_indikator_kinerja}</textarea>`
						+`</div>`
					+`</form>`);
				jQuery("#modal-crud").find(`.modal-footer`).html(``
					+`<button type="button" class="btn btn-danger" data-dismiss="modal">`
						+`Tutup`
					+`</button>`
					+`<button type="button" class="btn btn-success" id="simpan-data-pokin" data-action="update_indikator_pokin" data-view="pokinLevel2">`
						+`Update`
					+`</button>`);
				jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','');
				jQuery("#modal-crud").find('.modal-dialog').css('width','');
				jQuery("#modal-crud").modal('show');
			}
		});
	})

	jQuery(document).on('click', '.hapus-indikator-pokin-level2', function(){
		let parent = jQuery(this).data('parent');
		if(confirm(`Data akan dihapus?`)){
			jQuery("#wrap-loading").show();
			jQuery.ajax({
				method:'POST',
				url:esakip.url,
				data:{
					'action': 'delete_indikator_pokin',
		      		'api_key': esakip.api_key,
					'id':jQuery(this).data('id')
				},
				dataType:'json',
				success:function(response){
					jQuery("#wrap-loading").hide();
					alert(response.message);
					if(response.status){
						pokinLevel2({
							'parent':parent
						}).then(function(){
							jQuery("#pokinLevel2").DataTable();
						});
					}
				}
			})
		}
	});

	jQuery(document).on('click', '.view-pokin-level3', function(){
		pokinLevel3({
			'parent':jQuery(this).data('id')
		}).then(function(){
			jQuery("#pokinLevel3").DataTable();
		});
	})

	jQuery(document).on('click', '#tambah-pokin-level3', function(){
		jQuery("#modal-crud").find('.modal-title').html('Tambah Pohon Kinerja');
		jQuery("#modal-crud").find('.modal-body').html(``
			+`<form id="form-pokin">`				
				+`<input type="hidden" name="parent" value="${jQuery(this).data('parent')}">`
				+`<input type="hidden" name="level" value="3">`
				+`<div class="form-group">`
						+`<textarea class="form-control" name="label" placeholder="Tuliskan pohon kinerja level 3..."></textarea>`
				+`</div>`
			+`</form>`);
		jQuery("#modal-crud").find('.modal-footer').html(''
			+'<button type="button" class="btn btn-danger" data-dismiss="modal">'
				+'Tutup'
			+'</button>'
			+'<button type="button" class="btn btn-success" id="simpan-data-pokin" data-action="create_pokin" data-view="pokinLevel3">'
				+'Simpan'
			+'</button>');
		jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','');
		jQuery("#modal-crud").find('.modal-dialog').css('width','');
		jQuery("#modal-crud").modal('show');
	})

	jQuery(document).on('click', '.edit-pokin-level3', function(){
		jQuery("#wrap-loading").show();
		jQuery.ajax({
			method:'POST',
			url:esakip.url,
			data:{
		  		"action": "edit_pokin",
		  		"api_key": esakip.api_key,
		  		'id':jQuery(this).data('id')
			},
			dataType:'json',
			success:function(response){
				jQuery("#wrap-loading").hide();
				jQuery("#modal-crud").find('.modal-title').html('Edit Pohon Kinerja');
				jQuery("#modal-crud").find('.modal-body').html(``
					+`<form id="form-pokin">`
						+`<input type="hidden" name="id" value="${response.data.id}">`
						+`<input type="hidden" name="parent" value="${response.data.parent}">`
						+`<input type="hidden" name="level" value="${response.data.level}">`
						+`<div class="form-group">`
							+`<textarea class="form-control" name="label">${response.data.label}</textarea>`
						+`</div>`
					+`</form>`);
				jQuery("#modal-crud").find(`.modal-footer`).html(``
					+`<button type="button" class="btn btn-danger" data-dismiss="modal">`
						+`Tutup`
					+`</button>`
					+`<button type="button" class="btn btn-success" id="simpan-data-pokin" data-action="update_pokin" data-view="pokinLevel3">`
						+`Update`
					+`</button>`);
				jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','');
				jQuery("#modal-crud").find('.modal-dialog').css('width','');
				jQuery("#modal-crud").modal('show');
			}
		});
	})

	jQuery(document).on('click', '.hapus-pokin-level3', function(){
		let parent = jQuery(this).data('parent');
		if(confirm(`Data akan dihapus?`)){
			jQuery("#wrap-loading").show();
			jQuery.ajax({
				method:'POST',
				url:esakip.url,
				data:{
					'action': 'delete_pokin',
		      		'api_key': esakip.api_key,
					'id':jQuery(this).data('id'),
					'level':3,
				},
				dataType:'json',
				success:function(response){
					jQuery("#wrap-loading").hide();
					alert(response.message);
					if(response.status){
						pokinLevel3({
							'parent':parent
						}).then(function(){
							jQuery("#pokinLevel3").DataTable();
						});
					}
				}
			})
		}
	});

	jQuery(document).on('click', '.tambah-indikator-pokin-level3', function(){
		jQuery("#modal-crud").find('.modal-title').html('Tambah Indikator');
		jQuery("#modal-crud").find('.modal-body').html(``
			+`<form id="form-pokin">`
				+`<input type="hidden" name="parent_all" value="${jQuery(this).data('parent')}">`
				+`<input type="hidden" name="parent" value="${jQuery(this).data('id')}">`
				+`<input type="hidden" name="label" value="${jQuery(this).parent().parent().find('.label-level3').text()}">`
				+`<input type="hidden" name="level" value="3">`
				+`<div class="form-group">`
					+`<label for="indikator-label">${jQuery(this).parent().parent().find('.label-level3').text()}</label>`
					+`<textarea class="form-control" name="indikator_label" placeholder="Tuliskan indikator..."></textarea>`
				+`</div>`
			+`</form>`);
		jQuery("#modal-crud").find('.modal-footer').html(``
			+`<button type="button" class="btn btn-danger" data-dismiss="modal">`
				+`Tutup`
			+`</button>`
			+`<button type="button" class="btn btn-success" id="simpan-data-pokin" data-action="create_indikator_pokin" data-view="pokinLevel3">`
				+`Simpan`
			+`</button>`);
		jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','');
		jQuery("#modal-crud").find('.modal-dialog').css('width','');
		jQuery("#modal-crud").modal('show');
	})

	jQuery(document).on('click', '.edit-indikator-pokin-level3', function(){
		jQuery("#wrap-loading").show();
		jQuery.ajax({
			method:'POST',
			url:esakip.url,
			data:{
	  			"action": "edit_indikator_pokin",
	  			"api_key": esakip.api_key,
	  			'id':jQuery(this).data('id')
			},
			dataType:'json',
			success:function(response){
				jQuery("#wrap-loading").hide();
				jQuery("#modal-crud").find('.modal-title').html('Edit Indikator');
				jQuery("#modal-crud").find('.modal-body').html(``
					+`<form id="form-pokin">`
						+`<input type="hidden" name="id" value="${response.data.id}">`
						+`<input type="hidden" name="parent_all" value="${response.data.parent_all}">`
						+`<input type="hidden" name="parent" value="${response.data.parent}">`
						+`<input type="hidden" name="level" value="${response.data.level}">`
						+`<div class="form-group">`
							+`<label for="indikator-label">${response.data.label}</label>`
							+`<textarea class="form-control" name="indikator_label">${response.data.label_indikator_kinerja}</textarea>`
						+`</div>`
					+`</form>`);
				jQuery("#modal-crud").find(`.modal-footer`).html(``
					+`<button type="button" class="btn btn-danger" data-dismiss="modal">`
						+`Tutup`
					+`</button>`
					+`<button type="button" class="btn btn-success" id="simpan-data-pokin" data-action="update_indikator_pokin" data-view="pokinLevel3">`
						+`Update`
					+`</button>`);
				jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','');
				jQuery("#modal-crud").find('.modal-dialog').css('width','');
				jQuery("#modal-crud").modal('show');
			}
		});
	})

	jQuery(document).on('click', '.hapus-indikator-pokin-level3', function(){
		let parent = jQuery(this).data('parent');
		if(confirm(`Data akan dihapus?`)){
			jQuery("#wrap-loading").show();
			jQuery.ajax({
				method:'POST',
				url:esakip.url,
				data:{
					'action': 'delete_indikator_pokin',
		      		'api_key': esakip.api_key,
					'id':jQuery(this).data('id')
				},
				dataType:'json',
				success:function(response){
					jQuery("#wrap-loading").hide();
					alert(response.message);
					if(response.status){
						pokinLevel3({
							'parent':parent
						}).then(function(){
							jQuery("#pokinLevel3").DataTable();
						});
					}
				}
			})
		}
	});

	jQuery(document).on('click', '.view-pokin-level4', function(){
		pokinLevel4({
			'parent':jQuery(this).data('id'),
		}).then(function(){
			jQuery("#pokinLevel4").DataTable();
		});
	})

	jQuery(document).on('click', '#tambah-pokin-level4', function(){
		jQuery("#modal-crud").find('.modal-title').html('Tambah Pohon Kinerja');
		jQuery("#modal-crud").find('.modal-body').html(``
			+`<form id="form-pokin">`				
				+`<input type="hidden" name="parent" value="${jQuery(this).data('parent')}">`
				+`<input type="hidden" name="level" value="4">`
				+`<div class="form-group">`
						+`<textarea class="form-control" name="label" placeholder="Tuliskan pohon kinerja level 4..."></textarea>`
				+`</div>`
			+`</form>`);
		jQuery("#modal-crud").find('.modal-footer').html(''
			+'<button type="button" class="btn btn-danger" data-dismiss="modal">'
				+'Tutup'
			+'</button>'
			+'<button type="button" class="btn btn-success" id="simpan-data-pokin" data-action="create_pokin" data-view="pokinLevel4">'
				+'Simpan'
			+'</button>');
		jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','');
		jQuery("#modal-crud").find('.modal-dialog').css('width','');
		jQuery("#modal-crud").modal('show');
	})

	jQuery(document).on('click', '.edit-pokin-level4', function(){
		jQuery("#wrap-loading").show();
		jQuery.ajax({
			method:'POST',
			url:esakip.url,
			data:{
		  		"action": "edit_pokin",
		  		"api_key": esakip.api_key,
		  		'id':jQuery(this).data('id')
			},
			dataType:'json',
			success:function(response){
				jQuery("#wrap-loading").hide();
				jQuery("#modal-crud").find('.modal-title').html('Edit Pohon Kinerja');
				jQuery("#modal-crud").find('.modal-body').html(``
					+`<form id="form-pokin">`
						+`<input type="hidden" name="id" value="${response.data.id}">`
						+`<input type="hidden" name="parent" value="${response.data.parent}">`
						+`<input type="hidden" name="level" value="${response.data.level}">`
						+`<div class="form-group">`
							+`<textarea class="form-control" name="label">${response.data.label}</textarea>`
						+`</div>`
					+`</form>`);
				jQuery("#modal-crud").find(`.modal-footer`).html(``
					+`<button type="button" class="btn btn-danger" data-dismiss="modal">`
						+`Tutup`
					+`</button>`
					+`<button type="button" class="btn btn-success" id="simpan-data-pokin" data-action="update_pokin" data-view="pokinLevel4">`
						+`Update`
					+`</button>`);
				jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','');
				jQuery("#modal-crud").find('.modal-dialog').css('width','');
				jQuery("#modal-crud").modal('show');
			}
		});
	})

	jQuery(document).on('click', '.hapus-pokin-level4', function(){
		let parent = jQuery(this).data('parent');
		if(confirm(`Data akan dihapus?`)){
			jQuery("#wrap-loading").show();
			jQuery.ajax({
				method:'POST',
				url:esakip.url,
				data:{
					'action': 'delete_pokin',
		      		'api_key': esakip.api_key,
					'id':jQuery(this).data('id'),
					'level':4,
				},
				dataType:'json',
				success:function(response){
					jQuery("#wrap-loading").hide();
					alert(response.message);
					if(response.status){
						pokinLevel4({
							'parent':parent
						}).then(function(){
							jQuery("#pokinLevel4").DataTable();
						});
					}
				}
			})
		}
	});

	jQuery(document).on('click', '.tambah-indikator-pokin-level4', function(){
		jQuery("#modal-crud").find('.modal-title').html('Tambah Indikator');
		jQuery("#modal-crud").find('.modal-body').html(``
			+`<form id="form-pokin">`
				+`<input type="hidden" name="parent_all" value="${jQuery(this).data('parent')}">`
				+`<input type="hidden" name="parent" value="${jQuery(this).data('id')}">`
				+`<input type="hidden" name="label" value="${jQuery(this).parent().parent().find('.label-level4').text()}">`
				+`<input type="hidden" name="level" value="4">`
				+`<div class="form-group">`
					+`<label for="indikator-label">${jQuery(this).parent().parent().find('.label-level4').text()}</label>`
					+`<textarea class="form-control" name="indikator_label" placeholder="Tuliskan indikator..."></textarea>`
				+`</div>`
			+`</form>`);
		jQuery("#modal-crud").find('.modal-footer').html(``
			+`<button type="button" class="btn btn-danger" data-dismiss="modal">`
				+`Tutup`
			+`</button>`
			+`<button type="button" class="btn btn-success" id="simpan-data-pokin" data-action="create_indikator_pokin" data-view="pokinLevel4">`
				+`Simpan`
			+`</button>`);
		jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','');
		jQuery("#modal-crud").find('.modal-dialog').css('width','');
		jQuery("#modal-crud").modal('show');
	});

	jQuery(document).on('click', '.edit-indikator-pokin-level4', function(){
		jQuery("#wrap-loading").show();
		jQuery.ajax({
			method:'POST',
			url:esakip.url,
			data:{
	  			"action": "edit_indikator_pokin",
	  			"api_key": esakip.api_key,
	  			'id':jQuery(this).data('id')
			},
			dataType:'json',
			success:function(response){
				jQuery("#wrap-loading").hide();
				jQuery("#modal-crud").find('.modal-title').html('Edit Indikator');
				jQuery("#modal-crud").find('.modal-body').html(``
					+`<form id="form-pokin">`
						+`<input type="hidden" name="id" value="${response.data.id}">`
						+`<input type="hidden" name="parent_all" value="${response.data.parent_all}">`
						+`<input type="hidden" name="parent" value="${response.data.parent}">`
						+`<input type="hidden" name="level" value="${response.data.level}">`
						+`<div class="form-group">`
							+`<label for="indikator-label">${response.data.label}</label>`
							+`<textarea class="form-control" name="indikator_label">${response.data.label_indikator_kinerja}</textarea>`
						+`</div>`
					+`</form>`);
				jQuery("#modal-crud").find(`.modal-footer`).html(``
					+`<button type="button" class="btn btn-danger" data-dismiss="modal">`
						+`Tutup`
					+`</button>`
					+`<button type="button" class="btn btn-success" id="simpan-data-pokin" data-action="update_indikator_pokin" data-view="pokinLevel4">`
						+`Update`
					+`</button>`);
				jQuery("#modal-crud").find('.modal-dialog').css('maxWidth','');
				jQuery("#modal-crud").find('.modal-dialog').css('width','');
				jQuery("#modal-crud").modal('show');
			}
		});
	})

	jQuery(document).on('click', '.hapus-indikator-pokin-level4', function(){
		let parent = jQuery(this).data('parent');
		if(confirm(`Data akan dihapus?`)){
			jQuery("#wrap-loading").show();
			jQuery.ajax({
				method:'POST',
				url:esakip.url,
				data:{
					'action': 'delete_indikator_pokin',
		      		'api_key': esakip.api_key,
					'id':jQuery(this).data('id')
				},
				dataType:'json',
				success:function(response){
					jQuery("#wrap-loading").hide();
					alert(response.message);
					if(response.status){
						pokinLevel4({
							'parent':parent
						}).then(function(){
							jQuery("#pokinLevel4").DataTable();
						});
					}
				}
			})
		}
	});

	jQuery(document).on('click', '#simpan-data-pokin', function(){
		jQuery('#wrap-loading').show();
		let modal = jQuery("#modal-crud");
		let action = jQuery(this).data('action');
		let view = jQuery(this).data('view');
		let form = getFormData(jQuery("#form-pokin"));
		form['id_jadwal'] = '<?php echo $input['periode']; ?>';
		
		jQuery.ajax({
			method:'POST',
			url:esakip.url,
			dataType:'json',
			data:{
				'action': action,
	      		'api_key': esakip.api_key,
				'data': JSON.stringify(form),
			},
			success:function(response){
				jQuery('#wrap-loading').hide();
				alert(response.message);
				if(response.status){
					runFunction(view, [form])
					modal.modal('hide');
				}
			}
		})
	});
});

function pokinLevel1(){
	jQuery("#wrap-loading").show();
	return new Promise(function(resolve, reject){
		jQuery.ajax({
			url: esakip.url,
	      	type: "post",
	      	data: {
	      		"action": "get_data_pokin",
	      		"level": 1,
	      		"parent": 0,
	      		"id_jadwal": '<?php echo $input['periode']; ?>',
	      		"api_key": esakip.api_key
	      	},
	      	dataType: "json",
	      	success: function(res){
          		jQuery('#wrap-loading').hide();
          		let level1 = ``
	          		+`<div style="margin-top:10px">`
          				+`<button type="button" class="btn btn-success mb-2" id="tambah-pokin-level1"><i class="dashicons dashicons-plus" style="margin-top: 2px;"></i>Tambah Data</button>`
	          		+`</div>`
	          		+`<table class="table" id="pokinLevel1">`
	          			+`<thead>`
	          				+`<tr>`
	          					+`<th class="text-center" style="width:20%">No</th>`
	          					+`<th class="text-center" style="width:60%">Label Pohon Kinerja</th>`
	          					+`<th class="text-center" style="width:20%">Aksi</th>`
	          				+`</tr>`
	          			+`</thead>`
	          			+`<tbody>`;
			          		res.data.map(function(value, index){
			          			level1 += ``
				          			+`<tr id="pokinLevel1_${value.id}">`
					          			+`<td class="text-center">${index+1}.</td>`
					          			+`<td class="label-level1">${value.label}</td>`
					          			+`<td class="text-center">`
					          				+`<a href="javascript:void(0)" data-id="${value.id}" class="btn btn-sm btn-success tambah-indikator-pokin-level1" title="Tambah Indikator"><i class="dashicons dashicons-plus"></i></a> `
					          				+`<a href="javascript:void(0)" data-id="${value.id}" class="btn btn-sm btn-warning view-pokin-level2" title="Lihat pohon kinerja level 2"><i class="dashicons dashicons dashicons-menu-alt"></i></a> `
				          					+`<a href="javascript:void(0)" data-id="${value.id}" class="btn btn-sm btn-primary edit-pokin-level1" title="Edit"><i class="dashicons dashicons-edit"></i></a>&nbsp;`
				          					+`<a href="javascript:void(0)" data-id="${value.id}" class="btn btn-sm btn-danger hapus-pokin-level1" title="Hapus"><i class="dashicons dashicons-trash"></i></a>`
					          			+`</td>`
					          		+`</tr>`;

					          	let indikator = Object.values(value.indikator);
					          	if(indikator.length > 0){
									indikator.map(function(indikator_value, indikator_index){
										level1 += ``
								     	+`<tr>`
								      		+`<td><span style="display:none">${index+1}</span></td>`
								      		+`<td>${index+1}.${indikator_index+1} ${indikator_value.label}</td>`
								      		+`<td class="text-center">`
							      				+`<a href="javascript:void(0)" data-id="${indikator_value.id}" class="btn btn-sm btn-primary edit-indikator-pokin-level1" title="Edit"><i class="dashicons dashicons-edit"></i></a> `
							      				+`<a href="javascript:void(0)" data-id="${indikator_value.id}" class="btn btn-sm btn-danger hapus-indikator-pokin-level1" title="Hapus"><i class="dashicons dashicons-trash"></i></a>`
								      		+`</td>`
								      	+`</tr>`;
									});
					          	}
			          		});
          					level1+=`<tbody>`
          			+`</table>`;

          		jQuery("#nav-level-1").html(level1);
				jQuery('.nav-tabs a[href="#nav-level-1"]').tab('show');
				jQuery('#modal-pokin').modal('show');
				resolve();
    		}
		});
	});
}

function pokinLevel2(params){
	jQuery("#wrap-loading").show();
	let parent = params.parent_all ?? params.parent;	
	return new Promise(function(resolve, reject){
		jQuery.ajax({
			url: esakip.url,
	      	type: "post",
	      	data: {
	      		"action": "get_data_pokin",
	      		"level": 2,
	      		"parent": parent,
	      		"id_jadwal": '<?php echo $input['periode']; ?>',
	      		"api_key": esakip.api_key
	      	},
	      	dataType: "json",
	      	success: function(res){
          		jQuery('#wrap-loading').hide();
          		let level2 = ``
	          		+`<div style="margin-top:10px">`
          				+`<button type="button" data-parent="${parent}" class="btn btn-success mb-2" id="tambah-pokin-level2"><i class="dashicons dashicons-plus" style="margin-top: 2px;"></i>Tambah Data</button>`
	          		+`</div>`
	          		+`<table class="table">`
      					+`<thead>`;
      						res.parent.map(function(value, index){
      							if(value!=null){
		          					level2 += ``
				          				+`<tr>`
				          					+`<th class="text-center" style="width: 160px;">Level ${(index+1)}</th>`
				          					+`<th>${value}</th>`
				          				+`</tr>`;
      							}
	          				});
      					level2+=`</thead>`
      				+`</table>`
	          		+`<table class="table" id="pokinLevel2">`
	          			+`<thead>`
	          				+`<tr>`
	          					+`<th class="text-center" style="width:20%">No</th>`
	          					+`<th class="text-center" style="width:60%">Label Pohon Kinerja</th>`
	          					+`<th class="text-center" style="width:20%">Aksi</th>`
	          				+`</tr>`
	          			+`</thead>`
	          			+`<tbody>`;
			          		res.data.map(function(value, index){
			          			level2 += ``
				          			+`<tr>`
					          			+`<td class="text-center">${index+1}.</td>`
					          			+`<td class="label-level2">${value.label}</td>`
					          			+`<td class="text-center">`
					          				+`<a href="javascript:void(0)" data-id="${value.id}" data-parent="${value.parent}" class="btn btn-sm btn-success tambah-indikator-pokin-level2" title="Tambah Indikator"><i class="dashicons dashicons-plus"></i></a> `
					          				+`<a href="javascript:void(0)" data-id="${value.id}" class="btn btn-sm btn-warning view-pokin-level3" title="Lihat pohon kinerja level 3"><i class="dashicons dashicons dashicons-menu-alt"></i></a> `
				          					+`<a href="javascript:void(0)" data-id="${value.id}" data-parent="${value.parent}" class="btn btn-sm btn-primary edit-pokin-level2" title="Edit"><i class="dashicons dashicons-edit"></i></a>&nbsp;`
				          					+`<a href="javascript:void(0)" data-id="${value.id}" data-parent="${value.parent}" class="btn btn-sm btn-danger hapus-pokin-level2" title="Hapus"><i class="dashicons dashicons-trash"></i></a>`
					          			+`</td>`
					          		+`</tr>`;

					          	let indikator = Object.values(value.indikator);
					          	if(indikator.length > 0){
									indikator.map(function(indikator_value, indikator_index){
										level2 += ``
								     	+`<tr>`
								      		+`<td><span style="display:none">${index+1}</span></td>`
								      		+`<td>${index+1}.${indikator_index+1} ${indikator_value.label}</td>`
								      		+`<td class="text-center">`
							      				+`<a href="javascript:void(0)" data-id="${indikator_value.id}" class="btn btn-sm btn-primary edit-indikator-pokin-level2" title="Edit"><i class="dashicons dashicons-edit"></i></a> `
							      				+`<a href="javascript:void(0)" data-id="${indikator_value.id}" data-parent="${value.parent}" class="btn btn-sm btn-danger hapus-indikator-pokin-level2" title="Hapus"><i class="dashicons dashicons-trash"></i></a>`
								      		+`</td>`
								      	+`</tr>`;
									});
					          	}
			          		});
          					level2+=`<tbody>`
          			+`</table>`;

				jQuery("#nav-level-2").html(level2);
				jQuery('.nav-tabs a[href="#nav-level-2"]').tab('show');
				jQuery('#modal-pokin').modal('show');
				resolve();
    		}
		});
	});
}

function pokinLevel3(params){
	jQuery("#wrap-loading").show();
	let parent = params.parent_all ?? params.parent;
	return new Promise(function(resolve, reject){
		jQuery.ajax({
			url: esakip.url,
	      	type: "post",
	      	data: {
	      		"action": "get_data_pokin",
	      		"level": 3,
	      		"parent": parent,
	      		"id_jadwal": '<?php echo $input['periode']; ?>',
	      		"api_key": esakip.api_key
	      	},
	      	dataType: "json",
	      	success: function(res){
          		jQuery('#wrap-loading').hide();
          		let level3 = ``
	          		+`<div style="margin-top:10px">`
          				+`<button type="button" data-parent="${parent}" class="btn btn-success mb-2" id="tambah-pokin-level3"><i class="dashicons dashicons-plus" style="margin-top: 2px;"></i>Tambah Data</button>`
	          		+`</div>`
	          		+`<table class="table">`
      					+`<thead>`;
      						res.parent.map(function(value, index){
      							if(value!=null){
		          					level3 += ``
				          				+`<tr>`
				          					+`<th class="text-center" style="width: 160px;">Level ${(index+1)}</th>`
				          					+`<th>${value}</th>`
				          				+`</tr>`;
      							}
	          				});
      					level3+=`</thead>`
      				+`</table>`
	          		+`<table class="table" id="pokinLevel3">`
	          			+`<thead>`
	          				+`<tr>`
	          					+`<th class="text-center" style="width:20%">No</th>`
	          					+`<th class="text-center" style="width:60%">Label Pohon Kinerja</th>`
	          					+`<th class="text-center" style="width:20%">Aksi</th>`
	          				+`</tr>`
	          			+`</thead>`
	          			+`<tbody>`;
			          		res.data.map(function(value, index){
			          			level3 += ``
				          			+`<tr>`
					          			+`<td class="text-center">${index+1}.</td>`
					          			+`<td class="label-level3">${value.label}</td>`
					          			+`<td class="text-center">`
					          				+`<a href="javascript:void(0)" data-id="${value.id}" data-parent="${value.parent}" class="btn btn-sm btn-success tambah-indikator-pokin-level3" title="Tambah Indikator"><i class="dashicons dashicons-plus"></i></a> `
					          				+`<a href="javascript:void(0)" data-id="${value.id}" class="btn btn-sm btn-warning view-pokin-level4" title="Lihat pohon kinerja level 4"><i class="dashicons dashicons dashicons-menu-alt"></i></a> `
				          					+`<a href="javascript:void(0)" data-id="${value.id}" data-parent="${value.parent}" class="btn btn-sm btn-primary edit-pokin-level3" title="Edit"><i class="dashicons dashicons-edit"></i></a>&nbsp;`
				          					+`<a href="javascript:void(0)" data-id="${value.id}" data-parent="${value.parent}" class="btn btn-sm btn-danger hapus-pokin-level3" title="Hapus"><i class="dashicons dashicons-trash"></i></a>`
					          			+`</td>`
					          		+`</tr>`;

					          	let indikator = Object.values(value.indikator);
					          	if(indikator.length > 0){
									indikator.map(function(indikator_value, indikator_index){
										level3 += ``
								     	+`<tr>`
								      		+`<td><span style="display:none">${index+1}</span></td>`
								      		+`<td>${index+1}.${indikator_index+1} ${indikator_value.label}</td>`
								      		+`<td class="text-center">`
							      				+`<a href="javascript:void(0)" data-id="${indikator_value.id}" class="btn btn-sm btn-primary edit-indikator-pokin-level3" title="Edit"><i class="dashicons dashicons-edit"></i></a> `
							      				+`<a href="javascript:void(0)" data-id="${indikator_value.id}" data-parent="${value.parent}" class="btn btn-sm btn-danger hapus-indikator-pokin-level3" title="Hapus"><i class="dashicons dashicons-trash"></i></a>`
								      		+`</td>`
								      	+`</tr>`;
									});
					          	}
			          		});
          					level3+=`<tbody>`
          			+`</table>`;

				jQuery("#nav-level-3").html(level3);
				jQuery('.nav-tabs a[href="#nav-level-3"]').tab('show');
				jQuery('#modal-pokin').modal('show');
				resolve();
    		}
		});
	});
}

function pokinLevel4(params){
	jQuery("#wrap-loading").show();
	let parent = params.parent_all ?? params.parent;
	return new Promise(function(resolve, reject){
		jQuery.ajax({
			url: esakip.url,
	      	type: "post",
	      	data: {
	      		"action": "get_data_pokin",
	      		"level": 4,
	      		"parent": parent,
	      		"id_jadwal": '<?php echo $input['periode']; ?>',
	      		"api_key": esakip.api_key
	      	},
	      	dataType: "json",
	      	success: function(res){
          		jQuery('#wrap-loading').hide();
          		let level4 = ``
	          		+`<div style="margin-top:10px">`
          				+`<button type="button" data-parent="${parent}" class="btn btn-success mb-2" id="tambah-pokin-level4"><i class="dashicons dashicons-plus" style="margin-top: 2px;"></i>Tambah Data</button>`
	          		+`</div>`
	          		+`<table class="table">`
      					+`<thead>`;
      						res.parent.map(function(value, index){
      							if(value!=null){
		          					level4 += ``
				          				+`<tr>`
				          					+`<th class="text-center" style="width: 160px;">Level ${(index+1)}</th>`
				          					+`<th>${value}</th>`
				          				+`</tr>`;
      							}
	          				});
      					level4+=`</thead>`
      				+`</table>`
	          		+`<table class="table" id="pokinLevel4">`
	          			+`<thead>`
	          				+`<tr>`
	          					+`<th class="text-center" style="width:20%">No</th>`
	          					+`<th class="text-center" style="width:60%">Label Pohon Kinerja</th>`
	          					+`<th class="text-center" style="width:20%">Aksi</th>`
	          				+`</tr>`
	          			+`</thead>`
	          			+`<tbody>`;
			          		res.data.map(function(value, index){
			          			level4 += ``
				          			+`<tr>`
					          			+`<td class="text-center">${index+1}.</td>`
					          			+`<td class="label-level4">${value.label}</td>`
					          			+`<td class="text-center">`
					          				+`<a href="javascript:void(0)" data-id="${value.id}" data-parent="${value.parent}" class="btn btn-sm btn-success tambah-indikator-pokin-level4" title="Tambah Indikator"><i class="dashicons dashicons-plus"></i></a> `
					          				+`<a href="javascript:void(0)" data-id="${value.id}" data-parent="${value.parent}" class="btn btn-sm btn-primary edit-pokin-level4" title="Edit"><i class="dashicons dashicons-edit"></i></a>&nbsp;`
				          					+`<a href="javascript:void(0)" data-id="${value.id}" data-parent="${value.parent}" class="btn btn-sm btn-danger hapus-pokin-level4" title="Hapus"><i class="dashicons dashicons-trash"></i></a>`
					          			+`</td>`
					          		+`</tr>`;

					          	let indikator = Object.values(value.indikator);
					          	if(indikator.length > 0){
									indikator.map(function(indikator_value, indikator_index){
										level4 += ``
								     	+`<tr>`
								      		+`<td><span style="display:none">${index+1}</span></td>`
								      		+`<td>${index+1}.${indikator_index+1} ${indikator_value.label}</td>`
								      		+`<td class="text-center">`
							      				+`<a href="javascript:void(0)" data-id="${indikator_value.id}" class="btn btn-sm btn-primary edit-indikator-pokin-level4" title="Edit"><i class="dashicons dashicons-edit"></i></a> `
							      				+`<a href="javascript:void(0)" data-id="${indikator_value.id}" data-parent="${value.parent}" class="btn btn-sm btn-danger hapus-indikator-pokin-level4" title="Hapus"><i class="dashicons dashicons-trash"></i></a>`
								      		+`</td>`
								      	+`</tr>`;
									});
					          	}
			          		});
          					level4+=`<tbody>`
          			+`</table>`;

				jQuery("#nav-level-4").html(level4);
				jQuery('.nav-tabs a[href="#nav-level-4"]').tab('show');
				jQuery('#modal-pokin').modal('show');
				resolve();
    		}
		});
	});
}

function runFunction(name, arguments){
    var fn = window[name];
    if(typeof fn !== 'function')
        return;

    var run = fn.apply(window, arguments);
    run.then(function(){
 		jQuery("#"+name).DataTable();
	});
}

function getFormData($form) {
    let unindexed_array = $form.serializeArray();
    let indexed_array = {};

    jQuery.map(unindexed_array, function (n, i) {
    	indexed_array[n['name']] = n['value'];
    });

    return indexed_array;
}

</script>