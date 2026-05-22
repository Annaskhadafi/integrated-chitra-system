<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
<?php include'header.php';?>
  <body class="nav-md">
    <div class="container body">
        <div class="main_container">
            <div class="col-md-3 left_col">
                <a href="halamanDataMaster.php" class="site_title"></a>
                <div class="navbar nav_title" style="border: 0;"></div>
                <div class="clearfix"></div>
                <br>
                <?php include "template_menu.php";
                    $selectmtrl = isset($_GET['material']) ? $_GET['material'] : "All";
                    $selectloc = isset($_GET['location']) ? $_GET['location'] : "All";
                    
                    $perintah3 = mysqli_query($koneksi2,"SELECT material FROM mining_company GROUP BY material");
                    while ($data3 = mysqli_fetch_array($perintah3)) {
                        $material[]=$data3['material'];
                    }
                    $perintah4 = mysqli_query($koneksi2,"SELECT location FROM site_master GROUP BY location");
                    while ($data4 = mysqli_fetch_array($perintah4)) {
                        $location[]=$data4['location'];
                    }
                    if($selectmtrl=="All" && $selectloc=="All"){
                        $perintah = mysqli_query($koneksi2,"SELECT * FROM mining_company WHERE year(tgl_update) like '2025%' ORDER BY target DESC limit 10");    
                        $perintah2 =mysqli_query($koneksi2,"SELECT b.customer,sum(a.target) as target FROM site_master a,customer_master b where a.id_customer=b.id_customer_master and year_update= $tahun group by a.id_customer ORDER BY target DESC limit 10");
                    
                    }
                    else{
                        if($selectmtrl=="All"){        
                            $perintah = mysqli_query($koneksi2,"SELECT DISTINCT a.mining_company,a.target FROM mining_company a,site_master b where a.id_mining=b.mining_company and b.location='$selectloc' and year(tgl_update)= $tahun ORDER BY a.target DESC limit 10");
                            $perintah2 =mysqli_query($koneksi2,"SELECT b.customer,sum(a.target) as target FROM site_master a,customer_master b where a.id_customer=b.id_customer_master and a.location='$selectloc' and year_update= $tahun group by a.id_customer ORDER BY target DESC limit 10");
                        }
                        elseif($selectloc=="All"){        
                            $perintah = mysqli_query($koneksi2,"SELECT * FROM mining_company where material='$selectmtrl' and year(tgl_update)= $tahun ORDER BY target DESC limit 10");
                            $perintah2 =mysqli_query($koneksi2,"SELECT b.customer,sum(a.target) as target FROM site_master a,customer_master b,mining_company c where a.id_customer=b.id_customer_master and a.mining_company=c.id_mining and c.material='$selectmtrl' and year_update= $tahun group by a.id_customer ORDER BY target DESC limit 10");
                        }
                        
                        else{        
                            $perintah = mysqli_query($koneksi2,"SELECT DISTINCT a.mining_company,a.target FROM mining_company a,site_master b where a.id_mining=b.mining_company and a.material='$selectmtrl' and b.location='$selectloc' and year_update= $tahun ORDER BY a.target DESC limit 10");
                            $perintah2 =mysqli_query($koneksi2,"SELECT b.customer,sum(a.target) as target FROM site_master a,customer_master b,mining_company c where a.id_customer=b.id_customer_master and a.mining_company=c.id_mining and a.location='$selectloc' and c.material='$selectmtrl' and year_update= $tahun group by a.id_customer ORDER BY target DESC limit 10");
                        }
                    }
                    while ($data = mysqli_fetch_array($perintah)) {
                        $mincom[]=$data['mining_company'];
                        $target[]=$data['target'];
                    }
                    $mincom = array_reverse($mincom);
                    $target = array_reverse($target);
                    
                    while ($data2 = mysqli_fetch_array($perintah2)) {
                        $contractor[]=$data2['customer'];
                        $targetcontractor[]=$data2['target'];
                    }
                    $contractor = array_reverse($contractor);
                    $targetcontractor = array_reverse($targetcontractor);
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
                        <h3>Summary Mining Information <?php echo $tahun;?></h3>
                        <div class="btn-group">
                              <button type="button" class="btn btn-primary"><?php echo $selectmtrl;?></button>
                              <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><span class="caret"></span><span class="sr-only">Toggle Dropdown</span>
                              </button>
                              <ul class="dropdown-menu" role="menu">
                                <li><a href="halamansummarymincom.php?material=All&location=<?php echo $selectloc;?>">All Material</a></li>
                                <?php foreach($material as $mtrloop){?>
                                    <li><a href="halamansummarymincom.php?material=<?php echo $mtrloop;?>&location=<?php echo $selectloc;?>"><?php echo $mtrloop;?></a></li>
                                <?php } ?>
                              </ul>
                        </div>
                        <div class="btn-group">
                              <button type="button" class="btn btn-primary"><?php echo $selectloc;?></button>
                              <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                              </button>
                              <ul class="dropdown-menu pre-scrollable-side" role="menu" >
                                <li><a href="halamansummarymincom.php?material=<?php echo $selectmtrl;?>&location=All">All Location</a></li>
                                <?php foreach($location as $loc){?>
                                    <li><a href="halamansummarymincom.php?material=<?php echo $selectmtrl;?>&location=<?php echo $loc;?>"><?php echo $loc; ?></a></li>
                                <?php } ?>
                              </ul>
                        </div>
                        <!--<div class="btn-group">-->
                        <!--      <button type="button" class="btn btn-primary">Top 10</button>-->
                        <!--      <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">-->
                        <!--        <span class="caret"></span>-->
                        <!--        <span class="sr-only">Toggle Dropdown</span>-->
                        <!--      </button>-->
                        <!--      <ul class="dropdown-menu" role="menu" >-->
                        <!--        <li><a href="halamansummarymincom.php?material=<?php echo $mtrl;?>&location=All">Top 20</a></li>-->
                        <!--        <li><a href="halamansummarymincom.php?material=<?php echo $mtrl;?>&location=All">Top 50</a></li>-->
                        <!--      </ul>-->
                        <!--</div>-->
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
                    <div class="clearfix"></div>
                    <div class="row" style="margin-top:0px">
                        <div class="x_panel">
                          <div class="x_content">
                            <div id="echart_bar_horizontal" style="height:370px;"></div>
                          </div>
                        </div>
                    </div>
                    <div class="row" style="margin-top:0px">
                        <div class="x_panel">
                          <div class="x_content">
                            <div id="echart_bar_horizontal2" style="height:370px;"></div>
                          </div>
                        </div>
                    </div>
                </div>  
        </div>
    </div>
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-ku">
            <div class="modal-content">
                <div class="modal-body">
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
    <!-- ECharts -->
    <script src="../vendors/echarts/dist/echarts.min.js"></script>
    <script src="../vendors/echarts/map/js/world.js"></script>
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


    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>
    <script>window.jQuery || document.write('<script src=""../vendors/js/jquery.min.js"><\/script>')</script>
    <script src="../vendors/js/bootstrap.min.js"></script>
    <script src="../vendors/js/docs.min.js"></script>
    <!-- Datatables -->
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
      var theme2 = {
          color: [
              '#34495E', '#BDC3C7', '#3498DB',
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
        var echartBar = echarts.init(document.getElementById('echart_bar_horizontal'), theme);
        echartBar.setOption({
        title: {
          text: 'Top 10 Mining Company',
          subtext: 'In <?php echo $selectloc;?> area'
        },
        tooltip: {
          trigger: 'axis'
        },
        legend: {
          x: 178,
          y: 32,
          data: ['Target Production']
        },
        toolbox: {
          show: true,
          feature: {
            saveAsImage: {
              show: true,
              title: "Save Image"
            }
          }
        },
        calculable: true,
        xAxis: [{
          type: 'value',
          boundaryGap: [0, 0.01]
        }],
        yAxis: [{
          type: 'category',
          data: [<?php foreach($mincom as $company){echo "'".$company."',";} ?>]
        }],
        series: [{
          name: 'Target Production',
          type: 'bar',
          data: [<?php foreach($target as $trgt){echo "'".$trgt."',";} ?>],
          color: ['#dd6b66']
        }]
      });
      
        var echartBar2 = echarts.init(document.getElementById('echart_bar_horizontal2'), theme2);
        echartBar2.setOption({
        title: {
          text: 'Top 10 Mining Contractor',
          subtext: 'In <?php echo $selectloc;?> area'
        },
        tooltip: {
          trigger: 'axis'
        },
        legend: {
          x: 178,
          y: 32,
          data: ['Target Production']
        },
        toolbox: {
          show: true,
          feature: {
            saveAsImage: {
              show: true,
              title: "Save Image"
            }
          }
        },
        calculable: true,
        xAxis: [{
          type: 'value',
          boundaryGap: [0, 0.01]
        }],
        yAxis: [{
          type: 'category',
          data: [<?php foreach($contractor as $cont){echo "'".$cont."',";} ?>]
        }],
        series: [{
          name: 'Target Production',
          type: 'bar',
          data: [<?php foreach($targetcontractor as $trgtc){echo "'".$trgtc."',";} ?>],
          color: ['#759aa0']
        }]
      });
    </script>
  </body>
</html>