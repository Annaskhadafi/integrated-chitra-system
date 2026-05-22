<?php session_start();?>
<!DOCTYPE html>
<?php include 'header.php';?>
<html lang="en">
  <body class="nav-md">
    <div class="container body">
      <div class="main_container">        
      <?php include('template_menu.php');?>
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
                        <?php echo date("l");echo date(", Y-m-d");?>
                      </a>
                    </h3>
                  </li>
                </ul>
            </div>
        </div>
        <?php if($name!=""){ ?>
          <div class="right_col" role="main">
            <div class="clearfix"></div>
            <div class="row" style="margin-top:0px">            
              <?php include "koneksi.php";?>
              <div class="x_panel">
                <div class="x_title">
                    <h2>Market Share</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                      Last update : <b>
                       <?php 
                        $perintah = mysqli_query($koneksi2,"SELECT MAX(`date`) as datea FROM fleet_list");
                        $data = mysqli_fetch_array($perintah);
                        $update = $data['datea'];
                        echo $update;
                      ?></b>
                      <!-- <table id="datatable-buttons" class="table table-striped table-bordered">
                          <thead style="background:#f5f5f5;">
                            <tr>
                              <th>No</th>
                              <th>Rotasi</th>
                              <th>Scrap</th>
                              <th>Tire_Quantity</th>
                              <th>Customer</th>
                              <th>Size</th>
                              <th>Unit_Qty</th>
                            </tr>
                          </thead>                       -->
                      <div id="layoutObj" style="position: relative; width: 100%; height: 400px;"></div>
                      <input type="button" value="Get as Excel" onclick="myGrid.toExcel('codebase/grid-excel-php/generate.php');">
                      <div id="myGrid" style="width:399px;height:270px;"></div>
                    </div>  
                  </div>
                </div>                
<!--                 <div class="x_content">
                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="row">                    
                      <table id="example" class="display" style="width:100%">
                        <thead style="background:#f5f5f5;">
                            <tr>
                              <th>Customer</th>
                              <th>Size</th>
                              <th>Tire forecast</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $perintah = mysqli_query($koneksi2,"SELECT * from customer");
                            $no=1;
                            while ($data = mysqli_fetch_array($perintah)) { ?>
                            <tr>
                              <td><?php echo $data['customer'];?></td>
                              <td><?php echo $data['size'];?></td>
                              <td><?php echo $data['forecast_tire'];?></td>
                            </tr><?php $no++; } ?>
                        </tbody>
                        <tfoot>
                          <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                          </tr>
                        </tfoot>
                      </table>
                    </div>  
                  </div>
                </div> -->
              </div>
            </div>
          </div>          
        <?php }
          else {}
        ?>
      </div>
    </div>
    <footer>
      <div class="pull-right">
        Chitra Tire System by Chitra Paratama @2017
      </div>
      <div class="clearfix"></div>
    </footer>

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
    <!-- ECharts -->
    <script src="../vendors/echarts/dist/echarts.min.js"></script>
    <script src="../vendors/echarts/map/js/world.js"></script>
    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>
    <script>window.jQuery || document.write('<script src=""../vendors/js/jquery.min.js"><\/script>')</script>
    <script src="../vendors/js/bootstrap.min.js"></script>
    <script src="../vendors/js/docs.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
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