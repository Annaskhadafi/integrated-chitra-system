<style>
table, th, td {
  border: 1px solid black;
}
td {
  height: 25px;
  vertical-align: center;
}
td {
  text-align: center;
}
</style>
<?php 
    include "koneksi.php"; 
    $picgi = $_POST['picgi'];
    $pn = $_POST['pn'];
    $date = $_POST['date'];
    $qty = $_POST['qty'];
    $storeloc = $_POST['storeloc'];
    
    $perintah = mysqli_query($koneksi6,"SELECT count(*) as onhand
                                        FROM stock a,part_number b,storeloc c
                                        WHERE a.id_part_number=b.id_part_number AND a.id_storeloc=c.id_storeloc AND b.id_part_number=$pn AND c.id_storeloc=$storeloc AND wo IS NULL AND gi IS NULL;
                                        ");
    $data = mysqli_fetch_array($perintah);
    $onhand=$data['onhand'];
    // jika wo lebih kecil dari stock onhand, tampilkan list
    if ($qty<=$onhand){
        ?>
        <form id="formData" action="vhs_updatestock.php" method="post">
            <input type="hidden" name="picgi" value=<?php echo $picgi;?>>
            <input type="hidden" name="date" value="<?php echo $date;?>">
            <table style="width:100%">
                <tr>
                <th>No</th>
                <th>id</th>
                <th>Size</th>
                <th>Brand</th>
                <th>Pattern</th>
                <th>Part number</th>
                <th>MM_CK</th>
                <th>Do</th>
                <th>Do date</th>
                <th>Status</th>
                <th>Storeloc</th>
                <th>Location</th>
                <th>GR/GI date</th>
                <th style="width:10%">WO number</th>
                <th style="width:10%">GI</th>
            </tr>
                <?php
                // echo $pn."<br>";
                // echo $date."<br>";
                // echo $qty."<br>";
                // echo $storeloc."<br>";
                $no=1;
                $perintah = mysqli_query($koneksi6,"SELECT * 
                                                    FROM stock a,part_number b,storeloc c
                                                    WHERE a.id_part_number=b.id_part_number AND a.id_storeloc=c.id_storeloc AND b.id_part_number=$pn AND c.id_storeloc=$storeloc AND wo IS NULL AND gi IS NULL
                                                    LIMIT $qty;
                                                    ");
                while ($data = mysqli_fetch_array($perintah)) {?>
                    <tr>
                        <td><?php echo $no;?>.</td>
                        <td>
                            <?php echo $data['id_stock'];?>
                            <input type="hidden" name="idstock[]" value="<?php echo $data['id_stock'];?>">
                        </td>
                        <td><?php echo $data['size'];?></td>
                        <td><?php echo $data['brand'];?></td>
                        <td><?php echo $data['pattern'];?></td>
                        <td><?php echo $data['part_number'];?></td>
                        <td><?php echo $data['mm_ck'];?></td>
                        <td><?php echo $data['do'];?></td>
                        <td><?php echo $data['delivery_date'];?></td>
                        <td><?php echo $data['status'];?></td>
                        <td><?php echo $data['storeloc'];?></td>
                        <td><?php echo $data['location'];?></td>
                        <td><?php echo $date;?></td>
                        <td><input type="text" name="wo[]" placeholder="WO" required></td>
                        <td><input type="text" name="gi[]" placeholder="GR/GI" required></td>
                    </tr><?php 
                    $no++;
                }
                ?>
            </table>
            <br>
            <button type="submit">Update</button>
        </form>
        <?php 
    }
    else {
        echo "<script>alert('Stock on hand kurang atau tidak ada !!\\nonhand=".$onhand." '); window.location.href='vhs_halamanstockvhs.php';</script>";
    }
?>