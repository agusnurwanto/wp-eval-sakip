<style>
    /* CSS Scoped khusus agar tidak merusak layout bawaan WordPress */
    .sakip-container {
        font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        color: #333;
        background-color: #f4f7f6;
        padding: 20px;
        border-radius: 8px;
        margin: 20px 0;
    }
    .sakip-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #0d6efd;
        padding: 15px 25px;
        border-radius: 8px 8px 0 0;
        color: #fff;
    }
    .sakip-logo {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: bold;
        font-size: 1.1rem;
    }
    .sakip-nav {
        display: flex;
        align-items: center;
        gap: 20px;
    }
    .sakip-nav a {
        color: rgba(255,255,255,0.8);
        text-decoration: none;
        font-size: 0.95rem;
    }
    .sakip-nav a.active, .sakip-nav a:hover {
        color: #fff;
        font-weight: 600;
    }
    .sakip-nav .btn-masuk {
        background: #00c0ef;
        padding: 6px 15px;
        border-radius: 4px;
        color: #fff !important;
    }
    .sakip-hero {
        text-align: center;
        padding: 40px 20px;
        background: #eef5f9;
    }
    .sakip-hero .sub-title {
        color: #0d6efd;
        text-transform: uppercase;
        font-size: 0.85rem;
        font-weight: bold;
        letter-spacing: 1px;
    }
    .sakip-hero h1 {
        font-size: 2.2rem;
        margin: 10px 0;
        color: #2b3a4a;
    }
    .sakip-hero p {
        color: #6c757d;
        max-width: 600px;
        margin: 0 auto;
    }
    .sakip-layout {
        display: flex;
        gap: 25px;
        margin-top: 25px;
    }
    .sakip-sidebar {
        width: 280px;
        flex-shrink: 0;
    }
    .menu-group {
        background: #fff;
        border-radius: 6px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.04);
        margin-bottom: 15px;
        overflow: hidden;
    }
    .menu-group h3 {
        font-size: 0.95rem;
        padding: 12px 15px;
        margin: 0;
        background: #f8f9fa;
        border-bottom: 1px solid #edf2f7;
        color: #495057;
    }
    .menu-group ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .menu-item {
        padding: 10px 15px;
        font-size: 0.9rem;
        cursor: pointer;
        color: #616e7c;
        border-left: 3px solid transparent;
        transition: all 0.2s;
    }
    .menu-item:hover {
        background: #f1f5f9;
        color: #0d6efd;
    }
    .menu-item.active {
        background: #e6f0ff;
        color: #0d6efd;
        font-weight: 600;
        border-left-color: #0d6efd;
    }
    .sakip-main-table {
        flex-grow: 1;
    }
    .table-card {
        background: #fff;
        border-radius: 6px;
        padding: 25px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.04);
    }
    .table-card h2 {
        font-size: 1.3rem;
        margin-top: 0;
        margin-bottom: 20px;
        color: #2b3a4a;
    }
    .table-toolbar {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
    }
    .form-select, .search-box input {
        padding: 8px 12px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        outline: none;
        font-size: 0.9rem;
    }
    .search-box {
        flex-grow: 1;
    }
    .search-box input {
        width: 100%;
        box-sizing: border-box;
    }
    .table-responsive {
        overflow-x: auto;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        font-size: 0.9rem;
    }
    th {
        background: #0d6efd;
        color: #fff;
        padding: 12px;
        font-weight: 500;
    }
    td {
        padding: 12px;
        border-bottom: 1px solid #eff2f5;
        color: #495057;
    }
    tr:hover td {
        background: #f8fafc;
    }
    .btn-view {
        display: inline-block;
        padding: 4px 10px;
        background: #e6f0ff;
        color: #0d6efd;
        text-decoration: none;
        border-radius: 4px;
        font-size: 0.85rem;
        font-weight: 500;
    }
    .btn-view:hover {
        background: #0d6efd;
        color: #fff;
    }
    .table-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
        padding-top: 15px;
        border-top: 1px solid #eff2f5;
        font-size: 0.85rem;
        color: #6c757d;
    }
    .pagination {
        display: flex;
        gap: 5px;
    }
    .page-num {
        padding: 5px 10px;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        cursor: pointer;
    }
    .page-num.active, .page-num:hover {
        background: #0d6efd;
        color: #fff;
        border-color: #0d6efd;
    }
    .sakip-footer {
        margin-top: 25px;
        text-align: center;
        font-size: 0.85rem;
        color: #a0aec0;
    }
    .sakip-footer a {
        color: #718096;
        text-decoration: none;
    }

    /* Responsif Mobile */
    @media (max-width: 768px) {
        .sakip-layout {
            flex-direction: column;
        }
        .sakip-sidebar {
            width: 100%;
        }
        .sakip-header {
            flex-direction: column;
            gap: 10px;
        }
        .table-toolbar {
            flex-direction: column;
        }
    }
    .dataTables_wrapper .dataTables_filter {
        margin-right: 2px;
    }
    
    #tabel-dinamis-sakip_length label,
    #tabel-dinamis-sakip_filter label {
        display: inline-flex !important;
        align-items: center !important;
        gap: 8px;
        white-space: nowrap;
    }
    
    #tabel-dinamis-sakip_length select,
    #tabel-dinamis-sakip_filter input {
        width: auto !important;
        display: inline-block !important;
    }
    
    #tabel-dinamis-sakip_length, 
    #tabel-dinamis-sakip_filter {
        margin-bottom: 15px !important;
    }
</style>
<div class="sakip-container">
    <!-- Konten Utama -->
    <main class="sakip-content">
        <div class="sakip-hero">
            <h1>Laporan Kinerja</h1>
            <p>Proses pemilihan dan pengembangan tindakan yang terbaik dan menguntungkan mencapai tujuan.</p>
        </div>

        <div class="sakip-layout">
            <!-- Sidebar Menu Kiri -->
            <aside class="sakip-sidebar">
                <div class="menu-group">
                    <h3>Perencanaan</h3>
                    <ul>
                        <li class="menu-item" data-target="jnis-plan">🔗 Pedoman Teknis Perencanaan</li>
                        <li class="menu-item" data-target="rpjpd">🔗 RPJPD</li>
                        <li class="menu-item" data-target="pohon">📄 Pohon Kinerja</li>
                        <li class="menu-item active" data-target="rpjmd">📄 RPJMD / RENSTRA</li>
                        <li class="menu-item" data-target="rkpd">📄 RKPD / RENJA</li>
                        <li class="menu-item" data-target="iku">📄 Indikator Kinerja Utama</li>
                        <li class="menu-item" data-target="pk">📄 Perjanjian Kinerja</li>
                        <li class="menu-item" data-target="ra">📄 Rencana Aksi</li>
                    </ul>
                </div>
                <div class="menu-group">
                    <h3>Pengukuran</h3>
                    <ul>
                        <li class="menu-item" data-target="monev">📄 Monev Pengukuran</li>
                        <li class="menu-item" data-target="pko">📄 Penilaian Kinerja Organisasi</li>
                        <li class="menu-item" data-target="pkop">📄 Penilaian Kinerja Organisasi Periodik</li>
                        <li class="menu-item" data-target="jnis-ukur">🔗 Pedoman Teknis Pengukuran</li>
                    </ul>
                </div>
                <div class="menu-group">
                    <h3>Pelaporan</h3>
                    <ul>
                        <li class="menu-item" data-target="lkj">📄 Laporan Kinerja</li>
                    </ul>
                </div>
                <div class="menu-group">
                    <h3>Evaluasi</h3>
                    <ul>
                        <li class="menu-item" data-target="lhe">🔗 LHE AKIP Internal</li>
                        <li class="menu-item" data-target="tl-lhe">📄 TL LHE AKIP Internal</li>
                        <li class="menu-item" data-target="jnis-eval">🔗 Pedoman Teknis Evaluasi</li>
                    </ul>
                </div>
            </aside>

            <!-- Area Tabel Kanan -->
            <section class="sakip-main-table">
                <div class="table-card">
                    <h2 id="dynamic-table-title" data-id="rpjmd">RPJMD / RENSTRA</h2>
                    <div class="table-toolbar row align-items-center mb-3">
                        <label for="dynamic-periode" class="col-md-3 text-md-end mb-0 text-right label-dynamic-periode">Tahun</label>
                        <div class="col-md-6">
                            <select id="dynamic-periode" class="form-select" onchange="getTableSakipAjax();"></select>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="tabel-dinamis-sakip" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="label-dynamic-periode" style="max-width: 250px">Tahun</th>
                                    <th>Perangkat Daerah</th>
                                    <th>Data</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </section>
        </div>
    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const menuItems = document.querySelectorAll('.menu-item');
    const tableTitle = document.getElementById('dynamic-table-title');
    const tableSection = document.querySelector('.sakip-main-table');

    menuItems.forEach(item => {
        item.addEventListener('click', function() {
            menuItems.forEach(i => i.classList.remove('active'));
            this.classList.add('active');
            const menuText = this.innerText.replace(/[^\w\s\/]/g, '').trim(); 
            tableTitle.innerText = menuText;
            var target = jQuery(this).attr('data-target');
            jQuery(tableTitle).attr('data-id', target);

            tableSection.scrollIntoView({ 
                behavior: 'smooth', 
                block: 'start' 
            });
            getTableSakip(target);
        });
    });

});

jQuery(document).ready(function(){
    const urlParams = new URLSearchParams(window.location.search);
    let targetMenu = jQuery('.menu-item[data-target="rpjmd"');
    if (urlParams.get('tab') === 'pengukuran') {
        targetMenu = jQuery('.menu-item[data-target="monev"]');
    }else if (urlParams.get('tab') === 'pelaporan') {
        targetMenu = jQuery('.menu-item[data-target="lkj"]');
    }else if (urlParams.get('tab') === 'evaluasi') {
        targetMenu = jQuery('.menu-item[data-target="lhe"]');
    }
    getjadwal(function(){
        targetMenu.click();
    });
});

function getjadwal(cb){
    jQuery('#wrap-loading').show();
    jQuery.ajax({
        url: esakip.url,
        type: 'POST',
        dataType: 'json',
        data: {
            action: 'get_jadwal_sakip'
        },
        success: function(response) {
            window.jadwal_sakip = response;
            jQuery('#wrap-loading').hide();
            cb();
        }
    });
}

function getTableSakip(target) {
    const label = jQuery('.label-dynamic-periode');
    const select = document.getElementById('dynamic-periode');
    const data = window.jadwal_sakip;
    select.innerHTML = '';

    if (['rpjmd', 'iku', 'pohon'].includes(target)) {
        label.text('Periode');
        data.periode.forEach(item => {
            const option = document.createElement('option');
            option.value = item.id; // Or specific ID field
            option.textContent = `${item.nama_jadwal} (${item.tahun_anggaran} - ${item.tahun_selesai_anggaran})`;
            select.appendChild(option);
        });
    } else {
        label.text('Tahun');
        data.tahun_anggaran.forEach(item => {
            const option = document.createElement('option');
            option.value = item.tahun_anggaran;
            option.textContent = item.tahun_anggaran;
            select.appendChild(option);
        });
    }
    getTableSakipAjax();
}

function getTableSakipAjax() {
    const slug = document.getElementById('dynamic-table-title').getAttribute('data-id');
    const periode = document.getElementById('dynamic-periode');
    const text_periode = periode.options[periode.selectedIndex].text;
    const $loading = jQuery('#wrap-loading');

    if (jQuery.fn.DataTable.isDataTable('#tabel-dinamis-sakip')) {
        jQuery('#tabel-dinamis-sakip').DataTable().destroy();
    }

    jQuery('#tabel-dinamis-sakip').DataTable({
        "processing": false, // Kita gunakan custom loader
        "serverSide": false,
        "ordering": false,
        "ajax": {
            "url": esakip.url,
            "type": "POST",
            beforeSend: function() {
                $loading.show();
            },
            "data": {
                "action": "get_data_sakip_publik",
                "target": slug,
                "periode": periode.value
            },
            "dataSrc": function(json) {
                $loading.hide();
                return json.data || [];
            },
            "error": function() {
                $loading.hide();
                alert('Gagal mengambil data.');
            }
        },
        "columns": [
            { 
                "data": "periode",
                "render": function(data, type, row) {
                    return text_periode;
                }
            },
            { 
                "data": "perangkat_daerah",
                "render": function(data, type, row) {
                    return data.toUpperCase();
                }
            },
            { "data": "data" }
        ],
        "drawCallback": function(settings) {
            $loading.hide();
        }
    });
}
</script>