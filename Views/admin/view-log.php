<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">

    <div class="card pt-2">
     
      <div class="table-responsive text-nowrap container">
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
            url: '<?= base_url(ADMINPATH . 'log-data'); ?>?' + qparam,
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
                "url": '<?= base_url(ADMINPATH . 'log-data'); ?>' + newQueryParam,
                "type": 'POST',
                dataSrc: (res) => {
                    return res.data
                }
            },
            "columns": [{ data: "sr_no", "name": "Sr.No", "title": "Sr.No" },
            { data: "user_name", "title": "User Name" },
          //  { data: "id", "title": "Location","render":locationFormatter },
            { data: "id", "title": "Device Detail","render":device_detail },
            { data: "id", "title": "Session","render":session },
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

  var locationFormatter = (data, type, row, meta) => {
  let displayData = ''; // Renamed variable to avoid conflict with the 'data' parameter

  let login_city = row.login_city != null ? row.login_city : "";
  let login_state = row.login_state != null ? row.login_state : "";
  let login_country = row.login_country != null ? row.login_country : "";

  if (type === 'display') {
    displayData += '<span class="fotr_10"><b>City : </b>' + login_city + '</span><br>';
    displayData += '<span class="fotr_10"><b>State: </b>' + login_state + '</span><br>';
    displayData += '<span class="fotr_10"><b>Country: </b>' + login_country + '</span>';
  }

  return displayData;
}

var device_detail = (data, type, row, meta) => {
  var data = '';
  let login_ip = row.login_ip != null ? row.login_ip : "";
  let device = row.device != null ? row.device : "";
  let os = row.os != null ? row.os : "";
  if (type === 'display') {
    data += '<span class="fotr_10"><b>IP : </b>' + login_ip + '</span><br>';
    data += '<span class="fotr_10"><b>Device : </b>' + device + '</span><br>';
    data += '<span class="fotr_10"><b>OS : </b>' + os + '</span>';
  }
  return data;
}

var session = (data, type, row, meta) => {
  var data = '';
  let login_at = row.login_at != null ? row.login_at : "";
  let logout_at = row.logout_at != null ? row.logout_at : "";

  if (type === 'display') {
    data += '<span class="fotr_10"><b>Login At : </b>' + login_at + '</span><br>';
    data += '<span class="fotr_10"><b>Logout At : </b>' + logout_at + '</span>';
  }
  return data;
}

// function action_render(data, type, row, meta) {
//   let output = '';
//   if (type === 'display') {
//     var onclick = "remove('" + row.id + "','dt_menus')";
//     output = '<a href="<?php // base_url(ADMINPATH . "add-menu?id=") ?>' + row.id + '" class="btn btn-primary btn-sm text-white" title="Edit Menu"><i class="tf-icons bx bx-edit"></i></a> ';
//   // output += '<a class="btn btn-sm btn-danger text-white" onclick="' + onclick + '"><i class="fa fa-trash"></i></a> ';
//   }
//   return output;
// }



</script>