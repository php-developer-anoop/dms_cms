<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
      <div class="col-xxl">
        <div class="card mb-4">
          <div class="card-header">
            <a href="<?= base_url(ADMINPATH . 'user-list') ?>" class="btn btn-success m-auto" style="float:right;position:relative;">View Users</a>
          </div>
          <div class="card-body">
            <form id="myForm">
              <input type="hidden" id="user_id" value="<?=$id?>">
              <div class="row mb-3">
                <div class="col-sm-3">
                  <?=form_label('Name','user_name',['class'=>'col-form-label'])?>
                  <input type="text" name="user_name" autocomplete="off" value="<?=$user_name?>" class="form-control restrictedInput" required id="user_name" placeholder="Name" />
                </div>
                <div class="col-sm-4">
                  <?=form_label('Email','user_email',['class'=>'col-form-label'])?>
                  <div class="input-group input-group-merge">
                    <input type="email" name="user_email" autocomplete="off"  id="user_email" value="<?=$user_email?>" required class="form-control emailInput" placeholder="Email" aria-label="Email" aria-describedby="basic-default-email" />
                  </div>
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
                    <div class="col-3">
                      <div class="custom-control custom-checkbox">
                        <input class="custom-control-input" name="status" <?= ($status == 'Blocked') ? 'checked' : '' ?> type="radio" id="checkStatus3" value="Blocked">
                        <?=form_label('Blocked','checkStatus3',['class'=>'custom-control-label'])?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row justify-content-start">
                <?php if(!empty($access) || ($user_type != "Admin")){?>
                <div class="col-sm-10">
                  <button type="button" id="submit" onclick="return checkDuplicateUser()"  class="btn btn-primary <?=!empty($id)?"submit":""?>"><?=empty($id)?"Submit":"Update"?></button>
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