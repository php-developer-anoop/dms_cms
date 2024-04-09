<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
      <div class="col-xxl">
        <div class="card mb-4">
          <div class="card-header">
            <a href="<?= base_url(ADMINPATH . 'records-list') ?>" class="btn btn-success m-auto" style="float:right;position:relative;">View Records</a>
          </div>
          <div class="card-body">
            <form enctype="multipart/form-data">
              <input type="hidden" id="record_id" value="<?=$id?>">
              <input type="hidden" id="old_file" value="<?=$file?>">
           
            <div class="row mb-3">
              <div class="col-sm-3">
                <?=form_label('Session Year','years',['class'=>'col-form-label'])?>
                <select name="financial_year" id="financial_year" class="form-control select2" required>
                  <option value="">--Select Session Year--</option>
                  <?php if(!empty($years)){foreach($years as $key=>$value){?>
                  <option value="<?=$value['financial_year']?>" <?=!empty($financial_year) && ($financial_year==$value['financial_year'])?"selected":""?>><?=$value['financial_year']?></option>
                  <?php }} ?>
                </select>
              </div>
              <div class="col-sm-4">
                <?=form_label('Select CMS Code','teacher',['class'=>'col-form-label'])?>
                <input type="text" name="teacher_name" autocomplete="off" id="teach_name"  value="<?=$teacher_name?>" placeholder="Type CMS Code" class="form-control" required onkeyup="getTeacherName(this.value)">
                <input type="hidden" name="teacher_id" id="teach_id" value="<?=$teacher_id?>">
                <ul class="autocomplete-list" id="suggestion-list" onclick="return selectTeacherName()"></ul>
              </div>
              <div class="col-sm-2">
                <?=form_label('Record Type','record_type',['class'=>'col-form-label'])?>
                <select name="record_type" id="record_type" class="form-control select2" required>
                  <option value="">--Select Type--</option>
                  <option value="Job" <?=!empty($record_type) && ($record_type=="Job")?"selected":""?>>Job Record</option>
                  <option value="Service" <?=!empty($record_type) && ($record_type=="Service")?"selected":""?>>Service Record</option>
                  <option value="Amended" <?=!empty($record_type) && ($record_type=="Amended")?"selected":""?>>Amended Record</option>
                </select>
              </div>
              <div class="col-sm-3">
                <?=form_label('File Title','filename',['class'=>'col-form-label'])?>
                <input type="text" name="filename" autocomplete="off" id="filename" value="<?=$filename?>"  class="form-control" placeholder="File Title" aria-label="File Title" aria-describedby="File Title" />
              </div>
            </div>
            <div class="row mb-3">
              <?=form_label('File (jpg,png,jpeg,pdf)','file',['class'=>'col-sm-2 col-form-label'])?>
              <div class="col-sm-4">
                <input type="file" name="file"  id="file" <?=empty($file)?"required":""?> class="form-control file" />
              </div>
              <?php if(!empty($file)){?>
              <div class="col-sm-2">
                <?php
                  $fileUrl = base_url('uploads/' . $file);
                  ?>
                <a href="javascript:void(0)" onclick="appendfile('<?php echo $fileUrl; ?>')" data-bs-toggle="modal" data-bs-target="#basicModal">Click To View</a>
              </div>
              <?php } ?>
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
                <button type="button" id="submit" onclick="return checkDuplicateRecord()" class="btn btn-primary <?=!empty($id)?"submit":""?>"><?=empty($id)?"Submit":"Update"?></button>
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
      var iframe = $('#appendfile');
      iframe.on('load', function() {
        var doc = iframe.contents();
        var body = doc.find('body');
        body.html('<embed src="' + file + '#toolbar=0"  width="100%" height="100%" />');
      });
      iframe.attr('src', 'about:blank');
    }
    
 function checkDuplicateRecord() {
  var id = $('#record_id').val();
  var old_file = $('#old_file').val();
  var financial_year = $('#financial_year').val();
  var teach_name = $('#teach_name').val();
  var teach_id = $('#teach_id').val();
  var record_type = $('#record_type').val();
  var filename = $('#filename').val();
  var fileInput = $('#file')[0].files[0];
  
  // Check if required fields are empty
  if (financial_year.trim() === "") {
    toastr.error("Please Select Year");
    return false;
  } else if (teach_name.trim() === "") {
    toastr.error("Please Enter CMS Code/Employee Name");
    return false;
  } else if (record_type.trim() === "") {
    toastr.error("Please Select Record Type");
    return false;
  } else if (filename.trim() === "") {
    toastr.error("Please Enter File Title");
    return false;
  }else{
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
      formData.append('file', fileInput);
      formData.append('id', id);
      formData.append('old_file', old_file);
      formData.append('financial_year', financial_year);
      formData.append('teacher_id', teach_id);
      formData.append('teacher_name', teach_name);
      formData.append('record_type', record_type);
      formData.append('filename', filename);

      $.ajax({
        url: '<?= base_url(ADMINPATH.'save-record') ?>',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        cache: false,
        dataType:"json",
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
        error: function(xhr, status, error) {
          console.error(xhr, status, error);
          Swal.fire("Error occurred. Please try again.");
        }
      });
    }
  });
  }
}

</script>