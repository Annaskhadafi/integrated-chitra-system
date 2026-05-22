<?php 
$idforecast= $_POST['idforecast'];
// echo $idforecast;
?>
<h2>Edit prepared stock</h2>
<?php include "koneksi.php";?>
    <form action="updatestockvhssales.php" method="POST">
        <?php 
            $loop=0;
            $perintahmodal=mysqli_query($koneksi6, "SELECT * from stock a, part_number b where a.id_forecast=$idforecast and a.id_part_number=b.id_part_number "); 
            while ($datamodal = mysqli_fetch_array($perintahmodal)){
                $size=$datamodal['size'];?>
                <input class="form-control" type="hidden" value=<?php echo $datamodal['id_stock'];?> name="idstock[]"/>
                <select class="form-control" name="idpartnumber[]"required>
                    <option value="<?php $idpartnumber=$datamodal['id_part_number']; echo $datamodal['id_part_number'];?>"><?php echo $datamodal['brand']." // ".$datamodal['pattern'];?></option>
                    <?php
                        $perintahmodal2=mysqli_query($koneksi6, "SELECT * FROM part_number WHERE size='$size' and id_part_number != $idpartnumber  ");
                        while($datamodal2 = mysqli_fetch_array($perintahmodal2)){
                            ?>
                            <option value="<?php echo $datamodal2['id_part_number']?>"><?php echo $datamodal2['brand']." // ".$datamodal2['pattern'];?></option>
                            <?php
                        }
                    ?>
                </select>
                <?php
                $loop++;
            }
        ?>
        <input class="form-control" type="hidden" value=<?php echo $loop;?> name="loop"/>
        <button class="btn btn-success" type="submit">Submit</button>
    </form>
