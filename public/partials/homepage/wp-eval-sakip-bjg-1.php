<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page - SAKIP</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #0056b3 0%, #002d62 100%);
            color: white;
            padding: 80px 0;
            position: relative;
            overflow: hidden;
        }
        .hero-img {
            max-width: 100%;
            height: auto;
            object-fit: contain;
        }
        .card-laporan {
            transition: transform 0.3s, box-shadow 0.3s;
            border: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .card-laporan:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        .icon-box {
            font-size: 2.5rem;
            color: #0056b3;
            margin-bottom: 15px;
        }
        .app-card {
            border: 1px solid #eef2f5;
            transition: all 0.3s;
            text-decoration: none;
            color: initial;
        }
        .app-card:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            border-color: #0056b3;
        }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-transparent position-absolute w-100" style="z-index: 10;">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center fw-bold" href="#">
                <!-- Ganti src dengan logo pemkab jika ada -->
                <div class="bg-light rounded-circle me-2" style="width: 30px; height: 30px;"></div> 
                Pemerintah Kab. Bojonegoro
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item"><a class="nav-link active" href="#">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Laporan</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Peraturan</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Infografis</a></li>
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-info text-white px-4" href="#">Masuk</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <header class="hero-section d-flex align-items-center pt-5">
        <div class="container pt-5">
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
                    <img src="https://via.placeholder.com/500x400?text=Ilustrasi/Foto+Pejabat" alt="Mockup SAKIP" class="hero-img">
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
                <p class="text-muted">Aplikasi yang mendukung SAKIP Kabupaten Bojonegoro</p>
            </div>

            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-3 justify-content-center">
                <!-- E-Kinerja -->
                <div class="col">
                    <a href="#" class="card h-100 p-3 text-center app-card rounded-3">
                        <div class="p-3 mb-2 bg-light rounded-circle mx-auto d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                            <i class="bi bi-person-workspace text-primary fs-3"></i>
                        </div>
                        <h4 class="h6 fw-bold mb-1">E-Kinerja</h4>
                        <p class="text-muted card-text" style="font-size: 0.75rem;">E-Kinerja Badan Kepegawaian Negara</p>
                    </a>
                </div>
                <!-- e-Monev -->
                <div class="col">
                    <a href="#" class="card h-100 p-3 text-center app-card rounded-3">
                        <div class="p-3 mb-2 bg-light rounded-circle mx-auto d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                            <i class="bi bi-tv text-primary fs-3"></i>
                        </div>
                        <h4 class="h6 fw-bold mb-1">e-Monev</h4>
                        <p class="text-muted card-text" style="font-size: 0.75rem;">Monitoring & Evaluasi Kinerja Anggaran</p>
                    </a>
                </div>
                <!-- e-SAKIP REVIU -->
                <div class="col">
                    <a href="#" class="card h-100 p-3 text-center app-card rounded-3">
                        <div class="p-3 mb-2 bg-light rounded-circle mx-auto d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                            <i class="bi bi-search text-primary fs-3"></i>
                        </div>
                        <h4 class="h6 fw-bold mb-1">e-SAKIP REVIU</h4>
                        <p class="text-muted card-text" style="font-size: 0.75rem;">Kementerian PAN-RB</p>
                    </a>
                </div>
                <!-- Perisai -->
                <div class="col">
                    <a href="#" class="card h-100 p-3 text-center app-card rounded-3">
                        <div class="p-3 mb-2 bg-light rounded-circle mx-auto d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                            <i class="bi bi-shield-check text-primary fs-3"></i>
                        </div>
                        <h4 class="h6 fw-bold mb-1">Perisai</h4>
                        <p class="text-muted card-text" style="font-size: 0.75rem;">Penilaian Mandiri Implementasi AKIP</p>
                    </a>
                </div>
                <!-- Si-Pinter -->
                <div class="col">
                    <a href="#" class="card h-100 p-3 text-center app-card rounded-3">
                        <div class="p-3 mb-2 bg-light rounded-circle mx-auto d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                            <i class="bi bi-cpu text-primary fs-3"></i>
                        </div>
                        <h4 class="h6 fw-bold mb-1">Si-Pinter v.3.7</h4>
                        <p class="text-muted card-text" style="font-size: 0.75rem;">Aplikasi Pengendalian Internal Terintegrasi</p>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-dark text-white py-3 border-top border-secondary">
        <div class="container text-center">
            <p class="mb-0 small">Copyright &copy; 2026 <a href="#" class="text-info text-decoration-none">Pemerintah Kab. Bojonegoro</a>. All Rights Reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>