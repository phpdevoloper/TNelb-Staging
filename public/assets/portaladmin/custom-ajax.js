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
                        Swal.fire({
                            icon: 'success',
                            title: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $('#feesForm')[0].reset();
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








});