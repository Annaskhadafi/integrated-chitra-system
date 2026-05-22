<?php 
$idforecast= $_POST['idforecast'];
// echo $idforecast;
include "koneksi.php";
?>
<h2>Review Forecast</h2>
<table id="datatable-buttons" class="table table-striped table-bordered">
    <thead style="background:#f5f5f5;">
        <tr>
            <th>No</th>
            <th>Serial number</th>
            <th>Size</th>
            <th>Brand</th>
            <th>Pattern</th>
            <th>Allocation date</th>
            <th>Delivery date</th>
            <th>Received Date</th>
            <th>Status</th>
            <th>Site</th>
            <th>Forecast</th>
        </tr>
    </thead>
    <tbody>
    <?php
        $perintah = mysqli_query($koneksi6,"SELECT a.id_stock,a.sn,c.size,c.brand,c.pattern,a.allocation_date,a.delivery_date,a.received_date,a.status,b.project,b.id_forecast 
                                                FROM stock a, forecast b ,part_number c
                                                WHERE a.id_forecast=$idforecast AND a.id_forecast=b.id_forecast AND a.id_part_number=c.id_part_number");
        while ($data = mysqli_fetch_array($perintah)) {?>
        <tr>
            <td><?php echo $data['id_stock'];?></td>
            <td><?php echo $data['sn'];?></td>
            <td><?php echo $data['size'];?></td>
            <td><?php echo $data['brand'];?></td>
            <td><?php echo $data['pattern'];?></td>
            <td><?php $allocationdate = strtotime($data['allocation_date']); echo $data['allocation_date'];?></td>
            <td><?php $deliverydate = strtotime($data['delivery_date']); echo $data['delivery_date'];?></td>
            <td><?php $receiveddate = strtotime($data['received_date']); echo $data['received_date'];?></td>
            <td><?php $status=$data['status']; echo $status;?></td>
            <td><?php echo $data['project'];?></td>
            <td>Forc-<?php echo $data['id_forecast'];?></td>
        </tr>
            <?php 
        } ?>
    </tbody>
</table><br>
<button class="btn btn-success" type="submit">Submit</button>
