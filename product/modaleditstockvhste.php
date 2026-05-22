<?php 
$idstock= $_POST['idstock'];
// echo $idstock;
?>
<h2>Edit VHS stock</h2>
<?php include "koneksi.php";?>
<form action="updatestockvhste.php" method="POST" data-parsley-validate class="form-horizontal form-label-left">
    <table id="datatable-buttons" class="table table-striped table-bordered">
        <thead style="background:#f5f5f5;">	
            <tr>
                <th>Size / Brand / Pattern</th>
                <th>SN</th>
                <th>Status</th>
                <th width="130px">Install date</th>
                <th width="130px">Unit</th>
                <th width="130px">Position</th>
                <th width="180px">WO</th>
                <th width="180px">MRKO</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $loop=0;
                $perintahmodal=mysqli_query($koneksi6, "SELECT * from stock a, part_number b where a.id_stock=$idstock and a.id_part_number=b.id_part_number "); 
                while ($datamodal = mysqli_fetch_array($perintahmodal)){?>
                    <input class="form-control" type="hidden" value=<?php echo $datamodal['id_stock'];?> name="idstock"/>
                    <tr>
                        <td><?php echo $datamodal['size']." ".$datamodal['brand']." ".$datamodal['pattern'];?></td>
                        <td><?php echo $datamodal['sn'];?></td>
                        <td><?php echo $datamodal['status'];?></td>
                        <td><input class="form-control" type="date" value="<?php echo $datamodal['install_date'];?>" name="install" min="<?php echo $datamodal['received_date'];?>" required></td>
                        <td><input class="form-control" type="text" value="<?php echo $datamodal['unit'];?>" name="unit" required></td>
                        <td><input class="form-control" type="text" value="<?php echo $datamodal['position'];?>" name="position" required></td>
                        <td><input class="form-control" type="text" value="<?php echo $datamodal['wo'];?>" name="wo" required></td>
                        <td><input class="form-control" type="text" value="<?php echo $datamodal['mrko'];?>" name="mrko"></td>
                    </tr>
                    <?php
                    $loop++;
                }
            ?>
        </tbody>
    </table>
    <div class="ln_solid"></div>
    <div class="form-group">
        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
            <button type="submit" class="btn btn-primary">Cancel</button>
            <button type="submit" class="btn btn-success">Submit</button>
        </div>
    </div>
</form>
<script src="../vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>
    <!-- jquery.inputmask -->
    <script>
      $(document).ready(function() {
        $(":input").inputmask();
      });
    </script>
    <!-- /jquery.inputmask -->