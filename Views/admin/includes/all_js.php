<?=script_tag(base_url('assets/toastr/toastr.min.js'))?>
<?=script_tag(base_url('assets/vendor/libs/popper/popper.js'))."\n"?>
<?=script_tag(base_url('assets/vendor/js/bootstrap.js'))."\n"?>
<?=script_tag(base_url('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js'))."\n"?>
<?=script_tag(base_url('assets/vendor/js/menu.js'))."\n"?>
<?=script_tag(base_url('assets/vendor/libs/apex-charts/apexcharts.js'))."\n"?>
<?=script_tag(base_url('assets/js/main.js'))."\n"?>
<?=script_tag(base_url('assets/js/dashboards-analytics.js'))."\n"?>
<?=script_tag(base_url('assets/common.js'))."\n"?>
<?=script_tag(base_url('assets/select2/js/select2.full.min.js'))."\n"?>
<?=script_tag('https://buttons.github.io/buttons.js')."\n"?>
<?=script_tag(base_url('assets/sweetalert2/sweetalert2.min.js'))?>
<?=script_tag('https://unpkg.com/sweetalert/dist/sweetalert.min.js')."\n"?>
<?=script_tag('https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js')."\n"?>

<script>
$(function() {
  var Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 2000
  }); 
  <?php if (session()->getFlashdata('success')) {?>

    setTimeout(function() {
      toastr.success('<?php echo session()->getFlashdata('success'); ?>')
    }, 500); 
    <?php } ?>
  <?php if (session()->getFlashdata('failed')) { ?>
    setTimeout(function() {
      toastr.error('<?php echo session()->getFlashdata('failed'); ?>')
    }, 500); 
    <?php } ?>
});

    function getSlug(val) {
      $.ajax({
        url: '<?= base_url(ADMINPATH.'getSlug') ?>',
        type: 'POST',
        data: {
          'keyword': val
        },
        cache: false,
        success: function(response) {
          $('#menu_slug').val(response);
        }
      });
    }

    function change_status(id, table) {
      $.ajax({
        url: '<?= base_url(ADMINPATH.'changeStatus') ?>',
        type: "POST",
        data: {
          'id': id,
          'table': table
        },
        cache: false,
        success: function(response) {
          $('#status_label' + id).html(response);
        }
      });
    }

    function checkDuplicateUser() {
      var id = $('#user_id').val();
      var emailId = $('#user_email').val();
      var user_name = $('#user_name').val();
      if (user_name.trim() === "") {
        toastr.error("Please Enter Name");
        return false;
      } else if (emailId.trim() === "") {
        toastr.error("Please Enter Email Id");
        return false;
      } else {
        Swal.fire({
          title: 'Are you sure?',
          text: 'You Want to Submit',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, submit it!'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: '<?= base_url(ADMINPATH.'save-user') ?>', // Replace this with the actual backend endpoint URL
              type: 'POST',
              data: {
                'id': id,
                'user_email': emailId,
                'user_name': user_name
              },
              cache: false,
              dataType: "json",
              success: function(response) {
                if (response.status === false) {
                  toastr.error(response.message);
                } else if (response.status === true) {
                  $('#submit').addClass('disabled');
                  toastr.success(response.message);
                  setTimeout(function() {
                    window.location.href = response.url;
                  }, 500);
                }
              },
              error: function(xhr, status, error) {
                // Handle error if necessary
                console.error(xhr, status, error);
                Swal.fire("Error occurred. Please try again."); // Show a generic error message
              }
            });
          }
        });
      }
    }

    function checkDuplicateSchool() {
      var id = $('#school_id').val();
      var school_name = $('#school_name').val();
      var school_code = $('#school_code').val();
      if (school_name.trim() === "") {
        toastr.error("Please Enter School Name");
        return false;
      } else if (school_code.trim() === "") {
        toastr.error("Please Enter School Code");
        return false;
      } /*else if (school_code.length < 6) {
        toastr.error("Please Enter Valid School Code");
        return false;
      } */
        else {
        
          Swal.fire({
            title: 'Are you sure?',
            text: 'You Want to Submit',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, submit it!'
          }).then((result) => {
            if (result.isConfirmed) {
              $.ajax({
                url: '<?= base_url(ADMINPATH.'save-school') ?>',
                type: 'POST',
                data: {
                    'id':id,
                    'school_name':school_name,
                    'school_code': school_code
                },
                cache: false,
                dataType: "json",
                success: function(response) {
                   if (response.status === false) {
                  toastr.error(response.message);
                } else if (response.status === true) {
                  $('#submit').addClass('disabled');
                  toastr.success(response.message);
                  setTimeout(function() {
                    window.location.href = response.url;
                  }, 500);
                }
                },
                error: function(xhr, status, error) {
                  // Handle error if necessary
                  console.error(xhr, status, error);
                  Swal.fire("Error occurred. Please try again."); // Show a generic error message
                }
              });
            }
          });
       
      }
    }
    
    function checkDuplicateGrade() {
      var id = $('#grade_id').val();
      var grade = $('#grade').val();
     
      if (grade.trim() === "") {
        toastr.error("Please Enter Grade Value");
        return false;
      } else {
          Swal.fire({
            title: 'Are you sure?',
            text: 'You Want to Submit',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, submit it!'
          }).then((result) => {
            if (result.isConfirmed) {
              $.ajax({
                url: '<?= base_url(ADMINPATH.'save-grade') ?>',
                type: 'POST',
                data: {
                    'id':id,
                    'grade':grade,
                },
                cache: false,
                dataType: "json",
                success: function(response) {
                   if (response.status === false) {
                  toastr.error(response.message);
                } else if (response.status === true) {
                  $('#submit').addClass('disabled');
                  toastr.success(response.message);
                  setTimeout(function() {
                    window.location.href = response.url;
                  }, 500);
                }
                },
                error: function(xhr, status, error) {
                  // Handle error if necessary
                  console.error(xhr, status, error);
                  Swal.fire("Error occurred. Please try again."); // Show a generic error message
                }
              });
            }
          });
      }
    }

    function checkDuplicateTeacher(val, type) {
      let maxLength = 0;
      if (type === "aadhaar_number") {
        maxLength = 12; // Assuming Aadhar number length is 12 characters
      } else if (type === "cms_code") {
        maxLength = 11; // Assuming CMS code length is 11 characters
      }
      if (val.length == maxLength) {
        $.ajax({
          url: '<?= base_url(ADMINPATH.'checkDuplicateTeacher') ?>',
          type: 'POST',
          data: {
            'val': val.trim(),
            'type': type
          },
          cache: false,
          success: function(response) {
            if (response && response === "yes") {
              let message = "";
              if (type === "cms_code") {
                message = "CMS Code Already Exists";
              } else if (type === "aadhaar_number") {
                message = "Aadhaar Number Already Exists";
              }
              swal(message);
              $('#submit').prop('disabled', true);
            } else {
              // Enable submit button if no duplicate found
              $('#submit').prop('disabled', false);
            }
          },
          error: function() {
            // Handle AJAX error if needed
            console.log('Error occurred during AJAX request');
          }
        });
      }
    }

    function deleteRecord(id, table) {
      $.ajax({
        url: '<?= base_url(ADMINPATH .'deleteRecords'); ?>',
        type: "POST",
        data: {
          'id': id,
          'table': table
        },
        cache: false,
        success: function(response) {
          $(document).ready(function() {
            let qparam = (new URL(location)).searchParams;
            getTotalRecordsData(qparam);
          });
        }
      });
    }

    function getTeacherName(val) {
      $('.autocomplete-list').show();
      $('.autocomplete-list').html('');
      $('#hosp_id').val('');
      if (val !== "") {
        $.ajax({
          url: '<?= base_url(ADMINPATH.'getTeacherName') ?>',
          method: 'POST',
          data: {
            val: val
          },
          dataType: "html",
          success: function(response) {
            if (response.length < 20) {
              swal(response);
              return false;
            } else if (response.length > 20) {
              $('.autocomplete-list').html(response);
            }
          }
        });
      }
    }
    
    
    function addDownloadActivity(master_file,name_cms){
     
    $.ajax({
      url: '<?= base_url(ADMINPATH.'addDownloadActivity') ?>',
      type: 'POST',
      data: {
        'master_file': master_file,
        'name_cms':name_cms
      },
      cache: false,
      success: function(response) {
        
      },
      error: function(xhr, status, error) {
        console.error(xhr, status, error);
      }
    });
    }
</script>
</body>
</html>