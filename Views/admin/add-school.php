<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
      <div class="col-xxl">
        <div class="card mb-4">
          <div class="card-header">
            <a href="<?= base_url(ADMINPATH . 'school-list') ?>" class="btn btn-success m-auto" style="float:right;position:relative;">View School</a>
          </div>
          <div class="card-body">
            <form id="myForm">
              <input type="hidden" id="school_id" value="<?=$id?>">
              <div class="row mb-3">
                <?=form_label('School Name','school_name',['class'=>'col-sm-2 col-form-label'])?>
                <div class="col-sm-3">
                  <input type="text" name="school_name" autocomplete="off" value="<?=$school_name?>" class="form-control restrictedInput" required id="school_name" placeholder="School Name" />
                </div>
                <?=form_label('School Code','school_code',['class'=>'col-sm-2 col-form-label'])?>
                <div class="col-sm-3">
                  <input type="text" name="school_code" autocomplete="off"  id="school_code" value="<?=$school_code?>" required class="form-control" placeholder="School Code" aria-label="School Code" aria-describedby="School Code" />
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
                  <input type="button" id="submit" onclick="return checkDuplicateSchool()" class="btn btn-primary <?=!empty($id)?"submit":""?>" value="<?=empty($id)?"Submit":"Update"?>">
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