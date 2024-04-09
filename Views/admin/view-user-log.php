<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="row pb-2 justify-content-center">
        <div class="col-lg-4">
        <div class="selectschl">
          <label>Select User</label>
          <select name="user_id" id="user_id" class="form-control select2">
              <option value="">--Select User--</option>
              <?php if(!empty($users)){foreach($users as $ukey=>$uvalue){?>
              <option value="<?=$uvalue['id']?>" <?=!empty($user_id) && ($user_id==$uvalue['id'])?"selected":""?>><?=$uvalue['user_name'].' ['.$uvalue['user_email'].']'?></option>
              <?php }} ?>
          </select>
        </div>
      </div>
      <div class="col-lg-2">
        <div class="selectschl">
          <label>From Date</label>
          <input type="date" name="from_date" id="from_date" onchange="validate_to()"  required class="form-control" value="<?=$from_date?>" max="<?=date('Y-m-d')?>">
        </div>
      </div>
      <div class="col-lg-2">
        <div class="selectschl">
          <?=form_label('To Date','to_date',['class'=>'col-form-label'])?>
          <input type="date" name="to_date" id="to_date" value="<?=$to_date?>" onchange="validate_from()"  required class="form-control" max="<?=date('Y-m-d')?>">
        </div>
      </div>
      <div class="form-group col-lg-3 mt-4 pt-1">
        <button type="button" onclick="return getUserLog()" class="btn btn-primary" >Submit</button>
        <button class="btn btn-success"><a href="<?=base_url(ADMINPATH.'user-activity-log')?>" class="text-white">Reset</a></button>
      </div>
    </div>
    <div class="card pt-2">
      <div class="table-responsive text-nowrap container">
        <input type="hidden" value="0" id="totalRecords" />
        <table id="responseData" class="table  mb-0 tablesizer">
        </table>
      </div>
    </div>
  </div>
  <div class="content-backdrop fade"></div>
</div>
<script>
    function getTotalRecordsData(qparam) {
        $.ajax({
            url: '<?= base_url(ADMINPATH . 'user-activity-log-data'); ?>?' + qparam,
            type: "POST",
            data: { 'is_count': 'yes', 'start': 0, 'length': 10 },
            cache: false,
            success: function (response) {
                $('#totalRecords').val(response);
                //if (response) {
                    loadAllRecordsData(qparam);
                //}
            }
        });
    }

    $(document).ready(function () {
        let qparam = (new URL(location)).searchParams;
        getTotalRecordsData(qparam);
    });

    function loadAllRecordsData(qparam) {
       // alert(qparam);
        $('#responseData').html('');
        var newQueryParam = '?'+qparam + '&recordstotal=' + $('#totalRecords').val();
        $('#responseData').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": '<?= base_url(ADMINPATH . 'user-activity-log-data'); ?>' + newQueryParam,
                "type": 'POST',
                dataSrc: (res) => {
                    return res.data
                }
            },
            "columns": [{ data: "sr_no", "name": "Sr.No", "title": "Sr.No" },
            { data: "id", "title": "User Detail","render":user_detail },
            { data: "id", "title": "Activity Detail","render":activity_detail },
            { data: "add_date", "title": "Activity On" },
          ],
             
            "rowReorder": { selector: 'td:nth-child(2)' },
            "responsive": false,
            "autoWidth": false,
            "destroy": true,
            "searchDelay": 500,
            "searching": true,
            "pagingType": 'simple_numbers',
            "rowId": (a) => { return 'id_' + a.id; },
            "iDisplayLength": 10,
            "order": [3, "asc"],
        });
    }



var user_detail = (data, type, row, meta) => {
  var data = '';
  let user_email = row.user_email != null ? row.user_email : "";
  let user_name = row.user_name != null ? row.user_name : "";
  if (type === 'display') {
    data += '<span class="fotr_10"><b>User Email : </b>' + user_email + '</span><br>';
    data += '<span class="fotr_10"><b>User Name : </b>' + user_name + '</span>';
  }
  return data;
}

var activity_detail = (data, type, row, meta) => {
  var data = '';
  let activity_type = row.activity_type != null ? row.activity_type : "";
  let details = row.details != null ? row.details : "";

  if (type === 'display') {
    data += '<span class="fotr_10"><b>Activity : </b>' + activity_type + '</span><br>';
    data += '<span class="fotr_10"><b>Detail : </b>' + details + '</span>';
  }
  return data;
}

function getUserLog() {
    var from_date = $('#from_date').val();
    var to_date = $('#to_date').val();
    var user_id = $('#user_id').val();

    if (from_date === "" && to_date === "" && user_id === "" ) {
        toastr.error("Select at least one thing to filter");
        return false;
    } else {
        var url = "<?=base_url(ADMINPATH.'user-activity-log')?>";
        if (user_id !== "") {
            url += "?user_id=" + user_id;
        }
        if ((from_date == "" || to_date == "") && user_id === "") {
        toastr.error("Please Select both Date");
        return false;
        }else{
            
        url += (url.includes("?") ? "&" : "?") + "from_date=" + from_date+"&to_date="+to_date;
        }

        window.location.href = url;
    }
}

function resetToDate(){
    $('#to_date').val('');
}

function validate_from(){
		var max = $("#to_date").val();
		$("#from_date").attr("max", max);
	
	}
	
	function validate_to(){
		var min = $("#from_date").val();
		$("#to_date").attr("min", min);
		$("#to_date").val(min);
	} 
</script>