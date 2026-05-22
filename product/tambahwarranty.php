<?php
include "koneksi.php";
    print_r($_POST);
    $sn_tire = $_POST['sn_tire'];
    $size = $_POST['size'];
    $area = $_POST['area'];
    $otd = $_POST['otd'];
    $rtd = $_POST['rtd'];
    $tire_desc = $_POST['tire_desc'];
    $compound_tire = $_POST['compound_tire'];
    $lifetime = $_POST['lifetime'];
    $brand = $_POST['brand'];
    $injury = $_POST['injury'];
    $costumer = $_POST['costumer'];
    $site = $_POST['site'];
    $date_doc = $_POST['date_doc'];
    $prob_cause = $_POST['prob_cause'];
    $target = $_POST['target'];
    $status = $_POST['status'];
    $price = $_POST['price'];
    $est_loss = ($price/$target)*($target-$lifetime);
    $worn = (($otd-$rtd)/$otd)*100;
    $worn = $worn."%";
    
    

        $query = mysqli_query($koneksi5, "
        INSERT into tab_warranty (sn_tire,tire_size,otd,rtd,worn,tire_desc,compound_tire,lifetime,brand,injury,costumer,site,date_in,prob_cause,date_princ,act_plan,date_closed,target,status,price,est_loss,area)
        VALUES ('$sn_tire','$size','$otd','$rtd','$worn','$tire_desc','$compound_tire','$lifetime','$brand','$injury','$costumer','$site','$date_doc','$prob_cause','$date_cp','Waiting Document','$date_princ','$target','$status','$price','$est_loss','$area')");

        echo "<script>
        history.go(-1);
        </script>";
?>