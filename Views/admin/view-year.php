<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">

    <div class="card">
  
      <div class="card-header">
        <a href="<?= base_url(ADMINPATH . 'add-year') ?>" class="btn btn-success m-auto" style="float:right;position:relative;">Add Year</a>
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
            url: '<?= base_url(ADMINPATH . 'year-data'); ?>?' + qparam,
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
                "url": '<?= base_url(ADMINPATH . 'year-data'); ?>' + newQueryParam,
                "type": 'POST',
                dataSrc: (res) => {
                    return res.data
                }
            },
            "columns": [{ data: "sr_no", "title": "Sr.No" },
            { data: "financial_year", "title": "Session Year" },
          //  { data: "", "title": "From/To","render":from_to },
            { data: "", "title": "Dates","render":dates },
            <?php if(!empty($access) || ($user_type != "Admin")){?>
            { data: "", "title": "Status", "render": status_render },
            
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
// var from_to = (data, type, row, meta) => {
//   var data = '';
//   let from_date = row.from_date != null ? row.from_date : "";
//   let to_date = row.to_date != null ? row.to_date : "";
//   let user_phone = row.user_phone != null ? row.user_phone : "";
//   if (type === 'display') {
//     data += '<span class="fotr_10"><b>From : </b>' + from_date + '</span><br>';
//     data += '<span class="fotr_10"><b>To : </b>' + to_date + '</span>';
//   }
//   return data;
// }


function action_render(data, type, row, meta) {
  let output = '';
  if (type === 'display') {
    //var onclick = "remove('" + row.id + "','dt_users')";
    output = '<a href="<?= base_url(ADMINPATH . "add-year?id=") ?>' + row.id + '" class="btn btn-primary btn-sm text-white" title="Edit Year"><i class="tf-icons bx bx-edit"></i></a> ';
   // output += '<a class="btn btn-sm btn-danger text-white" onclick="' + onclick + '"><i class="fa fa-trash"></i></a> ';
  }
  return output;
}

function status_render(data, type, row, meta) {
  if (type === 'display') {
    const isChecked = row.status === 'Active';
    const label = isChecked ? 'Active' : 'Inactive';
    const id = `tableswitch5${row.id}`;
    const onchange = `change_status(${row.id}, 'dt_financial_years')`;

    return `<div class="custom-control custom-switch">
                
                <label class="custom-control-label" for="${id}" id="status_label">${label}</label>
            </div> `;
  }
  return '';
}

</script>