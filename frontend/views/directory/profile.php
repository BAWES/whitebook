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

$baselink =  Url::to("@web/images/item-default.png");

if($vendor_details['vendor_logo_path']) {
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

$session = Yii::$app->session;
$deliver_location   = ($session->has('deliver-location')) ? $session->get('deliver-location') : null;
$deliver_date       = ($session->has('deliver-date')) ? $session->get('deliver-date') : '';

?>
<!-- coniner start -->
<section id="inner_pages_white_back">
    <div class="container paddng0">
        <div class="vender_profile_new">
            <div class="product_detials_vender aother_dates">
                <div class="col-md-5 padding0 vendor_photo thumbnail">
                    <a href="#" title="">
                        <?= Html::img($baselink, ['class'=>'','width'=>'450','alt'=>'Logo']); ?>
                    </a>
                </div>
                <div class="col-md-7 vendor_detail">
                    <div class="right_descr_product">
                        <div class="accad_menus">
                            <div class="bakery_title">
                                <h3><?php echo $vendor_detail['vendor_name']; ?></h3>
                            </div>
                            <div class="panel-group vendor-profile-detail" id="accordion">

                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                      <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">
                                            <?php echo Yii::t('frontend', 'Description'); ?>
                                        </a>
                                      </h4>
                                    </div>
                                    <div id="collapse1" class="panel-collapse collapse in">
                                      <div class="panel-body">
                                        <p><?= nl2br(LangFormat::format(strip_tags($vendor_detail['short_description']), strip_tags($vendor_detail['short_description_ar']))); ?></p>
                                      </div>
                                    </div>
                                </div><!-- END .panel -->

                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                      <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse2" class="collapsed">
                                            <?php echo Yii::t('frontend', 'Return policy'); ?>
                                        </a>
                                      </h4>
                                    </div>
                                    <div id="collapse2" class="panel-collapse collapse">
                                      <div class="panel-body">
                                        <p><?= nl2br(LangFormat::format(strip_tags($vendor_detail['vendor_return_policy']), strip_tags($vendor_detail['vendor_return_policy_ar']))); ?></p>
                                      </div>
                                    </div>
                                </div><!-- END .panel -->

                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                      <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse3" class="collapsed">
                                            <?= Yii::t('frontend', 'Contact info'); ?>
                                        </a>
                                      </h4>
                                    </div>
                                    <div id="collapse3" class="panel-collapse collapse">
                                        <div class="panel-body vendor_social_info">
                                            <ul>         
                                                <?php if($phones) { ?>
                                                <li class="vendor_phone_list">
                                                    <?php foreach ($phones as $key => $value) { ?>
                                                        <a class="color-808080" href="tel:<?= $value->phone_no; ?>"><i class="<?= $phone_icons[$value->type] ?>"></i><?= $value->phone_no; ?>
                                                        </a>
                                                    <?php } ?>
                                                </li> 
                                                <?php } ?>

                                                <?php if (!empty($vendor_detail['vendor_contact_address'])) { ?>
                                                <li>
                                                    <a><i class="fa fa-map-marker"></i>
                                                        <?= LangFormat::format($vendor_detail['vendor_contact_address'], $vendor_detail['vendor_contact_address_ar']); ?>
                                                    </a>
                                                </li>
                                                <?php } ?>

                                                <?php if($txt_day_off) { ?>
                                                <li>
                                                    <a>
                                                        <i class="fa fa-clock-o"></i>
                                                        <?= Yii::t('frontend', '{txt_day_off} off', [
                                                                'txt_day_off' => $txt_day_off
                                                            ]); ?>
                                                    </a>
                                                </li>
                                                <?php } ?>

                                                <?php if (!empty($vendor_detail['vendor_public_email'])) { ?>
                                                <li>                                                    
                                                    <a href="mailto:<?=$vendor_detail['vendor_public_email']; ?>" title="<?= $vendor_detail['vendor_public_email']; ?>">
                                                        <i class="fa fa-envelope-o"></i>
                                                        <?= $vendor_detail['vendor_public_email']; ?>
                                                    </a>
                                                </li>
                                                <?php } ?>

                                                <?php if (!empty($vendor_detail['vendor_website'])) { ?>
                                                <li>
                                                    <a target="_blank" href="<?= $vendor_detail['vendor_website']; ?>" title="<?php echo $vendor_detail['vendor_website']; ?>">
                                                        <i class="fa fa-globe"></i>
                                                        <?php echo $vendor_detail['vendor_website']; ?>
                                                    </a>
                                                </li>     
                                                <?php } ?>

                                                <?php if($vendor_detail['vendor_instagram']) { ?>
                                                <li>
                                                    <a target="_blank" href="<?= $vendor_detail['vendor_instagram'] ?>" alt="<?= Yii::t('frontend', 'Instatgram') ?>"><i class="fa fa-instagram"></i>
                                                        <?= $vendor_detail['vendor_instagram_text'] ?>
                                                    </a>
                                                </li>
                                                <?php } ?>  

                                                <?php if($vendor_detail['vendor_twitter']) { ?>
                                                <li>
                                                    <a target="_blank" href="<?= $vendor_detail['vendor_twitter'] ?>" alt="<?= Yii::t('frontend', 'Twitter') ?>"><i class="fa fa-twitter"></i>
                                                        <?= $vendor_detail['vendor_twitter_text'] ?>
                                                    </a>
                                                </li>
                                                <?php } ?>

                                                <?php if($vendor_detail['vendor_facebook']) { ?>
                                                <li>
                                                    <a target="_blank" href="<?= $vendor_detail['vendor_facebook'] ?>" alt="<?= Yii::t('frontend', 'Facebook') ?>"><i class="fa fa-facebook"></i>
                                                        <?= $vendor_detail['vendor_facebook_text'] ?>
                                                    </a>
                                                </li>
                                                <?php } ?>

                                                <?php if($vendor_detail['vendor_youtube']) { ?>
                                                <li>
                                                    <a target="_blank" href="<?= $vendor_detail['vendor_youtube'] ?>" alt="<?= Yii::t('frontend', 'Youtube') ?>"><i class="fa fa-youtube"></i>
                                                        <?= $vendor_detail['vendor_youtube_text'] ?>
                                                    </a>
                                                </li>
                                                <?php } ?>
                                            </ul>
                                        </div><!-- END .panel-body -->
                                    </div>
                                </div><!-- END .panel -->

                                <?php 

                                if ($vendor_detail['vendor_logo_path'] != '') {
                                    $baselink = Yii::getAlias('@vendor_logo/').$vendor_detail['vendor_logo_path'];
                                } else {
                                    $baselink = Url::to("@web/images/item-default.png");
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
                                </div><!-- END .social_share -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="plan_venues total_continer" id="plan_vanues">

            <span class="filter_butt visible-xs visible-sm">
                <i class="fa fa-filter"></i>
            </span>

            <div class="col-md-3 paddingleft0 hidden-xs hidden-sm vendor-filter" id="left_side_cate">

                <?= $this->render('@frontend/views/browse/_filter.php', [
                            'deliver_date' => $deliver_date,
                            'deliver_location' => $deliver_location,
                            'themes' => $themes,
                            'vendor' => [],
                            'slug' => $slug,
                            'TopCategories' => $TopCategories,
                            'Category' => []
                    ]);  ?>

            </div>
            <div class="col-md-9 paddingright0">
                <div class="listing_right">
                    <?= $this->render('@frontend/views/common/items', [
                            'items' => $provider, 
                            'customer_events_list' => $customer_events_list
                        ]); ?>
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