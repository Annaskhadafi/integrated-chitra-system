<?php 
$idwo= $_POST['idwo'];
include "koneksi.php";
$perintah = mysqli_query($koneksi3, "SELECT * from work_order where id_wo=$idwo ");
$data = mysqli_fetch_array($perintah);//hasil query di baris 4 dimasukkan ke dalam array//
$perintah3 = mysqli_query($koneksi3, "SELECT date FROM job where wo='$idwo' and job='Quality control' ORDER BY id_job DESC LIMIT 1");
$data3 = mysqli_fetch_array($perintah3);
$wo=$data['wo'];
$date=isset($data3['date']);
$podate=$data['po_date'];
if($idwo!=""&& $wo==""&& $date==""&& $podate==""){
    $status=1;
}
elseif($idwo!=""&& $wo!=""&& $date==""&& $podate==""){
    $status=2;
}
elseif($idwo!=""&& $wo!=""&& $date!=""&& $podate==""){
    $status=4;
}
elseif($idwo!=""&& $wo!=""&& $date!=""&& $podate!=""){
    $status=5;
}
else{
    $status=6;
}
$perintah = mysqli_query($koneksi3, "UPDATE work_order SET status='$status' WHERE id_wo=$idwo");
?>
<h2>Show (No : <?php echo $idwo;?>)</h2> 
Show Update status tire from repair progress ?
<p>
    Status : 
    <?php 
        if ($status==1){$stat="Inspect";}
        elseif ($status==2){$stat="Progress";}
        elseif ($status==3){$stat="Rejected";}
        elseif ($status==4){$stat="BAST/PO/Invoice";}
        elseif ($status==6){$stat="Hidden";}
        else{$stat="Completed";}
        echo $stat;
    ?>
</p>
<form  class="form" role="form" action="updatestatusshow.php" method="post">
  <input class="form-inline" type="hidden" value="<?php echo $idwo; ?>" name="idwo"/>
  <input class="form-inline" type="hidden" value="<?php echo $status; ?>" name="status"/>
  </br></br> 
  <button type="submit" value="submit" class="btn btn-danger btn-xs"> Ok !</button>
</form>