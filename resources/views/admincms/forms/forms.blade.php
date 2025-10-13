@include('admincms.include.top')
@include('admincms.include.header')
@include('admincms.include.navbar')
<style>
    .offcanvas-header .btn-close {
        background-color: rgb(253.4, 232.5, 232.5);
        font-size: 8px;
        padding: 8px;
        border-radius: 50rem;
        margin-right: 0;
    }

    .offcanvas-header {
        border-bottom: 1px solid #e8e8e8;
    }

    .icon-box {
        padding: 5px 5px;
        border: 1px solid #e1d9d9;
    }

    .custom-box{
        padding: 10px 10px;
        border: 1px solid #d3d3d3;
        border-radius: 11px;
        background: #fff;
        box-shadow: rgba(145, 158, 171, 0.2) 0px 0px 2px 0px, rgba(145, 158, 171, 0.12) 0px 12px 24px -4px;
    }
</style>
<div id="content" class="main-content">
    <div class="layout-px-spacing">

        <div class="middle-content p-0">
            <!--  BEGIN BREADCRUMBS  -->
            <div class="secondary-nav">
                <div class="breadcrumbs-container" data-page-heading="Analytics">
                    <header class="header navbar navbar-expand-sm">
                        <a href="#" class="btn-toggle sidebarCollapse" data-placement="bottom">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu">
                                <line x1="3" y1="12" x2="21" y2="12"></line>
                                <line x1="3" y1="6" x2="21" y2="6"></line>
                                <line x1="3" y1="18" x2="21" y2="18"></line>
                            </svg>
                        </a>
                        <div class="d-flex breadcrumb-content">
                            <div class="page-header">
                                <div class="page-title"></div>
                                <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                                        <li class="breadcrumb-item"><a href="#">Library</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Data</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>

                    </header>
                </div>
            </div>
            <!--  END BREADCRUMBS  -->

            <div class="row layout-top-spacing">
                <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing mb-5">
                    <div class="card">
                        <div class="card-header">
                            <h5>Cerificate / Licence Management</h5>
                        </div>
                        <div class="card-body">
                            <div class="simple-tab">
                                <ul class="nav nav-fill nav-tabs nav-justified" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Competency Certificates / Forms</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Contractor Licence / Forms</button>
                                    </li>
                                </ul>

                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                                        {{-- <p class="mt-3">Etiam iaculis imperdiet maximus. Curabitur at tempus massa, a aliquet ex. Aliquam faucibus sapien ut ex vulputate interdum. Quisque in ex sed eros malesuada vehicula. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Maecenas massa felis, maximus eu risus ut, finibus feugiat neque. In id dictum elit.</p>
                                        <p>Aenean ut aliquet dolor. Integer accumsan odio non dignissim lobortis. Sed rhoncus ante eros, vel ullamcorper orci molestie congue. Phasellus vel faucibus dolor. Morbi magna eros, vulputate eu sem nec, venenatis egestas quam. Maecenas hendrerit mollis eros, eget faucibus quam dignissim vel.</p> --}}

                                        <button class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#addFormModal"><i class="fa fa-plus"></i> Add</button>
                                        <table class="zero-config table dt-table-hover" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>S.No</th>
                                                    <th>Cerificate Name</th>
                                                    <th>Form Name</th>
                                                    <th>Created Date</th>
                                                    <th>Updated Date</th>
                                                    <th>Status</th>
                                                    <th class="no-content">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td>Certificate S</td>
                                                    <td>FORM C</td>
                                                    <td>2011/04/25</td>
                                                    <td>2011/04/25</td>
                                                    <td>Active</td>
                                                    <td>
                                                        <span class="badge badge-primary"><i class="fa fa-edit"></i></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td>Certificate B</td>
                                                    <td>FORM W</td>
                                                    <td>2011/07/25</td>
                                                    <td>2011/07/25</td>
                                                    <td>Active</td>
                                                    <td>
                                                        <span class="badge badge-primary"><i class="fa fa-edit"></i></span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                    </div>
                                    <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                                        {{-- <p class="mt-3">Aliquam at sem nunc. Maecenas tincidunt lacus justo, non ultrices mauris egestas eu. Vestibulum ut ipsum ac eros rutrum blandit in eget quam. Nullam et lobortis nunc. Nam sodales, ante sed sodales rhoncus, diam ipsum faucibus mauris, non interdum nisl lacus vel justo.</p>
                                        <p>Sed imperdiet mi tincidunt mauris convallis, ut ullamcorper nunc interdum. Praesent maximus massa eu varius gravida. Nullam in malesuada enim. Morbi commodo pellentesque velit sodales pretium. Mauris scelerisque augue vel est pulvinar laoreet.</p> --}}


                                        <button class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#addFormModal"><i class="fa fa-plus"></i> Add Form</button>
                                        <table class="zero-config table dt-table-hover" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>S.No</th>
                                                    <th>Cerificate Name</th>
                                                    <th>Form Name</th>
                                                    <th>Created Date</th>
                                                    <th>Updated Date</th>
                                                    <th>Status</th>
                                                    <th class="no-content">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td>EA</td>
                                                    <td>FORM A</td>
                                                    <td>2011/04/25</td>
                                                    <td>2011/04/25</td>
                                                    <td>Active</td>
                                                    <td>
                                                        <span class="badge badge-primary"><i class="fa fa-edit"></span></i>
                                                    </td>
                                                </tr>
                                                {{-- <tr>
                                                    <td>B</td>
                                                    <td>FORM W</td>
                                                    <td>Active</td>
                                                    <td>2011/07/25</td>
                                                    <td>
                                                       <span class="badge badge-primary"><i class="fa fa-edit"></i></span>  
                                                    </td>
                                                </tr> --}}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="widget-content widget-content-area br-8"></div> --}}
                </div>
            </div>
        </div>

    </div>
</div>



<!-- Modal -->
<div class="modal fade" id="addFormModal" tabindex="-1" role="dialog" aria-labelledby="addFormModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addFormModalLabel"><span class="badge badge-primary"><i class="fa fa-wpforms"></i></span> Add Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                  <svg> ... </svg>
                </button>
                {{-- <span><span class="text-danger">(Note:</span> Currently, late fees are applicable only during the last 3 months before the expiry date.)</span> --}}
            </div>
            <form id="feesForm" enctype="multipart/form-data">
            <div class="modal-body">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="inputEmail4" class="form-label">Certificate Name <span class="text-danger">*</span> </label>
                        <input type="text" class="form-control" name="cert_name">
                    </div>
                    <div class="col-md-6">
                        <label for="inputPassword4" class="form-label">Form Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="form_name">
                    </div>
                </div>

                <hr style="border-top: 1px solid #4361ee;">

                <div class="row g-3 mb-3">
                    <div class="col-md-6 custom-box mb-3">
                        <div class="text-primary mb-3">₹ Fees Details</div>
                        <div class="row g-3">
                            <div class="col-md-6 mb-2">
                                <label for="inputEmail4" class="form-label">Fees for Fresh Form <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" class="form-control" id="fresh_fees" name="fresh_fees" min="0" placeholder="0">
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="inputEmail4" class="form-label">Fresh Form Fees As on<span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="fresh_fees_on">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="inputPassword4" class="form-label">Fees for Renewal Form <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" class="form-control" name="renewal_fees" min="0" placeholder="0">
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="inputEmail4" class="form-label">Renewal Form As on<span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="renewal_fees_on">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="inputPassword4" class="form-label">Late Fees for Renewal Form <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" class="form-control" name="latefee_for_renewal" min="0" placeholder="0">
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="inputEmail4" class="form-label">Renewal Late Fees As on<span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="late_renewal_fees_on">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 custom-box mb-3">
                        <div class="text-primary mb-3"><i class="fa fa-clock-o"></i> Durations</div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="inputEmail4" class="form-label">Duration for Fresh Form <span class="text-danger">*</span></label><br>
                                <div class="input-group">
                                    <input type="number" class="form-control fees_amount" name="fresh_form_duration" min="0">
                                    <span class="input-group-text">months</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="inputEmail4" class="form-label">Fresh Form Duration As on<span class="text-danger">*</span></label>
                                <input type="date" name="fresh_form_duration_on" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="inputPassword4" class="form-label">Duration for Renewal Form <span class="text-danger">*</span></label><br>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="renewal_form_duration"  min="0">
                                    <span class="input-group-text">months</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="inputEmail4" class="form-label">Renewal Duration As on<span class="text-danger">*</span></label>
                                <input type="date" name="renewal_duration_on" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="inputPassword4" class="form-label">Duration of Renewal Late Fees<span class="text-danger">*</span></label><br>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="renewal_late_fee_duration"  min="0">
                                    <span class="input-group-text">Days</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="inputEmail4" class="form-label">Renewal Late Duration As on<span class="text-danger">*</span></label>
                                <input type="date" name="renewal_late_fee_duration_on" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row custom-box g-3">
                    <div class="text-primary mb-3"><i class="fa fa-file-pdf-o"></i> Others</div>
                    <div class="col-md-6">
                        <label for="inputEmail4" class="form-label">Instrctions Upload</label>
                        <span class="text-success">(PDF. Max size of 250K)</span>
                        <input type="file" class="form-control" name="instruction_upload">
                    </div>
                    <div class="col-md-6">
                        <label for="inputEmail4" class="form-label d-block mb-2">Status</label>
                        <!-- Switch centered -->
                        <div class="d-inline-block text-center">
                            <div class="switch form-switch-custom switch-inline form-switch-success form-switch-custom dual-label-toggle">
                                <label class="switch-label switch-label-left" for="form-custom-switch-dual-label">In Active</label>
                                <div class="input-checkbox">
                                    <input class="switch-input" type="checkbox" role="switch" name="form_status" checked>
                                </div>
                                <label class="switch-label switch-label-right" for="form-custom-switch-dual-label">Active</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn btn-light-dark" data-bs-dismiss="modal"><i class="flaticon-cancel-12"></i> Discard</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
            </form>
        </div>
    </div>
</div>


@include('admincms.include.footer');

<script>
    $('.zero-config').DataTable({
        "dom": "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
    "<'table-responsive'tr>" +
    "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
        "oLanguage": {
            "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
            "sInfo": "Showing page _PAGE_ of _PAGES_",
            "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
            "sSearchPlaceholder": "Search...",
            "sLengthMenu": "Results :  _MENU_",
        },
        "stripeClasses": [],
        "lengthMenu": [7, 10, 20, 50],
        "pageLength": 10 
    });

    // $(".fees_amount").TouchSpin({
    //     verticalbuttons: true,
    // });
</script>