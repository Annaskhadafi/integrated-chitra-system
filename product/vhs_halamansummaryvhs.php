<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
<?php include'header.php';?>
  <body class="nav-md">
    <div class="container body">
        <div class="main_container">
            <div class="col-md-3 left_col">
                <div class="navbar nav_title" style="border: 0;"></div>
                <div class="clearfix"></div>
                <br>
                <?php include "template_menu.php";?>
                <?php 
                    $project = array();
                    $status = array();
                    $quantity = array();
                    
                    $perintah2 = mysqli_query($koneksi6,"SELECT a.status,count(a.status) as quantity FROM stock a group by a.status");
                    while($data2=mysqli_fetch_array($perintah2)){
                        $status[]=$data2['status'];
                        $quantity[]=$data2['quantity'];
                    }
                        $summary = array_combine($status,$quantity);
                        $supplied=$summary['onsite']+$summary['Done'];
                ?>
            </div>
            <div class="top_nav">
                <div class="nav_menu">
                    <div class="nav toggle">
                      <a id="menu_toggle">
                        <i class="fa fa-bars">
                        </i>
                      </a>
                    </div>
                    <ul class="nav navbar-nav navbar-left">
                      <li class="">
                        <h3>Summary VHS Stock</h3>
                      </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                      <li class="">
                        <h3>
                          <a style="margin-right:20px;">
                            <?php echo date("l");echo date(", d-m-Y");?>
                          </a>
                        </h3>
                      </li>
                    </ul>
                </div>
            </div>   
            <div class="right_col" role="main">
                <div class="row top_tiles">   
                  <div class="animated flipInY col-lg-4 col-md-4 col-sm-12 col-xs-12">
                      <div class="tile-stats">
                        <div class="icon">
                        </div>
                        <div class="count">
                          <?php echo empty($supplied) ? "0" : $supplied;?>
                        </div>
                        <h3>Supply</h3>
                        <p>Already supplied  
                          <a class="panel-heading" role="tab" id="headingC" data-toggle="collapse" data-parent="#accordion" href="#collapseC" aria-expanded="false" aria-controls="collapseC">
                            <button><span class="fa fa-search"></span></button>
                          </a>
                          <div id="collapseC" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingC">
                                  <div class="panel-body">
                                    <table class="table table-bordered">
                                      <thead>
                                        <tr style="text-align: center;background-color:#B8BFF1;">
                                          <th>Site</th>
                                          <th>Size</th>
                                          <th>Qty</th>
                                        </tr>
                                      </thead>
                                      <tbody>                                
                                        <?php
                                            $perintah3 = mysqli_query($koneksi6,"SELECT b.location,c.size,count(a.id_stock) as quantity 
                                                                                FROM stock a,storeloc b,part_number c
                                                                                WHERE a.id_storeloc=b.id_storeloc and a.id_part_number=c.id_part_number
                                                                                GROUP BY c.size,b.location 
                                                                                ORDER BY b.location,c.size");
                                            while ($data=mysqli_fetch_array($perintah3)){
                                                ?>
                                                <tr>
                                                  <td><?php echo $data['location'];?></td>
                                                  <td><?php echo $data['size'];?></td>
                                                  <td><?php echo $data['quantity'];?></td>
                                                </tr>
                                                <?php 
                                            }
                                        ?>
                                      </tbody>
                                    </table>
                                  </div>
                          </div>
                        </p>
                      </div>
                  </div>      
                  <div class="animated flipInY col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="tile-stats">
                    <div class="icon"></div>
                    <div class="count">
                          <?php echo empty($summary['Done']) ? "0" : $summary['Done'];?>      
                    </div>
                    <h3>GR/GI</h3>
                    <p>Already done GR & GI
                    <a class="panel-heading" role="tab" id="headingD" data-toggle="collapse" data-parent="#accordion" href="#collapseD" aria-expanded="false" aria-controls="collapseD">
                      <button><span class="fa fa-search"></span></button>
                    </a>
                    <div id="collapseD" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingD">
                                  <div class="panel-body">
                                    <table class="table table-bordered">
                                      <thead>
                                        <tr style="text-align: center;background-color:#B8BFF1;">
                                          <th>Site</th>
                                          <th>Size</th>
                                          <th>Qty</th>
                                        </tr>
                                      </thead>
                                      <tbody>                                
                                        <?php
                                            $perintah3 = mysqli_query($koneksi6,"SELECT b.location,c.size,count(a.id_stock) as quantity 
                                                                                FROM stock a,storeloc b,part_number c
                                                                                WHERE a.id_storeloc=b.id_storeloc and a.id_part_number=c.id_part_number and a.status='Done'
                                                                                GROUP BY c.size,b.location 
                                                                                ORDER BY b.location,c.size");
                                            while ($data=mysqli_fetch_array($perintah3)){
                                                ?>
                                                <tr>
                                                  <td><?php echo $data['location'];?></td>
                                                  <td><?php echo $data['size'];?></td>
                                                  <td><?php echo $data['quantity'];?></td>
                                                </tr>
                                                <?php 
                                            }
                                        ?>
                                      </tbody>
                                    </table>
                                  </div>
                    </div>
                        </p>
                    </div>
                  </div> 
                  <div class="animated flipInY col-lg-4 col-md-4 col-sm-12 col-xs-12">
                      <div class="tile-stats">
                        <div class="icon">
                        </div>
                        <div class="count">
                          <?php echo empty($summary['onsite']) ? "0" : $summary['onsite'];?>
                        </div>
                        <h3>On hand</h3>
                        <p>On hand stock  
                          <a class="panel-heading" role="tab" id="headingA" data-toggle="collapse" data-parent="#accordion" href="#collapseA" aria-expanded="false" aria-controls="collapseA">
                            <button><span class="fa fa-search"></span></button>
                          </a>
                          <div id="collapseA" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingA">
                                  <div class="panel-body">
                                    <table class="table table-bordered">
                                      <thead>
                                        <tr style="text-align: center;background-color:#B8BFF1;">
                                          <th>Site</th>
                                          <th>Size</th>
                                          <th>Qty</th>
                                        </tr>
                                      </thead>
                                      <tbody>                                
                                        <?php
                                            $perintah3 = mysqli_query($koneksi6,"SELECT b.location,c.size,count(a.id_stock) as quantity 
                                                                                FROM stock a,storeloc b,part_number c
                                                                                WHERE a.id_storeloc=b.id_storeloc and a.id_part_number=c.id_part_number and a.status='onsite'
                                                                                GROUP BY c.size,b.location 
                                                                                ORDER BY b.location,c.size");
                                            while ($data=mysqli_fetch_array($perintah3)){
                                                ?>
                                                <tr>
                                                  <td><?php echo $data['location'];?></td>
                                                  <td><?php echo $data['size'];?></td>
                                                  <td><?php echo $data['quantity'];?></td>
                                                </tr>
                                                <?php 
                                            }
                                        ?>
                                      </tbody>
                                    </table>
                                  </div>
                          </div>
                        </p>
                      </div>
                  </div>
                </div>
                <div class="clearfix"></div>
                <div class="row" style="margin-top:0px">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>VHS stock summary</h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                
                                <div class="col-md-12 col-sm-12 col-xs-12"> 
                                    <table id="datatable-buttons" class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Site</th>
                                                <th>PN_CP</th>
                                                <th>MM_CK</th>
                                                <th>Size</th>
                                                <th>Brand</th>
                                                <th>Pattern</th>
                                                <th>Supply</th>
                                                <th>GR/GI</th>
                                                <th>Stock on (SAP)</th>
                                                <th>Stock Actual</th>
                                                <th>Outstanding GI</th>
                                                <th>Last Actual Update</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $actual=array();
                                                $last=array();
                                                $picinput=array();
                                                $sql = "
                                                    SELECT * 
                                                    FROM actual a
                                                    JOIN chitraparatama_ics.user b 
                                                        ON a.pic = b.id_user
                                                ";
                                                
                                                // Tambahkan filter kalau $idstoreloc tidak kosong
                                                if (!empty($idstoreloc)) {
                                                    $sql .= " WHERE a.id_storeloc = '$idstoreloc'";
                                                }
                                                
                                                $sql .= " ORDER BY id_actual";
                                                
                                                $perintah = mysqli_query($koneksi6, $sql);
                                                while ($data = mysqli_fetch_array($perintah)) {
                                                    $actual[$data['id_part_number']][$data['id_storeloc']] = $data['qty_actual'];
                                                    $last[$data['id_part_number']][$data['id_storeloc']] = $data['last_update'];
                                                    $picinput[$data['id_part_number']][$data['id_storeloc']] = $data['name'];
                                                }
                                                $sql = "
                                                    SELECT 
                                                        location,
                                                        a.id_part_number,
                                                        a.id_storeloc,
                                                        part_number,
                                                        mm_ck,
                                                        size,
                                                        brand,
                                                        pattern,
                                                        COUNT(a.id_stock) AS qty,
                                                        SUM(CASE WHEN a.gi IS NOT NULL AND a.gi <> '' THEN 1 ELSE 0 END) AS gi,
                                                        COUNT(a.id_stock) - SUM(CASE WHEN a.gi IS NOT NULL AND a.gi <> '' THEN 1 ELSE 0 END) AS onhand
                                                    FROM stock a
                                                    JOIN storeloc b ON a.id_storeloc = b.id_storeloc
                                                    JOIN part_number c ON a.id_part_number = c.id_part_number
                                                ";
                                                
                                                // Filter hanya kalau $idstoreloc ada isinya
                                                if (!empty($idstoreloc)) {
                                                    $sql .= " WHERE b.id_storeloc = '$idstoreloc'";
                                                }
                                                
                                                $sql .= " GROUP BY a.id_storeloc, a.id_part_number";
                                                
                                                $perintah = mysqli_query($koneksi6, $sql);
                                            while ($data = mysqli_fetch_array($perintah)){
                                                $idpartnumber=$data['id_part_number'];
                                                $idstrloc=$data['id_storeloc'];
                                            ?>
                                                <tr>
                                                    <td><?php echo $data['location'];?></td>
                                                    <td><?php echo $data['part_number'];?></td>
                                                    <td><?php echo $data['mm_ck'];?></td>
                                                    <td><?php echo $data['size'];?></td>
                                                    <td><?php echo $data['brand'];?></td>
                                                    <td><?php echo $data['pattern'];?></td>
                                                    <td><?php echo $data['qty'];?></td>
                                                    <td><?php echo $data['gi'];?></td>
                                                    <td><?php echo $data['onhand'];?></td>
                                                    <td><?php echo $actual[$idpartnumber][$idstrloc];?></td>
                                                    <td><?php echo $data['onhand']-$actual[$idpartnumber][$idstrloc];?></td>
                                                    <td><?php echo $last[$idpartnumber][$idstrloc];?></td>
                                                </tr>
                                            <?    
                                            }
                                            ?>    
                                        </tbody>
                                    </table> 
                                </div>
                            </div>
                        </div>
                </div>
                <div class="row" style="margin-top:0px">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Weekly GI</h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <?php
                                    $perintah = mysqli_query($koneksi6,"SELECT count(gi_date) as qty,WEEKOFYEAR(gi_date) as week FROM `stock` WHERE gi_date>'2020-01-01' AND year(gi_date)='$tahun'  group by week ");
                                    $tanggaltiaphari=array();
                                    while ($data = mysqli_fetch_array($perintah)){
                                        $weekgi[]=$data['week'];
                                        $weekqtygi[]=$data['qty'];
                                    }
                                    ?>
                                    
                                    <div id="mainbDailysum" style="height:250px;"></div> 
                                    
                                </div>
                            </div>
                        </div>
                </div>
            </div>  
        </div>
    </div>
    <footer>
      <div class="pull-right">
      </div>
      <div class="clearfix"></div>
    </footer>

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
    <script src="../vendors/pdfmake/build/pdfmake.min.js"></script>
    <script src="../vendors/pdfmake/build/vfs_fonts.js"></script>
    <!-- Chart.js -->
    <script src="../vendors/Chart.js/dist/Chart.min.js"></script>
    <!-- bootstrap-progressbar -->
    <script src="../vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
    <!-- ECharts -->
    <script src="../vendors/echarts/dist/echarts.min.js"></script>
    <script src="../vendors/echarts/map/js/world.js"></script>


    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>
    <script>window.jQuery || document.write('<script src=""../vendors/js/jquery.min.js"><\/script>')</script>
    <script src="../vendors/js/bootstrap.min.js"></script>
    <script src="../vendors/js/docs.min.js"></script>
    <!-- Datatables -->
    <script>
          $(document).ready(function() {
            var handleDataTableButtons = function() {
              if ($("#datatable-buttons").length) {
                $("#datatable-buttons").DataTable({
                  dom: "Bfrtip",
                  paging: false,
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
                  responsive: false,
                  order: [[0, 'desc']]
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

            $('#datatable').dataTable();

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
              'order': [[ 0, 'desc' ]],
              'columnDefs': [
                { orderable: true, targets: [0] }
              ]
            });
            
                
            TableManageButtons.init();
          });
        
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
        var dataStyle = {
            normal: {
              label: {
                show: false
              },
              labelLine: {
                show: false
              }
            }
        };
        var placeHolderStyle = {
            normal: {
              color: 'rgba(0,0,0,0)',
              label: {
                show: false
              },
              labelLine: {
                show: false
              }
            },
            emphasis: {
              color: 'rgba(0,0,0,0)'
            }
        };    
          //grafik pressure daily
      var echartBar = echarts.init(document.getElementById('mainbDailysum'), theme);
      function generateDateRangeLabels(startDate, endDate) {
        var labels = [];
        var currentDate = new Date(startDate);
        while (currentDate <= endDate) {
            labels.push(currentDate.toISOString().slice(0, 10));
            currentDate.setDate(currentDate.getDate() + 1);
            }
            return labels;
      }
      echartBar.setOption({
          title: {
            text: '',
            subtext: '<?php echo $tahun;?>'
          },
          tooltip: {
            trigger: 'axis'
          },
          legend: {
            data: ['Tire Checked', 'Low Pressure', 'Target Checked']
          },
          toolbox: {
            show: false
          },
          calculable: true,
          grid: {
            left: '4%',
            right: '4%',
            containLabel: true
          },
          xAxis: [{
            type: 'category',
            data: [
              <?php 
                foreach($weekgi as $tanggaltiaphari1){
                    echo "'".$tanggaltiaphari1."',";    
                } 
              ?>
            ]
          }],
          yAxis: [{
            splitLine: {
              show: false
            }
          }],
          dataZoom: [{}],
          series: [{
            name: 'Issued',
            type: 'bar',
            data: [
              <?php
                foreach($weekqtygi as $tanggaltiaphari4){
                    echo $tanggaltiaphari4 . ",";
                }
              ?>
            ]
          }]
        }); 
    </script>
  </body>
</html>