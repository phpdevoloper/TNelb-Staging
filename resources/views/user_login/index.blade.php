@include('include.header')

<style>
    td {
        font-size: 15px;
    }


     .custom-fieldset {
        border: 1px solid #ccc;
        padding: 1rem;
        border-radius: 6px;
        margin-bottom: 1.5rem;
        position: relative;
    }

    .custom-legend {
    font-weight: bold;
    font-size: 1rem;
    padding: 0 10px;
    color: #333;
    display: inline-block;
    }

    /* basic positioning */
    .legend {
        list-style: none;
        padding: 0;
        margin: 0 0 1rem 0;
        display: flex;
        justify-content: flex-end;
        gap: 20px;
        flex-wrap: wrap;          /* wrap on smaller screens */
    }
    .legend li { 
        display: flex;
        align-items: center;        /* align box + text vertically */
        font-size: 14px;
    }
    /* color box */
    .legend span {
    display: inline-block;
    width: 14px;
    height: 14px;
    border: 1px solid #ccc;
    margin-right: 6px;          /* spacing between box and text */
    border-radius: 2px;         /* optional, softer look */
    }
    /* your colors */
    /* .legend .superawesome { background-color: #ff00ff; }
    .legend .awesome      { background-color: #00ffff; }
    .legend .kindaawesome { background-color: #0000ff; }
    .legend .notawesome   { background-color: #000000; } */

</style>
<section class="dashboard-panel">
    <div class="layout-login">
        <div class="container-fluid">
            <div class="row">
                @include('include.sidebar')

                <main class="main-content-login">
                    <!-- Tasks and Projects Section -->
                    <section class="tasks-projects-login">


                        <!-- Projects -->
                        <div class="projects-section-login">
                            <h5 class="mb-2"><strong>Active / Present Licence Details</strong></h5>
                            <div class="project-list-login mt-2">

                                <div class="project-card-login" data-status="en-cours">
                                    @if (!$present_license && !$present_license_ea)
                                        <div class="row">
                                            <div class="col-12">
                                                <p>No Active Licences</p>
                                            </div>
                                        </div>
                                    @endif

                                    @forelse($present_license as $workflow)
                                        <div class="row" style="border: none;">
                                            <div class="col-6 col-lg-4">
                                                @php
                                                    // var_dump($workflow);die;
                                                    $licenses = ['C', 'B', 'W', 'H'];
                                                    $category = in_array($workflow->license_name, $licenses)
                                                        ? 'Competency Certificate'
                                                        : 'Contractor License';
                                                @endphp
                                                <p><strong>License:</strong> {{ $workflow->license_number ?? 'NA' }}
                                                    ({{ $category }})</p>
                                            </div>
                                            <div class="col-6 col-lg-3">
                                                <p><strong>Issued On:</strong>
                                                    {{ $workflow->issued_at ? \Carbon\Carbon::parse($workflow->issued_at)->format('d-m-Y') : 'N/A' }}
                                                </p>

                                            </div>
                                            <div class="col-6 col-lg-3">
                                                <p>
                                                    <strong>Validity Upto:</strong>
                                                    <span>
                                                        {{ $workflow->expires_at ? \Carbon\Carbon::parse($workflow->expires_at)->format('d-m-Y') : 'N/A' }}
                                                    </span>
                                                </p>
                                            </div>

                                            <div class="col-6 col-lg-2">
                                                <p>
                                                    <strong>Status:</strong>
                                                    <span class="text-danger">
                                                        @if ($workflow->expires_at && \Carbon\Carbon::parse($workflow->expires_at)->lte(\Carbon\Carbon::today()))
                                                            <span class="badge badge-danger">Expired</span>
                                                        @else
                                                        <span class="badge badge-success">Active</span>
                                                        @endif
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                    @empty
                                        {{-- <div class="row">
                                            <div class="col-12">
                                                <p>No Active Licenses</p>
                                            </div>
                                        </div> --}}
                                    @endforelse


                                    @forelse($present_license_ea as $workflow)
                                        <div class="row" style="border: none;">
                                            <div class="col-6 col-lg-4">
                                                @php
                                                    $licenses = ['C', 'B', 'W', 'WH'];
                                                    $category = in_array($workflow->license_name, $licenses)
                                                        ? 'Competency Certificate'
                                                        : 'Contractor License';
                                                @endphp
                                                <p><strong>License:</strong> {{ $workflow->license_name ?? 'NA' }}
                                                    ({{ $category }})</p>
                                            </div>
                                            <div class="col-6 col-lg-3">
                                                <p><strong>Issued On:</strong>
                                                    {{ $workflow->issued_at ? \Carbon\Carbon::parse($workflow->issued_at)->format('d-m-Y') : 'N/A' }}
                                                </p>

                                            </div>
                                            <div class="col-6 col-lg-3">
                                                <p>
                                                    <strong>Validity Upto:</strong>
                                                    <span>
                                                        {{ $workflow->expires_at ? \Carbon\Carbon::parse($workflow->expires_at)->format('d-m-Y') : 'N/A' }}
                                                    </span>
                                                </p>
                                            </div>

                                            <div class="col-6 col-lg-2">
                                                <p>
                                                    <strong>Status:</strong>
                                                    <span class="text-danger">
                                                        @if ($workflow->expires_at && \Carbon\Carbon::parse($workflow->expires_at)->lte(\Carbon\Carbon::today()))
                                                            <span class="badge badge-danger">Expired</span>
                                                        @else
                                                           <span class="badge badge-success">Active</span>
                                                       
                                                        @endif
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="row">
                                            <div class="col-12">
                                                {{-- <p>No License Found</p> --}}
                                            </div>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <!-- Tasks -->
                        @if (isset($workflows_present) && $workflows_present->isNotEmpty())

                        <div class="mobile_formview d-block d-sm-none" >
                            <h5 class="mb-2"><strong>Status of Applications ( Competency Certificate )</strong></h5>
                            @foreach ($workflows_present as $index => $workflow)
                                <div class="card mb-3 p-3 shadow-sm border rounded">
                                    <h6 class="mb-2">
                                        <strong>Application {{ $index + 1 }}</strong>
                                    </h6>

                                    <p><strong>Form Type:</strong> Form {{ strtoupper($workflow->form_name ?? 'NA') }}</p>
                                    <p><strong>Application ID:</strong> {{ $workflow->application_id ?? 'NA' }}</p>
                                    <p><strong>Applied On:</strong> 
                                        {{ isset($workflow->created_at) ? \Carbon\Carbon::parse($workflow->created_at)->format('d/m/Y') : 'NA' }}
                                    </p>

                                    <!-- Application Status -->
                                    <p><strong>Application Status:</strong>
                                        @if ($workflow->payment_status == 'draft')
                                            @php
                                                $view_page = isset($workflow->appl_type) && $workflow->appl_type == 'R'
                                                    ? 'renew_form'
                                                    : 'edit-application';
                                            @endphp
                                            <a href="{{ route($view_page, ['application_id' => $workflow->application_id]) }}" class="btn btn-warning btn-sm">
                                                <i class="fa fa-pencil"></i> Draft
                                            </a>
                                        @else
                                            @if ($workflow->appl_type == 'R')
                                                @if ($workflow->status == 'P')
                                                    <span class="badge badge-warning">Renewal Form Submitted</span>
                                                @elseif($workflow->status == 'F')
                                                    <span class="badge badge-danger">In Progress</span>
                                                @else
                                                    <span class="badge badge-success">Completed</span>
                                                @endif
                                            @else
                                                @if ($workflow->status == 'P')
                                                    <span class="badge badge-primary">Submitted</span>
                                                @elseif($workflow->status == 'F')
                                                    <span class="badge badge-danger">In Progress</span>
                                                @else
                                                    <span class="badge badge-success">Completed</span>
                                                @endif
                                            @endif
                                        @endif
                                    </p>

                                    <!-- Payment Status -->
                                    <p><strong>Payment Status:</strong>
                                        @if ($workflow->payment_status == 'payment')
                                            <span class="text-success"><strong>Success</strong></span>
                                        @else
                                            <span class="text-warning"><strong>Pending</strong></span>
                                        @endif
                                    </p>

                                    <!-- Payment Receipt -->
                                    <p><strong>Payment Receipt:</strong>
                                        @if ($workflow->payment_status == 'payment')
                                            <a href="{{ route('paymentreceipt.pdf', ['loginId' => $workflow->application_id]) }}" target="_blank">
                                                <i class="fa fa-file-pdf-o" style="font-size:20px;color:red"></i>
                                            </a>
                                        @else
                                            <span class="text-warning">Pending</span>
                                        @endif
                                    </p>

                                    <!-- Application Download -->
                                    <p><strong>Application Download:</strong>
                                        @if ($workflow->payment_status == 'draft')
                                            <span>-</span>
                                        @else
                                            <a href="{{ route('generate.tamil.pdf', ['login_id' => $workflow->application_id]) }}" target="_blank">
                                                <i class="fa fa-file-pdf-o" style="font-size:20px;color:red"></i> <span style="font-size: small;">தமிழ்</span>
                                            </a>
                                            &nbsp;<br>
                                            <a href="{{ route('generate.pdf', ['login_id' => $workflow->application_id]) }}" target="_blank">
                                                <i class="fa fa-file-pdf-o" style="font-size:20px;color:red"></i> <span style="font-size: small;">English</span>
                                            </a>
                                        @endif
                                    </p>

                                    <!-- License Status -->
                                    <p><strong>License Status:</strong>
                                        <?php //var_dump($workflow->appl_type); ?>
                                        @if (!empty($workflow->license_number) && $workflow->status == 'A')
                                            <a href="{{ route('admin.generate.pdf', ['application_id' => $workflow->application_id]) }}" target="_blank">
                                                <span class="badge badge-info" style="font-size: 15px;">{{ $workflow->license_number }}</span>
                                            </a>
                                            @php
                                                $license_details = DB::table('tnelb_application_tbl')
                                                    ->where('license_number', $workflow->license_number)
                                                    ->first();

                                                $renewed = DB::table('tnelb_renewal_license')
                                                    ->where('license_number', $workflow->license_number)
                                                    ->first();
                                            @endphp
                                            <br>
                                            @if (isset($renewed) && !empty($renewed))

                                            @elseif (isset($license_details->application_id) && !empty($license_details->application_id))
                                                <strong>Renewal Application</strong><br>
                                                ID: <span class="text-success">{{ $license_details->application_id }}</span>
                                            @endif
                                        @else
                                            @if($workflow->appl_type == 'R')
                                                @php
                                                    $renewed1 = DB::table('tnelb_renewal_license')
                                                        ->where('application_id', $workflow->application_id)
                                                        ->first();
                                                         $workflow->renewed_license_number = $renewed1->license_number ?? 'NA';
                                                @endphp
                                            @else
                                                <span class="text-primary">NA</span>
                                            @endif
                                        @endif
                                    </p>
                                </div>
                            @endforeach 

                        </div>   
                        <!-- ----------------- -->
                        <div class="tasks-section-login d-none d-sm-block">
                            <fieldset class="custom-fieldset">
                                <legend class="custom-legend">
                                    <h5 class="mb-2">
                                        <strong>Status of Applications ( Competency Certificate )</strong>
                                    </h5>
                                </legend>
                                <ul class="legend justify-content-end mb-2">
                                    <li><span class="bg-success"></span> Completed</li>
                                    <li><span class="bg-warning"></span> In Progress</li>
                                    <li><span class="bg-danger"></span> Rejected</li>
                                    <li><span class="bg-primary"></span> Draft</li>
                                </ul>
                                <div id="applicationsTable">
                                    @include('user_login.pagination-list')
                                </div>
                            </fieldset>
                        </div>
                        @endif

                        <!-- ---------------------------------------------------------- -->
                        @if (isset($workflows_cl) && $workflows_cl->isNotEmpty())
                        <div class="mobile_formview d-block d-sm-none">
                            <h5 class="mb-2"><strong>Status of Applications ( Contractor License )</strong></h5>

                            @if (isset($workflows_cl) && $workflows_cl->isNotEmpty())
                                @foreach ($workflows_cl as $index => $workflow)
                                    <div class="card mb-3 p-3 border rounded shadow-sm">
                                        <h6 class="mb-2"><strong>Application {{ $index + 1 }}</strong></h6>

                                        <p><strong>Form Type:</strong> Form {{ strtoupper($workflow->form_name ?? 'N/A') }}</p>
                                        <p><strong>Application ID:</strong> {{ $workflow->application_id ?? 'N/A' }}</p>
                                        <p><strong>Applied On:</strong> {{ isset($workflow->created_at) ? \Carbon\Carbon::parse($workflow->created_at)->format('d/m/Y') : 'N/A' }}</p>

                                        <!-- Application Status -->
                                        <p><strong>Application Status:</strong>
                                            @if ($workflow->payment_status == 'draft')
                                                @if (strtoupper(trim($workflow->appl_type)) === 'N')
                                                    <a href="{{ route('apply-form-a_draft', ['application_id' => $workflow->application_id]) }}">
                                                        <button class="btn btn-primary btn-sm">
                                                            <i class="fa fa-pencil"></i> Draft
                                                        </button>
                                                    </a>
                                                @else
                                                    <a href="{{ route('apply-form-a_renewal_draft', ['application_id' => $workflow->application_id]) }}">
                                                        <button class="btn btn-primary btn-sm">
                                                            <i class="fa fa-pencil"></i> Draft
                                                        </button>
                                                    </a>
                                                @endif
                                            @else
                                                @if ($workflow->appl_type == 'R')
                                                    @if ($workflow->application_status == 'P')
                                                        <span class="badge bg-warning">Renewal Form Submitted</span>
                                                    @elseif($workflow->application_status == 'F')
                                                        <span class="badge bg-danger">In Progress</span>
                                                    @else
                                                        <span class="badge bg-success">Completed</span>
                                                    @endif
                                                @else
                                                    @if ($workflow->application_status == 'P')
                                                        <span class="badge bg-primary">Submitted</span>
                                                    @elseif($workflow->application_status == 'F')
                                                        <span class="badge bg-danger">In Progress</span>
                                                    @else
                                                        <span class="badge bg-success">Completed</span>
                                                    @endif
                                                @endif
                                            @endif
                                        </p>

                                        <!-- Payment Status -->
                                        <p><strong>Payment Status:</strong>
                                            @if ($workflow->payment_status == 'paid')
                                                <span class="text-success">Success</span>
                                            @else
                                                <span class="text-primary">Pending</span>
                                            @endif
                                        </p>

                                        <!-- Payment Receipt -->
                                        <p><strong>Payment Receipt:</strong>
                                            @if ($workflow->payment_status == 'paid')
                                                <a href="{{ route('paymentreceipt.pdf', ['loginId' => $workflow->application_id]) }}" 
                                                    target="_blank" title="Download Payment Receipt PDF">
                                                    <i class="fa fa-file-pdf-o" style="font-size:20px;color:red"></i>
                                                </a>
                                            @else
                                                <span class="text-primary">Pending</span>
                                            @endif
                                        </p>

                                        <!-- Application Download -->
                                        <p><strong>Application Download:</strong>
                                            @if ($workflow->payment_status == 'draft')
                                                <span>-</span>
                                            @else
                                                <a href="{{ route('generatea.pdf', ['login_id' => $workflow->application_id]) }}" target="_blank">
                                                    <i class="fa fa-file-pdf-o" style="font-size:20px;color:red"></i>
                                                    <span style="font-size: x-small;">English</span>
                                                </a>
                                            @endif
                                        </p>

                                        <!-- License Number -->
                                        <p><strong>License Number:</strong>
                                            @if (!empty($workflow->license_number) && $workflow->application_status == 'A')
                                                <a href="{{ route('admin.generateForma.pdf', ['application_id' => $workflow->application_id]) }}" target="_blank">
                                                    <span class="badge bg-info" style="font-size: 15px;">
                                                        {{ $workflow->license_number }}
                                                    </span>
                                                </a>

                                                @php
                                                    $license_details = DB::table('tnelb_application_tbl')
                                                        ->where('license_number', $workflow->license_number)
                                                        ->first();

                                                         $new_license_details = DB::table('tnelb_ea_applications')
                                                        ->where('license_number', $workflow->license_number)
                                                        ->first();

                                                    $renewed = DB::table('tnelb_renewal_license')
                                                        ->where('license_number', $workflow->license_number)
                                                        ->first();
                                                @endphp

                                                <br>
                                                @if (isset($renewed) && !empty($renewed))
                                                    {{-- Renewed License Exists --}}
                                                @elseif (isset($license_details->application_id) && !empty($license_details->application_id))
                                                    <strong>Renewal Application:</strong> 
                                                    <span class="text-success">{{ $license_details->application_id }}</span>
                                                @endif
                                            @else
                                                <span class="text-primary">NA</span>
                                            @endif
                                            @if (empty($license_details))
                                                @if (isset($workflow->license_number) && \Carbon\Carbon::parse($workflow->expires_at)->lt(\Carbon\Carbon::now()))
                                                    <br>
                                                    <a href="{{ route('renew-form_ea', ['application_id' => $workflow->application_id]) }}" class="text-primary">
                                                        (Apply for renewal)
                                                    </a>
                                                @endif
                                            @endif
                                        </p>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-danger">No records found</p>
                            @endif

                        </div>    
                        

                        <div class="tasks-section-login d-none d-sm-block">
                            <fieldset class="custom-fieldset">
                                <legend class="custom-legend">
                                    <h5 class="mb-2"><strong>Status of Applications ( Contractor Licence )</strong></h5>
                                </legend>
                                <ul class="legend justify-content-end mb-2">
                                    <li><span class="bg-success"></span> Completed</li>
                                    <li><span class="bg-warning"></span> In Progress</li>
                                    <li><span class="bg-danger"></span> Rejected</li>
                                    <li><span class="bg-primary"></span> Draft</li>
                                </ul>
                                <table class="table-login">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Form Type</th>
                                            <th>Application ID</th>
                                            <th>Applied On</th>
                                            <th>Application Status</th>
                                            <th>Payment Status</th>
                                            <th>Payment Receipt</th>
                                            <th>Application Download</th>
                                            <th>Licence Number</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (isset($workflows_cl) && $workflows_cl->isNotEmpty())
                                        @foreach ($workflows_cl as $index => $workflow)
                                        <?php //var_dump($workflow);die;
                                        ?>
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>Form {{ strtoupper($workflow->form_name ?? 'N/A') }}</td>
                                            <td>{{ $workflow->application_id ?? 'N/A' }}</td>
                                            <td>{{ isset($workflow->created_at) ? \Carbon\Carbon::parse($workflow->created_at)->format('d/m/Y') : 'N/A' }}
                                            </td>

                                            <!-- Application Status -->
                                            <td>
                                                @if ($workflow->payment_status == 'draft')

        
                                                @if (strtoupper(trim($workflow->appl_type)) === 'N')
                                                <a href="{{ route('apply-form-a_draft', ['application_id' => $workflow->application_id]) }}">
                                                    <button class="btn btn-primary">
                                                        <i class="fa fa-pencil"></i> Draft
                                                    </button>
                                                </a>
                                                @else
                                                <a href="{{ route('apply-form-a_renewal_draft', ['application_id' => $workflow->application_id]) }}">
                                                    <button class="btn btn-primary">
                                                        <i class="fa fa-pencil"></i> Draft
                                                    </button>
                                                </a>
                                                @endif

                                                @else
                                                @if ($workflow->appl_type == 'R')
                                                @if ($workflow->application_status == 'P')
                                                <span class="btn btn-sm btn-warning">Renewal Form
                                                    Submitted</span>
                                                @elseif($workflow->application_status == 'F')
                                                <span class="btn btn-danger">In Progress</span>
                                                @else
                                                <span class="btn btn-sm btn-success">Completed</span>
                                                @endif
                                                @else
                                                @if ($workflow->application_status == 'P')
                                                <span class="btn btn-sm btn-primary">Submitted</span>
                                                @elseif($workflow->application_status == 'F')
                                                <span class="btn btn-danger">In Progress</span>
                                                @else
                                                <span class="btn btn-sm btn-success">Completed</span>
                                                @endif
                                                @endif
                                                @endif
                                            </td>

                                            <!-- Payment Status -->
                                            <td>
                                                @if ($workflow->payment_status == 'paid')
                                                <p class="text-success">Success</p>
                                                @else
                                                <p class="text-primary">Pending</p>
                                                @endif
                                            </td>

                                            <td>
                                                @if ($workflow->payment_status == 'paid')
                                                <a href="{{ route('paymentreceipt.pdf', ['loginId' => $workflow->application_id]) }}"
                                                    target="_blank" rel="noopener noreferrer"
                                                    title="Download Payment Receipt PDF"
                                                    style="font-weight:500;">
                                                    <i class="fa fa-file-pdf-o"
                                                        style="font-size:20px;color:red"></i>
                                                </a>
                                                @else
                                                <p class="text-primary">Pending</p>
                                                @endif
                                            </td>

                                            <!-- Application Download -->
                                            <td>
                                                @if ($workflow->payment_status == 'draft')
                                                <p>-</p>
                                                @else
                                                <a href="{{ route('generatea.pdf', ['login_id' => $workflow->application_id]) }}"
                                                    target="_blank" style="font-weight:500;">
                                                    <i class="fa fa-file-pdf-o"
                                                        style="font-size:20px;color:red"></i>
                                                    <span style="font-size: x-small;">English</span>
                                                </a>
                                                @endif
                                            </td>

                                            <!-- License Number -->
                                    <td>
                                                            @if (!empty($workflow->license_number) && $workflow->application_status == 'A')
                                                            <a href="{{ route('admin.generateForma.pdf', ['application_id' => $workflow->application_id]) }}" target="_blank"> 
                                                                <span class="badge badge-info" style="font-size: 15px;">{{ $workflow->license_number }}</span>
                                                            </a>
                                                            <br>

                                                            @if (!empty($workflow->renewals))
                                                                <span class="text-muted" style="font-size: 12px;">
                                                                    Renewed {{ count($workflow->renewals) }} times
                                                                </span>
                                                                <br>
                                                            @endif

                                                            @if (!empty($workflow->expires_at) && \Carbon\Carbon::parse($workflow->expires_at)->lt(\Carbon\Carbon::now()))
                                                                @if (!empty($workflow->renewal_application_id))
                                                                    <strong>Renewal Application</strong><br>
                                                                    ID :
                                                                    <a href="{{ route('generate.pdf', ['login_id' => $workflow->renewal_application_id]) }}" 
                                                                    target="_blank" 
                                                                    class="text-success">
                                                                    {{ $workflow->renewal_application_id }}
                                                                    </a>
                                                                @else
                                                                    <a href="{{ route('renew-form_ea', ['application_id' => $workflow->application_id]) }}"
                                                                    class="text-primary">
                                                                    (Apply for renewal)
                                                                    </a>
                                                                @endif
                                                            @endif

                                                        @elseif (!empty($workflow->renewal_application_id))
                                                            <strong>Renewal Application</strong><br>
                                                            ID :
                                                            <a href="{{ route('generate.pdf', ['login_id' => $workflow->renewal_application_id]) }}" 
                                                            target="_blank" 
                                                            class="text-success">
                                                            {{ $workflow->renewal_application_id }}
                                                            </a>

                                                        @else
                                                            <p class="text-primary">NA</p>
                                                        @endif
                                                    </td>

                                        </tr>
                                        @endforeach
                                        @else
                                        <tr>
                                            <td colspan="9" class="text-center text-danger">No records found</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            </fieldset>
                        @endif
                    </section>
                </main>
            </div>
        </div>
    </div>
</section>

<footer class="main-footer">
    @include('include.footer')
    @if(session('already_applied'))
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            Swal.fire({
                icon: 'warning',
                title: 'You already applied this application!',
                // text: 'Redirecting to dashboard...',
                timer: 3000,
                showConfirmButton: false
            });
        });
    </script>
    @endif
    <script>
    $(document).on('click', '.pagination a', function (e) {
        e.preventDefault();

        var url = $(this).attr('href');

        $.ajax({
            url: url,
            type: "GET",
            success: function (data) {
                $("#applicationsTable").html(data);
            }
        });
    });
</script>
