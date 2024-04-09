<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
  
      <div class="card-header">
        <a href="<?= base_url(ADMINPATH . 'add-employee') ?>" class="btn btn-success m-auto" style="float:right;position:relative;">Add Employee</a>
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
<script>
    function getTotalRecordsData(qparam) {
        $.ajax({
            url: '<?= base_url(ADMINPATH . 'teacher-data'); ?>?' + qparam,
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
                "url": '<?= base_url(ADMINPATH . 'teacher-data'); ?>' + newQueryParam,
                "type": 'POST',
                dataSrc: (res) => {
                    return res.data
                }
            },
            "columns": [{ data: "sr_no", "title": "Sr.No" },
            { data: "", "title": "Employee Detail","render":teacher_detail },
            { data: "", "title": "Service Details","render":service_details },
            { data: "", "title": "Dates","render":dates },
            <?php if(!empty($access) || ($user_type != "Admin")){?>
           // { data: "", "title": "Status", "render": status_render },
            { data: "id", "title": "Action", "render": action_render }
            <?php } ?>
          ],

            "rowReorder": { selector: 'td:nth-child(2)' },
            "responsive": false,
            "autoWidth": false,
            "destroy": true,
            "searchDelay": 2000,
            "searching": true,
            "pagingType": 'simple_numbers',
            "rowId": (a) => { return 'id_' + a.id; },
            "iDisplayLength": 10,
            "order": [2, "asc"],
        });
    }

var dates = (data, type, row, meta) => {
  var data = '';
  let add_date = row.add_date != null ? row.add_date : "";
  let update_date = row.update_date != null ? row.update_date : "";
  if (type === 'display') {
    data += '<span class="fotr_10"><b>Added On : </b>' + add_date + '</span><br>';
    data += '<span class="fotr_10"><b>Updated On: </b>' + update_date + '</span>';

  }
  return data;
}

var teacher_detail = (data, type, row, meta) => {
  var data = '';
  let teacher_name = row.teacher_name != null ? row.teacher_name : "";
  let aadhaar_number = row.aadhaar_number != null ? row.aadhaar_number : "N/A";
  let cmss_code = row.cmss_code != null ? row.cmss_code : "";
  let grade = row.grade != null ? row.grade : "";
  let url = '<?=base_url(ADMINPATH.'view-teacher-detail')?>?id='+row.id;
  if (type === 'display') {
    data += '<span class="fotr_10"><b>Name : </b>' + teacher_name + '</span><br>';
    data += '<span class="fotr_10"><b>Aadhaar Number : </b>' + aadhaar_number + '</span><br>';
    data += '<span class="fotr_10"><b>CMS Code : </b>' + cmss_code + '</span><br>';
    data += '<span class="fotr_10"><b>Grade : </b>' + grade + '</span>';
  }
  return data;
}

var service_details = (data, type, row, meta) => {
  var data = '';
  let school_name = row.school_name != null ? row.school_name : "";
  let school_code = row.school_code != null ? row.school_code : "";
  let date_of_joining = row.date_of_joining != null ? row.date_of_joining : "";
  let grade = row.grade != null ? row.grade : "";
  if (type === 'display') {
    data += '<span class="fotr_10"><b>School Name : </b>' + school_name + '</span><br>';
    data += '<span class="fotr_10"><b>School Code : </b>' + school_code + '</span><br>';
    data += '<span class="fotr_10"><b>DOJ : </b>' + date_of_joining + '</span>';
  
  }
  return data;
}


function action_render(data, type, row, meta) {
  let output = '';
  if (type === 'display') {
    //var onclick = "remove('" + row.id + "','dt_teacher_master')";
    output = '<a href="<?= base_url(ADMINPATH . "add-employee?id=") ?>' + row.id + '" class="btn btn-primary btn-sm text-white" title="Edit Teacher"><i class="tf-icons bx bx-edit"></i></a> ';
    output += '<a href="<?= base_url(ADMINPATH . "view-teacher-detail?id=") ?>' + row.id + '" class="btn btn-info btn-sm text-white" title="Preview"><i class="tf-icons bx bx-detail"></i></a> ';
  }
  return output;
}


function status_render(data, type, row, meta) {
  if (type === 'display') {
    const isChecked = row.status === 'Active';
    const label = isChecked ? 'Active' : 'Inactive';
    const id = `tableswitch5${row.id}`;
    const onchange = `change_status(${row.id}, 'dt_teacher_master')`;

    return `<div class="custom-control custom-switch">
                <input type="checkbox" onchange="${onchange}" ${isChecked ? 'checked' : ''} class="custom-control-input form-check-input" id="${id}" role="switch">
                <label class="custom-control-label" for="${id}" id="status_label${row.id}">${label}</label>
            </div> `;
  }
  return '';
}

</script>