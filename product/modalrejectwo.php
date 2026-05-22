<?php $idwo= $_POST['idwo'];?>
<h2>Reject (No : <?php echo $idwo;?>)</h2> 
Reject tire from repair progress ?
<form  class="form" role="form" action="updatewo.php" method="post">
  <input class="form-inline" type="hidden" value="<?php echo $idwo; ?>" name="idwo"/>
  <input class="form-inline" type="hidden" value="3" name="status"/>
  </br></br> 
  <button type="submit" value="submit" class="btn btn-danger btn-xs"> Ok !</button>
</form>