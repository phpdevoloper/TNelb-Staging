$(document).ready(function() {

        $('#feesForm').on('submit', function(e) {
            e.preventDefault();

            let formData = new FormData(this);
            

            // Handle checkboxes explicitly if needed
            $(this).find('input[type=checkbox]').each(function() {
                formData.set(this.name, $(this).is(':checked') ? 1 : 0);
            });


            // console.log(Object.fromEntries(formData.entries()));
            // console.log(BASE_URL +'/admin/addNewForm');
            // return false;

            $.ajax({
                url: BASE_URL + '/admin/forms/addNewForm', 
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                success: function(response) {
                    if (response.status == 'success') {
                        $('#addFormModal').modal('hide');
                        $('#feesForm')[0].reset();
                        Swal.fire({
                            icon: 'success',
                            title: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            // Refresh the page after the alert closes
                            location.reload();
                        });
                    } else {
                         Swal.fire({
                            icon: 'error',
                            title: response.message,
                        });
                    }
                },
                error: function(xhr) {
                    let msg = 'An unexpected error occurred';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: msg,
                    });
                    // console.log(msg);
                    // return false;
                    // $(responseSelector).html('<span class="text-danger">' + msg + '</span>');
                    // console.error(xhr.responseText);
                }
            });
        });


    
    // Click to edit

    $(document).on('click', '.editFormBtn', function () {
        // Get all data-* attributes
        const form_id = $(this).data('id');
        const form_name = $(this).data('form_name');
        const cert_name = $(this).data('cert_name');
        const fresh_fee = $(this).data('fresh_form_fees');
        const renewal_fee = $(this).data('renewal_form_fees');
        const late_fee = $(this).data('renewal_late_fees');
        const fresh_fees_on = $(this).data('fresh_fees_on');
        const renewal_fees_on = $(this).data('renewal_fees_on');
        const late_fees_on = $(this).data('renewal_late_fees_on');
        const fresh_duration = $(this).data('fresh_form_duration');
        const renewal_duration = $(this).data('renewal_form_duration');
        const late_duration = $(this).data('renewal_late_fees_duration');

        // Fill modal inputs
        $('#edit_id').val(form_id);
        $('#edit_form_name').val(form_name);
        $('#edit_cert_name').val(cert_name);
        $('#edit_fresh_fee').val(fresh_fee);
        $('#edit_renewal_fee').val(renewal_fee);
        $('#edit_late_fee').val(late_fee);
        $('#edit_fresh_on').val(fresh_fees_on);
        $('#edit_renewal_on').val(renewal_fees_on);
        $('#edit_late_on').val(late_fees_on);
        $('#edit_fresh_duration').val(fresh_duration);
        $('#edit_renewal_duration').val(renewal_duration);
        $('#edit_late_duration').val(late_duration);
    });

});