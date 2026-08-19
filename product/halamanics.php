<?php include_once "auth_check.php"; ?>
<!DOCTYPE html>
<html lang="en">
  <?php 
    include "header.php"; // call sectionhead.php as library
  ?>
  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <?php 
          // include "koneksi.php"; // Call koneksi.php as connection to DB
          include "template_menu.php"; // Call template_menu.php as nav bar menu
        ?>
        
        <!-- Top Nav / Breadcrumb -->
        <?php if (isset($idsection) && $idsection == 3) { ?>
            <div class="top_nav">
                <div class="nav_menu repair-top-nav">
                    <div class="nav toggle">
                      <a id="menu_toggle" title="Toggle Sidebar"><i class="fa fa-bars"></i></a>
                    </div>
                    <div class="repair-top-nav-breadcrumb">
                        <i class="fa fa-clipboard" style="color: #9ca3af; font-size: 16px;"></i>
                        <span class="app-name">Integrated Chitra System</span>
                        <span class="repair-breadcrumb-separator">/</span>
                        <span class="current">Dashboard</span>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <!-- Top nav lama untuk divisi lain -->
            <div class="top_nav">
                <div class="nav_menu">
                    <div class="nav toggle">
                      <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                    </div>
                  <li class="nav navbar-nav navbar-left"><h3 style="">Integrated Chitra System</h3></li>
                </div>
            </div>
        <?php } ?>
        <!-- page content --> 
        <?php if($name!=""){ ?>
            <div class="right_col" role="main">
                    <div class="">
                    <div class="page-title">
                        <div class="title_right">
                          <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                            <div class="input-group">
                            </div>
                          </div>
                        </div>
                      </div>
    
                      <div class="clearfix"></div>

                      <?php if ((int)$idsection == 3) {
                        $repairDashboardYear = isset($tahun) ? (int)$tahun : (int)date('Y');
                        if ($repairDashboardYear < 2000) {
                          $repairDashboardYear = (int)date('Y');
                        }

                        $repairStatusConfig = array(
                          'waiting' => array(
                            'db_status' => 'w/ work_order',
                            'label' => 'Waiting WO',
                            'class' => 'waiting',
                            'color' => '#f5b000'
                          ),
                          'progress' => array(
                            'db_status' => 'Progress',
                            'label' => 'In Progress',
                            'class' => 'in-progress',
                            'color' => '#4f9df7'
                          ),
                          'complete' => array(
                            'db_status' => 'Complete',
                            'label' => 'Complete',
                            'class' => 'complete',
                            'color' => '#10d878'
                          ),
                          'reject' => array(
                            'db_status' => 'Reject',
                            'label' => 'Reject',
                            'class' => 'reject',
                            'color' => '#ff6268'
                          )
                        );
                        $repairStatusCounts = array('waiting' => 0, 'progress' => 0, 'complete' => 0, 'reject' => 0);
                        $repairStatusMap = array(
                          'w/ work_order' => 'waiting',
                          'progress' => 'progress',
                          'in progress' => 'progress',
                          'complete' => 'complete',
                          'reject' => 'reject'
                        );

                        $repairStatusQuery = mysqli_query($koneksi3, "
                          SELECT status, COUNT(*) AS total
                          FROM work_order
                          WHERE received_date LIKE '" . $repairDashboardYear . "%'
                          GROUP BY status
                        ");
                        while ($repairStatusRow = mysqli_fetch_assoc($repairStatusQuery)) {
                          $repairStatusKey = strtolower(trim((string)$repairStatusRow['status']));
                          if (isset($repairStatusMap[$repairStatusKey])) {
                            $repairStatusCounts[$repairStatusMap[$repairStatusKey]] = (int)$repairStatusRow['total'];
                          }
                        }
                        $repairStatusTotal = array_sum($repairStatusCounts);

                        $repairDayNames = array('Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab');
                        $repairMonthNames = array(1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des');

                        $repairWeeklyLabels = array();
                        $repairWeeklyCounts = array();
                        $repairWeeklyIndex = array();

                        // tes minggu lain
                        // $repairWeekStart = '2026-03-30';
                        // $repairWeekEnd = '2026-04-03';
                        // $repairWeekEndExclusive = '2026-04-04';

                        $repairWeekStart = date('Y-m-d', strtotime('-6 days'));
                        $repairWeekEnd = date('Y-m-d');
                        $repairWeekEndExclusive = date('Y-m-d', strtotime('+1 day'));

                        $startDay = date('j', strtotime($repairWeekStart));
                        $startMonth = $repairMonthNames[(int)date('n', strtotime($repairWeekStart))];
                        $endDay = date('j', strtotime($repairWeekEnd));
                        $endMonth = $repairMonthNames[(int)date('n', strtotime($repairWeekEnd))];

                        if ($startMonth === $endMonth) {
                            $repairWeeklyRangeText = $startDay . ' - ' . $endDay . ' ' . $startMonth;
                        } else {
                            $repairWeeklyRangeText = $startDay . ' ' . $startMonth . ' - ' . $endDay . ' ' . $endMonth;
                        }

                        $currentDate = $repairWeekStart;
                        while ($currentDate <= $repairWeekEnd) {
                          $repairWeeklyIndex[$currentDate] = count($repairWeeklyLabels);
                          $repairWeeklyLabels[] = $repairDayNames[(int)date('w', strtotime($currentDate))];
                          $repairWeeklyCounts[] = 0;
                          $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
                        }

                        $repairWeeklyQuery = mysqli_query($koneksi3, "
                          SELECT DATE(received_date) AS received_day, COUNT(*) AS total
                          FROM work_order
                          WHERE received_date >= '" . $repairWeekStart . "'
                          AND received_date < '" . $repairWeekEndExclusive . "'
                          GROUP BY DATE(received_date)
                        ");
                        while ($repairWeeklyRow = mysqli_fetch_assoc($repairWeeklyQuery)) {
                          $repairReceivedDay = $repairWeeklyRow['received_day'];
                          if (isset($repairWeeklyIndex[$repairReceivedDay])) {
                            $repairWeeklyCounts[$repairWeeklyIndex[$repairReceivedDay]] = (int)$repairWeeklyRow['total'];
                          }
                        }

                        $repairMonthNames = array(1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des');
                        $repairMonthLimit = $repairDashboardYear === (int)date('Y') ? (int)date('n') : 12;
                        $repairMonthlyLabels = array();
                        $repairMonthlyTotal = array();
                        $repairMonthlyComplete = array();

                        for ($repairMonth = 1; $repairMonth <= $repairMonthLimit; $repairMonth++) {
                          $repairMonthlyLabels[] = $repairMonthNames[$repairMonth];
                          $repairMonthlyTotal[$repairMonth] = 0;
                          $repairMonthlyComplete[$repairMonth] = 0;
                        }

                        $repairMonthlyQuery = mysqli_query($koneksi3, "
                          SELECT
                            MONTH(received_date) AS month_no,
                            COUNT(*) AS total,
                            SUM(CASE WHEN LOWER(TRIM(status)) = 'complete' THEN 1 ELSE 0 END) AS complete_total
                          FROM work_order
                          WHERE received_date LIKE '" . $repairDashboardYear . "%'
                          GROUP BY MONTH(received_date)
                        ");
                        while ($repairMonthlyRow = mysqli_fetch_assoc($repairMonthlyQuery)) {
                          $repairMonthNo = (int)$repairMonthlyRow['month_no'];
                          if ($repairMonthNo >= 1 && $repairMonthNo <= $repairMonthLimit) {
                            $repairMonthlyTotal[$repairMonthNo] = (int)$repairMonthlyRow['total'];
                            $repairMonthlyComplete[$repairMonthNo] = (int)$repairMonthlyRow['complete_total'];
                          }
                        }

                        $repairRecentWorkOrders = array();
                        $repairQuery = mysqli_query($koneksi3, "
                          SELECT id_wo, wo, customer, site, received_date, wo_date, status
                          FROM work_order
                          ORDER BY received_date DESC, id_wo DESC
                          LIMIT 5
                        ");

                        while ($repairRow = mysqli_fetch_assoc($repairQuery)) {
                          $repairRecentWorkOrders[] = $repairRow;
                        }

                        function repair_dashboard_status_meta($status) {
                          $normalized = strtolower(trim((string)$status));

                          if ($normalized === 'complete') {
                            return array('label' => 'Complete', 'class' => 'repair-status-complete');
                          }

                          if ($normalized === 'progress' || $normalized === 'in progress') {
                            return array('label' => 'In Progress', 'class' => 'repair-status-progress');
                          }

                          if ($normalized === 'w/ work_order') {
                            return array('label' => 'Waiting WO', 'class' => 'repair-status-waiting');
                          }

                          if ($normalized === 'reject') {
                            return array('label' => 'Reject', 'class' => 'repair-status-reject');
                          }

                          return array('label' => trim((string)$status) !== '' ? (string)$status : '-', 'class' => 'repair-status-default');
                        }

                        $repairHour = (int)date('G');
                        if ($repairHour < 12) {
                          $repairGreeting = 'Selamat Pagi';
                        } elseif ($repairHour < 17) {
                          $repairGreeting = 'Selamat Siang';
                        } else {
                          $repairGreeting = 'Selamat Malam';
                        }

                        $repairFullDayNames = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
                        $repairFullMonthNames = array(1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember');
                        $repairTodayText = $repairFullDayNames[(int)date('w')] . ', ' . date('j') . ' ' . $repairFullMonthNames[(int)date('n')] . ' ' . date('Y');
                        $repairUserName = $user['name'] ?? $name ?? 'User';
                        $repairUserSn = $user['sn'] ?? '';
                        $repairUserDisplay = $repairUserName . (trim((string)$repairUserSn) !== '' ? ' - ' . $repairUserSn : '');
                        $repairLevelNames = array(1 => 'Admin', 2 => 'Staff', 3 => 'Managerial', 910 => 'Super Admin');
                        $repairUserLevel = isset($user['level']) ? (int)$user['level'] : 0;
                        $repairUserLevelName = isset($repairLevelNames[$repairUserLevel]) ? $repairLevelNames[$repairUserLevel] : 'User';
                      ?>
                      <div class="repair-dashboard">
                        <div class="repair-card repair-user-card">
                          <div class="repair-user-content">
                            <div class="repair-user-intro">
                              <p class="repair-user-greeting"><?php echo htmlspecialchars($repairGreeting); ?>, <?php echo htmlspecialchars($repairUserLevelName); ?></p>
                              <h2 class="repair-user-name"><?php echo htmlspecialchars($repairUserName); ?></h2>
                              <p class="repair-user-subtitle"><?php echo htmlspecialchars($repairTodayText); ?> &nbsp;&middot;&nbsp; Integrated Chitra System</p>
                            </div>
                            <div class="repair-user-meta">
                              <span class="repair-user-meta-dot" aria-hidden="true"></span>
                              <div class="repair-user-meta-body">
                                <p class="repair-user-meta-name"><?php echo htmlspecialchars($repairUserDisplay); ?></p>
                                <div class="repair-user-meta-row">
                                  <span class="repair-user-meta-label">Department</span>
                                  <span class="repair-user-meta-value"><?php echo htmlspecialchars($user['department'] ?? '-'); ?></span>
                                </div>
                                <div class="repair-user-meta-row">
                                  <span class="repair-user-meta-label">Section</span>
                                  <span class="repair-user-meta-value"><?php echo htmlspecialchars($user['section'] ?? '-'); ?></span>
                                </div>
                                <div class="repair-user-meta-row">
                                  <span class="repair-user-meta-label">Level</span>
                                  <span class="repair-user-meta-value"><?php echo htmlspecialchars($user['level'] ?? '-'); ?></span>
                                </div>
                                <div class="repair-user-meta-row">
                                  <span class="repair-user-meta-label">Email</span>
                                  <span class="repair-user-meta-value-email"><?php echo htmlspecialchars($user['email'] ?? '-'); ?></span>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="repair-card">
                          <div class="repair-status-summary">
                            <div class="repair-status-head">
                              <div>
                                <h2 class="repair-card-title">Status Work Order</h2>
                                <p class="repair-card-subtitle"><?php echo htmlspecialchars($repairDashboardYear); ?></p>
                              </div>
                              <span class="repair-status-total"><?php echo htmlspecialchars($repairStatusTotal); ?> total</span>
                            </div>

                            <div class="repair-status-grid">
                              <?php foreach ($repairStatusConfig as $repairStatusKey => $repairStatusItem) { ?>
                                <div class="repair-status-box <?php echo htmlspecialchars($repairStatusItem['class']); ?>">
                                  <strong><?php echo htmlspecialchars($repairStatusCounts[$repairStatusKey]); ?></strong>
                                  <span><?php echo htmlspecialchars($repairStatusItem['label']); ?></span>
                                </div>
                              <?php } ?>
                            </div>

                            <div class="repair-stack-bar" aria-label="Status Work Order">
                              <?php if ($repairStatusTotal > 0) { ?>
                                <?php foreach ($repairStatusConfig as $repairStatusKey => $repairStatusItem) {
                                  $repairStatusPercent = ($repairStatusCounts[$repairStatusKey] / $repairStatusTotal) * 100;
                                ?>
                                  <div
                                    class="repair-stack-segment"
                                    title="<?php echo htmlspecialchars($repairStatusItem['label']); ?>: <?php echo htmlspecialchars($repairStatusCounts[$repairStatusKey]); ?>"
                                    style="width: <?php echo htmlspecialchars(number_format($repairStatusPercent, 4, '.', '')); ?>%; background: <?php echo htmlspecialchars($repairStatusItem['color']); ?>;"
                                  ></div>
                                <?php } ?>
                              <?php } else { ?>
                                <div class="repair-stack-segment is-empty"></div>
                              <?php } ?>
                            </div>

                            <div class="repair-legend">
                              <?php foreach ($repairStatusConfig as $repairStatusKey => $repairStatusItem) {
                                $repairStatusPercent = $repairStatusTotal > 0 ? round(($repairStatusCounts[$repairStatusKey] / $repairStatusTotal) * 100) : 0;
                              ?>
                                <span class="repair-legend-item">
                                  <span class="repair-legend-dot" style="background: <?php echo htmlspecialchars($repairStatusItem['color']); ?>;"></span>
                                  <?php echo htmlspecialchars($repairStatusItem['label']); ?> (<?php echo htmlspecialchars($repairStatusPercent); ?>%)
                                </span>
                              <?php } ?>
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-md-5 col-sm-12 col-xs-12">
                            <div class="repair-card repair-chart-card">
                              <div class="repair-chart-head">
                                <div>
                                  <h2 class="repair-card-title">Work Order 7 Hari Terakhir</h2>
                                  <p class="repair-card-subtitle">Jumlah WO per hari</p>
                                </div>
                                <span class="repair-chart-pill pill-blue"><?php echo htmlspecialchars($repairWeeklyRangeText); ?></span>
                              </div>
                              <div class="repair-chart-wrap">
                                <canvas id="repairWeeklyChart"></canvas>
                              </div>
                            </div>
                          </div>

                          <div class="col-md-7 col-sm-12 col-xs-12">
                            <div class="repair-card repair-chart-card">
                              <div class="repair-chart-head">
                                <div>
                                  <h2 class="repair-card-title">Tren Work Order Bulanan</h2>
                                  <p class="repair-card-subtitle">Total vs Selesai</p>
                                </div>
                                <span class="repair-chart-pill pill-green"><?php echo htmlspecialchars($repairMonthlyLabels[0]); ?> - <?php echo htmlspecialchars($repairMonthlyLabels[count($repairMonthlyLabels) - 1]); ?> <?php echo htmlspecialchars($repairDashboardYear); ?></span>
                              </div>
                              <div class="repair-chart-wrap">
                                <canvas id="repairMonthlyTrendChart"></canvas>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-md-8 col-sm-12 col-xs-12">
                            <div class="repair-card">
                              <div class="repair-card-header">
                                <div>
                                  <h2 class="repair-card-title">Work Order Terbaru</h2>
                                  <p class="repair-card-subtitle">Data terbaru berdasarkan tanggal masuk ban/work order.</p>
                                </div>
                                <a class="repair-view-all" href="repair_halamanwo.php">Lihat Semua <i class="fa fa-arrow-right"></i></a>
                              </div>

                              <?php if (count($repairRecentWorkOrders) > 0) { ?>
                                <div class="repair-table-responsive">
                                  <table class="table repair-table">
                                    <thead>
                                      <tr>
                                        <th>Nomor WO</th>
                                        <th>Customer &middot; Site</th>
                                        <th>Rcv_Date</th>
                                        <th>Status</th>
                                        <th class="text-center">Detail</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <?php foreach ($repairRecentWorkOrders as $repairWorkOrder) {
                                        $repairHasWoNumber = trim((string)$repairWorkOrder['wo']) !== '';
                                        $repairWoNumber = $repairHasWoNumber ? $repairWorkOrder['wo'] : 'Belum ada WO';
                                        $repairDate = trim((string)$repairWorkOrder['received_date']) !== '' ? $repairWorkOrder['received_date'] : '-';
                                        $repairStatus = repair_dashboard_status_meta($repairWorkOrder['status']);
                                      ?>
                                        <tr>
                                          <td>
                                            <div class="repair-wo-cell">
                                              <!-- Ikon repair disimpan dulu; belum ada fungsi khusus untuk dashboard terbaru. -->
                                              <!-- <span class="repair-wo-icon"><i class="fa fa-wrench"></i></span> -->
                                              <span class="repair-wo-number-stack">
                                                <?php if ($repairHasWoNumber) { ?>
                                                  <span><?php echo htmlspecialchars($repairWoNumber); ?></span>
                                                <?php } else { ?>
                                                  <span class="repair-wo-missing"><?php echo htmlspecialchars($repairWoNumber); ?></span>
                                                  <span class="repair-wo-ref">ID: <?php echo htmlspecialchars($repairWorkOrder['id_wo']); ?></span>
                                                <?php } ?>
                                              </span>
                                            </div>
                                          </td>
                                          <td>
                                            <?php echo htmlspecialchars($repairWorkOrder['customer'] ?: '-'); ?><br>
                                            <span class="repair-muted"><?php echo htmlspecialchars($repairWorkOrder['site'] ?: '-'); ?></span>
                                          </td>
                                          <td class="repair-muted"><?php echo htmlspecialchars($repairDate); ?></td>
                                          <td>
                                            <span class="repair-status-badge <?php echo htmlspecialchars($repairStatus['class']); ?>">
                                              <?php echo htmlspecialchars($repairStatus['label']); ?>
                                            </span>
                                          </td>
                                          <td class="text-center">
                                            <?php if ($repairHasWoNumber) { ?>
                                              <a class="repair-detail-link" href="repair_jobcard.php?id=<?php echo urlencode($repairWorkOrder['id_wo']); ?>" title="Detail Work Order">
                                                <i class="fa fa-external-link"></i>
                                              </a>
                                            <?php } else { ?>
                                              <span class="repair-detail-empty" aria-hidden="true">-</span>
                                            <?php } ?>
                                          </td>
                                        </tr>
                                      <?php } ?>
                                    </tbody>
                                  </table>
                                </div>
                              <?php } else { ?>
                                <div class="repair-empty">Belum ada work order terbaru.</div>
                              <?php } ?>
                            </div>
                          </div>

                          <div class="col-md-4 col-sm-12 col-xs-12">
                            <div class="repair-card">
                              <div class="repair-card-header">
                                <div>
                                  <h2 class="repair-card-title">Akses Cepat</h2>
                                  <p class="repair-card-subtitle">Shortcut menu Central Services</p>
                                </div>
                              </div>
                              <div class="repair-shortcuts">
                                <a class="repair-shortcut shortcut-blue" href="repair_halamanwo.php">
                                  <i class="fa fa-clipboard"></i>
                                  <strong>All Work Order</strong>
                                  <span>Daftar semua work order</span>
                                </a>
                                <a class="repair-shortcut shortcut-purple" href="repair_halamanjobdata.php">
                                  <i class="fa fa-file-text-o"></i>
                                  <strong>Raw Data Report</strong>
                                  <span>Laporan data mentah repair</span>
                                </a>
                                <a class="repair-shortcut shortcut-orange" href="https://workshop.chitraparatama.com/">
                                  <i class="fa fa-calendar"></i>
                                  <strong>Scheduling</strong>
                                  <span>Jadwal perbaikan ban</span>
                                </a>
                                <a class="repair-shortcut shortcut-green" href="halamanrmiprice.php">
                                  <i class="fa fa-wrench"></i>
                                  <strong>Raw Material</strong>
                                  <span>Raw Material Index</span>
                                </a>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <?php } else { ?>
                      <div class="row">
                        <div class="col-md-12">
                          <div class="x_panel">
                            <div class="x_title">
                              <h2>Welcome User</h2>
    
                              <ul class="nav navbar-right panel_toolbox">
                            </ul>
                            <div class="clearfix"></div>
                          </div>
                          <!-- untuk nama user di welcome -->
                          <div class="x_content">
                            <div class="dashboard-widget-content">
                              <!-- query manggil session php di halaman beranda -->
                              <ul class="list-unstyled timeline widget">
                                <li>
                                  <div class="block">
                                    <div class="block_content">
                                      <h2 class="title"><a><?php echo $user['name'];?> - <?php echo $user['sn'];?> </a></h2>
                                      <div class="byline">
                                        <span>Department</span> : <a><?php echo $user['department'];?></a> <br>
                                        <span>Section</span> : <a><?php echo $user['section'];?></a> <br>
                                        <span>Level</span> : <a><?php echo $user['level'];?></a> <br>
                                        <span>Email</span> : <a><?php echo $user['email'];?></a>
                                      </div>
                                    </div>
                                  </div>
                                </li>
                                <!-- untuk ambil data php session dari proses login -->
                              </ul>   
                              <div class="clearfix"></div>
                            </div>
                                
                            <div class="x_content">
                              <div class="row">  
                              </div>
                            </div>
                          </div>
                      </div>
                      <?php } ?>
        <?php } ?>
        <div class="clearfix"></div>
        
      </div>
    </div>
    <!-- /footer content -->
    <script src="codebase/dhtmlx.js"></script>
       <!-- jQuery -->
    <script src="../vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="../vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="../vendors/nprogress/nprogress.js"></script>
    <!-- iCheck -->
    <script src="../vendors/iCheck/icheck.min.js"></script>
    <!-- Datatables -->
    <script src="../vendors/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="../vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="../vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="../vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
    <script src="../vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="../vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="../vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="../vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
    <script src="../vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
    <script src="../vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="../vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
    <script src="../vendors/datatables.net-scroller/js/datatables.scroller.min.js"></script>
    <script src="../vendors/jszip/dist/jszip.min.js"></script>
    <script src="../vendors/pdfmake/build/pdfmake.min.js"></script>
    <script src="../vendors/pdfmake/build/vfs_fonts.js"></script>
    <script src="../vendors/js/jquery.min.js"></script>
    <script src="../vendors/js/bootstrap.min.js"></script>
    <script src="../vendors/js/docs.min.js"></script>
    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>

    <?php if (isset($idsection) && (int)$idsection == 3) { ?>
    <script>
      if (!window.Chart) {
        document.write('<script src="../vendors/Chart.js/dist/Chart.min.js"><\/script>');
      }
    </script>
    <script>
      (function() {
        if (!window.Chart) {
          return;
        }

        var weeklyLabels = <?php echo json_encode($repairWeeklyLabels); ?>;
        var weeklyCounts = <?php echo json_encode($repairWeeklyCounts); ?>;
        var monthlyLabels = <?php echo json_encode($repairMonthlyLabels); ?>;
        var monthlyTotal = <?php echo json_encode(array_values($repairMonthlyTotal)); ?>;
        var monthlyComplete = <?php echo json_encode(array_values($repairMonthlyComplete)); ?>;
        var gridColor = '#edf1f5';
        var tickColor = '#8a94a6';
        var teal = '#1a6b8a';
        var softLine = '#cbd5e1';

        Chart.defaults.global.defaultFontFamily = 'Arial, Verdana, sans-serif';
        Chart.defaults.global.defaultFontColor = tickColor;

        var weeklyCanvas = document.getElementById('repairWeeklyChart');
        if (weeklyCanvas) {
          new Chart(weeklyCanvas.getContext('2d'), {
            type: 'bar',
            data: {
              labels: weeklyLabels,
              datasets: [{
                label: 'Work Order',
                data: weeklyCounts,
                backgroundColor: teal,
                borderColor: teal,
                borderWidth: 0
              }]
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              legend: {
                display: false
              },
              tooltips: {
                backgroundColor: 'rgba(15, 23, 42, 0.9)',
                cornerRadius: 6,
                displayColors: false
              },
              scales: {
                xAxes: [{
                  gridLines: {
                    color: gridColor,
                    drawBorder: false,
                    borderDash: [3, 3]
                  },
                  ticks: {
                    fontColor: tickColor
                  },
                  barPercentage: 0.45,
                  categoryPercentage: 0.7
                }],
                yAxes: [{
                  ticks: {
                    beginAtZero: true,
                    precision: 0,
                    fontColor: tickColor
                  },
                  gridLines: {
                    color: gridColor,
                    drawBorder: false,
                    borderDash: [3, 3]
                  }
                }]
              }
            }
          });
        }

        var monthlyCanvas = document.getElementById('repairMonthlyTrendChart');
        if (monthlyCanvas) {
          new Chart(monthlyCanvas.getContext('2d'), {
            type: 'line',
            data: {
              labels: monthlyLabels,
              datasets: [{
                label: 'Total WO',
                data: monthlyTotal,
                borderColor: softLine,
                backgroundColor: 'rgba(203, 213, 225, 0)',
                borderWidth: 2,
                pointRadius: 0,
                lineTension: 0.25
              }, {
                label: 'Selesai',
                data: monthlyComplete,
                borderColor: teal,
                backgroundColor: 'rgba(26, 107, 138, 0)',
                borderWidth: 2,
                pointBackgroundColor: teal,
                pointBorderColor: teal,
                pointRadius: 4,
                pointHoverRadius: 5,
                lineTension: 0.25
              }]
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              legend: {
                position: 'bottom',
                labels: {
                  boxWidth: 18,
                  fontColor: tickColor,
                  padding: 14
                }
              },
              tooltips: {
                backgroundColor: 'rgba(15, 23, 42, 0.9)',
                cornerRadius: 6
              },
              scales: {
                xAxes: [{
                  gridLines: {
                    color: gridColor,
                    drawBorder: false,
                    borderDash: [3, 3]
                  },
                  ticks: {
                    fontColor: tickColor
                  }
                }],
                yAxes: [{
                  ticks: {
                    beginAtZero: true,
                    precision: 0,
                    fontColor: tickColor
                  },
                  gridLines: {
                    color: gridColor,
                    drawBorder: false,
                    borderDash: [3, 3]
                  }
                }]
              }
            }
          });
        }
      })();
    </script>
    <?php } ?>

    <!-- JS pop up modal -->
    <script>
      $(document).ready(function(){
          $('[data-toggle="popover"]').popover();   
      });
    </script>
    <script>
      //Buat layout utama
      var myLayout = new dhtmlXLayoutObject({
        parent: "layoutObj",
        pattern: "2U",
        offsets: {
            top: 0,
            right: 0,
            bottom: 0,
            left: 0
        },
        cells: [
          {id: "a", text: "Summary"},
          {id: "b", text: " "}
        ]
      }); 
      //Grid dengan mengambil data dari database
      var myGrid = myLayout.cells("a").attachGrid();  
      myGrid.setHeader("Customer, Size, Unit qty,Forecast qty");
      myGrid.attachHeader("#select_filter,#select_filter,,Total : {#stat_total}");
      myGrid.setColTypes("ro,ro,ro,ro");
      myGrid.init();  
      //Chart pada layout kanan (b)
      var myChart = myLayout.cells("b").attachChart({
        view: "bar", //bar,pie,line
        color: "#66ccff",
        gradient: "3d",
        value: "#data3#", //#data0# -> kolom pertama grid 
        label: "#data3#", //#data1# -> kolom kedua grid
        tooltip: "#data0#,#data1#, #data3#", //info ketika mouse over
        width: 30,
        origin: 0,
        yAxis: {
          title: "Tire Forecast",
          start: 0,
          step: 500,
          end: 4500
        },
        xAxis: {
          title: "Size",
          template: "#data1#"
        }
      });

      //Integrasi Grid & Chart
      function refresh_func() {
        myChart.clearAll();
        myChart.parse(myGrid, "dhtmlxgrid");
      }

      //Event saat memuat data ke grid & perubahan(filter)
      myGrid.load("grid.php", refresh_func);
      myGrid.attachEvent("onGridReconstructed", refresh_func);
    </script>
    <script>
      var theme = {
          color: [
              '#26B99A', '#34495E', '#BDC3C7', '#3498DB',
              '#9B59B6', '#8abb6f', '#759c6a', '#bfd3b7'
          ],

          title: {
              itemGap: 8,
              textStyle: {
                  fontWeight: 'normal',
                  color: '#408829'
              }
          },

          dataRange: {
              color: ['#1f610a', '#97b58d']
          },

          toolbox: {
              color: ['#408829', '#408829', '#408829', '#408829']
          },

          tooltip: {
              backgroundColor: 'rgba(0,0,0,0.5)',
              axisPointer: {
                  type: 'line',
                  lineStyle: {
                      color: '#408829',
                      type: 'dashed'
                  },
                  crossStyle: {
                      color: '#408829'
                  },
                  shadowStyle: {
                      color: 'rgba(200,200,200,0.3)'
                  }
              }
          },

          dataZoom: {
              dataBackgroundColor: '#eee',
              fillerColor: 'rgba(64,136,41,0.2)',
              handleColor: '#408829'
          },
          grid: {
              borderWidth: 0
          },

          categoryAxis: {
              axisLine: {
                  lineStyle: {
                      color: '#408829'
                  }
              },
              splitLine: {
                  lineStyle: {
                      color: ['#eee']
                  }
              }
          },

          valueAxis: {
              axisLine: {
                  lineStyle: {
                      color: '#408829'
                  }
              },
              splitArea: {
                  show: true,
                  areaStyle: {
                      color: ['rgba(250,250,250,0.1)', 'rgba(200,200,200,0.1)']
                  }
              },
              splitLine: {
                  lineStyle: {
                      color: ['#eee']
                  }
              }
          },
          timeline: {
              lineStyle: {
                  color: '#408829'
              },
              controlStyle: {
                  normal: {color: '#408829'},
                  emphasis: {color: '#408829'}
              }
          },

          k: {
              itemStyle: {
                  normal: {
                      color: '#68a54a',
                      color0: '#a9cba2',
                      lineStyle: {
                          width: 1,
                          color: '#408829',
                          color0: '#86b379'
                      }
                  }
              }
          },
          map: {
              itemStyle: {
                  normal: {
                      areaStyle: {
                          color: '#ddd'
                      },
                      label: {
                          textStyle: {
                              color: '#c12e34'
                          }
                      }
                  },
                  emphasis: {
                      areaStyle: {
                          color: '#99d2dd'
                      },
                      label: {
                          textStyle: {
                              color: '#c12e34'
                          }
                      }
                  }
              }
          },
          force: {
              itemStyle: {
                  normal: {
                      linkStyle: {
                          strokeColor: '#408829'
                      }
                  }
              }
          },
          chord: {
              padding: 4,
              itemStyle: {
                  normal: {
                      lineStyle: {
                          width: 1,
                          color: 'rgba(128, 128, 128, 0.5)'
                      },
                      chordStyle: {
                          lineStyle: {
                              width: 1,
                              color: 'rgba(128, 128, 128, 0.5)'
                          }
                      }
                  },
                  emphasis: {
                      lineStyle: {
                          width: 1,
                          color: 'rgba(128, 128, 128, 0.5)'
                      },
                      chordStyle: {
                          lineStyle: {
                              width: 1,
                              color: 'rgba(128, 128, 128, 0.5)'
                          }
                      }
                  }
              }
          },
          gauge: {
              startAngle: 225,
              endAngle: -45,
              axisLine: {
                  show: true,
                  lineStyle: {
                      color: [[0.2, '#86b379'], [0.8, '#68a54a'], [1, '#408829']],
                      width: 8
                  }
              },
              axisTick: {
                  splitNumber: 10,
                  length: 12,
                  lineStyle: {
                      color: 'auto'
                  }
              },
              axisLabel: {
                  textStyle: {
                      color: 'auto'
                  }
              },
              splitLine: {
                  length: 18,
                  lineStyle: {
                      color: 'auto'
                  }
              },
              pointer: {
                  length: '90%',
                  color: 'auto'
              },
              title: {
                  textStyle: {
                      color: '#333'
                  }
              },
              detail: {
                  textStyle: {
                      color: 'auto'
                  }
              }
          },
          textStyle: {
              fontFamily: 'Arial, Verdana, sans-serif'
          }
        };
      $(document).ready(function() {
        $('#example').DataTable( {
          dom: "Bfrtip",
          buttons: [
            {extend: "copy",className: "btn-sm"},
            {extend: "csv",className: "btn-sm"},
            {extend: "print",className: "btn-sm"},
          ],
          paging: false,
          initComplete: function () {
            this.api().columns([0, 1]).every( function () {
                var column = this;
                var select = $('<select><option value=""></option></select>')
                    .appendTo( $(column.header()) )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                          $(this).val()
                        ); 
                        column
                        .search( val ? '^'+val+'$' : '', true, false )
                        .draw();
                    } ); 
                column.data().unique().sort().each( function ( d, j ) {
                  select.append( '<option value="'+d+'">'+d+'</option>' )
                });
            } );
          },
          footerCallback: function ( row, data, start, end, display ) {
                var api = this.api(), data; 
                // converting to interger to find total
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };
     
                // computing column Total of the complete result 
                // var wedTotal = api
                //         .column( 3 )
                //         .data()
                //         .reduce( function (a, b) {
                //             return intVal(a) + intVal(b);
                //         }, 0 );

                // var Total1 = api
                //         .column( 4, { page: 'current'} )
                //         .data()
                //         .reduce( function (a, b) {
                //             return intVal(a) + intVal(b);
                //         }, 0 ); 

                // var total = api
                //         .column( 4 )
                //         .data()
                //         .reduce( function (a, b) {
                //             return intVal(a) + intVal(b);
                //         }, 0 );

                var Total2 = api
                        .column( 2, { page: 'current'} )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 ); 
            
                // Update footer by showing the total with the reference of the column index 
                $( api.column( 0 ).footer() ).html('Total');
                $( api.column( 2 ).footer() ).html(Total2);
          }
        });
      });
    </script>
  </body>
</html>
