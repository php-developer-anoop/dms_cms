<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">
   
    <div class="card p-2">
      <div class="row">
        <div class="col-4"><b>Teacher Name : </b> <?=!empty($details['teacher_name'])?$details['teacher_name']:""?></div>
        <div class="col-4"></div>
        <div class="col-4">
          <?php  $currentURL = current_url(); //for simple URL
            $params = $_SERVER['QUERY_STRING']; //for parameters
            $fullURL = $currentURL . '?' . $params; //full URL with parameter?>
          <?=form_open_multipart(ADMINPATH . 'save-master-file?id='.$id); ?>
          <?=form_hidden('old_file',$file)?>
          <?=form_hidden('url',$fullURL)?>
          <div class="row mb-3">
            <?=form_label('File','file',['class'=>'col-sm-2 col-form-label'])?>
            <div class="col-sm-5">
              <input type="file" name="file"  id="file" required class="form-control" />
            </div>
            <?php if(!empty($access) || ($user_type != "Admin")){?>
            <div class="col-sm-3">
              <button type="submit" id="submit" class="btn btn-primary">Submit</button>
            </div>
            <?php } ?>
          </div>
          <div class="row">
            <?php if(!empty($file)){?>
            <div class="col-sm-6">
              <a href="javascript:void(0)" onclick="appendfile('<?=base_url('uploads/'.$file)?>')" data-bs-toggle="modal" data-bs-target="#basicModal"> Click To View</a>
              <?php if(!empty($access) || ($user_type != "Admin")){?>
              <a href="<?=base_url('uploads/').$file?>" download onclick="return addDownloadActivity('<?=$file?>','<?=$details['teacher_name']."/".$details['school_code']?>')"><span class="bg-success rounded-2 ms-3 pb-1 pt-0"><i class="bx bx-download mx-1 fs-5  text-white"></i></span></a>
                 <?php } ?>
            </div>
            <?php } ?>
          </div>
          <?=form_close()?>
        </div>
      </div>
      <p><b>Aadhaar Number : </b> <?=!empty($details['aadhaar_number'])?$details['aadhaar_number']:"N/A"?></p>
      <p><b>CMSS Code : </b> <?=!empty($details['cmss_code'])?$details['cmss_code']:""?></p>
      <p><b>Grade : </b> <?=!empty($details['grade'])?$details['grade']:""?></p>
      <p><b>School Code : </b> <?=!empty($details['school_code'])?$details['school_code']:""?></p>
      <p><b>School Name : </b> <?=!empty($details['school_name'])?$details['school_name']:""?></p>
      <p><b>Date Of Joining : </b> <?=!empty($details['date_of_joining'])?date('d-m-Y',strtotime($details['date_of_joining'])):""?></p>
      <p><b>Profile Image : </b> <img src="<?=!empty($details['profile_image'])?base_url('uploads/'.$details['profile_image']):""?>" height="100" width="100"></p>
      <div class="d-flex gap-3 flex-row">
        <b>Select Record Type </b> 
        <div class="col-2 justify-content-center"><a onclick="gotolink('Job')" class="btn btn-primary text-white">Job</a></div>
        <div class="col-2 justify-content-center"><a onclick="gotolink('Service')" class="btn btn-primary text-white">Service</a></div>
        <div class="col-2 justify-content-center"><a onclick="gotolink('Amended')" class="btn btn-primary text-white">Amended</a></div>
        <div class="col-2"></div>
        <div class="col-2 justify-content-center"><a href="<?=base_url(ADMINPATH.'add-records')?>" class="btn btn-success text-white">Add Record</a></div>
      </div>
      <?php if(!empty($type)){?>
      <table id="responseData" class="table  mb-0 ">
        <thead>
          <tr>
            <th class="text-center">Sr. No.</th>
            <th class="text-center">Financial Year</th>
            <th class="text-center">Filename</th>
           <?php if(!empty($access) || ($user_type != "Admin")){?>
            <th class="text-center">File</th>
            <th class="text-center">Action</th>
           <?php } ?>
          </tr>
        </thead>
        <tbody>
          <?php 
            $i = 1;
            foreach ($records as $key => $value) {
            ?>
          <tr>
            <td class="text-center"><?= $i;?></td>
            <td class="text-center"><?= $value['financial_year'];?></td>
            <td class="text-center"><?= $value['filename'];?></td>
           <?php if(!empty($access) || ($user_type != "Admin")){?>
            <td class="text-center"><a href="javascript:void(0)" onclick="appendfile('<?=base_url('uploads/'.$value['file'])?>')" data-bs-toggle="modal" data-bs-target="#basicModal">
              Click To View
              </a>
            </td>
            <td class="text-center">
              <a href="<?= base_url(ADMINPATH . "add-records?id=".$value['id']) ?>" class="btn btn-primary btn-sm text-white" title="Edit Record"><i class="tf-icons bx bx-edit"></i></a>
              <a href="javascript:void(0)" onclick="remove('<?=$value['id']?>','dt_records')" class="btn btn-danger btn-sm text-white" title="Delete Record"><i class="tf-icons bx bx-trash"></i></a>
              <a href="<?= base_url(ADMINPATH . "add-records") ?>" class="btn btn-primary btn-sm text-white" title="Add Record"><i class="tf-icons bx bx-plus"></i></a>
            </td>
            <?php } ?>
          </tr>
          <?php
            $i++;  }
            ?>
        </tbody>
      </table>
      <?php } ?>
    </div>
  </div>
  <div class="content-backdrop fade"></div>
</div>
<div class="modal fade" id="basicModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <iframe src="" id="appendfile" width="500" height="300" frameborder="0"></iframe>
      </div>
      <div class="modal-footer d-none">
        <button type="button" class="btn btn-primary">Download</button>
      </div>
    </div>
  </div>
</div>
<script>
  function gotolink(type) {
      if (type === "") {
          window.location.href = "<?=base_url(ADMINPATH.'view-teacher-detail')?>?id=<?=$id?>";
      } else {
          window.location.href = "<?=base_url(ADMINPATH.'view-teacher-detail')?>?id=<?=$id?>&type=" + type;
      }
  }
  
</script>
<script language="javascript" type="text/javascript">
  function appendfile(file){
     
    var iframe = $('#appendfile');
  
     iframe.on('load', function() {
         var doc = iframe.contents();
         var body = doc.find('body');
  
         body.html('<embed src="' + file + '#toolbar=0" width="100%" height="100%" />');
     });

     iframe.attr('src', 'about:blank');
  }                 
</script>