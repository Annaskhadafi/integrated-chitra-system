<?php
require_once "login_helper.php";
safe_session_start();

$ip = get_ip_address();
$lockout_remaining = check_lockout($ip);
$show_captcha = get_failed_count($ip) >= CAPTCHA_THRESHOLD;
?>
<!DOCTYPE html>
<html lang="en">
<?php
include "sectionhead.php"; // call sectionhead.php as library
?>
<style>
  body {
    background-image: url("images/10.jpg");
    background-repeat: no-repeat;
    background-size: cover;
  }
</style>

<body>
  <div class="login_wrapper">
    <div class="animate form login_form">
      <section class="login_content">
        <!-- start form login -->
        <form role="form" action="proses_login.php" method="post">
          <h3> Integrated Chitra System-ICS </h3>
          <div class="form-group">
            <!-- form username dilempar dengan fungsi POST -->
            <input type="username" name="username" class="form-control" id="username" placeholder="Username" required <?php echo ($lockout_remaining > 0) ? 'disabled' : ''; ?>>
          </div>
          <div class="form-group">
            <!-- form password dilempar dengan fungsi POST -->
            <input type="password" name="password" class="form-control" id="pwd" placeholder="Password" required <?php echo ($lockout_remaining > 0) ? 'disabled' : ''; ?>>
          </div>

          <?php if ($lockout_remaining > 0): ?>
            <div class="alert alert-danger" style="margin-top: 10px; font-size: 13px; text-align: left; background-color: #f2dede; color: #a94442; border-color: #ebccd1; padding: 10px; border-radius: 4px;">
              <strong>Akses Diblokir Sementara!</strong> IP Anda melakukan terlalu banyak percobaan gagal. Silakan coba lagi dalam <?php echo $lockout_remaining; ?> menit.
            </div>
          <?php endif; ?>

          <?php if ($show_captcha && $lockout_remaining == 0): 
            $equation = generate_captcha();
          ?>
            <div class="form-group" style="text-align: left; margin-bottom: 15px;">
              <label for="captcha" style="font-weight: bold; color: #555; display: block; margin-bottom: 5px;">Selesaikan Matematika: <?php echo $equation; ?> = ?</label>
              <input type="text" name="captcha" class="form-control" id="captcha" placeholder="Jawaban Anda" required autocomplete="off">
            </div>
          <?php endif; ?>

          <div>
            <button type="submit" name="submit" value="Login" class="btn btn-default" <?php echo ($lockout_remaining > 0) ? 'disabled style="cursor: not-allowed; opacity: 0.6;"' : ''; ?>>Login</button>
          </div>
          <div class="separator">
            <div class="clearfix"></div>
            <div>
              <img src="images/cp_logo.png" width="250" height="110">
              </br></br>
              <p>
                PT. CHITRA PARATAMA BALIKPAPAN - CP BALIKPAPAN Jln. AMD RT.46/No.69, Kariangau Kel.Graha Indah,Balikpapan Utara-76126
                </br>Telp: 0542-7588101 Fax: 0542-7588100
                </br> Ver 2.2.9
              </p>
            </div>
          </div>
        </form>
        <!-- end form login -->
      </section>
    </div>
  </div>
</body>

</html>