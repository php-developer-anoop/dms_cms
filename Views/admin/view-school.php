<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">
  
    <div class="card">
  
      <div class="card-header">
        <a href="<?= base_url(ADMINPATH . 'add-school') ?>" class="btn btn-success m-auto" style="float:right;position:relative;">Add School</a>
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
            url: '<?= base_url(ADMINPATH . 'school-data'); ?>?' + qparam,
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
                "url": '<?= base_url(ADMINPATH . 'school-data'); ?>' + newQueryParam,
                "type": 'POST',
                dataSrc: (res) => {
                    return res.data
                }
            },
            "columns": [{ data: "sr_no", "title": "Sr.No" },
            { data: "id", "title": "School Detail","render":school_detail },
            //{ data: "id", "title": "Location","render":location_details },
            { data: "id", "title": "Dates","render":dates },
            <?php if(!empty($access) || ($user_type != "Admin")){?>
            { data: "id", "title": "Status", "render": status_render },
            
            { data: "id", "title": "Action", "render": action_render }
            <?php } ?>
          ],

            "rowReorder": { selector: 'td:nth-child(2)' },
            "responsive": true,
            "autoWidth": false,
            "destroy": true,
            "searchDelay": 1000,
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
var school_detail = (data, type, row, meta) => {
  var data = '';
  let school_name = row.school_name != null ? row.school_name : "";
  let school_code = row.school_code != null ? row.school_code : "";
  if (type === 'display') {
    data += '<span class="fotr_10"><b>School Name : </b>' + school_name + '</span><br>';
    data += '<span class="fotr_10"><b>School Code : </b>' + school_code + '</span><br>';
  }
  return data;
}

// var location_details = (data, type, row, meta) => {
//   var data = '';
//   let state = row.state != null ? row.state : "";
//   let city = row.city != null ? row.city : "";
//   let address = row.address != null ? row.address : "";
//   let pincode = row.pincode != null ? row.pincode : "";
//   if (type === 'display') {
//     data += '<span class="fotr_10"><b>State : </b>' + state + '</span><br>';
//     data += '<span class="fotr_10"><b>City : </b>' + city + '</span><br>';
//     data += '<span class="fotr_10"><b>Address : </b>' + address + '</span><br>';
//     data += '<span class="fotr_10"><b>Pincode : </b>' + pincode + '</span>';
//   }
//   return data;
// }

function action_render(data, type, row, meta) {
  let output = '';
  if (type === 'display') {
   // var onclick = "remove('" + row.id + "','dt_school_master')";
    output = '<a href="<?= base_url(ADMINPATH . "add-school?id=") ?>' + row.id + '" class="btn btn-primary btn-sm text-white" title="Edit School"><i class="tf-icons bx bx-edit"></i></a> ';
   // output += '<a class="btn btn-sm btn-danger text-white" onclick="' + onclick + '"><i class="fa fa-trash"></i></a> ';
  }
  return output;
}

function status_render(data, type, row, meta) {
  if (type === 'display') {
    const isChecked = row.status === 'Active';
    const label = isChecked ? 'Active' : 'Inactive';
    const id = `tableswitch5${row.id}`;
    const onchange = `change_status(${row.id}, 'dt_school_master')`;

    return `<div class="d-flex gap-3 checked-color">
    <div class="custom-control custom-switch form-switch">
                <input type="checkbox" onchange="${onchange}" ${isChecked ? 'checked' : ''} class="custom-control-input form-check-input" id="${id}" role="switch">
                <label class="custom-control-label" for="${id}" id="status_label${row.id}">${label}</label>
            </div> 
            </div> `;
  }
  return '';
}

</script>