<?php
session_start();
include "koneksi.php";

// --- LOGIKA FILTER TAHUN ---
// 1. Ambil daftar tahun unik dari database untuk dropdown
$queryYears = "SELECT DISTINCT YEAR(price_date) as tahun FROM material_price ORDER BY tahun DESC";
$resYears = mysqli_query($koneksi, $queryYears);
$listTahun = [];
while ($y = mysqli_fetch_assoc($resYears)) {
    $listTahun[] = $y['tahun'];
}

// 2. Tangkap tahun yang dipilih user (default ke tahun terbaru atau 'All')
if (isset($_GET['filter_tahun'])) {
    $selectedYear = $_GET['filter_tahun'];
} else {
    $selectedYear = date('Y');
}

// 3. Bangun Query dengan Filter
$whereClause = "";
// Jika $selectedYear kosong (user pilih 'Semua Tahun'), WHERE clause tetap kosong
if ($selectedYear != "") {
    $whereClause = " WHERE YEAR(price_date) = '" . mysqli_real_escape_string($koneksi, $selectedYear) . "'";
}

$query = "SELECT * FROM material_price $whereClause ORDER BY price_date ASC, material_name ASC";
$result = mysqli_query($koneksi, $query);

$query2 = "SELECT DISTINCT(material_name) as material ,source FROM `material_price` ORDER BY `material_price`.`source` DESC;";
$result2 = mysqli_query($koneksi, $query2);
// --- END LOGIKA FILTER ---

$dataHasil = [];
$dataSorce = [];
$labels = [];
$temp_data_mineral = [];
$temp_data_material = [];
$material_info = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $dataHasil[] = $row;
        $m_name = $row['material_name'];
        $p_date = $row['price_date'];
        $m_type = strtolower($row['material_type']);

        if (!in_array($p_date, $labels)) {
            $labels[] = $p_date;
        }

        if ($m_type == 'mineral') {
            $temp_data_mineral[$m_name][$p_date] = (float)$row['material_price'];
        } else {
            $temp_data_material[$m_name][$p_date] = (float)$row['material_price'];
        }

        if (!isset($material_info[$m_name])) {
            $material_info[$m_name] = $row['material_currency'] . "/" . $row['material_unit'];
        }
    }
}

$colors = [
    '#e6194b',
    '#3cb44b',
    '#ffe119',
    '#4363d8',
    '#f58231',
    '#911eb4',
    '#46f0f0',
    '#f032e6',
    '#bcf60c',
    '#fabebe',
    '#008080',
    '#e6beff',
    '#9a6324',
    '#fffac8',
    '#800000',
    '#aaffc3'
];

function buildDataset($temp_data, $labels, $material_info, $colors)
{
    $datasets = [];
    $colorIndex = 0;
    foreach ($temp_data as $name => $values) {
        $chartData = [];
        foreach ($labels as $date) {
            $chartData[] = isset($values[$date]) ? $values[$date] : null;
        }
        $currentColor = $colors[$colorIndex % count($colors)];
        $datasets[] = [
            'label' => $name . " (" . $material_info[$name] . ")",
            'data' => $chartData,
            'borderColor' => $currentColor,
            'backgroundColor' => 'transparent',
            'tension' => 0.3,
            'hidden' => true,
            'borderWidth' => 2,
            'pointRadius' => 3
        ];
        $colorIndex++;
    }
    return $datasets;
}

$datasetsMineral = buildDataset($temp_data_mineral, $labels, $material_info, $colors);
$datasetsMaterial = buildDataset($temp_data_material, $labels, $material_info, $colors);
?>

<!DOCTYPE html>
<html lang="en">
<?php include "header.php"; ?>
<style>
    .material-filter {
        max-height: 200px;
        overflow-y: auto;
        border: 1px solid #e0e0e0;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 10px;
        background: #f9f9f9;
    }

    .filter-item {
        display: inline-flex;
        align-items: center;
        width: 230px;
        margin-bottom: 8px;
        cursor: pointer;
        font-size: 13px;
    }

    .filter-item input {
        margin-right: 10px;
    }

    .color-box {
        width: 12px;
        height: 12px;
        display: inline-block;
        margin-right: 8px;
        border-radius: 2px;
    }

    .chart-container {
        margin-bottom: 50px;
        padding: 20px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    /* Tambahan style untuk form filter */
    .year-filter-box {
        background: #fff;
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 8px;
        border: 1px solid #ddd;
    }
</style>

<body class="nav-md">
    <div class="container body">
        <div class="main_container">
            <?php include "template_menu.php"; ?>

            <div class="right_col" role="main">

                <div class="x_panel">
                    <div class="x_content">
                        <form method="GET" action="" class="form-inline">
                            <div class="form-group">
                                <label for="filter_tahun" style="margin-right: 10px;">Filter Berdasarkan Tahun: </label>
                                <select name="filter_tahun" id="filter_tahun" class="form-control" onchange="this.form.submit()">
                                    <?php foreach ($listTahun as $th): ?>
                                        <option value="<?= $th ?>" <?= ($selectedYear == $th) ? 'selected' : '' ?>>
                                            <?= $th ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <noscript><input type="submit" value="Filter"></noscript>
                        </form>
                    </div>
                </div>

                <div class="x_panel">
                    <div class="x_title">
                        <h2><i class="fa fa-line-chart"></i> Mineral Price <?= $selectedYear ? "($selectedYear)" : "(Semua)" ?></h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="material-filter">
                            <?php $idx = 0;
                            foreach ($temp_data_mineral as $name => $v): $c = $colors[$idx % count($colors)]; ?>
                                <label class="filter-item">
                                    <input type="checkbox" class="mineral-toggle" data-index="<?= $idx ?>">
                                    <span class="color-box" style="background-color: <?= $c ?>;"></span>
                                    <?= htmlspecialchars($name) ?>
                                </label>
                            <?php $idx++;
                            endforeach; ?>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <canvas id="mineralChart" style="width:100%; height:350px;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="x_panel">
                    <div class="x_title">
                        <h2><i class="fa fa-line-chart"></i> Raw Material Index <?= $selectedYear ? "($selectedYear)" : "(Semua)" ?></h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="material-filter">
                            <?php $idx = 0;
                            foreach ($temp_data_material as $name => $v): $c = $colors[$idx % count($colors)]; ?>
                                <label class="filter-item">
                                    <input type="checkbox" class="material-toggle" data-index="<?= $idx ?>">
                                    <span class="color-box" style="background-color: <?= $c ?>;"></span>
                                    <?= htmlspecialchars($name) ?>
                                </label>
                            <?php $idx++;
                            endforeach; ?>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <canvas id="materialChart" style="width:100%; height:350px;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="x_panel">
                    <div class="x_title">
                        <h2>Data Detail</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="table-responsive">
                        <table id="datatable-buttons" class="table table-striped table-bordered">
                            <thead>
                                <tr style="background:#f5f5f5;">
                                    <th>ID</th>
                                    <th>Material Name</th>
                                    <th>Type</th>
                                    <th>Price</th>
                                    <th>Unit</th>
                                    <th>Currency</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($dataHasil as $row): ?>
                                    <tr>
                                        <td><?= $row['id'] ?></td>
                                        <td><?= htmlspecialchars($row['material_name']) ?></td>
                                        <td><span class="badge"><?= htmlspecialchars($row['material_type']) ?></span></td>
                                        <td><?= number_format($row['material_price'], 2) ?></td>
                                        <td><?= htmlspecialchars($row['material_unit']) ?></td>
                                        <td><?= htmlspecialchars($row['material_currency']) ?></td>
                                        <td><?= $row['price_date'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Data Source</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="table-responsive">
                        <?php
                        $querySource = "SELECT DISTINCT material_name as material, source FROM material_price ORDER BY source DESC";
                        $resSource = mysqli_query($koneksi, $querySource);

                        if (!$resSource) {
                            echo "Error: " . mysqli_error($koneksi);
                        } elseif (mysqli_num_rows($resSource) == 0) {
                            echo "<p>Tidak ada data sumber tersedia.</p>";
                        } else {
                        ?>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr style="background:#f5f5f5;">
                                        <th>Material Name</th>
                                        <th>Source</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($rowSrc = mysqli_fetch_assoc($resSource)): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($rowSrc['material']) ?></td>
                                            <td><?= htmlspecialchars($rowSrc['source'] ?: '-') ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../vendors/jquery/dist/jquery.min.js"></script>
    <script src="../vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../vendors/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="../vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

    <!-- DataTables Buttons -->
    <script src="../vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="../vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
    <script src="../vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="../vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="../vendors/jszip/dist/jszip.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        $(document).ready(function() {
            const commonOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        title: {
                            display: true,
                            text: 'Price'
                        }
                    }
                }
            };

            const ctxMineral = document.getElementById('mineralChart').getContext('2d');
            const mineralChart = new Chart(ctxMineral, {
                type: 'line',
                data: {
                    labels: <?= json_encode($labels); ?>,
                    datasets: <?= json_encode($datasetsMineral); ?>
                },
                options: commonOptions
            });

            const ctxMaterial = document.getElementById('materialChart').getContext('2d');
            const materialChart = new Chart(ctxMaterial, {
                type: 'line',
                data: {
                    labels: <?= json_encode($labels); ?>,
                    datasets: <?= json_encode($datasetsMaterial); ?>
                },
                options: commonOptions
            });

            $('.mineral-toggle').on('change', function() {
                const index = $(this).data('index');
                this.checked ? mineralChart.show(index) : mineralChart.hide(index);
            });

            $('.material-toggle').on('change', function() {
                const index = $(this).data('index');
                this.checked ? materialChart.show(index) : materialChart.hide(index);
            });

            if ($("#datatable-buttons").length) {
                $("#datatable-buttons").DataTable({
                    dom: "Bfrtip",
                    buttons: [{
                        extend: "excelHtml5",
                        text: "Export Excel",
                        className: "btn-sm btn-primary"
                    }],
                    order: [
                        [6, "desc"]
                    ]
                });
            }
        });
    </script>
</body>

</html>