<?php 
//tangkap nilai idpattern, inisialisasi $idpattern
$idpattern= $_POST['idpattern'];
?>
<form  class="form-inline" role="form" action="updatepattern.php" method="post">
  <?php include "koneksi.php";
  $perintahmodal=mysqli_query($sambung, "SELECT * from tire_pattern a,tire_manufac b where id_pattern = $idpattern and a.manufac=b.id_manufac");
  $datamodal = mysqli_fetch_array($perintahmodal) ?>
    <input class="form-control" value= "<?php echo  $datamodal['id_pattern']; ?>" type="hidden" name="id_pattern"/>
    <input class="form-control" type="text" value="<?php echo  $datamodal['pattern']; ?>" name="pattern" />
    <select class="form-control" name="manufac">  
      <option value="<?php echo  $datamodal['id_manufac']; ?>"><?php echo  $datamodal['manufac']; ?></option>
        <?php 
        	$perintah=mysqli_query($sambung, "SELECT * from tire_manufac");
       	 	$no =1;
        	while ($data = mysqli_fetch_array($perintah)) {?>
        	<option value=<?php echo  $data['id_manufac']; ?>><?php echo  $data['manufac']; ?></option>
	        <?php
	        $no++;
	        }
	        ?> 
    </select>
    <button type="submit" value="submit" class="btn btn-default"><span class="glyphicon glyphicon-pencil"></span> Edit</button>
</form>