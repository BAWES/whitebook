<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\VendorCategory;
use common\components\LangFormat;
use yii\widgets\ActiveForm;

$vendor_details = $vendor_detail;

\Yii::$app->view->registerMetaTag(['name' => 'csrf-token', 'content' => Yii::$app->request->csrfToken]);

\Yii::$app->view->title = Yii::$app->params['SITE_NAME'].' | '.$vendor_details['vendor_name'];
\Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => Yii::$app->params['META_DESCRIPTION']]);
\Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => Yii::$app->params['META_DESCRIPTION']]);

$baselink =  Url::to("@web/images/item-default.png");

if($vendor_details['vendor_logo_path']) {
    $baselink = Yii::getAlias('@vendor_logo/').$vendor_details['vendor_logo_path'];
}

$url = \yii\helpers\Url::toRoute(["community/profile", 'slug' => $vendor_details->slug], true);
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
$deliver_location   = ($session->has('delivery-location')) ? $session->get('delivery-location') : null;
$deliver_date       = ($session->has('delivery-date')) ? $session->get('delivery-date') : '';

$description = nl2br(LangFormat::format(strip_tags($vendor_detail['short_description']), strip_tags($vendor_detail['short_description_ar'])));

$return_policy = nl2br(LangFormat::format(strip_tags($vendor_detail['vendor_return_policy']), strip_tags($vendor_detail['vendor_return_policy_ar'])));

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

                                <?php if($description) { ?>
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
                                        <p><?= $description; ?></p>
                                      </div>
                                    </div>
                                </div><!-- END .panel -->
                                <?php } ?>

                                <?php if($return_policy) { ?>
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
                                        <p><?= $return_policy; ?></p>
                                      </div>
                                    </div>
                                </div><!-- END .panel -->
                                <?php } ?>

                                <?php if($canAddReview || $reviews) { ?>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                      <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse3" class="collapsed">
                                            <?php echo Yii::t('frontend', 'Review'); ?>
                                        </a>
                                      </h4>
                                    </div>
                                    <div id="collapse3" class="panel-collapse collapse">
                                      <div class="panel-body">

                                            <?php if($canAddReview) { ?>

                                            <?php $form = ActiveForm::begin(['options'=> ['id' => 'review-form']]); ?>

                                                <?= $form->field($modelReview, 'vendor_id')
                                                    ->hiddenInput()
                                                    ->label(false) ?>

                                                <?= $form->field($modelReview, 'rating')
                                                    ->hiddenInput()
                                                    ->label(false) ?>

                                                <div class="rating">
                                                    <ul>
                                                        <li data-value="1">
                                                            <i class="fa fa-star"></i>
                                                        </li>
                                                        <li data-value="2">
                                                            <i class="fa fa-star"></i>
                                                        </li>
                                                        <li data-value="3">
                                                            <i class="fa fa-star"></i>
                                                        </li>
                                                        <li data-value="4">
                                                            <i class="fa fa-star"></i>
                                                        </li>
                                                        <li data-value="5">
                                                            <i class="fa fa-star"></i>
                                                        </li>
                                                    </ul>
                                                </div>

                                                <?= $form->field($modelReview, 'review')
                                                    ->textarea(['rows' => 3, 'placeholder' => Yii::t('frontend', 'Enter your review here...')])
                                                    ->label(false) ?>

                                                <button class="btn btn-default btn-submit-review">
                                                    <?= Yii::t('frontend', 'Submit') ?>
                                                </button>

                                           <?php ActiveForm::end(); ?>

                                           <?php }//if canAddReview ?>
                                      </div>
                                    </div>
                                </div><!-- END .panel -->
                                <?php } ?>
                                
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
                                $vendorUrl = \yii\helpers\Url::toRoute(["community/profile", 'slug' => $vendor_detail->slug], true);
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
$this->registerJsFile("@web/js/pages/profile.js", ['depends' => [\yii\web\JqueryAsset::className()]]);

$get = Yii::$app->request->get();
$slug = (isset($get['slug'])) ? $get['slug'] : 'all';
$this->registerJs("
    var giflink = '".Url::to("@web/images/ajax-loader.gif")."';
    //var load_items = '".Url::to(['community/profile'])."';
    var load_items = '".Url::to(['/vendor'])."';
    var reviewUrl = '".Url::to(['/vendor/review'])."'; 
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