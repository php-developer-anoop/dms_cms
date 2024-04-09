<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <title>CMS - Welcome to Data Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link href="<?=base_url('assets/vendor/fonts/boxicons.css')?>" rel="stylesheet" type="text/css">
    <link rel="icon" type="image/x-icon" href="<?=$favicon?>" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <?=link_tag(base_url('assets/toastr/toastr.min.css'))."\n";?>
    <?=script_tag(base_url('assets/vendor/libs/jquery/jquery.js'))."\n"?>
    <style>
      .wellogo img {
     max-width: 150px;
}
 .welcmpage {
     padding: 60px;
     
}
 .wlcmrow h2 {
     color: #2D3C71;
     font-family: Inter;
     font-size: 53.878px;
     font-weight: 700;
     line-height: normal;
     margin-top:20px;
}
 .wlcmrow h2 span{
     color:#579AD4;
}
 .btmbtn_wrap h6 {
     color: #2B84C6;
     font-family: Inter;
     font-size: 35px;
     font-style: normal;
     font-weight: 600;
     line-height: normal;
     margin-bottom: 25px;
}
 .btmbtn_wrap {
     padding-top: 125px;
}
 a.btmbtn {
     display: flex;
     width: 200px;
     padding: 10px;
     justify-content: center;
     align-items: center;
     gap: 10px;
     text-decoration: none;
     border-radius: 15px;
     background: #589AD3;
     color: #fff;
     margin-bottom: 15px;
     font-size: 25px;
     font-style: normal;
     font-weight: 400;
     line-height: normal;
}
 img {
     max-width: 100%;
}
 @media(max-width:1320px){
     .welcmpage {
         padding: 50px;
    }
     .btmbtn_wrap {
         padding-top:90px;
    }
     .wlcmrow h2{
        font-size:44px;
    }
}
 @media(max-width:640px){
     .welcmpage {
         padding: 20px;
    }
     .wlcmrow h2 {
         font-size: 30px;
    }
     .btmbtn_wrap {
         padding-top: 40px;
    }
     .btmbtn_wrap h6 {
         font-size: 22px;
    }
     a.btmbtn {
         width: 140px;
         font-size: 20px;
    }
}

    </style>
  </head>
  <body>
      
    <div class="container-fluid">
      <div class="welcmpage">
        <div class="wellogo">
          <img src="https://dms.nshops.in/uploads/1701935117_b8dae82a91c8bc036767.png">
        </div>
        <div class="row wlcmrow">
          <div class="col-sm-6">
            <h2>DATA <br/> <span>MANAGEMENT SYSTEM</span> </h2>
            <div class="btmbtn_wrap">
              <h6>Sign in</h6>
              <a href="<?=base_url(ADMINPATH.'login')?>" class="btmbtn"><i class="bx bx-chevron-right"></i> Admin</a>
              <a href="<?=base_url(ADMINPATH.'login')?>" class="btmbtn"><i class="bx bx-chevron-right"></i> User</a>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="wlcmimg">
              <img src="../../uploads/cmswelcm.png">
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
    
    <?=script_tag(base_url('assets/toastr/toastr.min.js'))?>
    <script>
    toastr.options.timeOut = 1000;
    $(function() {
    <?php if (session()->getFlashdata('success')) {?>

    setTimeout(function() {
      toastr.success('<?php echo session()->getFlashdata('success'); ?>')
    }, 500); 
    <?php } ?>
  <?php if (session()->getFlashdata('failed')) { ?>
    setTimeout(function() {
      toastr.error('<?php echo session()->getFlashdata('failed'); ?>')
    }, 500); 
    <?php } ?>
    });
    </script>
</html>

