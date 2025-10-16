$(document).ready(function() {

        $('#feesForm').on('submit', function(e) {
            e.preventDefault();

            let formData = new FormData(this);
            

            // Handle checkboxes explicitly if needed
            $(this).find('input[type=checkbox]').each(function() {
                formData.set(this.name, $(this).is(':checked') ? 1 : 0);
            });

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
        const fresh_duration_on = $(this).data('fresh_form_duration_on');
        const renewal_duration_on = $(this).data('renewal_form_duration_on');
        const late_duration_on = $(this).data('renewal_late_fees_duration_on');
        const fresh_fees_ends_on =  $(this).data('fresh_fees_ends_on');
        const renewal_fees_ends_on =  $(this).data('renewal_fees_ends_on');
        const renewal_late_fees_ends_on =  $(this).data('renewal_late_fees_ends_on');
        const fresh_form_duration_ends_on =  $(this).data('fresh_form_duration_ends_on');
        const renewal_form_duration_ends_on =  $(this).data('renewal_form_duration_ends_on');
        const renewal_late_fees_duration_ends_on =  $(this).data('renewal_late_fees_duration_ends_on');
        const status = $(this).data('form_status');

        

        // Fill modal inputs
        $('#form_id').val(form_id);
        $('#cert_name').val(cert_name);
        $('#form_name').val(form_name);
        $('#fresh_fees').val(fresh_fee);
        $('#fresh_fees_on').val(fresh_fees_on);
        $('#renewal_fees').val(renewal_fee);
        $('#renewal_fees_starts').val(renewal_fees_on);
        $('#latefee_for_renewal').val(late_fee);
        $('#late_renewal_fees_starts').val(late_fees_on);
        $('#freshform_duration').val(fresh_duration);
        $('#freshform_duration_starts').val(fresh_duration_on);
        $('#renewal_form_duration').val(renewal_duration);
        $('#renewal_duration_starts').val(renewal_duration_on);
        $('#renewal_late_fee_duration').val(late_duration);
        $('#renewal_late_fee_duration_starts').val(late_duration_on);
        $('#fresh_fees_ends_on').val(fresh_fees_ends_on);
        $('#renewal_fees_ends_on').val(renewal_fees_ends_on);
        $('#late_renewal_fees_ends_on').val(renewal_late_fees_ends_on);
        $('#freshform_duration_ends').val(fresh_form_duration_ends_on);
        $('#renewal_duration_ends').val(renewal_form_duration_ends_on);
        $('#renewal_late_fee_duration_ends').val(renewal_late_fees_duration_ends_on);

        
        if (status == 1) {
            $('#form_status').prop('checked', true);
        } else {
            $('#form_status').prop('checked', false);
        }

    });

    //Update Form Details

    $('#editForm').on('submit', function (e) {
        e.preventDefault();

        let formData = new FormData(this);
            

        // Handle checkboxes explicitly if needed
        $(this).find('input[type=checkbox]').each(function() {
            formData.set(this.name, $(this).is(':checked') ? 1 : 0);
        });

        $.ajax({
            url: BASE_URL + '/admin/forms/updateForm', // your Laravel route
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                $('#editFormModal').modal('hide');
                $('#editForm')[0].reset();
                Swal.fire({
                    icon: 'success',
                    title: response.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
                
                // Optionally refresh the table here
            },
            error: function (xhr) {
                console.error(xhr.responseText);

                let msg = 'An unexpected error occurred';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: msg,
                    });
            }
        });
    });


    $('#openHistoryBtn').on('click', function() {

        $.ajax({
            url: BASE_URL + '/admin/forms/formHistory', // your Laravel route
            method: 'GET',
            dataType: 'json',
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                // Optionally refresh the table here
                $('#formHistoryTable tbody').html(response.html);
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                let msg = 'An unexpected error occurred';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: msg,
                    });
            }
        });

        // Close the first modal
        $('#editFormModal').modal('hide');

        // Open the second modal after the first one is hidden
        $('#editFormModal').on('hidden.bs.modal', function() {
            $('#viewHistoryModal').modal('show');
            // Remove this handler so it doesn't trigger again
            $(this).off('hidden.bs.modal');
        });
    });



    // Master category script for add License category

   window.addEventListener('load', function() {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('simple-example');
        var invalid = $('.simple-example .invalid-feedback');

       
        



        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function(form) {
        form.addEventListener('submit', function(event) {
             console.log(forms.checkValidity());
        console.log(invalid);
            if (form.checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
                invalid.css('display', 'block');
            } else {

                invalid.css('display', 'none');

                form.classList.add('was-validated');
            }
        }, false);
        });

    }, false);

});