<?php 
  $idwo= $_POST['idwo'];
  include "koneksi.php";      
  $perintah = mysqli_query($sambung, "SELECT * from work_order where id_wo=$idwo ");
  $data = mysqli_fetch_array($perintah);
?>
<h2>BAST, PO & Invoice Update</h2>
<form  class="form" role="form" action="updatebastpo.php" method="post">
  WO : <?php echo $data['wo']; ?><input class="form-inline" type="hidden" value="<?php echo $idwo; ?>" name="idwo"/>
  <br><br>
  BAST : <input class="form-inline" type="text" value="<?php echo $data['bast']; ?>" name="bast" placeholder="BAST number"/>
  BAST date : <input class="form-inline" type="date" value="<?php echo $data['bast_date']; ?>" name="bastdate"/>
  <br><br>
  PO : <input class="form-inline" type="text" value="<?php echo $data['po']; ?>" name="po" placeholder="PO number"/>
  PO date : <input class="form-inline" type="date" value="<?php echo $data['po_date']; ?>" name="podate"/>
  <br><br>
  Invoice : <input class="form-inline" type="text" value="<?php echo $data['invoice']; ?>" name="invoice" placeholder="invoice number"/>
  Invoice date : <input class="form-inline" type="date" value="<?php echo $data['invoice_date']; ?>" name="invoicedate"/>
  <button type="submit" value="submit" class="btn btn-info btn-xs"> Update !</button>
</form>