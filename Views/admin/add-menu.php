<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
      <div class="col-xxl">
        <div class="card mb-4">
          <div class="card-header">
            <a href="<?= base_url(ADMINPATH . 'menu-list') ?>" class="btn btn-success m-auto" style="float:right;position:relative;">View Menus</a>
          </div>
          <div class="card-body">
            <?=form_open_multipart(ADMINPATH . 'save-menu'); ?>
            <?=form_hidden('id',$id)?>
            <div class="row mb-3">
              <div class="col-sm-3">
                <?=form_label('Menu Type','menu_type',['class'=>'col-form-label'])?>
                <select name="menu_type" id="menu_type" class="form-control select2" required>
                  <option value="">--Select Menu Type--</option>
                  <option value="Menu" <?=!empty($menu_type) && ($menu_type=="Menu")?"selected":""?>>Menu</option>
                  <option value="Submenu" <?=!empty($menu_type) && ($menu_type=="Submenu")?"selected":""?>>Submenu</option>
                </select>
              </div>
              <div class="col-sm-3">
                <?=form_label('Parent Menu Name','menu_id',['class'=>'col-form-label'])?>
                <select name="menu_id" id="menu_id" class="form-control select2">
                  <option value="">--Select Parent Menu--</option>
                  <?php if(!empty($menu_list)){foreach($menu_list as $key=>$value){?>
                  <option value="<?=$value['id']?>" <?=!empty($menu_id) && ($menu_id==$value['id'])?"selected":""?>><?=$value['menu_title']?></option>
                  <?php }} ?>
                </select>
              </div>
              <div class="col-sm-3">
                <?=form_label('Menu Title','menu_title',['class'=>'col-form-label'])?>
                <div class="input-group input-group-merge">
                  <?= form_input(['name' => 'menu_title','autocomplete'=>'off', 'required' => 'required','onkeyup'=>'getSlug(this.value)','placeholder' => 'Enter Menu Title', 'id' => 'menu_title', 'class' => 'form-control restrictedInput','value'=>$menu_title]); ?>
                </div>
              </div>
              <div class="col-sm-3">
                <?=form_label('Menu Slug','menu_slug',['class'=>'col-form-label'])?>
                <?= form_input(['name' => 'menu_slug', 'autocomplete'=>'off','required' => 'required', 'placeholder' => 'Enter Menu Slug', 'id' => 'menu_slug', 'class' => 'form-control','value'=>$slug]); ?>
              </div>
            </div>
            <div class="row mb-3 d-none">
              <?=form_label('Status','status',['class'=>'col-sm-2 col-form-label'])?>
              <div class="col-sm-6">
                <div class="row mt-2">
                  <div class="col-3">
                    <div class="custom-control custom-checkbox">
                      <input class="custom-control-input checkbox-style " name="status" <?= ($status == 'Active') ? 'checked' : '' ?> type="radio" id="checkStatus1" value="Active">
                      <?=form_label('Active','checkStatus1',['class'=>'custom-control-label'])?>
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="custom-control custom-checkbox">
                      <input class="custom-control-input checkbox-style " name="status" <?= ($status == 'Inactive') ? 'checked' : '' ?> type="radio" id="checkStatus2" value="Inactive">
                      <?=form_label('Inactive','checkStatus2',['class'=>'custom-control-label'])?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row justify-content-start">
              <?php if(!empty($access) || ($user_type != "Admin") ){?>
              <div class="col-sm-10">
                <button type="submit" class="btn btn-primary <?=!empty($id)?"submit":""?>"><?=empty($id)?"Submit":"Update"?></button>
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