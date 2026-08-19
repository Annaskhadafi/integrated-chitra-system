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
$section_head = '';
ob_start();
include "sectionhead.php"; // call sectionhead.php as library
$section_head = ob_get_clean();
echo str_replace('</head>', '  <link href="css/login-redesign.css" rel="stylesheet">' . PHP_EOL . '  </head>', $section_head);
?>

<body class="ics-login-page">
  <main class="ics-login-shell" aria-label="Integrated Chitra System login">
    <section class="ics-login-card">
      <aside class="ics-login-brand" aria-label="Chitra Paratama information">
        <div class="ics-login-brand-bg"></div>
        <div class="ics-login-brand-shade"></div>
        <div class="ics-login-brand-fade"></div>

        <div class="ics-login-brand-content">
          <div class="ics-login-logo-box">
            <img src="images/cp-logo-no-bg.png" alt="Chitra Paratama" class="ics-login-logo">
          </div>

          <div class="ics-login-intro">
            <h1>Integrated Chitra<br>Sistem &mdash; (ICS)</h1>
            <p>Portal terpadu untuk manajemen work order, jadwal perawatan ban, dan laporan operasional perusahaan.</p>
          </div>

          <ul class="ics-login-feature-list" aria-label="ICS features">
            <li>Work Order Management</li>
            <li>Tire Repair Scheduling</li>
            <li>Operational Reports</li>
          </ul>

          <div class="ics-login-company">
            <p>
              <strong>PT. Chitra Paratama</strong><br>
              Jln. AMD RT 40/Rw.04, Karanganyar Grana Indah<br>
              Balikpapan Utara 76126<br>
              Telp: 0542-758193 | Fax: 0542-758193
            </p>
            <span>Ver 2.2.9</span>
          </div>
        </div>
      </aside>

      <section class="ics-login-form-panel" aria-label="Sign in form">
        <div class="ics-login-form-wrap">
          <div class="ics-login-accent" aria-hidden="true"></div>
          <h2>Sign in</h2>
          <p class="ics-login-subtitle">Masuk dengan akun Chitra Paratama Anda</p>

          <form role="form" action="proses_login.php" method="post" class="ics-login-form">
            <div class="ics-login-field">
              <label for="username">USERNAME</label>
              <div class="ics-login-input-wrap">
                <i class="fa fa-user" aria-hidden="true"></i>
                <input type="text" name="username" class="form-control" id="username" placeholder="Masukkan username" required autocomplete="username" <?php echo ($lockout_remaining > 0) ? 'disabled' : ''; ?>>
              </div>
            </div>

            <div class="ics-login-field">
              <label for="pwd">PASSWORD</label>
              <div class="ics-login-input-wrap">
                <i class="fa fa-lock" aria-hidden="true"></i>
                <input type="password" name="password" class="form-control" id="pwd" placeholder="Masukkan password" required autocomplete="current-password" <?php echo ($lockout_remaining > 0) ? 'disabled' : ''; ?>>
                <button type="button" class="ics-login-password-toggle" aria-label="Tampilkan password" aria-pressed="false" <?php echo ($lockout_remaining > 0) ? 'disabled' : ''; ?>>
                  <i class="fa fa-eye" aria-hidden="true"></i>
                </button>
              </div>
            </div>

            <?php if ($lockout_remaining > 0): ?>
              <div class="ics-login-alert ics-login-alert-danger" role="alert">
                <strong>Akses Diblokir Sementara!</strong>
                IP Anda melakukan terlalu banyak percobaan gagal. Silakan coba lagi dalam <?php echo $lockout_remaining; ?> menit.
              </div>
            <?php endif; ?>

            <?php if ($show_captcha && $lockout_remaining == 0):
              $equation = generate_captcha();
            ?>
              <div class="ics-login-field">
                <label for="captcha">SELESAIKAN MATEMATIKA: <?php echo $equation; ?> = ?</label>
                <div class="ics-login-input-wrap">
                  <i class="fa fa-shield" aria-hidden="true"></i>
                  <input type="text" name="captcha" class="form-control" id="captcha" placeholder="Jawaban Anda" required autocomplete="off" inputmode="numeric">
                </div>
              </div>
            <?php endif; ?>

            <button type="submit" name="submit" value="Login" class="ics-login-submit" <?php echo ($lockout_remaining > 0) ? 'disabled' : ''; ?>>LOGIN</button>
          </form>

          <p class="ics-login-copyright">&copy; <?php echo date('Y'); ?> PT. Chitra Paratama</p>
        </div>
      </section>
    </section>
  </main>

  <script>
    (function() {
      var toggle = document.querySelector('.ics-login-password-toggle');
      var password = document.getElementById('pwd');

      if (!toggle || !password) {
        return;
      }

      toggle.addEventListener('click', function() {
        var isVisible = password.getAttribute('type') === 'text';
        password.setAttribute('type', isVisible ? 'password' : 'text');
        toggle.setAttribute('aria-pressed', isVisible ? 'false' : 'true');
        toggle.setAttribute('aria-label', isVisible ? 'Tampilkan password' : 'Sembunyikan password');
        toggle.querySelector('i').className = isVisible ? 'fa fa-eye' : 'fa fa-eye-slash';
      });
    })();
  </script>
</body>

</html>