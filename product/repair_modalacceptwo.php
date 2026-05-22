<?php 
  $iduser= $_POST['iduser'];
  $idwo= $_POST['idwo'];
  $jobtype= $_POST['jobtype'];
  $tiretype= $_POST['tiretype'];
  include "koneksi.php";
?>
<h2><u>Create WO</u></h2>
<form  class="form" role="form" action="updatewo.php" method="post">
  <input class="form-inline" type="hidden" value="<?php echo $idwo; ?>" name="idwo"/>
  <input class="form-inline" type="hidden" value="2" name="status"/>
  <input class="form-inline" type="hidden" value="<?php echo $iduser;?>" name="createby"/>
  Created by : <?php echo $iduser;?>  
  <br>
  Serial Number : <?php echo $idwo;?>
  <br>
  <input class="form-inline" type="date" name="date"/>
  <br><input class="form-inline" type="number" name="wo" placeholder="WO number"/><br>
  <select class="form-inline" name="storeloc">
    <option value="">Store location</option>
    <?php 
      $perintah2=mysqli_query($koneksi3, "SELECT * from store_loc where id_store_loc!=0");
      while ($data2 = mysqli_fetch_array($perintah2)) {?>
        <option value="<?php echo  $data2['id_store_loc'];?>"><?php echo $data2['store_location'];?></option>
        <?php 
      } 
    ?> 
  </select>
  <br>
  <select class="form-inline" name="injury">
    <option value="">Injury</option>
    <option value="Spot">Spot</option>
    <option value="SR">SR</option>
    <option value="2SR">2SR</option>
    <option value="3SR">3SR</option>
    <option value="4SR">4SR</option>
  </select>
  <br><select class="form-inline" name="repairtype">
    <option value="">Type</option>
    <option value="Warranty">Warranty</option>
    <option value="No warranty">No warranty</option>
    <option value="Wire patch">Wire patch</option>
    <option value="Claim warranty">Claim warranty</option>
    <option value="Redo">Redo</option>
  </select>
  </br></br> 
  <input class="form-inline" type="hidden" value="<?php echo $jobtype;?>" name="jobtype"/>                      
  Job Type : <?php echo $jobtype; ?> 
  </br></br> 
  <input class="form-inline" type="hidden" value="<?php echo $tiretype;?>" name="type"/>                      
  Tire Type : <?php echo $tiretype; ?> 
  </br></br>
  <button type="submit" value="submit" class="btn btn-info btn-xs"> Start progress !</button>
</form>