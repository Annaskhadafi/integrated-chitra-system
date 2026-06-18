<?php 
include "koneksi.php";
include "auth_check.php";
require_super_admin($koneksi);

// Amankan input
$iduser = isset($_POST['iduser']) ? intval($_POST['iduser']) : 0;

// Ambil data user (termasuk info site)
$query = mysqli_query($koneksi, "
    SELECT *
    FROM user
    WHERE id_user = $iduser
");
$datamodal = mysqli_fetch_array($query);
?>

<!-- Form Edit Data User -->
<form action="updateuser.php" method="post">
  <input type="hidden" name="id_user" value="<?php echo $datamodal['id_user']; ?>">

  <div class="form-group">
    <label>SN</label>
    <input type="text" name="sn" class="form-control" value="<?php echo $datamodal['sn']; ?>" required>
  </div>

  <div class="form-group">
    <label>Name</label>
    <input type="text" name="name" class="form-control" value="<?php echo $datamodal['name']; ?>" required>
  </div>

  <div class="form-group">
    <label>Username</label>
    <input type="text" name="username" class="form-control" value="<?php echo $datamodal['username']; ?>" required>
  </div>

  <div class="form-group">
    <label>Password</label>
    <input type="text" name="password" class="form-control" value="<?php echo $datamodal['password']; ?>" required>
  </div>


    <div class="form-group">
    <label>Section</label>
        <select class="form-control" name="section" required>
            <option value="<?php echo $datamodal['section']; ?>"><?php echo $datamodal['section']; ?></option>
            <?php 
            $perintah = mysqli_query($koneksi, "SELECT * FROM section WHERE section !='' ORDER BY section ");
            while ($data = mysqli_fetch_array($perintah)) {
                echo "<option value='{$data['id_section']}'>{$data['section']}</option>";
            }
            ?>
        </select>
    </div>

  <div class="form-group">
    <label>Email</label>
    <input type="email" name="email" class="form-control" value="<?php echo $datamodal['email']; ?>">
  </div>

  <!-- SELECT: Level -->
  <div class="form-group">
    <label>Level</label>
    <select class="form-control" name="level" required>
      <option value="<?php echo $datamodal['level']; ?>">
        <?php 
        if ($datamodal['level'] == 1) {
          echo "Admin";
        } elseif ($datamodal['level'] == 2) {
          echo "Staff";
        } else {
          echo "Managerial";
        }
        ?>
      </option>
      <?php if ($datamodal['level'] != 1): ?><option value="1">Admin</option><?php endif; ?>
      <?php if ($datamodal['level'] != 2): ?><option value="2">Staff</option><?php endif; ?>
      <?php if ($datamodal['level'] != 3): ?><option value="3">Managerial</option><?php endif; ?>
    </select>
  </div>

  <div class="form-group text-right">
    <button type="submit" class="btn btn-primary">
      <span class="glyphicon glyphicon-pencil"></span> Update
    </button>
  </div>
</form>
