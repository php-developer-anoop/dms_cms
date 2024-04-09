<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
      <div class="card-header">
        <div class="form-group">
          <div class="col-sm-4">
            <label>Choose Admin</label>
            <select onchange="viewAssigned(this.value)" class="form-control select2" name="user" id="user">
              <option value="0">--Choose an option--</option>
              <?php if(!empty($users_data)){
                foreach ($users_data as $key => $value) { ?>
              <option <?= ($value['id'] == $id) ? 'selected' : '';?> value="<?=($value['id'])?>"><?= $value['user_email'];?></option>
              <?php } }?>
            </select>
          </div>
        </div>
      </div>
      <?php 
        $read_assigned = (!empty($user['read_menu_ids'])) ? explode(',',$user['read_menu_ids']) : [];
        $write_assigned = (!empty($user['write_menu_ids'])) ? explode(',',$user['write_menu_ids']) : [];
        ?>
      <div class="table-responsive text-nowrap container">
          <input type="hidden" id="useremail" value="<?=$useremail?>">
        <input type="hidden" value="0" id="totalRecords" />
        <table id="responseData" class="table  mb-0 ">
          <thead>
            <tr>
              <th class="text-center">Sr. No.</th>
              <th class="text-center">Menu Title</th>
              <th class="text-center">Menu Type</th>
              <th class="text-center">Added On</th>
              <?php if(!empty($access) || ($user_type != "Admin")){?>
              <th class="text-center">
                Read <input type="checkbox" id="readcheckAll" name="readcheckAll" <?= (count($read_assigned) == count_data('id', 'menus',['status'=>'Active','slug != '=>'user-activity-log'])) ? "checked" : "" ?> class="read pt-3">
              </th>
              <th class="text-center">
                Write <input type="checkbox" id="writecheckAll" name="writecheckAll" <?= (count($write_assigned) == count_data('id', 'menus',['status'=>'Active','slug != '=>'user-activity-log'])) ? "checked" : "" ?> class="write pt-3">
              </th>
              <?php } ?>
            </tr>
          </thead>
          <tbody>
            <?php 
              $i = 1;
              foreach ($menu_data as $key => $value) {
              ?>
            <tr>
              <td class="text-center"><?= $i;?></td>
              <td class="text-center"><?= $value['menu_title'];?></td>
              <td class="text-center"><?= $value['menu_type'];?></td>
              <td class="text-center"><?= $value['add_date'];?></td>
              <?php if(!empty($access) || ($user_type != "Admin")){?>
              <td class="text-center"><input <?= (in_array($value['id'], $read_assigned)) ? 'checked' : '' ?>  type="checkbox" value="<?= $value['id'];?>" onclick="assignMenu('read_menu',this,'<?=$value['menu_title']?>','Read')" class="read readmenu read-checkbox"></td>
              <td class="text-center"><input <?= (in_array($value['id'], $write_assigned)) ? 'checked' : '' ?> type="checkbox" value="<?= $value['id'];?>" onclick="assignMenu('write_menu',this,'<?=$value['menu_title']?>','Write')" class="write writemenu write-checkbox"></td>
              <?php } ?>
            </tr>
            <?php
              $i++;  }
              ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="content-backdrop fade"></div>
</div>
<script>
    function viewAssigned(id) {
    if (id == "0") {
      window.location.href = "<?= base_url(ADMINPATH.'assign-menu');?>";
    } else {
      window.location.href = "<?= base_url(ADMINPATH.'assign-menu?id=');?>" + id + "";
    }
  }

  function assignMenu(permission, id,menu_name,menu_type) {
    var isChecked = $(id).prop('checked');
    var useremail = $("#useremail").val();
    var read = $('.readmenu:checked').map(function() {
      return $(this).val();
    }).get();
    var write = $('.writemenu:checked').map(function() {
      return $(this).val();
    }).get();
    var user = $("#user").val();
    if (user != 0) {
      if (isChecked == true) {
        toastr.success(menu_type+" Access Assigned For "+menu_name);
        $.ajax({
            type: "POST",
            url: "<?=base_url(ADMINPATH.'add-user-log')?>",
            data: { activity_type: menu_type+" Access", details:menu_type+" Access Assigned For "+menu_name+' To '+useremail },
            success: function(response) {
                // Handle the response if needed
            }
        });
      } else if (isChecked == false) {
        toastr.success(menu_type+" Access Removed For "+menu_name);
        $.ajax({
            type: "POST",
            url: "<?=base_url(ADMINPATH.'add-user-log')?>",
            data: { activity_type: menu_type+" Access", details:menu_type+" Access Removed For "+menu_name+' To '+useremail },
            success: function(response) {
                // Handle the response if needed
            }
        });
      }
    }
    $.ajax({
      url: '<?=base_url(ADMINPATH.('assign-menus'));?>',
      type: "POST",
      data: {
        'user': user,
        'write': write,
        'read': read,
      },
      cache: false,
      dataType: 'json',
      success: function(response) {
        if (response.status) {
          toastr.success(response.message);
        } else {
          toastr.error(response.message);
        }
      },
    });
  }
  $(document).ready(function() {
    var user = $("#user").val();
    var useremail = $("#useremail").val();
    $("#readcheckAll").change(function() {
      if (user == 0) {
        toastr.error("Please Select An Admin");
        return false;
      }
      $(".read-checkbox").prop('checked', $(this).prop('checked'));
      getCheckedValues();
    });
    $(".read-checkbox").change(function() {
      if (!$(this).prop("checked")) {
        $("#readcheckAll").prop("checked", false);
      }
      getCheckedValues();
    });
    if (user != 0) {
      $("#readcheckAll").change(function() {
        var totalMenus = $(".read-checkbox").length;
        var checkedMenus = $(".read-checkbox:checked").length; 
        if (checkedMenus === totalMenus) {
          toastr.success("Read Access Assigned for all menus");
       
          $.ajax({
            type: "POST",
            url: "<?=base_url(ADMINPATH.'add-user-log')?>",
            data: { activity_type: "Assign Read Access", details:'Read Access Assigned for all menus To '+useremail },
            success: function(response) {
                // Handle the response if needed
            }
        });
        } else if (checkedMenus === 0) {
          toastr.success("Read Access Removed for all menus");
          $.ajax({
            type: "POST",
            url: "<?=base_url(ADMINPATH.'add-user-log')?>",
            data: { activity_type: "Removed Read Access", details:'Read Access Removed for all menus To '+useremail },
            success: function(response) {
                // Handle the response if needed
            }
        });
        }
      });
    }

    function getCheckedValues() {
      var user = $("#user").val();
      var useremail = $("#useremail").val();
      var checkedValues = [];
      $(".read-checkbox:checked").each(function() {
        checkedValues.push($(this).val());
      });
      $.ajax({
        url: '<?=base_url(ADMINPATH.('assignmenu'));?>',
        type: "POST",
        data: {
          'user': user,
          'read': checkedValues,
          'type': 'read'
        },
        cache: false,
        dataType: 'html',
        success: function(response) {}
      });
    }
    $("#writecheckAll").change(function() {
      if (user == 0) {
        toastr.error("Please Select An Admin");
        return false;
      }
      $(".write-checkbox").prop('checked', $(this).prop('checked'));
      getwriteCheckedValues()
    });
    $(".write-checkbox").change(function() {
      if (!$(this).prop("checked")) {
        $("#writecheckAll").prop("checked", false);
      }
      getwriteCheckedValues();
    });
    if (user != 0) {
      $("#writecheckAll").change(function() {
        var totalMenus = $(".write-checkbox").length;
        var checkedMenus = $(".write-checkbox:checked").length;
        if (checkedMenus === totalMenus) {
          toastr.success("Write Access Assigned for all menus");
          $.ajax({
            type: "POST",
            url: "<?=base_url(ADMINPATH.'add-user-log')?>",
            data: { activity_type: "Assign Write Access", details:'Write Access Assigned for all menus To '+useremail },
            success: function(response) {
                // Handle the response if needed
            }
        });
        } else if (checkedMenus === 0) {
          toastr.success("Write Access Removed for all menus");
          $.ajax({
            type: "POST",
            url: "<?=base_url(ADMINPATH.'add-user-log')?>",
            data: { activity_type: "Removed Write Access", details:'Write Access Removed for all menus To '+useremail },
            success: function(response) {
                // Handle the response if needed
            }
        });
        }
      });
    }

    function getwriteCheckedValues() {
      var checkedValues = [];
      var user = $("#user").val();
      $(".write-checkbox:checked").each(function() {
        checkedValues.push($(this).val());
      });
      $.ajax({
        url: '<?=base_url(ADMINPATH.('assignmenu'));?>',
        type: "POST",
        data: {
          'user': user,
          'write': checkedValues,
          'type': 'write'
        },
        cache: false,
        dataType: 'html',
        success: function(response) {
            
        },
      });
    }
  });
</script>
