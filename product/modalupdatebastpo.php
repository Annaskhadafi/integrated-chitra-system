<?php 
  $idwo= $_POST['idwo'];
  include "koneksi.php";      
  $perintah = mysqli_query($koneksi3, "SELECT * from work_order where id_wo=$idwo ");
  $data = mysqli_fetch_array($perintah);
  $repair_type=$data['repair_type'];
?>
<h2>BAST, PO & Invoice Update</h2>
<form  class="form" role="form" action="updatebastpo.php" method="post">
<?php 
    if($repair_type =="Claim warranty"||$repair_type =="Redo"){ ?>
        <table>
                <tr>
                    <td>WO</td>
                    <td>: <?php echo $data['wo']; ?></td>
                    <td><input class="form-inline" type="hidden" value="<?php echo $idwo; ?>" name="idwo"/></td>
                </tr>
                <tr>
                    <td>BAST</td>
                    <td><input class="form-inline" type="text" value="<?php echo $data['bast']; ?>" name="bast" placeholder="BAST number"/></td>
                    <td>BAST date</td>
                    <td><input class="form-inline" type="date" value="<?php echo $data['bast_date']; ?>" name="bastdate"/></td>
                </tr>
                <tr>
                    <td><input class="form-inline" type="hidden" value="<?php echo $data['po']; ?>" name="po" placeholder="PO number"/></td>
                    <td><input class="form-inline" type="hidden" value="<?php echo $data['po_date']; ?>" name="podate"/></td>
                </tr>
                <tr>
                    <td><input class="form-inline" type="hidden" value="<?php echo $data['invoice']; ?>" name="invoice" placeholder="invoice number"/></td>
                    <td><input class="form-inline" type="hidden" value="<?php echo $data['invoice_date']; ?>" name="invoicedate"/></td>
                </tr>
        </table>
        <?php 
    }
    elseif($repair_type =="Warranty"||$repair_type =="No warranty"||$repair_type =="Wire patch"){ ?>
        <table>
            <tr>
                <td>WO</td>
                <td>: <?php echo $data['wo']; ?></td>
                <td><input class="form-inline" type="hidden" value="<?php echo $idwo; ?>" name="idwo"/></td>
            </tr>
            <tr>
                <td>BAST</td>
                <td><input class="form-inline" type="text" value="<?php echo $data['bast']; ?>" name="bast" placeholder="BAST number"/></td>
                <td>BAST date</td>
                <td><input class="form-inline" type="date" value="<?php echo $data['bast_date']; ?>" name="bastdate"/></td>
            </tr>
            <tr>
                <td>PO</td>
                <td><input class="form-inline" type="text" value="<?php echo $data['po']; ?>" name="po" placeholder="PO number"/></td>
                <td>PO date</td>
                <td><input class="form-inline" type="date" value="<?php echo $data['po_date']; ?>" name="podate"/></td>
            </tr>
            <tr>
                <td>Invoice</td>
                <td><input class="form-inline" type="text" value="<?php echo $data['invoice']; ?>" name="invoice" placeholder="invoice number"/></td>
                <td>Invoice Date</td>
                <td><input class="form-inline" type="date" value="<?php echo $data['invoice_date']; ?>" name="invoicedate"/></td>
            </tr>
        </table>
        <?php
    } 
?>
  <br>
  <button type="submit" value="submit" class="btn btn-info btn-xs"> Update !</button>
</form>