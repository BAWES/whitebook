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
$deliver_location   = ($session->has('delivery-location')) ? $session->get('delivery-location') : null;
$deliver_date       = ($session->has('delivery-date')) ? $session->get('delivery-date') : '';

if(isset($get['themes']))
{
    $selected_themes = $get['themes'];
}
else
{
    $selected_themes = [];//['all'];
} 
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
                <div class="overlay_filter clearfix hidden-xs hidden-sm">

                    <div class="col-lg-3 padding-left-0 theme-filter">
                        <?= $this->render('@frontend/views/common/filter/themes.php', [
                            'themes' => $themes,
                            'selected_themes' => $selected_themes
                        ]); ?>
                    </div>  

                    <div class="col-lg-3 padding-left-0 date-filter">
                        <?= $this->render('@frontend/views/common/filter/date.php', [
                            'deliver_date' => $deliver_date
                        ]); ?>
                    </div>

                    <div class="col-lg-3 padding-left-0 event-filter">
                        <?= $this->render('@frontend/views/common/filter/event_time.php');  ?>
                    </div>

                    <div class="col-lg-3 padding-left-0 location-filter">
                        <?= $this->render('@frontend/views/common/filter/locations.php', [
                            'deliver_location' => $deliver_location
                        ]);  ?>
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

$(document).delegate('#theme_filter', 'change', function() {
    
    /*if($('#theme_filter option[value=\"all\"]:selected').length > 0) {
        $('#theme_filter').selectpicker('val', ['all']);
    }else{

        var a = [];

        $('#theme_filter option:selected').each(function(){

            if($(this).val() != 'all') 
                a.push($(this).val());
        });

        $('#theme_filter').selectpicker('val', a);
    }*/

    if($('#theme_filter option:selected').length > 0 && $('#theme_filter option:selected').val() != 'all') {
        $('#top_panel_theme .filter-clear').css('display', 'inline');
    }else{
        $('#top_panel_theme .filter-clear').css('display', 'none');
    }

    filter();
});

$('#top_panel_theme .filter-clear').click(function(){
    $('#theme_filter').selectpicker('val', []);
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
