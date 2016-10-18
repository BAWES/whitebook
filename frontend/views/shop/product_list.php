<?php
    use common\models\ChildCategory;
    use common\models\SubCategory;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\Breadcrumbs;
    use yii\web\view;

\Yii::$app->view->registerMetaTag(['name' => 'csrf-token', 'content' => Yii::$app->request->csrfToken]);

    $this->params['breadcrumbs'][] = ['label' => ucfirst($Category->category_name), 'url' => Url::to(["shop/products", 'slug' => $slug])];
    $get = Yii::$app->request->get();

$session = Yii::$app->session;
echo $deliver_location   = ($session->has('deliver-location')) ? $session->get('deliver-location') : null;
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
                <div class="overlay_filter"></div>

                <span class="filter_butt visible-xs visible-sm">
                    <i class="fa fa-filter"></i>
                </span>

                <div class="col-md-3 paddingleft0 hidden-xs hidden-sm" id="left_side_cate">
                    <div class="filter_content">
                        <div class="filter_section">

                            <div class=""><!-- responsive-category-top -->
                                <div class="listing_sub_cat1">
                                    <span class="title_filter"><?= Yii::t('frontend', 'Categories') ?></span>
                                    <select class="selectpicker" id="main-category">
                                        <?php

                                        foreach ($TopCategories as $category) {

                                            if ($Category->category_id == $category['category_id']) {
                                                $selected = 'selected="selected"';
                                            } else {
                                                $selected = '';
                                            }

                                            if(Yii::$app->language == "en"){
                                                $category_name = $category['category_name'];
                                            }else{
                                                $category_name = $category['category_name_ar'];
                                            }
                                            ?>
                                            <option
                                                data-icon="<?= $category['icon'] ?>"
                                                value="<?= Url::toRoute(['shop/products', 'slug'=> $category['slug']]) ?>"
                                                name="category" <?= $selected ?>>
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
                                            <?=$this->render('@frontend/views/common/filter/category.php',['slug'=>$slug]); ?>
                                        </div>
                                    </div>
                                </nav>
                                                                
                                <div class="filter_title">
                                    <span class="title_filter color_yellow"><?= Yii::t('frontend', 'Filter by') ?></span>
                                </div>

                                <nav class="row-offcanvas row-offcanvas-left">
                                    <div class="listing_content_cat sidebar-offcanvas" id="sidebar" role="navigation" >
                                        <div id="accordion" class="panel-group">
                                            <?=$this->render('@frontend/views/common/filter/date.php',['deliver_date'=>$deliver_date]);  ?>
                                            <?=$this->render('@frontend/views/common/filter/locations.php',['deliver_location'=>$deliver_location]);  ?>
                                            <?=$this->render('@frontend/views/common/filter/price.php');  ?>
                                            <?=$this->render('@frontend/views/common/filter/theme.php',['themes'=>$themes]); ?>
                                            <?=$this->render('@frontend/views/common/filter/vendor.php',['vendor'=>$vendor]); ?>
                                        </div>
                                    </div>
                                </nav>
                            </div>

                            <button class="btn btn-close-filter visible-sm visible-xs">Close filter</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-9 paddingright0">
                    <div class="banner_section_plan">
                        <?= Html::img("@web/images/banner_plan.png") ?>
                    </div>
                    <!-- BEGIN Item lists -->
                    <div class="listing_right">
                        <?=$this->render('@frontend/views/common/items',['items' => $provider, 'customer_events_list' => $customer_events_list]); ?>
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
var load_more_items = '".Url::to(['shop/load-more-items'])."';
//var load_items = '".Url::to(['shop/load-items'])."';
var load_items = '".Url::to(['shop/products'])."';
var product_slug = '".$get['slug']."';
", View::POS_BEGIN);

$this->registerJs("
jQuery(document).delegate('a#filter-clear-date', 'click', function(){
    jQuery('#delivery_date_2').val('');
    jQuery(this).hide();
    filter();
})
", View::POS_END);
