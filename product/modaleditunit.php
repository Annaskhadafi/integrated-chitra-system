<?php 
//tangkap nilai idunit, inisialisasi $idunit
$idunit= $_POST['idunit'];
?>
<!-- form edit data unit -->
<form  class="form-inline" role="form" action="updateunit.php" method="post">
  <?php include "koneksi.php";
  $perintahmodal=mysqli_query($sambung, "SELECT * from unit where id_unit = $idunit");
  $datamodal = mysqli_fetch_array($perintahmodal) ?>
  <input class="form-control" value= "<?php echo  $datamodal['id_unit']; ?>" type="hidden" name="id_unit"/>
  <table>  
    <tr>
      <td><label>machine model :</label></td>
      <td><input class="form-control" type="text" value="<?php echo  $datamodal['unit']; ?>" name="unit" /></td>
    </tr>
    <tr>
      <td><label>tire qty :</label></td>
      <td><input class="form-control" type="number" value="<?php echo  $datamodal['tire']; ?>" name="tire" /></td>
    </tr>                                        
    <tr>
      <td><label>tire size :</label></td>
      <td>
        <select class="form-control" name="size">
      <option value="<?php echo  $datamodal['size']; ?>"><?php echo  $datamodal['size']; ?></option>     
        <?php $perintah=mysqli_query($sambung, "SELECT * FROM tire_size GROUP BY size");$no =1;    
        while ($data = mysqli_fetch_array($perintah)) {?><option value=<?php echo  $data['size']; ?>><?php echo  $data['size']; ?></option>     
        <?php $no++; }?>         
        </select>
      </td>
    </tr>                       
    <tr>
      <td><label>axl2tire qty :</label></td>
      <td><input class="form-control" type="number" value="<?php echo  $datamodal['axl2tire']; ?>" name="axl2" /></td>
    </tr>
    <tr>
      <td><label>axl4tire qty :</label></td>
      <td><input class="form-control" type="number" value="<?php echo  $datamodal['axl4tire']; ?>" name="axl4" /></td>
    </tr>
    <tr>
      <td><label>axl8tire qty :</label></td>
      <td><input class="form-control" type="number" value="<?php echo  $datamodal['axl8tire']; ?>" name="axl8" /></td>
    </tr>
  </table>
  <button type="submit" value="submit" class="btn btn-default"><span class="glyphicon glyphicon-pencil"></span> Edit</button>
</form>
          