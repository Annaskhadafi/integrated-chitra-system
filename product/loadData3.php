<?php 
include "koneksi.php";
$size = $_POST['str'];
$tampil=mysqli_query($koneksi6,"SELECT brand,pattern FROM part_number WHERE size='$size' ORDER BY brand");
$jml=mysqli_num_rows($tampil);
if($jml > 0){    ?>
    <option value=""></option>
    <?php
    while($r=mysqli_fetch_array($tampil)){
        ?>
        <option value="<?php echo $r['brand'].";".$r['pattern'];?>"><?php echo $r['brand']." / ".$r['pattern'];?></option>
        <?php        
    }
}else{
    echo "<option selected>- Not available pattern -</option>";
}
?>