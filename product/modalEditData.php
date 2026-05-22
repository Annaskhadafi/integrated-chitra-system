<?php $item= $_POST['item'];
include "koneksi.php";
if($item=='fleet'){?>
  <?php 
  $idfleet= $_POST['idfleet'];
  $date=date('Y-m-d');
  $user = $_POST['name'];
  ?>
  <form  class="form-inline" role="form" action="updateData.php" method="post">
    <?php 
      $perintahmodal=mysqli_query($koneksi2,"SELECT * from fleet_list a,unit_master b,site_master c,customer_master d where a.id_site=c.id_site_master and a.id_unit=b.id_unit_master and a.id_fleet_list=$idfleet and c.id_customer=d.id_customer_master");
      $datamodal = mysqli_fetch_array($perintahmodal)
    ?>
    <input class="form-control" value= "<?php echo $datamodal['id_fleet_list']; ?>" type="hidden" name="idfleet">
    <input class="form-control" value= "<?php echo $date; ?>" type="hidden" name="date"> 
    <input class="form-control" value= "fleet" type="hidden" name="item">
    <input class="form-control" value="<?php echo $user;?>" type="hidden" name="name"/>      
    <table>  
      <tr>
        <td>Customer/Site : </td>
        <td>
          <select class="form-control" name="customer">
            <option value="<?php echo $datamodal['id_site_master'];?>"><?php echo $datamodal['customer'];?>/<?php echo $datamodal['site'];?></option>     
            <?php 
              $perintah=mysqli_query($koneksi2,"SELECT * from site_master a,customer_master b where a.id_customer=b.id_customer_master order by customer");
              while ($data = mysqli_fetch_array($perintah)) {?>    
                <option value='<?php echo $data['id_site_master']; ?>'><?php echo $data['customer'];?>/<?php echo $data['site'];?></option>     
            <?php }?>         
          </select>  
        </td>
      </tr>
      <tr>
        <td>Manufacture/Model :</td> 
        <td>
          <select class="form-control" name="unit">
            <option value="<?php echo $datamodal['id_unit'];?>"><?php echo $datamodal['unit_manufacture'];?>/<?php echo $datamodal['model'];?>/<?php echo $datamodal['tire_size'];?></option>     
            <?php 
              $perintah=mysqli_query($koneksi2,"SELECT id_unit_master,unit_manufacture,model,tire_size from unit_master order by unit_manufacture");
              while ($data = mysqli_fetch_array($perintah)) {?>    
                <option value='<?php echo  $data['id_unit_master']; ?>'><?php echo $data['unit_manufacture'];?>/<?php echo $data['model'];?>/<?php echo $data['tire_size'];?></option>     
            <?php }?>         
          </select>
        </td>
      </tr>
      <tr>
        <td>Unit qty : </td>
        <td><input class="form-control" type = "number" name = "qty" value="<?php echo $datamodal['unit_qty'];?>"></td>
      </tr>
      <tr>
        <td>Est serv/year </td>
        <td><input class="form-control" type = "number" name = "rotasi" value="<?php echo $datamodal['rotasi'];?>"></td>
      </tr>
      <tr>
        <td>Est perf life :</td>
        <td><input class="form-control" type = "number" name = "scrap" value="<?php echo $datamodal['scrap'];?>"></td>
      </tr>
      <tr>
        <td>Segment :</td>
        <td>
          <select class="form-control" name="segment">
            <option value='<?php echo $datamodal['segment'];?>'><?php echo $datamodal['segment'];?></option>  
            <option value='mining'>Mining</option> 
            <option value='infra'>Infra</option>           
          </select>
        </td>
      </tr>              
    </table>
    <br><br>
    <button type="submit" value="submit" class="btn btn-default"><span class="glyphicon glyphicon-pencil"></span> Edit</button>
  </form>
<?php }
elseif ($item==''){?>
<?php } 
elseif ($item==''){?>
<?php } 
?>
