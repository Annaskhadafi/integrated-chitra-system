<?php $item= $_POST['item'];
include "koneksi.php";
if($item=='tire'){?>
  <?php $idtire= $_POST['idtire'];?>
  <form  class="form-inline" role="form" action="updateMasterData.php" method="post">
    <?php 
      $perintahmodal=mysqli_query($koneksi2,"SELECT * from tire_master where id_tire_master='$idtire'");
      $datamodal = mysqli_fetch_array($perintahmodal)
    ?>
    <input class="form-control" value= "<?php echo $datamodal['id_tire_master']; ?>" type="hidden" name="idtire"> 
    <input class="form-control" value= "tire" type="hidden" name="item"> 
    <table>
      <tr>
        <td>Manufacture : </td>
        <td><input class="form-control" type="text" value="<?php echo $datamodal['manufacture']; ?>" name="manufacture"></td>
      </tr>
      <tr>
        <td>Pattern :</td> 
        <td><input class="form-control" type="text" value="<?php echo $datamodal['pattern']; ?>" name="pattern"></td>
      </tr>
      <tr>
        <td>Size : </td>
        <td><input class="form-control" type="text" value="<?php echo  $datamodal['size']; ?>" name="size"/></td>
      </tr>
      <tr>
        <td>Compound </td>
        <td><input class="form-control" type="text" value="<?php echo $datamodal['compound']; ?>" name="compound"></td>
      </tr>
      <tr>
        <td>Category </td>
        <td>                        
                        <select class="form-control" name="category">  
                         <option value="<?php echo $datamodal['category']; ?>"><?php echo $datamodal['category']; ?></option>  
                         <option value="Articulate Dump Truck">Articulate Dump Truck</option> 
                         <option value="Loader & Wheel Dozer">Loader & Wheel Dozer</option> 
                         <option value="Motor Grader">Motor Grader</option>  
                         <option value="Rigid Dump Trucks">Rigid Dump Trucks</option>    
                         <option value="Truck & Bus">Truck & Bus</option>
                         <option value="Underground Machine">Underground Machine</option>          
                        </select></td>
      </tr>
      <tr>
        <td>Supplier :</td>
        <td><input class="form-control" type="text" value="<?php echo $datamodal['supplier']; ?>" name="supplier"></td>
      </tr>
      <tr>
        <td>Price :</td>
        <td><input class="form-control" type="number" value="<?php echo $datamodal['price']; ?>" name="price"></td>
      </tr>           
    </table>
    <br><br>
    <button type="submit" value="submit" class="btn btn-default"><span class="glyphicon glyphicon-pencil"></span> Edit</button>
  </form>
<?php }
elseif ($item=='unit'){?>
  <?php $idunit= $_POST['idunit'];?>
  <form  class="form-inline" role="form" action="updateMasterData.php" method="post">
    <?php 
      $perintahmodal=mysqli_query($koneksi2,"SELECT * from unit_master where id_unit_master=$idunit");
      $datamodal = mysqli_fetch_array($perintahmodal);
    ?>
    <input class="form-control" value= "<?php echo $datamodal['id_unit_master']; ?>" type="hidden" name="idunit">
    <input class="form-control" value= "unit" type="hidden" name="item"> 
    <table>
      <tr>
        <td>Manufacture : </td>
        <td><input class="form-control" type="text" value="<?php echo $datamodal['unit_manufacture']; ?>" name="manufacture"></td>
      </tr>
      <tr>
        <td>Model :</td> 
        <td><input class="form-control" type="text" value="<?php echo $datamodal['model']; ?>" name="model"></td>
      </tr>
      <tr>
        <td>Tire size : </td>
        <td>
          <select class="form-control" name="tire_size">  
            <option value="<?php echo $datamodal['tire_size']; ?>"><?php echo $datamodal['tire_size']; ?></option>     
              <?php 
                $perintah=mysqli_query($koneksi2,"SELECT size from tire_master group by size order by size");
                while ($data = mysqli_fetch_array($perintah)) {?>    
                  <option value=<?php echo  $data['size']; ?>><?php echo  $data['size'];?></option>     
              <?php }?>         
           </select>            
        </td>
      </tr>
      <tr>
        <td>Tire Qty </td>
        <td><input class="form-control" type="text" value="<?php echo $datamodal['tire_quantity']; ?>" name="quantity"></td>
      </tr>
      <tr>
        <td>Category : </td>
        <td>
          <select class="form-control" name="category">  
            <option value="<?php echo $datamodal['category']; ?>"><?php echo $datamodal['category']; ?></option>
            <option value="RDT">RDT</option>
            <option value="ADT">ADT</option>
            <option value="Suppeq">Suppeq</option>
            <option value="T&B">T&B</option> 
            <option value="Drilling">Drilling</option> 
           </select>            
        </td>
      </tr>
    </table>
    <br><br>
    <button type="submit" value="submit" class="btn btn-default"><span class="glyphicon glyphicon-pencil"></span> Edit</button>
  </form>
<?php } 
elseif ($item=='customer'){?>
  <?php $idcustomer= $_POST['idcustomer'];?>
  <form  class="form-inline" role="form" action="updateMasterData.php" method="post">
    <?php 
      $perintahmodal=mysqli_query($koneksi2,"SELECT * from customer_master where id_customer_master=$idcustomer");
      $datamodal = mysqli_fetch_array($perintahmodal);
    ?>
    <input class="form-control" value= "<?php echo $datamodal['id_customer_master']; ?>" type="hidden" name="idcustomer">
    <input class="form-control" value= "customer" type="hidden" name="item"> 
    <table>
      <tr>
        <td>Customer name : </td>
        <td><input class="form-control" type="text" value="<?php echo $datamodal['customer']; ?>" name="customer"></td>
      </tr>
    </table>
    <br><br>
    <button type="submit" value="submit" class="btn btn-default"><span class="glyphicon glyphicon-pencil"></span> Edit</button>
  </form>
<?php } 
elseif ($item=='site'){?>
  <?php $idsite= $_POST['idsite'];?>
  <form  class="form-inline" role="form" action="updateMasterData.php" method="post">
    <?php 
      $perintahmodal=mysqli_query($koneksi2,"SELECT * from site_master a,customer_master b where a.id_site_master=$idsite and a.id_customer=b.id_customer_master");
      $datamodal = mysqli_fetch_array($perintahmodal);
    ?>
    <input class="form-control" value= "<?php echo $datamodal['id_site_master']; ?>" type="hidden" name="idsite">
    <input class="form-control" value= "site" type="hidden" name="item"> 
    <table>
      <tr>
        <td>Customer name : </td>
        <td>
          <select class="form-control" name="customer">  
            <option value="<?php echo $datamodal['id_customer']; ?>"><?php echo $datamodal['customer'];?></option>
              <?php 
                $perintah=mysqli_query($koneksi2,"SELECT id_customer_master,customer from customer_master order by customer");
                while ($data = mysqli_fetch_array($perintah)) {?>    
                  <option value=<?php echo  $data['id_customer_master']; ?>><?php echo  $data['customer'];?></option>     
                <?php }?>         
          </select>
        </td>
      </tr>
      <tr>
        <td>Mining company : </td>
        <td>
          <select class="form-control" name="mincom">  
            <option value="<?php echo $datamodal['mining_company']; ?>"><?php echo $datamodal['mining_company']; ?></option>
              <?php 
                $perintah=mysqli_query($koneksi2,"SELECT id_mining,mining_company from mining_company order by mining_company");
                while ($data = mysqli_fetch_array($perintah)) {?>    
                  <option value=<?php echo  $data['id_mining']; ?>><?php echo  $data['mining_company'];?></option>     
                <?php }?>         
          </select>
        </td>
      </tr>
      <tr>
        <td>Site/Project :</td> 
        <td><input class="form-control" type="text" value="<?php echo $datamodal['site']; ?>" name="site"></td>
      </tr>
      <tr>
        <td>Location : </td>
        <td><input class="form-control" type="text" value="<?php echo $datamodal['location']; ?>" name="location"></td>
      </tr>
      <tr>
        <td>Kabupaten :</td> 
        <td><input class="form-control" type="text" value="<?php echo $datamodal['kabupaten']; ?>" name="kabupaten"></td>
      </tr>
      <tr>
        <td>Kecamatan :</td> 
        <td><input class="form-control" type="text" value="<?php echo $datamodal['kecamatan']; ?>" name="kecamatan"></td>
      </tr>
      <tr>
        <td>Target OB:</td> 
        <td><input class="form-control" type="number" value="<?php echo $datamodal['target']; ?>" name="target"></td>
      </tr>
      <tr>
        <td>Target Coal:</td> 
        <td><input class="form-control" type="number" value="<?php echo $datamodal['target2']; ?>" name="target2"></td>
      </tr>
      <tr>
        <td>Status :</td> 
        <td><input class="form-control" type="text" value="<?php echo $datamodal['status']; ?>" name="status"></td>
      </tr>
      <tr>
        <td>Year update :</td> 
        <td><input class="form-control" type="number" value="<?php echo $datamodal['year_update']; ?>" name="yearupdate"></td>
      </tr>
    </table>
    <br><br>
    <button type="submit" value="submit" class="btn btn-default"><span class="glyphicon glyphicon-pencil"></span> Edit</button>
  </form>
<?php }
elseif ($item=='warranty'){
//   print_r($_POST); 
  $idwarranty= $_POST['idwarranty'];
  $q2 = mysqli_query ($koneksi5,("SELECT * from tab_warranty where no=$idwarranty"));
  $r2 = mysqli_fetch_array($q2);
  $actp = $r2['act_plan'];
  ?>
<div class="clearfix"></div>
<div class="col-md-12 left-margin">
    <form class="form-horizontal form-label-left" action="updateMasterData.php" method="post"> 
    <input class="form-control" value= "warranty" type="hidden" name="item"> 
    <input class="form-control" value= "<?php echo $idwarranty;?>" type="hidden" name="idwarranty"> 
        <div class="form-group">
            <label>Tire serial number</label>
            <input type="text" class="form-control" value="<?php echo $r2['sn_tire']; ?>" disabled>
        </div> 
        <div class="form-group">
            <label>Submit Date</label>
            <input type="text" class="form-control" value="<?php echo $r2['date_in']; ?>" disabled>
        </div> 
        <div class="form-group">
            <label>Status</label>
            <?php 
            if($actp=="Waiting Document"){ ?>
            <select class="form-control" name="act_plan">
                <option value="<?php echo $r2['act_plan'];?>" selected><?php echo $r2['act_plan']; ?></option>
                <option value="Accept">Accept</option>
                <option value="Reject">Reject</option>
            </select>
            <?php }
            elseif($actp=="Accept"){?>
            <select class="form-control" name="act_plan">
                <option value="<?php echo $r2['act_plan'];?>" selected><?php echo $r2['act_plan']; ?></option>
                <option value="Done">Done</option>
                <option value="Reject">Reject</option>
            </select>
            <?php }
            elseif($actp=="Reject"){?>
            <select class="form-control" name="act_plan">
                <option value="<?php echo $r2['act_plan'];?>" selected><?php echo $r2['act_plan']; ?></option>
            </select>
            <?php }
            elseif($actp=="Done"){?>
            <select class="form-control" name="act_plan">
                <option value="<?php echo $r2['act_plan'];?>" selected><?php echo $r2['act_plan']; ?></option>
            </select>
            <?php }
            ?>
        </div>
        <div class="form-group">
            <label>Accept / Reject date</label>
            <input type="date" class="form-control" name="status_date" value="<?php echo $r2['date_accept']; ?>" min="<?php echo $r2['date_in'];?>">
        </div>
        <div class="form-group">
            <label>Accept / Reject by</label>
            <select class="form-control" name="by">
                <option value="<?php echo $r2['acc_by'];?>"><?php echo $r2['acc_by'];?></option>
                <option value="Chitra Paratama">Chitra Paratama</option>
                <option value="Principal">Principal</option>
            </select>
        </div>
         <?php 
            if($actp=="Accept"){ ?>
            <div class="form-group">
                <label>Done date</label>
                <input type="date" class="form-control" name="done_date" value="<?php echo $r2['date_closed']; ?>" min="<?php echo $r2['date_accept'];?>">
            </div>
            <?php 
            } 
        ?>
        <div class="form-group">
            <label>Remark</label>
            <textarea class="form-control" rows="3" name="note"><?php echo $r2['note'];?></textarea>
        </div>  
        <button type="submit" value="submit" class="btn btn-success"> Submit</button>
    </form>
</div>
<form  class="form" role="form" action="updatedatawarranty.php" method="post"></form>
  <?php
} 
?>