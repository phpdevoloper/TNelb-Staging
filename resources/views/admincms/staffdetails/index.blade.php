@include('admincms.include.top')
@include('admincms.include.header')
@include('admincms.include.navbar')

<style>
 
:root{
    --border-color: #E2E8F0;
}

 .table tbody tr {
    border: 1px solid var(--border-color);
}

/* Base checkbox appearance */
.form-check-input[type=checkbox] {
    width: 1.1em;
    height: 1.1em;
    border: 1.5px solid #adb5bd;   /* visible border */
    background-color: #fff;
    cursor: pointer;
}

.form-check-input[type=checkbox] {
    border-radius: 0.25em;
}

/* Checked state */
.form-check:not(.form-switch)
.form-check-input:checked[type=checkbox] {
    background-color: #0d6efd;
    border-color: #0d6efd;

    /* BOLDER, CLEARER TICK */
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23ffffff' stroke-width='3' stroke-linecap='round' stroke-linejoin='round' d='M3 8l3 3 7-7'/%3e%3c/svg%3e");

    background-size: 75% 75%;
    background-position: center;
    background-repeat: no-repeat;
}

/* Focus */
.form-check-input:focus {
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, .25);
}

/* Label usability */
.form-check-label {
    cursor: pointer;
    font-weight: 500;
}




.swal-staff-success {
    border-radius: 10px;
    padding: 18px;
}

/* Main card */
.staff-success-card {
    font-size: 14px;
    color: #333;
}

/* Staff ID box */
.staff-id-box {
    background: #f4f8ff;
    border: 1px dashed #0d6efd;
    border-radius: 6px;
    padding: 12px;
    text-align: center;
    margin-bottom: 15px;
}

.staff-id-box small {
    display: block;
    font-size: 11px;
    color: #6c757d;
    letter-spacing: 1px;
}

.staff-id-box h4 {
    margin: 6px 0;
    color: #0d6efd;
    font-weight: 700;
}

.copy-btn {
    background: #0d6efd;
    color: #fff;
    border: none;
    padding: 4px 10px;
    font-size: 12px;
    border-radius: 4px;
    cursor: pointer;
}

.copy-btn:hover {
    background: #0b5ed7;
}

.login-note-muted {
    font-size: 12px;
    color: rgb(187, 47, 47);
    margin: 10px 0 15px;
    text-align: center;
}
</style>


<div id="content" class="main-content">
    <div class="layout-px-spacing">

        <div class="middle-content container-xxl p-0">
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
                                <div class="page-title">
                                </div>

                                <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item">
                                            <a href="#">Content Management System for TNELB</a>
                                        </li>
                                    </ol>
                                </nav>

                            </div>
                        </div>

                    </header>
                </div>
            </div>
            <!--  END BREADCRUMBS  -->

            <div class="row layout-top-spacing dashboard">

                <nav class="breadcrumb-style-five breadcrumbs_top  mb-2" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home">
                                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                </svg><span class="inner-text">Dashboard </span></a></li>
                        <li class="breadcrumb-item"><a href="#">Homepage</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Portal Staff Management Console</li>
                    </ol>
                </nav>

                <div id="tableCustomBasic" class="col-lg-12 col-12 layout-spacing">
                    <div class="statbox widget  box-shadow ">
                        <div class="widget-header mb-4">
                            <div class="row mt-2">
                                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                    <h4 class="text-dark card-title">Portal Staff Management Console </h4>
                                </div>

                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <div class="float-right">
                                    <button type="button" class="btn btn-info mb-2 me-4 float-end" data-bs-toggle="modal" data-bs-target="#inputFormModaladdstaffs">
                                        <i class="fa fa-plus"></i>&nbsp; Add New Staff
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <select id="customColumnFilter" class="form-select form-select-sm" style="display: none;">
                                    <option value="">All</option>
                                </select>
                                <table id="style-3" class="table style-3 dt-table-hover portaladmin">
                                    <thead>
                                        <tr>
                                            <th class="text-center">S.No</th>
                                            <th class="text-center">Staff Name</th>
                                            <!-- <th class="text-center">Date of Posted</th> -->
                                            <th class="text-center">Designation Name</th>
                                            <th class="text-center">Email</th>
                                            <th class="text-center">Handling Forms</th>
    
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <!-- id="sortable-menu" -->
                                    <tbody id="formtable">
                                        @foreach ($staffs as $staff)
                                        <tr data-id="{{ $staff->id }}">
                                            <td class="text-center">{{ $loop->iteration }}</td>
    
                                            <td>{{$staff->staff_name}}</td>
                                            <td>{{ $staff->name }}</td>
                                            <td>{{$staff->email}}</td>
                                            <td>
                                                @php
                                                    $handledFormIds = json_decode($staff->handle_forms, true) ?? [];
                                                    $matchedFormNames = $forms->whereIn('id', $handledFormIds)->pluck('form_name')->toArray();
                                                @endphp
                                                {{ implode(', ', $matchedFormNames) }}
                                            </td>
                                        
                                            <td>
                                                <span class="badge 
                                                {{ $staff->status == '1' ? 'badge-success' : 
                                                ($staff->status == '0' ? 'badge-dark' : 
                                                ($staff->status == '2' ? 'badge-danger' : '')) }}">
                                                    @if ($staff->status == '1')
                                                    Active
                                                    @elseif ($staff->status == '0')
                                                    Draft
                                                    @elseif ($staff->status == '2')
                                                    Inactive
                                                    @endif
                                                </span>
                                            </td>
                                            <td>
                                                <a href="javascript:void(0);" class="editstaffdata"
                                                    data-id="{{ $staff->id }}"
                                                    data-name="{{ $staff->name }}"
                                                    data-email="{{ $staff->email }}"
                                                    data-staff_name="{{ $staff->staff_name }}"
                                                    data-handle_forms='@json(json_decode($staff->handle_forms))'
                                                    data-status="{{ $staff->status }}"
                                                    data-bs-toggle="modal" data-bs-target="#inputFormModaleditstaffs">
                                                    <i class="fa fa-pencil text-primary me-2 cursor-pointer" title="Edit"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal fade inputForm-modal reset-on-open" id="inputFormModaladdstaffs" tabindex="-1" role="dialog" aria-labelledby="inputFormModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                        <div class="modal-content">

                            <div class="modal-header" id="inputFormModalLabel">
                                <h5 class="modal-title">Add New Staff</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true">
                                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                </button>
                            </div>

                            <div class="modal-body">
                                <form class="mt-0" id="newstaffmaster" novalidate enctype="multipart/form-data">
                                    <!-- Page Type Selection -->
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <div class="form-group">
                                                <label for="inputEmail4" class="form-label">Staff Name<span>*</span></label>
                                                <div class="input-group mb-1">
                                                    <input type="text" class="form-control" name="staff_name" id="staff_name">
                                                </div>
                                                <small class="text-danger error-text" data-error="staff_name"></small>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-2">
                                            <div class="form-group">
                                                <label>Staff Role<span>*</span></label>
                                                <select class="form-select" name="role_id" id="role_id">
                                                    <option value="">Please select the user role</option>
                                                    @foreach ($userRoles as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                                <small class="text-danger error-text" data-error="role_id"></small>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <div class="form-group">
                                                <label for="inputEmail4" class="form-label">Email<span>*</span></label>
                                                <div class="input-group mb-2">
                                                    <input type="email" class="form-control" name="staff_email" id="staff_email">
                                                </div>
                                                <small class="text-danger error-text" data-error="staff_email"></small>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="form-label">Password <span>*</span></label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="user_random_pass" id="user_random_pass" placeholder="Enter password">&nbsp;
                                                    <button class="btn btn-primary" type="button"  onclick="generatePassword()">Generate</button>
                                                </div>
                                                <small class="text-danger error-text" data-error="user_random_pass"></small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 mb-2">
                                            <div class="form-group">
                                                <label>Assign Form <span>*</span></label>
                                                <div class="row">
                                                    @foreach ($formlist as $form)
                                                        <div class="col-md-4 col-sm-6 mb-2">
                                                            <div class="form-check">
                                                                <input class="form-check-input"
                                                                    type="checkbox"
                                                                    id="handle_forms_{{ $form->id }}"
                                                                    name="handle_forms[]"
                                                                    value="{{ $form->id }}">

                                                                <label class="form-check-label" for="handle_forms_{{ $form->id }}">
                                                                    {{ $form->form_name }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <small class="text-danger error-text" data-error="handle_forms"></small>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Status <span>*</span></label>
                                                <select name="status" class="form-select">
                                                    <option value="" selected>Please select the status</option>
                                                    <option value="1" >Active</option>
                                                    <option value="0">Draft</option>
                                                    <option value="2">Inactive</option>
                                                </select>
                                                <small class="text-danger error-text" data-error="status"></small>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                                <!-- Modal Footer -->
                                <div class="modal-footer justify-content-center">
                                    <button type="button" class="btn btn-light-danger mt-2 mb-2 btn-no-effect" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary mt-2 mb-2 btn-no-effect">Add</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- --------------- -->

                <div class="modal fade" id="inputFormModaleditstaffs" tabindex="-1" role="dialog" aria-labelledby="inputFormModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header" id="inputFormModalLabel">
                                <h5 class="modal-title">Edit Staff Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="feather feather-x">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                </button>
                            </div>

                            <div class="modal-body">
                                <form id="editstaffstbls" enctype="multipart/form-data">
                                    <input type="hidden" name="id" id="form_id">
                                    <input type="hidden" name="updated_by" value="{{ Auth::user()->name }}">
                                    <input type="hidden" name="original_order_id" id="original_order_id">
                                    <div class="row">
                                        <div class="form-group pb-2 col-md-6">
                                            <label>Staff Name </label>
                                            <input type="hidden"  name="id" id="staff_id" >
                                            <input type="text" class="form-control" name="staff_name">
                                        </div>
                                        <div class="form-group pb-2 col-md-6">
                                            <label>Designation Name</label>
                                            <input type="text" class="form-control" name="name">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group pb-2 col-md-6">
                                            <label>Email </label>
                                            <input type="text" class="form-control" name="email">
                                        </div>
                                        <div class="pb-2 col-md-6">
                                            <label>Handling Forms of </label>
                                            <div class="row">
                                                @foreach ($formlist as $form)
                                                    <div class="col-md-6">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" 
                                                                id="handle_forms_{{ $loop->index + 1 }}" 
                                                                name="handle_forms[]" 
                                                                value="{{ $form->id }}"
                                                                {{ !empty($form->staff_id) ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="handle_forms_{{ $loop->index + 1 }}">
                                                                {{ $form->form_name }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                               
                                    <div class="row pt-1">
                                        <div class="form-group pb-2 col-md-6">
                                            <label>Status <span>*</span></label>
                                            <select name="status" class="form-select" id="statusSelectEdit">
                                                <option value="1">Active</option>
                                                <option value="0">Draft</option>
                                                <option value="2">Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light-danger mt-2 mb-2" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary mt-2 mb-2">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
        const chBoxes =
            document.querySelectorAll('.dropdown-menu input[type="checkbox"]');
        const dpBtn = 
            document.getElementById('multiSelectDropdown');
        let mySelectedListItems = [];

        function handleCB() {
            mySelectedListItems = [];
            let mySelectedListItemsText = '';

            chBoxes.forEach((checkbox) => {
                if (checkbox.checked) {
                    mySelectedListItems.push(checkbox.value);
                    mySelectedListItemsText += checkbox.value + ', ';
                }
            });

            dpBtn.innerText =
                mySelectedListItems.length > 0
                    ? mySelectedListItemsText.slice(0, -2) : 'Select';
        }

        chBoxes.forEach((checkbox) => {
            checkbox.addEventListener('change', handleCB);
        });
    </script>
@include('admincms.include.footer');


<script>
function generatePassword() {
    const chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789@#$!";
    let password = "";

    for (let i = 0; i < 10; i++) {
        password += chars.charAt(Math.floor(Math.random() * chars.length));
    }

    $("#user_random_pass").val(password).trigger("input"); // 🔥 THIS CLEARS THE ERROR
}
</script>

