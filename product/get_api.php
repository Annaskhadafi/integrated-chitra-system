<?php 
require_once "koneksi.php";
if(function_exists($_GET['function'])) {
         $_GET['function']();
    }
    
function get_tire_warranty()
   {
      global $koneksi5;    
      $query = $koneksi5->query("SELECT b.customer,a.site,a.tire_size,a.brand,a.tire_desc,a.sn_tire,a.lifetime,a.otd,a.rtd,a.date_in,a.date_accept,a.acc_by,c.aging,a.act_plan,a.note
                                    FROM chitraparatama_warranty.tab_warranty a,chitraparatama_fleetlist.customer_master b,
                                    	(select no,IF(act_plan='Done',DATEDIFF(date_closed,date_in),
                                                     IF(act_plan='Reject',DATEDIFF(date_accept,date_in),DATEDIFF(CURDATE(),date_in))
                                                    ) as aging FROM chitraparatama_warranty.tab_warranty) as c                                     
                                    WHERE a.costumer=b.id_customer_master and a.act_plan!='done' and a.act_plan!='reject' and a.no=c.no and c.aging>=30");            
      while($row=mysqli_fetch_object($query))
      {
         $data[] =$row;
      }
      $response=array(
                     'data' => $data
                  );
      header('Content-Type: application/json');
      echo json_encode($response);
   }  

function mining_contractor() 
   {
      global $koneksi2;    
      $query = $koneksi2->query("SELECT * FROM `customer_master`");            
      while($row=mysqli_fetch_object($query))
      {
         $data[] =$row;
      }
      $response=array(
                     'data' => $data
                  );
      header('Content-Type: application/json');
      echo json_encode($response);
   }
   
function mining_company() 
   {
      global $koneksi2;    
      $query = $koneksi2->query("SELECT * FROM mining_company");            
      while($row=mysqli_fetch_object($query))
      {
         $data[] =$row;
      }
      $response=array(
                     'data' => $data
                  );
      header('Content-Type: application/json');
      echo json_encode($response);
   }
   
function fleetlist() 
   {
      global $koneksi2;    
      $query = $koneksi2->query("
                                SELECT id_fleet_list,d.customer,c.site,c.status,c.location,c.kabupaten,c.kecamatan,unit_manufacture,model,tire_size,tire_quantity,unit_qty,(a.unit_qty*b.tire_quantity) as totaltire, round(((a.rotasi/a.scrap)*b.tire_quantity),0) as annual,(round(((a.rotasi/a.scrap)*b.tire_quantity),0)*a.unit_qty) as forecast,a.date as lastupdate
                                FROM fleet_list a,unit_master b,site_master c,customer_master d 
                                WHERE a.id_site=c.id_site_master and c.id_customer=d.id_customer_master and a.id_unit=b.id_unit_master
                                ");            
      while($row=mysqli_fetch_object($query))
      {
         $data[] =$row;
      }
      $response=array(
                     'data' => $data
                  );
      header('Content-Type: application/json');
      echo json_encode($response);
   }
function coalprice() 
   {
      global $koneksi;    
      $query = $koneksi->query("SELECT
                                  `date`,
                                  `ici index 6500`,
                                  `ici index 5800`,
                                  `ici index 5000`,
                                  `ici index 4200`,
                                  `ici index 3400`,
                                  `hba`,
                                  `hba1`,
                                  `hba2`,
                                  `hba3`
                                FROM `coalPrice`;
                                ");            
      while($row=mysqli_fetch_object($query))
      {
         $data[] =$row;
      }
      $response=array(
                     'data' => $data
                  );
      header('Content-Type: application/json');
      echo json_encode($response);
   }
function wo_repair() 
    {
      global $koneksi3;    
      $query = $koneksi3->query("SELECT * FROM work_order WHERE wo IS NOT NULL");            
      while($row=mysqli_fetch_object($query))
      {
         $data[] =$row;
      }
      $response=array(
                     'data' => $data
                  );
      header('Content-Type: application/json');
      echo json_encode($response);
    mysqli_close($koneksi3); 
    }


function repair_work_order_detail_material() 
{
    global $koneksi3;

    $query = $koneksi3->query("
        SELECT 
            j.id_job,
            wo.wo AS wo,
            j.job,
            j.material AS material_id,
            ms.material_name,
            ms.category,
            ms.smu,
            j.qty,
            j.time,
            j.date,
            j.person
        FROM job j
        LEFT JOIN work_order wo 
            ON j.wo = wo.id_wo
        LEFT JOIN material_stock ms 
            ON j.material = ms.id_matstock
        ORDER BY j.id_job DESC
    ");

    while($row = mysqli_fetch_object($query))
    {
        $data[] = $row;
    }

    $response = array(
        'data' => $data
    );

    header('Content-Type: application/json');
    echo json_encode($response);

    mysqli_close($koneksi3); 
}


function repair_material() 
    {
      global $koneksi3;    
      $query = $koneksi3->query("SELECT * FROM material_stock");            
      while($row=mysqli_fetch_object($query))
      {
         $data[] =$row;
      }
      $response=array(
                     'data' => $data
                  );
      header('Content-Type: application/json');
      echo json_encode($response);
    mysqli_close($koneksi3); 
    }
function manpower() 
    {
      global $koneksi3;    
      $query = $koneksi3->query("SELECT * FROM manpower WHERE job_title LIKE '%repairman%'");            
      while($row=mysqli_fetch_object($query))
      {
         $data[] =$row;
      }
      $response=array(
                     'data' => $data
                  );
      header('Content-Type: application/json');
      echo json_encode($response);
    mysqli_close($koneksi3); 
    }
function mining_contractor_size_population() 
   {
      global $koneksi2;    
      $query = $koneksi2->query("SELECT * from customer");            
      while($row=mysqli_fetch_object($query))
      {
         $data[] =$row;
      }
      $response=array(
                     'data' => $data
                  );
      header('Content-Type: application/json');
      echo json_encode($response);
   }
function goodreceive() 
    {
      global $koneksi;    
      $query = $koneksi->query("SELECT ponumb,sum(poqty),sum(grqty) FROM `data_goodreceive` group by ponumb;");            
      while($row=mysqli_fetch_object($query))
      {
         $data[] =$row;
      }
      $response=array(
                     'data' => $data
                  );
      header('Content-Type: application/json');
      echo json_encode($response);
    mysqli_close($koneksi3); 
    }
   ?>