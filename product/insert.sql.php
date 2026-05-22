<?php session_start();?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">   
 <html xmlns="http://www.w3.org/1999/xhtml">   
 <head>   
 <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1" />   
 <title>Insert Data</title>   
 </head>   
   
 <body>   
 <h1>Insert Data</h1>  
 <?php 
 $host = "localhost";  
 $user = "root";  
 $pass = "";  
 $dbnm = "test";  

 $conn = mysqli_connect ($host, $user, $pass, $dbnm);  
 if ($conn) {  
  $buka = mysqli_select_db ($dbnm);  
  if (!$buka) {  
  die ("Database tidak dapat dibuka");  
  }  
 } else {  
  die ("Server MySQL tidak terhubung");  
 }  
   
 $error=0;  
 if(isset($_POST['enter']))  
 {  
 $count=$_POST['count']+1;  
 for($j=2;$j<$count;$j++)  
 {   
 $input_idmov = "idmov".$j;  
 $idmov = addslashes (strip_tags (substr($_POST[$input_idmov],1,-1)));  
 $input_sn = "sn".$j;  
 $sn = addslashes (strip_tags (substr($_POST[$input_sn],1,-1)));  
 $input_size = "size".$j;  
 $size = addslashes (strip_tags (substr($_POST[$input_size],1,-1)));  
 $input_pattern = "pattern".$j;  
 $pattern = addslashes (strip_tags (substr($_POST[$input_pattern],1,-1)));  
 $input_brand = "brand".$j;  
 $brand = addslashes (strip_tags (substr($_POST[$input_brand],1,-1)));
 $input_unit = "unit".$j;  
 $unit = addslashes (strip_tags (substr($_POST[$input_unit],1,-1)));  
 $input_job = "job".$j;  
 $job = addslashes (strip_tags (substr($_POST[$input_job],1,-1)));  
 $input_hm = "hm".$j;  
 $hm = addslashes (strip_tags (substr($_POST[$input_hm],1,-1)));  
 $input_posisi = "posisi".$j;  
 $posisi = addslashes (strip_tags (substr($_POST[$input_posisi],1,-1))); 
 $input_date = "date".$j;  
 $date = addslashes (strip_tags (substr($_POST[$input_date],1,-1))); 
 $input_alasan = "alasan".$j;  
 $alasan = addslashes (strip_tags (substr($_POST[$input_alasan],1,-1))); 
 $input_status = "status".$j;  
 $status = addslashes (strip_tags (substr($_POST[$input_status],1,-1))); 
 $input_lifetime = "lifetime".$j;  
 $lifetime = addslashes (strip_tags (substr($_POST[$input_lifetime],1,-1)));  
 $input_site = "site".$j;  
 $site = addslashes (strip_tags (substr($_POST[$input_site],1,-1))); 
 $input_pair = "pair".$j;  
 $pair = addslashes (strip_tags (substr($_POST[$input_pair],1,-1)));    

 $q="INSERT INTO admin (idmov,sn,size,pattern,brand,unit_number,job,hm_on_job,pos,date,alasan,status,life_on_job,site,pair) VALUES ('$idmov','$sn','$size','$pattern','$brand','$unit','$job','$hm','$posisi','$date','$alasan','$status','$lifetime','$site','$pair')";  
 $r=mysqli_query($conn, $q);  
 if(!$r){$error++;}  
 }  
 if($error) { echo "STATUS : IMPORT DATA ERROR";}  
 else echo "STATUS : IMPORT DATA BERHASIL";  
 }  
 ?>  
 </body>  
 </html>  