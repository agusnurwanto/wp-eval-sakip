<?php
global $wpdb;

if (!defined('WPINC')) {
    die;
}

$input = shortcode_atts(array(
    'tahun' => '',
), $atts);

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
?>
<style>
    .daterangepicker {
        font-size: 20px !important; /* perkecil font */
        transform: scale(0.72); /* perkecil tampilan */
        transform-origin: top left;
        margin-top: -40px !important; /* geser ke atas */
        min-width: 700px !important;   /* atur lebar total */
    }

    .daterangepicker td.in-range {
        background-color: #cce7ffff !important;  
    }

    .daterangepicker .drp-calendar.right {
        padding-left: 25px !important;
    }

    .daterangepicker .drp-calendar {
        width: 48% !important;   /* masing-masing kalender setengah */
        margin: 0 15px !important; /* kasih jarak antar kalender */
    }

    .daterangepicker .calendar-table th, .daterangepicker .calendar-table td {
        font-size: 15px !important;
    }

    .daterangepicker .drp-selected  {
        font-size: 18px !important;
    } 

    .daterangepicker .drp-buttons .btn {
         font-size: 18px !important;
    }
    
    .daterangepicker select.hourselect, 
    .daterangepicker select.minuteselect, 
    .daterangepicker select.secondselect, 
    .daterangepicker select.ampmselect {
         font-size: 18px !important;
    }

</style>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<div class="container-md">
    <div style="padding: 10px;margin:0 0 3rem 0;">
        <h1 class="text-center" style="margin:3rem;">Halaman Penjadwalan Kuesioner<br>Tahun <?php echo $input['tahun']; ?></h1>
        <div class="wrap-table">
            <table id="table_jadwal_kuesioner" cellpadding="2" cellspacing="0" style="font-family:\'Open Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; border-collapse: collapse; width:100%; overflow-wrap: break-word;" class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center" style="vertical-align: middle;">Nama Jadwal</th>
                        <th class="text-center" style="vertical-align: middle;">Jadwal Mulai</th>
                        <th class="text-center" style="vertical-align: middle;">Jadwal Selesai</th>
                        <th class="text-center" style="vertical-align: middle;">Jenis Jadwal</th>
                        <th class="text-center" style="vertical-align: middle;">Jenis Kuesioner</th>
                        <th class="text-center" style="vertical-align: middle;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="hide-display-print container mt-4 p-4 mb-4 border rounded bg-light">
	<h4 class="font-weight-bold mb-3 text-dark">Catatan Jadwal Kuesioner:</h4>
	<ul class="pl-3 text-muted">
		<li>Jenis jadwal secara default adalah <strong>"Usulan"</strong>. Untuk mengubahnya menjadi <strong>"Penetapan"</strong>, gunakan tombol <strong>Edit</strong>, ubah jenis jadwal, lalu simpan.</li>
	</ul>
</div>

<!-- Modal tambah jadwal kuesioner -->
<div class="modal fade" id="modalTambahJadwal" tabindex="-1" aria-labelledby="modalTambahJadwalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalTambahJadwalLabel">Edit Penjadwalan</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="tambahJadwalForm">
                    <input type="hidden" id="id_jadwal">
					<div class="form-group">
						<label for="nama_jadwal">Nama Jadwal</label>
						<input type="text" id="nama_jadwal" class="form-control" readonly>
					</div>
					<div class="form-group">
						<label for="jadwal_tanggal">Jadwal Pelaksanaan</label>
						<input type="text" id="jadwal_tanggal" name="datetimes" class="form-control">
					</div>
					<div class="form-group">
						<label for="jenis_jadwal">Pilih Jenis Jadwal</label>
						<select class="form-control" id="jenis_jadwal">
							<option value="usulan" selected>Usulan</option>
							<option value="penetapan">Penetapan</option>
						</select>
						<small class="text-muted">Untuk mengisi nilai penetapan, ubah jenis jadwal dari <strong>Usulan</strong> ke <strong>Penetapan</strong></small>
					</div>								
				</form>
			</div>
			<div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="submitJadwal(); return false">Simpan</button>
            </div>
		</div>
	</div>
</div>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
    jQuery(document).ready(function() {
        get_table_jadwal_kuesioner();
            jQuery('#jadwal_tanggal').daterangepicker({
			timePicker: true,
			timePicker24Hour: true,
			startDate: moment().startOf('hour'),
			endDate: moment().startOf('hour').add(32, 'hour'),
			locale: {
				format: 'DD-MM-YYYY HH:mm'
			}
		});
    })
    function get_table_jadwal_kuesioner() {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_table_jadwal_kuesioner',
                api_key: esakip.api_key,
                tahun_anggaran:  <?php echo $input['tahun']; ?>
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                console.log(response);
                if (response.status) {
                    jQuery('#table_jadwal_kuesioner tbody').html(response.data);
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
    function handleEditJadwal(id) {
        jQuery('#modalTambahJadwal').modal('show');
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_data_jadwal_kuesioner_by_id',
                api_key: esakip.api_key,
                id: id,
                tahun_anggaran: <?php echo $input['tahun']; ?>
            },
            dataType: 'json',
            success: function(response) {
                let data = response.data;
                jQuery('#wrap-loading').hide();
                console.log(response);
                if (response.status) {
                    jQuery('#id_jadwal').val(id);   
                    jQuery('#nama_jadwal').val(data.nama_jadwal);
                    jQuery('#jadwal_tanggal').data('daterangepicker').setStartDate(moment(response.data.started_at).format('DD-MM-YYYY HH:mm'));
				    jQuery('#jadwal_tanggal').data('daterangepicker').setEndDate(moment(response.data.end_at).format('DD-MM-YYYY HH:mm'));
                    jQuery('#jenis_jadwal').val(data.jenis_jadwal);
                }
            }
        })
    }
    function submitJadwal() {
        const id = jQuery('#id_jadwal').val(); 
        const nama_jadwal = jQuery('#nama_jadwal').val();
        const jadwal_tanggal = jQuery('#jadwal_tanggal').val();
        const jenis_jadwal = jQuery('#jenis_jadwal').val();

        if (confirm('Apakah anda yakin untuk menyimpan data ini?')) {
            jQuery('#wrap-loading').show();
            jQuery.ajax({
                url: esakip.url,
                type: 'POST',
                data: {
                    action: 'submit_jadwal_kuesioner',
                    api_key: esakip.api_key,
                    id: id,
                    nama_jadwal: nama_jadwal,
                    jadwal_tanggal: jadwal_tanggal,
                    jenis_jadwal: jenis_jadwal,
                    tahun_anggaran: <?php echo $input['tahun']; ?>
                },
                dataType: 'json',
                success: function(response) {
                    jQuery('#wrap-loading').hide();
                    console.log(response);
                    if (response.status) {
                        alert('Berhasil disimpan!');
                        jQuery('#modalTambahJadwal').modal('hide');
                        get_table_jadwal_kuesioner();
                    } else {
                        alert('Gagal: ' + response.message);
                    }
                },
                error: function (xhr, status, error) {
                    jQuery('#wrap-loading').hide();
                    console.error(error);
                    alert('Terjadi kesalahan saat menyimpan.');
                }
            });
        } 
    }
</script>