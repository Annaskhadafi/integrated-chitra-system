  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chitra Paratama</title>
    <link rel="shortcut icon" href="images/cp_logo2.png"/>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="codebase/dhtmlx.css"></link>
    <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="../vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="../build/css/custom.min.css" rel="stylesheet">
    <!-- Datatables -->
    <link href="../vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="../vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
    <link href="../vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="../vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="../vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">
    
    <!-- ICS Redesign -->
    <?php
      $currentCssPage = basename($_SERVER['PHP_SELF']);
      $repairLayoutPages = array('halamanics.php', 'repair_halamanwo.php', 'repair_jobcard.php');
      if (in_array($currentCssPage, $repairLayoutPages, true)) {
    ?>
    <link rel="stylesheet" href="css/repair-layout.css">
    <?php } ?>
    <?php if ($currentCssPage === 'halamanics.php') { ?>
    <link rel="stylesheet" href="css/repair-dashboard.css">
    <?php } elseif ($currentCssPage === 'repair_halamanwo.php') { ?>
    <link rel="stylesheet" href="css/repair-work-order-list.css">
    <?php } elseif ($currentCssPage === 'repair_jobcard.php') { ?>
    <link rel="stylesheet" href="css/repair-jobcard-print.css">
    <?php } ?>

    <style>
    .modal-ku {
      width: 1140px;
      margin: auto;
    }
    .pre-scrollable-side {width: 1200px;}
    </style>
  </head>
