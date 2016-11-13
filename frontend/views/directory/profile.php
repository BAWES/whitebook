<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\VendorCategory;
use common\components\LangFormat;
$vendor_details = $vendor_detail;

\Yii::$app->view->registerMetaTag(['name' => 'csrf-token', 'content' => Yii::$app->request->csrfToken]);

\Yii::$app->view->title = Yii::$app->params['SITE_NAME'].' | '.$vendor_details['vendor_name'];
\Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => Yii::$app->params['META_DESCRIPTION']]);
\Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => Yii::$app->params['META_DESCRIPTION']]);

$baselink = 'https://placeholdit.imgix.net/~text?txtsize=20&txt=No%20Image&w=565&h=565';

if(isset($vendor_details['vendor_logo_path'])) {
    $baselink = Yii::getAlias('@vendor_logo/').$vendor_details['vendor_logo_path'];
}

$url = \yii\helpers\Url::toRoute(["directory/profile", 'slug' => $vendor_details->slug], true);
\Yii::$app->view->registerMetaTag(['property' => 'og:title', 'content' => ucfirst($vendor_details->vendor_name)]);
\Yii::$app->view->registerMetaTag(['property' => 'fb:app_id', 'content' => 157333484721518]);
\Yii::$app->view->registerMetaTag(['property' => 'og:url', 'content' => $url]);
\Yii::$app->view->registerMetaTag(['property' => 'og:image', 'content' => $baselink]);
\Yii::$app->view->registerMetaTag(['property' => 'og:image:width', 'content' => '200']);
\Yii::$app->view->registerMetaTag(['property' => 'og:image:height', 'content' => '200']);
\Yii::$app->view->registerMetaTag(['property' => 'og:site_name', 'content' => ucfirst($vendor_details->vendor_name)]);
\Yii::$app->view->registerMetaTag(['property' => 'og:description', 'content' => trim(strip_tags($vendor_details->short_description))]);

\Yii::$app->view->registerMetaTag(['property' => 'twitter:card', 'content' => 'summary_large_image']);

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
                    <div class="col-md-6 padding0 vendor_photo">
                        <a href="#" title="">
                            <?= Html::img($baselink, ['class'=>'','width'=>'565','alt'=>'Logo']); ?>
                        </a>
                    </div>
                    <div class="col-md-6 paddingcommon vendor_detail">
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
                                                    <span class="glyphicon glyphicon-menu-right text-align"></span>
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                                            <div class="panel-body">
                                                <p><?=LangFormat::format(strip_tags($vendor_detail['short_description']),strip_tags($vendor_detail['short_description_ar'])); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel panel-default" >
                                        <div class="panel-heading" role="tab" id="headingTwo">
                                            <h4 class="panel-title">
                                                <a class="collapsed" data-toggle="collapse" data-parent="#sub_accordion" id="policy_click" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                    <?php echo Yii::t('frontend', 'Return policy'); ?>
                                                    <span class="glyphicon glyphicon-menu-right text-align"></span>
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                            <div class="panel-body">
                                                <p><?=LangFormat::format(strip_tags($vendor_detail['vendor_return_policy']),strip_tags($vendor_detail['vendor_return_policy_ar'])); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel panel-default">
                                        <div class="panel-heading" role="tab" id="headingFive">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#sub_accordion"  id="contact_click" href="#collapseFive" aria-expanded="true" aria-controls="collapseOne">
                                                    <?= Yii::t('frontend', 'Contact info'); ?>
                                                    <span class="glyphicon glyphicon-menu-down text-align"></span>
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
                                                                            <a href="mailto:<?=$vendor_detail['vendor_public_email']; ?>" title="<?=$vendor_detail['vendor_public_email']; ?>"><?=$vendor_detail['vendor_public_email']; ?>&nbsp;</a>
                                                                        </h3>
                                                                        <span class="border-bottom"></span>
                                                                    <?php } ?>
                                                                    <?php if (trim($vendor_detail['vendor_public_phone'])) { ?>
                                                                        <h4 class="margin-top-13">
                                                                            <a class="color-808080" href="tel:<?=$vendor_detail['vendor_public_phone']; ?>"><?=$vendor_detail['vendor_public_phone']; ?></a>&nbsp;
                                                                        </h4>
                                                                        <span class="border-bottom border-bottom-none"></span>
                                                                    <?php } ?>
                                                                </div>
                                                            <?php } ?>
                                                            <?php if (trim($vendor_detail['vendor_website']) || trim($vendor_detail['vendor_working_hours'])) { ?>
                                                                <div class="col-md-6 col-xs-6 paddingright0 paddingleft0 cont_ifo_right">
                                                                    <?php if (trim($vendor_detail['vendor_website'])) { ?>
                                                                        <span class="links_left">
                                                                            <?php
                                                                            if (strpos($vendor_detail['vendor_website'],'http://') === false){
                                                                                $vendor_detail['vendor_website'] = 'http://'.$vendor_detail['vendor_website'];
                                                                            }
                                                                            ?>

                                                                            <a target="_blank" href="<?=$vendor_detail['vendor_website']; ?>" title="<?php echo $vendor_detail['vendor_website']; ?>">
                                                                                <?php echo $vendor_detail['vendor_website']; ?>&nbsp;
                                                                            </a>
                                                                        </span>
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

                                                        <?php

                                                        $search = array(0, 1, 2, 3, 4, 5, 6, ',');

                                                        $replace = array(
                                                            Yii::t('frontend', 'Sunday'),
                                                            Yii::t('frontend', 'Monday'),
                                                            Yii::t('frontend', 'Tuesday'),
                                                            Yii::t('frontend', 'Wednesday'),
                                                            Yii::t('frontend', 'Thursday'),
                                                            Yii::t('frontend', 'Friday'),
                                                            Yii::t('frontend', 'Saturday'),
                                                            ', '
                                                        );

                                                        $day_off = explode(',', $vendor_detail['day_off']);

                                                        $txt_day_off = str_replace($search, $replace, $vendor_detail['day_off']);

                                                        if($txt_day_off) { ?>
                                                            <div class="cont_ifo_right col-md-6 col-xs-6 paddingleft0 left border-top">
                                                            <span class="working_days">
                                                                <?= Yii::t('frontend', '{txt_day_off} off', [
                                                                    'txt_day_off' => $txt_day_off
                                                                ]); ?>
                                                            </span>
                                                            </div>
                                                        <?php } ?>

                                                        <?php if (trim($vendor_detail['vendor_contact_address']) || $vendor_detail['vendor_contact_address'] != 'n/a') { ?>
                                                            <div class="col-md-6 col-xs-6 paddingleft0 address_ifo_left border-top">
                                                                <h5 class="margin-top-13">
                                                                    <?=LangFormat::format($vendor_detail['vendor_contact_address'],$vendor_detail['vendor_contact_address_ar']); ?>
                                                                </h5>
                                                            </div>
                                                        <?php } ?>


                                                    </address>
                                                </div>
                                            </div>
                                        </div>

                                        <?php if (count($vendor_detail) > 0) {

                                            if ($vendor_detail['vendor_logo_path'] != '') {
                                                $baselink = Yii::getAlias('@vendor_logo/').$vendor_detail['vendor_logo_path'];
                                            } else {
                                                $baselink = Yii::$app->homeUrl . Yii::getAlias('@vendor_images/no_image.png');
                                            }
                                            $title = Yii::$app->name .' '. ucfirst($vendor_detail->vendor_name);
                                            $url = urlencode(Yii::$app->homeUrl . $_SERVER['REQUEST_URI']);
                                            $summary = Yii::$app->name .' '. substr(strip_tags($vendor_detail->short_description),0,10);
                                            $image = isset($baselink) ? $baselink : '';
                                            $vendorUrl = \yii\helpers\Url::toRoute(["directory/profile", 'slug' => $vendor_detail->slug], true);
                                            $mailbody = "Check out ".ucfirst($vendor_detail->vendor_name)." on ".Yii::$app->name." ".$vendorUrl;
                                            ?>
                                            <div class="social_share">
                                                <h3><?= Yii::t('frontend', 'Share this'); ?></h3>
                                                <ul>
                                                    <li><a title="Facebook" href='https://www.facebook.com/sharer/sharer.php?u=<?=urlencode($vendorUrl)?>' onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><span class="flaticon-facebook55"></span></a></li>
                                                    <li><a onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" href="http://twitter.com/share?text=<?=$title?>&url=<?=$vendorUrl; ?>" ><span class="flaticon-twitter13"></span></a></li>
                                                    <?php if ($vendor_detail['vendor_instagram']) { ?>
                                                        <li><a target="_blank" href="<?php echo $vendor_detail['vendor_instagram']; ?>" title="Instatgram"><span class="flaticon-instagram7"></span></a></li>
                                                    <?php } ?>
                                                    <li class="hidden-lg hidden-md"><a href="whatsapp://send?text=<?=$mailbody?>" data-action="share/whatsapp/share"><i class="fa fa-whatsapp" aria-hidden="true"></i></a></li>
                                                    <li><a href="mailto:?subject=TWB Inquiry&body=<?php echo $mailbody; ?>" title="MailTo"><i class="flaticon-email5"></i></a></li>
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

            <div class="plan_venues total_continer" id="plan_vanues">
                <div class="col-md-3 paddingleft0 filter_content_wrapper">

                    <div class="filter_content">

                        <div class="filter_title">
                            <span class="title_filter color_yellow"><?= Yii::t('frontend', 'Filter by'); ?></span>
                        </div>
                        <div class="filter_section">
                            <?php

                            $data = Yii::$app->request->get();

                            $category_list = VendorCategory::find()
                                ->select(['{{%category}}.category_id', '{{%category}}.category_name', '{{%category}}.category_name_ar', '{{%category}}.slug','{{%category}}.icon'])
                                ->leftJoin('{{%category}}','{{%category}}.category_id = {{%vendor_category}}.category_id')
                                ->where([
                                    '{{%category}}.trash' =>'Default',
                                    '{{%category}}.category_level' => 0
                                ])
                                ->groupBy('{{%category}}.category_id')
                                ->asArray()
                                ->all();
                            if (count($category_list) > 3) {
                                $class = "test_scroll";
                            } else {
                                $class = "";
                            } ?>

                            <div class="responsive-category-top">
                                <div class="listing_sub_cat1">
                                    <span class="title_filter"><?= Yii::t('frontend', 'Categories') ?></span>
                                    <select class="selectpicker" style="display: none;" id="main-category">
                                        <option data-icon="" value="<?=yii\helpers\Url::toRoute(['directory/profile', 'slug' => 'all','vendor'=>$data['vendor']]); ?>"><?=Yii::t('frontend','All')?></option>
                                        <?php
                                        foreach($category_list as $category) {

                                            if (isset($data['slug']) && ($data['slug'] == $category['slug'])) {
                                                $selected = 'selected="selected"';
                                                $attributes = 'data-hidden="true"';
                                            } else {
                                                $selected = '';
                                                $attributes = '';
                                            }
                                            $category_name = LangFormat::format($category['category_name'],$category['category_name_ar']);
                                            ?>
                                            <option
                                                data-icon="<?= $category['icon'] ?>"
                                                value="<?=yii\helpers\Url::toRoute(['directory/profile','slug' => $category['slug'], 'vendor' => $data['vendor']]); ?>"
                                                name="category" <?= $selected .' '. $attributes ?>>
                                                <?= $category_name ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div><!-- END .listing_sub_cat1 -->

                            </div><!-- END .responsive-category-top -->

                            <div class="responsive-category-bottom">
                                <nav class="row-offcanvas row-offcanvas-left">
                                    <div class="listing_content_cat sidebar-offcanvas" id="sidebar" role="navigation" >
                                        <div id="accordion" class="panel-group">
                                            <?=$this->render('@frontend/views/common/filter/category.php',['slug'=>$slug]);?>
                                        </div>
                                </nav>
                                <span class="filter_butt title_filter color_yellow col-xs-12 text-right padding0" data-toggle="offcanvas"><?=Yii::t('frontend','Filter')?></span>
                                <div class="filter_butt hamburger is-closed" data-toggle="offcanvas">
                                    <img width="32" height="35" src="<?php echo Url::to("@web/images/cross92.svg"); ?>" alt="click here">
                                </div>
                                <nav class="row-offcanvas row-offcanvas-left">
                                    <div class="listing_content_cat sidebar-offcanvas" id="sidebar" role="navigation" >
                                        <div id="accordion" class="panel-group">
                                            <!-- BEGIN CATEGORY FILTER  -->
                                            <?php
                                                echo $this->render('@frontend/views/common/filter/price.php');
                                                echo $this->render('@frontend/views/common/filter/theme.php',['themes'=>$themes]);
                                            ?>
                                        </div>
                                </nav>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-9 paddingright0">
                    <div class="listing_right">
                        <?=$this->render('@frontend/views/common/items',['items' => $provider, 'customer_events_list' => $customer_events_list]); ?>
                    </div>
                </div>
            </div>

        </div>
    </section>
<?php
$this->registerCssFile("@web/css/owl.carousel.css");
$this->registerCssFile("@web/css/jquery.mCustomScrollbar.css");
$this->registerCssFile("@web/css/bootstrap-select.min.css");
$this->registerJsFile("@web/js/jquery.mCustomScrollbar.concat.min.js", ['depends' => [\yii\web\JqueryAsset::className()]]);

$get = Yii::$app->request->get();
$slug = (isset($get['slug'])) ? $get['slug'] : 'all';
$this->registerJs("
var giflink = '".Url::to("@web/images/ajax-loader.gif")."';
//var load_items = '".Url::to(['directory/profile'])."';
var load_items = '".Url::to(['/vendor'])."';
var product_slug = '".$slug."';
var vendor_profile = '".$get['vendor']."';
var current_page = 'vendor';
", yii\web\View::POS_BEGIN);

$this->registerCss("
.color-808080{color: #808080!important;}
.fa-whatsapp{font-size: 169%;margin-top: 2px;}
.slider-container{
    clear: both;
    padding-top: 9px;
    width: 200px;
}
.margin-top-13{margin-top: 13px;}
")?>