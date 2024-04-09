<div class="container-xl w-50">
  <div class="authentication-wrapper">
    <div class="authentication-inner py-4">
      <div class="card">
        <div class="card-body">
          <!-- /Logo -->
          <h4 class="mb-2">Change Password</h4>
          <?=form_open(ADMINPATH . 'update-password'); ?>
          <?=form_hidden('id',$id)?>
          <div class="mb-3">
            <label for="password" class="form-label">New Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" autofocus="">
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Confirm New Password</label>
            <input type="password" class="form-control" id="cpassword" name="cpassword" placeholder="Confirm New Password" autofocus="">
          </div>
          <button class="btn btn-primary d-grid w-100" type="submit" onclick="return validatepassword()">Submit</button>
          <?=form_close()?>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
    function validatepassword() {
     var password = $('#password').val();
     var cpassword = $('#cpassword').val();
     if (password.trim() == "") {
       toastr.error("Please Enter Password");
       return false;
     } else if (cpassword.trim() == "") {
       toastr.error("Please Enter Confirm Password");
       return false;
     } else if (password.trim() !== cpassword.trim()) {
       toastr.error("Password Mismatch");
       return false;
     }
     return true;
   }
</script>