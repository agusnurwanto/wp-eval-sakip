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

$laporan_pk_publik = $this->functions->generatePage(array(
    'nama_page'     => 'Perjanjian Kinerja Perangkat Daerah | Tahun ' . $input['tahun_anggaran'],
    'content'       => '[perjanjian_kinerja_publik tahun_anggaran=' . $input['tahun_anggaran'] . ']',
    'show_header'   => 1,
    'post_status'   => 'publish'
));

// Get kepala daerah and status jabatan for option atasan
$nama_kepala_daerah = get_option('_crb_kepala_daerah') ?: 'Kepala Daerah (set di halaman Pengaturan)';
$status_jabatan_kepala_daerah = get_option('_crb_status_jabatan_kepala_daerah') ?: 'Kepala Daerah (set di halaman Pengaturan)';
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
                            <div class="form-group" id="nama-pegawai-atasan-teks-container">
                                <label for="nama-pegawai-atasan-teks">Nama Pegawai Atasan</label>
                                <input type="text" class="form-control" id="nama-pegawai-atasan-teks" disabled>
                            </div>
                            <div class="form-group" id="nama-pegawai-atasan-container">
                                <label for="nama-pegawai-atasan">Pilih Atasan Pegawai</label>
                                <select class="form-control" id="nama-pegawai-atasan">
                                </select>
                            </div>
                            <div class="alert alert-primary mt-2" role="alert" id="info-atasan-pegawai"></div>
                            <div class="form-group">
                                <div class="form-check" id="terapkan-all-satker-container">
                                    <input type="checkbox" class="form-check-input" id="terapkan-all-satker">
                                    <label class="form-check-label" for="terapkan-all-satker">
                                        Terapkan Perubahan ke Seluruh Pegawai di Satuan Kerja Ini
                                    </label>
                                    <small class="text-muted">
                                        Centang jika ingin menerapkan perubahan ini ke seluruh pegawai di satuan kerja <i class="font-weight-bold" id="nama-satker-info"></i> <strong>(pegawai definitif dikecualikan)</strong>. Jangan dicentang jika hanya ingin menerapkan perubahan ke pegawai ini saja.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-3 shadow-md bg-light">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="nama-pegawai-atasan">Ubah nama Jabatan Pegawai</label>
                                <input type="text" class="form-control" id="nama-jabatan-pegawai-custom" placeholder="Masukkan nama jabatan pegawai (opsional)">
                                <small class="text-muted">
                                    nama jabatan ini akan digunakan untuk menampilkan nama jabatan pada laporan Perjanjian Kinerja. Jika tidak diisi, akan menggunakan nama jabatan dari data SIMPEG.
                                </small>
                            </div>
                            <div class="form-group" id="plt-plh-teks-container">
                                <label for="plt-plh-teks">Status Jabatan</label>
                                <input type="text" class="form-control" id="plt-plh-teks">
                                <small class="text-muted">
                                    Wajib diisi karena jabatan non definitif. (Contoh PJ, PLT, PLH dll.)
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-3 shadow-md bg-light">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="format_halaman_kedua">Format halaman ke-2 Laporan Perjanjian Kinerja</label>
                                <select class="form-control" id="format_halaman_kedua">
                                    <option value="gabungan">Gabungan (Program, Kegiatan, Sub Kegiatan)</option>
                                    <option value="program">Program</option>
                                    <option value="kegiatan">Kegiatan</option>
                                    <option value="sub_kegiatan">Sub Kegiatan</option>
                                </select>
                                <small class="text-muted">
                                    Opsi bawaan adalah Gabungan
                                </small>
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
            <a target="_blank" href="<?php echo $halaman_pegawai_skpd['url'] . "&id_skpd=" . $id_skpd; ?>" class="btn btn-warning"><span class="dashicons dashicons-groups"></span> Daftar Perjanjian Kinerja Pegawai</a>
            <a target="_blank" href="<?php echo $laporan_pk_publik['url'] . "&id_skpd=" . $id_skpd; ?>" class="btn btn-info"><span class="dashicons dashicons-admin-site-alt2"></span> Laporan Perjanjian Kinerja Publik</a>
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
        if (Object.keys(data).length == 0) {
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
                if (response.status == 'success') {
                    jQuery('#p_aktif').text(response.aktif);
                    jQuery('#p_non_aktif').text(response.non_aktif);
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

    function handleEditPegawai(idPegawai) {
        jQuery('#wrap-loading').show();
        jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_data_pegawai_simpeg_by_id_ajax',
                api_key: esakip.api_key,
                id: idPegawai,
                tahun_anggaran: <?php echo $input['tahun_anggaran']; ?>
            },
            dataType: 'json',
            success: function(response) {
                if (!response.status) {
                    alert('Gagal memuat data pegawai: ' + response.message);
                    jQuery('#wrap-loading').hide();
                    return;
                }

                const data = response.data;
                let message = '';
                let namaSatuanKerja = jQuery('.table_list_pegawai tbody tr[data-id="' + idPegawai + '"] td:nth-child(3)').text();

                // Cek jika ada atasan definitif
                if (data.atasan) {
                    message = 'Pegawai memiliki atasan definitif, tidak bisa diubah!';

                    jQuery('#terapkan-all-satker-container').hide();
                    jQuery('#nama-pegawai-atasan-container').hide();
                    jQuery('#nama-pegawai-atasan-teks-container').show();

                    jQuery('#nama-pegawai-atasan').val('').trigger('change');
                    const opt = formatPegawaiOption(data.atasan);

                    jQuery('#nama-pegawai-atasan-teks').val(`${opt.label}`);

                    // Jika tidak ada atasan definitif, izinkan pemilihan manual
                } else {
                    message = 'Atasan definitif tidak ditemukan. Silakan pilih atasan pengganti.';
                    jQuery('#terapkan-all-satker-container').show();
                    jQuery('#nama-pegawai-atasan-container').show();
                    jQuery('#nama-pegawai-atasan-teks-container').hide();

                    jQuery('#nama-pegawai-atasan-teks').val('');

                    let pegawaiOptions = '<option value="">-- Pilih Atasan --</option>';

                    // Cek flag dari PHP untuk memunculkan opsi Kepala Daerah
                    if (data.show_kepala_daerah_option) {
                        pegawaiOptions += `<option value="0"><?php echo $nama_kepala_daerah; ?> (Kepala Daerah) | <?php echo $status_jabatan_kepala_daerah; ?></option>`;
                    }

                    // Tentukan satker_id untuk dropdown
                    let satkerIdDropDown;
                    const is_kepala_atau_plt = (data.tipe_pegawai_id == "11" || data.plt_plh == "1");

                    if (is_kepala_atau_plt && data.satker_id.length > 2) {
                        satkerIdDropDown = data.satker_id.slice(0, -2);
                    } else {
                        satkerIdDropDown = data.satker_id;
                    }

                    getPegawaiBySatkerId(satkerIdDropDown)
                        .done(function(pegawaiResponse) {
                            if (pegawaiResponse.status && pegawaiResponse.data.length > 0) {
                                pegawaiResponse.data.forEach(function(pegawai) {
                                    const opt = formatPegawaiOption(pegawai, data.id);
                                    if (!opt) return;

                                    pegawaiOptions += `
                                        <option value="${opt.value}">
                                            ${opt.label}
                                        </option>
                                    `;
                                });
                            }
                            jQuery('#nama-pegawai-atasan').html(pegawaiOptions).select2({
                                placeholder: '-- Pilih Pegawai Atasan --',
                                dropdownParent: jQuery('#modal-edit-pegawai .modal-body'),
                                width: '100%'
                            });

                            // Set value berdasarkan atasan custom yang sudah tersimpan
                            if (data.atasan_custom) {
                                jQuery('#nama-pegawai-atasan').val(data.atasan_custom.id).trigger('change');
                            } else if (data.is_kepala_daerah_atasan) {
                                // Jika tidak ada atasan custom tapi seharusnya Kepala Daerah
                                jQuery('#nama-pegawai-atasan').val('0').trigger('change');
                            }

                        })
                        .fail(function(xhr) {
                            console.error(xhr.responseText);
                            alert('Terjadi kesalahan saat memuat daftar calon atasan!');
                        });
                }

                jQuery('#id-pegawai').val(data.id);
                jQuery('#nama-pegawai').val(data.nama_pegawai);
                jQuery('#nama-jabatan-pegawai-custom').val(
                    data.custom_jabatan || (data.jabatan?.trim() + ' ' + namaSatuanKerja?.trim())
                );
                jQuery('#nama-satker').val(namaSatuanKerja);
                jQuery('#nama-satker-info').text(namaSatuanKerja);
                jQuery('#info-atasan-pegawai').text(message);
                jQuery('#format_halaman_kedua').val(data.format_halaman_kedua);
                jQuery('#terapkan-all-satker').prop('checked', false);

                jQuery('#plt-plh-teks-container').hide();
                jQuery('#plt-plh-teks').val('');
                jQuery('#plt-plh-teks').prop('required', false);
                if (data.plt_plh == "1") {
                    jQuery('#plt-plh-teks-container').show();
                    jQuery('#plt-plh-teks').prop('required', true);
                    jQuery('#plt-plh-teks').val(data.plt_plh_teks);
                }

                jQuery('#wrap-loading').hide();
                jQuery('#modal-edit-pegawai').modal('show');
            },
            error: function(xhr) {
                jQuery('#wrap-loading').hide();
                console.error(xhr.responseText);
                alert('Terjadi kesalahan fatal saat memuat data pegawai!');
            }
        });
    }

    function formatPegawaiOption(pegawai, currentUserId = null) {
        // Hindari menampilkan diri sendiri
        if (currentUserId && pegawai.id == currentUserId) {
            return null;
        }

        // ===== JABATAN =====
        let jabatan = '-';

        if (pegawai.custom_jabatan && pegawai.custom_jabatan.trim() !== '') {
            jabatan = pegawai.custom_jabatan.trim();
        } else {
            const jab = (pegawai.jabatan || '').trim();
            const bidang = (pegawai.nama_bidang || '').trim();
            jabatan = `${jab} ${bidang}`.trim();
        }

        if (pegawai.plt_plh === "1" && pegawai.plt_plh_teks) {
            jabatan = `${pegawai.plt_plh_teks.trim()} ${jabatan}`;
        }

        // ===== NAMA =====
        const namaLengkap = [
            (pegawai.gelar_depan || '').trim(),
            (pegawai.nama_pegawai || '').trim(),
            (pegawai.gelar_belakang || '').trim()
        ].filter(Boolean).join(' ');

        const nip = (pegawai.nip_baru || '').trim();

        return {
            value: pegawai.id,
            label: `${namaLengkap} (${nip}) | ${jabatan}`
        };
    }


    function getPegawaiBySatkerId(satkerId) {
        return jQuery.ajax({
            url: esakip.url,
            type: 'POST',
            data: {
                action: 'get_data_pegawai_simpeg_by_satker_id_ajax',
                api_key: esakip.api_key,
                satker_id: satkerId,
                tahun_anggaran: <?php echo $input['tahun_anggaran']; ?>
            },
            dataType: 'json'
        });
    }

    function handleUpdatePegawai() {
        let idPegawai = jQuery('#id-pegawai').val();
        let idPegawaiAtasan = jQuery('#nama-pegawai-atasan').val();
        let namaJabatanCustom = jQuery('#nama-jabatan-pegawai-custom').val();
        let plt_plh_teks = jQuery('#plt-plh-teks').val();
        let format_halaman_kedua = jQuery('#format_halaman_kedua').val();
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
                action: 'update_atasan_pegawai_ajax',
                api_key: esakip.api_key,
                id_pegawai: idPegawai,
                id_atasan: idPegawaiAtasan,
                jabatan_custom: namaJabatanCustom,
                terapkan_all_satker: terapkanAllSatker,
                format_halaman_kedua: format_halaman_kedua,
                plt_plh_teks: plt_plh_teks,
                tahun_anggaran: <?php echo $input['tahun_anggaran']; ?>
            },
            dataType: 'json',
            success: function(response) {
                jQuery('#wrap-loading').hide();
                if (response.status) {
                    alert(response.message);
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