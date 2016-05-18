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
                    <a href="#" title=""><img src="<?php echo Url::to("@web/images/no_banner.png"); ?>" alt=""/></a>
                </div>
                <div class="col-md-6 paddingcommon">
                    <div class="right_descr_product">
                        <div class="accad_menus">
                            <div class="bakery_title">
                                <h3><h3><?php echo $vendor_detail[0]['vendor_name']; ?></h3></h3>
                            </div>
                            <div class="panel-group" id="sub_accordion">
<?php if ($vendor_detail[0]['vendor_brief'] != '') { ?>
                                    <div class="panel panel-default" >
                                        <div class="panel-heading" role="tab" id="headingThree">
                                            <h4 class="panel-title">
                                                <a class="collapsed" id="description_click" data-toggle="collapse" data-parent="#sub_accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
    <?php echo Yii::t('frontend', 'description'); ?>    <span class="glyphicon glyphicon-menu-right text-align pull-right"></span></a> </a>
                                            </h4>

                                        </div>
                                        <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                                            <div class="panel-body">
                                                <p><?= strip_tags($vendor_detail[0]['vendor_brief']); ?></p>
                                            </div>
                                        </div>
                                    </div>
<?php } ?>
<?php if ($vendor_detail[0]['vendor_return_policy'] != '') { ?>
                                    <div class="panel panel-default" >
                                        <div class="panel-heading" role="tab" id="headingTwo">
                                            <h4 class="panel-title">
                                                <a class="collapsed" data-toggle="collapse" data-parent="#sub_accordion" id="policy_click" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
    <?php echo Yii::t('frontend', 'return_policy'); ?>
                                                    <span class="glyphicon glyphicon-menu-right text-align pull-right"></span></a> </a>
                                            </h4>
                                        </div>
                                        <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                            <div class="panel-body">
                                                <p><?= strip_tags($vendor_detail[0]['vendor_return_policy']); ?></p>
                                            </div>
                                        </div>
                                    </div>
<?php } ?>

<?php if ($vendor_detail[0]['vendor_public_email'] != '' || $vendor_detail[0]['vendor_public_phone'] != '' || $vendor_detail[0]['vendor_website'] != '' || $vendor_detail[0]['vendor_working_hours']) { ?>
                                    <div class="panel panel-default">
                                        <div class="panel-heading" role="tab" id="headingFive">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#sub_accordion"  id="contact_click" href="#collapseFive" aria-expanded="true" aria-controls="collapseOne">
    <?php echo Yii::t('frontend', 'contact_info'); ?>
                                                    <span class="glyphicon glyphicon-menu-down text-align pull-right"></span></a> </a>
                                            </h4>
                                        </div>
                                        <div id="collapseFive" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                            <div class="panel-body">
                                                <div class="contact_information">
                                                    <address>
                                                        <div class="col-md-6 col-xs-6 cont_ifo_left paddingleft0">
                                                            <?php if ($vendor_detail[0]['vendor_public_email'] != '') { ?>
                                                                <h3><a href="#" title="<?php echo $vendor_detail[0]['vendor_public_email']; ?>"><?php echo $vendor_detail[0]['vendor_public_email']; ?></a></h3>
                                                                <span class="border-bottom"></span>
                                                            <?php } ?>
    <?php if ($vendor_detail[0]['vendor_public_phone'] != '') { ?>
                                                                <h4><?php echo $vendor_detail[0]['vendor_public_phone']; ?></h4>
                                                            <?php } ?>
                                                            <span class="border-bottom border-bottom-none"></span>
                                                        </div>
                                                        <div class="col-md-6 col-xs-6 paddingright0 cont_ifo_right">
                                                            <?php if ($vendor_detail[0]['vendor_website'] != '') { ?>
                                                                <span class="links_left"><a href="<?php echo $vendor_detail[0]['vendor_website']; ?>" title="<?php echo $vendor_detail[0]['vendor_website']; ?>"><?php echo $vendor_detail[0]['vendor_website']; ?></a></span>
                                                                <span class="border-bottom"></span>
                                                            <?php } ?>
                                                            <?php if ($vendor_detail[0]['vendor_working_hours'] != '') { ?>
                                                                <span class="timer_common"><?php echo $vendor_detail[0]['vendor_working_hours']; ?></span>
                                                            <?php } ?>
    <?php if ($vendor_detail[0]['vendor_working_hours_to'] != '') { ?>
                                                                - <span class="timer_common"><?php echo $vendor_detail[0]['vendor_working_hours_to']; ?></span>
    <?php } ?>
                                                        </div>
                                                    </address>

                                                </div>

                                            </div>
                                        </div>
                                                <?php if (count($vendor_detail) > 0) { ?>
                                            <div class="social_share">
                                                <h3>Share this</h3>
                                                <ul>
                                                    <?php if ($vendor_detail[0]['vendor_facebook'] != '') { ?>
                                                        <li><a target="_blank" href="<?php echo $vendor_detail[0]['vendor_facebook']; ?>" title="Facebook"><span class="flaticon-facebook55"></span></a></li>
                                                    <?php } ?>
                                                    <?php if ($vendor_detail[0]['vendor_twitter'] != '') { ?>
                                                        <li><a target="_blank" href="<?php echo $vendor_detail[0]['vendor_twitter']; ?>" title="Twitter"><span class="flaticon-twitter13"></span></a></li>
                                                    <?php } ?>
                                                    <?php if ($vendor_detail[0]['vendor_googleplus'] != '') { ?>
                                                        <li><a target="_blank" href="<?php echo $vendor_detail[0]['vendor_googleplus']; ?>" title="Google+"><span class="flaticon-google109"></span></a></li>
                                                    <?php } ?>
                                                    <?php if ($vendor_detail[0]['vendor_instagram'] != '') { ?>
                                                        <li><a target="_blank" href="<?php echo $vendor_detail[0]['vendor_instagram']; ?>" title="Instatgram"><span class="flaticon-tumblr14"></span></a></li>
        <?php } ?>
                                            <?php $vendor_url = Yii::$app->homeUrl . '/vendor/' . $vendor_detail[0]['vendor_contact_name'] . '.html'; ?>
                                                    <li><a href="mailto:<?php echo $vendor_detail[0]['vendor_contact_email']; ?>?subject=Vendor Profile&body=<?php echo $vendor_url; ?>" title="MailTo"><i class="flaticon-email5"></i></a></li>
                                                </ul>
                                            </div>
    <?php } ?>
                                    </div>
<?php } ?>
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
                                <span class="title_filter color_yellow">Filter by</span>
                            </div>
                            <div class="filter_butt hamburger is-closed" data-toggle="offcanvas">
                                <img width="32" height="35" src="<?php echo Url::to("@web/images/cross92.svg"); ?>" alt="click here">
                            </div>
                            <nav class="row-offcanvas row-offcanvas-left">
                                <div class="listing_content_cat sidebar-offcanvas" id="sidebar" role="navigation" >
                                    <div id="accordion" class="panel-group">
                                        <!-- BEGIN CATEGORY FILTER  -->
                                        <?php
                                        $subcategory = SubCategory::loadsubcat('invitations');
                                        $category_ids = Vendor::Vendorcategories($slug);
                                        $category_list = Category::Vendorcategorylist($category_ids['category_id']);
                                       // print_r($category_list);die;
                                        if (count($category_list) > 3) {
                                            $class = "test_scroll";
                                        } else {
                                            $class = "";
                                        }
                                        ?>
                                        <div class="panel panel-default" >
                                            <div class="panel-heading">
                                                <div class="clear_left"><p>Categories <a href="javascript:void(0)" class="filter-clear" id="filter-clear" title="Clear">- Clear</a></p></div>
                                                <div class="clear_right">
                                                    <a href="#bakery" id="sub_category_cakes" data-parent="#accordion" data-toggle="collapse" class="collapsed">
                                                        <h4 class="panel-title">
                                                            <span class="plus_acc"></span>
                                                        </h4>
                                                    </a>
                                                </div>
                                            </div>
                                            <div id="bakery" class="panel-collapse collapse" area-expanded="true" aria-expanded="true">
                                                <div class="panel-body">
                                                    <div class="table">
                                                        <ul class="<?= $class; ?>">
                                                <?php foreach ($category_list as $key => $c_value) { ?>
                                                                <li>
                                                                <?php if ($c_value['slug'] == 'say-thank-you') { ?>
                                                                        <label class="label_check" for="checkbox-<?= $c_value['category_name'] ?>"><input name="category" data-element="input" class="category" id="checkbox-<?= $c_value['category_name'] ?>" value="<?= $c_value['slug'] ?>" step="<?= $c_value['category_id'] ?>" type="checkbox" <?php echo (isset($checked) && $checked != "") ? $checked : ''; ?> >Say "Thank You"</label>
    <?php } else { ?>
                                                                        <label class="label_check" for="checkbox-<?= $c_value['category_name'] ?>"><input name="category" data-element="input" class="category" id="checkbox-<?= $c_value['category_name'] ?>" value="<?= $c_value['slug'] ?>" step="<?= $c_value['category_id'] ?>" type="checkbox" <?php echo (isset($checked) && $checked != "") ? $checked : ''; ?> ><?= ucfirst(strtolower($c_value['category_name'])); ?></label>
                                                                    </li>
                                            <?php }
                                        } ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php /* Hide Subcategory filters here
                                          $col=1;
                                          foreach ($subcategory as $key => $value) {
                                          $t = $in ='';
                                          if($col==1){
                                          $s_class='minus_acc';$t='area-expanded="true"';$in='in';
                                          }else{
                                          $s_class='plus_acc';
                                          }
                                          ?>
                                          <div class="panel panel-default" >
                                          <div class="panel-heading">
                                          <div class="clear_left"><p><?= $value['category_name']; ?> <a href="javascript:void(0)" class="filter-clear" id="filter-clear" title="Clear">- Clear</a></p></div>
                                          <div class="clear_right">
                                          <a href="#<?= $value['category_id']; ?>" id="category" data-parent="#accordion" data-toggle="collapse" class="collapsed">
                                          <h4 class="panel-title">
                                          <span class="<?= $s_class;?>"></span>
                                          </h4>
                                          </a>
                                          </div>
                                          </div>
                                          <div id="<?= $value['category_id']; ?>" <?= $t; ?> class="panel-collapse collapse <?= $in; ?>"  >
                                          <div class="panel-body">
                                          <div class="table">
                                          <?php $childcategory = ChildCategory::loadchildcategoryslug($value['category_id']);
                                          /* Display scroll for more than three li *//*
                                          if(count($childcategory) > 3 ) { $class = "test_scroll"; } else { $class = "";}
                                          /* Display scroll for more than three li *//*
                                          ?>
                                          <ul class="<?= $class; ?>">
                                          <?php
                                          foreach ($childcategory as $key => $value) {

                                          /* BEGIN check category checbox values */ /*
                                          if(isset($_GET['category']) && $_GET['category'] !="")
                                          {
                                          $val = explode(',',$_GET['category']);
                                          if(in_array($key,$val))
                                          {
                                          $checked = 'checked=checked';
                                          }
                                          else
                                          {
                                          $checked = '';
                                          }
                                          }
                                          /* END check category checbox values */ /*
                                          ?>
                                          <li>
                                          <label class="label_check" for="checkbox-<?= $value['category_name'] ?>"><input name="items" data-element="input" class="items" id="checkbox-<?= $value['category_name'] ?>" value="<?= $value['slug'] ?>" step="<?= $value['category_id'] ?>" type="checkbox" <?php echo (isset($checked) && $checked !="") ?  $checked : ''; ?> ><?= $value['category_name']; ?></label>
                                          </li>

                                          <?php }  ?>
                                          </ul>
                                          </div>
                                          </div>
                                          </div>
                                          </div>
                                          <?php $col++; } */ ?>
                                        <!--  END CATEGORY FILTER-->
                                        <!--  BEGIN THEME FILTER-->
                                        <div class="panel panel-default" >
                                            <div class="panel-heading">
                                                <div class="clear_left"><p>Themes <a href="javascript:void(0)" class="filter-clear" id="filter-clear" title="Clear">- Clear</a></p></div>
                                                <div class="clear_right">
                                                    <a href="#themes" id="category" data-parent="#accordion" data-toggle="collapse" class="collapsed">
                                                        <h4 class="panel-title">
                                                            <span class="plus_acc"></span>
                                                        </h4>
                                                    </a>
                                                </div>
                                            </div>
                                            <div id="themes" class="panel-collapse collapse" aria-expanded="false">
                                                <div class="panel-body">
                                                    <div class="table">
                                                            <?php
                                                            /* BEGIN Display scroll for more than three li */
                                                            if (count($themes) > 3) {
                                                                $class = "test_scroll";
                                                            } else {
                                                                $class = "";
                                                            }
                                                            /* END Display scroll for more than three li */
                                                            ?>
                                                        <ul class="<?= $class; ?>">
<?php foreach ($themes as $key => $value) { ?>
                                                                <li>
                                                                    <label class="label_check" for="checkbox-<?= $value['theme_name'] ?>"><input name="themes" data-element="input" class="items" id="checkbox-<?= $value['theme_name'] ?>" step="<?= $value['theme_id'] ?>" value="<?= $value['slug'] ?>" type="checkbox" <?php echo (isset($checked) && $checked != "") ? $checked : ''; ?> ><?= $value['theme_name']; ?></label>
                                                                </li>
<?php } ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--  END THEME FILTER -->
                                        <!--  BEGIN PRICE FILTER -->
                                        <div class="panel panel-default" >
                                            <div class="panel-heading">
                                                <div class="clear_left"><p>Price <a href="javascript:void(0)" class="filter-clear" id="filter-clear" title="Clear">- Clear</a></p></div>
                                                <div class="clear_right">
                                                    <a href="#price" data-parent="#accordion" data-toggle="collapse" class="collapsed" id="sub_category_price">
                                                        <h4 class="panel-title">
                                                            <span class="plus_acc">
                                                            </span>
                                                        </h4></a>
                                                </div>
                                            </div>
                                            <div class="panel-collapse collapse" style="height: 0px;" id="price" area-expanded="true" aria-expanded="true">
                                                <div class="panel-body">
                                                    <div class="table">
                                                        <ul class="test_scroll">
                                                            <?php
                                                            /* Get max price_per_unit in item table */
                                                            $min_price = Yii::$app->db->createCommand('SELECT MIN(item_price_per_unit) as price FROM `whitebook_vendor_item` WHERE trash="Default" and item_approved="Yes"  and item_status="Active" and item_for_sale="Yes"')->queryAll();
                                                            $max_price = Yii::$app->db->createCommand('SELECT MAX(item_price_per_unit) as price FROM `whitebook_vendor_item` WHERE trash="Default" and item_approved="Yes"  and item_status="Active" and item_for_sale="Yes"')->queryAll();
                                                            $max = $max_price[0]['price'];
                                                            $divide = round($max / 5);
//$maxx = $max+
                                                            $i = 0;
                                                            for ($x = $min_price[0]['price']; $x <= 1000; $x+=$divide) {
//$item_price = $imageData[$i]['item_price_per_unit'];
                                                                $min_kd = round($x - $divide);

//if($min_kd > 0 && $item_price >= $min_kd && $item_price <= $x)
                                                                if ($min_kd > 0) {
                                                                    foreach ($vendorData as $key => $value) {
                                                                        # code...
                                                                        $item_price = $value['item_price_per_unit'];

                                                                        $check_range = ($item_price >= $min_kd && $item_price <= $x) ? 1 : 0;

                                                                        if ($check_range == 1) {
                                                                            ?>
                                                                            <li>
                                                                                <label class="label_check" for="checkbox-<?php echo $x; ?>">
                                                                                    <input name="price" id="checkbox-<?php echo $x; ?>" value=<?php echo $min_kd . '-' . $x; ?> type="checkbox">
                <?php echo $min_kd = floor($min_kd / 100) * 100;
                $min_kd; ?> KD  -  <?php echo $x = ceil($x / 100) * 100; ?> KD</label>
                                                                            </li>
                <?php
                break;
            }
            $i++;
        }
    }
}
?>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--  END PRICE FILTER-->
                                        <!-- END FILTER  -->
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
                                                        // echo $value['image_path'];die;
                                                        if ($value['image_path'] != "") {
                                                            ?>
                                        <li>
                                            <div class="events_items">
                                                <div class="events_images">
                                                    <div class="hover_events">
                                                        <div class="pluse_cont">
                                                        <?php if (Yii::$app->user->isGuest) { ?>
                                                                <a href=""  role="button" class=""  data-toggle="modal"  onclick="show_login_modal(<?php echo $value['item_id']; ?>);" data-target="#myModal" title="<?php echo Yii::t('frontend', 'ADD_EVENT'); ?>"></a>
                                                        <?php } else { ?>
                                                                <a  href="#" role="button" id="<?php echo $value['item_id']; ?>" name="<?php echo $value['item_id']; ?>" class=""   data-toggle="modal" data-target="#add_to_event<?php echo $value['item_id']; ?>" onclick="addevent('<?php echo $value['item_id']; ?>')" title="<?php echo Yii::t('frontend', 'ADD_EVENT'); ?>"></a>
                                                        <?php } ?></div>

                                                                <?php if (Yii::$app->user->isGuest) { ?>
                                                            <div class="faver_icons">
                                                                <a href=""  role="button" class=""  data-toggle="modal" id="<?php echo $value['item_id']; ?>" onclick="show_login_modal_wishlist(<?php echo $value['item_id']; ?>);" data-target="#myModal" title="<?php echo Yii::t('frontend', 'ADD_FAV'); ?>"></a>
                                                            </div>
            <?php
            } else {
                $k = array();
                foreach ($customer_events_list as $l) {
                    $k[] = $l['item_id'];
                }
                $result = array_search($value['item_id'], $k);
                if (is_numeric($result)) {
                    ?>  <div class="faver_icons faverited_icons"> <?php } else { ?>
                                                                    <div class="faver_icons">
                                            <?php } ?>
                                                                    <a  href="javascript:;" role="button" id="<?php echo $value['item_id']; ?>"  class="add_to_favourite" name="add_to_favourite" title="<?php echo Yii::t('frontend', 'ADD_FAV'); ?>"></a></div>
                                        <?php } ?>
                                                        </div>

                                                        <a href="<?= Url::to(["product/product", 'slug' => $value['slug']]) ?>" title="" ><?= Html::img(Yii::getAlias("@vendor_item_images_210/") . $value['image_path'], ['class' => 'item-img', 'style' => 'width:210px; height:208px;']); ?></a>
                                                    </div>
                                                    <div class="events_descrip">
                                                        <a href="<?= Url::to(["product/product", 'slug' => $value['slug']]) ?>" title=""><?= $value['vendor_name'] ?>
                                                            <h3><?= $value['item_name']; ?></h3>
                                                            <p><? if($value['item_price_per_unit'] !='') {echo $value['item_price_per_unit'].'.00 KD'; }else echo '-';?></p></a>
                                                    </div>
                                                </div>
                                        </li>
        <?php
        }
    }
} else {
    echo "No records found";
}
?>
                        </ul>
                    </div>
                    <div id="planloader"><img src="<?php echo Url::to("@web/images/ajax-loader.gif"); ?>" title="Loader" style="margin-top: 15%;"></div>
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

                                                    jQuery('.label_check input').on('change', function ()
                                                    {
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
                                                        if (newUrl != '')
                                                        {
                                                            slug = newUrl.substring(newUrl.lastIndexOf('/') + 1);
                                                        }
                                                        else
                                                        {
                                                            slug = url.substring(url.lastIndexOf('/') + 1);

                                                        }

                                                        /* if all checkbox uncheck load items based on category */
                                                        if (category_name == "" && theme_name == "")
                                                        {
                                                            window.history.pushState("test", "Title", newUrl);
                                                            slug = "<?php echo $slug; ?>";
                                                        }

                                                        if (category_name != "" || theme_name != "" || price_val != "")
                                                        {
                                                            url_path = '?category=' + category_name + '?themes=' + theme_name + '&price=' + price_val;
                                                        }

                                                        var path = "<?= Url::toRoute('/plan/loadvendoritems',true); ?>";
                                                        <?php $giflink = Yii::$app->homeUrl . Yii::getAlias('@gif_img'); ?>

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
                                                            jQuery(".test_scroll").mCustomScrollbar(
                                                                    {theme: "rounded-dark",
                                                                        mouseWheelPixels: 50,
                                                                        scrollInertia: 0
                                                                    });
                                                        });
                                                    })(jQuery);
</script>
