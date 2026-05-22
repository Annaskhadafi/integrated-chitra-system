<?php 
$project= $_POST['project'];
$mrko= $_POST['idforecast'];
$job= $_POST['job'];
// echo $mrko;
// echo $job;
?>
<h2>Edit VHS stock</h2>
<?php include "koneksi.php";
if($job=='delivery'){?>
    <form action="updatestockvhsscm.php" method="POST" data-parsley-validate class="form-horizontal form-label-left">
    <table id="datatable-buttons" class="table table-striped table-bordered">
        <thead style="background:#f5f5f5;">	
            <tr>
                <th>No</th>
                <th>Size / Brand / Pattern</th>
                <th>PN</th>
                <th>SN</th>
                <th width="130px">Est-Deliv</th>
                <th width="130px">Act-Deliv</th>
                <th width="180px">DO</th>
                <th>Site</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $loop=1;
                $perintahmodal=mysqli_query($koneksi6, "SELECT * from stock a, part_number b,forecast c where a.id_forecast=$mrko and a.id_part_number=b.id_part_number and a.id_forecast=c.id_forecast "); 
                while ($datamodal = mysqli_fetch_array($perintahmodal)){?>
                    <input class="form-control" type="hidden" value=<?php echo $datamodal['id_stock'];?> name="idstock[]"/>
                    <tr>
                        <td><?php echo $loop;?></td>
                        <td><?php echo $datamodal['size']." ".$datamodal['brand']." ".$datamodal['pattern'];?></td>
                        <td><?php echo $datamodal['part_number'];?></td>
                        <td><input class="form-control" type="text" value="<?php echo $datamodal['sn'];?>" name="sn[]"></td>
                        <td><input class="form-control" type="text" value="<?php echo $datamodal['estimasi'];?>" name="est[]" data-inputmask="'mask': '9999-99-99'" placeholder="yyyy/mm/dd"></td>
                        <td><input class="form-control" type="text" value="<?php echo $datamodal['delivery_date'];?>" name="delivery[]" data-inputmask="'mask': '9999-99-99'" placeholder="yyyy/mm/dd"></td>
                        <td><input class="form-control" type="text" value="<?php echo $datamodal['do'];?>" name="do[]"></td>
                        <td><?php echo $datamodal['project'];?></td>
                    </tr>
                    <?php
                    $loop++;
                }
            ?>
        </tbody>
    </table>
    <input class="form-control" type="hidden" value=<?php echo $idforecast;?> name="idforecast"/>
    <input class="form-control" type="hidden" value=<?php echo $loop;?> name="loop"/>
    <input class="form-control" type="hidden" value=<?php echo $job;?> name="job"/>
    <div class="ln_solid"></div>
    <div class="form-group">
        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
            <button type="submit" class="btn btn-primary">Cancel</button>
            <button type="submit" class="btn btn-success">Submit</button>
        </div>
    </div>
</form>
    <?php
}
elseif($job=='invoice'){ ?>
    <form action="updatestockvhsscm.php" method="POST" data-parsley-validate class="form-horizontal form-label-left">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-6">
                <label>Invoice :</label><input class="form-control" type="text" value="<?php echo $datamodal['invoice'];?>" name="invoice" required>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6">
                <label>Invoice Date :</label><input class="form-control" type="text" value="<?php echo $datamodal['inv_date'];?>" data-inputmask="'mask': '9999-99-99'" placeholder="yyyy/mm/dd" name="inv_date" required>
                <input class="form-control" type="hidden" value="<?php echo $mrko;?>" name="mrko">
            </div>
        </div>
    <table id="datatable-buttons" class="table table-striped table-bordered">
        <thead style="background:#f5f5f5;">	
            <tr>
                <th>Size / Brand / Pattern</th>
                <th>PN</th>
                <th>SN</th>
                <th width="130px">DO</th>
                <th width="130px">MRKO</th>
                <th>Site</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $loop=0;
                $perintahmodal=mysqli_query($koneksi6, "SELECT * from stock a, part_number b,forecast c where a.mrko='$mrko' and a.id_part_number=b.id_part_number and a.id_forecast=c.id_forecast and c.project like '$project' "); 
                while ($datamodal = mysqli_fetch_array($perintahmodal)){?>
                    <input class="form-control" type="hidden" value=<?php echo $datamodal['id_stock'];?> name="idstock[]"/>
                    <tr>
                        <td><?php echo $datamodal['size']." ".$datamodal['brand']." ".$datamodal['pattern'];?></td>
                        <td><?php echo $datamodal['part_number'];?></td>
                        <td><?php echo $datamodal['sn'];?></td>
                        <td><?php echo $datamodal['do'];?></td>
                        <td><?php echo $datamodal['mrko'];?></td>
                        <td><?php echo $datamodal['project'];?></td>
                    </tr>
                    <?php
                    $loop++;
                }
            ?>
        </tbody>
    </table>
    <input class="form-control" type="hidden" value=<?php echo $idforecast;?> name="idforecast"/>
    <input class="form-control" type="hidden" value=<?php echo $loop;?> name="loop"/>
    <input class="form-control" type="hidden" value=<?php echo $job;?> name="job"/>
    <div class="ln_solid"></div>
    <div class="form-group">
        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
            <button type="submit" class="btn btn-primary">Cancel</button>
            <button type="submit" class="btn btn-success">Submit</button>
        </div>
    </div>
</form>
    <?php
}
?>
<script src="../vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>
    <!-- jquery.inputmask -->
    <script>
      $(document).ready(function() {
        $(":input").inputmask();
      });
    </script>
    <!-- /jquery.inputmask -->