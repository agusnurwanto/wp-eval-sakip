<?php 
if ( ! defined( 'WPINC' ) ) {
	die;
}
global $wpdb;
$tahun_anggaran= get_option('_crb_tahun_wpsipd');
$api_key = get_option('_crb_apikey_esakip');

$unit = $wpdb->get_results("
	SELECT 
		nama_skpd, 
		id_skpd, 
		kode_skpd, 
		namakepala , 
		nipkepala 
	from esakip_data_unit 
	where active=1
		and tahun_anggaran=$tahun_anggaran
	group by id_skpd
	order by kode_skpd ASC
", ARRAY_A);

$html = '';
foreach ($unit as $kk => $vv) {
	$html .= '
		<tr>
			<td>'.$vv['kode_skpd'].'</td>
			<td>'.$vv['nama_skpd'].'</td>
			<td class="text-center">'.$vv['namakepala'].'<br>'.$vv['nipkepala'].'</td>
			<td><select class="form-class unor" style="width:100%"></select></td>
		</tr>
	';
}
?>
<div id="wrap-table" style="padding: 10px">
	<h1 class="text-center">Mapping Perangkat Daerah SIPD-SIMPEG </br>Tahun <?php echo $tahun_anggaran; ?></h1>
	<div style="margin-bottom: 25px;">
        <button class="btn btn-success" onclick="getUnor();"><i class="dashicons dashicons-arrow-down-alt"></i> Singkron Data Unit Organisasi Simpeg</button>
        <button class="btn btn-success" onclick="getPegawai();"><i class="dashicons dashicons-arrow-down-alt"></i> Singkron Data Pegawai Simpeg</button>
    </div>
	<table>
		<thead>
			<tr>
				<th class="text-center">Kode Perangkat Daerah SIPD</th>
				<th class="text-center" style="width: 500px;">Nama Perangkat Daerah SIPD</th>
				<th class="text-center" style="width: 500px;">Nama dan NIP</th>
				<th class="text-center" style="width: 500px;">Nama Perangkat Daerah SIMPEG</th>
			</tr>
		</thead>
		<tbody>
			<?php echo $html; ?>
		</tbody>
	</table>
</div>

<div class="modal fade" id="modal" data-backdrop="static"  role="dialog" aria-labelledby="modal-label" aria-hidden="true">
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

	var unorList;

	jQuery(document).ready(function(){

		getUnorList();

		jQuery(".unor").select2({
		  	ajax: {
		    	    url: esakip.url,
			        type: 'POST',
				    dataType: 'json',
				    delay: 250,
				    data: function (params) {
				      return {
				       	action: 'get_list_satker_simpeg',
					    api_key: esakip.api_key,
					    tahun_anggaran:'<?php echo $tahun_anggaran ?>',
					    type:'search',
						q: params.term,
				      };
				    },
				    processResults: function (data, params) {
				      params.page = params.page || 1;

				      return {
				        results: data.data,
				        pagination: {
				          more: (params.page * 30) < data.total_count
				        }
				      };
				    },
				    cache: true
		  },
		  placeholder: 'Cari unit organisasi',
		  minimumInputLength: 5,
		  templateResult: formatUnor,
		  templateSelection: formatUnorSelection
		});
	});

	function formatUnor (response) {
	  if (response.loading) {
	    return response.text;
	  }

	  var $container = jQuery(
	    "<div class='select2-result-repository clearfix'>" +
	      "<div class='select2-result-repository__meta'>" +
	        "<div class='select2-result-repository__title'></div>" +
	      "</div>" +
	    "</div>"
	  );

	  $container.find(".select2-result-repository__title").text(response.nama);

	  return $container;
	}

	function formatUnorSelection (response) {
	  return response.nama || response.text;
	}


	function getUnor(){
	    jQuery('#wrap-loading').show();
	    jQuery.ajax({
	        url: esakip.url,
	        type: 'POST',
	        data: {
	            action: 'get_satker_simpeg',
	            api_key: esakip.api_key,
	            tahun_anggaran:'<?php echo $tahun_anggaran ?>'
	        },
	        dataType: 'json',
	        success: function(response) {
	            jQuery('#wrap-loading').hide();
	            alert(response.message);
	        },
	        error: function(xhr, status, error) {
	    	    jQuery('#wrap-loading').hide();
	            alert('Terjadi kesalahan saat ambil data!');
	        }
	    });
	}

	function getPegawai(){
		if(unorList.length > 0){
			let optionSatker = `<option value=''>Pilih Salah Satu</option>`;
		    unorList.forEach(function(value, index){
		        optionSatker+=`<option value='${value.satker_id}'>${value.nama}</option>`;
		    })
		    jQuery("#modal").find('.modal-title').html('Singkronisasi Data Pegawai Simpeg');
			jQuery("#modal").find('.modal-body').html(`
				<nav class="mb-3">
				  	<div class="nav nav-tabs" id="nav-tab" role="tablist">
					    <a class="nav-item nav-link active" id="nav-unor-tab" data-toggle="tab" href="#nav-unor" role="tab" aria-controls="nav-unor" aria-selected="false">Unit Organisasi</a>
					    <a class="nav-item nav-link" id="nav-asn-tab" data-toggle="tab" href="#nav-asn" role="tab" aria-controls="nav-asn" aria-selected="false">ASN</a>
				  	</div>
				</nav>
				<div class="tab-content" id="nav-tabContent">
				  	<div class="tab-pane fade show active" id="nav-unor" role="tabpanel" aria-labelledby="nav-unor-tab">
				  		<select class'form-control' id="unor-list">${optionSatker}</select>
				  	</div>
				  	<div class="tab-pane fade" id="nav-asn" role="tabpanel" aria-labelledby="nav-asn-tab">
						<input type="text" class="form-control" id="nip" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" placeholder="Masukkan NIP ASN">
				  	</div>
				</div>
			`);
			jQuery("#modal").find('.modal-footer').html(`
				<button type="button" class="btn btn-warning" data-dismiss="modal">
					Tutup
				</button>
				<button type="button" class="btn btn-success" onclick="pullPegawai()">
					Singkron Data
				</button>`);
			jQuery("#modal").find('.modal-dialog').css('maxWidth','700');
			jQuery("#modal").modal('show');
		}else{
			alert('Unit Organisasi belum singkron!');
		}
	}

	function pullPegawai(){
		var activeLink = jQuery(".nav-tabs .nav-link.active");
		var hrefValue = activeLink.attr("href");
		var type = '';
		var value = '';

		switch(hrefValue){
			case '#nav-unor':
				type = 'unor';
				value = jQuery("#unor-list").val();
				break;
			case '#nav-asn':
				type = 'asn';
				value = jQuery("#nip").val();
				break;
			default:
				alert('Pilihan tidak diketahui');
				return false;
		}

		if(value.length > 0){
			jQuery('#wrap-loading').show();
		    jQuery.ajax({
		        url: esakip.url,
		        type: 'POST',
		        data: {
		            action: 'get_pegawai_simpeg',
		            api_key: esakip.api_key,
		            type: type,
		            value: value,
		        },
		        dataType: 'json',
		        success: function(response) {
		            jQuery('#wrap-loading').hide();
		            alert(response.message);
		        },
		        error: function(xhr, status, error) {
		    	    jQuery('#wrap-loading').hide();
		            alert('Terjadi kesalahan saat ambil data!');
		        }
		    });
		}else{
			alert('Pilih Unit Organisasi atau Masukkan NIP!');
		}
	}

	function getUnorList(){
		jQuery('#wrap-loading').show();
		return new Promise(function(resolve, reject){
			jQuery.ajax({
		        url: esakip.url,
		        type: 'POST',
		        data: {
		            action: 'get_list_satker_simpeg',
		            api_key: esakip.api_key,
		            tahun_anggaran:'<?php echo $tahun_anggaran ?>'
		        },
		        dataType: 'json',
		        success: function(response) {
					jQuery('#wrap-loading').hide();
		        	unorList = response.data;
					resolve();
		        },
		        error: function(xhr, status, error) {
		    	    jQuery('#wrap-loading').hide();
		            alert('Terjadi kesalahan saat ambil data!');
		        }
		    });
		})
	}
</script>