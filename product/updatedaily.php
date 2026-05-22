<?php
//tangkap data pressure1-6 dan id1-6
$pressure1= $_POST['pressure1'];
$pressure2= $_POST['pressure2'];
$pressure3= $_POST['pressure3'];
$pressure4= $_POST['pressure4'];
$pressure5= $_POST['pressure5'];
$pressure6= $_POST['pressure6'];
$id1= $_POST['id1'];
$id2= $_POST['id2'];
$id3= $_POST['id3'];
$id4= $_POST['id4'];
$id5= $_POST['id5'];
$id6= $_POST['id6'];
$loop= $_POST['loop'];?>
<?php
include "koneksi.php";
//update data tabel daily dengan id_daily=$id1-6
$perintah1 = mysqli_query($sambung, "update daily set pressure=$pressure1 where id_daily=$id1");
$perintah2 = mysqli_query($sambung, "update daily set pressure=$pressure2 where id_daily=$id2");
$perintah3 = mysqli_query($sambung, "update daily set pressure=$pressure3 where id_daily=$id3");
$perintah4 = mysqli_query($sambung, "update daily set pressure=$pressure4 where id_daily=$id4");
$perintah5 = mysqli_query($sambung, "update daily set pressure=$pressure5 where id_daily=$id5");
$perintah6 = mysqli_query($sambung, "update daily set pressure=$pressure6 where id_daily=$id6");
 echo"<script>
        history.go(-1);
        </script>";  
?>
