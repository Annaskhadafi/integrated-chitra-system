<!-- tangkap nilai idunitsite, inisialisasi $idunitsite -->
<?php $idunitsite= $_POST['idunitsite']; ?>
<!-- form edit unit_site (tambah HMU unit) -->
<form  class="form-inline" role="form" action="updateunitsite.php" method="post">
  <?php include "koneksi.php";
  $perintahmodal=mysqli_query($sambung, "SELECT * from unit_site a where id_unit_site = $idunitsite");
  $datamodal = mysqli_fetch_array($perintahmodal);
  $idunitmaster=$datamodal['unit'];
  $perintah1 = mysqli_query($sambung, "SELECT * FROM unit WHERE id_unit=$idunitmaster");
  $data1 = mysqli_fetch_array($perintah1);   

  //cek apakah tire ada atau tidak 
  $tire=$data1['tire']; // cek tire menggunakan $data1 
  $sum = 0;
  //setiap $loop mewakili 1 posisi, unit dengan 6 tire kan loopr sebanyak 6 kali
  for ($loop=1; $loop < $tire+1; $loop++){
    $perintahcek=mysqli_query($sambung, "SELECT * from tire_movement where unit_number = $idunitsite and posisi=$loop order by id_movement desc limit 1");
    $cek=mysqli_fetch_array($perintahcek);
    $nilai=$cek['job'];
    $sum += $nilai;   // jika tire terpasang, tambahkan value 1 ke variabel $sum 
  }
  ?>  

  <table>
    <col width="200">
    <tr>
      <td><h2>Unit Number</h2></td>
      <td><h2>: <?php echo $datamodal['unit_number']; ?></h2></td>
    </tr>
    <tr>
      <td><h2>Model</h2></td>
      <td><h2>: <?php echo $hm=$data1['unit']; ?></h2></td>
    </tr>
    <tr>
      <td><h2>Last HM</h2></td>
      <td><h2>: <?php $hm=$datamodal['hm']; echo $hm; ?></h2></td>
    </tr>
  </table>
  <input class="form-control" value= "<?php echo  $datamodal['id_unit_site']; ?>" type="hidden" name="idunitsite"/>
  <input  style="width:80px"class="form-control" type="text" value="<?php echo  $datamodal['unit_number']; ?>" name="unitnumber"/>
  <select class="form-control" name="unit">  
    <option value="<?php echo  $data1['id_unit']; ?>"><?php echo $data1['unit']; ?></option>
    <?php 
    $perintah7=mysqli_query($sambung, "SELECT * from unit ORDER BY unit ASC");
    while ($data7 = mysqli_fetch_array($perintah7)) {?>
    <option value="<?php echo $data7['id_unit']; ?>"><?php echo $data7['unit']; ?></option>
    <?php } ?> 

  </select>
  <!-- jika hasil $sum (pengecekan tire)!= jumlah tire($tire), munculkan "tire not complete" dan form upoate HM   -->
  <div class="item form-group">
    <?php if ($sum!=$tire){ ?> 
      Tire not complete !
      <input type="text" required="required" class="form-control col-md-7 col-xs-12" disabled="disabled" value="<?php echo $hm;?>" placeholder="<?php echo $hm;?>">
      <input class="form-control" value= "<?php echo $hm;?>" type="hidden" name="hm"/>
      <?php 
    }
    else { ?>
      <input type="number" id="number" name="hm" required="required" data-validate-minmax="<?php echo $hm;?>,<?php echo $hm+1000;?>" class="form-control col-md-7 col-xs-12" value="<?php echo $hm;?>"><?php 
      }?>
  </div>
  </br></br>
  <button type="submit" value="submit" class="btn btn-default"><span class="glyphicon glyphicon-pencil"></span> Edit</button>
</form>

<!-- validator -->
<script src="../vendors/validator/validator.js"></script>
<script>
  // initialize the validator function
  validator.message.date = 'not a real date';

  // validate a field on "blur" event, a 'select' on 'change' event & a '.reuired' classed multifield on 'keyup':
  $('form')
        .on('blur', 'input[required], input.optional, select.required', validator.checkField)
        .on('change', 'select.required', validator.checkField)
        .on('keypress', 'input[required][pattern]', validator.keypress);

      $('.multi.required').on('keyup blur', 'input', function() {
        validator.checkField.apply($(this).siblings().last()[0]);
      });

  $('form').submit(function(e) {
        e.preventDefault();
        var submit = true;

        // evaluate the form using generic validaing
        if (!validator.checkAll($(this))) {
          submit = false;
        }

        if (submit)
          this.submit();

        return false;
      });
</script>