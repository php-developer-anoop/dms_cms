<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">

    <div class="card">
      <div class="card-header">
        <a href="<?= base_url(ADMINPATH . 'add-menu') ?>" class="btn btn-success m-auto" style="float:right;position:relative;">Add Menu</a>
      </div>
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
            url: '<?= base_url(ADMINPATH . 'menu-data'); ?>?' + qparam,
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
                "url": '<?= base_url(ADMINPATH . 'menu-data'); ?>' + newQueryParam,
                "type": 'POST',
                dataSrc: (res) => {
                    return res.data
                }
            },
            "columns": [{ data: "sr_no", "name": "Sr.No", "title": "Sr.No" },
            { data: "", "name": "Menu Detail", "title": "Menu Detail","render":menu_detail },
            { data: "add_date", "title": "Added On" },
            <?php if(!empty($access) || ($user_type != "Admin")){?>
            { data: "", "name": "Status", "title": "Status", "render": status_render },
           
            { data: "id", "name": "Action", "title": "Action", "render": action_render }
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

    var dates = (data, type, row, meta) => {
  var data = '';
  let add_date = row.add_date != null ? row.add_date : "";
  let priority = row.priority != null ? row.priority : "";
  if (type === 'display') {
    data += '<span class="fotr_10"><b>Added On : </b>' + add_date + '</span><br>';
    data += '<span class="fotr_10"><b>Priority: </b>' + priority + '</span>';

  }
  return data;
}
var menu_detail = (data, type, row, meta) => {
  var data = '';
  let menu_title = row.menu_title != null ? row.menu_title : "";
  let slug = row.slug != null ? row.slug : "";
  let menu_type = row.menu_type != null ? row.menu_type : "";
  if (type === 'display') {
    data += '<span class="fotr_10"><b>Menu Title : </b>' + menu_title + '</span><br>';
    data += '<span class="fotr_10"><b>Slug : </b>' + slug + '</span><br>';
    data += '<span class="fotr_10"><b>Menu Type : </b>' + menu_type + '</span>';
  }
  return data;
}


function action_render(data, type, row, meta) {
  let output = '';
  if (type === 'display') {
    var onclick = "remove('" + row.id + "','dt_menus')";
    output = '<a href="<?= base_url(ADMINPATH . "add-menu?id=") ?>' + row.id + '" class="btn btn-primary btn-sm text-white" title="Edit Menu"><i class="tf-icons bx bx-edit"></i></a> ';
   // output += '<a class="btn btn-sm btn-danger text-white" onclick="' + onclick + '"><i class="fa fa-trash"></i></a> ';
  }
  return output;
}

function status_render(data, type, row, meta) {
  if (type === 'display') {
    const isChecked = row.status === 'Active';
    const label = isChecked ? 'Active' : 'Inactive';
    const id = `tableswitch5${row.id}`;
    const onchange = `change_status(${row.id}, 'dt_menus')`;

    return `<div class="custom-control custom-switch">
                
                <label class="custom-control-label" for="${id}" id="status_label">${label}</label>
            </div> `;
  }
  return '';
}

</script>