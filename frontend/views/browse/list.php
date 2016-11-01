<?php

use common\models\ChildCategory;
use common\models\SubCategory;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use yii\web\view;

\Yii::$app->view->registerMetaTag(['name' => 'csrf-token', 'content' => Yii::$app->request->csrfToken]);

$CName = (isset($Category->category_name)) ? $Category->category_name  : 'all';

$this->params['breadcrumbs'][] = ['label' => ucfirst($CName), 'url' => Url::to(["browse/list", 'slug' => 'all'])];

$get = Yii::$app->request->get();

$session = Yii::$app->session;
$deliver_location   = ($session->has('deliver-location')) ? $session->get('deliver-location') : null;
$deliver_date       = ($session->has('deliver-date')) ? $session->get('deliver-date') : '';

?>
    <!-- coniner start -->
    <section id="inner_pages_white_back">
        <div id="event_slider_wrapper">
            <div class="container paddng0">
                <?php echo $this->render('/product/events_slider.php');  ?>
            </div>
        </div>
        <div class="container paddng0">
            <div class="breadcrumb_common">
                <div class="bs-example">
                    <?= Breadcrumbs::widget([
                        'options' => ['class' => 'new breadcrumb'],
                        'homeLink' => [
                            'label' => Yii::t('yii', 'Home'),
                            'url' => Yii::$app->homeUrl,
                        ],
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    ]); ?>
                </div>
            </div>
            <div class="plan_venues" id="wrapper">
                <div class="overlay"></div>
                <div class="overlay_filter clearfix">
                    <div class="col-lg-3" style="padding-left:0px;height: auto!important;">
                        <?= $this->render('@frontend/views/common/filter/date.php', [
                            'deliver_date' => $deliver_date
                        ]); ?>
                    </div>
                    <div class="col-lg-3">
                        <?= $this->render('@frontend/views/common/filter/locations.php', [
                            'deliver_location' => $deliver_location
                        ]);  ?>
                    </div>

                </div>

                <span class="filter_butt visible-xs visible-sm">
                    <i class="fa fa-filter"></i>
                </span>

                <div class="col-md-3 paddingleft0 hidden-xs hidden-sm" id="left_side_cate">

                    <?= $this->render('@frontend/views/browse/_filter.php', [
                            'deliver_date' => $deliver_date,
                            'deliver_location' => $deliver_location,
                            'themes' => $themes,
                            'vendor' => $vendor,
                            'slug' => $slug,
                            'TopCategories' => $TopCategories,
                            'Category' => $Category
                    ]);  ?>

                </div>
                <div class="col-md-9 paddingright0">
                    <div class="banner_section_plan">
                        <?= Html::img("@web/images/banner_plan.png") ?>
                    </div>
                    <!-- BEGIN Item lists -->
                    <div class="listing_right">
                        <?=$this->render('@frontend/views/common/items', [
                            'items' => $provider, 
                            'customer_events_list' => $customer_events_list
                        ]); ?>
                    </div>
                    <div class="banner_section_plan">
                        <?= Html::img("@web/images/banner_plan.png") ?>
                    </div>
                    <!-- END Item lists -->
                </div>
            </div>
        </div>
    </section>
<?php
$this->registerCssFile("@web/css/owl.carousel.css");
$this->registerCssFile("@web/css/jquery.mCustomScrollbar.css");
$this->registerCssFile("@web/css/bootstrap-select.min.css");
$this->registerJsFile("@web/js/jquery.mCustomScrollbar.concat.min.js", ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJs("
var giflink = '".Url::to("@web/images/ajax-loader.gif")."';
var load_items = '".Url::to(['browse/list'])."';
var product_slug = '".$get['slug']."';
", View::POS_BEGIN);

$this->registerJs("
jQuery(document).delegate('a#filter-clear-date', 'click', function(){
    jQuery('#delivery_date_2').val('');
    jQuery(this).hide();
    filter();
})
", View::POS_END);
