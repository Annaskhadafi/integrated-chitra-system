<?php 
//   $no_quot= $_POST['no_quot'];
$no= $_POST['no'];
include "koneksi.php";      
$perintah = mysqli_query($koneksi4, "SELECT * from work_orderr where no=$no");
$data = mysqli_fetch_array($perintah);

?>
<h2>Edit Data</h2>
<form  class="form" role="form" action="updatehalamanwoservice.php" method="post">
  <input class="form-inline" type="hidden" value="<?php echo $data['no']; ?>" name="no"/>
  <table>
            <tr>
                <td>WO</td>
                <td><input class="form-inline" type="text" value="<?php echo $data['wo']; ?>" name="wo" placeholder="WO number"/></td>
                <td>WO Date</td>
                <td><input class="form-inline" type="date" value="<?php echo $data['wo_date']; ?>" name="wo_date"/></td>
            </tr>
            <tr>
                <td>BAST</td>
                <td><input class="form-inline" type="text" value="<?php echo $data['bast']; ?>" name="bast"/></td>
                <td>BAST date</td>
                <td><input class="form-inline" type="date" value="<?php echo $data['bast_date']; ?>" name="bast_date"/></td>
            </tr>
            <tr>
                <td>PO</td>
                <td><input class="form-inline" type="text" value="<?php echo $data['po']; ?>" name="po" placeholder="PO number"/></td>
                <td>PO date</td>
                <td><input class="form-inline" type="date" value="<?php echo $data['po_date']; ?>" name="po_date"/></td>
            </tr>
            <tr>
                <td>Invoice</td>
                <td><input class="form-inline" type="text" value="<?php echo $data['invoice']; ?>" name="invoice" placeholder="invoice number"/></td>
                <td>Invoice date</td>
                <td><input class="form-inline" type="date" value="<?php echo $data['invoice_date']; ?>" name="invoice_date"/></td>
            </tr>
        </table>
        <br>
        <button type="submit" value="submit" class="btn btn-info btn-xs"> Edit </button>
</form>