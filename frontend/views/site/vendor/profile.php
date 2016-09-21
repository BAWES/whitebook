<?php

use frontend\models\Category;
use common\models\ChildCategory;
use common\models\SubCategory;
use frontend\models\Vendor;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

$event_status = Yii::$app->session->get('event_status');

if ($event_status == -1) {
    ?>
    <script type="text/javascript">
        function display_event_modal()
        {
            jQuery('#EventModal').modal('show');
        }
        window.onload = display_event_modal;
    </script>
<?php
}
if ($event_status > 0) {
    ?>
    <script type="text/javascript">
        /* BEGIN ADD EVENT */
        function addevent1(item_id)
        {
            jQuery.ajax({
                type: 'POST',
                url: "<?php Url::toRoute('product/addevent'); ?>",
                data: {'item_id': item_id},
                success: function (data)
                {
                    jQuery('#addevent').html(data);
                    jQuery('#eventlist' + item_id).selectpicker('refresh');
                    jQuery('#add_to_event').modal('show');

                }
            });
        }

        /* END ADD EVENT */
        var x = '<?= $event_status; ?>';
        window.onload = addevent1(x);
    </script>
<?php } ?>
<!-- coniner start -->
<section id="inner_pages_white_back">
    <div class="container paddng0">
        <div class="vender_profile_new">
            <div class="product_detials_vender aother_dates">
                <div class="col-md-6 padding0">
                    <a href="#" title="">
                    <?php if(isset($vendor_detail['vendor_logo_path'])) {
                        echo Html::img(Yii::getAlias('@vendor_logo/').$vendor_detail['vendor_logo_path'], ['class'=>'','width'=>'565','height'=>'470','alt'=>'Logo']);
                    } ?>
                    </a>
                </div>
                <div class="col-md-6 paddingcommon">
                    <div class="right_descr_product">
                        <div class="accad_menus">
                            <div class="bakery_title">
                                <h3><?php echo $vendor_detail['vendor_name']; ?></h3>
                            </div>
                            <div class="panel-group" id="sub_accordion">
                                    <div class="panel panel-default" >
                                        <div class="panel-heading" role="tab" id="headingThree">
                                            <h4 class="panel-title">
                                                <a class="collapsed" id="description_click" data-toggle="collapse" data-parent="#sub_accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                                    <?php echo Yii::t('frontend', 'Description'); ?>    
                                                    <span class="glyphicon glyphicon-menu-right text-align pull-right"></span>
                                                </a> 
                                            </h4>
                                        </div>
                                        <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                                            <div class="panel-body">
                                                <p><?php
                                                    if(Yii::$app->language == "en") {
                                                        echo strip_tags($vendor_detail['short_description']);
                                                    } else {
                                                        echo strip_tags($vendor_detail['short_description_ar']);
                                                    }
                                                    ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel panel-default" >
                                        <div class="panel-heading" role="tab" id="headingTwo">
                                            <h4 class="panel-title">
                                                <a class="collapsed" data-toggle="collapse" data-parent="#sub_accordion" id="policy_click" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                    <?php echo Yii::t('frontend', 'Return policy'); ?>
                                                    <span class="glyphicon glyphicon-menu-right text-align pull-right"></span>
                                                </a> 
                                            </h4>
                                        </div>
                                        <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                            <div class="panel-body">
                                                <p><?php

                                                    if(Yii::$app->language == "en") {
                                                        echo strip_tags($vendor_detail['vendor_return_policy']);
                                                    } else {
                                                        echo strip_tags($vendor_detail['vendor_return_policy_ar']);
                                                    }
                                                    ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel panel-default">
                                        <div class="panel-heading" role="tab" id="headingFive">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#sub_accordion"  id="contact_click" href="#collapseFive" aria-expanded="true" aria-controls="collapseOne">
                                                    <?= Yii::t('frontend', 'Contact info'); ?>
                                                    <span class="glyphicon glyphicon-menu-down text-align pull-right"></span>
                                                </a> 
                                            </h4>
                                        </div>
                                        <div id="collapseFive" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                            <div class="panel-body">
                                                <div class="contact_information">
                                                    <address>
                                                        <div class="clearfix">
                                                        <?php if (trim($vendor_detail['vendor_public_email']) || trim($vendor_detail['vendor_public_phone'])) { ?>
                                                            <div class="col-md-6 col-xs-6 cont_ifo_left paddingleft0">
                                                                <?php if (trim($vendor_detail['vendor_public_email'])) { ?>
                                                                    <h3>
                                                                        <a href="#" title="<?php echo $vendor_detail['vendor_public_email']; ?>"><?php echo $vendor_detail['vendor_public_email']; ?>&nbsp;</a>
                                                                    </h3>
                                                                    <span class="border-bottom"></span>
                                                                <?php } ?>
                                                                <?php if (trim($vendor_detail['vendor_public_phone'])) { ?>
                                                                    <h4 style="margin-top: 13px;">
                                                                        <?php echo $vendor_detail['vendor_public_phone']; ?>&nbsp;
                                                                    </h4>
                                                                <span class="border-bottom border-bottom-none"></span>
                                                                <?php } ?>
                                                            </div>
                                                        <?php } ?>
                                                            <?php if (trim($vendor_detail['vendor_website']) || trim($vendor_detail['vendor_working_hours'])) { ?>
                                                                <div class="col-md-6 col-xs-6 paddingright0 paddingleft0 cont_ifo_right">
                                                                    <?php if (trim($vendor_detail['vendor_website'])) { ?>
                                                                        <span class="links_left"><a href="<?php echo $vendor_detail['vendor_website']; ?>" title="<?php echo $vendor_detail['vendor_website']; ?>"><?php echo $vendor_detail['vendor_website']; ?>&nbsp;</a></span>
                                                                        <span class="border-bottom"></span>
                                                                    <?php } ?>
                                                                    <?php if (trim($vendor_detail['vendor_working_hours'])) { ?>

                                                                        <span class="timer_common"><?php
                                                                            $from = explode(':',$vendor_detail['vendor_working_hours']);
                                                                            echo (isset($from[0])) ? $from[0] : '';
                                                                            echo (isset($from[1])) ? ':'.$from[1] : '';
                                                                            echo (isset($from[2])) ? ' '.$from[2] : ''
                                                                            ?></span>

                                                                        - <span class="timer_common">
                                                                            <?php
                                                                            $to = explode(':',$vendor_detail['vendor_working_hours_to']);
                                                                            echo (isset($to[0])) ? $to[0] : '';
                                                                            echo (isset($to[1])) ? ':'.$to[1] : '';
                                                                            echo (isset($to[2])) ? ' '.$to[2] : ''
                                                                            ?>
                                                                        </span>
                                                                    <?php } ?>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                        <?php if (trim($vendor_detail['vendor_contact_address']) || $vendor_detail['vendor_contact_address'] != 'n/a') { ?>
                                                        <div class="col-md-6 col-xs-6 paddingleft0 address_ifo_left border-top">
                                                            <h5 class="margin-top-13">
                                                                <?php
                                                                    $address = (Yii::$app->language == "en") ? 'vendor_contact_address' : 'vendor_contact_address_ar';
                                                                    echo $vendor_detail[$address];
                                                                ?>
                                                            </h5>
                                                        </div>
                                                        <?php } ?>
                                                    </address>
                                                </div>
                                            </div>
                                        </div>
                                            <?php if (count($vendor_detail) > 0) { ?>
                                            <div class="social_share">
                                                <h3><?= Yii::t('frontend', 'Share this'); ?></h3>
                                                <ul>
                                                    <li><a target="_blank" href="<?php echo $vendor_detail['vendor_facebook']; ?>" title="Facebook"><span class="flaticon-facebook55"></span></a></li>
                                                    <li><a target="_blank" href="<?php echo $vendor_detail['vendor_twitter']; ?>" title="Twitter"><span class="flaticon-twitter13"></span></a></li>
                                                    <li><a target="_blank" href="<?php echo $vendor_detail['vendor_googleplus']; ?>" title="Google+"><span class="flaticon-google109"></span></a></li>
                                                    <li><a target="_blank" href="<?php echo $vendor_detail['vendor_instagram']; ?>" title="Instatgram"><span class="flaticon-instagram7"></span></a></li>
                                                    <?php $vendor_url = Yii::$app->homeUrl . '/vendor/' . $vendor_detail['vendor_contact_name'] . '.html'; ?>
                                                    <li><a href="mailto:<?php echo $vendor_detail['vendor_contact_email']; ?>?subject=Vendor Profile&body=<?php echo $vendor_url; ?>" title="MailTo"><i class="flaticon-email5"></i></a></li>
                                                </ul>
                                            </div>
                                        <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="plan_venues total_continer">
            <div class="col-md-3 paddingleft0">
                <div class="filter_content">
                    <div class="filter_section">
                        <div class="responsive-category-bottom">
                            <span class="filter_butt title_filter color_yellow col-xs-12 text-right padding0" data-toggle="offcanvas">Filter</span>
                            <div class="filter_title">
                                <span class="title_filter color_yellow"><?= Yii::t('frontend', 'Filter by'); ?></span>
                            </div>
                            <div class="filter_butt hamburger is-closed" data-toggle="offcanvas">
                                <img width="32" height="35" src="<?php echo Url::to("@web/images/cross92.svg"); ?>" alt="click here">
                            </div>
                            <nav class="row-offcanvas row-offcanvas-left">
                                <div class="listing_content_cat sidebar-offcanvas" id="sidebar" role="navigation" >
                                    <div id="accordion" class="panel-group">
                                        <!-- BEGIN CATEGORY FILTER  -->
                                        <?php 
                                            echo $this->render('_filter/category.php',['slug'=>$slug]);
                                            echo $this->render('_filter/theme.php',['themes'=>$themes]);
                                            echo $this->render('_filter/price.php',['vendorData'=>$vendorData]);
                                        ?>
                                    </div>
                            </nav>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-9 paddingright0">
                <div class="listing_right">
                    <div class="events_listing">
                        <ul>
                        <?php
                            if (!empty($vendorData)) {
                                foreach ($vendorData as $key => $value) {
                                    echo $this->render('@frontend/views/plan/item',[
                                        'value' => $value,
                                        'customer_events_list' => $customer_events_list
                                    ]);
                                }
                            } else {
                                echo "No records found";
                            }
                        ?>
                        </ul>
                    </div>
                    <div id="planloader">
                        <img src="<?php echo Url::to("@web/images/ajax-loader.gif"); ?>" title="Loader" style="margin-top: 15%;" />
                    </div>
                    <div class="add_more_commons">
                        <?php if (!empty($vendor_item_details)) { ?>
                            <div class="lode_more_buttons">
                                <!-- <button title="Load More" class="btn btn-danger" type="button">Load More</button> -->
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
<link href="<?= Url::to("@web/css/owl.carousel.css") ?>" rel="stylesheet">
<link href="<?= Url::to("@web/css/bootstrap-select.min.css") ?>" rel="stylesheet">
<link href="<?= Url::to("@web/css/jquery.mCustomScrollbar.css") ?>" rel="stylesheet">
<script src="<?= Url::to("@web/js/jquery.mCustomScrollbar.concat.min.js") ?>"></script>
<!-- continer end -->
<script type="text/javascript">
    var csrfToken = jQuery('meta[name="csrf-token"]').attr("content");
    function setupLabel() {
        if (jQuery('.label_check input').length) {
            jQuery('.label_check').each(function () {
                jQuery(this).removeClass('c_on');

                if (jQuery(this).parents('.panel-body').find('label.c_on').length == 0) {
                    jQuery(this).parents('.panel-default').find('a.filter-clear').css('display', 'none');
                } else {
                    jQuery(this).parents('.panel-default').find('a.filter-clear').css('display', 'inline-block');
                }
            });

            jQuery('.label_check input:checked').each(function () {
                jQuery(this).parent('label').addClass('c_on');
                if (jQuery(this).parents('.panel-body').find('label.c_on').length == 0) {
                    jQuery(this).parents('.panel-default').find('a.filter-clear').css('display', 'none');
                } else {
                   jQuery(this).parents('.panel-default').find('a.filter-clear').css('display', 'inline-block');
                }
            });
        }

        if (jQuery('.label_radio input').length) {
            jQuery('.label_radio').each(function () {
                jQuery(this).removeClass('r_on');
            });
            jQuery('.label_radio input:checked').each(function () {
                jQuery(this).parent('label').addClass('r_on');
            });
        }

    }

    jQuery(document).ready(function () {
        jQuery('.label_check, .label_radio').click(function () {
            setupLabel();
        });
        setupLabel();
        jQuery(".custom-select").change(function () {
            var selectedOption = jQuery(this).find(":selected").text();
            jQuery(this).next(".holder").text(selectedOption);
        }).trigger('change');
    });

    jQuery('.label_check input').on('change', function () {
        vendorfilter();
    });


    /* */
    function vendorfilter() {
        jQuery("#planloader").show();
        var category_name = jQuery("input[name=category]:checked").map(function () {
            return this.value;
        }).get().join('+');


        var theme_name = jQuery("input[name=themes]:checked").map(function () {
            return this.value;
        }).get().join('+');

        var price_val = jQuery("input[name=price]:checked").map(function () {
            return this.value;
        }).get().join('+');

        var url_path;
        var url = window.location.href;
        var newUrl = url.substring(0, url.indexOf('?'));

        var slug;
        if (newUrl != '') {
            slug = newUrl.substring(newUrl.lastIndexOf('/') + 1);
        } else {
            slug = url.substring(url.lastIndexOf('/') + 1);
        }

        /* if all checkbox uncheck load items based on category */
        if (category_name == "" && theme_name == "") {
            window.history.pushState("test", "Title", newUrl);
            slug = "<?php echo $slug; ?>";
        }

        if (category_name != "" || theme_name != "" || price_val != "") {
            url_path = '?category=' + category_name + '?themes=' + theme_name + '&price=' + price_val;
        }

        var path = "<?= Url::toRoute('/plan/loadvendoritems',true); ?>";
        <?php $giflink = Url::to("@web/images/ajax-loader.gif"); ?>

        jQuery.ajax({
            type: 'POST',
            url: path,
            data: {category_name: category_name, themes: theme_name, price: price_val, slug: slug, _csrf: csrfToken},
            success: function (data) {
                window.history.pushState("test", "Title", url_path);
                jQuery('.events_listing ul').html(data);
                // Every fourth li change margin
                jQuery('.listing_right .events_listing ul li:nth-child(4n)').addClass("margin-rightnone");
                jQuery("#planloader").hide();
                jQuery(".events_listing").css({"opacity": "1.0", "position": "relative"});
            }
        }).done(function () {
            jQuery(".add_to_favourite").click(function () {

                jQuery('#loading_img_list').show();
                jQuery('#loading_img_list').html('<img id="loading-image" src="<?= $giflink; ?>" alt="Loading..." />');

                item_id = (jQuery(this).attr('id'));
                jQueryelement = jQuery(this)
                jQuery(jQueryelement).parent().toggleClass("faverited_icons");

                var _csrf = jQuery('#_csrf').val();
                jQuery.ajax({
                    url: "<?= Url::toRoute('/users/add_to_wishlist'); ?>",
                    type: "post",
                    data: "item_id=" + item_id + "&_csrf=" + _csrf,
                    //async: false,
                    success: function (data)
                    {
                        jQuery('#heart_fave').html(data);
                        jQuery('#loading_img_list').hide();
                    }
                });
            });
        });
    }

    /* BEGIN CLEAR FILTER */
    jQuery('a#filter-clear').on('click', function () {
        jQuery(this).parents('.panel-default').find('label.label_check').removeClass('c_on');
        jQuery(this).parents('.panel-default').find('label.label_check input').prop('checked', false);
        jQuery(this).hide();
        vendorfilter();
    })
    /* END CLEAR FILTER */

    $(document).ready(function () {
        jQuery('.listing_right .events_listing ul li:nth-child(4n)').addClass("margin-rightnone");
        jQuery('.thing_items li:nth-child(8n)').addClass("margin-rightnone");
    });

    jQuery('.collapse').on('shown.bs.collapse', function () {
        jQuery(this).parent().find(".plus_acc").removeClass("plus_acc").addClass("minus_acc");
    }).on('hidden.bs.collapse', function () {
        jQuery(this).parent().find(".minus_acc").removeClass("minus_acc").addClass("plus_acc");
    });
    (function (jQuery) {
        jQuery(window).load(function () {
            jQuery(".test_scroll").mCustomScrollbar({
                theme: "rounded-dark",
                mouseWheelPixels: 50,
                scrollInertia: 0
            });
        });
    })(jQuery);
</script>