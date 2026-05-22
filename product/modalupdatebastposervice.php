<?php 
//   $idwo= $_POST['idwo'];
  $no= $_POST['no'];
  include "koneksi.php";      
  $perintah = mysqli_query($koneksi4, "SELECT * from work_order where no=$no ");
  $data = mysqli_fetch_array($perintah);
?>
<h2>BAST, PO & Invoice Update</h2>
<form  class="form" role="form" action="updatebastposervice.php" method="post">
    
    <table>
            <tr>
                <td>WO</td>
                <td>: <?php echo $data['wo']; ?></td>
                <td><input class="form-inline" type="hidden" value="<?php echo $idwo; ?>" name="idwo"/></td>
            </tr>
            <tr>
                <td>BAST</td>
                <td><input class="form-inline" type="text" value="<?php echo $data['bast']; ?>" name="bast" placeholder="BAST"/></td>
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
    <br>
    <button type="submit" value="submit" class="btn btn-info btn-xs"> Update !</button>
</form>