<?php 
include_once "koneksi.php";
include_once "auth_check.php";
require_access($koneksi, array(), array(3)); 
?>
<!DOCTYPE html>
<html lang="en">
  <?php 
    include "header.php"; // call sectionhead.php as library
  ?>
  <body class="nav-md work-order-list-page">
    <div class="container body">
      <div class="main_container">
        <?php 
          include "template_menu.php";
          $tahunini = date('Y');
          $tahun = $_GET['year'] ?? $tahunini;
          if (!preg_match('/^\d{4}$/', $tahun)) {
            $tahun = $tahunini;
          }
          $loc = $_GET['loc'] ?? '';
          $locParam = $loc !== '' ? '&amp;loc=' . urlencode($loc) : '';
          $workOrderBreadcrumb = $loc !== '' ? $loc . ' Work Order' : 'All Work Order';
        ?>
        <!-- Top Nav / Breadcrumb -->
        <?php if (isset($idsection) && (int)$idsection == 3) { ?>
            <div class="top_nav">
                <div class="nav_menu repair-top-nav">
                    <div class="nav toggle">
                      <a id="menu_toggle" title="Toggle Sidebar"><i class="fa fa-bars"></i></a>
                    </div>
                    <div class="repair-top-nav-breadcrumb">
                        <i class="fa fa-clipboard" style="color: #9ca3af; font-size: 16px;"></i>
                        <a href="halamanics.php" class="app-name">Integrated Chitra System</a>
                        <span class="repair-breadcrumb-separator">/</span>
                        <span>Tire Repair Jobcard</span>
                        <span class="repair-breadcrumb-separator">/</span>
                        <span>Work Order Update</span>
                        <span class="repair-breadcrumb-separator">/</span>
                        <span class="current"><?php echo htmlspecialchars($workOrderBreadcrumb); ?></span>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <div class="top_nav">
                <div class="nav_menu">
                    <div class="nav toggle">
                      <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                    </div>
                  <li class="nav navbar-nav navbar-left"><h3 style="">Integrated Chitra System</h3></li>
                </div>
            </div>
        <?php } ?>
        <?php if($name!=""){ ?>
        <!-- page content -->        
        <div class="right_col" role="main">
          <div class="">
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_content">
                    <div class="x_title">
                        <div class="clearfix"></div>
                            <div class="row">
                                <div class="col-md-12 col-sm-6 col-xs-6">
                                    <div class="x_panel wo-list-card">
                                        <div class="x_content wo-list-content">
                                            <div class="x_title wo-list-header">
                                                <h3>Work order list </h3>  
                                            </div>                  
                                            <div class="wo-table-wrapper">
                                            <div class="btn-group wo-year-filter wo-toolbar-year-filter">
                                                <button type="button" class="btn btn-primary"><?php echo $tahun; ?></button>
                                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                    <span class="caret"></span>
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <ul class="dropdown-menu" role="menu">
                                                  <li><a href="repair_halamanwo.php?year=<?php echo $tahunini;?><?php echo $locParam; ?>"><?php echo $tahunini; ?></a>
                                                  </li>
                                                  <li><a href="repair_halamanwo.php?year=<?php echo $tahunini-1;?><?php echo $locParam; ?>"><?php echo $tahunini-1; ?></a>
                                                  </li>
                                                  <li><a href="repair_halamanwo.php?year=<?php echo $tahunini-2;?><?php echo $locParam; ?>"><?php echo $tahunini-2; ?></a>
                                                  </li>
                                                  <li><a href="repair_halamanwo.php?year=<?php echo $tahunini-3;?><?php echo $locParam; ?>"><?php echo $tahunini-3; ?></a>
                                                  </li>
                                                </ul>
                                            </div>
                                            <table id="datatable-buttons" class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                      <th class="wo-expand-col"></th>
                                                      <th>No</th>
                                                      <th>Work_order</th>
                                                      <th>Wo_date</th>
                                                      <th>SN</th>
                                                      <th>Injury</th>
                                                      <th>Job</th>
                                                      <th>Status</th>
                                                      <th>Customer</th>
                                                      <th>Site</th>
                                                      <th>Rcv_date</th>
                                                      <th>Insp_date</th>
                                                      <th>Size</th>
                                                      <th>Finish_dte</th>
                                                      <th>Invoice</th>
                                                      <th>Invoice Date</th>
                                                      <th>Repair_Loc</th>
                                                      <th>Create_by</th>
                                                      <th>Type</th>
                                                      <th class="wo-action-col">Action</th>
                                                    </tr>
                                                 </thead>
                                                <tbody>
                            <?php 
                            
                            $perintah = mysqli_query($koneksi3, "SELECT wo,job,date
                                                                FROM job
                                                                WHERE job='Painting' OR job='painting' ");
                            $finish = array();
                            while ($data = mysqli_fetch_array($perintah)) {
                                $finish[$data['wo']]=$data['date'];
                            }
                            
                            if ($loc === '') {
                                $perintah = mysqli_query($koneksi3, "
                                    SELECT *
                                    FROM work_order a
                                    WHERE a.received_date LIKE '$tahun%'
                                ");
                            } else {
                                $perintah = mysqli_query($koneksi3, "
                                    SELECT *
                                    FROM work_order a
                                    WHERE a.received_date LIKE '$tahun%'
                                    AND a.store_loc = '".mysqli_real_escape_string($koneksi3, $loc)."'
                                ");
                            }
                            $no=1;
                            $modals = "";
                        
                            while ($data = mysqli_fetch_array($perintah)) { 
                              $bast=$data['bast'];
                              $status=$data['status'];
                              $jobtype=$data['job_type'];
                              $tiretype=$data['type'];
                              $id_wo = $data['id_wo'];
                              $statusClass = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $status));
                              $statusClass = trim($statusClass, '-');
                              $rowStatusClass = 'wo-row-status-' . $statusClass;
                              $detailAction = 'none';
                              if ($status == 'Complete') {
                                $detailAction = 'complete';
                              } elseif ($status == 'Progress') {
                                $detailAction = 'progress';
                              }
                              $detailWorkOrder = htmlspecialchars($data['wo'] ?? '', ENT_QUOTES, 'UTF-8');
                              $detailSize = htmlspecialchars($data['size'] ?? '', ENT_QUOTES, 'UTF-8');
                              $detailSn = htmlspecialchars($data['tire_sn'] ?? '', ENT_QUOTES, 'UTF-8');
                              $detailInjury = htmlspecialchars($data['injury'] ?? '', ENT_QUOTES, 'UTF-8');
                              $detailJob = htmlspecialchars($data['job_type'] ?? '', ENT_QUOTES, 'UTF-8');
                              $detailType = htmlspecialchars($tiretype ?? '', ENT_QUOTES, 'UTF-8');
                              $detailCustomer = htmlspecialchars($data['customer'] ?? '', ENT_QUOTES, 'UTF-8');
                              $detailSite = htmlspecialchars($data['site'] ?? '', ENT_QUOTES, 'UTF-8');
                              $detailReceivedDate = htmlspecialchars($data['received_date'] ?? '', ENT_QUOTES, 'UTF-8');
                              $detailInspectDate = htmlspecialchars($data['inspect_date'] ?? '', ENT_QUOTES, 'UTF-8');
                              $detailWoDate = htmlspecialchars($data['wo_date'] ?? '', ENT_QUOTES, 'UTF-8');
                              $detailFinishDate = htmlspecialchars($finish[$data['id_wo']] ?? '-', ENT_QUOTES, 'UTF-8');
                              $detailInvoice = htmlspecialchars($data['invoice'] ?? '', ENT_QUOTES, 'UTF-8');
                              $detailInvoiceDate = htmlspecialchars($data['invoice_date'] ?? '', ENT_QUOTES, 'UTF-8');
                              $detailRepairLoc = htmlspecialchars($data['store_loc'] ?? '', ENT_QUOTES, 'UTF-8');
                              $detailCreateBy = htmlspecialchars($data['createby'] ?? '', ENT_QUOTES, 'UTF-8');
                              $detailStatus = htmlspecialchars($status ?? '', ENT_QUOTES, 'UTF-8');
                              $detailStatusClass = htmlspecialchars($statusClass, ENT_QUOTES, 'UTF-8');
                              $detailId = htmlspecialchars($id_wo, ENT_QUOTES, 'UTF-8');
                              $detailPdfFileParts = array(
                                $data['wo'] ?? '',
                                $data['tire_sn'] ?? '',
                                $data['customer'] ?? '',
                                $data['site'] ?? ''
                              );
                              $detailPdfFileParts = array_map(function($value) {
                                $value = preg_replace('/\s+/', ' ', trim((string) $value));
                                return preg_replace('/[\/\\\\:*?"<>|]/', '', $value);
                              }, $detailPdfFileParts);
                              $detailPdfFileParts = array_filter($detailPdfFileParts, function($value) {
                                return $value !== '';
                              });
                              $detailPdfFilename = implode('-', $detailPdfFileParts);
                              if ($detailPdfFilename === '') {
                                $detailPdfFilename = 'Repair Jobcard';
                              }
                              $detailPdfFilename .= '.pdf';
                              $detailPdfUrl = 'repair_jobcard_pdf.php/' . rawurlencode($detailPdfFilename) . '?id=' . urlencode($id_wo);
                              $detailPdfUrlAttr = htmlspecialchars($detailPdfUrl, ENT_QUOTES, 'UTF-8');
                              $detailAttributes = 'data-detail-id="'.$detailId.'"'
                                . ' data-detail-work-order="'.$detailWorkOrder.'"'
                                . ' data-detail-size="'.$detailSize.'"'
                                . ' data-detail-sn="'.$detailSn.'"'
                                . ' data-detail-injury="'.$detailInjury.'"'
                                . ' data-detail-job="'.$detailJob.'"'
                                . ' data-detail-type="'.$detailType.'"'
                                . ' data-detail-customer="'.$detailCustomer.'"'
                                . ' data-detail-site="'.$detailSite.'"'
                                . ' data-detail-received-date="'.$detailReceivedDate.'"'
                                . ' data-detail-inspect-date="'.$detailInspectDate.'"'
                                . ' data-detail-wo-date="'.$detailWoDate.'"'
                                . ' data-detail-finish-date="'.$detailFinishDate.'"'
                                . ' data-detail-invoice="'.$detailInvoice.'"'
                                . ' data-detail-invoice-date="'.$detailInvoiceDate.'"'
                                . ' data-detail-repair-loc="'.$detailRepairLoc.'"'
                                . ' data-detail-create-by="'.$detailCreateBy.'"'
                                . ' data-detail-status="'.$detailStatus.'"'
                                . ' data-detail-status-class="'.$detailStatusClass.'"'
                                . ' data-detail-pdf-url="'.$detailPdfUrlAttr.'"'
                                . ' data-detail-action="'.$detailAction.'"';
                              
                              // Collect modals
                              $modals .= '
                            <div class="modal fade" id="editModal'.$id_wo.'" tabindex="-1" role="dialog" aria-labelledby="editModalLabel'.$id_wo.'" aria-hidden="true">
                              <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                  <form action="repair_updatewo.php" method="POST">
                                    <div class="modal-body">
                                      <input type="hidden" name="idwo" value="'.$id_wo.'">
                                      <input type="hidden" name="status" value="'.$status.'">
                                      <div class="form-group">
                                        <label for="wo">Work Order</label>
                                        <input type="text" class="form-control" name="wo" value="'.$data['wo'].'" required>
                                      </div>
                                      <div class="form-group">
                                        <label for="wo_date">Date</label>
                                        <input type="date" class="form-control" name="date" value="'.$data['wo_date'].'" required>
                                      </div>
                                      <div class="form-group">
                                        <label for="inv">Invoice</label>
                                        <input type="text" class="form-control" name="inv" value="'.$data['invoice'].'">
                                      </div>  
                                      <div class="form-group">
                                        <label for="invdate">Invoice Date</label>
                                        <input type="date" class="form-control" name="invdate" value="'.$data['invoice_date'].'">
                                      </div>
                                    </div>
                                    <div class="modal-footer">
                                      <button type="submit" class="btn btn-success">Simpan</button>
                                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                    </div>
                                  </form>
                                </div>
                              </div>
                            </div>';

                              if($status=='w/ work_order'){?>
                                <tr class="wo-data-row wo-row-waiting <?php echo $rowStatusClass; ?>">
                                        <td class="wo-expand-cell"></td>
                                        <td><?php echo $no; ?></td>
                                        <td>
                                            <input
                                                type="text"
                                                class="wo-inline-input"
                                                name="wo"
                                                value="<?php echo $data['wo']; ?>"
                                                form="wo-update-form-<?php echo $data['id_wo']; ?>"
                                                required
                                            >
                                        </td>
                                        <td>
                                            <input
                                                type="date"
                                                class="wo-inline-input"
                                                name="date"
                                                value="<?php echo $data['wo_date']; ?>"
                                                max="<?php echo date('Y-m-d'); ?>"
                                                form="wo-update-form-<?php echo $data['id_wo']; ?>"
                                                required
                                            >
                                        </td>
                                        <td><?php echo $data['tire_sn']; ?></td>
                                        <td><?php echo $data['injury']; ?></td>
                                        <td><span class="wo-job-badge wo-job-<?php echo $statusClass; ?>"><?php echo htmlspecialchars($data['job_type']); ?></span></td>
                                        <td><span class="wo-status-badge wo-status-<?php echo $statusClass; ?>"><?php echo $data['status']; ?></span></td>
                                        <td><?php echo $data['customer']; ?></td>
                                        <td><?php echo $data['site']; ?></td>
                                        <td><?php echo $data['received_date']; ?></td>
                                        <td><?php echo $data['inspect_date']; ?></td>
                                        <td><?php echo $data['size']; ?></td>
                                        <td><?php echo $finish[$data['id_wo']] ?? '-'; ?></td>
                                        <td></td>
                                        <td></td>
                                        <td><?php echo $data['store_loc']; ?></td>
                                        <td><?php echo $data['createby']; ?></td>
                                        <td><?php echo $tiretype; ?></td>
                                        <td class="wo-action-cell">
                                            <form id="wo-update-form-<?php echo $data['id_wo']; ?>" method="POST" action="repair_updatewo.php">
                                                <input type="hidden" name="name" value="<?php echo $name; ?>">
                                                <input type="hidden" name="idwo" value="<?php echo $data['id_wo']; ?>">
                                                <input type="hidden" name="status" value="Progress">
                                            </form>
                                            <button type="submit" class="btn btn-sm btn-primary" form="wo-update-form-<?php echo $data['id_wo']; ?>">
                                                <i class="fa fa-save"></i> Save
                                            </button>
                                        </td>
                                </tr>
                                  <?php
                              }
                              elseif ($status=='Complete'){?>  
                                <tr class="wo-data-row <?php echo $rowStatusClass; ?>"
                                    <?php echo $detailAttributes; ?>>
                                        <td class="wo-expand-cell">
                                            <button type="button" class="wo-expand-toggle" aria-expanded="false" title="Tampilkan detail">+</button>
                                        </td>
                                        <td><?php echo $no; ?></td>
                                        <td>
                                            <?php echo $data['wo'];?>
                                        </td>
                                        <td><?php echo $data['wo_date']; ?></td>
                                        <td><?php echo $data['tire_sn']; ?></td>
                                        <td><?php echo $data['injury']; ?></td>
                                        <td><span class="wo-job-badge wo-job-<?php echo $statusClass; ?>"><?php echo htmlspecialchars($data['job_type']); ?></span></td>
                                        <td><span class="wo-status-badge wo-status-<?php echo $statusClass; ?>"><?php echo $data['status']; ?></span></td>
                                        <td><?php echo $data['customer']; ?></td>
                                        <td><?php echo $data['site']; ?></td>
                                        <td><?php echo $data['received_date']; ?></td>
                                        <td><?php echo $data['inspect_date']; ?></td>
                                        <td><?php echo $data['size']; ?></td>
                                        <td><?php echo $finish[$data['id_wo']] ?? '-'; ?></td>
                                        <td><?php echo $data['invoice']; ?></td>
                                        <td><?php echo $data['invoice_date']; ?></td>
                                        <td><?php echo $data['store_loc']; ?></td>
                                        <td><?php echo $data['createby']; ?></td>
                                        <td><?php echo $tiretype; ?></td>
                                        <td class="wo-action-cell">
                                            <span class="wo-action-group">
                                                <button type="button"
                                                    class="btn btn-sm btn-primary"
                                                    data-toggle="modal"
                                                    data-target="#editModal<?php echo $data['id_wo']; ?>"
                                                    title="Edit">
                                                    <i class="fa fa-pencil"></i>
                                                </button>
                                                <a href="repair_jobcard.php?id=<?php echo $data['id_wo']; ?>" class="btn btn-sm btn-primary">Detail</a>
                                                <a href="<?php echo $detailPdfUrlAttr; ?>" target="_blank" rel="noopener" class="btn btn-sm btn-primary" title="Print PDF">
                                                    <i class="fa fa-print"></i>
                                                </a>
                                            </span>
                                        </td>               
                                </tr>
                                <?php
                              }
                              elseif ($status=='Progress'){?>  
                                <tr class="wo-data-row <?php echo $rowStatusClass; ?>"
                                    <?php echo $detailAttributes; ?>>
                                        <td class="wo-expand-cell">
                                            <button type="button" class="wo-expand-toggle" aria-expanded="false" title="Tampilkan detail">+</button>
                                        </td>
                                        <td><?php echo $no; ?></td>
                                        <td>
                                            <?php echo $data['wo']; ?>
                                        </td>
                                        <td><?php echo $data['wo_date']; ?></td>
                                        <td><?php echo $data['tire_sn']; ?></td>
                                        <td><?php echo $data['injury']; ?></td>
                                        <td><span class="wo-job-badge wo-job-<?php echo $statusClass; ?>"><?php echo htmlspecialchars($data['job_type']); ?></span></td>
                                        <td><span class="wo-status-badge wo-status-<?php echo $statusClass; ?>"><?php echo $data['status']; ?></span></td>
                                        <td><?php echo $data['customer']; ?></td>
                                        <td><?php echo $data['site']; ?></td>
                                        <td><?php echo $data['received_date']; ?></td>
                                        <td><?php echo $data['inspect_date']; ?></td>
                                        <td><?php echo $data['size']; ?></td>
                                        <td><?php echo $finish[$data['id_wo']] ?? '-'; ?></td>
                                        <td><?php echo $data['invoice']; ?></td>
                                        <td><?php echo $data['invoice_date']; ?></td>
                                        <td><?php echo $data['store_loc']; ?></td>
                                        <td><?php echo $data['createby']; ?></td>
                                        <td><?php echo $tiretype; ?></td>
                                        <td class="wo-action-cell">
                                            <button type="button"
                                                class="btn btn-sm btn-primary"
                                                data-toggle="modal"
                                                data-target="#editModal<?php echo $data['id_wo']; ?>"
                                                title="Edit">
                                                <i class="fa fa-pencil"></i>
                                            </button>
                                        </td>               
                                </tr>
                                <?php
                              }
                              else{?>  
                                <tr class="wo-data-row <?php echo $rowStatusClass; ?>"
                                    <?php echo $detailAttributes; ?>>
                                        <td class="wo-expand-cell">
                                            <button type="button" class="wo-expand-toggle" aria-expanded="false" title="Tampilkan detail">+</button>
                                        </td>
                                        <td><?php echo $no; ?></td>
                                        <td><?php echo $data['wo']; ?></td>
                                        <td><?php echo $data['wo_date']; ?></td>
                                        <td><?php echo $data['tire_sn']; ?></td>
                                        <td><?php echo $data['injury']; ?></td>
                                        <td><span class="wo-job-badge wo-job-<?php echo $statusClass; ?>"><?php echo htmlspecialchars($data['job_type']); ?></span></td>
                                        <td><span class="wo-status-badge wo-status-<?php echo $statusClass; ?>"><?php echo $data['status']; ?></span></td>
                                        <td><?php echo $data['customer']; ?></td>
                                        <td><?php echo $data['site']; ?></td>
                                        <td><?php echo $data['received_date']; ?></td>
                                        <td><?php echo $data['inspect_date']; ?></td>
                                        <td><?php echo $data['size']; ?></td>
                                        <td><?php echo $finish[$data['id_wo']] ?? '-'; ?></td>
                                        <td></td>
                                        <td></td>
                                        <td><?php echo $data['store_loc']; ?></td>
                                        <td><?php echo $data['createby']; ?></td>
                                        <td><?php echo $tiretype; ?></td>
                                        <td class="wo-action-cell">
                                        </td>               
                                </tr>
                                <?php 
                                }
                              $no++; 
                            } ?>
                        </tbody>
                        </table>
                        </div>
                        <?php echo $modals; ?>  
                                        </div>
                                    </div>
                                </div> 
                            </div>
          </div>
        </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->
        <!-- modal edit data tire inventory -->
        <?php } ?>
    <!-- footer content -->
    <footer>
      <div class="pull-right">
        Repair Jobcard
      </div>
      <div class="clearfix">
      </div>
    </footer>
    <!-- /footer content -->
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
    <script src="../vendors/jszip/dist/jszip.min.js"></script>
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
    <script>window.jQuery || document.write('<script src=""../vendors/js/jquery.min.js"></script>')</script>
    <script src="../vendors/js/bootstrap.min.js"></script>
    <script src="../vendors/js/docs.min.js"></script>
    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>
    <!-- Datatables -->
    <script>
      $(document).ready(function() {
        function escapeHtml(value) {
          return $('<div>').text(value || '-').html();
        }

        function detailActionHtml(rowData) {
          var id = escapeHtml(rowData.detailId);
          var pdfUrl = rowData.detailPdfUrl || jobcardPdfUrl({
            id_wo: rowData.detailId,
            wo: rowData.detailWorkOrder,
            tire_sn: rowData.detailSn,
            customer: rowData.detailCustomer,
            site: rowData.detailSite
          });

          if (rowData.detailAction === 'complete') {
            return '' +
              '<span class="wo-detail-actions">' +
                '<button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editModal' + id + '" title="Edit">' +
                  '<i class="fa fa-pencil"></i>' +
                '</button>' +
                '<a href="repair_jobcard.php?id=' + id + '" class="btn btn-sm btn-primary">Detail</a>' +
                '<a href="' + escapeHtml(pdfUrl) + '" target="_blank" rel="noopener" class="btn btn-sm btn-primary" title="Print PDF">' +
                  '<i class="fa fa-print"></i>' +
                '</a>' +
              '</span>';
          }

          if (rowData.detailAction === 'progress') {
            return '' +
              '<button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editModal' + id + '" title="Edit">' +
                '<i class="fa fa-pencil"></i>' +
              '</button>';
          }

          return '<span class="text-muted">-</span>';
        }

        function detailItemHtml(label, value, valueHtml) {
          return '' +
            '<div class="wo-detail-label">' + escapeHtml(label) + '</div>' +
            '<div class="wo-detail-value">' + (valueHtml || escapeHtml(value)) + '</div>';
        }

        function formatWorkOrderDetail(rowData) {
          var statusClass = escapeHtml(rowData.detailStatusClass);
          var statusText = escapeHtml(rowData.detailStatus);

          return '' +
            '<div class="wo-detail-panel">' +
              '<div class="wo-detail-section-title">Work Order Detail</div>' +
              '<div class="wo-detail-grid">' +
                detailItemHtml('Work_order', rowData.detailWorkOrder) +
                detailItemHtml('Wo_date', rowData.detailWoDate) +
                detailItemHtml('SN', rowData.detailSn) +
                detailItemHtml('Injury', rowData.detailInjury) +
                detailItemHtml('Job', rowData.detailJob) +
                detailItemHtml('Status', rowData.detailStatus, '<span class="wo-status-badge wo-status-' + statusClass + '">' + statusText + '</span>') +
                detailItemHtml('Customer', rowData.detailCustomer) +
                detailItemHtml('Site', rowData.detailSite) +
                detailItemHtml('Rcv_date', rowData.detailReceivedDate) +
                detailItemHtml('Insp_date', rowData.detailInspectDate) +
                detailItemHtml('Size', rowData.detailSize) +
                detailItemHtml('Finish_dte', rowData.detailFinishDate) +
                detailItemHtml('Invoice', rowData.detailInvoice) +
                detailItemHtml('Invoice Date', rowData.detailInvoiceDate) +
                detailItemHtml('Repair_Loc', rowData.detailRepairLoc) +
                detailItemHtml('Create_by', rowData.detailCreateBy) +
                detailItemHtml('Type', rowData.detailType) +
                detailItemHtml('Action', '', detailActionHtml(rowData)) +
              '</div>' +
            '</div>';
        }

        var handleDataTableButtons = function() {
          if ($("#datatable-buttons").length) {
            var workOrderTable = $("#datatable-buttons").DataTable({
              dom: '<"wo-dt-toolbar"Bf><"wo-table-scroll"t>ip',
              buttons: [
                {
                  extend: "copy",
                  className: "btn-sm"
                },
                {
                  extend: "csv",
                  className: "btn-sm"
                },
                {
                  extend: "excel",
                  className: "btn-sm"
                },
                {
                  extend: "pdfHtml5",
                  className: "btn-sm"
                },
                {
                  extend: "print",
                  className: "btn-sm"
                },
              ],
              scrollX: false,
              responsive: false,
              autoWidth: false,
              paging: true,
              pageLength: 10,
              lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
              columnDefs: [
                { orderable: false, searchable: false, targets: [0, 19] }
              ],
              order: [[ 1, "desc" ]],
              initComplete: function() {
                $('.wo-toolbar-year-filter').prependTo('.wo-dt-toolbar');
                refreshWorkOrderLayout(this.api());
              }
            });

            function refreshWorkOrderLayout(tableApi) {
              tableApi.columns.adjust();
              $('.right_col').css('min-height', '');
              $(window).trigger('resize');
            }

            workOrderTable.on('draw.dt', function() {
              refreshWorkOrderLayout(workOrderTable);
            });

            setTimeout(function() { refreshWorkOrderLayout(workOrderTable); }, 100);
            setTimeout(function() { refreshWorkOrderLayout(workOrderTable); }, 400);
            setTimeout(function() { refreshWorkOrderLayout(workOrderTable); }, 900);

            function updateDetailData($tr, data) {
              var detailData = {
                detailId: data.id_wo,
                detailWorkOrder: data.wo,
                detailSize: data.size,
                detailSn: data.tire_sn,
                detailInjury: data.injury,
                detailJob: data.job_type,
                detailType: data.type,
                detailCustomer: data.customer,
                detailSite: data.site,
                detailReceivedDate: data.received_date,
                detailInspectDate: data.inspect_date,
                detailWoDate: data.wo_date,
                detailFinishDate: data.finish_date,
                detailInvoice: data.invoice,
                detailInvoiceDate: data.invoice_date,
                detailRepairLoc: data.store_loc,
                detailCreateBy: data.createby,
                detailStatus: data.status,
                detailStatusClass: data.statusClass,
                detailPdfUrl: jobcardPdfUrl(data),
                detailAction: data.detailAction || 'progress'
              };

              $tr.data(detailData);
              $tr.attr({
                'data-detail-id': detailData.detailId,
                'data-detail-work-order': detailData.detailWorkOrder,
                'data-detail-size': detailData.detailSize,
                'data-detail-sn': detailData.detailSn,
                'data-detail-injury': detailData.detailInjury,
                'data-detail-job': detailData.detailJob,
                'data-detail-type': detailData.detailType,
                'data-detail-customer': detailData.detailCustomer,
                'data-detail-site': detailData.detailSite,
                'data-detail-received-date': detailData.detailReceivedDate,
                'data-detail-inspect-date': detailData.detailInspectDate,
                'data-detail-wo-date': detailData.detailWoDate,
                'data-detail-finish-date': detailData.detailFinishDate,
                'data-detail-invoice': detailData.detailInvoice,
                'data-detail-invoice-date': detailData.detailInvoiceDate,
                'data-detail-repair-loc': detailData.detailRepairLoc,
                'data-detail-create-by': detailData.detailCreateBy,
                'data-detail-status': detailData.detailStatus,
                'data-detail-status-class': detailData.detailStatusClass,
                'data-detail-pdf-url': detailData.detailPdfUrl,
                'data-detail-action': detailData.detailAction
              });
            }

            function jobcardPdfFilename(data) {
              var parts = [data.wo, data.tire_sn, data.customer, data.site].map(function(value) {
                return (value || '').toString().replace(/\s+/g, ' ').trim().replace(/[\/\\:*?"<>|]/g, '');
              }).filter(function(value) {
                return value !== '';
              });

              return (parts.length ? parts.join('-') : 'Repair Jobcard') + '.pdf';
            }

            function jobcardPdfUrl(data) {
              return 'repair_jobcard_pdf.php/' + encodeURIComponent(jobcardPdfFilename(data)) + '?id=' + encodeURIComponent(data.id_wo || '');
            }

            function updateInlineModal(data) {
              var $modal = $('#editModal' + data.id_wo);
              $modal.find('input[name="wo"]').val(data.wo || '');
              $modal.find('input[name="date"]').val(data.wo_date || '');
              $modal.find('input[name="inv"]').val(data.invoice || '');
              $modal.find('input[name="invdate"]').val(data.invoice_date || '');
              $modal.find('input[name="status"]').val(data.status || 'Progress');
            }

            function updateSavedInlineRow($tr, data) {
              var statusClass = escapeHtml(data.statusClass || 'progress');
              var statusText = escapeHtml(data.status || 'Progress');
              var id = escapeHtml(data.id_wo);
              var $cells = $tr.children('td');

              $tr.attr('class', function(index, className) {
                return (className || '')
                  .replace(/\bwo-row-waiting\b/g, '')
                  .replace(/\bwo-row-status-\S+/g, '')
                  .trim();
              });
              $tr.addClass('wo-row-status-' + statusClass);

              $cells.eq(0).html('<button type="button" class="wo-expand-toggle" aria-expanded="false" title="Tampilkan detail">+</button>');
              $cells.eq(2).text(data.wo || '');
              $cells.eq(6).html('<span class="wo-job-badge wo-job-' + statusClass + '">' + escapeHtml(data.job_type) + '</span>');
              $cells.eq(7).html('<span class="wo-status-badge wo-status-' + statusClass + '">' + statusText + '</span>');
              $cells.eq(3).text(data.wo_date || '');
              $cells.eq(12).text(data.size || '');
              $cells.eq(14).text(data.invoice || '');
              $cells.eq(15).text(data.invoice_date || '');
              $cells.eq(17).text(data.createby || '');
              $cells.eq(18).text(data.type || '');
              $cells.eq(19).html(
                '<button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editModal' + id + '" title="Edit">' +
                  '<i class="fa fa-pencil"></i>' +
                '</button>'
              );

              updateDetailData($tr, data);
              updateInlineModal(data);
              workOrderTable.row($tr).invalidate('dom').draw(false);
              refreshWorkOrderLayout(workOrderTable);
            }

            $('#datatable-buttons tbody').on('submit', 'form[id^="wo-update-form-"]', function(event) {
              event.preventDefault();

              var form = this;
              var $form = $(form);
              var formId = $form.attr('id');
              var $row = $form.closest('tr');
              var $button = $('button[form="' + formId + '"]');
              var requestData = $form.serializeArray();

              $('[form="' + formId + '"]').not('button').each(function() {
                requestData = requestData.concat($(this).serializeArray());
              });
              requestData.push({ name: 'ajax', value: '1' });

              $button.data('original-html', $button.html());
              $button.prop('disabled', true).html('Saving...');

              $.ajax({
                url: $form.attr('action'),
                type: 'POST',
                data: $.param(requestData),
                dataType: 'json'
              }).done(function(response) {
                if (!response || !response.success) {
                  alert((response && response.message) || 'Gagal menyimpan WO.');
                  $button.prop('disabled', false).html($button.data('original-html'));
                  return;
                }

                updateSavedInlineRow($row, response);
                alert(response.message || 'Data WO berhasil disimpan.');
              }).fail(function(xhr) {
                var response = xhr.responseJSON || {};
                alert(response.message || 'Gagal menyimpan WO.');
                $button.prop('disabled', false).html($button.data('original-html'));
              });
            });

            $('#datatable-buttons tbody').on('click', '.wo-expand-toggle', function() {
              var $button = $(this);
              var tr = $button.closest('tr');
              var row = workOrderTable.row(tr);

              if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
                $button.removeClass('is-open').attr('aria-expanded', 'false').text('+').attr('title', 'Tampilkan detail');
              } else {
                row.child(formatWorkOrderDetail(tr.data())).show();
                tr.addClass('shown');
                $button.addClass('is-open').attr('aria-expanded', 'true').text('-').attr('title', 'Sembunyikan detail');
              }
            });
          }
        };

        TableManageButtons = function() {
          "use strict";
          return {
            init: function() {
              handleDataTableButtons();
            }
          };
        }();
        $('#datatable-keytable').DataTable({
          keys: true
        });

        $('#datatable-responsive').DataTable();

        $('#datatable-scroller').DataTable({
          ajax: "js/datatables/json/scroller-demo.json",
          deferRender: true,
          scrollY: 380,
          scrollCollapse: true,
          scroller: true
        });

        $('#datatable-fixed-header').DataTable({
          fixedHeader: true
        });

        var $datatable = $('#datatable-checkbox');

        $datatable.dataTable({
          'order': [[ 0, "desc" ]],
          'columnDefs': [
            { orderable: false, targets: [0] }
          ]
        });
        $datatable.on('draw.dt', function() {
          $('input').iCheck({
            checkboxClass: 'icheckbox_flat-green'
          });
        });

        TableManageButtons.init();
      });
    </script>
    <script>
      $(function() {
        var currentLoc = <?php echo json_encode(trim((string) $loc)); ?>;
        var $woLinks = $('#sidebar-menu a[data-wo-menu-loc]');

        $woLinks.parent('li').removeClass('current-page');

        $woLinks.each(function() {
          var linkLoc = ($(this).data('wo-menu-loc') || '').toString();
          if (linkLoc.toLowerCase() === currentLoc.toLowerCase()) {
            $(this).parent('li').addClass('current-page');
          }
        });
      });
    </script>
  </body>
</html>
