<?php
$title = 'Dashboard';
include __DIR__ . '/fragments/header.php';
include __DIR__ . '/fragments/navbar.php';
include __DIR__ . '/fragments/sidebar.php';
?>

<style type="text/css">
  /* Chart.js */
  @keyframes chartjs-render-animation {
    from {
      opacity: .99
    }

    to {
      opacity: 1
    }
  }

  .chartjs-render-monitor {
    animation: chartjs-render-animation 1ms
  }

  .chartjs-size-monitor,
  .chartjs-size-monitor-expand,
  .chartjs-size-monitor-shrink {
    position: absolute;
    direction: ltr;
    left: 0;
    top: 0;
    right: 0;
    bottom: 0;
    overflow: hidden;
    pointer-events: none;
    visibility: hidden;
    z-index: -1
  }

  .chartjs-size-monitor-expand>div {
    position: absolute;
    width: 1000000px;
    height: 1000000px;
    left: 0;
    top: 0
  }

  .chartjs-size-monitor-shrink>div {
    position: absolute;
    width: 200%;
    height: 200%;
    left: 0;
    top: 0
  }
</style>
</head>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Admin HomePage</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">Home</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">

      <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Jumlah Anggota</span>
              <span class="info-box-number">
                <?php echo isset($totalAnggota) ? $totalAnggota : 'Data tidak tersedia'; ?>
              </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box mb-3">
            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-book"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Jumlah Buku</span>
              <span class="info-box-number"><?php echo isset($totalBuku) ? $totalBuku : 'Data tidak tersedia'; ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix hidden-md-up"></div>

        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box mb-3">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-shopping-cart"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total Peminjaman</span>
              <span class="info-box-number">
                <?php echo isset($totalPeminjaman) ? $totalPeminjaman : 'Data tidak tersedia'; ?>
              </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box mb-3">
            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-exchange-alt"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Belum Dikembalikan</span>
              <span
                class="info-box-number"><?php echo isset($belumDikembalikan) ? $belumDikembalikan : 'Data tidak tersedia'; ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
      </div>

      <div class="row">
        <!-- Chart Section -->
        <div class="col-md-6">
          <!-- LINE CHART -->
          <div class="card card-info">
            <div class="card-header">
              <h3 class="card-title">Jumlah Peminjaman per Bulan</h3>

              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
            <div class="card-body">
              <div class="chart">
                <canvas id="lineChartPeminjaman"
                  style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 487px;"
                  width="487" height="250" class="chartjs-render-monitor"></canvas>
              </div>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->

          <div class="card">
            <div class="card-header border-0">
              <h3 class="card-title">5 Buku Terbaru</h3>
              <div class="card-tools">
                <a href="#" class="btn btn-tool btn-sm">
                  <i class="fas fa-download"></i>
                </a>
                <a href="#" class="btn btn-tool btn-sm">
                  <i class="fas fa-bars"></i>
                </a>
              </div>
            </div>
            <div class="card-body table-responsive p-0">
              <table class="table table-striped table-valign-middle">
                <thead>
                  <tr>
                    <th>Judul</th>
                    <th>Penulis</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach (isset($bukuTerbaru) ? $bukuTerbaru : [] as $buku) { ?>
                    <tr>
                      <td><?php echo $buku['Judul']; ?></td>
                      <td><?php echo $buku['Penulis']; ?></td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>

          <div class="card">
            <div class="card-header border-0">
              <h3 class="card-title">5 Anggota Terbaru</h3>
              <div class="card-tools">
                <a href="#" class="btn btn-tool btn-sm">
                  <i class="fas fa-download"></i>
                </a>
                <a href="#" class="btn btn-tool btn-sm">
                  <i class="fas fa-bars"></i>
                </a>
              </div>
            </div>
            <div class="card-body table-responsive p-0">
              <table class="table table-striped table-valign-middle">
                <thead>
                  <tr>
                    <th>Nama</th>
                    <th>No Telepon</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach (isset($anggotaTerbaru) ? $anggotaTerbaru : [] as $anggota) { ?>
                    <tr>
                      <td><?php echo $anggota['Nama']; ?></td>
                      <td><?php echo $anggota['No_Telepon']; ?></td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>

          <div class="card">
            <div class="card-header border-0">
              <h3 class="card-title">5 Transaksi Peminjaman Terbaru</h3>
              <div class="card-tools">
                <a href="#" class="btn btn-tool btn-sm">
                  <i class="fas fa-download"></i>
                </a>
                <a href="#" class="btn btn-tool btn-sm">
                  <i class="fas fa-bars"></i>
                </a>
              </div>
            </div>
            <div class="card-body table-responsive p-0">
              <table class="table table-striped table-valign-middle">
                <thead>
                  <tr>
                    <th>NIM Anggota</th>
                    <th>ISBN Buku</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach (isset($peminjamanTerbaru) ? $peminjamanTerbaru : [] as $peminjaman) { ?>
                    <tr>
                      <td><?php echo $peminjaman['NIM_Anggota']; ?></td>
                      <td><?php echo $peminjaman['ISBN_Buku']; ?></td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>

          <div class="card">
            <div class="card-header border-0">
              <h3 class="card-title">5 Buku dengan Stok Terendah</h3>
              <div class="card-tools">
                <a href="#" class="btn btn-tool btn-sm">
                  <i class="fas fa-download"></i>
                </a>
                <a href="#" class="btn btn-tool btn-sm">
                  <i class="fas fa-bars"></i>
                </a>
              </div>
            </div>
            <div class="card-body table-responsive p-0">
              <table class="table table-striped table-valign-middle">
                <thead>
                  <tr>
                    <th>Judul</th>
                    <th>Jumlah Tersedia</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach (isset($bukuStokTerendah) ? $bukuStokTerendah : [] as $buku) { ?>
                    <tr>
                      <td><?php echo $buku['Judul']; ?></td>
                      <td><?php echo $buku['Jumlah_Tersedia']; ?></td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>

        </div>

        <div class="col-md-6">
          <!-- LINE CHART -->
          <div class="card card-info">
            <div class="card-header">
              <h3 class="card-title">Total Denda per Bulan</h3>

              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
            <div class="card-body">
              <div class="chart">
                <canvas id="lineChartDenda"
                  style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 487px;"
                  width="487" height="250" class="chartjs-render-monitor"></canvas>
              </div>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->

          <div class="card">
            <div class="card-header border-0">
              <h3 class="card-title">5 Pengembalian Terbaru</h3>
              <div class="card-tools">
                <a href="#" class="btn btn-tool btn-sm">
                  <i class="fas fa-download"></i>
                </a>
                <a href="#" class="btn btn-tool btn-sm">
                  <i class="fas fa-bars"></i>
                </a>
              </div>
            </div>
            <div class="card-body table-responsive p-0">
              <table class="table table-striped table-valign-middle">
                <thead>
                  <tr>
                    <th>ID Peminjaman</th>
                    <th>Denda</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach (isset($pengembalianTerbaru) ? $pengembalianTerbaru : [] as $pengembalian) { ?>
                    <tr>
                      <td><?php echo $pengembalian['ID_Peminjaman']; ?></td>
                      <td><?php echo $pengembalian['Denda']; ?></td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>

          <div class="card">
            <div class="card-header border-0">
              <h3 class="card-title">Buku yang Tidak Tersedia</h3>
              <div class="card-tools">
                <a href="#" class="btn btn-tool btn-sm">
                  <i class="fas fa-download"></i>
                </a>
                <a href="#" class="btn btn-tool btn-sm">
                  <i class="fas fa-bars"></i>
                </a>
              </div>
            </div>
            <div class="card-body table-responsive p-0">
              <table class="table table-striped table-valign-middle">
                <thead>
                  <tr>
                    <th>Judul</th>
                    <th>Penulis</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach (isset($bukuTidakTersedia) ? $bukuTidakTersedia : [] as $buku) { ?>
                    <tr>
                      <td><?php echo $buku['Judul']; ?></td>
                      <td><?php echo $buku['Penulis']; ?></td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>

          <div class="card">
            <div class="card-header border-0">
              <h3 class="card-title">5 Anggota dengan Transaksi Terbanyak</h3>
              <div class="card-tools">
                <a href="#" class="btn btn-tool btn-sm">
                  <i class="fas fa-download"></i>
                </a>
                <a href="#" class="btn btn-tool btn-sm">
                  <i class="fas fa-bars"></i>
                </a>
              </div>
            </div>
            <div class="card-body table-responsive p-0">
              <table class="table table-striped table-valign-middle">
                <thead>
                  <tr>
                    <th>Nama</th>
                    <th>Jumlah Transaksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach (isset($anggotaTerbanyak) ? $anggotaTerbanyak : [] as $anggota) { ?>
                    <tr>
                      <td><?php echo $anggota['Nama']; ?></td>
                      <td><?php echo $anggota['Jumlah_Transaksi']; ?></td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->



<?php
include __DIR__ . '/fragments/control_sidebar.php';
include __DIR__ . '/fragments/footer.php';
?>

<!-- ChartJs -->
<script src="../src/views/admin/templates/plugins/chart.js/Chart.min.js"></script>

<!-- Script Khusus Halaman Dashboard -->
<script>
  $(document).ready(function () {
    var currentPath = window.location.pathname;

    $('.nav-sidebar a').each(function () {
      var linkPath = $(this).attr('href');

      if (currentPath === linkPath) {

        $(this).addClass('active');
        $(this).parents('.nav-item').addClass('menu-open');
        $(this).parents('.nav-treeview').prev('.nav-link').addClass('active');
      }
    });

    // Data untuk chart peminjaman
    const labels = <?php echo json_encode($labels); ?>;
    const dataPeminjaman = <?php echo json_encode($dataPeminjaman); ?>;

    // Chart.js Line Chart untuk Peminjaman
    const ctxPeminjaman = document.getElementById('lineChartPeminjaman').getContext('2d');
    new Chart(ctxPeminjaman, {
      type: 'line',
      data: {
        labels: labels,
        datasets: [{
          label: 'Jumlah Peminjaman',
          data: dataPeminjaman,
          backgroundColor: 'rgba(75, 192, 192, 0.2)',
          borderColor: 'rgba(75, 192, 192, 1)',
          borderWidth: 2
        }]
      },
      options: {
        scales: {
          x: {
            title: {
              display: true,
              text: 'Bulan'
            }
          },
          y: {
            title: {
              display: true,
              text: 'Jumlah Peminjaman'
            },
            beginAtZero: true
          }
        }
      }
    });

    // Data untuk chart denda
    const dataDenda = <?php echo json_encode($dataDenda); ?>;

    // Chart.js Line Chart untuk Denda
    const ctxDenda = document.getElementById('lineChartDenda').getContext('2d');
    new Chart(ctxDenda, {
      type: 'line',
      data: {
        labels: labels,
        datasets: [{
          label: 'Total Denda',
          data: dataDenda,
          backgroundColor: 'rgba(255, 99, 132, 0.2)',
          borderColor: 'rgba(255, 99, 132, 1)',
          borderWidth: 2
        }]
      },
      options: {
        scales: {
          x: {
            title: {
              display: true,
              text: 'Bulan'
            }
          },
          y: {
            title: {
              display: true,
              text: 'Total Denda'
            },
            beginAtZero: true
          }
        }
      }
    });
  });
</script>
</body>

</html>