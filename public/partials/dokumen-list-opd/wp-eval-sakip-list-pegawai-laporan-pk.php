<?php
if (!defined('WPINC')) {
    die;
}
global $wpdb;

$input = shortcode_atts(array(
    'tahun_anggaran' => '2000'
), $atts);

$id_skpd = '';
if (!empty($_GET) && !empty($_GET['id_skpd'])) {
    $id_skpd = $_GET['id_skpd'];
} else {
    die('Parameter tidak lengkap!');
}

$tahun_anggaran_sakip = get_option(ESAKIP_TAHUN_ANGGARAN);
$nama_skpd = $this->get_data_skpd_by_id($id_skpd, $tahun_anggaran_sakip);

$id_satker = $wpdb->get_var(
    $wpdb->prepare("
        SELECT 
            id_satker_simpeg
        FROM esakip_data_mapping_unit_sipd_simpeg
        WHERE id_skpd = %d
          AND tahun_anggaran = %d
          AND active = 1
    ", $id_skpd, $tahun_anggaran_sakip)
);

$halaman_pegawai_skpd = $this->functions->generatePage(array(
    'nama_page' => 'List Perjanjian Kinerja ' . $input['tahun_anggaran'],
    'content' => '[list_perjanjian_kinerja tahun_anggaran=' . $input['tahun_anggaran'] . ']',
    'show_header' => 1,
    'post_status' => 'private'
));
?>
<div class="container-md">
    <div class="cetak">
        <div style="padding: 10px;margin:0 0 3rem 0;">
            <h1 class="text-center">Daftar Pegawai</br><?php echo $nama_skpd['nama_skpd'] ?></br>Tahun Anggaran <?php echo $input['tahun_anggaran']; ?></h1>
            <h3 class="text-center">Aktif = <span id="p_aktif">0</span> Pegawai || Tidak Aktif = <span id="p_non_aktif">0</span> Pegawai</h3>
            <div id="action" class="action-section hide-excel"></div>
            <table id="cetak" title="List Pegawai Laporan Perjanjian Kinerja Perangkat Daerah" class="table table-bordered table_list_pegawai" cellpadding="2" cellspacing="0">
                <thead style="background: #ffc491;">
                    <tr>
                        <th class="text-center" style="width: 30px;" title="Input Rencana Hasil Kerja (RHK) / Rencana Aksi">Aktif<br><input type="checkbox" id="cek_all"></th>
                        <th class="text-center">Satker ID</th>
                        <th class="text-center">Satuan Kerja</th>
                        <th class="text-center">Tipe Pegawai</th>
                        <th class="text-center">NIP</th>
                        <th class="text-center">Nama Pegawai</th>
                        <th class="text-center">Jabatan</th>
                        <th class="text-center">Atasan</th>
                        <th class="text-center" style="width: 70px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-edit-pegawai" data-backdrop="static" role="dialog" aria-labelledby="modal-edit-pegawai" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">Edit Pegawai</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <input type="hidden" id="id-pegawai">

                    <div class="card mb-3 shadow-lg bg-light">
                        <div class="card-header font-weight-bold">
                            Informasi Pegawai
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="nama-pegawai">Nama Pegawai</label>
                                <input type="text" class="form-control" id="nama-pegawai" disabled>
                            </div>
                            <div class="form-group">
                                <label for="nama-satker">Nama Satuan Kerja</label>
                                <input type="text" class="form-control" id="nama-satker" disabled>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-3 shadow-md bg-light">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="nama-pegawai-atasan">Pilih Atasan Pegawai</label>
                                <select class="form-control" id="nama-pegawai-atasan">
                                </select>
                                <div class="alert alert-primary mt-2" role="alert">
                                    Pastikan pegawai atasan tidak ada (sedang kosong). Jika pegawai ini sudah punya atasan aktif, proses update tidak dapat dilakukan (gagal).
                                    Kosongkan kolom input ini jika pegawai atasan adalah definitif.
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="nama-pegawai-atasan">Kustomisasi nama Jabatan Pegawai</label>
                                <input type="text" class="form-control" id="nama-jabatan-pegawai" placeholder="Jabatan + Nama PD">
                                <small class="form-text text-muted">
                                    nama jabatan ini akan digunakan untuk menampilkan nama jabatan pada laporan Perjanjian Kinerja. Jika tidak diisi, akan menggunakan nama jabatan dari data SIMPEG.
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-3 shadow-md bg-light">
                        <div class="card-body">
                            <div class="form-group">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="terapkan-all-satker">
                                    <label class="form-check-label" for="terapkan-all-satker">
                                        Terapkan Perubahan Ke Seluruh Pegawai di Satuan Kerja Ini
                                    </label>
                                    <small class="form-text text-muted">
                                        Centang jika ingin menerapkan perubahan ini ke seluruh pegawai di satuan kerja ini. Jangan dicentang jika hanya ingin menerapkan perubahan ke pegawai ini saja.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="handleUpdatePegawai()">Simpan</button>
            </div>
        </div>
    </div>
</div>

<script>
    jQuery(document).ready(function() {
        run_download_excel_sakip();
        var tombol_aksi = `
            <a id="singkron-pegawai" onclick="ajax_get_pegawai(); return false;" href="#" class="btn btn-danger"><span class="dashicons dashicons-update-alt"></span>Singkron Pegawai Dengan Data SIMPEG</a>
            <a target="_blank" href="<?php echo $halaman_pegawai_skpd['url'] . "&id_skpd=" . $id_skpd; ?>" class="btn btn-warning"><span class="dashicons dashicons-groups"></span>Daftar Perjanjian Kinerja Pegawai</a>
            <br><a onclick="simpan_pegawai(); return false;" href="#" class="btn btn-primary" style="margin-top: 5px;"><span class="dashicons dashicons-saved"></span>Simpan Pegawai Aktif</a>
        `;
        jQuery('#action-sakip').append(tombol_aksi);
        getTablePegawai();

        jQuery('#cek_all').on('change', function() {
            if (jQuery(this).is(':checked')) {
                jQuery('.input_rhk').prop('checked', true);
            } else {
                jQuery('.input_rhk').prop('checked', false);
            }
        });
    });

    function ajax_get_pegawai() {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_pegawai_simpeg',
                api_key: esakip.api_key,
                type: 'unor',
                value: '<?php echo $id_satker; ?>',
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                if (response.status == true) {
                    alert('Berhasil singkron data pegawai SIMPEG!');
                    getTablePegawai(1);
                } else {
                    alert('Gagal singkron data pegawai SIMPEG!');
                }
            },
            error: function(xhr, status, error) {
                jQuery('#wrap-loading').hide();
                console.error(xhr.responseText);
                alert('Terjadi kesalahan ajax!');
            }
        });
    }

    function simpan_pegawai() {
        var data = {};
        jQuery('.input_rhk').map(function(i, b) {
            var id = jQuery(b).val();
            if (jQuery(b).is(':checked')) {
                data[id] = 1;
            } else {
                data[id] = 0;
            }
        });
        if (Object.keys(data).length === 0) {
            return alert('Data pegawai tidak boleh kosong!');
        }
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'simpan_pegawai_simpeg',
                api_key: esakip.api_key,
                tahun_anggaran: <?php echo $input['tahun_anggaran']; ?>,
                id_skpd: <?php echo $id_skpd; ?>,
                ids: data
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                alert(response.message);
                if (response.status == 'success') {
                    getTablePegawai(1);
                }
            },
            error: function(xhr, status, error) {
                jQuery('#wrap-loading').hide();
                console.error(xhr.responseText);
                alert('Terjadi kesalahan!');
            }
        });
    }

    function getTablePegawai(destroy) {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_table_pegawai_simpeg',
                api_key: esakip.api_key,
                tahun_anggaran: <?php echo $input['tahun_anggaran']; ?>,
                id_skpd: <?php echo $id_skpd; ?>
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    jQuery('#p_aktif').text(response.aktif);
                    jQuery('#p_non_aktif').text(response.non_aktif);
                    jQuery('#nama-pegawai-atasan').html(response.option_pegawai);
                    jQuery('#nama-pegawai-atasan').select2({
                        placeholder: '-- Pilih Pegawai Atasan --',
                        dropdownParent: jQuery('#modal-edit-pegawai .modal-body'),
                        width: '100%'
                    });
                    if (destroy == 1) {
                        laporan_pk_table.fnDestroy();
                    }
                    jQuery('.table_list_pegawai tbody').html(response.data);
                    window.laporan_pk_table = jQuery('.table_list_pegawai').dataTable({
                        aLengthMenu: [
                            [5, 10, 25, 50, 100, -1],
                            [5, 10, 25, 50, 100, "All"]
                        ],
                        iDisplayLength: 50,
                        order: [],
                        aoColumnDefs: [{
                                bSortable: false,
                                aTargets: [0, 8]
                            },
                            {
                                bSearchable: false,
                                aTargets: [0, 8]
                            }
                        ]
                    });
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

    function editPegawai(idPegawai) {
        return alert('Fitur ini belum tersedia!');
        
        // jQuery('#wrap-loading').show();
        // jQuery.ajax({
        //     url: esakip.url,
        //     type: 'POST',
        //     data: {
        //         action: 'get_pegawai_by_id',
        //         api_key: esakip.api_key,
        //         id_pegawai: idPegawai
        //     },
        //     dataType: 'json',
        //     success: function(response) {
        //         jQuery('#wrap-loading').hide();
        //         if (response.status === 'success') {
        //             let namaPegawai = jQuery('.table_list_pegawai tbody tr[data-id="' + idPegawai + '"] td:nth-child(6)').text();
        //             let namaSatuanKerja = jQuery('.table_list_pegawai tbody tr[data-id="' + idPegawai + '"] td:nth-child(3)').text();
        //             jQuery('#id-pegawai').val(idPegawai);
        //             jQuery('#nama-pegawai').val(namaPegawai);
        //             jQuery('#nama-satker').val(namaSatuanKerja);
        //             jQuery('#nama-pegawai-atasan').val('').trigger('change');
        //             jQuery('#terapkan-all-satker').prop('checked', false);
        //             jQuery('#modal-edit-pegawai').modal('show');
        //         } else {
        //             alert('Gagal memuat data pegawai: ' + response.message);
        //         }
        //     },
        //     error: function(xhr, status, error) {
        //         jQuery('#wrap-loading').hide();
        //         console.error(xhr.responseText);
        //         alert('Terjadi kesalahan saat memuat data pegawai!');
        //     }
        // });
    }

    function handleUpdatePegawai() {
        let idPegawai = jQuery('#id-pegawai').val();
        let idPegawaiAtasan = jQuery('#nama-pegawai-atasan').val();
        let namaJabatanCustom = jQuery('#nama-jabatan-pegawai').val();
        let terapkanAllSatker = jQuery('#terapkan-all-satker').is(':checked') ? 1 : 0;

        if (!idPegawai) {
            alert('Pegawai harus dipilih!');
            return;
        }

        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'update_atasan_pegawai_simpeg',
                api_key: esakip.api_key,
                id_pegawai: idPegawai,
                id_atasan: idPegawaiAtasan,
                jabatan_custom: namaJabatanCustom,
                terapkan_all_satker: terapkanAllSatker
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                if (response.status === 'success') {
                    alert('Data pegawai berhasil diperbarui!');
                    getTablePegawai(1); // Refresh the table after update
                    jQuery('#modal-edit-pegawai').modal('hide');
                } else {
                    alert('Gagal memperbarui data pegawai: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert('Terjadi kesalahan saat memperbarui data pegawai!');
            }
        });
    }
</script>