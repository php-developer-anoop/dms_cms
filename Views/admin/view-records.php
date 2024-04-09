<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
      <div class="row justify-content-center pt-2">
        <div class="form-group col-lg-4">
          <?=form_label('Select CMS Code','teacher',['class'=>'col-form-label'])?>
          <input type="text" name="teacher_name" autocomplete="off" id="teach_name" value="<?=$teacher_name?>" placeholder="Type CMS Code / Teacher Name" class="form-control" required onkeyup="getTeacherName(this.value)">
          <input type="hidden" name="teacher_id" id="teach_id" value="<?=$teacher_id?>">
          <ul class="autocomplete-list" id="suggestion-list" onclick="return selectTeacherName()"></ul>
        </div>
        <div class="form-group col-lg-2">
          <?=form_label('Record Type','record_type',['class'=>'col-form-label'])?>
          <select name="record_type" id="record_type" class="form-control select2" required>
            <option value="">--Select Type--</option>
            <option value="Job" <?=!empty($record_type) && ($record_type=="Job")?"selected":""?>>Job Record</option>
            <option value="Service" <?=!empty($record_type) && ($record_type=="Service")?"selected":""?>>Service Record</option>
            <option value="Amended" <?=!empty($record_type) && ($record_type=="Amended")?"selected":""?>>Amended Record</option>
          </select>
        </div>
        <div class="form-group col-lg-4 mt-4 pt-2">
          <button type="submit" class="btn btn-primary" onclick="return validatefilter()">Submit</button>
          <a href="<?=base_url(ADMINPATH.'records-list')?>" class="btn btn-success">Reset</a>
        </div>
      </div>
      <div class="card-header ">
        <a href="<?= base_url(ADMINPATH . 'add-records') ?>" class="btn btn-success m-auto" style="float:right;position:relative;">Add Record</a>
      </div>
      <div class="table-responsive text-nowrap container ">
        <input type="hidden" value="0" id="totalRecords" />
        <table id="responseData" class="table  mb-0 ">
        </table>
      </div>
    </div>
  </div>
  <div class="content-backdrop fade"></div>
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
    function getTotalRecordsData(qparam) {
        $.ajax({
            url: '<?= base_url(ADMINPATH . 'record-data'); ?>?' + qparam,
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
                "url": '<?= base_url(ADMINPATH . 'record-data'); ?>' + newQueryParam,
                "type": 'POST',
                dataSrc: (res) => {
                    return res.data
                }
            },
            "columns": [{ data: "sr_no", "title": "Sr.No" },
            { data: "financial_year", "title": "Session Year" },
            { data: "", "title": "Teacher Details","render":teacher_detail },
             { data: "record_type", "title": "Record Type" },
            { data: "filename", "title": "Filename" },
            { data: "", "title": "File","render":file_detail },
            <?php if(!empty($access) || ($user_type != "Admin")){?>
            { data: "", "title": "Status", "render": status_render },
            { data: "id", "title": "Action", "render": action_render }
            <?php } ?>
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
            "order": [2, "asc"],
        });
    }
function appendfile(file) {
   
    var iframe = $('#appendfile');
    iframe.on('load', function() {
        var doc = iframe.contents();
        var body = doc.find('body');
        body.html('<embed src="' + file + '#toolbar=0"  width="100%" height="100%" />');
    });
    iframe.attr('src', 'about:blank');
}

var file_detail = (data, type, row, meta) => {
    let filepreview = row.filepreview != null ? row.filepreview : "";
    if (type === 'display') {
        if (row.file != null) {
            data = '<span class="fotr_10"><a href="javascript:void(0)" onclick="appendfile(\'' + filepreview + '\')" data-bs-toggle="modal" data-bs-target="#basicModal">Click To View</a></span>';
        } else {
            data = 'N/A';
        }
    }
    return data;
};


var teacher_detail = (data, type, row, meta) => {
  var output = '';
  let teacher_name = row.teacher_name != null ? row.teacher_name : "";
  let cmss_code = row.cmss_code != null ? row.cmss_code : "";
  if (type === 'display') {
    output = '<span class="fotr_10"><b>Teacher Name</b>: ' + teacher_name + '</span><br>';
    output += '<span class="fotr_10"><b>CMS Code</b>: ' + cmss_code + '</span>';
  }
  return output;
}

function action_render(data, type, row, meta) {
  let output = '';
  if (type === 'display') {
    var onclick = "remove('" + row.id + "','dt_records')";
    output = '<a href="<?= base_url(ADMINPATH . "add-records?id=") ?>' + row.id +'" class="btn btn-primary btn-sm text-white" title="Edit Record"><i class="tf-icons bx bx-edit"></i></a> ';
    output += '<a class="btn btn-sm btn-danger text-white" onclick="' + onclick + '"><i class="tf-icons bx bx-trash"></i></a> ';
  }
  return output;
}

function status_render(data, type, row, meta) {
  if (type === 'display') {
    const isChecked = row.status === 'Active';
    const label = isChecked ? 'Active' : 'Inactive';
    const id = `tableswitch5${row.id}`;
    const onchange = `change_status(${row.id}, 'dt_records')`;

    return `<div class="custom-control custom-switch">
                <label class="custom-control-label" for="${id}" id="status_label">${label}</label>
            </div> `;
  }
  return '';
}

function validatefilter() {
    var teach_id = $("#teach_id").val();
    var record_type = $("#record_type").val();
    
    if (teach_id === "") {
        toastr.error("Please type a keyword");
    } else {
        if (record_type === "") {
            window.location.href = "<?= base_url(ADMINPATH.'records-list?id=') ?>" + teach_id;
        } else {
            window.location.href = "<?= base_url(ADMINPATH.'records-list?id=') ?>" + teach_id + "&record_type=" + record_type;
        }
    }
}

</script>