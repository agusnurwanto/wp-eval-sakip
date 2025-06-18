<?php
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

$input = shortcode_atts(array(
    'tahun_anggaran' => '2024'
), $atts);

global $wpdb;

$all_lembaga = $wpdb->get_results(
    $wpdb->prepare("
        SELECT 
            *
        FROM esakip_data_lembaga_lainnya 
        WHERE active=1 
          AND tahun_anggaran=%d
    ", $input['tahun_anggaran']),
    ARRAY_A
);


if (empty($all_lembaga)) {
    return "<p><strong>Tidak ada data lembaga</strong> untuk tahun anggaran {$input['tahun_anggaran']}.</p>";
}

$html = '
        <div style="text-align: center; margin-bottom: 20px;" >
            <h2 style="
                font-size: 36px;
                color: #1a1a1a;
                margin-bottom: 15px;
            ">
                Lembaga Lainnya Tahun Anggaran ' . esc_html($input['tahun_anggaran']) . ' 
            </h2>
        </div>
    ';

$html .= '
        <div class="text-center mb-3">
        <button onclick="bukaModalSalin()" class="btn btn-primary  fw-bold text-decoration-none">
        <span class="dashicons dashicons-admin-page"></span>
            Salin Data        
        </button>
        </div>
    ';    

$html .= '
        <div class="text-start mb-3" style="margin-left: 10px;">
        <button onclick="handleTambah()" class="btn btn-success fw-bold text-decoration-none">
        <span class="dashicons dashicons-plus"></span>
            Tambah Data
        </button>      
        </div>
    ';

$html .= '<table border="1" cellspacing="0" style="border-collapse: collapse; width: 97%; margin-left: 10px;">';
$html .= '<thead><tr>';
$html .= '<th style="width: 50px; padding: 8px; text-align: center;">No</th>';
$html .= '<th style="width: 800px; padding: 8px; text-align: center;">Nama Lembaga</th>';
$html .= '<th style="width: 150px; padding: 8px; text-align: center;">Aksi</th>';
$html .= '</tr></thead><tbody>';

$no = 1;
foreach ($all_lembaga as $baris) {
    $btn = '';
    $btn .= '<button class="btn btn-warning m-1" onclick="handleEdit(' . $baris['id'] . ')"><span class="dashicons dashicons-edit"></span></button>';
    $btn .= '<button class="btn btn-danger m-1" onclick="handleDelete(' . $baris['id'] . ')"><span class="dashicons dashicons-trash"></span></button>';

    $html .= '<tr>';
    $html .= '<td style="padding: 8px 12px 8px 16px; text-align: center;">' . $no++ . '</td>';
    $html .= '<td style="width: 800px; padding: 8px;">' . esc_html($baris['nama_lembaga']) . '</td>';
    $html .= '<td class="text-center">' . $btn . '</td>'; //untuk aksi
    $html .= '</tr>';
}

$html .= '</tbody></table>';

echo $html;

?>
<div class="modal" tabindex="-1" role="dialog" id="modalTambah">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Tambah Lembaga</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="lembaga_id" name="lembaga_id" value="">

                <div class="form-group">
                    <label for="nama_lembaga"><strong>Nama Lembaga</strong></label>
                    <input type="text" class="form-control" id="nama_lembaga" name="nama_lembaga" placeholder="Masukkan nama lembaga" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-success" onclick="handleSubmit()">Simpan</button>
            </div>
        </div>
    </div>
</div>


<div class="modal" tabindex="-1" role="dialog" id="modalSalin">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Salin Data Lembaga</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="tahun_asal"><strong>Tahun Asal</strong></label>
            <select class="form-control" id="tahun_asal">
            </select>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-success" onclick="handleCopy()">Salin</button>
      </div>

    </div>
  </div>
</div>
<script>
    jQuery(document).ready(() => {
        getTahunAnggaran();
    })

    function handleTambah() {
        jQuery('#modalTitle').text('Tambah Lembaga')
        jQuery('#modalTambah').modal('show');
    }

    function handleEdit(id) {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_lembaga_lainnya_by_id',
                api_key: esakip.api_key,
                id: id
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    let data = response.data;
                    jQuery('#lembaga_id').val(data.id);
                    jQuery('#nama_lembaga').val(data.nama_lembaga);

                    jQuery('#modalTitle').text('Edit Lembaga');
                    jQuery('#modalTambah').modal('show');
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

    function handleDelete(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus Data ini?')) {
            return;
        }
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'hapus_lembaga_lainnya_by_id',
                api_key: esakip.api_key,
                id: id
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    alert(response.message);
                    window.location.reload();
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                jQuery('#wrap-loading').hide();
                alert('Terjadi kesalahan saat mengirim data!');
            }
        });
    }

    function handleSubmit() {
        let lembaga_id = jQuery("#lembaga_id").val();
        let nama_lembaga = jQuery("#nama_lembaga").val();

        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'upsert_lembaga_lainnya',
                id: lembaga_id,
                nama_lembaga: nama_lembaga,
                tahun_anggaran: <?php echo $input['tahun_anggaran']; ?>,
                api_key: esakip.api_key
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    alert(response.message);
                    jQuery('#modalTambah').modal('hide');
                    window.location.reload();
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

    function bukaModalSalin() {
        jQuery('#modalSalin').modal('show');
    }

    function getTahunAnggaran() {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_tahun_anggaran_data_unit',
                api_key: esakip.api_key
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    console.log(response.data.tahun_anggaran)
                    const select = jQuery('#tahun_asal');
                select.empty(); // kosongkan dulu
                response.data.forEach(function(item) {
                select.append('<option value="' + item.tahun_anggaran + '">' + item.tahun_anggaran + '</option>');
                });
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                jQuery('#wrap-loading').hide();
                console.error(xhr.responseText);
                alert('Terjadi kesalahan saat ambil tahun anggaran!');
            }
        });    
    }

    function handleCopy() {
        let tahun_tujuan = <?php echo $input['tahun_anggaran']; ?>;
        let tahun_asal = jQuery("#tahun_asal").val();

        if (!tahun_asal || isNaN(tahun_asal)) {
            alert("Tahun asal tidak valid!!!");
            return;
        }

        if (tahun_asal == tahun_tujuan) {
            alert("Tahun asal dan tahun tujuan tidak boleh sama!");
            return;
        }

        if (!confirm(`Apakah Anda yakin ingin menyalin data dari tahun ${tahun_asal} ke tahun ${tahun_tujuan}?`)) 
            return;

        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'copy_data_lembaga_lainnya',
                tahun_asal: tahun_asal,
                tahun_tujuan: tahun_tujuan,
                api_key: esakip.api_key
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    alert(response.message);
                    jQuery('#modalSalin').modal('hide');
                    window.location.reload();
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                jQuery('#wrap-loading').hide();
                console.error(xhr.responseText);
                alert('Terjadi kesalahan saat menyalin data!');
            }
        });
    }
</script>