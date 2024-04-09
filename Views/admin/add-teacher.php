<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
      <div class="col-xxl">
        <div class="card mb-4">
          <div class="card-header">
            <a href="<?= base_url(ADMINPATH . 'employee-list') ?>" class="btn btn-success m-auto" style="float:right;position:relative;">View Employees</a>
          </div>
          <div class="card-body">
            <form enctype="multipart/form-data">
              <input type="hidden" id="employee_id" value="<?=$id?>">
              <input type="hidden" id="old_file" value="<?=$master_file?>">
              <input type="hidden" id="old_profile_image" value="<?=$profile_image?>">
            <div class="row mb-3">
              <div class="col-sm-3">
                <?=form_label('Employee Type','employee_type',['class'=>'col-form-label'])?>
                <select name="employee_type" id="employee_type" class="form-control select2" onchange="getemptype(this.value)" required>
                  <option value="">--Select Employee Type--</option>
                  <option value="Superior (CMSS)" <?=!empty($employee_type) && ($employee_type=="Superior (CMSS)")?"selected":""?>>Superior (CMSS)</option>
                  <option value="Menial (CMSM)" <?=!empty($employee_type) && ($employee_type=="Menial (CMSM)")?"selected":""?>>Menial (CMSM)</option>
                </select>
              </div>
              <div class="col-sm-3">
                <?=form_label('Name','teacher_name',['class'=>'col-form-label'])?>
                <input type="text" name="teacher_name" autocomplete="off" value="<?=$teacher_name?>" class="form-control  restrictedInput" required id="teacher_name" placeholder="Name" />
              </div>
              <div class="col-sm-3">
                <?=form_label('CMS Code','cmss_code',['class'=>'col-form-label'])?>
                <input type="text" name="cmss_code" maxlength="11" autocomplete="off" onkeyup="return checkDuplicateTeacher(this.value,'cms_code')" value="<?=$cmss_code?>" class="form-control"  required id="cmss_code" placeholder="CMS Code" />
              </div>
              <div class="col-sm-3">
                <?=form_label('Aadhaar Number','aadhaar_number',['class'=>'col-form-label'])?>
                <input type="text" name="aadhaar_number" autocomplete="off" onkeyup="return checkDuplicateTeacher(this.value,'aadhaar_number')"  minlength="12" maxlength="12" id="aadhaar_number" value="<?=$aadhaar_number?>"  class="form-control numbersWithZeroOnlyInput" placeholder="Aadhaar Number" aria-label="Aadhaar Number" aria-describedby="Aadhaar Number" />
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-sm-5">
                <?=form_label('Select School','school',['class'=>'col-form-label'])?>
                <select name="school" id="school" class="form-control select2" required>
                  <option value="">--Select School--</option>
                  <?php if(!empty($schools)){foreach($schools as $key=>$value){?>
                  <option value="<?=$value['school_code'].'/'.$value['school_name']?>" <?=!empty($school_code) && ($school_code==$value['school_code'])?"selected":""?>><?=$value['school_name'].'/'.$value['school_code']?></option>
                  <?php }} ?>
                </select>
              </div>
              <div class="col-sm-3">
                <?=form_label('Grade','grade',['class'=>'col-form-label'])?>
                <select name="grade" id="grade" class="form-control select2" required>
                  <option value="">--Select Grade--</option>
                  <?php if(!empty($grades)){foreach($grades as $key=>$value){?>
                  <option value="<?=$value['grade']?>" <?=!empty($grade) && ($grade==$value['grade'])?"selected":""?>><?=$value['grade']?></option>
                  <?php }} ?>
                </select>
              </div>
              <div class="col-sm-3">
                <?=form_label('Date Of Joining','date_of_joining',['class'=>'col-form-label'])?>
                <input type="date" name="date_of_joining" autocomplete="off"  id="date_of_joining" value="<?=$date_of_joining?>" required class="form-control"  />
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-sm-4">
                <?=form_label('Profile Image','profile_image',['class'=>'col-form-label'])?>
                <input type="file" name="profile_image"  class="form-control" id="profile_image" />
              </div>
              <?php if(!empty($profile_image)){?>
              <div class="col-sm-2">
                <img src="<?=base_url("uploads/".$profile_image)?>" height="100" width="100">
              </div>
              <?php } ?>
              <div class="col-sm-4">
                <?=form_label('Master File (jpg,png,jpeg,pdf)','master_file',['class'=>'col-form-label'])?>
                <input type="file" name="file"  class="form-control file" id="master_file" />
                <?php if(!empty($master_file)){?>
                <div class="d-flex justify-content-between flex-row w-100 align-items-end gap-2">
                <span class="mt-2"><b class="text-wrap">Uploaded File </b> : <?=$master_file?></span>
                <?php if(!empty($access) || ($user_type != "Admin")){?>
                <a href="<?=base_url('uploads/').$master_file?>" download onclick="return addDownloadActivity('<?=$master_file?>','<?=getTeacherNameCmsCode($id)?>')"><div class="bg-success rounded-2 ms-3 pb-1 pt-0" ><i class="bx bx-download mx-1 fs-5  text-white" ></i></div></a>
                <?php } ?>
                </div>
                <?php } ?>
              </div>
              <?php if(!empty($master_file)){?>
              <div class="col-sm-2 mt-2 p-4">
                <a href="javascript:void(0)" onclick="appendfile('<?=base_url('uploads/').$master_file?>')" data-bs-toggle="modal" data-bs-target="#basicModal">Click To View</a>
              </div>
              <?php } ?>
            </div>
            <div class="row mb-3 ">
              <?=form_label('Status','status',['class'=>'col-sm-2 col-form-label'])?>
              <div class="col-sm-6">
                <div class="row mt-2">
                  <div class="col-3">
                    <div class="custom-control custom-checkbox">
                      <input class="custom-control-input checkStatus" name="status" <?= ($status == 'Active') ? 'checked' : '' ?> type="radio" id="checkStatus1" value="Active">
                      <?=form_label('Active','checkStatus1',['class'=>'custom-control-label'])?>
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="custom-control custom-checkbox">
                      <input class="custom-control-input checkStatus" name="status" <?= ($status == 'Inactive') ? 'checked' : '' ?> type="radio" id="checkStatus2" value="Inactive">
                      <?=form_label('Inactive','checkStatus2',['class'=>'custom-control-label'])?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row justify-content-start">
              <?php if(!empty($access) || ($user_type != "Admin")){?>
              <div class="col-sm-10">
                <button type="button" id="submit" onclick="return validateTeacher()" class="btn btn-primary <?=!empty($id)?"submit":""?>"><?=empty($id)?"Submit":"Update"?><div class="spinner-border spinner-border-sm text-white" id="loader" role="status">
            <span class="visually-hidden">Loading...</span>
          </div></button>
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
<div class="modal fade mt-4" id="basicModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <iframe src="" id="appendfile" width="500" height="500" frameborder="0"></iframe>
      </div>
    </div>
  </div>
</div>

<script>
function appendfile(file) {
  //  alert(file);
   
    var iframe = $('#appendfile');
    iframe.on('load', function() {
        var doc = iframe.contents();
        var body = doc.find('body');
        body.html('<embed src="' + file + '#toolbar=0"  width="100%" height="100%" />');
    });
    iframe.attr('src', 'about:blank');
}
    function getemptype(val) {
      var prefilledText = '';
      if (val === "Superior (CMSS)") {
        prefilledText = 'CMSS-';
      } else {
        prefilledText = 'CMSM-';
      }
      $('#cmss_code').val(prefilledText);
      $('#cmss_code').on('input', function() {
        const enteredText = $(this).val();
        const regex = /^[0-9]+$/;
         if (enteredText.startsWith(prefilledText) && regex.test(enteredText.slice(prefilledText.length))) {
          $(this).prop('disabled', false);
        } else {
          $(this).val(prefilledText);
        }
      });
    }
    
    $('#loader').hide();
function validateTeacher() {
  var id = $('#employee_id').val();
  var old_file = $('#old_file').val();
  var old_profile_image = $('#old_profile_image').val();
  var employee_type = $('#employee_type').val();
  var teacher_name = $('#teacher_name').val();
  var cmss_code = $('#cmss_code').val();
  var aadhaar_number = $('#aadhaar_number').val();
  var school = $('#school').val();
  var grade = $('#grade').val();
  var date_of_joining = $('#date_of_joining').val();
  var profile_image = $('#profile_image').prop('files')[0];
  var master_file = $('#master_file').prop('files')[0];

  var status= $('.checkStatus:checked').val();
  
  if (employee_type.trim() === "") {
    toastr.error("Please Select Employee Type");
    return false;
  } else if (teacher_name.trim() === "") {
    toastr.error("Please Enter Employee Name");
    return false;
  } else if (cmss_code.trim() === "") {
    toastr.error("Please Enter CMS Code");
    return false;
  } else if (cmss_code.length > 11) {
    toastr.error("Please Enter Valid CMS Code");
    return false;
  } else if (aadhaar_number.trim() === "") {
    toastr.error("Please Enter Aadhaar Number");
    return false;
  } else if (aadhaar_number.length < 12) {
    toastr.error("Please Enter Full Aadhaar Number");
    return false;
  } else if (school.trim() === "") {
    toastr.error("Please Select School");
    return false;
  } else if (grade.trim() === "") {
    toastr.error("Please Select Grade");
    return false;
  } else if (date_of_joining.trim() === "") {
    toastr.error("Please Select Date Of Joining");
    return false;
  } else{
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
      var formData = new FormData();
      formData.append('profile_image', profile_image);
      formData.append('file', master_file);
      formData.append('id', id);
      formData.append('old_file', old_file);
      formData.append('old_profile_image', old_profile_image);
      formData.append('employee_type', employee_type);
      formData.append('teacher_name', teacher_name);
      formData.append('cmss_code', cmss_code);
      formData.append('aadhaar_number', aadhaar_number);
      formData.append('school', school);
      formData.append('grade', grade);
      formData.append('date_of_joining', date_of_joining);
      formData.append('status', status);
      $.ajax({
    url: '<?= base_url(ADMINPATH.'save-teacher') ?>',
    type: 'POST',
    data: formData,
    processData: false,
    contentType: false,
    cache: false,
    dataType: "json",
    beforeSend: function () {
        $('#submit').prop('disabled', true);
        $('#loader').show();
        Swal.fire({
    title: 'Please Wait your file is uploading',
   // text: 'You Want to Submit',
    icon: 'warning',
    showCancelButton: false,
    showConfirmButton: false,
    confirmButtonColor: '#3085d6',
    allowOutsideClick: false,
    cancelButtonColor: '#d33',
    //confirmButtonText: 'Yes, submit it!'
  });
    },
    success: function (response) {
        $('#submit').prop('disabled', false);
        

        if (response.status === false) {
            toastr.error(response.message);
            return false;
        } else if (response.status === true) {
            toastr.success(response.message);
            $('#loader').hide();
            setTimeout(function () {
                window.location.href = response.url;
            }, 500);
        }
    },
    error: function (jqXHR, textStatus, errorThrown) {
        $('#submit').prop('disabled', false);
        $('#loader').hide();
        toastr.error('An error occurred while processing the request.');
    }
});

    }
  });
  }
}



</script>