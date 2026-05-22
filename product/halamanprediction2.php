<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
<?php 
include 'header.php';
    
// Contoh data dari model
$dates_str = $_GET['dates'];
$prediction_str = $_GET['prediction'];
    
$thn = date("Y");
$arrdata =  explode(',', $_POST['array_data']);
// print_r($arrdata);
$dates_array = explode(',', $dates_str);
$prediction_array = explode(',', $prediction_str);
$count = count($prediction_array);

$cleanprediction = array();

foreach ($prediction_array as $prediction) {
    // Menghilangkan bagian yang tidak diinginkan dari string prediksi
    $clean_prediction = str_replace(array("{'predicted_ytdrevenue': ", "}"), "", $prediction);

    // Mengganti None dengan 0
    if ($clean_prediction === 'None') {
        $clean_prediction = 0;
    }

    // Tambahkan ke array bersih
    $cleanprediction[] = $clean_prediction;    
}

// Contoh lainnya untuk manipulasi tanggal
$start = ($dates_array[0] == "") ? new DateTime($_POST['start']) : new DateTime($dates_array[0]); 
$end = (end($dates_array) == "") ? new DateTime($_POST['end']) : new DateTime(end($dates_array));
    
$diff = $start->diff($end);
$months = $diff->y * 12 + $diff->m;
// Menambahkan 1 bulan ke tanggal akhir untuk memastikan bulan terakhir dimasukkan dalam iterasi
$end->modify('+1 month');
// Buat objek DatePeriod untuk mengiterasi melalui rentang tanggal
$interval = new DateInterval('P1M'); // Interval bulanan
$period = new DatePeriod($start, $interval, $end);
?>
  <body class="nav-md">
    <div class="container body">
        <div class="main_container">
            <div class="col-md-3 left_col">
                <a href="halamanDataMaster.php" class="site_title"></a>
                <div class="navbar nav_title" style="border: 0;"></div>
                <div class="clearfix"></div>
                <br>
                <?php include "template_menu.php";?>
            </div>c
            <div class="top_nav">
                <div class="nav_menu">
                    <div class="nav toggle">
                      <a id="menu_toggle">
                        <i class="fa fa-bars">
                        </i>
                      </a>
                    </div>
                    <ul class="nav navbar-nav navbar-right">
                      <li class="">
                        <h3>
                          <a style="margin-right:20px;">
                            <?php 
                                echo date("l");echo date(", d-m-Y");
                            ?>
                          </a>
                        </h3>
                      </li>
                    </ul>
                </div>
            </div>   
            <div class="right_col" role="main">
                    <div class="row" style="margin-top:0px">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2><b>Commodity Revenue Prediction</b></h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <form action="prediksi2.php" method="POST" class="form-horizontal">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th style="text-align: center;">Month</th>
                                                <th style="text-align: center;">IDR to USD Exchange Rates</th>
                                                <th style="text-align: center;">Coal Price GAR 6500 in USD</th>
                                                <th style="text-align: center;">Coal Price GAR 5800 in USD</th>
                                                <th style="text-align: center;">Coal Price GAR 5000 in USD</th>
                                                <th style="text-align: center;">Coal Price GAR 4200 in USD</th>
                                                <th style="text-align: center;">Coal Price GAR 3400 in USD</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            // Iterasi melalui rentang tanggal dan mencetak tahun dan bulan
                                            $datetopred = array();
                                            $leadtime = array();
                                            $coalprice = array();
                                            foreach ($period as $date) {
                                                $dt = $date->format('Y-m'); ?>
                                                <tr>
                                                    <td style="text-align: center;">
                                                        <input type="hidden" name="date[]" class="form-control" value="<?php echo $dt; ?>">
                                                        <?php echo $dt; ?>
                                                    </td>
                                                    <td>
                                                        <input type="number" name="exchangerate[]" class="form-control" step="0.01" required>
                                                    </td>
                                                    <td>
                                                        <input type="number" name="ici_index_6500[]" class="form-control" step="0.01" required>
                                                    </td>
                                                    <td>
                                                        <input type="number" name="ici_index_5800[]" class="form-control" step="0.01" required>
                                                    </td>
                                                    <td>
                                                        <input type="number" name="ici_index_5000[]" class="form-control" step="0.01" required>
                                                    </td>
                                                    <td>
                                                        <input type="number" name="ici_index_4200[]" class="form-control" step="0.01" required>
                                                    </td>
                                                    <td>
                                                        <input type="number" name="ici_index_3400[]" class="form-control" step="0.01" required>
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                    <div class="form-group">
                                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                            <button type="submit" class="btn btn-success">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!--Prediksi Revenue-->
                    <div class="row" style="margin-top:0px">
                        <div class="x_panel">
                            <div class="x_content">
							  	<div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="x_panel">
                                  <div class="x_title">
                                    <h2>Revenue Prediction</h2>
                                    <div class="clearfix"></div>
                                  </div>
                                  <?php 
                                  $donepred=isset($dates_str) ? 1:0;
                                  if($donepred==1){ ?>
                                    <div class="x_content">
                                        <div id="echart_line" style="height: 250px;"></div><br><br>
                                        <?php
                                            $actualrev=array();
                                            $actualqty=array();
                                            $perintah = mysqli_query($koneksi,"SELECT * FROM coalPrice a WHERE year(a.date)=$thn");
                                            while ($data=mysqli_fetch_array($perintah)){
                                                $actualrev[]=$data['mtdrevenue'];
                                            }
                                        ?>
                                        <table class="table table-bordered">
        							        <tr>
        							            <th></th>
        							            <?php
        							                foreach ($dates_array as $date) {
                                                        $month_name = date('F', strtotime($date));
                                                        echo "<th style='text-align: center;'>".$month_name."</th>";
                                                    }
                                                ?>
        							        </tr>
        							        <tr>
        							        <th>Actual</th>
        							        <?php
                                            for ($i = 0; $i < $count; $i++) {
                                                echo "<td>0</td>";
                                            }
                                            ?>
        							        </tr>
        							        <tr>
        							        <th>Prediction</th>
        							        <?php
                                            for ($i = 0; $i < $count; $i++) {
                                                echo "<td>".number_format($cleanprediction[$i])."</td>";
                                            }
                                            ?>
        							        </tr>
    							    	</table>
                                    </div>    
                                      <?php
                                  }
                                  else{ ?>
                                    <div class="x_content">
                                        <div id="echart_line" style="height: 250px;"></div><br><br>
                                        <?php
                                            $actualrev=array();
                                            $actualqty=array();
                                            $perintah = mysqli_query($koneksi,"SELECT * FROM coalPrice a WHERE year(a.date)=$thn");
                                            while ($data=mysqli_fetch_array($perintah)){
                                                $actualrev[]=$data['mtdrevenue'];
                                            }
                                        ?>
                                        <table class="table table-bordered">
        							        <tr>
        							            <th></th>
        							            <th style="text-align: center;">Jan</th>
        							            <th style="text-align: center;">Feb</th>
        							            <th style="text-align: center;">Mar</th>
        							            <th style="text-align: center;">Apr</th>
        							            <th style="text-align: center;">May</th>
        							            <th style="text-align: center;">Jun</th>
        							            <th style="text-align: center;">Jul</th>
        							            <th style="text-align: center;">Aug</th>
        							            <th style="text-align: center;">Sep</th>
        							            <th style="text-align: center;">Oct</th>
        							            <th style="text-align: center;">Nov</th>
        							            <th style="text-align: center;">Dec</th>
        							        </tr>
        							        <tr>
        							        <th>Actual</th>
        							        <?php
                                            for ($i = 0; $i <= 11; $i++) {
                                                echo "<td>".number_format($actualrev[$i])."</td>";
                                            }
                                            ?>
        							        </tr>
        							        <tr>
        							        <th>Prediction</th>
        							        <?php
        							        $reversed_array=$arrdata;
                                            for ($i = 0; $i <= 11; $i++) {
                                                echo "<td>".number_format($reversed_array[$i])."</td>";
                                            }
                                            ?>
        							        </tr>
    							    	</table>
                                    </div>    
                                      <?php
                                  }
                                  ?>
                                  
                                </div>
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
    <!-- ECharts -->
    <script src="../vendors/echarts/dist/echarts.min.js"></script>
    <script src="../vendors/echarts/map/js/world.js"></script>
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
                borderWidth: 1
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
            textStyle: {
                fontFamily: 'Arial, Verdana, sans-serif'
            }
        };
        var dataStyle = {
            normal: {
              label: {
                show: true
              },
              labelLine: {
                show: true
              }
            }
        };
        var placeHolderStyle = {
            normal: {
              color: 'rgba(0,0,0,0)',
              label: {
                show: true
              },
              labelLine: {
                show: true
              }
            },
            emphasis: {
              color: 'rgba(0,0,0,0)'
            }
        };   

      var echartLine = echarts.init(document.getElementById('echart_line'), theme);
      echartLine.setOption({
        title: {
          text: '',
          subtext: ''
        },
        tooltip: {
          trigger: 'axis'
        },
        legend: { // Menambahkan legend di sini
            data: ['Act Rev', 'Pred Rev']
        },
        toolbox: {
          show: true,
          feature: {
            magicType: {
              show: false,
              title: {
                line: 'Line',
                bar: 'Bar',
                stack: 'Stack',
                tiled: 'Tiled'
              },
              type: ['line', 'bar', 'stack', 'tiled']
            },
            restore: {
              show: false,
              title: "Restore"
            },
            saveAsImage: {
              show: false,
              title: "Save Image"
            }
          }
        },
        calculable: true,
        xAxis: [{
          type: 'category',
          boundaryGap: false,
          data: [
                <?php
                    if($donepred==1){
                        foreach ($dates_array as $date) {
                            $month_name = date('F', strtotime($date));
                            echo "'".$month_name."',";
                        }
                    }
                    else{
                        echo "'Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'";
                    }
                ?>
              ]
        }],
        yAxis: [{
          type: 'value'
        }],
        grid: {
        left: '0%', // Atur jarak kiri grafik dari tepi kanvas
        right: '5%', // Atur jarak kanan grafik dari tepi kanvas
        top: '15%', // Atur jarak atas grafik dari tepi kanvas
        bottom: '0%', // Atur jarak bawah grafik dari tepi kanvas
        containLabel: true // Otomatis menyesuaikan lebar grafik agar label tidak terpotong
    },
        series: [{
          name: 'Act Rev',
          type: 'line',
          smooth: true,
          itemStyle: {
            normal: {
              areaStyle: {
                type: 'default'
              }
            }
          },
          data: [
                <?php
                    if($donepred==1){
                        foreach ($cleanprediction as $tampil) {
                        echo "'0',";
                        }
                    }
                    else{ 
                        for ($i = 0; $i <= 11; $i++) {
                            echo $actualrev[$i].",";
                        }
                    }
                ?>
            ]
        }, {
          name: 'Pred Rev',
          type: 'line',
          smooth: true,
          itemStyle: {
            normal: {
              areaStyle: {
                type: 'default'
              }
            }
          },
          data: [
                <?php
                    if($donepred==1){
                        foreach ($cleanprediction as $tampil) {
                        echo "'".$tampil."',";
                        }
                    }
                    else{ 
                        for ($i = 0; $i <= 11; $i++) {
                            echo $reversed_array[$i].",";
                        }
                    }
                ?>
                ]
        }]
      });
        // Initialize the second ECharts instance
      var echartLine2 = echarts.init(document.getElementById('echart_line2'), theme);
      echartLine2.setOption({
        title: {
          text: '',
          subtext: ''
        },
        tooltip: {
          trigger: 'axis'
        },
        legend: { // Menambahkan legend di sini
            data: ['Act Inv', 'Pred Inv']
        },
        toolbox: {
          show: true,
          feature: {
            magicType: {
              show: false,
              title: {
                line: 'Line',
                bar: 'Bar',
                stack: 'Stack',
                tiled: 'Tiled'
              },
              type: ['line', 'bar', 'stack', 'tiled']
            },
            restore: {
              show: false,
              title: "Restore"
            },
            saveAsImage: {
              show: false,
              title: "Save Image"
            }
          }
        },
        calculable: true,
        xAxis: [{
          type: 'category',
          boundaryGap: false,
          data: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec']
        }],
        yAxis: [{
          type: 'value'
        }],
        grid: {
        left: '0%', // Atur jarak kiri grafik dari tepi kanvas
        right: '5%', // Atur jarak kanan grafik dari tepi kanvas
        top: '15%', // Atur jarak atas grafik dari tepi kanvas
        bottom: '0%', // Atur jarak bawah grafik dari tepi kanvas
        containLabel: true // Otomatis menyesuaikan lebar grafik agar label tidak terpotong
    },
        series: [{
          name: 'Act Inv',
          type: 'line',
          smooth: true,
          itemStyle: {
            normal: {
              areaStyle: {
                type: 'default'
              }
            }
          },
          data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
        }, {
          name: 'Pred Inv',
          type: 'line',
          smooth: true,
          itemStyle: {
            normal: {
              areaStyle: {
                type: 'default'
              }
            }
          },
          data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
        }]
      });
    </script>
  </body>
</html>