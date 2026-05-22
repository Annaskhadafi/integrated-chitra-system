<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
  <?php 
    include "header.php"; // call sectionhead.php as library
  ?>
  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <?php 
          include "template_menu.php";
          $year=date("Y");
        ?>
        <?php if($name!=""){ ?>
        <!-- page content -->        
        <div class="right_col" role="main">
          <div class="clearfix"></div>
          <div class="row">
            <div class="col-md-8 col-sm-8 col-xs-6">
              <div class="x_panel">
                <div class="x_content">
                  <div class="x_title">
                    <h3>Repair status <?php echo $year;?></h3>
                  </div>
                  <div id="echart_line" style="height:250px;"></div>
                  <!--<canvas id="mybarChart" style="height:250px;"></canvas>-->
                  <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>Cust_name</th>
                          <th>Inspect</th>
                          <th>Reject</th>
                          <th>Progress</th>
                          <th>BAST/PO</th>
                          <th>Complete</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                        $perintah = mysqli_query($koneksi3, "SELECT * FROM customer_data");
                        while ($data = mysqli_fetch_array($perintah)) { 
                          $idcust=$data['id_cust'];?>
                          <tr>
                            <th><?php echo $data['cust_name'];?></th>
                            <?php 
                              $perintah2=mysqli_query($koneksi3, "SELECT count(id_wo) as ins from work_order a,customer b where a.status=1 and a.received_date like '$year%' and a.customer=b.id_customer and b.nama_customer=$idcust ");
                              $datains=mysqli_fetch_assoc($perintah2); 
                              $jumlahinspect=$datains['ins'];
                              $perintah3=mysqli_query($koneksi3, "SELECT count(id_wo) as pro from work_order a,customer b where a.status=2 and a.received_date like '$year%' and a.customer=b.id_customer and b.nama_customer=$idcust ");
                              $datapro=mysqli_fetch_assoc($perintah3); 
                              $jumlahprog=$datapro['pro'];
                              $perintah4=mysqli_query($koneksi3, "SELECT count(id_wo) as rej from work_order a,customer b where a.status=3 and a.received_date like '$year%' and a.customer=b.id_customer and b.nama_customer=$idcust ");
                              $datarej=mysqli_fetch_assoc($perintah4); 
                              $jumlahrej=$datarej['rej'];
                              $perintah5=mysqli_query($koneksi3, "SELECT count(id_wo) as bp from work_order a,customer b where a.status=4 and a.received_date like '$year%' and a.customer=b.id_customer and b.nama_customer=$idcust ");
                              $databp=mysqli_fetch_assoc($perintah5); 
                              $jumlahbp=$databp['bp'];
                              $perintah6=mysqli_query($koneksi3, "SELECT count(id_wo) as com from work_order a,customer b where a.status=5 and a.received_date like '$year%' and a.customer=b.id_customer and b.nama_customer=$idcust ");
                              $datacom=mysqli_fetch_assoc($perintah6); 
                              $jumlahcom=$datacom['com'];
                            ?>
                            <td><?php echo $jumlahinspect;?></td>
                            <td><?php echo $jumlahrej;?></td>
                            <td><?php echo $jumlahprog;?></td>
                            <td><?php echo $jumlahbp;?></td>
                            <td><?php echo $jumlahcom;?></td>
                          </tr><?php 
                        }?>
                      </tbody>
                  </table>              
                </div>
              </div>
            </div> 
            <div class="col-md-4 col-sm-4 col-xs-12">
              <div class="x_panel">
              </div>
            </div> 
          </div>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">              
              <div class="x_panel">
                <div class="x_content">
                  <div class="x_title"><h3>Material avg consumption <?php echo $tahun;?></h3></div>            
                  <div class="x_content">
                    <?php 
                      $BulanIni= date('n');
                      $bulan = array("01","02","03","04","05","06","07","08","09","10","11","12");
                    ?>                   
                    <table class="table table-bordered">
                      <thead>
                        <tr style="background-color:#B8BFF1;">
                          <th></th>
                          <th>Jan</th>
                          <th>Feb</th>
                          <th>Mar</th>
                          <th>Apr</th>
                          <th>May</th>
                          <th>Jun</th>
                          <th>Jul</th>
                          <th>Aug</th>
                          <th>Sep</th>
                          <th>Oct</th>
                          <th>Nov</th>
                          <th>Dec</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php 
                        $perintahInv = mysqli_query($koneksi3, "SELECT a.desc FROM mat_inventory a group by a.desc");
                          while ($dataInv = mysqli_fetch_array($perintahInv)) { 
                          $nmainv=$dataInv['desc'];?>
                            <tr>
                              <th scope="row"><?php echo $nmainv;?></th>              
                              <?php                 
                                foreach ($bulan as $month){
                                  if ($month <= $BulanIni){
                                    //hitung rata rata penggunaan material CRP76
                                    $perintahAVG = mysqli_query($koneksi3, "SELECT AVG(a.qty) AS Avg FROM mat_usage a,job b,mat_inventory c where a.job=b.id_job and b.date like '$tahun-$month-%' and b.wo>0 and a.inv=c.id_inv and c.desc='$nmainv'");
                                    $dataAVG=mysqli_fetch_array($perintahAVG);
                                    $avg = str_replace(",", "", number_format($dataAVG['Avg']));
                                            }
                                            else{
                                              $avg = 0;
                                            }?>
                                            <td><?php echo $avg;?></td>
                                            <?php
                                          }
                              ?>
                            </tr>
                            <?php
                          } ?>
                      </tbody>
                    </table>  
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="clearfix"></div>
        </div>
        <?php } ?>
      </div>
    </div>
    <!-- modal edit data tire inventory -->
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
    <!-- ECharts -->
    <script src="../vendors/echarts/dist/echarts.min.js"></script>
    <script src="../vendors/echarts/map/js/world.js"></script>
    <!-- iCheck -->
    <script src="../vendors/iCheck/icheck.min.js"></script>
    <!-- Chart.js -->
    <script src="../vendors/Chart.js/dist/Chart.min.js"></script>
    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>
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
      var echartLine = echarts.init(document.getElementById('echart_line'), theme);
      echartLine.setOption({
        title: {
          text: 'Line Graph',
          // subtext: 'Subtitle'
        },
        tooltip: {
          trigger: 'axis'
        },
        // legend: {
        //     data: 
        //     [
        //       <?php 
        //         $id_cust=mysqli_query($koneksi3, "SELECT b.nama_customer from work_order a,customer b where a.customer=b.id_customer and group by b.nama_customer");
        //         $i=1;
        //         while ($data=mysqli_fetch_array($id_cust)){
        //           $idcs=$data['id_cust'];
        //           echo "'".$idcs."'".",";
        //           $i++;
        //         }
        //       ?> 
        //     ]
        //   },

        toolbox: {
          show: true,
          feature: {
            magicType: {
              show: true,
              title: {
                bar: 'Bar',
                line: 'Line',
                stack: 'Stack',
                tiled: 'Tiled'
              },
              type: ['bar','line','stack', 'tiled']
            },
            restore: {
              show: true,
              title: "Restore"
            },
            saveAsImage: {
              show: true,
              title: "Save Image"
            }
          }
        },
        calculable: true,
        xAxis: [{
          type: 'category',
          boundaryGap: true,
          data: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul','Agst', 'Sept', 'Okt', 'Nov', 'Des']
        }],
        yAxis: [{
          type: 'value'
        }],
        series: [{
          name: 'PT. KAYAN PUTRA UTAMA COAL [Reject]',
          type: 'bar',
          stack: 'Ad',
          smooth: true,
          itemStyle: {
            normal: {
              areaStyle: {
                type: 'default'
              }
            }
          },
          data: [0, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
        }, {
          name: 'PT. KAYAN PUTRA UTAMA COAL [Complete]',
          type: 'bar',
          stack: 'Ad',
          smooth: true,
          itemStyle: {
            normal: {
              areaStyle: {
                type: 'default'
              }
            }
          },
          data: [0, 0, 0, 0, 0, 8, 0, 4, 0, 2, 0, 0]
        }, {
          name: 'PT. CIPTA KRIDATAMA [Reject]',
          type: 'bar',
          stack: 'Ad',
          smooth: true,
          itemStyle: {
            normal: {
              areaStyle: {
                type: 'default'
              }
            }
          },
          data: [0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
        },{
          name: 'PT. CIPTA KRIDATAMA [Complete]',
          type: 'bar',
          stack: 'Ad',
          smooth: true,
          itemStyle: {
            normal: {
              areaStyle: {
                type: 'default'
              }
            }
          },
          data: [0, 0, 0, 0, 0, 51, 16, 17, 19, 13, 0, 1]
        },{
          name: 'PT. PETROSEA [Reject]',
          type: 'bar',
          stack: 'Ad',
          smooth: true,
          itemStyle: {
            normal: {
              areaStyle: {
                type: 'default'
              }
            }
          },
          data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
        },{
          name: 'PT. PETROSEA [Complete]',
          type: 'bar',
          stack: 'Ad',
          smooth: true,
          itemStyle: {
            normal: {
              areaStyle: {
                type: 'default'
              }
            }
          },
          data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
        },{
          name: 'PT. KALTIM PRIMA COAL [Reject]',
          type: 'bar',
          stack: 'Ad',
          smooth: true,
          itemStyle: {
            normal: {
              areaStyle: {
                type: 'default'
              }
            }
          },
          data: [0, 3, 0, 2, 0, 0, 0, 0, 0, 0, 0, 0]
        },{
          name: 'PT. KALTIM PRIMA COAL [Complete]',
          type: 'bar',
          stack: 'Ad',
          smooth: true,
          itemStyle: {
            normal: {
              areaStyle: {
                type: 'default'
              }
            }
          },
          data: [0, 0, 0, 0, 70, 74, 75, 71, 73, 0, 0, 0]
        },{
          name: 'PT. PUTRA PERKASA ABADI [Reject]',
          type: 'bar',
          stack: 'Ad',
          smooth: true,
          itemStyle: {
            normal: {
              areaStyle: {
                type: 'default'
              }
            }
          },
          data: [0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
        },{
          name: 'PT. PUTRA PERKASA ABADI [Complete]',
          type: 'bar',
          stack: 'Ad',
          smooth: true,
          itemStyle: {
            normal: {
              areaStyle: {
                type: 'default'
              }
            }
          },
          data: [0, 0, 0, 0, 0, 0, 0, 0, 13, 0, 0, 0]
        },{
          name: 'PT. BUKIT MAKMUR MANDIRI UTAMA [Reject]',
          type: 'bar',
          stack: 'Ad',
          smooth: true,
          itemStyle: {
            normal: {
              areaStyle: {
                type: 'default'
              }
            }
          },
          data: [1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
        },{
          name: 'PT. BUKIT MAKMUR MANDIRI UTAMA [Complete]',
          type: 'bar',
          stack: 'Ad',
          smooth: true,
          itemStyle: {
            normal: {
              areaStyle: {
                type: 'default'
              }
            }
          },
          data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
        },{
          name: 'PT. SAPTAINDRA SEJATI [Reject]',
          type: 'bar',
          stack: 'Ad',
          smooth: true,
          itemStyle: {
            normal: {
              areaStyle: {
                type: 'default'
              }
            }
          },
          data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
        },{
          name: 'PT. SAPTAINDRA SEJATI [Complete]',
          type: 'bar',
          stack: 'Ad',
          smooth: true,
          itemStyle: {
            normal: {
              areaStyle: {
                type: 'default'
              }
            }
          },
          data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
        },{
          name: 'PT. ULIMA NITRA [Reject]',
          type: 'bar',
          stack: 'Ad',
          smooth: true,
          itemStyle: {
            normal: {
              areaStyle: {
                type: 'default'
              }
            }
          },
          data: [0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0]
        },{
          name: 'PT. ULIMA NITRA [Complete]',
          type: 'bar',
          stack: 'Ad',
          smooth: true,
          itemStyle: {
            normal: {
              areaStyle: {
                type: 'default'
              }
            }
          },
          data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
        },{
          name: 'PT. ABADI JAYA LAXMINDO [Reject]',
          type: 'bar',
          stack: 'Ad',
          smooth: true,
          itemStyle: {
            normal: {
              areaStyle: {
                type: 'default'
              }
            }
          },
          data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0]
        },{
          name: 'PT. ABADI JAYA LAXMINDO [Complete]',
          type: 'bar',
          stack: 'Ad',
          smooth: true,
          itemStyle: {
            normal: {
              areaStyle: {
                type: 'default'
              }
            }
          },
          data: [0, 0, 0, 0, 0, 0, 0, 0, 8, 0, 0, 0]
        },{
          name: 'PT.CAKRAWALA DINAMIKA ENERGI [Reject]',
          type: 'bar',
          stack: 'Ad',
          smooth: true,
          itemStyle: {
            normal: {
              areaStyle: {
                type: 'default'
              }
            }
          },
          data: [0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0]
        },{
          name: 'PT.CAKRAWALA DINAMIKA ENERGI [Complete]',
          type: 'bar',
          stack: 'Ad',
          smooth: true,
          itemStyle: {
            normal: {
              areaStyle: {
                type: 'default'
              }
            }
          },
          data: [0, 0, 0, 0, 0, 0, 0, 0, 6, 0, 0, 0]
        },{
          name: 'PT. INDO MURO KENCANA [Reject]',
          type: 'bar',
          stack: 'Ad',
          smooth: true,
          itemStyle: {
            normal: {
              areaStyle: {
                type: 'default'
              }
            }
          },
          data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
        },{
          name: 'PT. INDO MURO KENCANA [Complete]',
          type: 'bar',
          stack: 'Ad',
          smooth: true,
          itemStyle: {
            normal: {
              areaStyle: {
                type: 'default'
              }
            }
          },
          data: [0, 0, 0, 0, 0, 0, 0, 0, 5, 0, 0, 0]
        },{
          name: 'PT. NUSA PERDANA INDAH [Reject]',
          type: 'bar',
          stack: 'Ad',
          smooth: true,
          itemStyle: {
            normal: {
              areaStyle: {
                type: 'default'
              }
            }
          },
          data: [0, 7, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
        },{
          name: 'PT. NUSA PERDANA INDAH [Complete]',
          type: 'bar',
          stack: 'Ad',
          smooth: true,
          itemStyle: {
            normal: {
              areaStyle: {
                type: 'default'
              }
            }
          },
          data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
        },{
          name: 'PT. BINA PERTIWI [Reject]',
          type: 'bar',
          stack: 'Ad',
          smooth: true,
          itemStyle: {
            normal: {
              areaStyle: {
                type: 'default'
              }
            }
          },
          data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
        },{
          name: 'PT. BINA PERTIWI [Complete]',
          type: 'bar',
          stack: 'Ad',
          smooth: true,
          itemStyle: {
            normal: {
              areaStyle: {
                type: 'default'
              }
            }
          },
          data: [0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0]
        },{
          name: 'PT. PAMAPERSADA NUSANTARA [Reject]',
          type: 'bar',
          stack: 'Ad',
          smooth: true,
          itemStyle: {
            normal: {
              areaStyle: {
                type: 'default'
              }
            }
          },
          data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
        },{
          name: 'PT. PAMAPERSADA NUSANTARA [Complete]',
          type: 'bar',
          stack: 'Ad',
          smooth: true,
          itemStyle: {
            normal: {
              areaStyle: {
                type: 'default'
              }
            }
          },
          data: [0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0]
        },]
      
      });
    </script>
  </body>
</html>

  </body>
</html>
