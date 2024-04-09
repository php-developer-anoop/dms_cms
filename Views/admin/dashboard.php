<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">
    <?php   //if($user_type!="Admin" ){
      ?>
    <div class="row dashtopfilters">
      <div class="col-lg-4">
        <div class="selectschl">
          <label>Filter School</label>
          <select class="form-control select2" aria-label="Default select example" id="school_id" onchange="getTeachers(this.value)">
            <?php if(!empty($schools)){foreach($schools as $key=>$value){?>
            <option value="<?=$value['school_code']?>" <?=!empty($school_code) && ($school_code==$value['school_code'])?"selected":""?>><?=$value['school_name'].' / '.$value['school_code']?></option>
            <?php }} ?>
          </select>
        </div>
      </div>
      <div class="form-group selectschl col-lg-4">
        <?=form_label('Select Employee','employee',['class'=>'col-form-label'])?>
        <select name="employee" id="employee" class="form-control select2" required>
          <option value="">--Select Employee--</option>
        </select>
      </div>
      <div class="form-group col-lg-4 mt-4">
        <button type="submit" class="btn btn-primary" onclick="return getRecordsTeachers()">Submit</button>
      </div>
    </div>
    <div class="col-sm-12">
      <div class="card">
        <div class="card-body">
          <div class="emp-prfwrap">
            <div class="empprf_side">
              <h5>Employee Name</h5>
              <div class="tabwrpr">
                <div class="tabhdr">
                  <a>Employee</a>
                  <a>CMSS Code</a>
                </div>
                <div class="tab_content">
                  <?php if(!empty($teachers)){?>
                  <ul>
                    <?php foreach($teachers as $tckey=>$tcvalue){ ?>
                    <input type="hidden" id="teacher_id" value="<?=$tcvalue['id']?>">
                    <li><a href="javascript:void(0)" onclick="getDetail(<?=$tcvalue['id']?>)"><?=$tcvalue['teacher_name'].' | '.$tcvalue['cmss_code']?></a></li>
                    <?php } ?>
                  </ul>
                  <?php } ?>
                </div>
              </div>
            </div>
            <div class="prfldata">
              <h5>Profile Data</h5>
              <div class="prflbox">
              </div>
              <div class="mastrfiletabl">
                <table>
                  <tr>
                    <th>Master file name</th>
                    <th>Last update</th>
                    <th>Upload Date</th>
                  </tr>
                  <tr>
                    <td>
                      <div class="fileicns">
                        <p class="mfilename"></p>
                        <div class="linkicon_wrp d-flex flex-row gap-3 align-items-start">
                          <?php /*if(!empty($access) || ($user_type != "Admin")){
                            <a href="javascript:void(0)" class="linkicons d-none"><i class="bx bx-upload" data-bs-toggle="modal" data-bs-target="#upModal"></i></a>
                            <a href="javascript:void(0)" class="linkicons d-none" data-bs-toggle="modal" data-bs-target="#downloadModal"><i class="bx bx-download"></i></a>
                             }*/ ?>
                          <a href="javascript:void(0)" class="myfile" onclick="appendfile()" data-bs-toggle="modal" data-bs-target="#basicModal"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
  <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
  <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
</svg></a>
             <?php if(!empty($access) || ($user_type != "Admin")){?>
              <a href="javascript:void(0)" class="downloadmyfile"><span class="bg-success rounded-2 ms-3 pb-1 pt-0"><i class="bx bx-download mx-1 fs-5  text-white"></i></span></a>
                       <?php } ?>
                        </div>
                      </div>
                    </td>
                    <td class="mupd"></td>
                    <td class="mupl"></td>
                  </tr>
                </table>
              </div>
              <div class="col-sm-4 mt-3">
                <div class="financyear">
                  <label>Session Year</label>
                  <select class="form-select select2" id="year" onchange="callgetrecords()" aria-label="Default select example">
                    <?php if(!empty($years)){foreach($years as $ykey=>$yvalue){?>
                    <option value="<?=$yvalue['financial_year']?>"><?=$yvalue['financial_year']?></option>
                    <?php }} ?>
                  </select>
                </div>
              </div>
              <div class="cstmtab_wrapper mt-4">
                <div class="tablikehdr">
                  <a href="javascript:void(0)" onclick="getRecords('Job')" id="record_type_Job" value="Job" data-tab-value="Job" class="tablike current active">Job Record</a>
                  <a href="javascript:void(0)" onclick="getRecords('Service')" id="record_type_Service"   class="tablike current" data-tab-value="Service" value="Service">Service Record</a>
                  <a href="javascript:void(0)" onclick="getRecords('Amended')" id="record_type_Amended" class="tablike current" data-tab-value="Amended" value="Amended">Amended Record</a>
                </div>
                <div class="tabcontent">
                  <div class="tabaddbtn">
                    <?php if(!empty($access) || ($user_type != "Admin")){?>
                    <button class="sitebtn"  data-bs-toggle="modal" onclick="appenvalue()" data-bs-target="#uploadModal">Add</button>
                    <?php } ?>
                  </div>
                  <table>
                    <thead>
                      <th>File name</th>
                      <th>Upload date</th>
                      <th>File size</th>
                      <?php if(!empty($access) || ($user_type != "Admin")){ ?>
                      <th>Edit</th>
                      <?php } ?>
                      <th>View</th>
                    </thead>
                    <tbody id="append_records">
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php // } ?>
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
<div class="modal fade" id="recordModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <iframe src="" id="appendrecordfile" width="500" height="500" frameborder="0"></iframe>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="upModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h5 class="modal-title" id="exampleModalLabel">Upload PDF</h5>
        <div class="d-flexbtwn modldata">
          <?php  $currentURL = current_url(); //for simple URL
            $params = !empty($_SERVER['QUERY_STRING'])?'?'.$_SERVER['QUERY_STRING']:""; //for parameters
            $fullURL = $currentURL . $params; //full URL with parameter?>
          <?=form_open_multipart(ADMINPATH . 'save-master-file'); ?>
          <input type="hidden" name="id" id="id" value="">
          <input type="hidden" name="old_file" id="old_file" value="">
          <?=form_hidden('url',$fullURL)?>
          <div class="input-group mb-3">
            <input type="file" name="file" class="form-control" autocomplete="off" id="#" required>
            <button type="submit" class="input-group-text">Upload</button>
          </div>
          <?=form_close()?>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="downloadModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      <div class="modal-body">
        <h5 class="modal-title" id="exampleModalLabel">Download</h5>
        <div class="d-flexbtwn modldata">
          <div class="mdltxt">
            <h6 class="mfilename"></h6>
            <p>Last update: <span class="mupd"></span></p>
            <p>Uploaded on: <span class="mupl"></span></p>
          </div>
          <div class="mdlbtn">
            <a href="javascript:void(0)" class="btn btn-primary" id="myfile" download>Download</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      <div class="modal-body">
        <h5 class="modal-title" id="exampleModalLabel">Upload File</h5>
        <div class="d-flexbtwn modldata">
          <?=form_open_multipart(ADMINPATH . 'save_record'); ?>
          <input type="hidden" id="" name="url" value="<?=$fullURL?>">
          <input type="hidden" id="fin_year" name="fin_year" value="">
          <input type="hidden" id="teach_name" name="teach_name" value="">
          <input type="hidden" id="cmsscode" name="cmsscode" value="">
          <input type="hidden" id="jobtype" name="jobtype" value="">
          <div class="input-group mb-3">
            <input type="text" class="form-control" autocomplete="off" id="#" name="filetitle" placeholder="File Title" required>
          </div>
          <div class="input-group mb-3">
            <input type="file" class="form-control"  autocomplete="off" id="#" name="file" required>
          </div>
          <button class="input-group-text" type="submit">Upload</button>
          <?=form_close()?>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
 function appendfile(){
     var file=$('.myfile').attr('href');
    var iframe = $('#appendfile');
     iframe.on('load', function() {
         var doc = iframe.contents();
         var body = doc.find('body');
         body.html('<embed src="' + file + '#toolbar=0"  width="100%" height="100%" />');
     });
     iframe.attr('src', 'about:blank');
  }
  
  function appendrecordfile(file){
     
    var iframe = $('#appendrecordfile');
  
     iframe.on('load', function() {
         var doc = iframe.contents();
         var body = doc.find('body');
  
         body.html('<embed src="' + file + '#toolbar=0"   width="100%" height="100%" />');
     });

     iframe.attr('src', 'about:blank');
  }
  
  function getTeachers(school_code){
     if(school_code.trim()==""){
         window.location.href="<?=base_url(ADMINPATH.'dashboard')?>";
     }else{
         window.location.href="<?=base_url(ADMINPATH.'dashboard')?>?school_code="+school_code;
     }
  }

$(document).ready(function () {
    $('.downloadmyfile').attr('href','');
    $('.downloadmyfile').prop('download',false);
    $('.downloadmyfile').attr('onclick', '');
    var teacher_id = $('#teacher_id').val();
        $('#id').val(teacher_id);
    $.ajax({
        url: '<?= base_url(ADMINPATH.'getTeacherDetail') ?>',
        method: 'POST',
        dataType: 'json',
        data: { teacher_id: teacher_id },
        success: function (response) {
            $('.prflbox').html(response.content);
            
            if (response.master_file_name !== "") {
                $('#old_file').val(response.master_file_name);
                $('.mfilename').html(response.master_file_name);
            }

            if (response.master_file !== "") {onclick=""
                $('.myfile').attr('href', response.master_file);
                $('.downloadmyfile').attr('href', response.master_file);
                $('.downloadmyfile').attr('onclick', 'return addDownloadActivity("'+response.master_file_name+'","'+response.name_cms+'")');
                $('.downloadmyfile').prop('download',response.master_file_name);
            }

            if (response.mfupload_date !== null && response.mfupload_date !== "") {
                $('.mupl').html(response.mfupload_date);
            } else {
                $('.mupl').html('N/A');
            }
            
            if (response.mfupd_date !== null && response.mfupd_date !== "") {
                $('.mupd').html(response.mfupd_date);
            } else {
                $('.mupd').html('N/A');
            }
        },
        error: function (xhr, status, error) {
            // Handle error situations here if needed
            console.error(xhr, status, error);
        }
    });
});

function getRecordsTeachers(){
    var teacher_id=$('#employee').val();
    
    getDetail(teacher_id);
}

function getDetail(teacher_id) {
    $('.mfilename').html('');
    $('.downloadmyfile').attr('href','');
    $('.downloadmyfile').attr('onclick', '');
    $('.downloadmyfile').prop('download',false);
    $('#myfile').attr('href', '');
    
    $('.mupl').html('');
    $('.mupd').html('');
    $('#id').val(teacher_id);
    var record_type = $('.tablike').attr('value');
   //alert(record_type);
    getRecords(record_type,teacher_id);
    $.ajax({
        url: '<?= base_url(ADMINPATH.'getTeacherDetail') ?>',
        method: 'POST',
        dataType: 'json',
        data: { teacher_id: teacher_id },
        success: function(response) {
            $('.prflbox').html(response.content);

            if (response.master_file_name !== "") {
                $('#old_file').val(response.master_file_name);
                $('.mfilename').html(response.master_file_name);
            }

            if (response.master_file !== "") {
                $('.myfile').attr('href', response.master_file);
                $('.downloadmyfile').attr('href',response.master_file);
                $('.downloadmyfile').attr('onclick', 'return addDownloadActivity("'+response.master_file_name+'","'+response.name_cms+'")');
                $('.downloadmyfile').prop('download',response.master_file_name);
            }

            if (response.mfupload_date !== null && response.mfupload_date !== "") {
                $('.mupl').html(response.mfupload_date);
            } else {
                $('.mupl').html('N/A');
            }

            if (response.mfupd_date !== null && response.mfupd_date !== "") {
                $('.mupd').html(response.mfupd_date);
            } else {
                $('.mupd').html('N/A');
            }
        },
        error: function(xhr, status, error) {
            // Handle error situations here if needed
            console.error(xhr, status, error);
        }
    });
}

$(document).ready(function () {
    var teacher_id = $('#id').val();
    var financial_year = $('#year').val();
     var record_type = $('.tablike').attr('value');
 
    $.ajax({
        url: '<?= base_url(ADMINPATH.'getRecords') ?>',
        method: 'POST',
      //  dataType: 'json',
        data: { teacher_id: teacher_id,financial_year:financial_year,record_type:record_type },
        success: function (response) {
            $('#append_records').html(response);
        },
        error: function (xhr, status, error) {
            // Handle error situations here if needed
            console.error(xhr, status, error);
        }
    });
});

function getRecords(record_type,teacher_id=null){
    if(teacher_id==null){
       var teacher_id = $('#id').val(); 
    }else{
       var teacher_id =teacher_id;
    }
     $('.current').removeClass('active', true );
    $('#record_type_'+record_type).addClass('active', true);
     var financial_year = $('#year').val();
    
    $.ajax({
        url: '<?= base_url(ADMINPATH.'getRecords') ?>',
        method: 'POST',
      //  dataType: 'json',
        data: { teacher_id: teacher_id,financial_year:financial_year,record_type:record_type },
        success: function (response) {
            
            $('#append_records').html(response);
        },
        error: function (xhr, status, error) {
            // Handle error situations here if needed
            console.error(xhr, status, error);
        }
    });
}

function callgetrecords(){
    var record_type = $('.tablike').attr('value');
    var teacher_id = $('#id').val(); 
    getRecords(record_type,teacher_id=null)
}

function appenvalue() {
    $('#fin_year').val($('#year').val()); 
    $('#teach_name').val($('#teachname').val()); 
    $('#cmsscode').val($('#cmscode').val()); 
    
    var activeValue = $('.tablike.active').data('tab-value');
    //alert(activeValue);
     // Alert the value for debugging purposes
    
    $('#jobtype').val(activeValue); 
}

$(document).ready(function(){
   getTeachersList();
});

function getTeachersList(){
    var school_code=$('#school_id').val();
    $.ajax({
        url: '<?= base_url(ADMINPATH.'getTeachersList') ?>',
        method: 'POST',
      //  dataType: 'json',
        data: { school_code: school_code},
        success: function (response) {
            
            $('#employee').html(response);
        },
        error: function (xhr, status, error) {
            // Handle error situations here if needed
            console.error(xhr, status, error);
        }
    });
}
</script>
