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
                        <li class="breadcrumb-item active" aria-current="page">Certificates & Licences</li>
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
                        {{-- <div class="col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <form id="addForms" class="simple-example" novalidate>
                                        <div class="mb-2">
                                            <label for="inputEmail4" class="form-label">Category<span class="text-danger">*</span> </label>
                                            <select class="form-select" name="form_cate" id="form_cate">
                                                <option value="">Please select category</option>
                                                @foreach ($categories as $item)
                                                    <option value="{{ $item->id }}">{{ $item->category_name }}</option>
                                                @endforeach
                                            </select>
                                            <small class="text-danger d-none error-form_cate">Please choose the category</small>
                                        </div>
                                        <div class="mb-2">
                                            <label for="inputEmail4" class="form-label">Certificate / Licence Name <span class="text-danger">*</span> </label>
                                            <input type="text" class="form-control" name="cert_name" id="cert_name">
                                            <small class="text-danger d-none error-cer_val">Please fill the Certificate / Licence Name</small>
                                        </div>
                                        <div class="mb-2">
                                            <label for="inputEmail4" class="form-label">Certificate / Licence Code <span class="text-danger">*</span> </label>
                                            <input type="text" class="form-control" name="cate_licence_code" id="cate_licence_code" maxlength="5" placeholder="eg.C,B">
                                            <small class="text-danger d-none error-cert_code">Please fill the Certificate / Licence Code</small>
                                        </div>
                                        <div class="mb-2">
                                            <label for="inputEmail4" class="form-label">Form Name <span class="text-danger">*</span> </label>
                                            <input type="text" class="form-control" name="form_name" id="form_name">
                                            <small class="text-danger d-none error-form_name">Please fill the Form Name</small>
                                        </div>
                                        <div class="mb-2">
                                            <label for="inputEmail4" class="form-label">Form Code <span class="text-danger">*</span> </label>
                                            <input type="text" class="form-control" name="form_code" id="form_code" maxlength="5" placeholder="eg.S,W">
                                            <small class="text-danger d-none error-form_code">Please choose the Form Code</small>
                                        </div>
                                        <div class="mb-2">
                                            <label for="inputEmail4" class="form-label">Status<span class="text-danger">*</span> </label>
                                            <select class="form-select" name="form_status" id="form_status">
                                                <option value="1">Active</option>
                                                <option value="2">In Active</option>
                                            </select>
                                            <small class="text-danger d-none error-form_status">Please choose the Form status</small>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary">Create</button>
                                    </form>
                                </div>
                            </div>
                        </div> --}}
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header ">
                                    <h5>Certificates / Licences</h5>
                                    <button class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#addFormModal"><i class="fa fa-plus"></i> Add</button>
                                </div>
                                <div class="card-body">
                                    <table id="style-2" class="table style-2 zero-config dt-table-hover">
                                        <thead>
                                            <tr>
                                                <th class="checkbox-column dt-no-sorting"> S.No </th>
                                                <th>Certificate / Licence Name</th>
                                                <th>Form Name</th>
                                                <th>Category</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">Created At</th>
                                                <th class="text-center dt-no-sorting">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($all_licences as $index => $row)
                                                <tr>
                                                    <!-- S.No -->
                                                    <td class="text-center">{{ $index + 1 }}</td>

                                                    <!-- Category Name -->
                                                    <td>{{ $row->licence_name }}</td>

                                                    <td>{{ $row->form_name }}</td>
                                                    
                                                    <td>{{ $row->category_name }}</td>

                                                    <!-- Status -->
                                                    <td class="text-center">
                                                        @if($row->status == 1)
                                                            <span class="badge outline-badge-success">Active</span>
                                                        @else
                                                            <span class="badge outline-badge-danger">Inactive</span>
                                                        @endif
                                                    </td>

                                                    <td class="text-center">{{ $row->created_at }}</td>

                                                    <!-- Action -->
                                                    <td class="text-center">
                                                        <a href="javascript:void(0);" class="bs-tooltip editForm" 
                                                            data-bs-toggle="modal" data-bs-target="#editFormModal" title="Edit"
                                                            data-row_id="{{ $row->id }}"
                                                            data-form_name="{{ $row->form_name }}"
                                                            data-licence_name="{{ $row->licence_name }}"
                                                            data-category="{{ $row->category_id }}"
                                                            data-cert_licence_code="{{ $row->cert_licence_code }}"
                                                            data-form_code="{{ $row->form_code }}"
                                                            data-status="{{ $row->status }}"
                                                            >
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" 
                                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                                                                class="feather feather-edit">
                                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                            </svg>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted">No records found</td>
                                                </tr>
                                            @endforelse
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


<!--Add Modal -->
<div class="modal fade" id="addFormModal" tabindex="-1" role="dialog" aria-labelledby="addFormModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addFormModalLabel"><span class="badge badge-primary"><i class="fa fa-wpforms"></i></span> Add Certificate / Licences</h5>
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
            <form id="addForms" class="simple-example" novalidate>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 mb-2">
                            <label for="inputEmail4" class="form-label">Category<span class="text-danger">*</span> </label>
                            <select class="form-select" name="form_cate" id="form_cate">
                                <option value="">Please select category</option>
                                @foreach ($categories as $item)
                                    <option value="{{ $item->id }}">{{ $item->category_name }}</option>
                                @endforeach
                            </select>
                            <small class="text-danger d-none error-form_cate">Please choose the category</small>
                        </div>
                        <div class="col-lg-6 mb-2">
                            <label for="inputEmail4" class="form-label">Certificate / Licence Name <span class="text-danger">*</span> </label>
                            <input type="text" class="form-control" name="cert_name" id="cert_name">
                            <small class="text-danger d-none error-cer_val">Please fill the Certificate / Licence Name</small>
                        </div>
                        <div class="col-lg-6 mb-2">
                            <label for="inputEmail4" class="form-label">Certificate / Licence Code <span class="text-danger">*</span> </label>
                            <input type="text" class="form-control" name="cate_licence_code" id="cate_licence_code" maxlength="5" placeholder="eg.C,B">
                            <small class="text-danger d-none error-cert_code">Please fill the Certificate / Licence Code</small>
                        </div>
                        <div class="col-lg-6 mb-2">
                            <label for="inputEmail4" class="form-label">Form Name <span class="text-danger">*</span> </label>
                            <input type="text" class="form-control" name="form_name" id="form_name">
                            <small class="text-danger d-none error-form_name">Please fill the Form Name</small>
                        </div>
                        <div class="col-lg-6 mb-2">
                            <label for="inputEmail4" class="form-label">Form Code <span class="text-danger">*</span> </label>
                            <input type="text" class="form-control" name="form_code" id="form_code" maxlength="5" placeholder="eg.S,W">
                            <small class="text-danger d-none error-form_code">Please choose the Form Code</small>
                        </div>
                        <div class="col-lg-6 mb-2">
                            <label for="inputEmail4" class="form-label">Status<span class="text-danger">*</span> </label>
                            <select class="form-select" name="form_status" id="form_status">
                                <option value="1">Active</option>
                                <option value="2">In Active</option>
                            </select>
                            <small class="text-danger d-none error-form_status">Please choose the Form status</small>
                        </div>
                        <div class="text-center mt-3">
                            <button type="submit" class="btn btn-primary">Create</button>
                            <button type="button" class="btn btn btn-light-dark" data-bs-dismiss="modal" onclick="$('#addForms').trigger('reset');"><i class="flaticon-cancel-12"></i> Discard</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!--Add Edit Modal -->
<div class="modal fade" id="editFormModal" tabindex="-1" role="dialog" aria-labelledby="editFormModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editFormModalLabel"><span class="badge badge-primary"><i class="fa fa-wpforms"></i></span> Edit Certificate / Licences</h5>
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
            <form id="editForms" class="simple-example" novalidate>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 mb-2">
                            <label for="inputEmail4" class="form-label">Category<span class="text-danger">*</span> </label>
                            <select class="form-select" name="edit_form_cate" id="edit_form_cate">
                                <option value="">Please select category</option>
                                @foreach ($categories as $item)
                                    <option value="{{ $item->id }}">{{ $item->category_name }}</option>
                                @endforeach
                            </select>
                            <small class="text-danger d-none error-edit_form_cate">Please choose the category</small>
                        </div>
                        <div class="col-lg-6 mb-2">
                            <label for="inputEmail4" class="form-label">Certificate / Licence Name <span class="text-danger">*</span> </label>
                            <input type="text" class="form-control" name="edit_cert_name" id="edit_cert_name">
                            <small class="text-danger d-none error-cer_error">Please fill the Certificate / Licence Name</small>
                        </div>
                        <div class="col-lg-6 mb-2">
                            <label for="inputEmail4" class="form-label">Certificate / Licence Code <span class="text-danger">*</span> </label>
                            <input type="text" class="form-control" name="edit_cate_licence_code" id="edit_cate_licence_code" maxlength="5" placeholder="eg.C,B">
                            <small class="text-danger d-none error-cert_code_error">Please fill the Certificate / Licence Code</small>
                        </div>
                        <div class="col-lg-6 mb-2">
                            <label for="inputEmail4" class="form-label">Form Name <span class="text-danger">*</span> </label>
                            <input type="text" class="form-control" name="edit_form_name" id="edit_form_name">
                            <small class="text-danger d-none error-edit_form_name">Please fill the Form Name</small>
                        </div>
                        <div class="col-lg-6 mb-2">
                            <label for="inputEmail4" class="form-label">Form Code <span class="text-danger">*</span> </label>
                            <input type="text" class="form-control" name="edit_form_code" id="edit_form_code" maxlength="5" placeholder="eg.S,W">
                            <small class="text-danger d-none error-edit_form_code">Please choose the Form Code</small>
                        </div>
                        <div class="col-lg-6 mb-2">
                            <label for="inputEmail4" class="form-label">Status<span class="text-danger">*</span> </label>
                            <select class="form-select" name="edit_form_status" id="edit_form_status">
                                <option value="1">Active</option>
                                <option value="2">In Active</option>
                            </select>
                            <small class="text-danger d-none error-edit_form_status">Please choose the Form status</small>
                        </div>
                        <input type="hidden" name="cert_id" id="edit_cert_id">
                        <div class="text-center mt-3">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <button type="button" class="btn btn btn-light-dark" data-bs-dismiss="modal" onclick="$('#feesForm').trigger('reset');"><i class="flaticon-cancel-12"></i> Discard</button>
                        </div>
                    </div>
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