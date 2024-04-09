<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
      <div class="col-xxl">
        <div class="card mb-4">
          <div class="card-header">
            <a href="<?= base_url(ADMINPATH . 'years-list') ?>" class="btn btn-success m-auto" style="float:right;position:relative;">View Session Years</a>
          </div>
          <div class="card-body">
            <form>
              <input type="hidden" id="year_id" value="<?=$id?>">
              <div class="row mb-3">
                <?=form_label('Start Year','from',['class'=>'col-sm-2 col-form-label'])?>
                <div class="col-sm-3">
                  <input type="text" name="start_year"  autocomplete="off" value="<?=$start_year?>" maxlength="4" class="form-control numbersWithZeroOnlyInput" required id="start_year" placeholder="Start Year" />
                </div>
                <?=form_label('End Year','to',['class'=>'col-sm-2 col-form-label'])?>
                <div class="col-sm-3">
                  <input type="text" name="end_year" autocomplete="off" value="<?=$end_year?>" maxlength="4" class="form-control numbersWithZeroOnlyInput" required id="end_year" placeholder="End Year" />
                </div>
              </div>
              <div class="row mb-3 d-none">
                <?=form_label('Status','status',['class'=>'col-sm-2 col-form-label'])?>
                <div class="col-sm-6">
                  <div class="row mt-2">
                    <div class="col-3">
                      <div class="custom-control custom-checkbox">
                        <input class="custom-control-input" name="status" <?= ($status == 'Active') ? 'checked' : '' ?> type="radio" id="checkStatus1" value="Active">
                        <?=form_label('Active','checkStatus1',['class'=>'custom-control-label'])?>
                      </div>
                    </div>
                    <div class="col-3">
                      <div class="custom-control custom-checkbox">
                        <input class="custom-control-input" name="status" <?= ($status == 'Inactive') ? 'checked' : '' ?> type="radio" id="checkStatus2" value="Inactive">
                        <?=form_label('Inactive','checkStatus2',['class'=>'custom-control-label'])?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row justify-content-start">
                <?php if(!empty($access) || ($user_type != "Admin")){?>
                <div class="col-sm-10">
                  <button type="button" id="submit" onclick="return validateYear()" class="btn btn-primary <?=!empty($id)?"submit":""?>"><?=empty($id)?"Submit":"Update"?></button>
                </div>
                <?php } ?>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
 function validateYear() {
   const currentYear = new Date().getFullYear();
   const nextYear = currentYear + 1;
   var id = $('#year_id').val();
   var startYear = parseInt($('#start_year').val(), 10);
   var endYear = parseInt($('#end_year').val(), 10);
   if (isNaN(startYear)) {
     toastr.error("Please Enter a Valid Start Year");
     return false;
   } else if (isNaN(endYear)) {
     toastr.error("Please Enter a Valid End Year");
     return false;
   } else if (startYear < currentYear) {
     $('#start_year').val(currentYear);
     toastr.error("Start Year cannot be less than the current year");
     return false;
   } else if (endYear < nextYear) {
     $('#end_year').val(nextYear);
     toastr.error("End Year cannot be less than the next year");
     return false;
   } else if ((endYear - startYear) !== 1) {
     toastr.error("Year Gap Should Be 1");
     return false;
   } else {
     Swal.fire({
       title: 'Are you sure?',
       text: 'You Want to Submit',
       icon: 'warning',
       showCancelButton: true,
       confirmButtonColor: '#3085d6',
       cancelButtonColor: '#d33',
       confirmButtonText: 'Yes, submit it!'
     }).then((result) => {
       if (result.isConfirmed) {
         $.ajax({
           url: '<?= base_url(ADMINPATH.'save-year') ?>',
           type: 'POST',
           data: {
             'id': id,
             'startYear': startYear,
             'endYear': endYear
           },
           cache: false,
           dataType: "json",
           success: function(response) {
             if (response.status === false) {
               toastr.error(response.message);
             } else if (response.status === true) {
               $('#submit').addClass('disabled');
               toastr.success(response.message);
               setTimeout(function() {
                 window.location.href = response.url;
               }, 500);
             }
           },
           error: function() {
             console.log('Error occurred during AJAX request');
           }
         });
       }
     });
   }
 }


</script>