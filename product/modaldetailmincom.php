<?php 
    $idmining= $_POST['idmining'];
    include "koneksi.php";
    $perintah = mysqli_query($koneksi2,"SELECT * from mining_company where id_mining=$idmining");
    $data = mysqli_fetch_array($perintah);
    $tglupdate=$data['tgl_update'];
?>
<div class="row">
    <div class="col-md-12">
        <div class="x_panel">
            <div class="x_title">
                <h1><b><?php echo $data['mining_company'];?></b></h1>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <section class="content invoice">
                    <div class="row invoice-info">
                        <div class="col-sm-4 invoice-col">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th>Target production :</th>
                                        <td><?php echo $data['target'];?>*</td>
                                    </tr>
                                    <tr>
                                        <th>Material :</th>
                                        <td><?php echo $data['material'];?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="row">
                        <!-- start accordion -->
                        <div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">
                            <?php $perintah = mysqli_query($koneksi2," SELECT * From customer_master a, site_master b WHERE a.id_customer_master=b.id_customer AND b.mining_company=$idmining and status='Active' ");
                                $no=1;
                                while ($data = mysqli_fetch_array($perintah)) {
                                    $idcust=$data['id_customer_master'];?>
                                    <div class="panel">
                                        <a class="panel-heading collapsed" role="tab" id="heading<?php echo $no;?>" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $no;?>" aria-expanded="false" aria-controls="collapse<?php echo $no;?>">
                                          <h4 class="panel-title"><?php echo $data['customer'];?></h4>
                                        </a>
                                        <div id="collapse<?php echo $no;?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading<?php echo $no;?>">
                                          <div class="panel-body">
                                               <div class="table-responsive">
                                                  <table class="table table-bordered table-striped">
                                                    <thead class="thead-light">
                                                      <tr>
                                                        <th style="width: 30%;">Size</th>
                                                        <th>Category</th>
                                                        <th>Unit Population</th>
                                                        <th>Tire Population</th>
                                                        <th>Rotasi</th>
                                                        <th>Scrap</th>
                                                        <th>Forecast</th>
                                                      </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php 
                                                            $perintah1 = mysqli_query($koneksi2,"SELECT 
                                                                                                  d.tire_size,
                                                                                                  SUM(a.unit_qty) AS unit,
                                                                                                  SUM(a.unit_qty * d.tire_quantity) AS running,
                                                                                                  MAX(a.rotasi) AS rotasi,
                                                                                                  MAX(a.scrap) AS scrap,
                                                                                                  round((MAX(a.rotasi) / NULLIF(MAX(a.scrap), 0)) * SUM(a.unit_qty * d.tire_quantity)) AS forecast,
                                                                                                  MAX(d.category) AS category
                                                                                                FROM fleet_list a
                                                                                                JOIN site_master b ON a.id_site = b.id_site_master
                                                                                                JOIN unit_master d ON a.id_unit = d.id_unit_master
                                                                                                WHERE b.mining_company = $idmining
                                                                                                  AND status = 'Active' 
                                                                                                  AND id_customer = $idcust  
                                                                                                GROUP BY d.tire_size
                                                                                                ORDER BY d.tire_size");
                                                            while($data1 = mysqli_fetch_array($perintah1)){
                                                        ?>
                                                      <tr>
                                                        <td><?php echo $data1['tire_size']; ?></td>
                                                        <td><?php echo $data1['category']; ?></td>
                                                        <td><?php echo $data1['unit']; ?></td>
                                                        <td><?php echo $data1['running']; ?></td>
                                                        <td><?php echo $data1['rotasi']; ?></td>
                                                        <td><?php echo $data1['scrap']; ?></td>
                                                        <td><?php echo $data1['forecast']; ?></td>
                                                      </tr>
                                                      <?php } ?>
                                                    </tbody>
                                                  </table>
                                                </div>
                                          </div>
                                        </div>
                                      </div>
                                    <?php
                                    $no++;  
                                }
                          ?>
                        </div>
                        <!-- end of accordion -->
                        <!-- /.col -->
                    </div>
                </section>
                  </div>
                </div>
              </div>
            </div>