@include('include.header')


<style>
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
        background-color: white;
        width: 300px;
        padding: 20px;
        text-align: center;
        border-radius: 10px;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
    }

    .modal button {
        padding: 10px 20px;
        background-color: #28a745;
        color: white;
        border: none;
        cursor: pointer;
        border-radius: 5px;
        margin-top: 10px;
    }

    .modal button:hover {
        background-color: #218838;
    }
</style>

<style>
    .overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        z-index: 999;
    }

    .popup {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: #fff;
        padding: 30px;
        border-radius: 8px;
        text-align: center;
        z-index: 1000;
        max-width: 400px;
        width: 100%;
    }

    .popup h2 {
        font-size: 24px;
        color: #28a745;
    }

    .popup p {
        font-size: 16px;
        margin-bottom: 20px;
        color: #333;
    }

    .popup .btn {
        background-color: #28a745;
        color: white;
        padding: 10px 20px;
        text-decoration: none;
        font-size: 16px;
        border-radius: 5px;
        cursor: pointer;
    }

    .popup .btn:hover {
        background-color: #218838;
    }
</style>
<!-- <section class="page-title" style="background-image: url(assets/images/slider/slider3.jpg);">
    <div class="auto-container">
        <div class="content-box">
            <div class="content-wrapper">
                <div class="title">
                    <h1 class="text-uppercase">Register</h1>
                </div>
                <ul class="bread-crumb">
                    <li><a href="index.php">Home</a></li>
                    <li>Register </li>

                </ul>
            </div>
        </div>
    </div>
</section> -->

<!-- About section -->

<section class="register-form ">
    <div class="auto-container">
        <div class="wrapper-box">
            <div class="row">
                <div class="col-lg-12 col-12">
                    <div class="card card-info" data-select2-id="14">
                        <div class="card-header" style="background-color: #877e85 !important;">
                            <h4 class="card-title_apply text-white text-center">Applicant Registration / Sign up Form</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="d-none d-sm-block col-md-12 col-12 text-md-right">
                                    <span class="text-primary"><strong><span style="color: red;">*</span> Fields are must to be Filled</strong></span>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col">
                                    <hr>
                                </div>
                            </div>
                            <!-- autocomplete="off" -->
                            <form id="form1" autocomplete="off">
                                @csrf
                                <div class="row">

                                    <div class="col-12 col-md-6">
                                        <div class="row">
                                            <div class="col-12 col-md-5">
                                                <div class="form-group">
                                                    <label class="col-12 col-md-12 " for="Name">Name <span style="color: red;">*</span></label>

                                                </div>
                                            </div>
                                            <div class="col-12 col-md-7">
                                                <input type="text" id="Name" name="Name" class="form-control">
                                                <span id="NameError" class="text-danger"></span>
                                            </div>
                                        </div>

                                        <div class="row pt-4">
                                            <div class="col-12 col-md-5">
                                                <div class="form-group">
                                                    <label class="col-12 col-md-12 " for="Name">Gender <span style="color: red;">*</span></label>

                                                </div>
                                            </div>
                                            <div class="col-12 col-md-7">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <input type="radio" name="gender" value="Male">
                                                        <label for="Male">Male</label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <input type="radio" name="gender" value="Female">
                                                        <label for="Female">Female</label>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <input type="radio" name="gender" value="Transgender">
                                                        <label for="Transgender">Transgender</label>
                                                    </div>
                                                </div>
                                                <span id="GenderError" class="text-danger"></span>

                                            </div>
                                        </div>


                                        <div class="row pt-3">
                                            <div class="col-12 col-md-5">
                                                <div class="form-group">
                                                    <label class="col-12 col-md-12" for="PhoneNo">Mobile number <span style="color: red;">*</span></label>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-7">
                                                <input type="text" id="PhoneNo" name="PhoneNo" class="form-control">
                                                <span id="PhoneNoError" class="text-danger"></span>
                                            </div>
                                        </div>


                                        <div class="row pt-4">
                                            <div class="col-12 col-md-5">
                                                <div class="form-group">
                                                    <label class="col-12 col-md-12" for="PhoneNo">E-mail address </label>

                                                </div>
                                            </div>
                                            <div class="col-12 col-md-7">
                                                <input type="email" id="EmailAddress" name="EmailAddress" class="form-control">
                                                <span id="EmailError" class="text-danger"></span>

                                            </div>
                                        </div>


                                        <div class="row pt-4">
                                            <div class="col-12 col-md-5">
                                                <div class="form-group">
                                                    <label class="col-12 col-md-12" for="aadhaar">Aadhaar Card Number <span style="color: red;">*</span> </label>

                                                </div>
                                            </div>
                                            <div class="col-12 col-md-7">
                                                <input type="text" id="aadhaar" name="aadhaar" class="form-control">
                                                <span id="aadhaarError" class="text-danger"></span>

                                            </div>
                                        </div>

                                        <div class="row pt-4">
                                            <div class="col-12 col-md-5">
                                                <div class="form-group">
                                                    <label class="col-12 col-md-12" for="aadhaar">Pan Card Number <span style="color: red;">*</span> </label>

                                                </div>
                                            </div>
                                            <div class="col-12 col-md-7">
                                                <input type="text" id="pancard" name="pancard" class="form-control">
                                                <span id="pancardError" class="text-danger"></span>

                                            </div>
                                        </div>




                                    </div>

                                    <div class="col-12 col-md-6">

                                        <div class="row ">
                                            <div class="col-12 col-md-3">
                                                <div class="form-group">
                                                    <label class="col-12 col-md-12" for="Address">Address <span style="color: red;">*</span></label>

                                                </div>
                                            </div>
                                            <div class="col-12 col-md-8">

                                                <textarea rows='3' id="Address" name="Address" class="form-control"></textarea>
                                                <span id="AddressError" class="text-danger"></span>

                                            </div>
                                        </div>


                                        <div class="row pt-4">
                                            <div class="col-12 col-md-3">
                                                <div class="form-group">
                                                    <label class="col-12 col-md-12 " for="Address1">Select State</label>

                                                </div>
                                            </div>
                                            <div class="col-12 col-md-8">
                                                <select id="state" name="state" class="form-control">
                                                    <option value="">--- Select State ---</option>
                                                    <option value="Andhra Pradesh">Andhra Pradesh</option>
                                                    <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                                                    <option value="Assam">Assam</option>
                                                    <option value="Bihar">Bihar</option>
                                                    <option value="Chhattisgarh">Chhattisgarh</option>
                                                    <option value="Goa">Goa</option>
                                                    <option value="Gujarat">Gujarat</option>
                                                    <option value="Haryana">Haryana</option>
                                                    <option value="Himachal Pradesh">Himachal Pradesh</option>
                                                    <option value="Jharkhand">Jharkhand</option>
                                                    <option value="Karnataka">Karnataka</option>
                                                    <option value="Kerala">Kerala</option>
                                                    <option value="Madhya Pradesh">Madhya Pradesh</option>
                                                    <option value="Maharashtra">Maharashtra</option>
                                                    <option value="Manipur">Manipur</option>
                                                    <option value="Meghalaya">Meghalaya</option>
                                                    <option value="Mizoram">Mizoram</option>
                                                    <option value="Nagaland">Nagaland</option>
                                                    <option value="Odisha">Odisha</option>
                                                    <option value="Punjab">Punjab</option>
                                                    <option value="Rajasthan">Rajasthan</option>
                                                    <option value="Sikkim">Sikkim</option>
                                                    <option value="Tamil Nadu">Tamil Nadu</option>
                                                    <option value="Telangana">Telangana</option>
                                                    <option value="Tripura">Tripura</option>
                                                    <option value="Uttar Pradesh">Uttar Pradesh</option>
                                                    <option value="Uttarakhand">Uttarakhand</option>
                                                    <option value="West Bengal">West Bengal</option>

                                                    <!-- Union Territories -->
                                                    <option value="Andaman and Nicobar Islands">Andaman and Nicobar Islands</option>
                                                    <option value="Chandigarh">Chandigarh</option>
                                                    <option value="Dadra and Nagar Haveli and Daman and Diu">Dadra and Nagar Haveli and Daman and Diu</option>
                                                    <option value="Lakshadweep">Lakshadweep</option>
                                                    <option value="Delhi">Delhi</option>
                                                    <option value="Puducherry">Puducherry</option>
                                                    <option value="Jammu and Kashmir">Jammu and Kashmir</option>
                                                    <option value="Ladakh">Ladakh</option>
                                                </select>
                                                <span id="StateError" class="text-danger"></span>

                                            </div>
                                        </div>

                                        <div class="row pt-4">
                                            <div class="col-12 col-md-3">
                                                <div class="form-group">
                                                    <label class="col-12 col-md-12 " for="Address1">Select District</label>

                                                </div>
                                            </div>
                                            <div class="col-12 col-md-8">
                                                <select id="district" name="district" class="form-control">
                                                    <option value="" data-select2-id="2">---Select District ---</option>

                                                    <option value="Ariyalur">Ariyalur</option>
                                                    <option value="Chengalpattu">Chengalpattu</option>
                                                    <option value="Chennai">Chennai</option>
                                                    <option value="Coimbatore">Coimbatore</option>
                                                    <option value="Cuddalore">Cuddalore</option>
                                                    <option value="Dharmapuri">Dharmapuri</option>
                                                    <option value="Dindigul">Dindigul</option>
                                                    <option value="Erode">Erode</option>
                                                    <option value="Kancheepuram">Kancheepuram</option>
                                                    <option value="Kanyakumari">Kanyakumari</option>
                                                    <option value="Karur">Karur</option>
                                                    <option value="Krishnagiri">Krishnagiri</option>
                                                    <option value="Madurai">Madurai</option>
                                                    <option value="Nagapattinam">Nagapattinam</option>
                                                    <option value="Namakkal">Namakkal</option>
                                                    <option value="Nilgiris">Nilgiris</option>
                                                    <option value="Perambalur">Perambalur</option>
                                                    <option value="Pudukkottai">Pudukkottai</option>
                                                    <option value="Ramanathapuram">Ramanathapuram</option>
                                                    <option value="Salem">Salem</option>
                                                    <option value="Sivagangai">Sivagangai</option>
                                                    <option value="Tenkasi">Tenkasi</option>
                                                    <option value="Thanjavur">Thanjavur</option>
                                                    <option value="The Nilgiris">The Nilgiris</option>
                                                    <option value="Theni">Theni</option>
                                                    <option value="Tirunelveli">Tirunelveli</option>
                                                    <option value="Tiruppur">Tiruppur</option>
                                                    <option value="Tiruvallur">Tiruvallur</option>
                                                    <option value="Tiruvannamalai">Tiruvannamalai</option>
                                                    <option value="Vellore">Vellore</option>
                                                    <option value="Viluppuram">Viluppuram</option>
                                                    <option value="Virudhunagar">Virudhunagar</option>
                                                </select>
                                                <span id="DistrictError" class="text-danger"></span>
                                            </div>
                                        </div>

                                        <div class="row pt-4">
                                            <div class="col-12 col-md-3">
                                                <div class="form-group">
                                                    <label class="col-12 col-md-12 " for="Pincode">Pincode <span style="color: red;">*</span></label>

                                                </div>
                                            </div>
                                            <div class="col-12 col-md-8">
                                                <input type="text" id="pincode" name="pincode" class="form-control">
                                                <span id="PincodeError" class="text-danger"></span>
                                            </div>
                                        </div>


                                    </div>



                                </div>





                                <div class="row">

                                    <!-- <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <div>&nbsp;</div>
                                            <div class="col-12 col-md-12">
                                                <img alt="Captcha" id="CaptchaImage" src="/Captcha/GetCaptcha" width="150" height="30">
                                                <a id="RefreshCaptcha" class="btn btn-default btn-social-icon"><i class="fa fa-refresh" aria-hidden="true"></i></a>
                                            </div>
                                        </div>
                                    </div> -->
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="offset-md-6 col-12 col-md-6">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary btn-social">
                                                Submit
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <div id="success-popup" class="popup">
                                <h2>Registration Successful!</h2>
                                <!-- <p>Your registration was completed successfully. You can now log in.</p> -->
                                <h6 class="mt-2">Your Login ID will be your Mobile Number</h6>
                                <a href="{{ route('login') }} " class="btn btn-primary log_in mt-2">OK</a>
                            </div>
                            <div id="overlay" class="overlay"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>




<div class="card1 register" style="display: none;">
    <h2>Register</h2>
    <form method="post" action="https://html.tonatheme.com/2021/Governlia/inc/sendemail.php" id="contact-form">
        <div class="row">

            <div class="form-group col-md-12">

                <input type="text" name="name" value="" placeholder="Your Name">
            </div>

        </div>

        <div class="row">
            <div class="form-group col-md-12">
                <input type="email" name="email" value="" placeholder="Enter Email">
            </div>

        </div>

        <div class="row">
            <div class="form-group col-md-12">
                <input type="text" name="mobile" value="" placeholder="Enter Mobile Number">
            </div>

        </div>

        <div class="row">

            <div class="form-group col-md-12">


                <select id="district" name="district">
                    <option value="">Select District</option>
                    <option value="Ariyalur">Ariyalur</option>
                    <option value="Chengalpattu">Chengalpattu</option>
                    <option value="Chennai">Chennai</option>
                    <option value="Coimbatore">Coimbatore</option>
                    <option value="Cuddalore">Cuddalore</option>
                    <option value="Dharmapuri">Dharmapuri</option>
                    <option value="Dindigul">Dindigul</option>
                    <option value="Erode">Erode</option>
                    <option value="Kancheepuram">Kancheepuram</option>
                    <option value="Kanyakumari">Kanyakumari</option>
                    <option value="Karur">Karur</option>
                    <option value="Krishnagiri">Krishnagiri</option>
                    <option value="Madurai">Madurai</option>
                    <option value="Nagapattinam">Nagapattinam</option>
                    <option value="Namakkal">Namakkal</option>
                    <option value="Nilgiris">Nilgiris</option>
                    <option value="Perambalur">Perambalur</option>
                    <option value="Pudukkottai">Pudukkottai</option>
                    <option value="Ramanathapuram">Ramanathapuram</option>
                    <option value="Salem">Salem</option>
                    <option value="Sivagangai">Sivagangai</option>
                    <option value="Tenkasi">Tenkasi</option>
                    <option value="Thanjavur">Thanjavur</option>
                    <option value="The Nilgiris">The Nilgiris</option>
                    <option value="Theni">Theni</option>
                    <option value="Tirunelveli">Tirunelveli</option>
                    <option value="Tiruppur">Tiruppur</option>
                    <option value="Tiruvallur">Tiruvallur</option>
                    <option value="Tiruvannamalai">Tiruvannamalai</option>
                    <option value="Vellore">Vellore</option>
                    <option value="Viluppuram">Viluppuram</option>
                    <option value="Virudhunagar">Virudhunagar</option>
                </select>
            </div>

        </div>


        <button type="submit">Submit</button>
    </form>
</div>

<div id="successModal" class="modal">
    <div class="modal-content">
        <h2>Registration Successful!</h2>
        <p>You have been successfully registered.</p>
        <button id="loginBtn">Login</button>
    </div>
</div>


<footer class="main-footer">
    @include('include.footer')

    <script>
        $("#contact-form").submit(function(e) {

                // console.log('asd');


                e.preventDefault(); // avoid to execute the actual submit of the form.

                // var phone = $("#phone").val();

                var phone = $('input[name="phone"]').val();
                // return false;


                intRegex = /[0-9 -()+]+$/;

                if ((phone.length < 10) || (!intRegex.test(phone))) {
                    alert('Please enter a valid phone number.');
                    return false;
                }


                if (phone.length !== 0) {
                    $('#otp_card').removeAttr("style");


                }
                console.log(phone);
                return false;


        });

        document.addEventListener("DOMContentLoaded", function() {
                let phoneInput = document.getElementById("PhoneNo");
                let phoneError = document.getElementById("PhoneNoError");

                phoneInput.addEventListener("input", function() {
                    // Remove non-digits
                    this.value = this.value.replace(/[^0-9]/g, '');

                    // Limit to 10 digits
                    if (this.value.length > 10) {
                        this.value = this.value.slice(0, 10);
                    }

                    // Live validation
                    if (this.value.length === 10) {
                        if (!/^[6-9]\d{9}$/.test(this.value)) {
                            phoneError.textContent = "Enter a valid 10-digit mobile number starting with 6-9.";
                        } else {
                            phoneError.textContent = "";
                        }
                    } else {
                        phoneError.textContent = "";
                    }
                });
            });
            document.addEventListener("DOMContentLoaded", function() {
                let aadhaarInput = document.getElementById("aadhaar");
                let aadhaarError = document.getElementById("aadhaarError");

                aadhaarInput.addEventListener("input", function() {
                    this.value = this.value.replace(/[^0-9]/g, '');

                    if (this.value.length > 12) {
                        this.value = this.value.slice(0, 12);
                    }

                    if (this.value.length === 12) {
                        aadhaarError.textContent = "";
                    } else if (this.value.length > 0) {
                        aadhaarError.textContent = "Aadhaar number must be exactly 12 digits.";
                    } else {
                        aadhaarError.textContent = "";
                    }
                });
            });

        document.addEventListener("DOMContentLoaded", function() {
            let NameInput = document.getElementById("Name");

            NameInput.addEventListener("input", function() {
                this.value = this.value.replace(/[^A-Za-z\s]/g, ''); // Only letters and spaces
            });
        });

        $(document).ready(function() {

   
        $("#form1").submit(function(event) {
            event.preventDefault();

            // Clear previous error messages
            $("#PhoneNoError").text("");
            $("#EmailError").text("");
            $("#NameError").text("");
            $("#GenderError").text("");
            $("#AddressError").text("");
            $("#StateError").text("");
            $("#DistrictError").text("");
            $("#PincodeError").text("");
            $("#aadhaarError").text("");
            $("#pancardError").text("");

            let name = $("#Name").val().trim();
            let gender = $("input[name='gender']:checked").val();
            let phone = $("#PhoneNo").val().trim();
            let email = $("#EmailAddress").val().trim();
            let address = $("#Address").val().trim();
            let state = $("#state").val();
            let district = $("#district").val();
            let pincode = $("#pincode").val().trim();

            let aadhaar = $("#aadhaar").val().trim();
            let pancard = $("#pancard").val().trim();




            let formData = {
                _token: "{{ csrf_token() }}",
                Name: name,
                gender: gender,
                PhoneNo: phone,
                EmailAddress: email,
                Address: address,
                state: state,
                district: district,
                pincode: pincode,
                aadhaar: aadhaar,
                pancard: pancard,
            };

            $.ajax({
                type: "POST",
                url: "{{ route('register.store') }}",
                data: formData,
                success: function(response) {
                    if (response.success) {
                        $("#login-id-display").text(response.login_id);
                        $("#success-popup").fadeIn();
                        $("#overlay").fadeIn();
                    }
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        let errors = xhr.responseJSON.errors;

                        if (errors.Name) {
                            $("#NameError").text(errors.Name[0]);
                        }
                        if (errors.gender) {
                            $("#GenderError").text(errors.gender[0]);
                        }
                        if (errors.PhoneNo) {
                            $("#PhoneNoError").text(errors.PhoneNo[0]);
                        }
                        if (errors.EmailAddress) {
                            $("#EmailError").text(errors.EmailAddress[0]);
                        }
                        if (errors.Address) {
                            $("#AddressError").text(errors.Address[0]);
                        }
                        if (errors.state) {
                            $("#StateError").text(errors.state[0]);
                        }
                        if (errors.district) {
                            $("#DistrictError").text(errors.district[0]);
                        }
                        if (errors.pincode) {
                            $("#PincodeError").text(errors.pincode[0]);
                        }

                        if (errors.aadhaar) {
                            $("#aadhaarError").text(errors.aadhaar[0]);
                        }
                        if (errors.pancard) {
                            $("#pancardError").text(errors.pancard[0]);
                        }
                    }
                }
            });
        });
        });

    </script>