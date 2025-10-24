$(document).ready(function() {

        $('#feesForm').on('submit', function(e) {
            e.preventDefault();

            let formData = new FormData(this);
            

            // Handle checkboxes explicitly if needed
            $(this).find('input[type=checkbox]').each(function() {
                formData.set(this.name, $(this).is(':checked') ? 1 : 0);
            });

            $.ajax({
                url: BASE_URL + '/admin/licences/addNewForm', 
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

        console.log(cert_name);

        

        // Fill modal inputs
        $('#form_id').val(form_id);
        // $('#openHistoryBtn').attr('data-form_id', '').attr('data-form_id', cert_name);

        
        $('#cert_name').val(cert_name);
        $('#form_name_edit').val(form_name);
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
            url: BASE_URL + '/admin/licences/updateForm', // your Laravel route
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.status == false) {
                    $('#editFormModal').modal('hide');
                    Swal.fire({
                        icon: 'warning',
                        title: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    })
                }else{
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
                }
                
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


    $(document).on('click', '#openHistoryBtn', function() {

        const form_id = $(this).data('id');
        console.log(form_id);
        

        $.ajax({
            url: BASE_URL + '/admin/licences/formHistory', // your Laravel route
            method: 'POST',
            dataType: 'json',
            data:{
                form_id : form_id
            },
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
        $('#viewHistoryModal').modal('show');

        // Close the first modal
        // $('#editFormModal').modal('hide');

        // Open the second modal after the first one is hidden
        // $('#editFormModal').on('hidden.bs.modal', function() {
        //     $('#viewHistoryModal').modal('show');
        //     // Remove this handler so it doesn't trigger again
        //     $(this).off('hidden.bs.modal');
        // });
    });



    // Master category script for add License category

    $("#addCategory").on("submit", function (e) {
        e.preventDefault();

        let cateInput = $("input[name='cate_name']");
        let cateName = $.trim(cateInput.val());
        let errorMsg = $(".error-cate");

        // Reset previous states
        cateInput.css("border", "");
        errorMsg.addClass("d-none");

        // Custom validation
        if (cateName === "") {
            cateInput.css("border", "1px solid red");
            errorMsg.text("Please fill the Category").removeClass("d-none");
            cateInput.focus();
            return false;
        } else if (cateName.length < 3) {
            cateInput.css("border", "1px solid red");
            errorMsg.text("Category name must be at least 3 characters").removeClass("d-none");
            cateInput.focus();
            return false;
        } else if (!/^[a-zA-Z\s]+$/.test(cateName)) {
            cateInput.css("border", "1px solid red");
            errorMsg.text("Category name should contain only letters and spaces").removeClass("d-none");
            cateInput.focus();
            return false;
        }

        // Prepare form data
        let formData = new FormData(this);

        $.ajax({
            url: BASE_URL + "/admin/licences/add_category", // change this to your route
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                console.log(response);
                
                if (response.status) {
                    Swal.fire({
                        icon: "success",
                        title: response.message || "Category created successfully!",
                        showConfirmButton: false,
                        timer: 1500
                    });
                    $("#addCategory")[0].reset();

                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Failed",
                        text: response.message || "Something went wrong!",
                    });
                }
            },
            error: function (xhr) {
                Swal.fire({
                    icon: "error",
                    title: "Server Error",
                    text: xhr.responseJSON?.message || "Please try again later.",
                });
            },
            complete: function () {
                $("#addCategory button[type='submit']")
                    .prop("disabled", false)
                    .text("Create");
            }
        });
    });


    $("#addForms").on("submit", function (e) {
        e.preventDefault();

        let cateInput = $("input[name='cate_name']");
        let cateName = $.trim(cateInput.val());
        let errorMsg = $(".error-cate");

        // Reset previous states
        cateInput.css("border", "");
        errorMsg.addClass("d-none");

        // Custom validation
        if (cateName === "") {
            cateInput.css("border", "1px solid red");
            errorMsg.text("Please fill the Category").removeClass("d-none");
            cateInput.focus();
            return false;
        } else if (cateName.length < 3) {
            cateInput.css("border", "1px solid red");
            errorMsg.text("Category name must be at least 3 characters").removeClass("d-none");
            cateInput.focus();
            return false;
        } else if (!/^[a-zA-Z\s]+$/.test(cateName)) {
            cateInput.css("border", "1px solid red");
            errorMsg.text("Category name should contain only letters and spaces").removeClass("d-none");
            cateInput.focus();
            return false;
        }

        // Prepare form data
        let formData = new FormData(this);

        $.ajax({
            url: BASE_URL + "/admin/licences/add_category", // change this to your route
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                console.log(response);
                
                if (response.status) {
                    $('#addForms').modal('hide');
                    Swal.fire({
                        icon: "success",
                        title: response.message || "Category created successfully!",
                        showConfirmButton: false,
                        timer: 1500
                    });
                    $("#addCategory")[0].reset();

                } else {
                    $('#addForms').modal('hide');
                    Swal.fire({
                        icon: "error",
                        title: "Failed",
                        text: response.message || "Something went wrong!",
                    });
                }
            },
            error: function (xhr) {
                Swal.fire({
                    icon: "error",
                    title: "Server Error",
                    text: xhr.responseJSON?.message || "Please try again later.",
                });
            },
            complete: function () {
                $("#addCategory button[type='submit']")
                    .prop("disabled", false)
                    .text("Create");
            }
        });
    });

    // Optional: remove red border while typing
    $("input[name='cate_name']").on("input", function () {
        $(this).css("border", "");
        $(".error-cate").addClass("d-none");
    });





    //Add Certificate / forms

    // 🔹 1. Auto-uppercase while typing (runs immediately)
    $("input[name='cate_licence_code']").on("input", function () {
        console.log('sdsds');
        this.value = this.value.toUpperCase();
    });

    $("input[name='form_code']").on("input", function () {
        console.log('sdsds');
        this.value = this.value.toUpperCase();
    });

    // 🔹 2. Hide red border + error message as user types (runs immediately)
    $("#addForms").on("input", "input, select", function () {
        $(this).css("border", "");
        $(this).siblings(".error").addClass("d-none").text("");
    });



    $("#addForms").on("submit", function (e) {
        e.preventDefault();
    
        let isValid = true; // flag to track form validity
    
        // Define your fields and rules
        const fields = [
            {
                name: "form_cate",
                selector: "select[name='form_cate']",
                errorSelector: ".error-form_cate",
                validate: function (val) {
                    if (val === "") return "Please choose the category";
                    return null;
                },
            },
            {
                name: "cert_name",
                selector: "input[name='cert_name']",
                errorSelector: ".error-cer_val",
                validate: function (val) {
                    if (val === "") return "Please fill the Certificate / Licence Name";
                    if (!/^[a-zA-Z\s]+$/.test(val)) return "Category name should contain only letters and spaces";
                    return null;
                },
            },
            {
                name: "cate_licence_code",
                selector: "input[name='cate_licence_code']",
                errorSelector: ".error-cert_code",
                validate: function (val) {
                    if (val === "") return "Please fill the Certificate / Licence Code";
                    if (!/^[A-Z0-9]+$/.test(val)) return "Category code should contain only uppercase letters and numbers";
                    return null;
                },
            },
            {
                name: "form_name",
                selector: "input[name='form_name']",
                errorSelector: ".error-form_name",
                validate: function (val) {
                    if (val === "") return "Please fill the Form Name";
                    return null;
                },
            },
            {
                name: "form_code",
                selector: "input[name='form_code']",
                errorSelector: ".error-form_code",
                validate: function (val) {
                    if (val === "") return "Please fill the Form code";
                    return null;
                },
            },
        ];
    
        // Reset previous states
        $(".error").addClass("d-none").text("");
        $("input, select").css("border", "");
    
        // Loop through fields for validation
        fields.forEach((field) => {
            const input = $(field.selector);
            const value = $.trim(input.val());
            const errorMsg = $(field.errorSelector);
            const error = field.validate(value);
    
            // Add "hide error when typing" listener once
            input.off("input change").on("input change", function () {
                $(this).css("border", "");
                errorMsg.addClass("d-none").text("");
            });

    
            if (error) {
                input.css("border", "1px solid red");
                errorMsg.text(error).removeClass("d-none");
                if (isValid) input.focus(); // Focus first invalid field
                isValid = false;
            }
        });
    
        if (!isValid) return false; // stop form submission if validation fails
    
        // Prepare form data
        let formData = new FormData(this);

        console.log(formData);
    
        // Disable button while submitting
        const submitBtn = $("#addForms button[type='submit']");
        submitBtn.prop("disabled", true).text("Creating...");
    
        // AJAX submission
        $.ajax({
            url: BASE_URL + "/admin/licences/add_licence",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.status) {
                    Swal.fire({
                        icon: "success",
                        title: response.message || "Form created successfully!",
                        showConfirmButton: false,
                        timer: 1500,
                    });
                    $("#addForms")[0].reset();
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Failed",
                        text: response.message || "Something went wrong!",
                    });
                }
            },
            error: function (xhr) {
                Swal.fire({
                    icon: "error",
                    title: "Server Error",
                    text: xhr.responseJSON?.message || "Please try again later.",
                });
            },
            complete: function () {
                submitBtn.prop("disabled", false).text("Create");
            },
        });
    });

    payment

});