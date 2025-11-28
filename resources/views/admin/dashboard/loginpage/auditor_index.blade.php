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
            <h4 class="dashboard_title layout-top-spacing">Competency Certificate</h4>
            <div class="row layout-top-spacing">
                @foreach($auditor_pendings as $form)
                @php
                    // var_dump($form);die;
                @endphp
                @if($form->form_name!= 'EA')
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-card-five {{ $formColors[$form->color_code] ?? 'bg-default' }} color_forms">
                        <div class="widget-content">
                            <div class="account-box">
                                <div class="info-box">
                                    <div class="row">
                                        <div class="col-lg-3 col-3">
                                            <div class="icon">
                                                <span><i class="fa fa-file-text"></i></span>
                                            </div>
                                        </div>
                                        <div class="col-lg-9 col-9">
                                            <div class="balance-info">
                                                <h6>{{ $form->form_name }}</h6>
                                                <h5>License {{ $form->licence_name }}</h5>
                                            </div>
                                        </div>

                                     
                                        <div class="card-bottom-section">
                                            <div class="layout-top-spacing" style="text-align: end">
                                                <a href="{{ route('admin.view', ['form_id' => $form->id]) }}" class="" data-form="{{ $form->id }}">
                                                   <h4>On Hold <span class="badge badge-warning">0</span></h4>
                                               </a>
                                                <a href="{{ route('admin.view_rejected', ['form_id' => $form->id]) }}" class="" data-form="{{ $form->id }}">
                                                   <h4>Rejected <span class="badge badge-danger">{{ $form->rejected_count }}</span></h4>
                                               </a>
                                            </div>
                                            <div class="layout-top-spacing" style="text-align: end">
                                              <a href="{{ route('admin.view_completed', ['form_id' => $form->id]) }}" class="" data-form="{{ $form->id }}">
                                                  <h4>Completed <span class="badge badge-success">{{ $form->completed_count }}</span></h4>
                                              </a>

                                              <a href="{{ route('admin.view', ['form_id' => $form->id]) }}" class="" data-form="{{ $form->id }}">
                                                  <h4>Pending <span class="badge badge-warning">{{ $form->pending_count }}</span></h4>
                                              </a>
                                            </div>
                                        </div> 


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            @endforeach

                <!-- <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-card-five bg-H color_forms">
                        <div class="widget-content">
                            <div class="account-box">

                                <div class="info-box">
                                    <div class="row">
                                        <div class="col-lg-3 col-3">
                                            <div class="icon">
                                                <span>
                                                     <i class="fa fa-file-text"></i>
                                                </span>
                                            </div>
                                        </div>
                                      
                                        <div class="col-lg-9 col-9">
                                            <div class="balance-info">
                                                <h6>Form H</h6>
                                                <h5>License WH</h5>
                                                <h4> Completed <span class="badge badge-success">0</span></h4>
                                                <a href="{{ route('admin.view_auditor')  }}" class=""> <h4> Pending <span class="badge badge-warning">0</span></h4></a>

                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-card-five bg-red color_forms">
                        <div class="widget-content">
                            <div class="account-box">

                                <div class="info-box">
                                    <div class="row">
                                        <div class="col-lg-3 col-3">
                                            <div class="icon">
                                                <span>
                                                <i class="fa fa-file-text"></i>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-lg-9 col-9">
                                            <div class="balance-info">
                                                <h6>Form W</h6>
                                                <h5>License A</h5>
                                                 <h4> Completed <span class="badge badge-success">0</span></h4>
                                                 <a href="{{ route('admin.view_auditor')  }}" class=""> <h4> Pending <span class="badge badge-warning">0</span></h4></a>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-card-five bg-yellow color_forms">
                        <div class="widget-content">
                            <div class="account-box">

                                <div class="info-box">
                                    <div class="row">
                                        <div class="col-lg-3 col-3">
                                            <div class="icon">
                                                <span>
                                                <i class="fa fa-file-text"></i>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-lg-9 col-9">
                                            <div class="balance-info">
                                                <h6>Form S</h6>
                                                <h5>License C</h5>
                                                 <h4> Completed <span class="badge badge-success">0</span></h4>
                                                 <a href="{{ route('admin.view_auditor')  }}" class="">  <h4> Pending <span class="badge badge-warning">0</span></h4></a>
                                            </div>
                                        </div>

                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>>

                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-card-five bg-green color_forms">
                        <div class="widget-content">
                            <div class="account-box">

                                <div class="info-box">
                                    <div class="row">
                                        <div class="col-lg-3 col-3">
                                            <div class="icon">
                                                <span>
                                                <i class="fa fa-file-text"></i>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-lg-9 col-9">
                                            <div class="balance-info">
                                                <h6>Form PG</h6>
                                                <h5>License PG</h5>
                                                 <h4> Completed <span class="badge badge-success">0</span></h4>
                                                 <a href="{{ route('admin.view_auditor')  }}" class=""> <h4> Pending <span class="badge badge-warning">0</span></h4></a>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
           
                <h4 class="dashboard_title">Contractor Licenses</h4>

                <!-- <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-card-five bg-dark color_forms">
                        <div class="widget-content">
                            <div class="account-box">

                                <div class="info-box">
                                    <div class="row">
                                        <div class="col-lg-3 col-3">
                                            <div class="icon">
                                                <span>
                                                <i class="fa fa-file-text"></i>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-lg-9 col-9">
                                            <div class="balance-info">
                                                <h6>Form EB</h6>
                                                <h5>License EB</h5>
                                                 <h4> Completed <span class="badge badge-success">0</span></h4>
                                                 <a href="{{ route('admin.view_auditor')  }}" class="">  <h4> Pending <span class="badge badge-warning">0</span></h4></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->

                <!-- --------------------------------------------------------- -->
                
                <!-- <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-card-five bg-gray color_forms">
                        <div class="widget-content">
                            <div class="account-box">

                                <div class="info-box">
                                    <div class="row">
                                        <div class="col-lg-3 col-3">
                                            <div class="icon">
                                                <span>
                                                <i class="fa fa-file-text"></i>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-lg-9 col-9">
                                            <div class="balance-info">
                                                <h6>Form ESB</h6>
                                                <h5>License ESB</h5>
                                                 <h4> Completed <span class="badge badge-success">0</span></h4>
                                                 <a href="{{ route('admin.view_auditor')  }}" class="">  <h4> Pending <span class="badge badge-warning">0</span></h4></a>

                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div> -->
                <!-- ------------------------------------------------------------ -->

                
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-card-five bg-thickgreen color_forms">
                        <div class="widget-content">
                            <div class="account-box">

                                <div class="info-box">
                                    <div class="row">
                                        <div class="col-lg-3 col-3">
                                            <div class="icon">
                                                <span>
                                                <i class="fa fa-file-text"></i>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-lg-9 col-9">
                                            <div class="balance-info">
                                                <h6>Form A</h6>
                                                <h5>License EA</h5>
                                                <!-- <span class="g-dot-success"></span> -->
                                                 <a href="{{ route('admin.view_forma_completed') }}"> <h4>Completed <span class="badge badge-success">{{ $auditorForma_pendings->completed_count }}</span> </h4> </a>
                                                 <a href="{{ route('admin.view_forma_pending')  }}" class=""> <h4> Pending <span class="badge badge-warning">{{ $auditorForma_pendings->pending_count }}</span></h4></a>
                                                <!-- <h5> Completed <span class="badge badge-success">0</span></h5>
                                                <h5> Pending <span class="badge badge-warning">0</span></h5> -->

                                            </div>
                                        </div>

                                    </div>

                                </div>
                              
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ------------------------------------------------------------ -->

                <!-- <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-card-five bg-thickblue color_forms">
                        <div class="widget-content">
                            <div class="account-box">

                                <div class="info-box">
                                    <div class="row">
                                        <div class="col-lg-3 col-3">
                                            <div class="icon">
                                                <span>
                                                <i class="fa fa-file-text"></i>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-lg-9 col-9">
                                            <div class="balance-info">
                                                <h6>Form ESA</h6>
                                                <h5>License ESA</h5>
                                                 <h4> Completed <span class="badge badge-success">0</span></h4>
                                                 <a href="{{ route('admin.view_auditor')  }}"> <h4> Pending <span class="badge badge-warning">0</span></h4></a>

                                            </div>
                                        </div>

                                    </div>

                                </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>

        </div>

    </div>
</div>

@include('admin.include.footer');
