<?php 
$idforecast= $_POST['idforecast'];
// echo $idforecast;
?>
<h2>Review Forecast</h2>
<?php include "koneksi.php";
  $perintahmodal=mysqli_query($koneksi6, "SELECT * FROM forecast WHERE id_forecast=$idforecast");
  $datamodal = mysqli_fetch_array($perintahmodal);
  $size= $datamodal['size'];
  ?>
  Size :<?php echo $size; ?><br>
  Forecast qty :<?php echo $datamodal['quantity'];?><br>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <form action="tambahstockvhs.php" method="POST" data-parsley-validate class="form-horizontal form-label-left">
                    <input class="form-control" type="hidden" value=<?php echo $datamodal['size'];?> name="size" />
                    <input class="form-control" type="hidden" value=<?php echo $datamodal['quantity'];?> name="forecastqty" />
                    <input class="form-control" type="hidden" value=<?php echo $datamodal['id_forecast'];?> name="idforecast" />
                    <div class="form-group">
                        <div class="col-md-4 col-sm-4 col-xs-4">
                          <h2>Brand</h2>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-4">
                          <h2>pattern</h2>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-4">
                          <h2>Quantity</h2>
                        </div>
                      </div>
                    <div class="form-group">
                        <div class="col-md-4 col-sm-4 col-xs-4">
                            <select class="form-control" name="brand[]" id="merek" required>
                                <option value=""></option>
                            		<?php
                                        $perintahmodal2=mysqli_query($koneksi6, "SELECT brand FROM part_number WHERE size='$size' GROUP BY brand ");
                            			while($f = mysqli_fetch_array($perintahmodal2)){
                            				?>
                            				<option value="<?php echo $f['brand'].';'.$datamodal['size']; ?>"><?php echo $f['brand']; ?></option>
                            				<?php
                            			}
                            			?>
                            </select>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-4">
                          <select class="form-control" name="idpartnumber[]" id="idpartnumber" required></select>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-4">
                          <input class="form-control col-md-7 col-xs-12" type="text" value="<?php echo $today;?>" name="qty[]">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-4 col-sm-4 col-xs-4">
                            <select class="form-control" name="brand[]" id="brand">
                                <option value=""></option>
                            		<?php
                                        $perintahmodal2=mysqli_query($koneksi6, "SELECT brand FROM part_number WHERE size='$size' GROUP BY brand ");
                            			while($f = mysqli_fetch_array($perintahmodal2)){
                            				?>
                            				<option value="<?php echo $f['brand'].';'.$datamodal['size']; ?>"><?php echo $f['brand']; ?></option>
                            				<?php
                            			}
                            			?>
                            </select>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-4">
                          <select class="form-control" name="idpartnumber[]" id="idpartnumber2"></select>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-4">
                          <input class="form-control col-md-7 col-xs-12" type="text" value="<?php echo $today;?>" name="qty[]">
                        </div>
                     </div>
                    <div class="ln_solid"></div>
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                          <button type="submit" class="btn btn-primary">Cancel</button>
                          <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                      </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
	$('#merek').change(function() { 
		var variabelajaxbrand = $(this).val(); 
		$.ajax({
			type: 'POST', 
			url: 'loadData2.php', 
			data: {str:variabelajaxbrand}, 
			success: function(response) { 
				$('#idpartnumber').html(response); 
			}
		});
	});
 
	$('#brand').change(function() { 
		var variabelajaxbrand = $(this).val(); 
		$.ajax({
			type: 'POST', 
			url: 'loadData2.php', 
			data: {str:variabelajaxbrand}, 
			success: function(response) { 
				$('#idpartnumber2').html(response); 
			}
		});
	});
</script>