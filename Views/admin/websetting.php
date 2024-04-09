<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">
   
    <div class="row">
      <div class="col-xxl">
        <div class="card mb-4">
          <div class="card-body">
            <?=form_open_multipart(ADMINPATH . 'save-setting'); ?>
            <?=form_hidden('id',!empty($web['id'])?$web['id']:"")?>
            <?=form_hidden('old_logo',!empty($web['logo'])?$web['logo']:"")?>
            <?=form_hidden('old_favicon',!empty($web['favicon'])?$web['favicon']:"")?>
            <div class="row mb-3">
              <div class="col-sm-3">
                <label class="col-form-label" for="basic-default-name">Company Name</label>
                <input type="text" name="company_name" value="<?=!empty($web['company_name']) ? $web['company_name']:''?>" class="form-control ucwords restrictedInput" required id="basic-default-name" placeholder="Company Name" />
              </div>
              <div class="col-sm-3">
                <label class="col-form-label" for="basic-default-company">Care Mobile No.</label>
                <input type="text" name="care_mobile_no" value="<?=!empty($web['care_mobile_no']) ? $web['care_mobile_no']:''?>" class="form-control notzero numbersWithZeroOnlyInput" minlength="10" required  maxlength="10" id="basic-default-company phone-mask" placeholder="Care Mobile No." />
              </div>
              <div class="col-sm-3">
                <label class="col-form-label" for="basic-default-email">Care Whatsapp No.</label>
                <div class="input-group input-group-merge">
                  <input type="text" name="care_whatsapp_no" value="<?=!empty($web['care_whatsapp_no']) ? $web['care_whatsapp_no']:''?>" id="basic-default-email" maxlength="10" minlength="10" required class="form-control phone-mask notzero numbersWithZeroOnlyInput" placeholder="Care Whatsapp No." aria-label="Care Whatsapp No." aria-describedby="basic-default-phone" />
                </div>
              </div>
              <div class="col-sm-3">
                <label class="col-form-label" for="basic-default-phone">Care Email Id</label>
                <input type="email" name="care_email_id" id="basic-default-phone" value="<?=!empty($web['care_email_id']) ? $web['care_email_id']:''?>" required class="form-control emailInput" placeholder="Care Email Id" aria-label="Care Email Id" aria-describedby="basic-default-email" />
              </div>
            </div>
            <div class="row mb-3">
              <label class="col-sm-2 col-form-label" for="office_address">Office Address</label>
              <div class="col-sm-10">
                <input type="text" name="office_address" value="<?=!empty($web['office_address']) ? $web['office_address']:''?>" required id="office_address" class="form-control" placeholder="Office Address" aria-label="Office Address" aria-describedby="basic-icon-default-message2"></textarea>
              </div>
            </div>
           
            <div class="row mb-3">
              <div class="col-sm-4">
                <label class="col-form-label" for="logo">Logo</label>
                <input type="file" name="logo" class="form-control" <?=empty($web['logo'])?"required":""?> id="logo"  accept="image/png, image/jpg, image/jpeg" />
              </div>
              <?php if(!empty($web['logo'])){?>
              <div class="col-sm-2">
                <img src="<?= base_url('uploads/') . $web['logo']; ?>" height="70px" width="100px" alt="Logo">
              </div>
              <?php } ?>
              <div class="col-sm-4">
                <label class="col-form-label" for="favicon">Favicon</label>
                <input type="file" name="favicon" class="form-control" <?=empty($web['favicon'])?"required":""?> id="favicon" accept="image/png, image/jpg, image/jpeg"  />
              </div>
              <?php if(!empty($web['favicon'])){?>
              <div class="col-sm-2">
                <img src="<?= base_url('uploads/') . $web['favicon']; ?>" height="70px" width="100px" alt="Logo">
              </div>
              <?php } ?>
            </div>
            <div class="row justify-content-start">
            <?php if(!empty($access) || ($user_type!="Admin")){?>
              <div class="col-sm-10">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
              <?php } ?>
            </div>
            <?=form_close()?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>