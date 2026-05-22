<?php
include "koneksi.php"; 
$to = $_POST['to'];
$from = $_POST['from'];
$do = $_POST['do'];
$pn = $_POST['pn'];
$date = $_POST['date'];
$qty = $_POST['qty'];

// echo $to.": to<br>";
// echo $from.": from<br>";
// echo $do.": do<br>";
// echo $date.": date<br>";
// echo $qty.": qty<br>";
// echo $pn.": pn<br>";
$perintah = mysqli_query($koneksi6,"SELECT count(*) as jumlahstock FROM stock WHERE id_storeloc=$from AND id_part_number=$pn AND status='onsite' and gi IS NULL");
$data = mysqli_fetch_array($perintah);
$stock=$data['jumlahstock'];
// echo $stock."<br>";
if($qty>$stock){
    echo "<script>
            alert('Stock tidak mencukupi, stock onsite = $stock');
            window.history.back();
          </script>";
}
else{
    $query = mysqli_query($koneksi6, "
        UPDATE stock sb
        JOIN (
            SELECT id_stock
            FROM stock
            WHERE id_storeloc = $from 
              AND id_part_number = $pn 
              AND status = 'onsite' 
              AND gi IS NULL
            LIMIT $qty
        ) AS temp ON sb.id_stock = temp.id_stock
        SET 
            sb.id_storeloc = $to,
            sb.do = '$do',
            sb.delivery_date = '$date'
    ");
    echo "<script>
            alert('Stock transferred succesfully');
            window.location.href = 'vhs_halamanstockvhs.php';
          </script>";
}
?>
       