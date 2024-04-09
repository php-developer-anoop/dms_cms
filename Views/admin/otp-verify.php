<!DOCTYPE html>
<html lang="en" class="light-style layout-wide customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="<?=base_url('assets/')?>" data-template="vertical-menu-template-free">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title><?=$meta_title?></title>
    <meta name="description" content="" />
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />
    <?=link_tag('https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap')."\n";?>
    <?=link_tag(base_url('assets/vendor/fonts/boxicons.css'))."\n";?>
    <?=link_tag(base_url('assets/vendor/css/core.css'))."\n";?>
    <?=link_tag(base_url('assets/vendor/css/theme-default.css'))."\n";?>
    <?=link_tag(base_url('assets/css/demo.css'))."\n";?>
    <?=link_tag(base_url('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css'))."\n";?>
    <?=link_tag(base_url('assets/vendor/css/pages/page-auth.css'))."\n";?>
    <?=script_tag(base_url('assets/vendor/js/helpers.js'))?>
    <?=script_tag(base_url('assets/js/config.js'))?>
    <?=link_tag(base_url('assets/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css'))."\n";?>
    <?=link_tag(base_url('assets/toastr/toastr.min.css'))."\n";?>
    <style>
      .swal2-popup.swal2-toast .swal2-title {
      font-size: 15px;
      margin: 10px;
      color: #6c757d;
      }
    </style>
  </head>
  <body>
    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
          <div class="card">
            <div class="card-body">
            <h2 class="mb-2 text-center"><?=$meta_title?></h2>            
              <p class="mb-2">Please Enter Otp Sent To Your Registered Email</p>
              <form onsubmit="return false;">
                <div class="mb-3">
                  <label for="otp" class="form-label">Enter OTP</label>
                  <input type="text" class="form-control numbersWithZeroOnlyInput" maxlength="6" id="otp" name="otp" required placeholder="Enter OTP" autofocus />
                </div>
              
                <div class="mb-3 d-none">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember-me" />
                    <label class="form-check-label" for="remember-me"> Remember Me </label>
                  </div>
                </div>
                <div class="mb-3 d-flex justify-content-center">
                  <button class="btn btn-primary d-grid w-50" id="submit" onclick="return validateOtp()" type="button">Verify OTP</button>
                </div>
                 </form>
                <p class="text-center"> <a href="<?=base_url(ADMINPATH.'login')?>">Back To Login</a></p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?=script_tag(base_url('assets/vendor/libs/jquery/jquery.js'))."\n"?>
    <?=script_tag(base_url('assets/vendor/libs/popper/popper.js'))."\n"?>
    <?=script_tag(base_url('assets/vendor/js/bootstrap.js'))."\n"?>
    <?=script_tag(base_url('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js'))."\n"?>
    <?=script_tag(base_url('assets/vendor/js/menu.js'))."\n"?>
    <?=script_tag(base_url('assets/js/main.js'))."\n"?>
    <?=script_tag(base_url('assets/common.js'))."\n"?>
    <?=script_tag('https://buttons.github.io/buttons.js')."\n"?>
    <?=script_tag(base_url('assets/sweetalert2/sweetalert2.min.js'))?>
    <?=script_tag(base_url('assets/toastr/toastr.min.js'))?>

    <script>
    toastr.options.timeOut = 1000;
      var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 1000
      });
      function validateOtp() {
        var otp = $('#otp').val();
      
      if (otp.trim() === "") {
        Toast.fire({
          icon: 'error',
          title: 'Please Fill OTP'
        });
        return false;
      } else if (otp.length !== 6) {
        Toast.fire({
          icon: 'error',
          title: 'Invalid OTP'
        });
        return false;
      }else if(otp.trim() != ""){
          $('#submit').addClass('disabled',true);
            $.ajax({
      url: '<?= base_url(ADMINPATH.'verifyOtp') ?>',
      type: 'POST',
      data: {
        'otp': otp
      },
      cache: false,
      dataType:"json",
      success: function (response) {
       if(response.status==true){
        toastr.success(response.message);
         
        setTimeout(function () {
				window.location.href="<?=base_url(ADMINPATH.'dashboard')?>";
		}, 1500);
       }else if(response.status==false){
            $('#submit').removeClass('disabled',true);
        toastr.error(response.message);
        return false;
       }
      }
    });
        }
      }
      
      $(function () {
        <?php if (session()->getFlashdata('success')) { ?>
          toastr.success("<?php echo session()->getFlashdata('success'); ?>")
        <?php } ?>
        <?php if (session()->getFlashdata('failed')) { ?>
          toastr.error('<?php echo session()->getFlashdata('failed'); ?>')
        <?php } ?>
      });
    </script>
  </body>
</html>