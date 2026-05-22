<?php 
//tangkap nilai idmanufac, inisialisasi $idmanufac
$idmanufac= $_POST['idmanufac'];
?>
<!-- form edit manufac -->
<form  class="form-inline" role="form" action="updatemanufac.php" method="post">
	<?php include "koneksi.php";
    	$perintahmodal=mysqli_query($sambung, "SELECT * from tire_manufac where id_manufac = $idmanufac");
        $datamodal = mysqli_fetch_array($perintahmodal) ?>
        <input class="form-control" value= "<?php echo  $datamodal['id_manufac']; ?>" type="hidden" name="id_manufac"/>
        <input class="form-control" type="text" value="<?php echo  $datamodal['manufac']; ?>" name="manufac" />
          <button type="submit" value="submit" class="btn btn-default"><span class="glyphicon glyphicon-pencil"></span> Edit</button>
</form>