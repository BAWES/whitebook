<?php $this->title = 'Vendor | Whitebook'; ?>
<link href="<?php echo Yii::$app->params['CSS_PATH']; ?>bootstrap-select.min.css" rel="stylesheet">
<section id="inner_page_detials">
    <div class="top_sections_titles">
        <div class="container">
            <div class="col-md-12">
                <div class="common_titles">
                    <div class="text-center"><span class="yellow_top"></span> </div> 
                    <h1>MY <b> WHITE </b>Book</h1>
                    <div class="botton_dwon"><button class="btn btn-default btn-lg active" type="button"><i class="flaticon-downwards"></i></button></div>
                </div>
            </div>
        </div>
    </div>
    <div class="product_detials">
        <div class="container">
            <div class="common_space_content">
                &nbsp;
            </div>
            <div class="col-md-12">
                <div class="product_detials_vender aother_dates">
                    <div class="col-md-6 padd_left0 no_images">
                        <a href="#" title=""><img src="<?php echo Yii::$app->params['IMAGE_PATH']; ?>/no_banner.png" alt=""/></a>
                    </div>
                    <div class="col-md-6">
                        <div class="right_descr_product">
                            <div class="accad_menus">
                                <div class="bakery_title">
                                    <h3><?php echo $vendor_detail[0]['vendor_name']; ?></h3>
                                </div>
                                <div class="panel-group" id="sub_accordion">
                                    <?php if ($vendor_detail[0]['vendor_brief'] != '') { ?>
                                        <div class="panel panel-default">
                                            <div class="panel-heading" role="tab" id="headingThree">
                                                <h4 class="panel-title">
                                                    <a id="description_click" class="collapsed" data-toggle="collapse" data-parent="#sub_accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                                        <?php echo Yii::t('frontend', 'description'); ?>
                                                        <i class="flaticon-downwards"></i></a> 
                                                </h4>
                                            </div>
                                            <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                                                <div class="panel-body">
                                                    <?php echo $vendor_detail[0]['vendor_brief']; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <?php if ($vendor_detail[0]['vendor_return_policy'] != '') { ?>
                                        <div class="panel panel-default" >
                                            <div class="panel-heading" role="tab" id="headingTwo">
                                                <h4 class="panel-title">
                                                    <a id="policy_click" class="collapsed" data-toggle="collapse" data-parent="#sub_accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                        <?php echo Yii::t('frontend', 'return_policy'); ?>
                                                        <i class="flaticon-downwards"></i></a> 
                                                </h4>
                                            </div>
                                            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                                <div class="panel-body">
                                                    <?php echo $vendor_detail[0]['vendor_return_policy']; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <?php if ($vendor_detail[0]['vendor_public_email'] != '' || $vendor_detail[0]['vendor_public_phone'] != '' || $vendor_detail[0]['vendor_website'] != '' || $vendor_detail[0]['vendor_working_hours']) { ?>
                                        <div class="panel panel-default">
                                            <div class="panel-heading" role="tab" id="headingOne">
                                                <h4 class="panel-title">
                                                    <a id="contact_click" data-toggle="collapse" data-parent="#sub_accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                        <?php echo Yii::t('frontend', 'contact_info'); ?>
                                                        <i class="flaticon-up151"></i></a> 
                                                </h4>
                                            </div>
                                            <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                                <div class="panel-body">
                                                    <div class="contact_information">
                                                        <address>
                                                            <div class="col-md-6 cont_ifo_left padd_left0">
                                                                <?php if ($vendor_detail[0]['vendor_public_email'] != '') { ?>
                                                                    <h3><a href="#" title="<?php echo $vendor_detail[0]['vendor_public_email']; ?>"><?php echo $vendor_detail[0]['vendor_public_email']; ?></a></h3>
                                                                    <span class="border-bottom"></span>
                                                                <?php } ?>
                                                                <?php if ($vendor_detail[0]['vendor_public_phone'] != '') { ?>
                                                                    <h4><?php echo $vendor_detail[0]['vendor_public_phone']; ?></h4>
                                                                <?php } ?>
                                                            </div>
                                                            <div class="col-md-6 cont_ifo_right">
                                                                <?php if ($vendor_detail[0]['vendor_website'] != '') { ?>
                                                                    <span class="links_left"><a href="<?php echo $vendor_detail[0]['vendor_website']; ?>" title="<?php echo $vendor_detail[0]['vendor_website']; ?>"><?php echo $vendor_detail[0]['vendor_website']; ?></a></span>
                                                                    <span class="border-bottom"></span>
                                                                <?php } ?>
                                                                <?php if ($vendor_detail[0]['vendor_working_hours'] != '') { ?>
                                                                    <span class="timer_common"><?php echo $vendor_detail[0]['vendor_working_hours']; ?></span>
                                                                <?php } ?>
                                                            </div>
                                                        </address>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php if (count($vendor_social_info) > 0) { ?>
                                <div class="social_share">
                                    <h3><?php echo Yii::t('frontend', 'share_this'); ?></h3>
                                    <ul>
                                        <?php if ($vendor_social_info[0]['vendor_facebook_share'] != '') { ?>
                                            <li><a target="_blank" href="<?php echo $vendor_social_info[0]['vendor_facebook_share']; ?>" title="Facebook"><span class="flaticon-facebook55"></span></a></li>
                                        <?php } ?>
                                        <?php if ($vendor_social_info[0]['vendor_twitter_share'] != '') { ?>
                                            <li><a target="_blank" href="<?php echo $vendor_social_info[0]['vendor_twitter_share']; ?>" title="Twitter"><span class="flaticon-twitter13"></span></a></li>
                                        <?php } ?>
                                        <?php if ($vendor_social_info[0]['vendor_google_share'] != '') { ?>
                                            <li><a target="_blank" href="<?php echo $vendor_social_info[0]['vendor_google_share']; ?>" title="Google+"><span class="flaticon-google109"></span></a></li>
                                        <?php } ?>
                                        <?php if ($vendor_social_info[0]['vendor_tumblr_url'] != '') { ?>
                                            <li><a target="_blank" href="<?php echo $vendor_social_info[0]['vendor_tumblr_url']; ?>" title="Tumblr"><span class="flaticon-tumblr14"></span></a></li>
                                        <?php } ?>
                                        <?php if ($vendor_social_info[0]['vendor_pinterest_url'] != '') { ?>
                                            <li><a target="_blank" href="<?php echo $vendor_social_info[0]['vendor_pinterest_url']; ?>" title="Pinterest"><span class="flaticon-image87"></span></a></li>
                                        <?php } ?>
                                        <?php $vendor_url = Yii::$app->params['BASE_URL'] . '/vendor/' . $vendor_detail[0]['vendor_contact_name'] . '.html'; ?>
                                        <li><a href="mailto:someone@example.com?subject=Vendor Profile&body=<?php echo $vendor_url; ?>" title="MailTo"><i class="flaticon-email5"></i></a></li>
                                    </ul>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="similar_product_listing filter_new_content">


                <div class="filter_cat_common">
                    <div class="col-md-3 padding8_left">

                        <div class="filter_section_left">
                            <div class="filter_section_categories">
                                <span class="cat_titles bg_none"><?php echo Yii::t('frontend', 'categories'); ?></span>
                                <ul class="directory_menu">
                                    <?php foreach ($category as $c) { ?>
                                        <li><a href="<?php echo Yii::$app->params['BASE_URL']; ?>/directory/<?php echo $c['category_url']; ?>.html" title="<?php echo $c['category_name']; ?>"><p class="venue_icon"></p><span><?php echo $c['category_name']; ?></span></a></li>
                                    <?php } ?>
                                </ul>
                            </div>  
                        </div>

                        <div class="filter_section_left">
                            <div class="filter_section_fillter">
                                <span class="cat_titles" >Filters</span>
                                <div class="sub_cat_sections">
                                    <h2 class="sub_cat_title">Sub Categories</h2>
                                    <div class="panel-group" id="accordion">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a class="" data-toggle="collapse" data-parent="#accordion" href="#bakery"  id="sub_category_bakery" area-expanded="true"><span class="minus_acc">
                                                        </span>Drinks</a>
                                                </h4>
                                            </div>
                                            <div  id="bakery" class="panel-collapse collapse in" area-expanded="true" aria-expanded="true">
                                                <div class="panel-body">
                                                    <div class="table">
                                                        <ul>
                                                            <li>
                                                                <label for="checkbox-01" class="label_check c_on"><input type="checkbox" checked="" value="1" id="checkbox-01" name="sample-checkbox-01">Event Cakes</label>
                                                            </li>
                                                            <li>
                                                                <label for="checkbox-02" class="label_check"><input type="checkbox" value="1" id="checkbox-02" name="sample-checkbox-02">Wedding Cakes</label>
                                                            </li>
                                                            <li>
                                                                <label for="checkbox-03" class="label_check c_on"><input type="checkbox" checked="" value="1" id="checkbox-03" name="sample-checkbox-03"> Birthday Cakes</label>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-default" >
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#dairy" id="sub_category_cakes"><span class="plus_acc">
                                                        </span>Cakes</a>
                                                </h4>
                                            </div>
                                            <div style="height: 0px;" id="dairy" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <div class="table">
                                                        <ul>
                                                            <li>
                                                                <label for="checkbox-04" class="label_check c_on"><input type="checkbox" checked="" value="1" id="checkbox-04" name="sample-checkbox-04">Event Cakes</label>
                                                            </li>
                                                            <li>
                                                                <label for="checkbox-05" class="label_check"><input type="checkbox" value="1" id="checkbox-05" name="sample-checkbox-05">Wedding Cakes</label>
                                                            </li>
                                                            <li>
                                                                <label for="checkbox-06" class="label_check c_on"><input type="checkbox" checked="" value="1" id="checkbox-06" name="sample-checkbox-06"> Birthday Cakes</label>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="vender_form">
                                    <h2>Vendor</h2>
                                    <div class="select_vender_form">


                                        <div class="bs-docs-example">
                                            <select class="selectpicker" data-style="btn-primary" style="display: none;">
                                                <option>Vendor Name</option>
                                                <option>Ketchup</option>
                                                <option>Relish</option>
                                            </select>

                                        </div>
                                    </div>
                                    <span class="space_content"></span>
                                </div>
                                <div class="vender_form">
                                    <h2>Theme</h2>
                                    <div class="select_vender_form">

                                        <div class="bs-docs-example">
                                            <select class="selectpicker" data-style="btn-primary" style="display: none;">
                                                <option>Event Theme</option>
                                                <option>Ketchup</option>
                                                <option>Relish</option>
                                            </select>

                                        </div>
                                    </div>
                                    <span class="space_content"></span>
                                </div>
                                <div class="vender_form">
                                    <h2>Price</h2>
                                    <div class="select_vender_form">
                                        <div class="prize_chate_conte">
                                            <img alt="" src="<?php echo Yii::$app->params['IMAGE_PATH']; ?>/prize_chat.png">
                                        </div>
                                    </div>
                                    <span class="space_content"></span>
                                </div>
                                <div class="vender_form">
                                    <div class="filter_left">
                                        <button title="Filter" type="submit" class="btn btn-default">Filter</button>

                                    </div>
                                    <div class="filter_left_clere">
                                        <input type="button" title="Clear Filter" value="Clear Filter" class="btn btn-default">
                                    </div>

                                    <span class="space_content"></span>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-9 padding8">
                        <div class="product_list_common">
                            <div class="product_list_common">
                                <div class="inner_product_listing">
                                    <div class="similar_listing">
                                        <div class="col-md-4 col-xs-6 padding7">
                                            <div class="items_similar1">
                                                <span class="smil_img">
                                                    <a href="#" title=""><img src="<?php echo Yii::$app->params['IMAGE_PATH']; ?>/similar1.png" alt=""></a>
                                                </span>
                                                <div class="similar_descript">
                                                    <div class="box_item1">
                                                        <h3>Vendor Name</h3>
                                                        <h2>Product Name Here</h2>
                                                        <div class="text-center"><span class="borderslid"></span></div>
                                                        <h6>It is a long established fact that a reader will be distracted by the readable content of a page...</h6>

                                                        <div class="favourite">
                                                            <div class="favourite_left right"><a title="" href="#"> <span class="flaticon-favorite21"></span></a></div>
                                                            <span class="bot_prize">  10.00KD</span>
                                                            <div class="favourite_right left"><span class="add_but"><a title="" href="#">+</a></span></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-xs-6 padding7">
                                            <div class="items_similar1">
                                                <span class="smil_img">
                                                    <a href="#" title=""><img src="<?php echo Yii::$app->params['IMAGE_PATH']; ?>/similar2.png" alt=""></a>
                                                </span>
                                                <div class="similar_descript">
                                                    <div class="box_item1">
                                                        <h3>Vendor Name</h3>
                                                        <h2>Product Name Here</h2>
                                                        <div class="text-center"><span class="borderslid"></span></div>
                                                        <h6>It is a long established fact that a reader will be distracted by the readable content of a page...</h6>

                                                        <div class="favourite">
                                                            <div class="favourite_left right"><a title="" href="#"> <span class="flaticon-favorite21"></span></a></div>
                                                            <span class="bot_prize">  10.00KD</span>
                                                            <div class="favourite_right left"><span class="add_but"><a title="" href="#">+</a></span></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-xs-6 padding7">
                                            <div class="items_similar1">
                                                <span class="smil_img">
                                                    <a href="#" title=""><img src="<?php echo Yii::$app->params['IMAGE_PATH']; ?>/similar3.png" alt=""></a>
                                                </span>
                                                <div class="similar_descript">
                                                    <div class="box_item1">
                                                        <h3>Vendor Name</h3>
                                                        <h2>Product Name Here</h2>
                                                        <div class="text-center"><span class="borderslid"></span></div>
                                                        <h6>It is a long established fact that a reader will be distracted by the readable content of a page...</h6>

                                                        <div class="favourite">
                                                            <div class="favourite_left right"><a title="" href="#"> <span class="flaticon-favorite21"></span></a></div>
                                                            <span class="bot_prize">  10.00KD</span>
                                                            <div class="favourite_right left"><span class="add_but"><a title="" href="#">+</a></span></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4 col-xs-6 padding7">
                                            <div class="items_similar1">
                                                <span class="smil_img">
                                                    <a href="#" title=""><img src="<?php echo Yii::$app->params['IMAGE_PATH']; ?>/similar1.png" alt=""></a>
                                                </span>
                                                <div class="similar_descript">
                                                    <div class="box_item1">
                                                        <h3>Vendor Name</h3>
                                                        <h2>Product Name Here</h2>
                                                        <div class="text-center"><span class="borderslid"></span></div>
                                                        <h6>It is a long established fact that a reader will be distracted by the readable content of a page...</h6>

                                                        <div class="favourite">
                                                            <div class="favourite_left right"><a title="" href="#"> <span class="flaticon-favorite21"></span></a></div>
                                                            <span class="bot_prize">  10.00KD</span>
                                                            <div class="favourite_right left"><span class="add_but"><a title="" href="#">+</a></span></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-xs-6 padding7">
                                            <div class="items_similar1">
                                                <span class="smil_img">
                                                    <a href="#" title=""><img src="<?php echo Yii::$app->params['IMAGE_PATH']; ?>/similar2.png" alt=""></a>
                                                </span>
                                                <div class="similar_descript">
                                                    <div class="box_item1">
                                                        <h3>Vendor Name</h3>
                                                        <h2>Product Name Here</h2>
                                                        <div class="text-center"><span class="borderslid"></span></div>
                                                        <h6>It is a long established fact that a reader will be distracted by the readable content of a page...</h6>

                                                        <div class="favourite">
                                                            <div class="favourite_left right"><a title="" href="#"> <span class="flaticon-favorite21"></span></a></div>
                                                            <span class="bot_prize">  10.00KD</span>
                                                            <div class="favourite_right left"><span class="add_but"><a title="" href="#">+</a></span></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-xs-6 padding7">
                                            <div class="items_similar1">
                                                <span class="smil_img">
                                                    <a href="#" title=""><img src="<?php echo Yii::$app->params['IMAGE_PATH']; ?>/similar3.png" alt=""></a>
                                                </span>
                                                <div class="similar_descript">
                                                    <div class="box_item1">
                                                        <h3>Vendor Name</h3>
                                                        <h2>Product Name Here</h2>
                                                        <div class="text-center"><span class="borderslid"></span></div>
                                                        <h6>It is a long established fact that a reader will be distracted by the readable content of a page...</h6>

                                                        <div class="favourite">
                                                            <div class="favourite_left right"><a title="" href="#"> <span class="flaticon-favorite21"></span></a></div>
                                                            <span class="bot_prize">  10.00KD</span>
                                                            <div class="favourite_right left"><span class="add_but"><a title="" href="#">+</a></span></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-xs-6 padding7">
                                            <div class="items_similar1">
                                                <span class="smil_img">
                                                    <a href="#" title=""><img src="<?php echo Yii::$app->params['IMAGE_PATH']; ?>/similar1.png" alt=""></a>
                                                </span>
                                                <div class="similar_descript">
                                                    <div class="box_item1">
                                                        <h3>Vendor Name</h3>
                                                        <h2>Product Name Here</h2>
                                                        <div class="text-center"><span class="borderslid"></span></div>
                                                        <h6>It is a long established fact that a reader will be distracted by the readable content of a page...</h6>

                                                        <div class="favourite">
                                                            <div class="favourite_left right"><a title="" href="#"> <span class="flaticon-favorite21"></span></a></div>
                                                            <span class="bot_prize">  10.00KD</span>
                                                            <div class="favourite_right left"><span class="add_but"><a title="" href="#">+</a></span></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-xs-6 padding7">
                                            <div class="items_similar1">
                                                <span class="smil_img">
                                                    <a href="#" title=""><img src="<?php echo Yii::$app->params['IMAGE_PATH']; ?>/similar2.png" alt=""></a>
                                                </span>
                                                <div class="similar_descript">
                                                    <div class="box_item1">
                                                        <h3>Vendor Name</h3>
                                                        <h2>Product Name Here</h2>
                                                        <div class="text-center"><span class="borderslid"></span></div>
                                                        <h6>It is a long established fact that a reader will be distracted by the readable content of a page...</h6>

                                                        <div class="favourite">
                                                            <div class="favourite_left right"><a title="" href="#"> <span class="flaticon-favorite21"></span></a></div>
                                                            <span class="bot_prize">  10.00KD</span>
                                                            <div class="favourite_right left"><span class="add_but"><a title="" href="#">+</a></span></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-xs-6 padding7">
                                            <div class="items_similar1">
                                                <span class="smil_img">
                                                    <a href="#" title=""><img src="<?php echo Yii::$app->params['IMAGE_PATH']; ?>/similar3.png" alt=""></a>
                                                </span>
                                                <div class="similar_descript">
                                                    <div class="box_item1">
                                                        <h3>Vendor Name</h3>
                                                        <h2>Product Name Here</h2>
                                                        <div class="text-center"><span class="borderslid"></span></div>
                                                        <h6>It is a long established fact that a reader will be distracted by the readable content of a page...</h6>

                                                        <div class="favourite">
                                                            <div class="favourite_left right"><a title="" href="#"> <span class="flaticon-favorite21"></span></a></div>
                                                            <span class="bot_prize">  10.00KD</span>
                                                            <div class="favourite_right left"><span class="add_but"><a title="" href="#">+</a></span></div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="lode_more_buttons col-md-12 padding8">
                                            <a title="" href="#">Load More</a>
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
<script src="<?php echo Yii::$app->params['JS_PATH']; ?>/bootstrap-select.js"></script>
<script src="<?php echo Yii::$app->params['JS_PATH']; ?>script.js"></script>
<script type="text/javascript">
    $('#description_click').click(function () {
        $("i", this).toggleClass("flaticon-up151");
        $('#contact_click i').addClass("flaticon-downwards");
        $('#policy_click i').addClass("flaticon-downwards");
        $('#contact_click i').removeClass("flaticon-up151");
        $('#policy_click i').removeClass("flaticon-up151");
    });
    $('#policy_click').click(function () {
        $("i", this).toggleClass("flaticon-up151");
        $('#contact_click i').addClass("flaticon-downwards");
        $('#description_click i').addClass("flaticon-downwards");
        $('#contact_click i').removeClass("flaticon-up151");
        $('#description_click i').removeClass("flaticon-up151");
    });

    $('#contact_click').click(function () {
        $("i", this).toggleClass("flaticon-up151 flaticon-downwards");
        $('#policy_click i').addClass("flaticon-downwards");
        $('#description_click i').addClass("flaticon-downwards");
        $('#policy_click i').removeClass("flaticon-up151");
        $('#description_click i').removeClass("flaticon-up151");

    });
    $('#sub_category_cakes').click(function () {
        $("span", this).toggleClass("minus_acc plus_acc");

        $('#sub_category_bakery span').removeClass("plus_acc");
        $('#sub_category_bakery span').removeClass("minus_acc");

        $('#sub_category_bakery span').addClass("plus_acc");
    });

    $('#sub_category_bakery').click(function () {
        $("span", this).toggleClass("minus_acc plus_acc");

        $('#sub_category_cakes span').removeClass("minus_acc");
        $('#sub_category_cakes span').addClass("plus_acc");
    });
</script>
