<?php
include "koneksi.php";
$loadType=$_POST['loadType'];
$loadId=$_POST['loadId'];

// if($loadType=="kabupaten"){
if($loadType=="site"){
    // $sql="select id,nama_kabupaten from kabupaten where id_provinsi='".$loadId."' order by nama_kabupaten asc";
    $res=mysqli_query($koneksi2,"SELECT id_site_master,site from site_master where id_customer=$loadId order by site asc");
}
// else{
//     $sql="select id,nama_kecamatan from kecamatan where id_kabupaten='".$loadId."' order by nama_kecamatan asc";
// }

$check=mysqli_num_rows($res);
if($check > 0){
    $HTML="";
    while($row=mysqli_fetch_array($res)){
        $HTML.="<option value='".$row['id_site_master']."'>".$row['1']."</option>";
    }
    echo $HTML;
}

?>