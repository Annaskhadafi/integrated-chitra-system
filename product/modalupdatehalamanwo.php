<?php 
  $idwo= $_POST['idwo'];
  include "koneksi.php";      
  $perintah = mysqli_query($koneksi3, "SELECT * from work_order where id_wo=$idwo ");
  $data = mysqli_fetch_array($perintah);
?>
<h2>Edit Data</h2>
<form  class="form" role="form" action="updatehalamanwo.php" method="post">
  <input class="form-inline" type="hidden" value="<?php echo $data['id_wo']; ?>" name="idwo"/>
  <table>
            <tr>
                <td>WO</td>
                <td><input class="form-inline" type="text" value="<?php echo $data['wo']; ?>" name="wo" placeholder="WO number"/></td>
                <td>WO Date</td>
                <td><input class="form-inline" type="date" value="<?php echo $data['input_date']; ?>" name="wodate"/></td>
            </tr>
            <tr>
                <td>Repair Type</td>
                <td><select class="form-inline" name="repair_type">
                    <option value="<?php echo $data['repair_type']; ?>"><?php echo $data['repair_type']; ?></option>
                    <option value="Warranty">Warranty</option>
                    <option value="No warranty">No warranty</option>
                    </select>
                </td>
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
                <td>Invoice date</td>
                <td><input class="form-inline" type="date" value="<?php echo $data['invoice_date']; ?>" name="invoicedate"/></td>
            </tr>
        </table>
    <br>
    <button type="submit" value="submit" class="btn btn-info btn-xs"> Edit!</button>
</form>