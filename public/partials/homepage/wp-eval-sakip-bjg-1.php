<?php
// If this file is called directly, abort.
if (! defined('WPINC')) {
    die;
}
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<style>
    .wp-eval-sakip-bjg-1 .hero-section {
        background: linear-gradient(135deg, #0056b3 0%, #002d62 100%);
        color: white;
        padding: 80px 0;
        position: relative;
        overflow: hidden;
    }
    .wp-eval-sakip-bjg-1 .hero-img {
        max-width: 100%;
        height: auto;
        object-fit: contain;
    }
    .wp-eval-sakip-bjg-1 .card-laporan {
        transition: transform 0.3s, box-shadow 0.3s;
        border: none;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }
    .wp-eval-sakip-bjg-1 .card-laporan:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    .wp-eval-sakip-bjg-1 .icon-box {
        font-size: 2.5rem;
        color: #0056b3;
        margin-bottom: 15px;
    }
    .wp-eval-sakip-bjg-1 .app-card {
        border: 1px solid #eef2f5;
        transition: all 0.3s;
        text-decoration: none;
        color: initial;
    }
    .wp-eval-sakip-bjg-1 .app-card:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        border-color: #0056b3;
    }
</style>

<div class="wp-eval-sakip-bjg-1">

    <!-- HERO SECTION -->
    <header class="hero-section d-flex align-items-center">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 text-center text-lg-start mb-5 mb-lg-0">
                    <!-- Placeholder Logo SAKIP -->
                    <div class="bg-light text-primary d-inline-block p-3 rounded mb-4 fw-bold fs-3" style="opacity: 0.9;">
                        S A K I P
                    </div>
                    <h1 class="display-5 fw-bold mb-3">Sistem Akuntabilitas Kinerja Instansi Pemerintah</h1>
                    <p class="lead mb-4" style="opacity: 0.85;">
                        Rangkaian sistematik pelaporan kinerja pada instansi pemerintah, dalam rangka pertanggungjawaban dan peningkatan kinerja instansi pemerintah.
                    </p>
                    <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-lg-start">
                        <a href="#" class="btn btn-info text-white px-4 py-2 fw-semibold">Laporan SAKIP <i class="bi bi-arrow-right ms-1"></i></a>
                        <a href="#" class="btn btn-outline-light px-4 py-2">Pohon Kinerja</a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <!-- Placeholder gambar bupati/wakil atau ilustrasi -->
                    <img src="<?php echo get_option('_crb_foto_kepala_daerah'); ?>" alt="Mockup SAKIP" class="hero-img">
                </div>
            </div>
        </div>
    </header>

    <!-- SECTION LAPORAN SAKIP -->
    <section class="py-5 bg-light">
        <div class="container py-4">
            <div class="text-center mb-5">
                <span class="text-primary text-uppercase fw-semibold tracking-wider">Laporan</span>
                <h2 class="fw-bold mt-1">Laporan SAKIP</h2>
                <p class="text-muted mx-auto" style="max-width: 600px;">
                    Laporan Perencanaan Kinerja, Pengukuran Kinerja, Pelaporan Kinerja, dan Evaluasi Kinerja
                </p>
            </div>

            <div class="row g-4">
                <!-- Perencanaan -->
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 p-4 card-laporan text-center">
                        <div class="icon-box"><i class="bi bi-journal-text"></i></div>
                        <h3 class="h5 fw-bold mb-3">Perencanaan</h3>
                        <p class="text-muted small flex-grow-1">Proses pemilihan dan pengembangan tindakan yang terbaik dan menguntungkan mencapai tujuan.</p>
                        <a href="#" class="text-primary text-decoration-none mt-3 fw-semibold small">Lihat Selengkapnya <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
                <!-- Pengukuran -->
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 p-4 card-laporan text-center">
                        <div class="icon-box"><i class="bi bi-speedometer2"></i></div>
                        <h3 class="h5 fw-bold mb-3">Pengukuran</h3>
                        <p class="text-muted small flex-grow-1">Proses di mana organisasi menetapkan parameter hasil untuk dicapai oleh program yang dilakukan.</p>
                        <a href="#" class="text-primary text-decoration-none mt-3 fw-semibold small">Lihat Selengkapnya <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
                <!-- Pelaporan -->
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 p-4 card-laporan text-center">
                        <div class="icon-box"><i class="bi bi-file-earmark-bar-graph"></i></div>
                        <h3 class="h5 fw-bold mb-3">Pelaporan</h3>
                        <p class="text-muted small flex-grow-1">Perbandingan antara target kinerja yang telah ditetapkan dengan realisasinya per triwulan atau tahunan.</p>
                        <a href="#" class="text-primary text-decoration-none mt-3 fw-semibold small">Lihat Selengkapnya <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
                <!-- Evaluasi -->
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 p-4 card-laporan text-center">
                        <div class="icon-box"><i class="bi bi-clipboard-check"></i></div>
                        <h3 class="h5 fw-bold mb-3">Evaluasi</h3>
                        <p class="text-muted small flex-grow-1">Metode dan proses penilaian pelaksanaan tugas unit-unit kerja dalam satu perusahaan atau organisasi.</p>
                        <a href="#" class="text-primary text-decoration-none mt-3 fw-semibold small">Lihat Selengkapnya <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION APLIKASI PENDUKUNG -->
    <section class="py-5">
        <div class="container py-4">
            <div class="text-center mb-5">
                <span class="text-primary text-uppercase fw-semibold tracking-wider">Aplikasi Pendukung</span>
                <h2 class="fw-bold mt-1">Aplikasi Pendukung</h2>
                <p class="text-muted">Aplikasi yang mendukung SAKIP Kabupaten xxx</p>
            </div>

            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-3 justify-content-center">
                <?php echo get_option('_crb_html_aplikasi_pendukung'); ?>
            </div>
        </div>
    </section>

</div>
