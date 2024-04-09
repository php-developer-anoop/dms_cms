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
            <h2 class="mb-2 text-center d-none">Admin Panel</h2>            
              <h4 class="mb-2">Please sign-in to your account</h4>
              <?= form_open(ADMINPATH . 'authenticate', ['id' => 'formAuthentication', 'class' => 'mb-3']); ?>
                <div class="mb-3">
                  <label for="email" class="form-label">Official Email Id</label>
                  <input type="email" class="form-control" id="email" name="email" required placeholder="Official Email Id" autofocus />
                </div>
                <div class="mb-3 form-password-toggle">
                  <div class="d-flex justify-content-between">
                    <label class="form-label" for="password" required>Password</label>
                  </div>
                  <div class="input-group input-group-merge">
                    <input type="password" id="password" class="form-control" name="password" placeholder="Password" aria-describedby="password" />
                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                  </div>
                </div>
                <div class="mb-3 d-none">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember-me" />
                    <label class="form-check-label" for="remember-me"> Remember Me </label>
                  </div>
                </div>
                <div class="mb-3">
                  <button class="btn btn-primary d-grid w-100" id="submit" onclick="return validate()" type="submit">Sign in</button>
                </div>
                <?= form_close(); ?>
                <div class="text-center">
                <a href="<?=base_url(ADMINPATH.'forgot-password')?>" class="d-flex align-items-center justify-content-center">
                
                Forgot Password
                </a>
              </div>
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
    <?=script_tag('https://buttons.github.io/buttons.js')."\n"?>
    <?=script_tag(base_url('assets/sweetalert2/sweetalert2.min.js'))?>
    <?=script_tag(base_url('assets/toastr/toastr.min.js'))?>

    <script>
      var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 500
      });
      toastr.options.timeOut = 1000;
      function validate() {
        var email = $('#email').val();
        var password = $('#password').val();
      
        if (email == "") {
          Toast.fire({
            icon: 'error',
            title: 'Please Fill Email'
          })
          return false;
        } else if (password == "") {
          Toast.fire({
            icon: 'error',
            title: 'Please Fill Password'
          })
          return false;
        }
        $('#submit').addClass('disabled',true);
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