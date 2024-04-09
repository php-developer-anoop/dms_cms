<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
      <div class="col-xxl">
        <div class="card mb-4">
          <div class="card-header">
            <a href="<?= base_url(ADMINPATH . 'grades-list') ?>" class="btn btn-success m-auto" style="float:right;position:relative;">View Grades</a>
          </div>
          <div class="card-body">
            <form>
              <input type="hidden" id="grade_id" value="<?=$id?>">
              <div class="row mb-3">
                <?=form_label('Grade','grade',['class'=>'col-sm-2 col-form-label'])?>
                <div class="col-sm-3">
                  <input type="text" name="grade"  autocomplete="off" value="<?=$grade?>" class="form-control" required id="grade" placeholder="Grade" />
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
                  <button type="button" id="submit" onclick="return checkDuplicateGrade()" class="btn btn-primary <?=!empty($id)?"submit":""?>"><?=empty($id)?"Submit":"Update"?></button>
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