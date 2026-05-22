<?php 
$idstoreloc= $_POST['idstoreloc'];
$idusage= $_POST['idusage'];
  include "koneksi.php";      
  $perintah = mysqli_query($sambung, "SELECT * from mat_usage a,mat_inventory b where a.id_usage=$idusage and a.inv=b.id_inv ");
  $data = mysqli_fetch_array($perintah);
//   echo $idstoreloc;
//   echo "<br>".$idusage;
//   echo "<br>".$data['desc'];
  
?>
<!-- <h2>Edit</h2> -->
<form  class="form" role="form" action="updatetransferstock.php" method="post">
<table>
        <tr>
              <td>Date</td>
              <td><input class="form-inline" type="date" value="<?php echo $data['date']; ?>" name="date"/></td>
        </tr>
        <tr>
              <td>Transfer qty</td>
              <td><input class="form-inline" type="number" value="<?php echo $data['qty']; ?>" name="qty"/></td>
        </tr>
        <tr>
              <td><input class="form-inline" type="hidden" value="<?php echo $data['qty']; ?>" name="qtysebelum"/></td>
        </tr>
        <tr>
            <td>
                  <input class="form-inline" type="hidden" value="<?php echo $data['id_usage']; ?>" name="idusage"/>
                  <input class="form-inline" type="hidden" value="<?php echo $idstoreloc; ?>" name="idstoreloc"/>
            </td>
        </tr>
        <tr>
              <td>
                  <input class="form-inline" type="hidden" value="<?php echo $data['inv']; ?>" name="idinv"/>
                  <input class="form-inline" type="hidden" value="<?php echo $data['desc']; ?>" name="desc"/>
            </td>
        </tr>
</table>
  <br>
  <button type="submit" value="submit" class="btn btn-info btn-xs"> Edit </button>
</form>