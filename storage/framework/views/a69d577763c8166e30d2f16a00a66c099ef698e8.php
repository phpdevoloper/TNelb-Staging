<?php echo $__env->make('include.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<style>
    td {
        font-size: 15px;
    }
</style>
<section class="dashboard-panel">
    <div class="layout-login">
        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar -->
                

                <?php echo $__env->make('include.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                <main class="main-content-login">
                    <!-- Tasks and Projects Section -->
                    <section class="tasks-projects-login">


                        <!-- Projects -->
                        <div class="projects-section-login">
                            <h5 class="mb-2"><strong>Active / Present License Details</strong></h5>
                            <div class="project-list-login mt-2">

                                <div class="project-card-login" data-status="en-cours">
                                    <?php if(!$present_license && !$present_license_ea): ?>
                                        <div class="row">
                                            <div class="col-12">
                                                <p>No Active Licenses</p>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <?php $__empty_1 = true; $__currentLoopData = $present_license; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $workflow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <div class="row" style="border: none;">
                                            <div class="col-6 col-lg-4">
                                                <?php
                                                    $licenses = ['C', 'B', 'W', 'WH'];
                                                    $category = in_array($workflow->license_name, $licenses)
                                                        ? 'Competency Certificate'
                                                        : 'Contractor License';
                                                ?>
                                                <p><strong>License:</strong> <?php echo e($workflow->license_name ?? 'NA'); ?>

                                                    (<?php echo e($category); ?>)</p>
                                            </div>
                                            <div class="col-6 col-lg-3">
                                                <p><strong>Issued On:</strong>
                                                    <?php echo e($workflow->issued_at ? \Carbon\Carbon::parse($workflow->issued_at)->format('d-m-Y') : 'N/A'); ?>

                                                </p>

                                            </div>
                                            <div class="col-6 col-lg-3">
                                                <p>
                                                    <strong>Validity Upto:</strong>
                                                    <span>
                                                        <?php echo e($workflow->expires_at ? \Carbon\Carbon::parse($workflow->expires_at)->format('d-m-Y') : 'N/A'); ?>

                                                    </span>
                                                </p>
                                            </div>

                                            <div class="col-6 col-lg-2">
                                                <p>
                                                    <strong>Status:</strong>
                                                    <span class="text-danger">
                                                        <?php if($workflow->expires_at && \Carbon\Carbon::parse($workflow->expires_at)->lte(\Carbon\Carbon::today())): ?>
                                                            <span class="badge badge-danger">Expired</span>
                                                        <?php else: ?>
                                                        <span class="badge badge-success">Active</span>
                                                        <?php endif; ?>
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        
                                    <?php endif; ?>


                                    <?php $__empty_1 = true; $__currentLoopData = $present_license_ea; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $workflow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <div class="row" style="border: none;">
                                            <div class="col-6 col-lg-4">
                                                <?php
                                                    $licenses = ['C', 'B', 'W', 'WH'];
                                                    $category = in_array($workflow->license_name, $licenses)
                                                        ? 'Competency Certificate'
                                                        : 'Contractor License';
                                                ?>
                                                <p><strong>License:</strong> <?php echo e($workflow->license_name ?? 'NA'); ?>

                                                    (<?php echo e($category); ?>)</p>
                                            </div>
                                            <div class="col-6 col-lg-3">
                                                <p><strong>Issued On:</strong>
                                                    <?php echo e($workflow->issued_at ? \Carbon\Carbon::parse($workflow->issued_at)->format('d-m-Y') : 'N/A'); ?>

                                                </p>

                                            </div>
                                            <div class="col-6 col-lg-3">
                                                <p>
                                                    <strong>Validity Upto:</strong>
                                                    <span>
                                                        <?php echo e($workflow->expires_at ? \Carbon\Carbon::parse($workflow->expires_at)->format('d-m-Y') : 'N/A'); ?>

                                                    </span>
                                                </p>
                                            </div>

                                            <div class="col-6 col-lg-2">
                                                <p>
                                                    <strong>Status:</strong>
                                                    <span class="text-danger">
                                                        <?php if($workflow->expires_at && \Carbon\Carbon::parse($workflow->expires_at)->lte(\Carbon\Carbon::today())): ?>
                                                            <span class="badge badge-danger">Expired</span>
                                                        <?php else: ?>
                                                           <span class="badge badge-success">Active</span>
                                                       
                                                        <?php endif; ?>
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <div class="row">
                                            <div class="col-12">
                                                
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Tasks -->
                        <?php if(isset($workflows_present) && $workflows_present->isNotEmpty()): ?>

                        <div class="mobile_formview d-block d-sm-none" >
                            <h5 class="mb-2"><strong>Status of Applications ( Competency Certificate )</strong></h5>

                            <?php $__currentLoopData = $workflows_present; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $workflow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="card mb-3 p-3 shadow-sm border rounded">
                                    <h6 class="mb-2">
                                        <strong>Application <?php echo e($index + 1); ?></strong>
                                    </h6>

                                    <p><strong>Form Type:</strong> Form <?php echo e(strtoupper($workflow->form_name ?? 'NA')); ?></p>
                                    <p><strong>Application ID:</strong> <?php echo e($workflow->application_id ?? 'NA'); ?></p>
                                    <p><strong>Applied On:</strong> 
                                        <?php echo e(isset($workflow->created_at) ? \Carbon\Carbon::parse($workflow->created_at)->format('d/m/Y') : 'NA'); ?>

                                    </p>

                                    <!-- Application Status -->
                                    <p><strong>Application Status:</strong>
                                        <?php if($workflow->payment_status == 'draft'): ?>
                                            <?php
                                                $view_page = isset($workflow->appl_type) && $workflow->appl_type == 'R'
                                                    ? 'renew_formcc'
                                                    : 'edit_application';
                                            ?>
                                            <a href="<?php echo e(route($view_page, ['application_id' => $workflow->application_id])); ?>" class="btn btn-warning btn-sm">
                                                <i class="fa fa-pencil"></i> Draft
                                            </a>
                                        <?php else: ?>
                                            <?php if($workflow->appl_type == 'R'): ?>
                                                <?php if($workflow->status == 'P'): ?>
                                                    <span class="badge badge-warning">Renewal Form Submitted</span>
                                                <?php elseif($workflow->status == 'F'): ?>
                                                    <span class="badge badge-danger">In Progress</span>
                                                <?php else: ?>
                                                    <span class="badge badge-success">Completed</span>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <?php if($workflow->status == 'P'): ?>
                                                    <span class="badge badge-primary">Submitted</span>
                                                <?php elseif($workflow->status == 'F'): ?>
                                                    <span class="badge badge-danger">In Progress</span>
                                                <?php else: ?>
                                                    <span class="badge badge-success">Completed</span>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </p>

                                    <!-- Payment Status -->
                                    <p><strong>Payment Status:</strong>
                                        <?php if($workflow->payment_status == 'payment'): ?>
                                            <span class="text-success"><strong>Success</strong></span>
                                        <?php else: ?>
                                            <span class="text-warning"><strong>Pending</strong></span>
                                        <?php endif; ?>
                                    </p>

                                    <!-- Payment Receipt -->
                                    <p><strong>Payment Receipt:</strong>
                                        <?php if($workflow->payment_status == 'payment'): ?>
                                            <a href="<?php echo e(route('paymentreceipt.pdf', ['loginId' => $workflow->application_id])); ?>" target="_blank">
                                                <i class="fa fa-file-pdf-o" style="font-size:20px;color:red"></i>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-warning">Pending</span>
                                        <?php endif; ?>
                                    </p>

                                    <!-- Application Download -->
                                    <p><strong>Application Download:</strong>
                                        <?php if($workflow->payment_status == 'draft'): ?>
                                            <span>-</span>
                                        <?php else: ?>
                                            <a href="<?php echo e(route('generate.tamil.pdf', ['login_id' => $workflow->application_id])); ?>" target="_blank">
                                                <i class="fa fa-file-pdf-o" style="font-size:20px;color:red"></i> <span style="font-size: small;">தமிழ்</span>
                                            </a>
                                            &nbsp;<br>
                                            <a href="<?php echo e(route('generate.pdf', ['login_id' => $workflow->application_id])); ?>" target="_blank">
                                                <i class="fa fa-file-pdf-o" style="font-size:20px;color:red"></i> <span style="font-size: small;">English</span>
                                            </a>
                                        <?php endif; ?>
                                    </p>

                                    <!-- License Status -->
                                    <p><strong>License Status:</strong>
                                        <?php //var_dump($workflow->appl_type); ?>
                                        <?php if(!empty($workflow->license_number) && $workflow->status == 'A'): ?>
                                            <a href="<?php echo e(route('admin.generate.pdf', ['application_id' => $workflow->application_id])); ?>" target="_blank">
                                                <span class="badge badge-info" style="font-size: 15px;"><?php echo e($workflow->license_number); ?></span>
                                            </a>
                                            <?php
                                                $license_details = DB::table('tnelb_application_tbl')
                                                    ->where('license_number', $workflow->license_number)
                                                    ->first();

                                                $renewed = DB::table('tnelb_renewal_license')
                                                    ->where('license_number', $workflow->license_number)
                                                    ->first();

                                                


                                            ?>
                                            <br>
                                            <?php if(isset($renewed) && !empty($renewed)): ?>

                                            <?php elseif(isset($license_details->application_id) && !empty($license_details->application_id)): ?>
                                                <strong>Renewal Application</strong><br>
                                                ID: <span class="text-success"><?php echo e($license_details->application_id); ?></span>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <?php if($workflow->appl_type == 'R'): ?>
                                                <?php
                                                    $renewed1 = DB::table('tnelb_renewal_license')
                                                        ->where('application_id', $workflow->application_id)
                                                        ->first();
                                                         $workflow->renewed_license_number = $renewed1->license_number ?? 'NA';
                                                ?>
                                            <?php else: ?>
                                                <span class="text-primary">NA</span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </p>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> 

                        </div>   
                        <!-- ----------------- -->
                        <div class="tasks-section-login d-none d-sm-block">
                            <h5 class="mb-2"><strong>Status of Applications ( Competency Certificate )</strong></h5>
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
                                        <th>License Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                        <?php $__currentLoopData = $workflows_present; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $workflow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($index + 1); ?></td>
                                                <td>Form <?php echo e(strtoupper($workflow->form_name ?? 'NA')); ?></td>
                                                <td><?php echo e($workflow->application_id ?? 'NA'); ?></td>
                                                <td><?php echo e(isset($workflow->created_at) ? \Carbon\Carbon::parse($workflow->created_at)->format('d/m/Y') : 'NA'); ?>

                                                </td>

                                                <!-- Application Status -->
                                                <td>
                                                    <?php if($workflow->payment_status == 'draft'): ?>
                                                        <?php
                                                            $view_page =
                                                                isset($workflow->appl_type) &&
                                                                $workflow->appl_type == 'R'
                                                                    ? 'renew_formcc'
                                                                    : 'edit_application';

                                                        ?>
                                                        <a
                                                            href="<?php echo e(route($view_page, ['application_id' => $workflow->application_id])); ?>">
                                                            <button class="btn btn-warning">
                                                                <i class="fa fa-pencil"></i> Draft
                                                            </button>
                                                        </a>
                                                    <?php else: ?>
                                                        <?php if($workflow->appl_type == 'R'): ?>
                                                            <?php if($workflow->status == 'P'): ?>
                                                                <span class="btn btn-sm btn-primary">Submitted</span>
                                                            <?php elseif($workflow->status == 'F'): ?>
                                                                <span class="btn btn-danger">In Progress</span>
                                                             <?php elseif($workflow->status == 'RJ'): ?>
                                                                <span class="btn btn-danger">Rejected</span>
                                                            <?php else: ?>
                                                                <span class="btn btn-sm btn-success">Completed</span>
                                                            <?php endif; ?>
                                                        <?php else: ?>
                                                            <?php if($workflow->status == 'P'): ?>
                                                                <span class="btn btn-sm btn-primary">Submitted</span>
                                                            <?php elseif($workflow->status == 'F'): ?>
                                                                <span class="btn btn-danger">In Progress</span>
                                                            <?php elseif($workflow->status == 'RJ'): ?>
                                                                <span class="btn btn-danger">Rejected</span>
                                                            <?php else: ?>
                                                                <span class="btn btn-sm btn-success">Completed</span>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                </td>

                                                <!-- Payment Status -->
                                                <td>
                                                    <?php if($workflow->payment_status == 'payment'): ?>
                                                    <p class="text-success"><strong>Success</strong></p>
                                                    <?php else: ?>
                                                        <p class="text-warning"><strong>Pending</strong></p>
                                                    <?php endif; ?>
                                                </td>

                                                <td>
                                                    <?php if($workflow->payment_status == 'payment'): ?>
                                                        <a href="<?php echo e(route('paymentreceipt.pdf', ['loginId' => $workflow->application_id])); ?>"
                                                            target="_blank" rel="noopener noreferrer"
                                                            title="Download Payment Receipt PDF"
                                                            style="font-weight:500;">
                                                            <i
                                                                class="fa fa-file-pdf-o"style="font-size:20px;color:red"></i>
                                                            
                                                        </a>
                                                    <?php else: ?>
                                                        <p class="text-warning">Pending</p>
                                                    <?php endif; ?>
                                                </td>

                                                <!-- Application Download -->
                                                <td>

                                                    
                                                        <?php if($workflow->payment_status == 'draft'): ?>
                                                            <p>-</p>
                                                        <?php else: ?>
                                                            <a href="<?php echo e(route('generate.tamil.pdf', ['login_id' => $workflow->application_id])); ?>"
                                                                target="_blank"
                                                                style="border-right:1px solid #000;font-weight:500;">
                                                                <i class="fa fa-file-pdf-o"
                                                                    style="font-size:20px;color:red"></i> <span
                                                                    style="font-size: x-small;">தமிழ்</span>
                                                            </a>

                                                            <a href="<?php echo e(route('generate.pdf', ['login_id' => $workflow->application_id])); ?>"
                                                                target="_blank" style="font-weight:500;">&nbsp;
                                                                <i class="fa fa-file-pdf-o"
                                                                    style="font-size:20px;color:red"></i> <span
                                                                    style="font-size: x-small;"> English</span>
                                                            </a>
                                                        <?php endif; ?>
                                                    
                                                </td>

                                                <!-- License Number -->

                                                <td>
                                                        <?php if(!empty($workflow->license_number) && $workflow->status == 'A'): ?>
                                                        <a href="<?php echo e(route('admin.generate.pdf', ['application_id' => $workflow->application_id])); ?>" target="_blank"> 
                                                            <span class="badge badge-info" style="font-size: 15px;"><?php echo e($workflow->license_number); ?></span>
                                                        </a>
                                                        <br>

                                                        <?php if(!empty($workflow->renewals)): ?>
                                                            <span class="text-muted" style="font-size: 12px;">
                                                                Renewed <?php echo e(count($workflow->renewals)); ?> times
                                                            </span>
                                                            <br>
                                                        <?php endif; ?>

                                                        <?php if(!empty($workflow->expires_at) && \Carbon\Carbon::parse($workflow->expires_at)->lt(\Carbon\Carbon::now())): ?>
                                                            <?php if(!empty($workflow->renewal_application_id)): ?>
                                                                <strong>Renewal Application</strong><br>
                                                                ID :
                                                                <a href="<?php echo e(route('generate.pdf', ['login_id' => $workflow->renewal_application_id])); ?>" 
                                                                target="_blank" 
                                                                class="text-success">
                                                                <?php echo e($workflow->renewal_application_id); ?>

                                                                </a>
                                                            <?php else: ?>
                                                                <a href="<?php echo e(route('renew_form', ['application_id' => $workflow->application_id])); ?>"
                                                                class="text-primary">
                                                                (Apply for renewal)
                                                                </a>
                                                            <?php endif; ?>
                                                        <?php endif; ?>

                                                    <?php elseif(!empty($workflow->renewal_application_id)): ?>
                                                        <strong>Renewal Application</strong><br>
                                                        ID :
                                                        <a href="<?php echo e(route('generate.pdf', ['login_id' => $workflow->renewal_application_id])); ?>" 
                                                        target="_blank" 
                                                        class="text-success">
                                                        <?php echo e($workflow->renewal_application_id); ?>

                                                        </a>

                                                    <?php else: ?>
                                                        <p class="text-primary">NA</p>
                                                    <?php endif; ?>
                                                </td>




                                                
                                                
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                        <?php endif; ?>

                        <!-- ---------------------------------------------------------- -->
                        <?php if(isset($workflows_cl) && $workflows_cl->isNotEmpty()): ?>
                        <div class="mobile_formview d-block d-sm-none">
                            <h5 class="mb-2"><strong>Status of Applications ( Contractor License )</strong></h5>

                            <?php if(isset($workflows_cl) && $workflows_cl->isNotEmpty()): ?>
                                <?php $__currentLoopData = $workflows_cl; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $workflow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="card mb-3 p-3 border rounded shadow-sm">
                                        <h6 class="mb-2"><strong>Application <?php echo e($index + 1); ?></strong></h6>

                                        <p><strong>Form Type:</strong> Form <?php echo e(strtoupper($workflow->form_name ?? 'N/A')); ?></p>
                                        <p><strong>Application ID:</strong> <?php echo e($workflow->application_id ?? 'N/A'); ?></p>
                                        <p><strong>Applied On:</strong> <?php echo e(isset($workflow->created_at) ? \Carbon\Carbon::parse($workflow->created_at)->format('d/m/Y') : 'N/A'); ?></p>

                                        <!-- Application Status -->
                                        <p><strong>Application Status:</strong>
                                            <?php if($workflow->payment_status == 'draft'): ?>
                                                <?php if(strtoupper(trim($workflow->appl_type)) === 'N'): ?>
                                                    <a href="<?php echo e(route('apply-form-a_draft', ['application_id' => $workflow->application_id])); ?>">
                                                        <button class="btn btn-primary btn-sm">
                                                            <i class="fa fa-pencil"></i> Draft
                                                        </button>
                                                    </a>
                                                <?php else: ?>
                                                    <a href="<?php echo e(route('apply-form-a_renewal_draft', ['application_id' => $workflow->application_id])); ?>">
                                                        <button class="btn btn-primary btn-sm">
                                                            <i class="fa fa-pencil"></i> Draft
                                                        </button>
                                                    </a>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <?php if($workflow->appl_type == 'R'): ?>
                                                    <?php if($workflow->application_status == 'P'): ?>
                                                        <span class="badge bg-warning">Renewal Form Submitted</span>
                                                    <?php elseif($workflow->application_status == 'F'): ?>
                                                        <span class="badge bg-danger">In Progress</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-success">Completed</span>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <?php if($workflow->application_status == 'P'): ?>
                                                        <span class="badge bg-primary">Submitted</span>
                                                    <?php elseif($workflow->application_status == 'F'): ?>
                                                        <span class="badge bg-danger">In Progress</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-success">Completed</span>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </p>

                                        <!-- Payment Status -->
                                        <p><strong>Payment Status:</strong>
                                            <?php if($workflow->payment_status == 'paid'): ?>
                                                <span class="text-success">Success</span>
                                            <?php else: ?>
                                                <span class="text-primary">Pending</span>
                                            <?php endif; ?>
                                        </p>

                                        <!-- Payment Receipt -->
                                        <p><strong>Payment Receipt:</strong>
                                            <?php if($workflow->payment_status == 'paid'): ?>
                                                <a href="<?php echo e(route('paymentreceipt.pdf', ['loginId' => $workflow->application_id])); ?>" 
                                                    target="_blank" title="Download Payment Receipt PDF">
                                                    <i class="fa fa-file-pdf-o" style="font-size:20px;color:red"></i>
                                                </a>
                                            <?php else: ?>
                                                <span class="text-primary">Pending</span>
                                            <?php endif; ?>
                                        </p>

                                        <!-- Application Download -->
                                        <p><strong>Application Download:</strong>
                                            <?php if($workflow->payment_status == 'draft'): ?>
                                                <span>-</span>
                                            <?php else: ?>
                                                <a href="<?php echo e(route('generatea.pdf', ['login_id' => $workflow->application_id])); ?>" target="_blank">
                                                    <i class="fa fa-file-pdf-o" style="font-size:20px;color:red"></i>
                                                    <span style="font-size: x-small;">English</span>
                                                </a>
                                            <?php endif; ?>
                                        </p>

                                        <!-- License Number -->
                                        <p><strong>License Number:</strong>
                                            <?php if(!empty($workflow->license_number) && $workflow->application_status == 'A'): ?>
                                                <a href="<?php echo e(route('admin.generateForma.pdf', ['application_id' => $workflow->application_id])); ?>" target="_blank">
                                                    <span class="badge bg-info" style="font-size: 15px;">
                                                        <?php echo e($workflow->license_number); ?>

                                                    </span>
                                                </a>

                                                <?php
                                                    $license_details = DB::table('tnelb_application_tbl')
                                                        ->where('license_number', $workflow->license_number)
                                                        ->first();

                                                         $new_license_details = DB::table('tnelb_ea_applications')
                                                        ->where('license_number', $workflow->license_number)
                                                        ->first();

                                                    $renewed = DB::table('tnelb_renewal_license')
                                                        ->where('license_number', $workflow->license_number)
                                                        ->first();
                                                ?>

                                                <br>
                                                <?php if(isset($renewed) && !empty($renewed)): ?>
                                                    
                                                <?php elseif(isset($license_details->application_id) && !empty($license_details->application_id)): ?>
                                                    <strong>Renewal Application:</strong> 
                                                    <span class="text-success"><?php echo e($license_details->application_id); ?></span>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="text-primary">NA</span>
                                            <?php endif; ?>
dd($workflow->license_number);
exit;
                                            <?php if(empty($license_details)): ?>
                                                <?php if(isset($workflow->license_number) && \Carbon\Carbon::parse($workflow->expires_at)->lt(\Carbon\Carbon::now())): ?>
                                                    <br>
                                                    <a href="<?php echo e(route('renew-form_ea', ['application_id' => $workflow->application_id])); ?>" class="text-primary">
                                                        (Apply for renewal)
                                                    </a>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <p class="text-danger">No records found</p>
                            <?php endif; ?>

                        </div>    
                        

                        <div class="tasks-section-login d-none d-sm-block">
                            <h5 class="mb-2"><strong>Status of Applications ( Contractor License )</strong></h5>
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
                                        <th>License Number</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(isset($workflows_cl) && $workflows_cl->isNotEmpty()): ?>
                                    <?php $__currentLoopData = $workflows_cl; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $workflow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php //var_dump($workflow);die;
                                    ?>
                                    <tr>
                                        <td><?php echo e($index + 1); ?></td>
                                        <td>Form <?php echo e(strtoupper($workflow->form_name ?? 'N/A')); ?></td>
                                        <td><?php echo e($workflow->application_id ?? 'N/A'); ?></td>
                                        <td><?php echo e(isset($workflow->created_at) ? \Carbon\Carbon::parse($workflow->created_at)->format('d/m/Y') : 'N/A'); ?>

                                        </td>

                                        <!-- Application Status -->
                                        <td>
                                            <?php if($workflow->payment_status == 'draft'): ?>

    
                                            <?php if(strtoupper(trim($workflow->appl_type)) === 'N'): ?>
                                            <a href="<?php echo e(route('apply-form-a_draft', ['application_id' => $workflow->application_id])); ?>">
                                                <button class="btn btn-primary">
                                                    <i class="fa fa-pencil"></i> Draft
                                                </button>
                                            </a>
                                            <?php else: ?>
                                            <a href="<?php echo e(route('apply-form-a_renewal_draft', ['application_id' => $workflow->application_id])); ?>">
                                                <button class="btn btn-primary">
                                                    <i class="fa fa-pencil"></i> Draft
                                                </button>
                                            </a>
                                            <?php endif; ?>

                                            <?php else: ?>
                                            <?php if($workflow->appl_type == 'R'): ?>
                                            <?php if($workflow->application_status == 'P'): ?>
                                            <span class="btn btn-sm btn-warning">Renewal Form
                                                Submitted</span>
                                            <?php elseif($workflow->application_status == 'F'): ?>
                                            <span class="btn btn-danger">In Progress</span>
                                            <?php else: ?>
                                            <span class="btn btn-sm btn-success">Completed</span>
                                            <?php endif; ?>
                                            <?php else: ?>
                                            <?php if($workflow->application_status == 'P'): ?>
                                            <span class="btn btn-sm btn-primary">Submitted</span>
                                            <?php elseif($workflow->application_status == 'F'): ?>
                                            <span class="btn btn-danger">In Progress</span>
                                            <?php else: ?>
                                            <span class="btn btn-sm btn-success">Completed</span>
                                            <?php endif; ?>
                                            <?php endif; ?>
                                            <?php endif; ?>
                                        </td>

                                        <!-- Payment Status -->
                                        <td>
                                            <?php if($workflow->payment_status == 'paid'): ?>
                                            <p class="text-success">Success</p>
                                            <?php else: ?>
                                            <p class="text-primary">Pending</p>
                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            <?php if($workflow->payment_status == 'paid'): ?>
                                            <a href="<?php echo e(route('paymentreceipt.pdf', ['loginId' => $workflow->application_id])); ?>"
                                                target="_blank" rel="noopener noreferrer"
                                                title="Download Payment Receipt PDF"
                                                style="font-weight:500;">
                                                <i class="fa fa-file-pdf-o"
                                                    style="font-size:20px;color:red"></i>
                                            </a>
                                            <?php else: ?>
                                            <p class="text-primary">Pending</p>
                                            <?php endif; ?>
                                        </td>

                                        <!-- Application Download -->
                                        <td>
                                            <?php if($workflow->payment_status == 'draft'): ?>
                                            <p>-</p>
                                            <?php else: ?>
                                            <a href="<?php echo e(route('generatea.pdf', ['login_id' => $workflow->application_id])); ?>"
                                                target="_blank" style="font-weight:500;">
                                                <i class="fa fa-file-pdf-o"
                                                    style="font-size:20px;color:red"></i>
                                                <span style="font-size: x-small;">English</span>
                                            </a>
                                            <?php endif; ?>
                                        </td>

                                        <!-- License Number -->
                                   <td>
                                                        <?php if(!empty($workflow->license_number) && $workflow->application_status == 'A'): ?>
                                                        <a href="<?php echo e(route('admin.generateForma.pdf', ['application_id' => $workflow->application_id])); ?>" target="_blank"> 
                                                            <span class="badge badge-info" style="font-size: 15px;"><?php echo e($workflow->license_number); ?></span>
                                                        </a>
                                                        <br>

                                                        <?php if(!empty($workflow->renewals)): ?>
                                                            <span class="text-muted" style="font-size: 12px;">
                                                                Renewed <?php echo e(count($workflow->renewals)); ?> times
                                                            </span>
                                                            <br>
                                                        <?php endif; ?>

                                                        <?php if(!empty($workflow->expires_at) && \Carbon\Carbon::parse($workflow->expires_at)->lt(\Carbon\Carbon::now())): ?>
                                                            <?php if(!empty($workflow->renewal_application_id)): ?>
                                                                <strong>Renewal Application</strong><br>
                                                                ID :
                                                                <a href="<?php echo e(route('generate.pdf', ['login_id' => $workflow->renewal_application_id])); ?>" 
                                                                target="_blank" 
                                                                class="text-success">
                                                                <?php echo e($workflow->renewal_application_id); ?>

                                                                </a>
                                                            <?php else: ?>
                                                                <a href="<?php echo e(route('renew-form_ea', ['application_id' => $workflow->application_id])); ?>"
                                                                class="text-primary">
                                                                (Apply for renewal)
                                                                </a>
                                                            <?php endif; ?>
                                                        <?php endif; ?>

                                                    <?php elseif(!empty($workflow->renewal_application_id)): ?>
                                                        <strong>Renewal Application</strong><br>
                                                        ID :
                                                        <a href="<?php echo e(route('generate.pdf', ['login_id' => $workflow->renewal_application_id])); ?>" 
                                                        target="_blank" 
                                                        class="text-success">
                                                        <?php echo e($workflow->renewal_application_id); ?>

                                                        </a>

                                                    <?php else: ?>
                                                        <p class="text-primary">NA</p>
                                                    <?php endif; ?>
                                                </td>

                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php else: ?>
                                    <tr>
                                        <td colspan="9" class="text-center text-danger">No records found</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>

                        </div>
                        <?php endif; ?>
                    </section>
                </main>
            </div>
        </div>
    </div>
</section>



<footer class="main-footer">
    <?php echo $__env->make('include.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php if(session('already_applied')): ?>
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
    <?php endif; ?>
    <script>
        // document.addEventListener("DOMContentLoaded", function () {
        //     const filterSelect = document.getElementById("filter-status-login");

        //     if (!filterSelect) {
        //         console.error("Element #filter-status-login not found in DOM.");
        //         return;
        //     }

        //     filterSelect.addEventListener("change", function(e) {
        //         const filter = e.target.value;

        //         // Filter projects
        //         document.querySelectorAll(".project-card-login").forEach(card => {
        //             if (filter === "all" || card.dataset.status === filter) {
        //                 card.style.display = "block";
        //             } else {
        //                 card.style.display = "none";
        //             }
        //         });

        //         // Filter tasks
        //         document.querySelectorAll(".table-login tbody tr").forEach(row => {
        //             if (filter === "all" || row.dataset.status === filter) {
        //                 row.style.display = "";
        //             } else {
        //                 row.style.display = "none";
        //             }
        //         });
        //     });
        // });
    </script>

    <script>
        $(document).ready(function() {
            $(document).on('click', '.clicktopayment', function() {
                console.log('sdfdsf');
            });
        });
    </script>
<?php /**PATH D:\xampp\hddocs\TNelb-Staging\resources\views/user_login/index.blade.php ENDPATH**/ ?>