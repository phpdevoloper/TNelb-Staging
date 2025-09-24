<?php echo $__env->make('include.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<section class="banner-section mt-0">
    <div class="swiper-container banner-slider" id="homeBannerSlider">
        <div class="swiper-wrapper">
            <?php $__currentLoopData = $sliders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slider): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <?php if($slider->media): ?>
            <div class="swiper-slide" style="background-image: url('<?php echo e(asset($slider->media->filepath_img_pdf)); ?>');">
                  
                                                <?php endif; ?>
                <div class="content-outer">
                    <div class="content-box">
                        <div class="inner">
                            <h1>
                                <!-- Welcome to <br> -->
                                <?php echo e($slider->slider_caption ?? ''); ?>

                            </h1>

                            <!-- <div class="link-box">
                                <a href="#" class="theme-btn btn-style-one"><span>Know More</span></a>

                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <!-- Slide Item -->

        </div>
    </div>
    <div class="banner-slider-nav">
        <div class="banner-slider-control banner-slider-button-prev"><span><i class="icon-arrow"></i></span>
        </div>
        <div class="banner-slider-control banner-slider-button-next"><span><i class="icon-arrow"></i></span>
        </div>
    </div>

    <!-- Stop Button -->
    <div class="stop-slider-container">
        <button class="stop-slider theme-btn btn-style-two"><i class="fa fa-pause"></i></button>
    </div>

</section>




<!-- End Bnner Section -->

<!-- Events section two -->
<section class="events-section-two">
    <div class="">
        <div class="event-wrapper scrollmsg">
            <div class="event-block-two">
                <div class="inner-box">
                    <div class="row align-items-center">

                        <div class="col-lg-1 col-md-1ss col-12 text-center mb-2">
                            <div class="image">
                                <!-- <img src="assets/images/whatsnew.png" alt="" class="img-fluid"> -->
                                <!-- <i class="fa fa-exclamation-circle"></i> -->
                            </div>
                        </div>
                        <!-- Date Column -->
                        <div class="col-lg-2 col-md-4 col-12  mb-2">
                            <div class="date  border_right">What's New</div>
                        </div>
                        <!-- Marquee Column -->
                        <div class="col-lg-8 col-md-5 col-12">
                            <div class="text-left">


                                <marquee id="newsMarquee" behavior="scroll" direction="left" onmouseover="this.stop();" onmouseout="this.start();">
                                    <h4 class="marquee-text">

                                        <?php $__empty_1 = true; $__currentLoopData = $whatsnew ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $news): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        || &nbsp;
                                        <?php if($news->page_type == 'url'): ?>

                                        <a href="<?php echo e($news->external_url); ?>" target="_blank">
                                            <?php echo $news->subject_en ?? 'No News Available'; ?> <span> <i class="fa fa-external-link"></i> </span> &nbsp; &nbsp;
                                        </a>
                                        <?php elseif($news->page_type == 'pdf'): ?>
                                        <a href="<?php echo e(asset($news->pdf_ta)); ?>" target="_blank">
                                            <?php echo $news->subject_en ?? 'No News Available'; ?> <span> <i class="fa fa-file-pdf-o"></i></span>
                                            &nbsp;
                                        </a>
                                        <?php else: ?>
                                        <?php echo $news->subject_en ?? 'No News Available'; ?> <i class="fa fa-folder-open-o"></i>
                                        &nbsp;
                                        <?php endif; ?>
                                        <!-- <i class='fas fa-caret-square-right'></i> -->
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        No News Available
                                        &nbsp; &nbsp;
                                        <?php endif; ?>
                                    </h4>
                                </marquee>
                            </div>
                        </div>
                        <div class="col-lg-1 col-md-5 col-12">
                            <div class="text-left">

                                <button id="toggleMarquee" class="btn btn-danger  mb-2"><i class="fa fa-pause"></i></button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="about-section events-section">
    <div class="auto-container">

        <div class="row">
            <div class="col-lg-8">
                <div class="sec-title ">


                    <h2>About</h2>
                </div>
                    <?php
                use Illuminate\Support\Str;
                ?>

                <div class="text text-dark" style="color:#000!important;font-size:15px;">
                    <?php echo Str::words($aboutus->menucontent ?? 'No content available', 200, '...'); ?>

                </div>
            </div>
            <!-- <div class="col-lg-3 team-block"> -->
            <!-- <div class="inner-box">
                    <div class="image"><img src="../CMS_MGMT_portal/uploads/about/about_image_1737541294.jpg" alt=""></div>
                    <div class="content">
                        <h4>Secretary</h4>
                        <div class="designation">Electrical LICENSE Board,
                            Thiru.Vi.Ka.Indl.Estate,
                            Guindy. Chennai – 600 032</div>
                    </div>
                    <div class="overlay">
                                <div class="content-two">
                                    <h4>Paul Wilson</h4>
                                    <div class="designation">mayor / Chairman</div>
                                    <ul class="contact-info">
                                        <li><a href="tel:+1(852)6105599"><i class="fas fa-phone"></i>+ 1 (852) 610
                                                5599</a></li>
                                        <li><a href="tel:+1(852)6105599"><i class="fas fa-envelope-open"></i>+ 1 (852)
                                                610 5599</a></li>
                                    </ul>
                                    <ul class="social-links">
                                        <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                        <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                        <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                                        <li><a href="#"><i class="fab fa-linked-in"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                </div> -->

             <div class="col-lg-4 event-block mt-5 news">
                <div class="inner-box wow fadeInUp animated" data-wow-delay="0ms" data-wow-duration="1500ms" style="visibility: visible; animation-duration: 1500ms; animation-delay: 0ms; animation-name: fadeInUp;">

                    <div class="lower-content ">

                        <!-- <div class="date">
                            
                            <img class="blinking-text" src="assets/images/new.png">

                        </div> -->
                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                                               <h5 class="pt-10 mt-2 mb-2 notice-board" style="    font-size: 30px;
    text-transform: uppercase;">Notice Board</h5>

                            </div>

                        </div>


                        <!-- <div class="location"><i class="fas fa-map-marker-alt"></i>The fees structure for new issue and renewal of contractor licenses and competency certificates are revised w.e.f. 01-01-2024</div> -->

                        <marquee id="scrollMarquee" behavior="scroll" onmouseover="this.stop();" onmouseout="this.start();" direction="up" scrollamount="2" height="200">
                            <?php $__empty_1 = true; $__currentLoopData = $newsboards ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $news): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php if($news->page_type === 'url'): ?>
                            <p><a href="<?php echo e($news->external_url); ?>" target="_blank"><?php echo e($news->subject_en); ?> <i class="fa fa-external-link"></i> </a> </p>
                            <?php elseif($news->page_type === 'pdf'): ?>
                            <p><a href="<?php echo e(asset($news->pdf_en)); ?>" target="_blank"><?php echo e($news->subject_en); ?> <i class="fa fa-file-pdf-o"></i></a></p>
                            <?php elseif($news->page_type === 'Static Page'): ?>
                            <p>
                                <a href="<?php echo e(route('noticeboardcontent', ['news_id' => $news->id])); ?>"><?php echo e($news->subject_en); ?> <i class="fa fa-folder-open-o"></i></a>

                            </p>
                            <?php else: ?>
                            <p><?php echo e($news->subject_en); ?></p>
                            <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <p>No Newsboard Available</p>
                            <?php endif; ?>
                        </marquee>
                        <div class="row">

                            <div class="col-lg-12 col-md-12 text-right">

                                <button id="toggleScrollMarquee" class="btn btn-danger  mb-2"><i class="fa fa-pause"></i></button>
                            </div>

                        </div>




                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<hr>


<section class="services-section" style="background: #fbfbfb;">
    <div class="auto-container">


        <div class="row">
            <div class="col-lg-6">
                <div class="sec-title text-center">
                    <h2 style="color: #035ab3;">Competency Certificate</h2>
                </div>
                <div class="row">
                    <div class="col-md-6 service-block">
                        <div class="inner-box">
                            <div class="icon"><i class="icon-election"></i></div>
                            <div class="content">
                                <h4>Form H</h4>
                                <div class="text">LICENSE `WH'</div>
                                <div class="link-btn"><a href="<?php echo e(route('login')); ?>"><button class="btn btn-primary">Apply Now</button></a></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 service-block">
                        <div class="inner-box">
                            <div class="icon"><i class="icon-election"></i></div>
                            <div class="content">
                                <h4>Form W</h4>
                                <div class="text">LICENSE  `A'</div>
                                <div class="link-btn"><a href="<?php echo e(route('login')); ?>"><button class="btn btn-primary">Apply Now</button></a></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 service-block">
                        <div class="inner-box">
                            <div class="icon"><i class="icon-election"></i></div>
                            <div class="content">
                                <h4>Form S</h4>
                                <div class="text">LICENSE  `C'</div>
                                <div class="link-btn"><a href="<?php echo e(route('login')); ?>"><button class="btn btn-primary">Apply Now</button></a></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 service-block">
                        <div class="inner-box">
                            <div class="icon"><i class="icon-election"></i></div>
                            <div class="content">
                                <h4>Form PG</h4>
                                <div class="text">LICENSE  `P'</div>
                                <div class="link-btn"><a href="<?php echo e(route('login')); ?>"><button class="btn btn-primary">Apply Now</button></a></div>
                            </div>
                        </div>
                    </div>

                    <!-- <div class="col-md-6 service-block">
                        <div class="inner-box">
                            <div class="icon"><i class="icon-election"></i></div>
                            <div class="content">
                                <h4>Form H TO B</h4>
                                <div class="text">LICENSE  `H TO B'</div>
                                <div class="link-btn"><a href="<?php echo e(route('login')); ?>"><button class="btn btn-primary">Apply Now</button></a></div>
                            </div>
                        </div>
                    </div> -->

                </div>
            </div>
            <div class="col-lg-6">
            <div class="sec-title text-center">
                    <h2 style="color: #035ab3;">Contractor Licenses</h2>
                </div>
                <div class="row">

                    <div class="col-md-6 service-block">
                        <div class="inner-box">
                            <div class="icon1"><i class="icon-election"></i></div>
                            <div class="content">
                                <h4>Form EB</h4>
                                <div class="text">LICENSE  `EB'</div>
                                <div class="link-btn"><a href="<?php echo e(route('login')); ?>"><button class="btn btn-primary" style="background:#5b5d60;">Apply Now</button></a></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 service-block">
                        <div class="inner-box">
                            <div class="icon1"><i class="icon-election"></i></div>
                            <div class="content">
                                <h4>Form ESB</h4>
                                <div class="text">LICENSE  `SB'</div>
                                <div class="link-btn"><a href="<?php echo e(route('login')); ?>"><button class="btn btn-primary" style="background:#5b5d60;">Apply Now</button></a></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 service-block">
                        <div class="inner-box">
                            <div class="icon1"><i class="icon-election"></i></div>
                            <div class="content">
                                <h4>Form EA</h4>
                                <div class="text">LICENSE  `A'</div>
                                <div class="link-btn"><a href="<?php echo e(route('login')); ?>"><button class="btn btn-primary" style="background:#5b5d60;">Apply Now</button></a></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 service-block">
                        <div class="inner-box">
                            <div class="icon1"><i class="icon-election"></i></div>
                            <div class="content">
                                <h4>Form ESA</h4>
                                <div class="text">LICENSE  `SA'</div>
                                <div class="link-btn"><a href="<?php echo e(route('login')); ?>"><button class="btn btn-primary" style="background:#5b5d60;">Apply Now</button></a></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
<section class="services-section-three">
    <div class="auto-container">

    </div>
</section>


<!-- Services section three -->
<section class="services-section-three" style="background-image: url(assets/images/bg/project-counter.jpg);">
    <div class="auto-container">
        <div class="row align-items-center">

            <div class="col-xl-6">
                <div class="content-block">
                    <div class="sec-title style-two mb-20 text-center">
                        <h2 class="text-white">Services & Standards</h2>
                    </div>
                    <div class="text text-white">We render the highest standards of service to public. This charter set out the standards for various functions of Electrical LICENSE Board so as to improve our service to public. These service levels are our maximum response period and we strive to beat these standards every time we can.</div>
                    <div class="row">
                        <!-- <div class="col-md-2 col-lg-2"></div> -->
                        <div class="col-md-12 col-lg-12">
                            <table class="services">
                                <tbody>
                                    <tr>
                                        <td>Issue of Certificates</td>
                                        <td><i class="fa fa-long-arrow-right"></i></td>
                                        <td>30 days</td>
                                    </tr>
                                    <tr>

                                        <td>Renewal of Certificates</td>
                                        <td><i class="fa fa-long-arrow-right"></i></td>
                                        <td>20 days</td>

                                    </tr>
                                    <tr>

                                        <td>Issue of Contractor License</td>
                                        <td><i class="fa fa-long-arrow-right"></i></td>
                                        <td>One month</td>

                                    </tr>
                                    <tr>

                                        <td>Renewal of License</td>
                                        <td><i class="fa fa-long-arrow-right"></i></td>
                                        <td>20 days</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- <div class="col-md-6  col-lg-6">
                                    <ul class="list2">
                                        <li>
                                            Issue of Certificates</li>
                                        <li>Renewal of Certificates</li>
                                        <li>Issue of Contractor License</li>
                                        <li>Renewal of License</li>
                       
                                    </ul>
                                </div>
                                <div class="col-md-6  col-lg-6">
                                    <ul class="list">
                                        <li>30 days</li>
                                        <li>20 days</li>
                                        <li>One month</li>
                                        <li>20 days</li>
                                    </ul>
                                </div> -->
                    </div>
                    <!-- <div class="icon"><img src="assets/images/bg/service-icon.png" alt=""></div> -->
                </div>
            </div>

            <div class="col-xl-6">
                <div class="row align-items-center">

                    <div class="col-xl-6">
                        <div class="sec-title style-two mb-20 text-center">
                            <h2 class="text-white">Contractor Licenses</h2>
                        </div>

                        <div class="ourfacts">
                            <!--Column-->
                            <div class="column counter-column">
                                <div class="inner wow fadeInLeft" data-wow-delay="0ms" data-wow-duration="1500ms">
                                    <div class="icon-outer">
                                        <div class="icon"><span class="icon-area"></span></div>
                                    </div>
                                    <div class="content">
                                        <div class="text">ESA</div>
                                        <div class="count-outer count-box">
                                            <span class="count-text" data-speed="3000"
                                                data-stop="412">0</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--Column-->
                            <div class="column counter-column">
                                <div class="inner wow fadeInLeft" data-wow-delay="0ms" data-wow-duration="1500ms">
                                    <div class="icon-outer">
                                        <div class="icon"><span class="icon-people"></span></div>
                                    </div>
                                    <div class="content">
                                        <div class="text">EA</div>
                                        <div class="count-outer count-box">
                                            <span class="count-text" data-speed="3000"
                                                data-stop="2292">0</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--Column-->
                            <div class="column counter-column">
                                <div class="inner wow fadeInLeft" data-wow-delay="0ms" data-wow-duration="1500ms">
                                    <div class="icon-outer">
                                        <div class="icon"><span class="icon-language"></span></div>
                                    </div>
                                    <div class="content">
                                        <div class="text">ESB</div>
                                        <div class="count-outer count-box">
                                            <span class="count-text" data-speed="3000"
                                                data-stop="2200">0</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- -------------------- -->
                            <div class="column counter-column">
                                <div class="inner wow fadeInLeft" data-wow-delay="0ms" data-wow-duration="1500ms">
                                    <div class="icon-outer">
                                        <div class="icon"><span class="icon-language"></span></div>
                                    </div>
                                    <div class="content">
                                        <div class="text">EB</div>
                                        <div class="count-outer count-box">
                                            <span class="count-text" data-speed="3000"
                                                data-stop="29">0</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- ------------------------------ -->
                        </div>
                    </div>

                    <div class="col-xl-6">
                        <div class="sec-title style-two mb-20 text-center">
                            <h2 class="text-white">Competency Certificate</h2>
                        </div>
                        <div class="ourfacts">
                            <!--Column-->
                            <div class="column counter-column">
                                <div class="inner wow fadeInLeft" data-wow-delay="0ms" data-wow-duration="1500ms">
                                    <div class="icon-outer">
                                        <div class="icon"><span class="icon-area"></span></div>
                                    </div>
                                    <div class="content">
                                        <div class="text">Supervisory</div>
                                        <div class="count-outer count-box">
                                            <span class="count-text" data-speed="3000"
                                                data-stop="53902">0</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--Column-->
                            <div class="column counter-column">
                                <div class="inner wow fadeInLeft" data-wow-delay="0ms" data-wow-duration="1500ms">
                                    <div class="icon-outer">
                                        <div class="icon"><span class="icon-people"></span></div>
                                    </div>
                                    <div class="content">
                                        <div class="text">Wireman</div>
                                        <div class="count-outer count-box">
                                            <span class="count-text" data-speed="3000"
                                                data-stop="138043">0</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--Column-->
                            <div class="column counter-column">
                                <div class="inner wow fadeInLeft" data-wow-delay="0ms" data-wow-duration="1500ms">
                                    <div class="icon-outer">
                                        <div class="icon"><span class="icon-language"></span></div>
                                    </div>
                                    <div class="content">
                                        <div class="text">Wireman Helper</div>
                                        <div class="count-outer count-box">
                                            <span class="count-text" data-speed="3000"
                                                data-stop="23532">0</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!--Column-->
                            <div class="column counter-column">
                                <div class="inner wow fadeInLeft" data-wow-delay="0ms" data-wow-duration="1500ms">
                                    <div class="icon-outer">
                                        <div class="icon"><span class="icon-language"></span></div>
                                    </div>
                                    <div class="content">
                                        <div class="text">Power Generating</div>
                                        <div class="count-outer count-box">
                                            <span class="count-text" data-speed="3000"
                                                data-stop="243">0</span>
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
</section>


<section class="portfolio-section" id="portfolio">
    <div class="container-fluid">
        <div class="sec-title style-two mb-20 text-center">
            <h2 class="text-dark">Gallery</h2>
        </div>
        <!-- <div class="portfolio-menu mt-2 mb-2">
            <nav class="controls">
                <button type="button" class="btn btn-primary" data-filter="all">All</button>
                <button type="button" class="btn btn-primary" data-filter=".web">Photos</button>
                <button type="button" class="btn btn-primary" data-filter=".dev">Videos</button>
                <button type="button" class="btn btn-primary" data-filter=".wp">Others</button>
            </nav>
        </div> -->
         <ul class="row portfolio-item justify-content-center">
            <?php $__currentLoopData = $Gallery; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li class="mix <?php echo e($item->category_class ?? 'web'); ?> col-xl-3 col-md-3 col-12 col-sm-6 pd">
                <img src="<?php echo e(asset('portaladmin/gallery/' . $item->image)); ?>" itemprop="thumbnail" alt="<?php echo e($item->imagetitle); ?>" />
                <div class="portfolio-overlay">
                    <div class="overlay-content">
                        <p class="category"><?php echo e($item->imagetitle); ?></p>

                        <a data-fancybox="item" title="click to zoom-in" href="<?php echo e(asset('portaladmin/gallery/' . $item->image)); ?>">
                            <div class="magnify-icon">
                                <p><span><i class="fa fa-search" aria-hidden="true"></i></span></p>
                            </div>
                        </a>
                    </div>
                </div>
            </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
</section>



<!-- Main  Footer -->
<footer class="main-footer">

    <div class="auto-container">
        <!--Widgets Section-->
        <div class="widgets-section">
            <div class="row clearfix">

                <div class="column col-lg-3 col-md-6">
                    <div class="widget contact-widget">
                        <h3 class="widget-title">Contact Details</h3>
                        <div class="widget-content">
                            <ul class="contact-info">
    <?php $__currentLoopData = $contactdetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <li>
            <div class="icon">
                <img src="<?php echo e(asset('assets/images/icons/icon-1.png')); ?>" alt="">
            </div>
            <div class="text text-white">
                <?php echo e($contact->address); ?>

            </div>
        </li>

        <li>
            <div class="icon">
                <img src="<?php echo e(asset('assets/images/icons/icon-2.png')); ?>" alt="">
            </div>
            <div class="text text-white">
                <strong>Phone No.</strong>
                <a href="tel:<?php echo e($contact->mobilenumber); ?>" class="text-white">
                    <?php echo e($contact->mobilenumber); ?>

                </a>
            </div>
        </li>

        <li>
            <div class="icon">
                <img src="<?php echo e(asset('assets/images/icons/icon-3.png')); ?>" alt="">
            </div>
            <div class="text text-white">
                <strong>Email</strong>
                <a href="mailto:<?php echo e($contact->email); ?>" class="text-white">
                    <?php echo e($contact->email); ?>

                </a>
            </div>
        </li>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</ul>
                        </div>
                    </div>
                </div>


                <!--Column-->
                <div class="column col-lg-4 col-md-6">
                    <div class="widget links-widget">
                        <h3 class="widget-title">Quick Links</h3>
                        <div class="widget-content">
                            <!-- <ul>
                                <li><a href="/about">About</a></li>
                                <li><a href="/members">Members</a></li>
                                <li><a href="/rules">Rules</a></li>
                                <li><a href="/services-and-standards">Services & Standards</a></li>
                                <li><a href="/complaints">Complaints</a></li>
                                <li><a href="/contact">Contact</a></li>
                            </ul> -->

                            <ul>
                                <?php $__currentLoopData = $quicklinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $quicklink): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="text-capitalize">
                                    <?php
                                    $link = '#';
                                    $target = '';
                                    $label = $quicklink->footer_menu_en;

                                    if ($quicklink->page_type === 'Static Page') {
                                    $link = '/tnelb_web' . $quicklink->menuPage?->page_url ?? '#';
                                    } elseif ($quicklink->page_type === 'url') {
                                    $link = $quicklink->menuPage?->external_url ?? '#';
                                    $target = '_blank';
                                    }
                                    ?>

                                    <?php if($quicklink->page_type === 'pdf'): ?>
                                    <?php if($quicklink->menuPage?->pdf_en): ?>
                                    <a href="<?php echo e(asset($quicklink->menuPage->pdf_en)); ?>" target="_blank" title="English PDF">
                                        <i class="fa fa-file-pdf-o text-danger"></i> <?php echo e($label); ?> (EN)
                                    </a>
                                    <?php endif; ?>
                                    <?php if($quicklink->menuPage?->pdf_ta): ?>
                                    <a href="<?php echo e(asset($quicklink->menuPage->pdf_ta)); ?>" target="_blank" title="Tamil PDF">
                                        <i class="fa fa-file-pdf-o text-success"></i> <?php echo e($label); ?> (TA)
                                    </a>
                                    <?php endif; ?>
                                    <?php elseif($quicklink->page_type === 'submenu'): ?>
                                    — <?php echo e($label); ?>

                                    <?php else: ?>
                                    <a href="<?php echo e($link); ?>" target="<?php echo e($target); ?>"><?php echo e($label); ?></a>
                                    <?php endif; ?>
                                </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>

                        </div>
                    </div>
                </div>

                <!--Column-->
                <div class="column col-lg-3 col-md-6">
                    <div class="widget links-widget">
                        <h3 class="widget-title">Useful Links</h3>
                        <div class="widget-content">
                            <ul>
                                   <?php $__currentLoopData = $usefullinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $usefullink): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="text-capitalize">
                                    <?php
                                    $link = '#';
                                    $target = '';
                                    $label = $usefullink->menu_name_en;

                                    if ($usefullink->page_type === 'Static Page') {
                                    $link = '/tnelb_web' .  $usefullink->menuPage?->page_url ?? '#';
                                    } elseif ($usefullink->page_type === 'url') {
                                    $link = $usefullink->menuPage?->external_url ?? '#';
                                    $target = '_blank';
                                    }
                                    ?>

                                    <?php if($usefullink->page_type === 'pdf'): ?>
                                    <?php if($usefullink->menuPage?->pdf_en): ?>
                                    <a href="<?php echo e(asset($usefullink->menuPage->pdf_en)); ?>" target="_blank" title="English PDF">
                                        <i class="fa fa-file-pdf-o text-danger"></i> <?php echo e($label); ?> (EN)
                                    </a>
                                    <?php endif; ?>
                                    <?php if($usefullink->menuPage?->pdf_ta): ?>
                                    <a href="<?php echo e(asset($usefullink->menuPage->pdf_ta)); ?>" target="_blank" title="Tamil PDF">
                                        <i class="fa fa-file-pdf-o text-success"></i> <?php echo e($label); ?> (TA)
                                    </a>
                                    <?php endif; ?>
                                    <?php elseif($usefullink->page_type === 'submenu'): ?>
                                    — <?php echo e($label); ?>

                                    <?php else: ?>
                                    <a href="<?php echo e($link); ?>" target="<?php echo e($target); ?>"><?php echo e($label); ?></a>
                                    <?php endif; ?>
                                </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <!-- <li><a href="#">Terms of use</a></li>
                                <li><a href="#">Website Policies</a></li>
                                <li><a href="#">Site Map</a></li>
                                <li><a href="#">Help</a></li> -->

                            </ul>
                        </div>
                    </div>
                </div>

                <!--Column-->


            </div>
        </div>
    </div>

    <?php echo $__env->make('include.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <script>
         /*Downloaded from https://www.codeseek.co/ezra_siton/mixitup-fancybox3-JydYqm */
        // 1. querySelector
        var containerEl = document.querySelector(".portfolio-item");
        // 2. Passing the configuration object inline
        //https://www.kunkalabs.com/mixitup/docs/configuration-object/
        var mixer = mixitup(containerEl, {
            animation: {
                effects: "fade translateZ(-100px)",
                effectsIn: "fade translateY(-100%)",
                easing: "cubic-bezier(0.645, 0.045, 0.355, 1)"
            }
        });
    </script><?php /**PATH D:\xampp\hddocs\TNelb-Staging\resources\views/index.blade.php ENDPATH**/ ?>