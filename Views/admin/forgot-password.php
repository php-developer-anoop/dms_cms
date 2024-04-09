<!DOCTYPE html>
<html
  lang="en"
  class="light-style layout-wide customizer-hide"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="<?=base_url()?>/assets/"
  data-template="vertical-menu-template-free">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Forgot Password</title>
    <meta name="description" content="" />
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?=base_url()?>/assets/img/favicon/favicon.ico" />
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet" />
    <link rel="stylesheet" href="<?=base_url()?>/assets/vendor/fonts/boxicons.css" />
    <!-- Core CSS -->
    <link rel="stylesheet" href="<?=base_url()?>/assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="<?=base_url()?>/assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="<?=base_url()?>/assets/css/demo.css" />
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="<?=base_url()?>/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="<?=base_url()?>/assets/vendor/css/pages/page-auth.css" />
    <?=link_tag(base_url('assets/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css'))."\n";?>
    <?=link_tag(base_url('assets/toastr/toastr.min.css'))."\n";?>
    <!-- Helpers -->
    <script src="<?=base_url()?>/assets/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="../assets/js/config.js"></script>
    <style>
      .swal2-popup.swal2-toast .swal2-title {
      font-size: 15px;
      margin: 10px;
      color: #6c757d;
      }
    </style>
  </head>
  <body>
    <!-- Content -->
    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner py-4">
          <!-- Forgot Password -->
          <div class="card">
            <div class="card-body">
                  <h2 class="text-center">Admin Panel</h2> 
              <!-- /Logo -->
              <h4 class="mb-2">Forgot Password? 🔒</h4>
              <p class="mb-4">Enter your email and we'll send you instructions to reset your password</p>
              <form onsubmit="return false;" class="mb-3">
                <div class="mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input
                    type="text"
                    class="form-control"
                    id="email"
                    name="email"
                    autocomplete="off"
                    placeholder="Enter your email"
                    autofocus />
                </div>
                <button class="btn btn-primary submit d-grid w-100" onclick="return validate()">Submit<div class="spinner-border text-success" role="status">
            <span class="visually-hidden">Loading...</span>
          </div></button>
                
              </form>
              <div class="text-center">
                <a href="<?=base_url(ADMINPATH.'login')?>" class="d-flex align-items-center justify-content-center">
                <i class="bx bx-chevron-left scaleX-n1-rtl bx-sm"></i>
                Back to login
                </a>
              </div>
            </div>
          </div>
       
        </div>
      </div>
    </div>

    <script src="<?=base_url()?>/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="<?=base_url()?>/assets/vendor/libs/popper/popper.js"></script>
    <script src="<?=base_url()?>/assets/vendor/js/bootstrap.js"></script>
    <script src="<?=base_url()?>/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="<?=base_url()?>/assets/vendor/js/menu.js"></script>
    <?=script_tag(base_url('assets/sweetalert2/sweetalert2.min.js'))?>
    <?=script_tag(base_url('assets/toastr/toastr.min.js'))?>
    <!-- endbuild -->
    <!-- Vendors JS -->
    <!-- Main JS -->
    <script src="<?=base_url()?>/assets/js/main.js"></script>
    <!-- Page JS -->
    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    
    <script>
        var Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 1000
});

$('.spinner-border').hide();
function validate() {
  var email = $('#email').val();
  if (email == "") {
    Toast.fire({
      icon: 'warning',
      title: 'Please Enter Email'
    })
    return false;
  } else if (email != "") {
      $('.submit').addClass('disabled',true);
      $('.spinner-border').show();
    $.ajax({
      url: '<?=base_url(ADMINPATH.'sendNewPassword')?>',
      type: "POST",
      data: {
        'email': email
      },
      cache: false,
      success: function(response) {
        if (response == "success") {
          toastr.success("New Password Has Been Sent On Entered Email Id");
          setTimeout(function() {
          window.location.href = "<?=base_url(ADMINPATH.'login')?>"
          }, 1000);
        } else {
          toastr.error(response);
          setTimeout(function() {
          $('.submit').removeClass('disabled',true);
      $('.spinner-border').hide();
          }, 500);
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