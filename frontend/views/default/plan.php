<?php $this->title = 'Plan | Whitebook'; ?>
<link href="<?php echo FONT_PATH; ?>/flaticon/plan/flaticon.css" rel="stylesheet">
<!-- coniner start -->
<section id="inner_pages_white_back">
    <div class="container paddng0">
        <!-- Directory slider start -->
        <?php require(__DIR__ . '/../product/directory_slider.php'); ?>
        <!-- Directory slider end -->
        <div class="breadcrumb_common">
            <div class="bs-example">
                <ul class="breadcrumb">
                    <li><a title="Home Page" href="#">Home Page</a></li>
                    <li><a title="Food &amp; Decorations" href="#">Food &amp; Decorations</a></li>
                    <li class="active">Cakes</li>
                </ul>
            </div>
        </div>
        <div class="plan_venues">

            <div class="col-md-3 paddingleft0">
                <div class="filter_content">
                    <div class="filter_section">
                        <span class="title_filter">Categories</span>
                        <div class="listing_sub_cat">
                            <span class="list_cat_dic"><img alt="Venues" src="images/venu11.png"></span>
                            <div class="select_plan_cat">
                                <select class="selectpicker" data-style="btn-primary" style="display: none;">
                                    <option>Venues</option>
                                    <option>Ketchup</option>
                                    <option>Relish</option>

                                </select>
                            </div>

                        </div>
                        <div class="filter_title">
                            <span class="title_filter color_yellow">Filter by</span>
                        </div>
                        <div class="listing_content_cat">
                            <div id="accordion" class="panel-group">
                                <div class="panel panel-default" >
                                    <div class="panel-heading">
                                        <a href="#bakery" id="sub_category_cakes" data-parent="#accordion" data-toggle="collapse" class="collapsed"> 
                                            <h4 class="panel-title">Drinks
                                                <span class="minus_acc">  </span>
                                            </h4>
                                        </a>
                                    </div>
                                    <div id="bakery" class="panel-collapse collapse in" area-expanded="true" aria-expanded="true">
                                        <div class="panel-body">
                                            <div class="table">
                                                <ul>
                                                    <li>
                                                        <label class="label_check" for="checkbox-01"><input name="sample-checkbox-01" id="checkbox-01" value="1" type="checkbox" checked="">Event Cakes</label>
                                                    </li>
                                                    <li>
                                                        <label class="label_check" for="checkbox-02"><input name="sample-checkbox-02" id="checkbox-02" value="1" type="checkbox">Wedding Cakes</label>
                                                    </li>
                                                    <li>
                                                        <label class="label_check" for="checkbox-03"><input name="sample-checkbox-03" id="checkbox-03" value="1" type="checkbox" checked=""> Birthday Cakes</label>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default" >
                                    <div class="panel-heading">
                                        <a href="#dairy" data-parent="#accordion" data-toggle="collapse" class="collapsed" id="sub_category_bakery">

                                            <h4 class="panel-title">Cakes
                                                <span class="plus_acc"> </span>
                                            </h4>
                                        </a>
                                    </div>
                                    <div class="panel-collapse collapse" style="height: 0px;" id="dairy">
                                        <div class="panel-body">
                                            <div class="table">
                                                <ul>
                                                    <li>
                                                        <label class="label_check" for="checkbox-04"><input name="sample-checkbox-04" id="checkbox-04" value="1" type="checkbox" checked="">Event Cakes</label>
                                                    </li>
                                                    <li>
                                                        <label class="label_check" for="checkbox-05"><input name="sample-checkbox-05" id="checkbox-05" value="1" type="checkbox">Wedding Cakes</label>
                                                    </li>
                                                    <li>
                                                        <label class="label_check" for="checkbox-06"><input name="sample-checkbox-06" id="checkbox-06" value="1" type="checkbox" checked=""> Birthday Cakes</label>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- vendor section -->
                                <div class="panel panel-default" >
                                    <div class="panel-heading">
                                        <a href="#vendor" data-parent="#accordion" data-toggle="collapse" class="collapsed" id="sub_category_vendor">
                                            <h4 class="panel-title">Vendor <i style="font-size:11px;">- Clear</i> 
                                                <span class="plus_acc"></span>   

                                            </h4></a>
                                    </div>
                                    <div class="panel-collapse collapse" style="height: 0px;" id="vendor">
                                        <div class="panel-body">
                                            <div class="table">
                                                <ul class="test_scroll">
                                                    <li>
                                                        <label class="label_check" for="checkbox-07"><input name="sample-checkbox-07" id="checkbox-07" value="1" type="checkbox" checked="">Lorem ipsum dolor</label>
                                                    </li>
                                                    <li>
                                                        <label class="label_check" for="checkbox-08"><input name="sample-checkbox-08" id="checkbox-08" value="1" type="checkbox">Phasellus sed</label>
                                                    </li>
                                                    <li>
                                                        <label class="label_check" for="checkbox-09"><input name="sample-checkbox-09" id="checkbox-09" value="1" type="checkbox" checked="">Curabitur porta </label>
                                                    </li>
                                                    <li>
                                                        <label class="label_check" for="checkbox-10"><input name="sample-checkbox-10" id="checkbox-10" value="1" type="checkbox" checked="">Lorem ipsum</label>
                                                    </li>
                                                    <li>
                                                        <label class="label_check" for="checkbox-11"><input name="sample-checkbox-11" id="checkbox-11" value="1" type="checkbox" checked="">Curabitur porta </label>
                                                    </li>
                                                    <li>
                                                        <label class="label_check" for="checkbox-31"><input name="sample-checkbox-31" id="checkbox-31" value="1" type="checkbox" checked="">Lorem ipsum dolor</label>
                                                    </li>
                                                    <li>
                                                        <label class="label_check" for="checkbox-32"><input name="sample-checkbox-32" id="checkbox-32" value="1" type="checkbox">Phasellus sed</label>
                                                    </li>
                                                    <li>
                                                        <label class="label_check" for="checkbox-33"><input name="sample-checkbox-33" id="checkbox-33" value="1" type="checkbox" checked="">Curabitur porta </label>
                                                    </li>
                                                    <li>
                                                        <label class="label_check" for="checkbox-34"><input name="sample-checkbox-34" id="checkbox-34" value="1" type="checkbox" checked="">Lorem ipsum</label>
                                                    </li>
                                                    <li>
                                                        <label class="label_check" for="checkbox-35"><input name="sample-checkbox-35" id="checkbox-35" value="1" type="checkbox" checked="">Curabitur porta </label>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- vendor section ends -->
                                <!-- Theme section -->
                                <div class="panel panel-default" >
                                    <div class="panel-heading">


                                        <a href="#theme" data-parent="#accordion" data-toggle="collapse" class="collapsed" id="sub_category_theme">
                                            <h4 class="panel-title" style="text-align:left;">Theme<span class="plus_acc"> </span>
                                            </h4>
                                        </a> 
                                    </div>
                                    <div class="panel-collapse collapse" style="height: 0px;" id="theme">
                                        <div class="panel-body">
                                            <div class="table">
                                                <ul class="test_scroll">
                                                    <li>
                                                        <label class="label_check" for="checkbox-12"><input name="sample-checkbox-12" id="checkbox-12" value="1" type="checkbox" checked="">Maecenas bibendum</label>
                                                    </li>
                                                    <li>
                                                        <label class="label_check" for="checkbox-13"><input name="sample-checkbox-13" id="checkbox-13" value="1" type="checkbox">Nullam eget magna</label>
                                                    </li>
                                                    <li>
                                                        <label class="label_check" for="checkbox-14"><input name="sample-checkbox-14" id="checkbox-14" value="1" type="checkbox" checked="">Quisque quis nunc </label>
                                                    </li>
                                                    <li>
                                                        <label class="label_check" for="checkbox-15"><input name="sample-checkbox-15" id="checkbox-15" value="1" type="checkbox" checked="">Aenean ultricies</label>
                                                    </li>
                                                    <li>
                                                        <label class="label_check" for="checkbox-16"><input name="sample-checkbox-16" id="checkbox-16" value="1" type="checkbox" checked="">Nunc consectetur</label>
                                                    </li>
                                                    <li>
                                                        <label class="label_check" for="checkbox-41"><input name="sample-checkbox-41" id="checkbox-41" value="1" type="checkbox" checked="">Maecenas bibendum</label>
                                                    </li>
                                                    <li>
                                                        <label class="label_check" for="checkbox-42"><input name="sample-checkbox-42" id="checkbox-42" value="1" type="checkbox">Nullam eget magna</label>
                                                    </li>
                                                    <li>
                                                        <label class="label_check" for="checkbox-43"><input name="sample-checkbox-43" id="checkbox-43" value="1" type="checkbox" checked="">Quisque quis nunc </label>
                                                    </li>
                                                    <li>
                                                        <label class="label_check" for="checkbox-44"><input name="sample-checkbox-44" id="checkbox-44" value="1" type="checkbox" checked="">Aenean ultricies</label>
                                                    </li>
                                                    <li>
                                                        <label class="label_check" for="checkbox-45"><input name="sample-checkbox-45" id="checkbox-45" value="1" type="checkbox" checked="">Nunc consectetur</label>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- theme section ends -->
                                <!-- price section -->
                                <div class="panel panel-default" >
                                    <div class="panel-heading">
                                        <a href="#price" data-parent="#accordion" data-toggle="collapse" class="collapsed" id="sub_category_price">
                                            <h4 class="panel-title">Price
                                                <span class="plus_acc">
                                                </span>
                                            </h4></a> 
                                    </div>
                                    <div class="panel-collapse collapse" style="height: 0px;" id="price" area-expanded="true" aria-expanded="true">
                                        <div class="panel-body">
                                            <div class="table">
                                                <ul class="test_scroll">
                                                    <li>
                                                        <label class="label_check" for="checkbox-17"><input name="sample-checkbox-17" id="checkbox-17" value="1" type="checkbox" checked="">20 KD  -  30 KD</label>
                                                    </li>
                                                    <li>
                                                        <label class="label_check" for="checkbox-18"><input name="sample-checkbox-18" id="checkbox-18" value="1" type="checkbox">30 KD  - 40 KD</label>
                                                    </li>
                                                    <li>
                                                        <label class="label_check" for="checkbox-19"><input name="sample-checkbox-19" id="checkbox-19" value="1" type="checkbox" checked="">40 KD  -  50 KD</label>
                                                    </li>
                                                    <li>
                                                        <label class="label_check" for="checkbox-20"><input name="sample-checkbox-20" id="checkbox-20" value="1" type="checkbox" checked="">60 KD  -  70 KD</label>
                                                    </li>
                                                    <li>
                                                        <label class="label_check" for="checkbox-21"><input name="sample-checkbox-21" id="checkbox-21" value="1" type="checkbox" checked="">70 KD  -  80 KD</label>
                                                    </li>
                                                    <li>
                                                        <label class="label_check" for="checkbox-22"><input name="sample-checkbox-22" id="checkbox-22" value="1" type="checkbox" checked="">20 KD  -  30 KD</label>
                                                    </li>
                                                    <li>
                                                        <label class="label_check" for="checkbox-23"><input name="sample-checkbox-23" id="checkbox-23" value="1" type="checkbox">30 KD  -  40 KD</label>
                                                    </li>
                                                    <li>
                                                        <label class="label_check" for="checkbox-24"><input name="sample-checkbox-24" id="checkbox-24" value="1" type="checkbox" checked="">40 KD  -  50 KD</label>
                                                    </li>
                                                    <li>
                                                        <label class="label_check" for="checkbox-25"><input name="sample-checkbox-25" id="checkbox-25" value="1" type="checkbox" checked="">60 KD  -  70 KD</label>
                                                    </li>
                                                    <li>
                                                        <label class="label_check" for="checkbox-26"><input name="sample-checkbox-26" id="checkbox-26" value="1" type="checkbox" checked="">70 KD  -  80 KD</label>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- theme section ends -->
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-9 paddingright0">
                <div class="banner_section_plan">
                    <img src="images/banner_plan.png" alt="images"/>
                </div>
                <div class="listing_right">

                    <div class="events_listing">
                        <ul>
                            <li>
                                <div class="events_items">
                                    <div class="events_images">
                                        <div class="hover_events">
                                            <div class="pluse_cont"><a title="" href="#"></a></div>
                                            <div class="faver_icons"><a title="" href="#"></a></div>
                                        </div>
                                        <a href="product_pageshop_with_table.html" title="" > <img alt="" src="images/event_item.jpg"></a>
                                    </div>
                                    <div class="events_descrip">
                                        <a href="product_pageshop_with_table.html" title="">Vendor Name</a>
                                        <h3>Product Name Here</h3>
                                        <p>10.00KD</p>
                                    </div>

                                </div>
                            </li>
                            <li>
                                <div class="events_items">
                                    <div class="events_images">
                                        <div class="hover_events">
                                            <div class="pluse_cont"><a title="" href="#"></a></div>
                                            <div class="faver_icons"><a title="" href="#"></a></div>
                                        </div>
                                        <a href="product_pageshop_with_table.html" title="" >  <img alt="" src="images/event_item.jpg"></a>
                                    </div>
                                    <div class="events_descrip">
                                        <a href="product_pageshop_with_table.html" title="">Vendor Name</a>
                                        <h3>Product Name Here</h3>
                                        <p>10.00KD</p>
                                    </div>

                                </div>
                            </li>
                            <li>
                                <div class="events_items">
                                    <div class="events_images">
                                        <div class="hover_events">
                                            <div class="pluse_cont"><a title="" href="#"></a></div>
                                            <div class="faver_icons"><a title="" href="#"></a></div>
                                        </div>
                                        <a href="product_pageshop_with_table.html" title="" > <img alt="" src="images/event_item.jpg"></a>
                                    </div>
                                    <div class="events_descrip">
                                        <a href="product_pageshop_with_table.html" title="">Vendor Name</a>
                                        <h3>Product Name Here</h3>
                                        <p>10.00KD</p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="events_items">
                                    <div class="events_images">
                                        <div class="hover_events">
                                            <div class="pluse_cont"><a title="" href="#"></a></div>
                                            <div class="faver_icons"><a title="" href="#"></a></div>
                                        </div>
                                        <a href="product_pageshop_with_table.html" title="" >     <img alt="" src="images/event_item.jpg"></a>
                                    </div>
                                    <div class="events_descrip">
                                        <a href="product_pageshop_with_table.html" title="">Vendor Name</a>
                                        <h3>Product Name Here</h3>
                                        <p>10.00KD</p>
                                    </div>
                                </div>
                            </li>

                            <li>
                                <div class="events_items">
                                    <div class="events_images">
                                        <div class="hover_events">
                                            <div class="pluse_cont"><a title="" href="#"></a></div>
                                            <div class="faver_icons"><a title="" href="#"></a></div>
                                        </div>
                                        <a href="product_pageshop_with_table.html" title="" >   <img alt="" src="images/event_item.jpg"></a>
                                    </div>
                                    <div class="events_descrip">
                                        <a href="product_pageshop_with_table.html" title="">Vendor Name</a>
                                        <h3>Product Name Here</h3>
                                        <p>10.00KD</p>
                                    </div>

                                </div>
                            </li>
                            <li>
                                <div class="events_items">
                                    <div class="events_images">
                                        <div class="hover_events">
                                            <div class="pluse_cont"><a title="" href="#"></a></div>
                                            <div class="faver_icons"><a title="" href="#"></a></div>
                                        </div>
                                        <a href="product_pageshop_with_table.html" title="" >  <img alt="" src="images/event_item.jpg"></a>
                                    </div>
                                    <div class="events_descrip">
                                        <a href="product_pageshop_with_table.html" title="">Vendor Name</a>
                                        <h3>Product Name Here</h3>
                                        <p>10.00KD</p>
                                    </div>

                                </div>
                            </li>
                            <li>
                                <div class="events_items">
                                    <div class="events_images">
                                        <div class="hover_events">
                                            <div class="pluse_cont"><a title="" href="#"></a></div>
                                            <div class="faver_icons"><a title="" href="#"></a></div>
                                        </div>
                                        <a href="product_pageshop_with_table.html" title="" >  <img alt="" src="images/event_item.jpg"></a>
                                    </div>
                                    <div class="events_descrip">
                                        <a href="product_pageshop_with_table.html" title="">Vendor Name</a>
                                        <h3>Product Name Here</h3>
                                        <p>10.00KD</p>
                                    </div>

                                </div>
                            </li>
                            <li>
                                <div class="events_items">
                                    <div class="events_images">
                                        <div class="hover_events">
                                            <div class="pluse_cont"><a title="" href="#"></a></div>
                                            <div class="faver_icons"><a title="" href="#"></a></div>
                                        </div>
                                        <a href="product_pageshop_with_table.html" title="" > <img alt="" src="images/event_item.jpg"></a>
                                    </div>
                                    <div class="events_descrip">
                                        <a href="product_pageshop_with_table.html" title="">Vendor Name</a>
                                        <h3>Product Name Here</h3>
                                        <p>10.00KD</p>
                                    </div>

                                </div>
                            </li>

                            <li>
                                <div class="events_items">
                                    <div class="events_images">
                                        <div class="hover_events">
                                            <div class="pluse_cont"><a title="" href="#"></a></div>
                                            <div class="faver_icons"><a title="" href="#"></a></div>
                                        </div>
                                        <a href="product_pageshop_with_table.html" title="" >  <img alt="" src="images/event_item.jpg"></a>
                                    </div>
                                    <div class="events_descrip">
                                        <a href="product_pageshop_with_table.html" title="">Vendor Name</a>
                                        <h3>Product Name Here</h3>
                                        <p>10.00KD</p>
                                    </div>

                                </div>
                            </li>
                            <li>
                                <div class="events_items">
                                    <div class="events_images">
                                        <div class="hover_events">
                                            <div class="pluse_cont"><a title="" href="#"></a></div>
                                            <div class="faver_icons"><a title="" href="#"></a></div>
                                        </div>
                                        <a href="product_pageshop_with_table.html" title="" >    <img alt="" src="images/event_item.jpg"></a>
                                    </div>
                                    <div class="events_descrip">
                                        <a href="product_pageshop_with_table.html" title="">Vendor Name</a>
                                        <h3>Product Name Here</h3>
                                        <p>10.00KD</p>
                                    </div>

                                </div>
                            </li>
                            <li>
                                <div class="events_items">
                                    <div class="events_images">
                                        <div class="hover_events">
                                            <div class="pluse_cont"><a title="" href="#"></a></div>
                                            <div class="faver_icons"><a title="" href="#"></a></div>
                                        </div>
                                        <a href="product_pageshop_with_table.html" title="" >  <img alt="" src="images/event_item.jpg"></a>
                                    </div>
                                    <div class="events_descrip">
                                        <a href="product_pageshop_with_table.html" title="">Vendor Name</a>
                                        <h3>Product Name Here</h3>
                                        <p>10.00KD</p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="events_items">
                                    <div class="events_images">
                                        <div class="hover_events">
                                            <div class="pluse_cont"><a title="" href="#"></a></div>
                                            <div class="faver_icons"><a title="" href="#"></a></div>
                                        </div>
                                        <a href="product_pageshop_with_table.html" title="" > <img alt="" src="images/event_item.jpg"></a>
                                    </div>
                                    <div class="events_descrip">
                                        <a href="product_pageshop_with_table.html" title="">Vendor Name</a>
                                        <h3>Product Name Here</h3>
                                        <p>10.00KD</p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="events_items">
                                    <div class="events_images">
                                        <div class="hover_events">
                                            <div class="pluse_cont"><a title="" href="#"></a></div>
                                            <div class="faver_icons"><a title="" href="#"></a></div>
                                        </div>
                                        <a href="product_pageshop_with_table.html" title="" > <img alt="" src="images/event_item.jpg"></a>
                                    </div>
                                    <div class="events_descrip">
                                        <a href="product_pageshop_with_table.html" title="">Vendor Name</a>
                                        <h3>Product Name Here</h3>
                                        <p>10.00KD</p>
                                    </div>

                                </div>
                            </li>
                            <li>
                                <div class="events_items">
                                    <div class="events_images">
                                        <div class="hover_events">
                                            <div class="pluse_cont"><a title="" href="#"></a></div>
                                            <div class="faver_icons"><a title="" href="#"></a></div>
                                        </div>
                                        <a href="product_pageshop_with_table.html" title="" >  <img alt="" src="images/event_item.jpg"></a>
                                    </div>
                                    <div class="events_descrip">
                                        <a href="product_pageshop_with_table.html" title="">Vendor Name</a>
                                        <h3>Product Name Here</h3>
                                        <p>10.00KD</p>
                                    </div>

                                </div>
                            </li><li>
                                <div class="events_items">
                                    <div class="events_images">
                                        <div class="hover_events">
                                            <div class="pluse_cont"><a title="" href="#"></a></div>
                                            <div class="faver_icons"><a title="" href="#"></a></div>
                                        </div>
                                        <a href="product_pageshop_with_table.html" title="" >   <img alt="" src="images/event_item.jpg"></a>
                                    </div>
                                    <div class="events_descrip">
                                        <a href="product_pageshop_with_table.html" title="">Vendor Name</a>
                                        <h3>Product Name Here</h3>
                                        <p>10.00KD</p>
                                    </div>

                                </div>
                            </li><li>
                                <div class="events_items">
                                    <div class="events_images">
                                        <div class="hover_events">
                                            <div class="pluse_cont"><a title="" href="#"></a></div>
                                            <div class="faver_icons"><a title="" href="#"></a></div>
                                        </div>
                                        <a href="product_pageshop_with_table.html" title="" >  <img alt="" src="images/event_item.jpg"></a>
                                    </div>
                                    <div class="events_descrip">
                                        <a href="product_pageshop_with_table.html" title="">Vendor Name</a>
                                        <h3>Product Name Here</h3>
                                        <p>10.00KD</p>
                                    </div>

                                </div>
                            </li>


                        </ul>
                    </div>
                    <div class="add_more_commons">
                        <div class="lode_more_buttons">
                            <button title="Load More" class="btn btn-danger" type="button">Load More</button>
                        </div>
                        <div class="banner_section_plan">
                            <img alt="images" src="images/banner_plan.png">
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
<!-- continer end -->
