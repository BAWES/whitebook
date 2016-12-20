<?php

use yii\web\view;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Breadcrumbs;
use common\models\image;
use common\models\Category;
use common\models\VendorItem;
use common\models\CategoryNote;
use common\components\CFormatter;
use common\components\LangFormat;
use frontend\models\EventItemlink;

$this->title = Yii::t('frontend', 'Event Detail'); 

?>
<section id="inner_pages_sections">
    <div class="container">
        <div class="title_main">
            <h1>
                <?php echo Yii::t('frontend', 'Event Detail'); ?>
            </h1>

            <h2><?= $event_details->event_name ?></h2>

            <center>
                <p><?php echo date('d-m-Y',strtotime($event_details->event_date)); ?></p>
                <label><?php echo $event_details->event_type; ?></label>
                <?php if($event_details->no_of_guests) { ?>
                    <p><?= Yii::t('frontend', 'Guests : {count}', [
                            'count' => $event_details->no_of_guests
                        ]); ?></p>
                <?php } ?>
            </center>
        </div>
        <div class="events_detials_common col-lg-12">
            
            <div class="right_descr_product">

            <div class="progressbar_wrapper">
                <?= $this->render('_progress', [
                        'categories' => $categories, 
                        'event_id' => $event_details->event_id
                    ]); ?>
            </div>

            <div class="accad_menus">
            <div class="panel-group row" id="accordion">

            <?php

            foreach ($categories as $key => $value1) {

            $items = VendorItem::find()
                ->select(['{{%vendor}}.vendor_name_ar', '{{%vendor}}.vendor_name',
                    '{{%vendor_item}}.item_id','{{%event_item_link}}.link_id','{{%vendor_item}}.item_price_per_unit',
                    '{{%vendor_item}}.item_name', '{{%vendor_item}}.item_name_ar', 
                    '{{%vendor_item}}.slug', '{{%vendor_item}}.item_id'
                ])
                ->innerJoin('{{%event_item_link}}', '{{%event_item_link}}.item_id = {{%vendor_item}}.item_id')
                ->leftJoin('{{%vendor}}', '{{%vendor}}.vendor_id = {{%vendor_item}}.vendor_id')
                ->leftJoin(
                    '{{%vendor_item_to_category}}', 
                    '{{%vendor_item_to_category}}.item_id = {{%vendor_item}}.item_id'
                )
                ->where([
                    '{{%vendor_item}}.item_status' => 'Active',
                    '{{%vendor_item}}.trash' => 'Default',
                    '{{%vendor_item}}.item_for_sale' => 'Yes',
                    '{{%vendor_item_to_category}}.category_id' => $value1['category_id'],
                    '{{%vendor_item}}.type_id' => 2,
                    '{{%event_item_link}}.trash' => 'Default',
                    '{{%event_item_link}}.event_id' => $event_details->event_id
                ])
                ->asArray()
                ->all();
            ?>
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="heading<?= $key ?>">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" id="description_click" data-parent="#accordion" href="#collapse<?= $key ?>" aria-expanded="false" aria-controls="collapse<?= $key ?>" class="collapsed">

                        <?php 

                        if(Yii::$app->language == "en"){
                            echo $value1['category_name'].' - '.'<span data-cateogry-id="'.$value1['category_id'].'" id="item_count">' .count($items). '</span>';
                        } else {
                            echo $value1['category_name_ar'].' - '.'<span id="item_count">' .count($items). '</span>';
                        }
                    
                        if($items) { ?>
                            <span class="glyphicon glyphicon-menu-right text-align pull-right"></span>
                        <?php } ?>
                        
                        </a>
                    </h4>
                </div>
                <div id="collapse<?= $key ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading<?= $key ?>" aria-expanded="false">
                    <div class="panel-body">
                        <div class="events_inner_listing_new">
                            <div class="events_listing">
                                <ul>
                                    <?php
                                    if(!empty($items))
                                    {
                                        foreach ($items as $key => $value) {
        
                                            $image_data = Image::find()
                                                ->where(['item_id' => $value['item_id']])
                                                ->orderBy(['vendorimage_sort_order' => SORT_ASC])
                                                ->one();

                                            if($image_data) {
                                                $image = Yii::getAlias("@s3/vendor_item_images_210/").$image_data->image_path;
                                            } else {
                                                $image = Url::to("@web/images/item-default.png");    
                                            }
                                    ?>
                                    <li class="pull-left">
                                        <div class="events_items">
                                            <div class="events_images">
                                                <div class="hover_events">
                                                    <?php 
                                                    
                                                    $k = array();
                                                    
                                                    foreach($customer_events_list as $l){
                                                        $k[] = $l['item_id'];
                                                    }

                                                    $result = array_search($value['item_id'], $k);

                                                    if (is_numeric($result)) { ?>
                                                        <div class="faver_icons faverited_icons"><a  href="javascript:;" role="button" id="<?php echo $value['item_id']; ?>"  class="add_to_favourite" name="add_to_favourite" title="<?php echo Yii::t('frontend','Add to Things I Like');?>"></a>
                                                        </div> 
                                                    <?php } else { ?>
                                                        <div class="faver_icons">
                                                            <a  href="javascript:;" role="button" id="<?php echo $value['item_id']; ?>"  class="add_to_favourite" name="add_to_favourite" title="<?php echo Yii::t('frontend','Add to Things I Like');?>"></a>
                                                        </div>
                                                    <?php }?>
                                                
                                                </div><!-- END .hover_events -->
                                                
                                                <?= Html::a(Html::img($image, ['class'=>'item-img']), Url::toRoute(['/browse/detail/', 'slug' => $value['slug']])) ?>

                                            </div><!-- END .events_images -->

                                            <div class="events_descrip">
                                                <a href="<?= Url::toRoute(['/browse/detail/', 'slug' => $value['slug']]); ?>">
                                                    <?= LangFormat::format($value['vendor_name'], $value['vendor_name_ar']) ?>
                                                    
                                                    <h3><?= LangFormat::format($value['item_name'], $value['item_name_ar'])?></h3>

                                                    <p><?= CFormatter::format($value['item_price_per_unit']) ?></p>
                                                </a>
                                            </div><!-- END .events_descrip --> 
                                        </div><!-- END .events_items -->
                                    </li>
                                    <?php 
                                        } //foreach items 
                                    } //if items 
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- END .panel -->
            <?php  
            }//for each category 
            ?>
            <!-- heading seven end -->


            <hr />
            
            <h3 class="text-center"><?= Yii::t('frontend','Invitees');?></h3>

            <hr />

            <div class="table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'emptyText' => Yii::t('frontend', 'Ops, nothing to show!'),
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        [
                            'header' => Yii::t('frontend', 'Name'),
                            'attribute' => 'name',
                        ],
                        [
                            'header' => Yii::t('frontend', 'Email'),
                            'attribute' => 'email',
                        ],
                        [
                            'header' => Yii::t('frontend', 'Phone'),
                            'attribute' => 'phone_number',
                        ]
                    ],
                ]); ?>
            </div>

            </div><!-- END #accordion -->
            </div><!-- END .accad_menus -->
            </div><!-- END .right_descr_product -->
        </div><!-- END .events_detials_common -->
    </div><!-- END .container -->
</section>

<?php
$this->registerCss("
    .item-img{width:210px; height:208px;}
    .margin-left-10{margin-left:10px;}
    .msg-success{margin-top: 5px; width: 320px; float: left; text-align: left;}
    .color-red{color:red;}
    table{    font-size: 12px;}
    .header-updated{padding-bottom:0; margin-bottom: 0;}
    .body-updated{background: white; margin-top: 0;}
    #inner_pages_sections .container{background:#fff; margin-top:12px;}
    .border-left{border-left: 1px solid #e2e2e2;}
");
