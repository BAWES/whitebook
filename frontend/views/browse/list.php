<?php

use yii\web\view;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use common\models\SubCategory;
use common\models\ChildCategory;

\Yii::$app->view->registerMetaTag(['name' => 'csrf-token', 'content' => Yii::$app->request->csrfToken]);

if(Yii::$app->language == 'ar') {
    $CName = (isset($Category->category_name_ar)) ? $Category->category_name_ar  : Yii::t('frontend', 'All');
}else{
    $CName = (isset($Category->category_name)) ? $Category->category_name  : Yii::t('frontend', 'All');
}

$this->params['breadcrumbs'][] = ['label' => ucfirst($CName), 'url' => Url::to(["browse/list", 'slug' => 'all'])];

$get = Yii::$app->request->get();

$session = Yii::$app->session;
$deliver_location   = ($session->has('deliver-location')) ? $session->get('deliver-location') : null;
$deliver_date       = ($session->has('deliver-date')) ? $session->get('deliver-date') : '';

?>

    <!-- coniner start -->
    <section id="inner_pages_white_back" class="<?=Yii::$app->controller->id;?>">
        <?php /* @TODO Removed Event Section ?>
        <div id="event_slider_wrapper">
            <div class="container paddng0">
                <?php echo $this->render('/product/events_slider.php');  ?>
            </div>
        </div>
        <?php */ ?>
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
                    <div class="col-lg-3 padding-left-0 date-filter">
                        <?= $this->render('@frontend/views/common/filter/date.php', [
                            'deliver_date' => $deliver_date
                        ]); ?>
                    </div>
                    <div class="mid-space">
                        &nbsp;
                    </div>
                    <div class="col-lg-3 padding-left-0 location-filter">
                        <?= $this->render('@frontend/views/common/filter/locations.php', [
                            'deliver_location' => $deliver_location
                        ]);  ?>
                    </div>
                    <div class="mid-space">
                        &nbsp;
                    </div>
                    <div class="col-lg-2 padding-left-0 available-filter">
                        <div class="panel panel-default" id="top_panel_location">
                            <div class="panel-heading clearfix" id="top_panel_heading">
                                <div class=""><p><?=Yii::t('frontend','Product Type')?></p></div>
                            </div>
                            <div id="available-for-sale" class="panel-collapse " aria-expanded="false">
                                <div class="">
                                    <div class="form-group margin-0">
                                        <?php
                                        $checked1 = '';
                                        if (isset($get['for_sale'])) {
                                            $checked1 = 'checked=checked';
                                        }
                                        ?>
                                        <label class="label_check margin-0" for="checkbox-available-for-sale">
                                        <input name="for_sale" data-element="input" class="items"
                                               id="checkbox-available-for-sale"
                                               value="sale"
                                               type="checkbox" <?php echo (isset($checked1) && $checked1 != "") ? $checked1 : ''; ?> >
                                                <span><?=Yii::t('frontend','Available For Sale')?></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <span class="filter_butt visible-xs visible-sm">
                    <i class="fa fa-filter"></i>
                </span>

                <div class="col-md-3 paddingleft0 hidden-xs hidden-sm left-sidebar" id="left_side_cate">

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
                <div class="col-md-9 paddingright0 right-sidebar">                  
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

$this->registerJs("
var giflink = '".Url::to("@web/images/ajax-loader.gif")."';
var load_items = '".Url::to(['/browse'],true)."';
var normalize_url = '".Url::to(['site/normalise-url'])."';
var product_slug = '".$get['slug']."';
var current_page = 'browse';
", View::POS_BEGIN);

$this->registerJs("

jQuery(document).delegate('a#filter-clear-date', 'click', function() {
    jQuery('#delivery_date_2').val('');
    jQuery(this).hide();
    filter();
});

", View::POS_END);

$this->registerCss("
@media screen and (max-width: 1195px) {
    .mid-space {display: none;}
}
.padding-left-0{padding-left:0px;height: auto!important;}
.mid-space{float:left;width: 1.5%;}
.label_check span{font-weight:normal;}
.label_check {
    margin-top: 10px!important;
    line-height: .9;
    }
    .margin-0{margin:0px!important;}
    #available-for-sale{
        padding: 15px 13px;
    }
");
