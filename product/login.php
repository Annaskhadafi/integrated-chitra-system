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
            <input type="username" name="username" class="form-control" id="username" placeholder="Username" required>
          </div>
          <div class="form-group">
            <!-- form password dilempar dengan fungsi POST -->
            <input type="password" name="password" class="form-control" id="pwd" placeholder="Password" required>
          </div>
          <div>
            <button type="submit" name="submit" value="Login" class="btn btn-default">Login</button>
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