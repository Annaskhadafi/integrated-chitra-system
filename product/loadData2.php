<?php 
include "koneksi.php";
$str = $_POST['str'];
$data=explode(";",$str);
$fakultas=$data[0];
$size=$data[1];
$tampil=mysqli_query($koneksi6,"SELECT part_number,pattern,id_part_number FROM part_number WHERE brand='$fakultas' AND size='$size' ");
$jml=mysqli_num_rows($tampil);
if($jml > 0){    ?>
    <option value=""></option>
    <?php
    while($r=mysqli_fetch_array($tampil)){
        ?>
        <option value="<?php echo $r['id_part_number']?>"><?php echo $r['pattern']?> / <?php echo $r['part_number']?></option>
        <?php        
    }
}else{
    echo "<option selected>- Not available pattern -</option>";
}
?>