<?php
global $wpdb;

if (!defined('WPINC')) {
	die;
}

$input = shortcode_atts(array(
	'tahun_anggaran' => '2022'
), $atts);

// Perangkat Daerah
$dokumen = $wpdb->get_results($wpdb->prepare("
	SELECT
		*
	FROM esakip_menu_dokumen
	WHERE tahun_anggaran=%d
		AND active=1
		AND user_role= 'perangkat_daerah'
		AND nama_dokumen NOT LIKE 'RENSTRA'
        AND nama_tabel != 'esakip_pohon_kinerja_opd'
        AND nama_tabel != 'esakip_pohon_kinerja_dan_cascading'
", $input['tahun_anggaran']), ARRAY_A);
$dok_html = "";
foreach($dokumen as $dok){
	$dok_html .= "<th class='text-center'>".$dok['nama_dokumen']."</th>";
}

$unit = $wpdb->get_results($wpdb->prepare("
	SELECT
		*
	FROM esakip_data_unit
	WHERE tahun_anggaran=%d
		AND active=1
		AND is_skpd=1
	ORDER BY kode_skpd ASC
", $input['tahun_anggaran']), ARRAY_A);
$unit_html = "";
$no = 0;
foreach($unit as $opd){
	$no++;
	$dok_html_opd = "";
	foreach($dokumen as $dok){
		$jml_dokumen = $wpdb->get_var($wpdb->prepare("
			SELECT 
				count(id)
			FROM $dok[nama_tabel]
			WHERE id_skpd=%d
				AND active=1
				AND tahun_anggaran=%d
		", $opd['id_skpd'], $input['tahun_anggaran']));
	$warning = "bg-success";
	if($jml_dokumen == 0){
		$warning="bg-danger";
	}
		$dok_html_opd .= "<td class='text-center $warning'>$jml_dokumen</td>";
	}
	$unit_html .= "
	<tr>
		<td class='text-center'>".$no."</td>
		<td>".$opd['nama_skpd']."</td>
		$dok_html_opd
	</tr>";
}

// RENSTRA
$dokumen_renstra = $wpdb->get_results(
    $wpdb->prepare("
	SELECT 
        j.id,
        j.nama_jadwal,
        j.nama_jadwal_renstra,
        j.tahun_anggaran AS tahun,
        j.lama_pelaksanaan,
        j.tahun_selesai_anggaran,
        r.id_jadwal_renstra,
        r.tahun_anggaran
    FROM esakip_data_jadwal j
    INNER JOIN esakip_pengaturan_upload_dokumen r
        ON r.id_jadwal_renstra = j.id
    WHERE j.tipe = 'RPJMD'
      AND j.status = 1
      AND r.tahun_anggaran = %d
    ", $input['tahun_anggaran']), ARRAY_A
);
$get_dok_html_renstra = "";
foreach($dokumen_renstra as $dok_renstra){
	if (!empty($dok_renstra['tahun_selesai_anggaran']) && $dok_renstra['tahun_selesai_anggaran'] > 1) {
        $tahun_anggaran_selesai_renstra = $dok_renstra['tahun_selesai_anggaran'];
    } else {
        $tahun_anggaran_selesai_renstra = $dok_renstra['tahun'] + $dok_renstra['lama_pelaksanaan'];
    }
	$get_dok_html_renstra .= "<th class='text-center'>RENSTRA | ".$dok_renstra['nama_jadwal']." Periode ".$dok_renstra['tahun']." - ".$tahun_anggaran_selesai_renstra."</th>";
}
$unit_html_renstra = "";
$no_renstra = 0;
foreach($unit as $opd_renstra){
	$no_renstra++;
	$dok_html_renstra = "";
	foreach($dokumen_renstra as $dok_renstra){
		$jml_dokumen_renstra = $wpdb->get_var($wpdb->prepare("
			SELECT 
				count(id)
			FROM esakip_renstra
			WHERE id_skpd=%d
				AND active=1
				AND id_jadwal=%d
		", $opd_renstra['id_skpd'], $dok_renstra['id']));
	$warning = "bg-success";
	if($jml_dokumen_renstra == 0){
		$warning="bg-danger";
	}
		$dok_html_renstra .= "<td class='text-center $warning'>$jml_dokumen_renstra</td>";
	}
	//Pohon Kinerja opd
	$dokumen_pokin_opd = $wpdb->get_results(
    	$wpdb->prepare("
    	SELECT 
	        j.id,
	        j.nama_jadwal,
	        j.nama_jadwal_renstra,
	        j.tahun_anggaran AS tahun,
	        j.lama_pelaksanaan,
	        j.tahun_selesai_anggaran,
	        r.id_jadwal_renstra,
	        r.id_jadwal_renstra
	    FROM esakip_data_jadwal j
	    INNER JOIN esakip_pengaturan_upload_dokumen r
	        ON r.id_jadwal_rpjmd = j.id
	    WHERE j.tipe = 'RPJMD'
	      	AND j.status = 1
      		AND r.tahun_anggaran = %d
    	", $input['tahun_anggaran']), ARRAY_A
	);
	$get_dok_html_pokin_opd = "";
	$dok_html_pokin_opd = "";
	foreach($dokumen_pokin_opd as $dok_pokin_opd){
		if (!empty($dok_pokin_opd['tahun_selesai_anggaran']) && $dok_pokin_opd['tahun_selesai_anggaran'] > 1) {
			$tahun_anggaran_selesai = $dok_pokin_opd['tahun_selesai_anggaran'];
		} else {
			$tahun_anggaran_selesai = $dok_pokin_opd['tahun'] + $dok_pokin_opd['lama_pelaksanaan'];
		}
		$get_dok_html_pokin_opd .= "<th class='text-center'>Pohon Kinerja dan Cascading | ".$dok_pokin_opd['nama_jadwal']." Periode ".$dok_pokin_opd['tahun']." - ".$tahun_anggaran_selesai."</th>";

		$jml_dokumen_pokin_opd = $wpdb->get_var($wpdb->prepare("
			SELECT 
				count(id)
			FROM esakip_pohon_kinerja_dan_cascading
			WHERE active=1
				AND id_skpd=%d
				AND id_jadwal=%d
		", $opd_renstra['id_skpd'], $dok_pokin_opd['id']));
		// print_r($jml_dokumen_pokin_opd); die($wpdb->last_query); 
		$warning = "bg-success";
		if($jml_dokumen_pokin_opd == 0){
			$warning="bg-danger";
		}
		$dok_html_pokin_opd .= "<td class='text-center $warning'>$jml_dokumen_pokin_opd</td>";
		
	}
	$unit_html_renstra .= "
	<tr>
		<td class='text-center'>".$no_renstra."</td>
		<td>".$opd_renstra['nama_skpd']."</td>
		$dok_html_renstra$dok_html_pokin_opd
	</tr>";
}

// Pemerintah Daerah
$dokumen_pemda = $wpdb->get_results($wpdb->prepare("
    SELECT
        *
    FROM esakip_menu_dokumen
    WHERE tahun_anggaran = %d
        AND active = 1
        AND user_role = 'pemerintah_daerah'
        AND nama_dokumen NOT IN ('RPJPD', 'RPJMD')
        AND nama_tabel != 'esakip_pohon_kinerja'
        AND nama_tabel != 'esakip_pohon_kinerja_dan_cascading_pemda'
", $input['tahun_anggaran']), ARRAY_A);
$get_dok_html_pemda = "";
foreach($dokumen_pemda as $dok_pemda){
	$get_dok_html_pemda .= "<th class='text-center'>".$dok_pemda['nama_dokumen']."</th>";
}

$unit_html_pemda = "";
$dok_html_pemda = "";
foreach($dokumen_pemda as $dok_pemda){
	$jml_dokumen_pemda = $wpdb->get_var($wpdb->prepare("
		SELECT 
			count(id)
		FROM $dok_pemda[nama_tabel]
		WHERE active=1
			AND tahun_anggaran=%d
	", $input['tahun_anggaran']));
	$warning = "bg-success";
	if($jml_dokumen_pemda == 0){
		$warning="bg-danger";
	}
	$dok_html_pemda .= "<td class='text-center $warning'>$jml_dokumen_pemda</td>";
}
$unit_html_pemda .= "
<tr>
	$dok_html_pemda
</tr>";

// RPJPD DAN RPJMD

$dokumen_rpjpd_rpjmd = $wpdb->get_results(
    $wpdb->prepare("
    SELECT 
        j.id,
        j.nama_jadwal,
        j.nama_jadwal_renstra,
        j.tahun_anggaran AS tahun,
        j.lama_pelaksanaan,
        j.tahun_selesai_anggaran,
        j.tipe,
        r.id_jadwal_rpjmd,
        r.tahun_anggaran
    FROM esakip_data_jadwal j
    INNER JOIN esakip_pengaturan_upload_dokumen r
        ON r.id_jadwal_rpjmd = j.id
    WHERE j.tipe = 'RPJMD'
      AND j.status = 1
      AND r.tahun_anggaran = %d
    ORDER BY j.tipe DESC, j.id ASC
    ", $input['tahun_anggaran']), ARRAY_A
);

$get_dok_html_rpjpd_rpjmd = "";
$unit_html_rpjpd_rpjmd = "";
$dok_html_rpjpd_rpjmd = "";
$tipe = "";  

foreach($dokumen_rpjpd_rpjmd as $dok_rpjpd_rpjmd){
    if (!empty($dok_rpjpd_rpjmd['tahun_selesai_anggaran']) && $dok_rpjpd_rpjmd['tahun_selesai_anggaran'] > 1) {
        $tahun_anggaran_selesai_rpjpd_rpjmd = $dok_rpjpd_rpjmd['tahun_selesai_anggaran'];
    } else {
        $tahun_anggaran_selesai_rpjpd_rpjmd = $dok_rpjpd_rpjmd['tahun'] + $dok_rpjpd_rpjmd['lama_pelaksanaan'];
    }

    $tipe = $dok_rpjpd_rpjmd['tipe'];  
    $get_dok_html_rpjpd_rpjmd .= "<th class='text-center'>".$tipe." | ".$dok_rpjpd_rpjmd['nama_jadwal']." Periode ".$dok_rpjpd_rpjmd['tahun']." - ".$tahun_anggaran_selesai_rpjpd_rpjmd."</th>";

    $tabel = "";
    if($tipe == 'RPJMD'){
        $tabel = 'esakip_rpjmd';
    }

    $jml_dokumen_rpjpd_rpjmd = $wpdb->get_var($wpdb->prepare("
        SELECT 
            count(id)
        FROM $tabel
        WHERE active=1
            AND id_jadwal=%d
    ", $dok_rpjpd_rpjmd['id']));

    $warning = "bg-success";
    if($jml_dokumen_rpjpd_rpjmd == 0){
        $warning="bg-danger";
    }
    $dok_html_rpjpd_rpjmd .= "<td class='text-center $warning'>$jml_dokumen_rpjpd_rpjmd</td>";
}

$dokumen_rpjpd = $wpdb->get_results(
    $wpdb->prepare("
        SELECT 
            j.id,
            j.nama_jadwal,
            j.tahun_anggaran AS tahun,
            j.lama_pelaksanaan,
            j.tahun_selesai_anggaran,
            j.tipe, 
            r.id_jadwal_rpjpd,
            r.tahun_anggaran
        FROM esakip_data_jadwal j
        INNER JOIN esakip_pengaturan_upload_dokumen r
            ON r.id_jadwal_rpjpd = j.id
        WHERE j.tipe = 'RPJPD'
          AND j.status = 1
          AND r.tahun_anggaran = %d
        ORDER BY j.tipe DESC, j.id ASC
    ", $input['tahun_anggaran']), ARRAY_A
);

// print_r($dokumen_rpjpd); die($wpdb->last_query);
$get_dok_html_rpjpd = "";
$unit_html_rpjpd = "";
$dok_html_rpjpd = "";
$tipe = "";  

foreach($dokumen_rpjpd as $dok_rpjpd){
    if (!empty($dok_rpjpd['tahun_selesai_anggaran']) && $dok_rpjpd['tahun_selesai_anggaran'] > 1) {
        $tahun_anggaran_selesai_rpjpd = $dok_rpjpd['tahun_selesai_anggaran'];
    } else {
        $tahun_anggaran_selesai_rpjpd = $dok_rpjpd['tahun'] + $dok_rpjpd['lama_pelaksanaan'];
    }

    $tipe = $dok_rpjpd['tipe'];  
    $get_dok_html_rpjpd .= "<th class='text-center'>".$tipe." | ".$dok_rpjpd['nama_jadwal']." Periode ".$dok_rpjpd['tahun']." - ".$tahun_anggaran_selesai_rpjpd."</th>";

    $tabel = "";
    if ($tipe == 'RPJPD' ){
        $tabel = 'esakip_rpjpd';
    } 

    $jml_dokumen_rpjpd = $wpdb->get_var($wpdb->prepare("
        SELECT 
            count(id)
        FROM $tabel
        WHERE active=1
            AND id_jadwal=%d
    ", $dok_rpjpd['id']));

    $warning = "bg-success";
    if($jml_dokumen_rpjpd == 0){
        $warning="bg-danger";
    }
    $dok_html_rpjpd .= "<td class='text-center $warning'>$jml_dokumen_rpjpd</td>";
}

//Pohon Kinerja Pemda
$dokumen_pokin_pemda = $wpdb->get_results(
    $wpdb->prepare("
	SELECT 
        j.id,
        j.nama_jadwal,
        j.nama_jadwal_renstra,
        j.tahun_anggaran AS tahun,
        j.lama_pelaksanaan,
        j.tahun_selesai_anggaran,
        r.id_jadwal_renstra
    FROM esakip_data_jadwal j
    INNER JOIN esakip_pengaturan_upload_dokumen r
        ON r.id_jadwal_rpjmd = j.id
    WHERE j.tipe = 'RPJMD'
      	AND j.status = 1
        AND r.tahun_anggaran = %d
    ", $input['tahun_anggaran']), ARRAY_A
);
$get_dok_html_pokin_pemda = "";
$dok_html_pokin_pemda = "";
foreach($dokumen_pokin_pemda as $dok_pokin_pemda){
	if (!empty($dok_pokin_pemda['tahun_selesai_anggaran']) && $dok_pokin_pemda['tahun_selesai_anggaran'] > 1) {
		$tahun_anggaran_selesai = $dok_pokin_pemda['tahun_selesai_anggaran'];
	} else {
		$tahun_anggaran_selesai = $dok_pokin_pemda['tahun'] + $dok_pokin_pemda['lama_pelaksanaan'];
	}
	$get_dok_html_pokin_pemda .= "<th class='text-center'>Pohon Kinerja dan Cascading | ".$dok_pokin_pemda['nama_jadwal']." Periode ".$dok_pokin_pemda['tahun']." - ".$tahun_anggaran_selesai."</th>";

	$jml_dokumen_pokin_pemda = $wpdb->get_var($wpdb->prepare("
		SELECT 
			count(id)
		FROM esakip_pohon_kinerja_dan_cascading_pemda
		WHERE active=1			
			AND id_jadwal=%d
	", $dok_pokin_pemda['id'])); 
	$warning = "bg-success";
	if($jml_dokumen_pokin_pemda == 0){
		$warning="bg-danger";
	}
	$dok_html_pokin_pemda .= "<td class='text-center $warning'>$jml_dokumen_pokin_pemda</td>";
	
}
$unit_html_rpjpd_rpjmd .= "
<tr>
	$dok_html_rpjpd$dok_html_rpjpd_rpjmd$dok_html_pokin_pemda
</tr>";

$current_user = wp_get_current_user();
$user_roles = $current_user->roles;
$is_admin_panrb = in_array('admin_panrb', $user_roles);
$admin_roles = array('administrator','admin_bappeda', 'admin_ortala');
$is_admin = false;

foreach ($user_roles as $role) {
    if (in_array($role, $admin_roles)) {
        $is_admin = true;
        break;
    }
}
?>

<div>
    <div style="padding: 10px;margin:0 0 3rem 0;">
    <input type="hidden" value="<?php echo get_option( '_crb_apikey_esakip' ); ?>" id="api_key">
    <h1 class="text-center" style="margin:3rem;">Setting Periode Dokumen untuk Tahun Anggaran <?php echo $input['tahun_anggaran']; ?></h1>
        <div class="d-flex justify-content-center">
            <div class="card" style="width: 50%;">
                <div class="card-body">
                    <form>
                        <div class="form-group">
                            <label for="jadwal-renstra">Pilih Jadwal RPJPD SI KSATRIA</label>
                            <select class="form-control" id="jadwal-rpjpd" <?php echo $is_admin_panrb ? 'disabled' : ''; ?>>
                            </select>
                            <small class="form-text text-muted">Untuk mendapatkan Periode sesuai jadwal RPJPD. digunakan untuk periode upload dokumen.</small>
                        </div>
                        <div class="form-group">
                            <label for="jadwal-renstra">Pilih Jadwal RPJMD/RPD SI KSATRIA</label>
                            <select class="form-control" id="jadwal-rpjmd" <?php echo $is_admin_panrb ? 'disabled' : ''; ?>>
                            </select>
                            <small class="form-text text-muted">Untuk mendapatkan Periode sesuai jadwal RPJMD/RPD. digunakan untuk periode upload dokumen.</small>
                        </div>
                        <div class="form-group">
                            <label for="jadwal-renstra">Pilih Jadwal RENSTRA SI KSATRIA</label>
                            <select class="form-control" id="jadwal-renstra" <?php echo $is_admin_panrb ? 'disabled' : ''; ?>>
                            </select>
                            <small class="form-text text-muted">Untuk mendapatkan Periode sesuai jadwal RENSTRA. digunakan untuk periode upload dokumen.</small>
                        </div>
                        <?php if (!$is_admin_panrb) : ?>
                        <div class="form-group d-flex">
                            <button onclick="submit_pengaturan_menu(); return false;" class="btn btn-primary ml-auto">Simpan</button>
                        </div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-md">
	<div class="cetak">
		<div style="padding: 10px;margin:0 0 3rem 0; overflow: auto;">
			<h1 class="text-center table-title">Monitor Upload Dokumen RPJPD dan RPJMD</h1>
			<table>
				<thead>
					<tr>
						<?php echo $get_dok_html_rpjpd; ?>
						<?php echo $get_dok_html_rpjpd_rpjmd; ?>
						<?php echo $get_dok_html_pokin_pemda; ?>
					</tr>
				</thead>
				<tbody>
					<?php echo $unit_html_rpjpd_rpjmd; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="container-md">
	<div class="cetak">
		<div style="padding: 10px;margin:0 0 3rem 0; overflow: auto;">
			<h1 class="text-center table-title">Monitor Upload Dokumen RENSTRA</h1>
			<table>
				<thead>
					<tr>
						<th>No</th>
						<th>Perangkat Daerah</th>
						<?php echo $get_dok_html_renstra; ?>
						<?php echo $get_dok_html_pokin_opd; ?>
					</tr>
				</thead>
				<tbody>
					<?php echo $unit_html_renstra; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="container-md">
	<div class="cetak">
		<div style="padding: 10px;margin:0 0 3rem 0; overflow: auto;">
			<h1 class="text-center table-title">Monitor Upload Dokumen Pemerintah Daerah Tahun <?php echo $input['tahun_anggaran']; ?></h1>
			<table>
				<thead>
					<tr>
						<?php echo $get_dok_html_pemda; ?>
					</tr>
				</thead>
				<tbody>
					<?php echo $unit_html_pemda; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="container-md">
	<div class="cetak">
		<div style="padding: 10px;margin:0 0 3rem 0; overflow: auto;">
			<h1 class="text-center table-title">Monitor Upload Dokumen Perangkat Daerah Tahun <?php echo $input['tahun_anggaran']; ?></h1>
			<table>
				<thead>
					<tr>
						<th>No</th>
						<th>Perangkat Daerah</th>
						<?php echo $dok_html; ?>
					</tr>
				</thead>
				<tbody>
					<?php echo $unit_html; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
    jQuery(document).ready(function(){
        globalThis.tahun_anggaran = "<?php echo $input['tahun_anggaran']; ?>"
        get_data_upload_dokumen();
    });

    function get_data_upload_dokumen(){
        jQuery("#wrap-loading").show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data:{
                'action'        : "get_data_upload_dokumen",
                'api_key'       : esakip.api_key,
                'tahun_anggaran': tahun_anggaran
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                console.log(response);
                if (response.status === 'success') {
                    jQuery('#jadwal-renstra').html(response.option_renstra)
                    jQuery('#jadwal-rpjmd').html(response.option_rpjmd)
                    jQuery('#jadwal-rpjpd').html(response.option_rpjpd)
                    if(response.data.length != 0){
                        console.log(response.data.id_jadwal)
                        jQuery('#jadwal-rpjpd').val(response.data.id_jadwal_rpjpd);                    
                        jQuery('#jadwal-rpjmd').val(response.data.id_jadwal_rpjmd);
                        jQuery('#jadwal-renstra').val(response.data.id_jadwal_renstra);
                    }
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

    function submit_pengaturan_menu(){
        let id_jadwal_rpjpd = jQuery("#jadwal-rpjpd").val();
        let id_jadwal_rpjmd = jQuery("#jadwal-rpjmd").val();
        let id_jadwal_renstra = jQuery("#jadwal-renstra").val();
        if (id_jadwal_rpjpd == '' || id_jadwal_rpjmd == '' ||  id_jadwal_renstra == '') {
            return alert('Ada data yang kosong!');
        }

        if(confirm("Apakah kamu yakin untuk mengubah data periode jadwal upload dokumen?")){
            jQuery('#wrap-loading').show();
            jQuery.ajax({
                method: 'POST',
                url: esakip.url,
                dataType: 'json',
                data:{
                    'action': 'submit_pengaturan_upload_dokumen',
                    'api_key': esakip.api_key,
                    'id_jadwal_rpjpd': id_jadwal_rpjpd,
                    'id_jadwal_rpjmd': id_jadwal_rpjmd,
                    'id_jadwal_renstra': id_jadwal_renstra,
                    'tahun_anggaran': tahun_anggaran
                },
                success: function(response) {
                    console.log(response);
                    jQuery('#wrap-loading').hide();
                    if (response.status === 'success') {
                        alert(response.message);
                        get_data_upload_dokumen();
                        afterSubmitForm();
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    alert('Terjadi kesalahan saat mengirim data!');
                    jQuery('#wrap-loading').hide();
                }
            });
        }
    }

    function afterSubmitForm(){
        jQuery("#keterangan").val("")
    }

</script> 