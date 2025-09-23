@include('include.header')

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

<style>
    hr {
        margin-top: 2px;
        margin-bottom: 5px;
        border: 0;
        border-top: 1px solid rgba(0, 0, 0, .1);
    }

    .form-group {
        margin-bottom: 0px;
    }

    #success {
        background: green;
    }

    #error {
        background: red;
    }

    #warning {
        background: coral;
    }

    #info {
        background: cornflowerblue;
    }

    #question {
        background: grey;
    }

    /* .swal2-popup.swal2-modal.swal2-show {
        width: 100%;
    } */

    .swal2-popup li {
        font-size: 15px;
        margin-bottom: 8px;
    }


    .swal2-popup li {
        font-size: 15px;
        margin-bottom: 8px;
    }

    .swal2-popup li ul{
        margin-left: 15px;
    }
</style>

<section class="">
    <div class="container">
        <ul id="breadcrumb">
            <li><a href="{{ route('dashboard')}}"><span class="fa fa-home"> </span> Dashboard</a></li>
            <li><a href="#"><span class=" fa fa-info-circle"> </span> License Date Change</a></li>

        </ul>
    </div>
</section>
<section class="apply-form">
    <div class="auto-container">
        <div class="wrapper-box">
            <div class="row">
                <div class="col-lg-12 col-12">
                    <div class="apply-card apply-card-info" data-select2-id="14">
                        <div class="apply-card-header" style="background-color: #70c6ef  !important;">
                            <div class="row">
                                <div class="col-6 col-lg-8">
                                    <h5 class="card-title_apply text-black text-left"> License Date Change </h5>
                                </div>

                              

                            </div>

                        </div>
                        <div class="apply-card-body">

                            <form id="license_date_change" enctype="multipart/form-data">
                                <div class="row">

                                    <div class="col-12 col-md-12">
                                        <div class="form-group">
                                            <div class="row pb-4 justify-content-center" style="display:none;">
                                                <div class="col-12 col-md-4 ">      
                                                    <div class="row align-items-center">
                                                        <div class="col-12 col-md-5 ">
                                                            <label for="Name">Select Table </label>
                                                           
                                                        </div>

                                                        <div class="col-12 col-md-7">
                                                           <select class="form-control" name="table">
                                                                <option value="cc">Competency </option>
                                                           </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row align-items-center">
                                                
                                                <div class="col-12 col-md-6 ">
                                                    <div class="row align-items-center">
                                                        <div class="col-12 col-md-5 ">
                                                            <label for="Name">License Number </label>
                                                           
                                                        </div>

                                                        <div class="col-12 col-md-7">
                                                             <select class="form-control" name="license_number" id="license_number">
                                                                  <option value="0">
                                                                           Select License
                                                                        </option>
                                                                   @foreach($licensedates as $license)
                                                                        <option value="{{ $license->license_number }}">
                                                                            {{ $license->license_number }}
                                                                        </option>
                                                                    @endforeach
                                                           </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <div class="row align-items-center">
                                                        <div class="col-12 col-md-3">
                                                            <label for="Name">Expiry Date </label>
                                                           
                                                        </div>

                                                        <div class="col-12 col-md-8 pd-left-40">
                                                 <input type="date" class="form-control"
       name="expires_at" id="expiry">


                                                      
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            @csrf

                                <div class="row mt-5">
                                    <div class="offset-md-5 col-12 col-md-6">
                                        <div class="form-group">
                                         
                                            <button type="submit" class="btn btn-success btn-social">
                                                Save Expiry Date
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div id="draftModal" class="overlay-bg" style="display: none;">
    <div class="otp-modal">
        <h5>Your Application Details Saved Successfully</h5>
        <br>
        <button onclick="closeDraftModal()">OK</button>
    </div>
</div>

</div>

<footer class="main-footer">

@include('include.footer')
    <script>
$(document).ready(function () {


    // Submit form via AJAX
    $('#license_date_change').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            url: "{{ route('update-license-expiry') }}",
            type: "POST",
            data: $(this).serialize(),
            success: function (response) {
                alert(response.message);
            },
            error: function (xhr) {
                alert(xhr.responseJSON?.message || 'Something went wrong');
            }
        });
    });

    // Fetch expiry date when license changes
   $('#license_number').on('change', function () {
    const licenseNumber = $(this).val();
    if (licenseNumber && licenseNumber !== '0') {
        $.get( BASE_URL + `/get-license-expiry/${licenseNumber}`, function (data) {
            if (data.expires_at) {
                // convert to YYYY-MM-DD for date input compatibility
                $('#expiry').val(data.expires_at);
            } else {
                $('#expiry').val('');
            }
        }).fail(function () {
            $('#expiry').val('');
        });
    } else {
        $('#expiry').val('');
    }
});

});

</script>