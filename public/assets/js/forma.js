$("#competency_form_a").on("submit", function (e) {
    //    alert('111');
    // exit;
    e.preventDefault();
    // alert('111');
    // exit;
    let formData = new FormData(this);
    let submitter = e.originalEvent?.submitter;
    let actionType = "submit";
    if ($(submitter).hasClass("save-draft")) {
        actionType = "draft";
    }
    // alert('111');
    // exit;
    $(".error").text(""); // Clear previous errors
    let isValid = true;

    let isValiddraft = true;

    let applicantName = $("#applicant_name").val().trim();
    let businessAddress = $("textarea[name='business_address']").val().trim();

    if (applicantName === "") {
        $("#applicant_name_error").text("Name is required.");
        isValid = false;
            
      
    }

    $("#applicant_name").on("keyup", function () {
        if ($(this).val().trim() !== "") {
            $("#applicant_name_error").text("");
        }
    });

    if (businessAddress === "") {
        $("#business_address_error").text("Business address is required.");
        isValid = false;
            
   
    }

    $("#business_address").on("keyup", function () {
        if ($(this).val().trim() !== "") {
            $("#business_address_error").text("");
        }
    });


        let proprietor_name = $("input[name='proprietor_name[]']").val();
    if (!proprietor_name || proprietor_name.trim() === "") {
        $("#proprietor_name_error").text("Proprietor Name is required.");
        isValid = false;
        // return false;
    }
    $("#proprietor_name").on("keyup", function () {
        if ($(this).val().trim() !== "") {
            $("#proprietor_name_error").text("");
        }
    });
    

    $("input[name='proprietor_name[]']").each(function () {
        let value = $(this).val().trim();
        let errorSpan = $(this).siblings(".proprietor_name_error");

        if (value === "") {
            errorSpan.text("Proprietor Name is required.");
            isValid = false;

            if (actionType === "draft") {
                // 👇 stop draft immediately on first failure
                return false;
            }
        } else {
            errorSpan.text("");
        }
    });

    // If draft and basic checks failed → stop right here
    if (actionType === "draft" && !isValid) {
        return false;
    }



    const allowedTypessize = ["application/pdf"];
    const maxdocSize = 250 * 1024;

    const aadhaarInputFile = document.querySelector(
        'input[type="file"]#aadhaar_doc'
    );
    const aadhaarInputHidden = document.querySelector(
        'input[type="hidden"]#aadhaar_doc'
    );

    let aadhaarFilePresent = false;

    if (aadhaarInputHidden && aadhaarInputHidden.value.trim() !== "") {
        aadhaarFilePresent = true;
    }

    // Aadhaar file validation
    if (aadhaarInputFile && aadhaarInputFile.offsetParent !== null) {
        const file = aadhaarInputFile.files[0];

        if (file) {
            // Check file type and size
            if (!allowedTypessize.includes(file.type)) {
                $(".aadhaar_doc_error").text("Only PDF files are allowed.");
                isValid = false;

                return false;
            } else if (file.size > maxdocSize) {
                $(".aadhaar_doc_error").text(
                    "File size Permitted Only 5 to 250 KB"
                );
                isValid = false;

                return false;
            } else {
                $(".aadhaar_doc_error").text("");
                aadhaarFilePresent = true;
            }
        }
    }

    // PAN

    const panInputFile = document.querySelector(
        'input[type="file"]#pancard_doc'
    );
    const panInputHidden = document.querySelector(
        'input[type="hidden"]#pancard_doc'
    );

    let panFilePresent = false;

    if (panInputHidden && panInputHidden.value.trim() !== "") {
        panFilePresent = true;
    }

    // Aadhaar file validation
    if (panInputFile && panInputFile.offsetParent !== null) {
        const file = panInputFile.files[0];

        if (file) {
            // Check file type and size
            if (!allowedTypessize.includes(file.type)) {
                $("#pancard_doc_error").text("Only PDF files are allowed.");
                isValid = false;

                return false;
            } else if (file.size > maxdocSize) {
                $("#pancard_doc_error").text(
                    "File size Permitted Only 5 to 250 KB"
                );
                isValid = false;

                return false;
            } else {
                $("#pancard_doc_error").text("");
                panFilePresent = true;
            }
        }
    }

    const gstInputFile = document.querySelector('input[type="file"]#gst_doc');
    const gstInputHidden = document.querySelector(
        'input[type="hidden"]#gst_doc'
    );

    let gstFilePresent = false;

    if (gstInputHidden && gstInputHidden.value.trim() !== "") {
        gstFilePresent = true;
    }

    // Aadhaar file validation
    if (gstInputFile && gstInputFile.offsetParent !== null) {
        const file = gstInputFile.files[0];

        if (file) {
            // Check file type and size
            if (!allowedTypessize.includes(file.type)) {
                $("#gst_doc_error").text("Only PDF files are allowed.");
                isValid = false;

                return false;
            } else if (file.size > maxdocSize) {
                $("#gst_doc_error").text(
                    "File size Permitted Only 5 to 250 KB"
                );
                isValid = false;

                return false;
            } else {
                $("#gst_doc_error").text("");
                panFilePresent = true;
            }
        }
    }

    // Clear errors on file change
    $("#aadhaar_doc").on("change", function () {
        $("#aadhaar_doc_error").text("");
    });

    $("#pancard_doc").on("change", function () {
        $("#pancard_doc_error").text("");
    });

    $("#gst_doc").on("change", function () {
        $("#gst_doc_error").text("");
    });

    if (isValiddraft) {
        if (actionType === "draft") {
            submitFormAFinal(formData, actionType);
            return;
        }
    }
    // alert(actionType);

    let authorisedSelected = $(
        'input[name="authorised_name_designation"]:checked'
    ).val();
    if (!authorisedSelected) {
        $("#authorised_name_designation_error").text(
            " select Yes or No for authorised signatory."
        );
        isValid = false;
    } else if (authorisedSelected === "yes") {
        let authName = $("#authorised_name").val().trim();
        let authDesig = $("#authorised_designation").val().trim();

        if (authName === "") {
            $("#authorised_name").after(
                '<span class="error text-danger d-block">Authorised Name is required.</span>'
            );
            isValid = false;
        }

        if (authDesig === "") {
            $("#authorised_designation").after(
                '<span class="error text-danger d-block">Authorised Designation is required.</span>'
            );
            isValid = false;
        }
    }

    $('input[name="authorised_name_designation"]').on("change", function () {
        $("#authorised_name_designation_error").text("");
    });

    // Authorised Name & Designation Inputs
    $("#authorised_name, #authorised_designation").on("keyup", function () {
        $(this).next(".error").remove(); // remove dynamically appended span
    });

    // ------------------ 3. Previous Contractor License ------------------
    let previousSelected = $(
        'input[name="previous_contractor_license"]:checked'
    ).val();

    if (!previousSelected) {
        $("#previous_contractor_license_error").text(
            "Select Yes or No for previous application."
        );
        isValid = false;
    } else if (previousSelected === "yes") {
        let prevAppNo = $("#previous_application_number").val().trim();
        $("#previous_application_number").next(".error").remove();

        if (prevAppNo === "") {
            $("#previous_application_number").after(
                '<span class="error text-danger d-block">Previous License Number is required.</span>'
            );
            isValid = false;
        }
        // ✅ Check if starts with EA
        else if (!/^EA|L/i.test(prevAppNo)) {
            $("#previous_application_number").after(
                '<span class="error text-danger d-block">License number must start with "EA or L".</span>'
            );
            isValid = false;
        }

        let prevAppNoval = $("#previous_application_validity").val().trim();
        $("#previous_application_validity").next(".error").remove();

        if (prevAppNoval === "") {
            $("#previous_application_validity").after(
                '<span class="error text-danger d-block">Previous License Validity is required.</span>'
            );
            isValid = false;
        }
    }

    // Clear errors on input change
    $('input[name="previous_contractor_license"]').on("change", function () {
        $("#previous_contractor_license_error").text("");
    });
    $("#previous_application_number").on("keyup", function () {
        $(this).next(".error").remove();
    });
    $("#previous_application_validity").on("change", function () {
        $(this).next(".error").remove();
    });

    // ---------------- 7 Bank------------------------

    let bankAddress = $("textarea[name='bank_address']").val().trim();
    let bankValidity = $("input[name='bank_validity']").val().trim();
    let bankAmount = $("#bank_amount").val().trim();

    if (bankAddress === "") {
        $("#bank_address_error").text("Bank name and address is required.");
        isValid = false;
    }

    if (bankValidity === "") {
        $("#bank_validity_error").text("Validity period is required.");
        isValid = false;
    }

    if (bankAmount === "") {
        $("#bank_amount_error").text("Amount is required.");
        isValid = false;
    }

    // Clear bank_address error on typing
    $("textarea[name='bank_address']").on("keyup", function () {
        if ($(this).val().trim() !== "") {
            $("#bank_address_error").text("");
        }
    });

    // Clear bank_validity error on typing
    $("input[name='bank_validity']").on("keyup change", function () {
        if ($(this).val().trim() !== "") {
            $("#bank_validity_error").text("");
        }
    });

    // Clear bank_amount error on typing
    $("#bank_amount").on("keyup change", function () {
        if ($(this).val().trim() !== "") {
            $("#bank_amount_error").text("");
        }
    });

    // -----------------8-----------------

    let criminalOffence = $('input[name="criminal_offence"]:checked').val();
    if (!criminalOffence) {
        $("#criminal_offence_error").text(" select Yes or No ");
        isValid = false;
    }

    $('input[name="criminal_offence"]').on("change", function () {
        $("#criminal_offence_error").text("");
    });

    // -----------------9-----------------

    let consent_letter_enclose = $(
        'input[name="consent_letter_enclose"]:checked'
    ).val();
    if (!consent_letter_enclose) {
        $("#consent_letter_enclose_error").text(" select Yes or No ");
        isValid = false;
    }

    $('input[name="consent_letter_enclose"]').on("change", function () {
        $("#consent_letter_enclose_error").text("");
    });

    // -----------------10-----------------

    let cc_holders_enclosed = $(
        'input[name="cc_holders_enclosed"]:checked'
    ).val();
    if (!cc_holders_enclosed) {
        $("#cc_holders_enclosed_error").text(" select Yes or No ");
        isValid = false;
    }

    $('input[name="cc_holders_enclosed"]').on("change", function () {
        $("#cc_holders_enclosed_error").text("");
    });

    // -----------------10 (ii)-----------------

    let purchase_bill_enclose = $(
        'input[name="purchase_bill_enclose"]:checked'
    ).val();
    if (!purchase_bill_enclose) {
        $("#purchase_bill_enclose_error").text(" select Yes or No ");
        isValid = false;
    }

    $('input[name="purchase_bill_enclose"]').on("change", function () {
        $("#purchase_bill_enclose_error").text("");
    });

    // -----------------10-----------------

    let test_reports_enclose = $(
        'input[name="test_reports_enclose"]:checked'
    ).val();
    if (!test_reports_enclose) {
        $("#test_reports_enclose_error").text(" select Yes or No ");
        isValid = false;
    }

    $('input[name="test_reports_enclose"]').on("change", function () {
        $("#test_reports_enclose_error").text("");
    });

    // -----------------11-----------------

    let specimen_signature_enclose = $(
        'input[name="specimen_signature_enclose"]:checked'
    ).val();
    if (!specimen_signature_enclose) {
        $("#specimen_signature_enclose_error").text(" select Yes or No ");
        isValid = false;
    }

    $('input[name="specimen_signature_enclose"]').on("change", function () {
        $("#specimen_signature_enclose_error").text("");
    });

    // -----------------11 (ii)-----------------

    let separate_sheet = $('input[name="separate_sheet"]:checked').val();
    if (!separate_sheet) {
        $("#separate_sheet_error").text(" select Yes or No ");
        isValid = false;
    }

    $('input[name="separate_sheet"]').on("change", function () {
        $("#separate_sheet_error").text("");
    });

    // Aadhaar number validation
    let aadhaar = $("#aadhaar").val().replace(/\s+/g, ""); // remove spaces
    if (aadhaar === "") {
        $("#aadhaar_error").text("Aadhaar number is required.");
        isValid = false;
    } else if (!/^\d{12}$/.test(aadhaar)) {
        $("#aadhaar_error").text("Enter a valid 12-digit Aadhaar number.");
        isValid = false;
    }

    // Aadhaar number live check
    $("#aadhaar").on("keyup", function () {
        let val = $(this).val().replace(/\D/g, ""); // only digits
        // Format as 4-4-4 grouping
        let formatted = val.replace(/(.{4})/g, "$1 ").trim();
        $(this).val(formatted);

        if (val.length === 12 && /^[2-9]/.test(val)) {
            $("#aadhaar_error").text("");
        } else {
            $("#aadhaar_error").text(
                "Enter a valid 12-digit Aadhaar number (should not start with 0 or 1)."
            );
        }
    });

    const pancard = $("#pancard").val().trim().toUpperCase();
    const panPattern = /^[A-Z]{5}[0-9]{4}[A-Z]$/;

    if (pancard === "") {
        $("#pancard_error").text("PAN card number is required.");
        isValid = false;
    } else if (!panPattern.test(pancard)) {
        $("#pancard_error")
            .text("Invalid PAN format (e.g., ABCDE1234F)")
            .css("color", "red");
        isValid = false;
    } else {
        $("#pancard_error").text("");
    }

    $("#pancard").on("keyup", function () {
        const value = $(this).val().toUpperCase();
        $(this).val(value); // Convert input to uppercase automatically

        const panPattern = /^[A-Z]{5}[0-9]{4}[A-Z]$/;

        if (panPattern.test(value)) {
            $("#pancard_error").text("");
        } else {
            $("#pancard_error")
                .text("Invalid PAN format (e.g., ABCDE1234F)")
                .css("color", "red");
        }
    });

    const gst_number = $("#gst_number").val().trim().toUpperCase();

    if (gst_number === "") {
        $("#gst_number_error").text("GST Number is required.");
        isValid = false;
    } else if (!/^[A-Z0-9]{15}$/.test(gst_number)) {
        $("#gst_number_error").text(
            "Enter 15-character alphanumeric GST Number."
        );
        isValid = false;
    } else {
        $("#gst_number_error").text("");
    }

    $("#gst_number").on("keyup", function () {
        const value = $(this).val().toUpperCase();
        $(this).val(value); // Force uppercase

        if (/^[A-Z0-9]{15}$/.test(value)) {
            $("#gst_number_error").text("");
        } else {
            $("#gst_number_error").text(
                "Enter 15-character alphanumeric GST Number."
            );
        }
    });

    // ----------document------------------------

    const allowedTypes = ["application/pdf"];
    const maxSize = 250 * 1024;

    const aadhaarInput = document.getElementById("aadhaar_doc");
    if (aadhaarInput && aadhaarInput.type === "file") {
        const aadhaarFileData = aadhaarInput.files[0];

        if (!aadhaarFileData) {
            $("#aadhaar_doc_error").text("Aadhaar document is Mandatory.");
            isValid = false;
        } else if (!allowedTypes.includes(aadhaarFileData.type)) {
            $("#aadhaar_doc_error").text("Only PDF files are allowed.");
            isValid = false;
        } else if (aadhaarFileData.size > maxSize) {
            $("#aadhaar_doc_error").text(
                "File size Permitted Only 5 to 250 KB"
            );
            isValid = false;
        } else {
            $("#aadhaar_doc_error").text("");
        }
    }

    // PAN Card
    let pancardInput = document.getElementById("pancard_doc");
    if (pancardInput && pancardInput.type === "file") {
        let pancardFileData = pancardInput.files[0];

        if (!pancardFileData) {
            $("#pancard_doc_error").text("Pan Card document is Mandatory.");
            isValid = false;
        } else {
            if (!allowedTypes.includes(pancardFileData.type)) {
                $("#pancard_doc_error").text("Only PDF files are allowed.");
                isValid = false;
            } else if (pancardFileData.size > maxSize) {
                $("#pancard_doc_error").text(
                    "File size Permitted Only 5 to 250 KB"
                );
                isValid = false;
            } else {
                $("#pancard_doc_error").text("");
            }
        }
    }

    // GST
    let gstInput = document.getElementById("gst_doc");
    if (gstInput && gstInput.type === "file") {
        let gstFileData = gstInput.files[0];

        if (!gstFileData) {
            $("#gst_doc_error").text("GST document is Mandatory.");
            isValid = false;
        } else {
            if (!allowedTypes.includes(gstFileData.type)) {
                $("#gst_doc_error").text("Only PDF files are allowed.");
                isValid = false;
            } else if (gstFileData.size > maxSize) {
                $("#gst_doc_error").text(
                    "File size Permitted Only 5 to 250 KB"
                );
                isValid = false;
            } else {
                $("#gst_doc_error").text("");
            }
        }
    }

    // Clear errors on file change
    $("#aadhaar_doc").on("change", function () {
        $("#aadhaar_doc_error").text("");
    });

    $("#pancard_doc").on("change", function () {
        $("#pancard_doc_error").text("");
    });

    $("#gst_doc").on("change", function () {
        $("#gst_doc_error").text("");
    });

    // -------------------end doc--------------------

    // -------------------- 1. Proprietor Validation --------------------
    let proprietorValid = true;

    $(".border.box-shadow-blue, .proprietor-block").each(function (index) {
        const block = $(this);

        // Basic required fields
        const name = block.find('input[name="proprietor_name[]"]');
        const address = block.find('textarea[name="proprietor_address[]"]');
        const age = block.find('input[name="age[]"]');
        const qualification = block.find('input[name="qualification[]"]');
        const fatherName = block.find('input[name="fathers_name[]"]');
        const present_business = block.find('input[name="present_business[]"]');

        if (name.val().trim() === "") {
            block.find("#proprietor_name_error").text("Name is required.");
            proprietorValid = false;
        } else {
            block.find("#proprietor_name_error").text("");
        }

        if (address.val().trim() === "") {
            block
                .find("#proprietor_address_error")
                .text("Address is required.");
            proprietorValid = false;
        } else {
            block.find("#proprietor_address_error").text("");
        }

        if (age.val().trim() === "") {
            block.find("#age_error").text("Age is required.");
            proprietorValid = false;
             isValid = false;
        } else {
            block.find("#age_error").text("");
        }

        if (qualification.val().trim() === "") {
            block
                .find("#qualification_error")
                .text("Qualification is required.");
            proprietorValid = false;
            isValid = false;
        } else {
            block.find("#qualification_error").text("");
        }

        if (fatherName.val().trim() === "") {
            block
                .find("#fathers_name_error")
                .text("Father/Husband's name is required.");
            proprietorValid = false;
            isValid = false;
        } else {
            block.find("#fathers_name_error").text("");
        }

        if (present_business.val().trim() === "") {
            block
                .find("#present_business_error")
                .text("Present business is required.");
            proprietorValid = false;
            isValid = false;
        } else {
            block.find("#present_business_error").text("");
        }

        // --- (v) Competency Certificate Validation ---
        const compSelected = block.find(
            `input[name="competency_certificate_holding[${index}]"]:checked`
        );
        if (compSelected.length === 0) {
            block
                .find(".competency_certificate_holding_error")
                .text("Please select Yes or No.");
            proprietorValid = false;
            isValid = false;
        } else {
            block.find(".competency_certificate_holding_error").text("");
            if (compSelected.val() === "yes") {
                const certNo = block.find(
                    'input[name="competency_certificate_number[]"]'
                );
                const certValid = block.find(
                    'input[name="competency_certificate_validity[]"]'
                );

                if (certNo.val().trim() === "") {
                    block
                        .find(".competency_number_error")
                        .text("Certificate Number is required.");
                    proprietorValid = false;
                } else if (!/^[HBCL]/i.test(certNo.val().trim())) {
                    block
                        .find(".competency_number_error")
                        .text("Certificate Number must start with H, B, C, L.");
                    proprietorValid = false;

                    isValid = false;
                } else {
                    block.find(".competency_number_error").text("");
                }

                if (certValid.val().trim() === "") {
                    block
                        .find(".competency_validity_error")
                        .text("Certificate Validity is required.");
                    proprietorValid = false;
                } else {
                    block.find(".competency_validity_error").text("");
                }
            }
        }

        // --- (vi) Presently Employed ---
        const empSelected = block.find(
            `input[name="presently_employed[${index}]"]:checked`
        );
        if (empSelected.length === 0) {
            block
                .find(".presently_employed_error")
                .text("Please select Yes or No.");
            proprietorValid = false;
            
        } else {
            block.find(".presently_employed_error").text("");
            if (empSelected.val() === "yes") {
                const empName = block.find(
                    'input[name="presently_employed_name[]"]'
                );
                const empAddr = block.find(
                    'textarea[name="presently_employed_address[]"]'
                );

                if (empName.val().trim() === "") {
                    empName
                        .next(".presently_employed_name_error")
                        .text("Employer name is required.");
                    proprietorValid = false;
                } else {
                    empName.next(".presently_employed_name_error").text("");
                }

                if (empAddr.val().trim() === "") {
                    empAddr
                        .next(".presently_employed_address_error")
                        .text("Employer address is required.");
                    proprietorValid = false;
                } else {
                    empAddr.next(".presently_employed_address_error").text("");
                }
            }
        }

        // --- (vii) Previous Experience ---
        const expSelected = block.find(
            `input[name="previous_experience[${index}]"]:checked`
        );
        if (expSelected.length === 0) {
            block
                .find(".previous_experience_error")
                .text("Please select Yes or No.");
            proprietorValid = false;
        } else {
            block.find(".previous_experience_error").text("");
            if (expSelected.val() === "yes") {
                const expName = block.find(
                    'input[name="previous_experience_name[]"]'
                );
                const expAddr = block.find(
                    'textarea[name="previous_experience_address[]"]'
                );
                const expLic = block.find(
                    'input[name="previous_experience_lnumber[]"]'
                );

                const expLicensevalid = block.find(
                    'input[name="previous_experience_lnumber_validity[]"]'
                );

                if (expName.val().trim() === "") {
                    expName
                        .closest(".col-md-5")
                        .find(".previous_experience_name_error")
                        .text("Contractor name is required.");
                    proprietorValid = false;
                } else {
                    expName
                        .closest(".col-md-5")
                        .find(".previous_experience_name_error")
                        .text("");
                }

                if (expAddr.val().trim() === "") {
                    expAddr
                        .closest(".col-md-5")
                        .find(".previous_experience_address_error")
                        .text("Contractor address is required.");
                    proprietorValid = false;
                } else {
                    expAddr
                        .closest(".col-md-5")
                        .find(".previous_experience_address_error")
                        .text("");
                }

                // License Number
                if (expLic.val().trim() === "") {
                    block
                        .find(".previous_experience_lnumber_error")
                        .text("License number is required.");
                    proprietorValid = false;
                } else if (!/^EA|L/i.test(expLic.val().trim())) {
                    block
                        .find(".previous_experience_lnumber_error")
                        .text("License number must start with EA or L.");
                    proprietorValid = false;
                    isValid = false;
                } else {
                    block.find(".previous_experience_lnumber_error").text("");
                }

                // License Validity
                if (expLicensevalid.val().trim() === "") {
                    block
                        .find(".previous_experience_lnumber_validity_error")
                        .text("License Validity is required.");
                    proprietorValid = false;
                } else {
                    block
                        .find(".previous_experience_lnumber_validity_error")
                        .text("");
                }
            }
        }
    });

    $(document).on(
        "keyup change input",
        'input[name="proprietor_name[]"], textarea[name="proprietor_address[]"], input[name="age[]"], input[name="qualification[]"], input[name="fathers_name[]"], input[name="present_business[]"], input[name="competency_certificate_number[]"], input[name="competency_certificate_validity[]"], input[name="presently_employed_name[]"], textarea[name="presently_employed_address[]"], input[name="previous_experience_name[]"], textarea[name="previous_experience_address[]"], input[name="previous_experience_lnumber[]"], input[name="previous_experience_lnumber_validity[]"] ',
        function () {
            $(this).siblings(".error").text(""); // Remove existing error text
            $(this).next(".error").remove(); // Remove appended span errors
        }
    );

    let staffValid = true;
let staffCount = 0;
let licenseNumbers = []; // store CC numbers for duplicate check
let duplicateFound = false;

$(".staff-fields").each(function (index) {
    const name = $(this).find('input[name="staff_name[]"]');
    const qual = $(this).find('select[name="staff_qualification[]"]');
    const ccNum = $(this).find('input[name="cc_number[]"]');
    const ccValid = $(this).find('input[name="cc_validity[]"]');
    const category = $(this).find('select[name="staff_category[]"]');

    const nameVal = name.val().trim();

    // Auto uppercase license on input
    ccNum.on("input", function () {
        this.value = this.value.toUpperCase();
    });

    // Real-time error clearing
    name.on("keyup", function () {
        if ($(this).val().trim() !== "") {
            name.siblings(".error").text("");
        }
    });

    qual.on("change", function () {
        if ($(this).val() !== "") {
            qual.siblings(".error").text("");
        }
    });

    ccNum.on("keyup input", function () {
        if ($(this).val().trim() !== "") {
            ccNum.siblings(".error").text("");
        }
    });

    ccValid.on("keyup change", function () {
        if ($(this).val().trim() !== "") {
            ccValid.siblings(".error").text("");
        }
    });

    category.on("change", function () {
        if ($(this).val() !== "") {
            category.siblings(".error").text("");
        }
    });

    // Validation logic
    if (nameVal === "") {
        name.siblings(".error").text("Name is required.");
        qual.siblings(".error").text("Qualification is required.");
        ccNum.siblings(".error").text("CC Number is required.");
        ccValid.siblings(".error").text("CC Validity is required.");
        category.siblings(".error").text("Category is required.");
        staffValid = false;
    } else {
        staffCount++;

        if (!qual.val()) {
            qual.siblings(".error").text("Qualification is required.");
            staffValid = false;
        }

        const ccVal = ccNum.val().trim().toUpperCase();
        if (ccVal === "") {
            ccNum.siblings(".error").text("CC Number is required.");
            staffValid = false;
        } else {
            // check duplicates
            if (licenseNumbers.includes(ccVal)) {
                duplicateFound = true;
                staffValid = false;
                ccNum.siblings(".error").text("Duplicate CC Number not allowed.");
            } else {
                licenseNumbers.push(ccVal);
            }

            // prefix validation
            const prefix = ccVal.charAt(0);
            if (index === 0 && !["C", "L"].includes(prefix)) {
                ccNum
                    .siblings(".error")
                    .text("First staff's license must start with 'C' or 'L'.");
                staffValid = false;
            } else if (index > 0 && !["C", "B", "H", "L"].includes(prefix)) {
                ccNum
                    .siblings(".error")
                    .text("License must start with 'C', 'B', 'H', or 'L'.");
                staffValid = false;
            } else if (!duplicateFound) {
                ccNum.siblings(".error").text(""); // clear error if valid and not duplicate
            }
        }

        if (ccValid.val().trim() === "") {
            ccValid.siblings(".error").text("CC Validity is required.");
            staffValid = false;
        }

        if (!category.val()) {
            category.siblings(".error").text("Category is required.");
            staffValid = false;
        }
    }
});

// if duplicate found → show SweetAlert
if (duplicateFound) {
    Swal.fire({
        icon: "error",
        title: "Duplicate License Number",
        html: "Two or more staff members have the same CC Number.<br>Please correct it before submitting.",
        width: "450px"
    });
    isValid = false;
}



    // Declaration Checkboxes
    const declaration1Checked = $("#declarationCheckbox").is(":checked");
    const declaration2Checked = $("#declarationCheckbox1").is(":checked");

    if (!declaration1Checked) {
        $("#declaration3_error").text(
            "⚠ Please check this declaration before proceeding."
        );
        isValid = false;
    }

    if (!declaration2Checked) {
        $("#declaration4_error").text(
            "⚠ Please check this declaration before proceeding."
        );
        isValid = false;
    }

    // Clear errors on change
    $("#declarationCheckbox").on("change", function () {
        if ($(this).is(":checked")) {
            $("#declaration3_error").text("");
        }
    });

    $("#declarationCheckbox1").on("change", function () {
        if ($(this).is(":checked")) {
            $("#declaration4_error").text("");
        }
    });
    // console.log({
    //     applicantName,
    //     businessAddress,
    //     aadhaar,
    //     pancard,
    //     gst_number
    // });
    // console.group("🔍 Form Validation Summary");

    // console.log("➡ Applicant Name:", applicantName);
    // console.log("➡ Business Address:", businessAddress);
    // console.log("➡ Aadhaar:", aadhaar);
    // console.log("➡ PAN:", pancard);
    // console.log("➡ GST:", gst_number);
    // console.log("➡ Authorised Signatory:", authorisedSelected);
    // if (authorisedSelected === "yes") {
    //     console.log("    ↪ Authorised Name:", $("#authorised_name").val().trim());
    //     console.log("    ↪ Authorised Designation:", $("#authorised_designation").val().trim());
    // }
    // console.log("➡ Previous License:", previousSelected);
    // if (previousSelected === "yes") {
    //     console.log("    ↪ Previous App Number:", $("#previous_application_number").val().trim());
    // }

    // console.log("➡ Bank Address:", bankAddress);
    // console.log("➡ Bank Validity:", bankValidity);
    // console.log("➡ Bank Amount:", bankAmount);

    // console.log("➡ Criminal Offence:", criminalOffence);
    // console.log("➡ Consent Letter Enclosed:", consent_letter_enclose);
    // console.log("➡ CC Holders Enclosed:", cc_holders_enclosed);
    // console.log("➡ Purchase Bill Enclosed:", purchase_bill_enclose);
    // console.log("➡ Test Reports Enclosed:", test_reports_enclose);
    // console.log("➡ Specimen Signature Enclosed:", specimen_signature_enclose);
    // console.log("➡ Separate Sheet:", separate_sheet);

    // console.log("➡ Declaration 1 Checked:", declaration1Checked);
    // console.log("➡ Declaration 2 Checked:", declaration2Checked);

    // console.log("➡ Proprietor Validation Status:", proprietorValid);
    // console.log("➡ Staff Validation Status:", staffValid);
    // console.log("➡ Total Staff Count:", staffCount);
    // console.log("➡ Form Action Type:", actionType);
    // console.log("✅ Overall Form Valid:", isValid);

    // console.groupEnd();
    // alert(isValid);
    if (isValid) {
        showDeclarationPopupformA(formData);
    }
    // if (staffCount < 4) {
    //     Swal.fire("Warning", "Please add at least 4 valid staff entries.", "warning");
    //     $('html, body').animate({
    //         scrollTop: $("#staff-table").offset().top - 100
    //     }, 800);
    //     return;
    // }

    // if (!proprietorValid) {
    //     Swal.fire("Warning", "Please fill all required fields in Proprietor / Partner section.", "warning");
    //     $('html, body').animate({
    //         scrollTop: $(".border.box-shadow-blue").offset().top - 100
    //     }, 800);
    //     return;
    // }

    // -------------------- 2. Staff Validation --------------------

    // if (!staffValid) {
    //     $('html, body').animate({
    //         scrollTop: $("#staff-table").offset().top - 100
    //     }, 800);
    //     return;
    // }

    // -------------------- 3. Submit Form via AJAX --------------------

    // $.ajax({
    //     url: "{{ route('forma.store') }}",
    //     type: "POST",
    //     data: formData,
    //     contentType: false,
    //     processData: false,
    //     headers: {
    //         "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    //     },
    //     beforeSend: function() {
    //         $(".save-draft, .submit-payment").prop("disabled", true);
    //     },
    //     success: function(response) {
    //         const loginId = response.login_id;
    //         const transactionId = response.transaction_id || 'TXN123456';
    //         const transactionDate = new Date().toLocaleDateString('en-GB');
    //         const applicantName = $("#applicant_name").val() || "Applicant";
    //         const amount = $("#amount").val() || "30000";

    //         if (actionType === "draft") {
    //             Swal.fire("Saved!", "Draft saved successfully!", "success").then(() => {
    //                 window.location.href = "/dashboard";
    //             });
    //         } else {
    //             showDeclarationPopupformA(loginId, loginId, transactionId, transactionDate, applicantName, amount);
    //         }

    //         $(".save-draft, .submit-payment").prop("disabled", false);
    //     },
    //     error: function(xhr) {
    //         $(".save-draft, .submit-payment").prop("disabled", false);
    //         if (xhr.status === 422) {
    //             let errors = xhr.responseJSON.errors;
    //             $.each(errors, function(field, message) {
    //                 $(`#${field}_error`).text(message);
    //             });
    //         } else {
    //             Swal.fire("Error!", "Something went wrong. Try again.", "error");
    //         }
    //     }
    // });
});

function showDeclarationPopupformA(formData) {
    let formName = formData.get("form_name");
    let appl_type = formData.get("appl_type");

    $.ajax({
        url: BASE_URL + "/get-form-instructions",
        method: "GET",
        data: { form_name: formName, appl_type: appl_type },
        success: function (response) {
            if (!response || !response.instructions) {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "No instructions found for this form.",
                });
                return;
            }

            formData.set("fees", response.fees);

            let cleanedInstructions = response.instructions;

            // 1️⃣ Remove all inline colors & styles
            cleanedInstructions = cleanedInstructions.replace(/color\s*:\s*[^;"]+;?/gi, "");
           cleanedInstructions = cleanedInstructions.replace(
                /(Rs\.\s?[0-9,]+\/-)/gi,
                '<span style="color:green; font-weight:600;">$1</span>'
            );

            // cleanedInstructions = cleanedInstructions.replace(/style\s*=\s*"[^"]*"/gi, "");

            // 2️⃣ Remove all <span>, <em>, <u>, <strong> but keep text
            cleanedInstructions = cleanedInstructions.replace(/<\/?(span|em|u|strong)[^>]*>/gi, "");

            // 3️⃣ Specifically format "Rs. xxxx" to green bold
            cleanedInstructions = cleanedInstructions.replace(
                /(Rs\.\s?[0-9,]+\/-)/gi,
                '<span style="color:green; font-weight:600;">$1</span>'
            );

            // 4️⃣ Wrap everything in black text container
            cleanedInstructions = `<div style="color:#000; font-weight:400;">${cleanedInstructions}</div>`;

            // Insert into modal
            const modalEl = document.getElementById("contractorInstructionsModal");
            const instructionsList = modalEl.querySelector(".instruct");
            instructionsList.innerHTML = cleanedInstructions;

            // Reset checkbox + error
            const agreeCheckbox = modalEl.querySelector("#declaration-agree-renew-contractor");
            const errorText = modalEl.querySelector("#declaration-error-renew-contractor");
            agreeCheckbox.checked = false;
            errorText.classList.add("d-none");

            // Proceed button validation
            const proceedBtn = modalEl.querySelector("#proceedPayment");
            proceedBtn.onclick = function () {
                if (!agreeCheckbox.checked) {
                    errorText.classList.remove("d-none");
                    return;
                }
                $("#contractorInstructionsModal").modal("hide");
                submitFormAFinal(formData, "submit");
            };

            // Show modal
            $("#contractorInstructionsModal").modal("show");
        },
        error: function () {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Unable to load instructions. Please try again.",
            });
        },
    });
}



function submitFormAFinal(formData, actionType) {
    formData.append("form_action", actionType);

    let applType = $("#appl_type").val()?.trim();
    let postUrl =
        applType === "R"
            ? BASE_URL + "/forma/storerenewal"
            : BASE_URL + "/forma/store";
     let amount = formData.get("fees") || 0;

    $.ajax({
        url: postUrl,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        beforeSend: function () {
            $(".save-draft, .submit-payment").prop("disabled", true);
        },
        success: function (response) {
            const loginId = response.login_id;
            const transactionId = response.transaction_id || "TXN123456";
            const transactionDate = new Date().toLocaleDateString("en-GB");
            const applicantName = $("#applicant_name").val() || "Applicant";

            if (actionType === "draft") {
                Swal.fire({
                    width: 450,
                    title: "Draft Saved!",
                    html: `Your Application ID is <strong>${loginId}</strong>`,
                    icon: "success",
                }).then(() => {
                    window.location.href = BASE_URL + "/dashboard";
                });
            } else {
    showPaymentInitiationPopupformA(
        loginId,
        transactionId,
        transactionDate,
        applicantName,
        amount
    );
}


            $(".save-draft, .submit-payment").prop("disabled", false);
        },

        error: function (xhr) {
            $(".save-draft, .submit-payment").prop("disabled", false);
            if (xhr.status === 422) {
                let errors = xhr.responseJSON.errors;
                $.each(errors, function (field, message) {
                    $(`#${field}_error`).text(message);
                });
            } else {
                Swal.fire(
                    "Error!",
                    "Fields 1 and 2 are Missing Fill it Properly.",
                    "error"
                );
            }
        },
    });
}

function showPaymentInitiationPopupformA(
    application_id,
    transactionId,
    transactionDate,
    applicantName,
    amount
) {
       Swal.fire({
                            title: "<span style='color:#0d6efd;'>Initiate Payment</span>",
                            html: `
                                <div class="text-start" style="font-size: 14px; padding: 10px 0;">
                                    <table style="width: 100%; font-size: 14px; border-collapse: collapse;">
                                        <tbody>
                                            <tr>
                                                <th style="text-align: left; padding: 6px 10px; width: 50%; color: #555;">Application ID</th>
                                                <td style="text-align: right; padding: 6px 10px; font-weight: 500;">${application_id}</td>
                                            </tr>
                                            <tr>
                                                <th style="text-align: left; padding: 6px 10px; color: #555;">Transaction ID</th>
                                                <td style="text-align: right; padding: 6px 10px; font-weight: 500;">${transactionId}</td>
                                            </tr>
                                            <tr>
                                                <th style="text-align: left; padding: 6px 10px; color: #555;">Date</th>
                                                <td style="text-align: right; padding: 6px 10px; font-weight: 500;">${transactionDate}</td>
                                            </tr>
                                            <tr>
                                                <th style="text-align: left; padding: 6px 10px; color: #555;">Applicant Name</th>
                                                <td style="text-align: right; padding: 6px 10px; font-weight: 500;">${applicantName}</td>
                                            </tr>
                                            <tr>
                                                <th style="text-align: left; padding: 10px; color: #333;">Amount</th>
                                                <td style="text-align: right; padding: 10px; font-weight: bold; color: #0d6efd;">Rs. ${amount} /-</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            `,
                            icon: "info",
                            iconHtml: '<i class="swal2-icon" style="font-size: 1 em">ℹ️</i>',
                            width: '450px',
                            showCancelButton: true,
                            confirmButtonText: '<span class="btn btn-primary px-4 pr-4">Pay Now</span>',
                            cancelButtonText: '<span class="btn btn-danger px-4">Cancel</span>',
                            showCloseButton: true,
                            customClass: {
                                popup: 'swal2-border-radius',
                                actions: 'd-flex justify-content-around mt-3',
                            },
                            buttonsStyling: false
                        }).then((result) => {
        if (result.isConfirmed) {
            // Simulate payment success
            setTimeout(() => {
                const apiUrl = BASE_URL.replace(/\/$/, "");
                $.post(
                    apiUrl + "/update-payment-status",
                    {
                        application_id: application_id,
                        payment_status: "paid",
                        _token: $('meta[name="csrf-token"]').attr("content"),
                    },
                    function () {
                        showPaymentSuccessPopupformA(
                            application_id,
                            transactionId,
                            transactionDate,
                            applicantName,
                            amount
                        );
                    }
                );
            }, 1000);
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            // Mark as draft if cancelled
            $.ajax({
                url: BASE_URL + "/update-payment-status",
                method: "POST",
                data: {
                    application_id: application_id,
                    payment_status: "draft",
                    _token: $('meta[name="csrf-token"]').attr("content"),
                },
                success: function () {
                    Swal.fire({
                        width: 450,
                        title: "Saved as Draft",
                        text: "You can resume payment later.",
                        icon: "info",
                        confirmButtonText: "OK",
                    }).then(() => {
                        window.location.href = BASE_URL + "/dashboard";
                    });
                },
                error: function (xhr) {
                    console.error(
                        "Failed to update payment status:",
                        xhr.responseText
                    );
                },
            });
        }
    });
}
function showPaymentSuccessPopupformA(
    application_id,
    transactionId,
    transactionDate,
    applicantName,
    amount
) {
   Swal.fire({
        title: `<h3 style="color:#198754; font-size:1.5rem;">Payment Successful!</h3>`,
        html: `
        <div style="font-size: 14px; text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: flex-start;">
            <div style="display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; max-width: 90%; margin: 0 auto;">
                <div style="
                    display: grid;
                    grid-template-columns: auto 1fr;
                    gap: 7px 50px;
                    font-size: 14px;
                    max-width: 350px;
                    border-right:2px solid #0d6efd;
                    padding: 0px 15px;
                ">
                    <div style="font-weight: bold;">Application ID:</div>
                    <div style="word-break: break-word;">${application_id}</div>

                    <div style="font-weight: bold;">Transaction ID:</div>
                    <div style="word-break: break-word;">${transactionId}</div>

                    <div style="font-weight: bold;">Transaction Date:</div>
                    <div>${transactionDate}</div>

                    <div style="font-weight: bold;">Applicant Name:</div>
                    <div>${applicantName}</div>

                    <div style="font-weight: bold;">Amount Paid:</div>
                    <div>${amount}</div>
                </div>
                <div style="min-width: 200px; text-align: center;">
                    <p><strong>Download Your Payment Receipt:</strong></p>
                    <button class="btn btn-info btn-sm mb-2" onclick="paymentreceipt('${application_id}')">
                        <i class="fa fa-file-pdf-o text-danger"></i> 
                        <i class="fa fa-download text-danger"></i>
                        Download Receipt
                    </button>
                    <p class="mt-2"><strong>Download Your Application PDF:</strong></p>
                    <button class="btn btn-primary btn-sm me-1" onclick="downloadPDFformA('${application_id}')">English PDF</button>
                </div>
            </div>
        </div>
        `,
        width: '50%',
        customClass: {
            popup: 'swal2-border-radius p-3'
        },
        confirmButtonText: "Go to Dashboard",
        confirmButtonColor: "#0d6efd",
        allowOutsideClick: true,
        allowEscapeKey: true,
        showCloseButton: true,
        didOpen: () => {
            const iconEl = document.querySelector('.swal2-icon');
            if (iconEl) iconEl.style.display = 'none';

            const popup = document.querySelector('.swal2-popup');
            if (popup) {
                popup.style.marginTop = '10px';
                popup.style.padding = '10px 20px';
            }

            const container = document.querySelector('.swal2-container');
            if (container) {
                container.style.alignItems = 'flex-start';
                container.style.paddingTop = '20px';
            }
        },
        willClose: () => {
            window.location.href = BASE_URL + '/dashboard';
        }
    });
}


function paymentreceipt(application_id) {
    window.open(`payment-receipt/${application_id}`, "_blank");
}

// Open Application PDF in New Tab
function downloadPDFformA(loginId) {
    alert(loginId);
    return; // stop execution after showing alert
    let login_id = loginId;
    let url = BASE_URL + `/generatea-pdf/${login_id}`;
    window.open(url, "_blank");
}



$("#closePopup").on("click", function () {
    $("#pdfPopup").fadeOut(function () {
        window.location.href = BASE_URL + "/dashboard";
    });
});
// -------------------------------------------------------------------------------------formA end--------------

// ---------------verify formA license---------------
function verifyCompetencyCertificate(e, btn) {
    e.preventDefault();

    const $parent = $(btn).closest(".row");
    let licenseNumber = $parent.find(".competency_number").val().trim();
    const date = $parent.find(".competency_validity").val().trim();
    const resultBox = $parent.find(".competency_verify_result");
    const statusInput = $parent.find(".proprietor_cc_verify"); // fixed selector

    const prefixPattern = /^(WH|C|B|L)/i;

    if (!licenseNumber || !date) {
        resultBox.text("⚠️ Enter license number and date.");
        statusInput.val("0");
        return;
    }

    // hide the blade Valid/Invalid block
    $parent.find(".license-status").hide();

    if (!prefixPattern.test(licenseNumber)) {
        resultBox.html(
            `<span class="text-danger">⚠️ License number must start with WH, C, or B, L.</span>`
        );
        statusInput.val("0");
        return;
    }

    // Remove prefix (if needed by backend)
    licenseNumber = licenseNumber.replace(/^(WH|C|B)/i, "");

    resultBox.html(`<span class="text-info">Verifying...</span>`);

    $.ajax({
        url: BASE_URL + "/verifylicenseformAccc",
        method: "POST",
        data: {
            license_number: licenseNumber,
            date: date,
            _token: $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            if (response.exists) {
                resultBox.html(
                    `<span class="text-success">&#10004; Valid License.</span>`
                );
                statusInput.val("1"); // append 1 for valid
            } else {
                resultBox.html(
                    `<span class="text-danger">&#10060; Invalid License.</span>`
                );
                statusInput.val("0"); // append 0 for invalid
            }
        },
        error: function (xhr) {
            resultBox.html(
                `<span class="text-danger">🚫 Error verifying license. Try again.</span>`
            );
            statusInput.val("0");
            console.error(xhr.responseText);
        },
    });
}

// function verifyCompetencyCertificate(e, btn) {
//     e.preventDefault();

//     const $parent = $(btn).closest('.row');
//     let licenseNumber = $parent.find('.competency_number').val().trim();
//     const date = $parent.find('.competency_validity').val().trim();
//     const resultBox = $parent.find('.competency_verify_result');
//     const statusInput = $parent.find('.competency_status'); // hidden input

//     const prefixPattern = /^(WH|C|B)/i;

//     if (!licenseNumber || !date) {
//         resultBox.text('⚠️ Enter license number and date.');
//         statusInput.val("0");
//         return;
//     }

//     $(btn).closest('.row').find('.license-status').hide();

//     if (!prefixPattern.test(licenseNumber)) {
//         resultBox.html(`<span class="text-danger">⚠️ License number must start with WH, C, or B.</span>`);
//         statusInput.val("0");
//         return;
//     }

//     // Remove prefix (optional based on your backend logic)
//     licenseNumber = licenseNumber.replace(/^(WH|C|B)/i, '');

//     resultBox.html(`<span class="text-info">Verifying...</span>`);

//     $.ajax({
//         url: "/verifylicenseformAccc",
//         method: 'POST',
//         data: {
//             license_number: licenseNumber,
//             date: date,
//             _token: $('meta[name="csrf-token"]').attr('content')
//         },
//         success: function(response) {
//             if (response.exists) {
//                 resultBox.html(`<span class="text-success">&#10004; Valid verified.</span>`);
//                 statusInput.val("1");
//             } else {
//                 resultBox.html(`<span class="text-danger">&#10060; Invalid found.</span>`);
//                 statusInput.val("0");
//             }
//         },
//         error: function(xhr) {
//             resultBox.html(`<span class="text-danger">🚫 Error verifying license. Try again.</span>`);
//             statusInput.val("0");
//             console.error(xhr.responseText);
//         }
//     });
// }

function verifyCompetencyCertificateincrease(e, btn) {
    e.preventDefault();

    const $parent = $(btn).closest(`[class^="competency-fields-"]`);
    let licenseNumber = $parent.find(".competency_number").val().trim();
    const date = $parent.find(".competency_validity").val().trim();
    const resultBox = $parent.find(".competency_verify_result");
    const statusInput = $parent.find(".competency_status"); //  Hidden input box to store status (1/0)

    const prefixPattern = /^(WH|C|B)/i;

    if (!licenseNumber || !date) {
        resultBox.text("⚠️ Enter license number and date.");
        statusInput.val("0"); //  Not verified
        return;
    }

    if (!prefixPattern.test(licenseNumber)) {
        resultBox.html(
            `<span class="text-danger">⚠️ License number must start with WH, C, or B.</span>`
        );
        statusInput.val("0"); //  Not verified
        return;
    }

    // Strip prefix
    licenseNumber = licenseNumber.replace(/^(WH|C|B)/i, "");

    resultBox.html(`<span class="text-info">Verifying...</span>`);

    $.ajax({
        url: BASE_URL + "/verifylicenseformAccc",
        method: "POST",
        data: {
            license_number: licenseNumber,
            date: date,
            _token: $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            if (response.exists) {
                resultBox.html(
                    `<span class="text-success">&#10004; Valid License.</span>`
                );
                statusInput.val("1");
            } else {
                resultBox.html(
                    `<span class="text-danger">&#10060; Invalid License.</span>`
                );
                statusInput.val("0");
            }
        },
        error: function (xhr) {
            resultBox.html(
                `<span class="text-danger">🚫 Error verifying license. Try again.</span>`
            );
            statusInput.val("0");
            console.error(xhr.responseText);
        },
    });
}

// ----------------------------------------
function verifyeaCertificateincrease(e, btn) {
    e.preventDefault();

    const $parent = $(btn).closest(".row");
    const licenseNumberInput = $parent.find(".ea_license_number_index");
    const dateInput = $parent.find(".ea_license_validity_index");
    const resultBox = $parent.find(".verifyeaincrease_result");
    const statusInput = $parent.find(".contactor_license_verify"); //  Actual input field for status

    let licenseNumber = licenseNumberInput.val().trim();
    const date = dateInput.val().trim();

    const prefixPattern = /^(EA|L)/i;

    if (!licenseNumber || !date) {
        resultBox.text("⚠️ Enter license number and date.");
        statusInput.val("0");
        return;
    }

    if (!prefixPattern.test(licenseNumber)) {
        resultBox.html(
            `<span class="text-danger">⚠️ License number must start with EA or L.</span>`
        );
        statusInput.val("0");
        return;
    }

    licenseNumber = licenseNumber.replace(/^EA/i, "");
    resultBox.html(`<span class="text-info">Verifying...</span>`);

    $.ajax({
        url: BASE_URL + "/verifylicenseformAea_appl",
        method: "POST",
        data: {
            license_number: licenseNumber,
            date: date,
            _token: $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            if (response.exists) {
                resultBox.html(
                    `<span class="text-success">&#10004; Valid License.</span>`
                );
                statusInput.val("1");
            } else {
                resultBox.html(
                    `<span class="text-danger">&#10060; Invalid License.</span>`
                );
                statusInput.val("0");
            }
        },
        error: function (xhr) {
            resultBox.html(
                `<span class="text-danger">🚫 Error verifying license. Try again.</span>`
            );
            statusInput.val("0");
            console.error(xhr.responseText);
        },
    });
}

function verifyeaCertificate(e, btn) {
    e.preventDefault();

    const $parent = $(btn).closest(".row");
    let licenseNumber = $parent.find(".ea_license_number").val().trim();
    const date = $parent.find(".ea_validity").val().trim();
    const resultBox = $parent.find(".competency_verifyea_result");
    const statusInput = $parent.find(".proprietor_contractor_verify"); // fixed to match hidden input

    const prefixPattern = /^(EA|L)/i;

    // ✅ Hide the license-status block immediately on verify click
    $parent.find(".license-status").hide();

    if (!licenseNumber || !date) {
        resultBox.text("⚠️ Enter license number and date.");
        statusInput.val("0");
        return;
    }

    if (!prefixPattern.test(licenseNumber)) {
        resultBox.html(
            `<span class="text-danger">⚠️ License number must start with EA Or LA.</span>`
        );
        statusInput.val("0");
        return;
    }

    // Remove prefix for backend if required
    licenseNumber = licenseNumber.replace(/^EA/i, "");
    resultBox.html(`<span class="text-info">Verifying...</span>`);

    $.ajax({
        url: BASE_URL + "/verifylicenseformAea_appl",
        method: "POST",
        data: {
            license_number: licenseNumber,
            date: date,
            _token: $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            if (response.exists) {
                resultBox.html(
                    `<span class="text-success">&#10004; Valid License.</span>`
                );
                statusInput.val("1"); // ✅ Mark as verified
            } else {
                resultBox.html(
                    `<span class="text-danger">&#10060; Invalid License.</span>`
                );
                statusInput.val("0");
            }
        },
        error: function (xhr) {
            resultBox.html(
                `<span class="text-danger">🚫 Error verifying license. Try again Check Date Properly.</span>`
            );
            statusInput.val("0");
            console.error(xhr.responseText);
        },
    });
}

// function verifyeaCertificate(e, btn) {
//     e.preventDefault();

//     const $parent = $(btn).closest('.row');
//     let licenseNumber = $parent.find('.ea_license_number').val().trim();
//     const date = $parent.find('.ea_validity').val().trim();
//     const resultBox = $parent.find('.competency_verifyea_result');
//     const statusInput = $parent.find('.contactor_license_verify'); // Hidden input

//     const prefixPattern = /^EA/i;

//     if (!licenseNumber || !date) {
//         resultBox.text('⚠️ Enter license number and date.');
//         statusInput.val("0");
//         return;
//     }

//     if (!prefixPattern.test(licenseNumber)) {
//         resultBox.html(`<span class="text-danger">⚠️ License number must start with EA.</span>`);
//         statusInput.val("0");
//         return;
//     }

//     licenseNumber = licenseNumber.replace(/^EA/i, '');
//     resultBox.html(`<span class="text-info">Verifying...</span>`);

//     $.ajax({
//         url: "/verifylicenseformAea_appl",
//         method: 'POST',
//         data: {
//             license_number: licenseNumber,
//             date: date,
//             _token: $('meta[name="csrf-token"]').attr('content')
//         },
//         success: function(response) {
//             if (response.exists) {
//                 resultBox.html(`<span class="text-success">&#10004; Valid License .</span>`);
//                 statusInput.val("1"); // ✅ Change the value in the input box
//             } else {
//                 resultBox.html(`<span class="text-danger">&#10060; Invalid License .</span>`);
//                 statusInput.val("0");
//             }
//         },
//         error: function(xhr) {
//             resultBox.html(`<span class="text-danger">🚫 Error verifying license. Try again.</span>`);
//             statusInput.val("0");
//             console.error(xhr.responseText);
//         }
//     });
// }


// ----recent added---------------------
function verifyeaCertificateprevoius(e, btn) {
    e.preventDefault();

    const $parent = $(btn).closest(".previous-license-fields");
    let licenseNumber = $parent.find(".previous_application_number").val().trim();
    const date = $parent.find(".previous_application_validity").val().trim();
    const resultBox = $parent.find("#verifyea_result");
    const hiddenInput = $parent.find(".previous_contractor_license_verify");

    $parent.find(".license-status").hide();
    const prefixPattern = /^(EA|L)/i;

    if (!licenseNumber || !date) {
        resultBox.text("⚠️ Enter license number and date.");
        hiddenInput.val(""); // keep null on missing input
        return;
    }

    if (!prefixPattern.test(licenseNumber)) {
        resultBox.html(`<span class="text-danger">⚠️ License number must start with EA or L.</span>`);
        hiddenInput.val("0"); // invalid
        return;
    }

    // Normalize EA prefix
    licenseNumber = licenseNumber.replace(/^EA/i, "");
    resultBox.html(`<span class="text-info">Verifying...</span>`);

    $.ajax({
        url: BASE_URL + "/verifylicenseformAea_appl",
        method: "POST",
        data: {
            license_number: licenseNumber,
            date: date,
            _token: $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            if (response.exists) {
                resultBox.html(`<span class="text-success"><i class="fa fa-check"></i> Valid License</span>`);
                hiddenInput.val("1"); // ✅ valid
            } else {
                resultBox.html(`<span class="text-danger">&#10060; Invalid License.</span>`);
                hiddenInput.val("0"); // ❌ invalid
            }
        },
        error: function (xhr) {
            resultBox.html(`<span class="text-danger">🚫 Error verifying license. Try again.</span>`);
            hiddenInput.val("0"); // mark invalid on error
            console.error(xhr.responseText);
        },
    });
}

// -----------------staff recent certificate check------------------------
function validatestaffcertificate(e, btn) {
    e.preventDefault();

    const $row = $(btn).closest("tr");
    // ✅ Hide backend-rendered status immediately
    $row.find(".license-status").hide();

    const $tableBody = $row.closest("tbody");
    const index = $tableBody.find("tr").index($row);

    let licenseNumber = $row.find(".cc_number").val().trim();
    const date = $row.find(".cc_validity").val().trim();
    const resultBox = $row.find(".competency_verify_result");
    const hiddenInput = $row.find(".staff_cc_verify");

    if (!licenseNumber || !date) {
        resultBox.html(
            `<span class="text-danger">⚠️ Enter license number and validity date.</span>`
        );
        hiddenInput.val("0");
        return;
    }

    // ✅ First row rule
    if (index === 0) {
        const startsWithC = /^C|LC/i.test(licenseNumber);
        if (!startsWithC) {
            resultBox.html(
                `<span class="text-danger">⚠️ First row license must start with 'C' or 'LC'.</span>`
            );
            hiddenInput.val("0");
            return;
        }
    } else {
        const prefixPattern = /^(H|C|B|LH|LB|LC)/i;
        if (!prefixPattern.test(licenseNumber)) {
            resultBox.html(
                `<span class="text-danger">⚠️ License must start with H, C, B or L.</span>`
            );
            hiddenInput.val("0");
            return;
        }
    }

    const strippedNumber = licenseNumber.replace(/^(WH|C|B|L|H)/i, "");
    resultBox.html(`<span class="text-info">Verifying...</span>`);

    const ajaxUrl = index === 0 ? "/verifylicensecc_slicense" : "/verifylicenseformAccc";

    $.ajax({
        url: BASE_URL + ajaxUrl,
        method: "POST",
        data: {
            license_number: strippedNumber,
            date: date,
            _token: $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            if (response.exists) {
                resultBox.html(
                    `<span class="text-success small"><i class="fa fa-check"></i> Valid License</span>`
                );
                hiddenInput.val("1"); // ✅ valid
            } else {
                resultBox.html(
                    `<span class="text-danger"> Invalid License.</span>`
                );
                hiddenInput.val("0"); // ❌ invalid
            }
        },
        error: function (xhr) {
            resultBox.html(
                `<span class="text-danger">🚫 Error verifying license. Try again.</span>`
            );
            hiddenInput.val("0");
            console.error(xhr.responseText);
        },
    });
}

