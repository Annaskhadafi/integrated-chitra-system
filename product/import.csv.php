
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">   
 <html xmlns="http://www.w3.org/1999/xhtml">   
 <head>   
 <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1" />   
 <title>Import File CSV</title>   
 </head>   
   
 <body>   
 <h1>Import File CSV</h1>  
 <form action="preview.import.php" method="post" enctype="multipart/form-data" name="form1" id="form1">   
  Pilih File CSV yang akan di import: <br />  
  <input name="csv" type="file" id="csv"/>   
  <input type="submit" name="Import" value="Enter" />   
 </form>   
 <br/>  
 <b>Note :</b><br/>  
  <font color=red>Pastikan urutan kolom pada file CSV= nama,alamat,tanggal lahir,pekerjaan</font><br/>  
  <font color=red>Pastikan tidak ada nama kolom pada baris atas</font><br/>    
 </body>   
 </html>  