<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
  <?php 
    include "header.php"; // call sectionhead.php as library
  ?>
  <body class="nav-md">
      <div class="container body">
        <div class="main_container">
          <?php include "template_menu.php";?>   
          <!-- page content -->
          <div class="right_col" role="main">
            
          <?php if($name!=""){ ?>
            <div class="clearfix"></div>
            <div class="row"><div class="col-md-12 col-sm-6 col-xs-6">               
                <div class="row">
                      <div class="col-md-12 col-sm-6 col-xs-6">
                        <div class="x_panel">
                          <div class="x_content">
                            <?php
                                $size = array();
                                $age = array();
                                $qty = array();
                                $q2 = mysqli_query ($koneksi5,("SELECT a.tire_size,count(*) as qty,sum(IF(a.act_plan='Done',DATEDIFF(a.date_closed,a.date_in),IF(a.act_plan='Reject',DATEDIFF(a.date_accept,a.date_in),DATEDIFF(CURDATE(),a.date_in)))) as aging 
                                    FROM chitraparatama_warranty.tab_warranty a,chitraparatama_fleetlist.customer_master b
                                    where a.costumer=b.id_customer_master and act_plan!='Done' and act_plan!='Reject'
                                    GROUP BY a.tire_size"));                                
                                $loop = 0;
                                while($r2 = mysqli_fetch_array($q2)){
                                 $size[] = $r2['tire_size'];
                                 $age[] = $r2['aging'];
                                 $qty[] = $r2['qty'];
                                 $loop++;
                                }
                            ?>
                            <div id="mainb" style="height:350px;"></div>  
                            <table id="datatable-buttons" class="table table-striped table-bordered">
                              <thead style="background:#f5f5f5;">
                                <tr>
                                  <th></th>
                                  <?php foreach($size as $sz) { ?>
                                      <th><?php echo $sz; ?></th>
                                  <?php } ?>
                                </tr>
                              </thead>
                              <tbody>
                                  <tr>
                                      <th>Warranty Aging </th>
                                      <?php foreach($age as $aging) { ?>
                                          <td><?php echo $aging; ?> (days)</td>
                                      <?php } ?>
                                  </tr>
                            </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                </div>
              </div>
            </div>
          <?php } ?>
          
        </div>
      </div>  

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
    <!-- ECharts -->
    <script src="../vendors/echarts/dist/echarts.min.js"></script>
    <script src="../vendors/echarts/map/js/world.js"></script>


    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>
    <script>window.jQuery || document.write('<script src=""../vendors/js/jquery.min.js"><\/script>')</script>
    <script src="../vendors/js/bootstrap.min.js"></script>
    <script src="../vendors/js/docs.min.js"></script>
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
        var echartBar = echarts.init(document.getElementById('mainb'), theme);
        echartBar.setOption({
        title: {
          text: 'Tire warranty summary',
          subtext: 'Aging'
        },
        tooltip: {
          trigger: 'axis'
        },
        legend: {
          data: ['Aging','Quantity']
        },
          toolbox: {
            show: false,
            feature: {
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
        calculable: false,
        xAxis: [{
          type: 'category',
          data: [
                <?php 
                    foreach($size as $sz) {
                    echo "'"."$sz"."',";
                    } 
                ?>
              ]
        }],
        yAxis: [{
          type: 'value'
        },
        {
         type: 'value',
         name: 'Quantity',
         position: 'right'
        }],
        series: [{
          name: 'Aging',
          type: 'bar',
          data: [
                <?php 
                    foreach($age as $aging) {
                    echo "'"."$aging"."',";
                    } 
                ?>
              ]
        },{
          name: 'Quantity',
          type: 'bar',
          yAxisIndex: 1,
          data: [
                <?php 
                    foreach($qty as $qt) {
                    echo "'"."$qt"."',";
                    } 
                ?>
              ]
        }]
      });
    </script>
  </body>
</html>