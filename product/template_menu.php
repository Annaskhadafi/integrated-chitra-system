<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['username'])) {
    if (headers_sent()) {
        echo "<script>window.location.href='login.php';</script>";
    } else {
        header("Location: login.php");
    }
    exit;
}

$current_page = basename($_SERVER['PHP_SELF']); // Hasil: repair_halamanwo.php
$current_loc = isset($_GET['loc']) ? trim((string) $_GET['loc']) : ''; // Hasil: CK-BIB (kalau ada)

// 1. PINDAHAN QUERY: Ambil data user di awal supaya kita tau idsection-nya
include_once "koneksi.php";
$username = isset($_SESSION['username']) ? $_SESSION['username'] : "";
$idsection = 0; // Default

if ($username != "") {
    $perintah = mysqli_query($koneksi, "SELECT * from user a, section b,department c where a.username='$username' and a.section=b.id_section and b.department=c.id_dept");
    $user = mysqli_fetch_array($perintah);
    if ($user) {
        $level = isset($user['level']) ? $user['level'] : 0;
        $id_dept = isset($user['id_department']) ? $user['id_department'] : 0;
        $dept = isset($user['department']) ? $user['department'] : "";
        $name = isset($user['name']) ? $user['name'] : "";
        $idlogin = isset($user['id_user']) ? $user['id_user'] : 0;
        $section = isset($user['section']) ? $user['section'] : "";
        $idsection = isset($user['id_section']) ? $user['id_section'] : 0;
        $idstoreloc = isset($user['id_storeloc']) ? $user['id_storeloc'] : 0;
        
        $lvl = $user['level'] ?? 0;
        $level = $level ?? $lvl;
        $username_display = $user['username'] ?? "User";
    }
}
?>

<!-- 2. PENGAMAN CLASS CSS: Tambah 'is-repair-section' khusus section 3 -->
<div class="col-md-3 left_col <?php echo ($idsection == 3) ? 'is-repair-section' : ''; ?>">
    <div class="left_col scroll-view">
        
        <?php if ($idsection == 3) { ?>
            <!-- 3A. HEADER KHUSUS REPAIR (Desain Baru) -->
            <div class="navbar nav_title" style="border: 0;">
                <a href="halamanics.php" class="ics-brand">
                    <div class="ics-brand-icon">
                        <i class="fa fa-bar-chart"></i>
                    </div>
                    <span>Integrated Chitra Sistem</span>
                </a>
            </div>
            <div class="clearfix"></div>
        <?php } else { ?>
            <!-- 3B. HEADER LAMA (Untuk divisi lain) -->
            <div class="navbar nav_title" style="border: 0;">
                <a href="halamanics.php" class="site_title" style="font-size: 16px">
                    <span><H2>Integrated Chitra System</H2></span>
                </a>
            </div>
            <div class="clearfix"></div>

            <!-- PROFILE BAWAAN (Disembunyikan dari anak Repair) -->
            <div class="profile">
                <div class="profile_info">
                    <br>
                    <h2> [ <u><?php echo $username_display; ?></u> ]
                        <br><br>
                        <?php
                        $bulan = isset($_GET['months']) ? $_GET['months'] : date('m');
                        $tahun = isset($_GET['year']) ? $_GET['year'] : date('Y');
                        if ($bulan <= 12) {
                            $nama_bulan = date("F", mktime(0, 0, 0, $bulan, 10));
                        } else {
                            $nama_bulan = "All months";
                        } ?>
                    </h2>
                </div>
            </div>
            <br>
        <?php } ?>

        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu menu_fixed">
            <div class="menu_section">
                <?php if ($idsection != 3) { ?>
                    <!-- Garis pembatas lama untuk selain anak Repair -->
                    <h3>__________________________</h3>
                <?php } else { ?>
                    <!-- TAMBAHAN MENU DASHBOARD (Khusus anak Repair Section 3) -->
                    <ul class="nav side-menu">
                        <li class="<?php echo ($current_page == 'halamanics.php') ? 'active current-page' : ''; ?>">
                            <a href="halamanics.php"><i class="fa fa-th-large" style="margin-right: 8px;"></i> Dashboard</a>
                        </li>
                    </ul>
                <?php } ?>

                <?php
                // level=1(adm),2(staff),3(managerial)
                // dept=1(),2(Centserv),3(Sales),4(scm)
                // sect= 1(te),2(serv),3(repair),4(sales),6(),7(scm)
                if ($level == 1) {
                    if ($idsection == 1) { ?>
                        <ul class="nav side-menu">
                            <li><a> Mining Information <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="halamansummarymincom.php">Summary Mining Information</a></li>
                                    <li><a href="halamanmincom.php">Mining Company Information</a></li>
                                    <li><a href="halamancontact.php">Mining Contractor Contact</a></li>
                                    <li><a href="halamanFleetListCustName.php">Mining Contractor Tire Size Population</a></li>
                                    <li><a href="halamanCustomerMaster.php">Mining Contractor Project</a></li>
                                    <li><a href="halamanFleetList.php">Project Fleetlist</a></li>
                                    <li><a>Master Data<span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <li><a href="halamanDataMaster.php">Tire</a></li>
                                            <li><a href="halamanCustomerMaster2.php">Customer</a></li>
                                            <li><a href="halamanTambahFleetList.php">Add Fleetlist</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>

                            <li><a> Machine Database <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="halamanUnitMaster.php">Machine</a></li>
                                    <li><a href="halamanUnitCompany.php">Machine Service Company</a></li>
                                </ul>
                            </li>

                            <li>
                                <a>Tire Warranty<span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="halamanhomewar.php">Summary Tire Warranty</a></li>
                                    <li><a href="halamansubmitwarranty.php">Tire Warranty Submit</a></li>
                                    <li><a href="halamanwarr.php">Tire Report Warranty</a></li>
                                </ul>
                            </li>
                            <li><a href="halamandeliveryupdate.php">Delivery Update</a></li>
                            <li><a href="halamanprupdate.php">Purchase request list</a></li>
                            <li><a href="halamansensor.php">Sensor Monitoring System</a></li>
                        <?php
                    } elseif ($idsection == 2) { ?>
                            <ul class="nav side-menu">
                                <li>
                                    <a>
                                        <i class="fa fa-book"></i> Service Tire
                                        <span class="fa fa-chevron-down"></span>
                                    </a>
                                    <ul class="nav child_menu">
                                        <li><a>Work Order Update<span class="fa fa-chevron-down"></span></a>
                                            <ul class="nav child_menu">
                                                <li><a href="halamanservice.php">Work Order Service</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        <?php
                    } elseif ($idsection == 3) { 
                        // Cek apakah lagi di dalam menu Work Order
                        $is_wo_page = ($current_page == 'repair_halamanwo.php');
                        $is_all_wo_active = ($is_wo_page && $current_loc === '');
                    ?>
                            <ul class="nav side-menu">
                                <!-- Bikin parent menu mekar kalau lagi di halaman WO -->
                                <li class="<?php echo $is_wo_page ? 'active' : ''; ?>">
                                    <a>Tire Repair Jobcard<span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu" <?php echo $is_wo_page ? 'style="display: block;"' : ''; ?>>
                                        
                                        <!-- Bikin parent sub-menu mekar kalau lagi di halaman WO -->
                                        <li class="<?php echo $is_wo_page ? 'active' : ''; ?>">
                                            <a>Work Order Update<span class="fa fa-chevron-down"></span></a>
                                            <ul class="nav child_menu" <?php echo $is_wo_page ? 'style="display: block;"' : ''; ?>>
                                                
                                                <!-- Logika highlight untuk "All Work Order" -->
                                                <li class="<?php echo $is_all_wo_active ? 'current-page' : ''; ?>">
                                                    <a href="repair_halamanwo.php" data-wo-menu-loc="">All Work Order</a>
                                                </li>

                                                <?php
                                                $perintahI = mysqli_query($koneksi3, "SELECT DISTINCT(TRIM(store_loc)) AS store_loc
                                                                    FROM work_order
                                                                    WHERE store_loc IS NOT NULL
                                                                      AND TRIM(store_loc) <> ''
                                                                    ORDER BY store_loc;");
                                                while ($dataI = mysqli_fetch_array($perintahI)) {
                                                    $loc = trim((string) $dataI['store_loc']);
                                                    
                                                    // Logika highlight per Site (misal CK-BIB)
                                                    $is_this_loc_active = ($is_wo_page && $current_loc !== '' && strcasecmp($current_loc, $loc) === 0);
                                                ?>
                                                    <li class="<?php echo $is_this_loc_active ? 'current-page' : ''; ?>">
                                                        <a href="repair_halamanwo.php?loc=<?php echo urlencode($loc); ?>" data-wo-menu-loc="<?php echo htmlspecialchars($loc); ?>">
                                                            <?php echo htmlspecialchars($loc); ?> Work Order
                                                        </a>
                                                    </li>
                                                <?php
                                                }
                                                ?>
                                            </ul>
                                        </li>
                                        <li><a>Raw Data Report<span class="fa fa-chevron-down"></span></a>
                                            <ul class="nav child_menu">
                                                <li class="<?php echo ($current_page == 'repair_halamanjobdata.php') ? 'current-page' : ''; ?>"><a href="repair_halamanjobdata.php">Repair Job Data</a></li>
                                                <li class="<?php echo ($current_page == 'repair_halamanmaterialexpenditure.php') ? 'current-page' : ''; ?>"><a href="repair_halamanmaterialexpenditure.php">Material Expenditure</a></li>
                                                <li class="<?php echo ($current_page == 'repair_halamanmaterialstock.php') ? 'current-page' : ''; ?>"><a href="repair_halamanmaterialstock.php">Material Stock</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li class="<?php echo ($current_page == 'https://workshop.chitraparatama.com/') ? 'current-page' : ''; ?>"><a href="https://workshop.chitraparatama.com/">Tire Repair Scheduling</a></li>
                            </ul>
                        <?php
                    } elseif ($idsection == 4) { ?>
                            <ul class="nav side-menu">
                                <li><a> Sales Operation<span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="vhs_halamansummaryvhs.php">Summary VHS Stock</a></li>
                                        <li><a href="vhs_halamanactualvhs.php">Update Actual VHS Stock</a></li>
                                        <li><a href="vhs_halamanstockvhs.php">VHS Stock</a></li>
                                    </ul>
                                </li>
                            </ul>
                        <?php
                    } elseif ($idsection == 7) { ?>
                            <ul class="nav side-menu">
                                <li><a> Sales Operation<span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="vhs_halamansummaryvhs.php">Summary VHS Stock</a></li>
                                        <li><a href="vhs_halamanstockvhs.php">VHS Stock</a></li>
                                        <li><a href="vhs_halamanactualvhs.php">Actual VHS Stock</a></li>
                                    </ul>
                                </li>
                                <li><a href="halamandeliveryupdate.php">Delivery Update</a></li>
                                <li><a href="halamanprupdate.php">Purchase request list</a></li>
                            </ul>
                        <?php
                    }
                } elseif ($level == 2) {
                    if ($idsection == 1) { ?>
                            <ul class="nav side-menu">
                                <li>
                                    <a>Mining Information<span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="halamanmincom.php">Mining Company Information</a></li>
                                        <li><a href="halamancontact.php">Mining Contractor Contact</a></li>
                                        <li><a href="halamanFleetListCustName.php">Mining Contractor Tire Size Population</a></li>
                                        <li><a href="halamanCustomerMaster.php">Mining Contractor Project</a></li>
                                        <li><a href="halamanFleetList.php">Project Fleetlist</a></li>
                                    </ul>
                                </li>

                                <li><a> Machine Database <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="halamanUnitMaster.php">Machine</a></li>
                                        <li><a href="halamanUnitCompany.php">Machine Service Company</a></li>
                                    </ul>
                                </li>

                                <li>
                                    <a>Tire Warranty<span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="halamanwarr.php">Tire Report Warranty</a></li>
                                    </ul>
                                </li>

                                <li>
                                    <a> Sales Operation<span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="halamanforecastvhs.php">VHS Forecast</a></li>
                                        <li><a href="halamanstockvhs.php">VHS Stock</a></li>
                                    </ul>
                                </li>
                                <li><a href="halamandeliveryupdate.php">Delivery Update</a></li>
                                <li><a href="halamanprupdate.php">Purchase request list</a></li>
                            </ul>
                        <?php
                    } elseif ($idsection == 2) { ?>
                            <ul class="nav side-menu">
                                <li>
                                    <a>
                                        <i class="fa fa-book"></i> Service Tire
                                        <span class="fa fa-chevron-down"></span>
                                    </a>
                                    <ul class="nav child_menu">
                                        <li><a>Work Order Update<span class="fa fa-chevron-down"></span></a>
                                            <ul class="nav child_menu">
                                                <li><a href="halamanservice.php">Work Order Service</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>

                            </ul>
                        <?php
                    } elseif ($idsection == 3) {
                        $is_wo_page = ($current_page == 'repair_halamanwo.php');
                        $is_all_wo_active = ($is_wo_page && $current_loc === '');
                    ?>
                            <ul class="nav side-menu">
                                <li class="<?php echo $is_wo_page ? 'active' : ''; ?>">
                                    <a> Tire Repair Jobcard<span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu" <?php echo $is_wo_page ? 'style="display: block;"' : ''; ?>>
                                        <li class="<?php echo $is_wo_page ? 'active' : ''; ?>">
                                            <a>Work Order Update<span class="fa fa-chevron-down"></span></a>
                                            <ul class="nav child_menu" <?php echo $is_wo_page ? 'style="display: block;"' : ''; ?>>
                                                <li class="<?php echo $is_all_wo_active ? 'current-page' : ''; ?>"><a href="repair_halamanwo.php" data-wo-menu-loc="">All Work Order</a></li>
                                                <?php
                                                $perintahI = mysqli_query($koneksi3, "SELECT DISTINCT(TRIM(store_loc)) AS store_loc
                                                                    FROM work_order
                                                                    WHERE store_loc IS NOT NULL
                                                                      AND TRIM(store_loc) <> ''
                                                                    ORDER BY store_loc;");
                                                while ($dataI = mysqli_fetch_array($perintahI)) {
                                                    $loc = trim((string) $dataI['store_loc']);
                                                    $is_this_loc_active = ($is_wo_page && $current_loc !== '' && strcasecmp($current_loc, $loc) === 0);
                                                ?>
                                                    <li class="<?php echo $is_this_loc_active ? 'current-page' : ''; ?>"><a href="repair_halamanwo.php?loc=<?php echo urlencode($loc); ?>" data-wo-menu-loc="<?php echo htmlspecialchars($loc); ?>">
                                                            <?php echo htmlspecialchars($loc); ?> Work Order
                                                        </a>
                                                    </li>
                                                <?php
                                                }
                                                ?>
                                            </ul>
                                        </li>
                                        <li><a>Raw Data Report<span class="fa fa-chevron-down"></span></a>
                                            <ul class="nav child_menu">
                                                <li><a href="repair_halamanjobdata.php">Repair Job Data</a></li>
                                                <li><a href="repair_halamanmaterialexpenditure.php">Material Expenditure</a></li>
                                                <li><a href="repair_halamanmaterialstock.php">Material Stock</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        <?php
                    } elseif ($idsection == 4) { ?>
                            <ul class="nav side-menu">
                                <li><a> Mining Information<span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="halamanmincom.php">Mining Company Information</a></li>
                                        <li><a href="halamancontact.php">Mining Contractor Contact</a></li>
                                        <li><a href="halamanFleetListCustName.php">Mining Contractor Tire Size Population</a></li>
                                        <li><a href="halamanCustomerMaster.php">Mining Contractor Project</a></li>
                                        <li><a href="halamanFleetList.php">Project Fleetlist</a></li>
                                    </ul>
                                </li>
                                <li><a href="halamanCompetitor.php">Competitor Database</a></li>
                                <li><a> Machine Database <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="halamanUnitMaster.php">Machine</a></li>
                                        <li><a href="halamanUnitCompany.php">Machine Service Company</a></li>
                                    </ul>
                                </li>
                            </ul>
                            <ul class="nav side-menu">
                                <li><a> Sales Operation<span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="vhs_halamansummaryvhs.php">Summary VHS Stock</a></li>
                                        <li><a href="vhs_halamanstockvhs.php">VHS Stock</a></li>
                                        <li><a href="marketing_halamansupply.php">Supply Positioning</a></li>

                                        <!--<li><a href="https://tmtgroup-my.sharepoint.com/:x:/r/personal/ali_rahman_chitraparatama_co_id/_layouts/15/guestaccess.aspx?e=4%3ANSINl1&at=9&CID=7B60335B-D194-4779-AA85-5232148945C0&wdLOR=c0FE08B05-F608-4730-A1F7-394DC7C4DB8A&share=EViyOzmoOHxFi6DMqlmkK_IB9t7mNTBeUhpFcmmW5-Fy6A">Physical stock</a></li>-->
                                    </ul>
                                </li>
                            </ul>
                        <?php } elseif ($idsection == 7) { ?>
                            <ul class="nav side-menu">
                                <li><a href="halamandeliveryupdate.php">Delivery Update</a></li>
                                <li><a href="halamanprupdate.php">Purchase request list</a></li>
                            </ul>
                        <?php } elseif ($idsection == 8) { ?>
                            <ul class="nav side-menu">
                                <li><a> Mining Information <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="halamansummarymincom.php">Summary Mining Information</a></li>
                                        <li><a href="halamanmincom.php">Mining Company Information</a></li>
                                        <li><a href="halamancontact.php">Mining Contractor Contact</a></li>
                                        <li><a href="halamanFleetListCustName.php">Mining Contractor Tire Size Population</a></li>
                                        <li><a href="halamanCustomerMaster.php">Mining Contractor Project</a></li>
                                        <li><a href="halamanFleetList.php">Project Fleetlist</a></li>
                                    </ul>
                                </li>

                                <li><a> Machine Database <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="halamanUnitMaster.php">Machine</a></li>
                                        <li><a href="halamanUnitCompany.php">Machine Service Company</a></li>
                                    </ul>
                                </li>
                            </ul>
                            <ul class="nav side-menu">
                                <li><a> Sales Operation<span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a>Payment <span class="fa fa-chevron-down"></span></a>
                                            <ul class="nav child_menu">
                                                <li class="sub_menu"><a href="halamanarsubmit.php">AR forecast submit</a></li>
                                                <li><a href="halamanarforecast.php">AR forecast summary</a></li>
                                            </ul>
                                    </ul>
                                </li>
                            </ul>
                        <?php
                    }
                } elseif ($level == 3) { ?>
                        <ul class="nav side-menu">
                            <li><a> Mining Information <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="halamansummarymincom.php">Summary Mining Information</a></li>
                                    <li><a href="halamanmincom.php">Mining Company</a></li>
                                    <li><a href="halamanCustomerMaster.php">Mining Contractor</a></li>
                                    <li><a href="halamanFleetListCustName.php">Mining Contractor Tire Size Population</a></li>
                                    <li><a href="halamanFleetList.php">Fleetlist</a></li>
                                </ul>
                            </li>

                            <li><a> Machine Database <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="halamanUnitMaster.php">Machine</a></li>
                                    <li><a href="halamanUnitCompany.php">Machine Service Company</a></li>
                                </ul>
                            </li>

                            <li><a href="halamanCompetitor.php">Competitor Database</a></li>

                            <!--<li>-->
                            <!--    <a>Tire Warranty -->
                            <!--      <span class="fa fa-chevron-down"></span>-->
                            <!--    </a>-->
                            <!--    <ul class="nav child_menu">-->
                            <!--        <li><a href="halamanwarr.php">Tire Report Warranty</a></li>-->
                            <!--    </ul>-->
                            <!--</li>-->
                            <!--<li><a href="halamandeliveryupdate.php">Delivery Update</a></li>-->
                            <!--<li><a href="halamanprupdate.php">Purchase request list</a></li>-->
                            <!--<li><a href="http://10.41.100.11:8080/prediksics">Commodity Prediction</a></li>-->
                            <!--<li><a href="prediksi.php">Commodity Prediction</a></li>-->
                        </ul>
                    <?php
                } elseif ($level == 910) { ?>
                        <ul class="nav side-menu">
                            <li><a href="adm_halamanusermaster.php">User Management</a></li>
                            <li><a href="adm_fleetlist.php">Fleetlist Management</a></li>
                            <li><a href="adm_evhs.php">EVHS Management</a></li>
                            <li><a href="adm_repair.php">Repair Management</a></li>
                        </ul>
                    <?php
                }
                    ?>
                    <ul class="nav side-menu">
                        <li><a href="halamanrmiprice.php"><i class="fa fa-line-chart"></i> Raw Material Index</a></li>
                    </ul>
                    <h3>__________________________</h3>
            </div>
        </div>
        
        <!-- /Sidebar Menu-->
        <div class="sidebar-footer hidden-small">
            <?php if ($idsection == 3) { ?>
                <!-- LOGOUT BARU KHUSUS REPAIR -->
                <a href="proses_logout.php" title="Logout">
                    <i class="fa fa-sign-out"></i>
                    <span>Logout</span>
                </a>
            <?php } else { ?>
                <!-- LOGOUT LAMA -->
                <a data-toggle="tooltip" data-placement="top" title="Logout" href="proses_logout.php">
                    <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                </a>
            <?php } ?>
        </div>
        <!-- /menu footer buttons -->
    </div>
</div>
