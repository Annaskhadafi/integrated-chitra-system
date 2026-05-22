<?php 
//tangkap nilai idstatus, inisialisasi $idstatus
$idstatus= $_POST['idstatus'];
?>
<!-- form edit data tire_size -->
        <form  class="form-inline" role="form" action="updatestatus.php" method="post">
        <?php include "koneksi.php";
        $perintahmodal=mysqli_query($sambung, "select * from tire_status where id_status = $idstatus");
        $datamodal = mysqli_fetch_array($perintahmodal) ?>
        <input class="form-control" value= "<?php echo  $datamodal['id_status']; ?>" type="hidden" name="id_status"/>
          <input class="form-control" type="text" value="<?php echo  $datamodal['status']; ?>" name="status" />
          <button type="submit" value="submit" class="btn btn-default"><span class="glyphicon glyphicon-pencil"></span> Edit</button>
          </form>