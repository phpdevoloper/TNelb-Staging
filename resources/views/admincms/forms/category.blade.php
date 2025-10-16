@include('admincms.include.top')
@include('admincms.include.header')
@include('admincms.include.navbar')
<style>
   
</style>
<div id="content" class="main-content">
    <div class="layout-px-spacing">

        <div class="middle-content p-0">
            <div class="page-meta">
                <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Licences Management</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Category</li>
                    </ol>
                </nav>
            </div>
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
                                        <li class="breadcrumb-item"><a href="#">Content Management System for TNELB</a></li>
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
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <form id="addCategory" class="simple-example" novalidate>
                                        <div class="mb-2">
                                            <label for="inputEmail4" class="form-label">Category Name <span class="text-danger">*</span> </label>
                                            <input type="text" class="form-control" name="cate_name">
                                            <div class="invalid-feedback"> Please fill the Category </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Create</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-body">
                                    <table id="style-2" class="table style-2 zero-config dt-table-hover">
                                        <thead>
                                            <tr>
                                                <th class="checkbox-column dt-no-sorting"> S.No </th>
                                                <th>Category Name</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center dt-no-sorting">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="checkbox-column"> 1 </td>
                                                <td>Jane</td>
                                                <td class="text-center"><span class="badge outline-badge-success">Success</span></td>
                                                <td class="text-center">
                                                    <a href="javascript:void(0);" class="bs-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete" data-original-title="Delete"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
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
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="red" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="15" y1="9" x2="9" y2="15"></line>
                            <line x1="9" y1="9" x2="15" y2="15"></line>
                        </svg>
                    </button>
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
                                    <input type="number" class="form-control" name="fresh_fees" min="0" placeholder="0">
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
                                    <span class="input-group-text">months</span>
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
                <button type="button" class="btn btn btn-light-dark" data-bs-dismiss="modal" onclick="$('#feesForm').trigger('reset');"><i class="flaticon-cancel-12"></i> Discard</button>
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
        "pageLength": 7 
    });

    // $(".fees_amount").TouchSpin({
    //     verticalbuttons: true,
    // });
</script>