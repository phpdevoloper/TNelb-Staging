

@include('admin.include.top')
@include('admin.include.header')
@include('admin.include.navbar')

<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="middle-content container-xxl p-0">
            <!--  BEGIN BREADCRUMBS  -->
            <div class="secondary-nav">
                <div class="breadcrumbs-container" data-page-heading="Analytics">
                    <header class="header navbar navbar-expand-sm">
                        <a href="{{ route('admin.secretary_table')}}" class="btn-toggle sidebarCollapse" data-placement="bottom">
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
                                        <li class="breadcrumb-item"><a href="#"></a></li>

                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </header>
                </div>
            </div>
            <!--  END BREADCRUMBS  -->

            <div class="row layout-top-spacing">
                <div class="col-xl-12 col-lg-6 col-md-6 col-sm-12 col-12  layout-spacing">
                    <h5 class="">Competancy Certificates</h5>
                </div>
                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12  layout-spacing">
                    <div class="widget widget-card-five bg-yellow color_forms">
                        <div class="widget-content">
                            <div class="account-box">
                                <div class="info-box">
                                    <div class="row">
                                        <div class="col-lg-3 col-3">
                                            <div class="icon bg-H">
                                                <span>
                                                    <i class="fa fa-file-text"></i>
                                                    <!-- <img src="{{url ('assets/images/icon/doc-icon.png') }}" alt="money-bag"> -->
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-lg-9 col-9">
                                            <div class="balance-info">
                                                <h6>Form S</h6>
                                                <p class="text-white">License C</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-bottom-section">
                                    {{-- <a href="{{ route('view_applications') }}">View More</a> --}}
                                    <div style="text-align: start;">
                                        <h4 onclick="window.location.href='{{ route('admin.get_applications') }}'" style="cursor: pointer"> On hold <span class="badge badge-dark">0</span></h4>
                                        <h4 onclick="window.location.href='{{ route('admin.get_rejected') }}'" style="cursor: pointer"> Rejected <span class="badge badge-danger">{{ $rejected_appls }}</span></h4>
                                    </div>
                                    <div style="text-align: end;">
                                        <h4 onclick="window.location.href='{{ route('admin.get_completed') }}'" style="cursor: pointer"> Completed <span class="badge badge-success">{{ $completed }}</span></h4>
                                        <h4 onclick="window.location.href='{{ route('admin.get_applications') }}'" style="cursor: pointer"> Pending <span class="badge badge-warning">{{ $pendings }}</span></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row layout-top-spacing">
                    <div class="col-xl-12 col-lg-6 col-md-6 col-sm-12 col-12  layout-spacing">
                        <h5 class="">Contractor License</h5>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12  layout-spacing">
                        <div class="widget widget-card-five bg-thickgreen color_forms">
                            <div class="widget-content">
                                <div class="account-box">
                                    <div class="info-box">
                                        <div class="row">
                                            <div class="col-lg-3 col-3">
                                                <div class="icon bg-H">
                                                    <span>
                                                        <i class="fa fa-file-text"></i>
                                                        <!-- <img src="{{url ('assets/images/icon/doc-icon.png') }}" alt="money-bag"> -->
                                                    </span>
                                                </div>
                                            </div>
    
                                            <div class="col-lg-9 col-9">
                                                <div class="balance-info">
                                                    <h6>Form A</h6>
                                                    <p class="text-white">License EA</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-bottom-section">
                                        <div style="text-align: start;">
                                            <h4 onclick="window.location.href='{{ route('admin.get_applications') }}'" style="cursor: pointer"> On hold <span class="badge badge-dark">0</span></h4>
                                            <h4 onclick="window.location.href='{{ route('admin.get_applications') }}'" style="cursor: pointer"> Rejected <span class="badge badge-danger">0</span></h4>
                                        </div>
                                        <div style="text-align: end;">
                                            <h4 onclick="window.location.href='{{ route('admin.completed_forma') }}'" style="cursor: pointer"> Completed <span class="badge badge-success">{{ $form_a_completed }}</span></h4>
                                            <h4 onclick="window.location.href='{{ route('admin.view_forma') }}'" style="cursor: pointer"> Pending <span class="badge badge-warning">{{ $form_a_pending }}</span></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

@include('admin.include.footer');