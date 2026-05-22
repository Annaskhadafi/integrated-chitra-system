<?php $idwo= $_POST['idwo'];?>
<h2>Hidden (No : <?php echo $idwo;?>)</h2> 
Hidden status tire ?
<form  class="form" role="form" action="updatestatus.php" method="post">
  <input class="form-inline" type="hidden" value="<?php echo $idwo; ?>" name="idwo"/>
  <input class="form-inline" type="hidden" value="6" name="status"/>
  </br></br> 
  <button type="submit" value="submit" class="btn btn-danger btn-xs"> Ok !</button>
</form>