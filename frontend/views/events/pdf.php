<?php

use yii\web\view;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Breadcrumbs;
use common\models\Category;
use common\models\CategoryNote;
use common\models\VendorItem;
use common\models\VendorPhoneNo;
use common\components\CFormatter;
use common\components\LangFormat;
use frontend\models\EventItemlink;

$this->title = Yii::t('frontend', 'Event Detail'); 

?>
<section>
    <div class="container">
       
        <center>
            <h1>
                <?php echo Yii::t('frontend', 'Event Detail'); ?>
            </h1>

            <h2><?= $event_details->event_name ?></h2>

            <p><?php echo date('d-m-Y',strtotime($event_details->event_date)); ?></p>
            <label><?php echo $event_details->event_type; ?></label>
            <?php if($event_details->no_of_guests) { ?>
                <p><?= Yii::t('frontend', 'Guests : {count}', [
                        'count' => $event_details->no_of_guests
                    ]); ?></p>
            <?php }else{ ?>
            <br />
            <br />
            <?php } ?>
        </center>
        
        <div class="events_pdf col-lg-12">
            
            <div class="right_descr_product">

            <div class="accad_menus">
            <div class="panel-group row" id="accordion">

            <?php

            foreach ($categories as $key => $value1) {

            $items = VendorItem::find()
                ->select(['{{%vendor}}.vendor_id', '{{%vendor}}.vendor_public_email', '{{%vendor}}.vendor_name_ar', '{{%vendor}}.vendor_name',
                    '{{%vendor_item}}.item_id','{{%event_item_link}}.link_id',
                    '{{%image}}.image_path','{{%vendor_item}}.item_price_per_unit',
                    '{{%vendor_item}}.item_name', '{{%vendor_item}}.item_name_ar', 
                    '{{%vendor_item}}.slug', '{{%vendor_item}}.item_id'
                ])
                ->innerJoin('{{%event_item_link}}', '{{%event_item_link}}.item_id = {{%vendor_item}}.item_id')
                ->leftJoin('{{%image}}', '{{%image}}.item_id = {{%vendor_item}}.item_id')
                ->leftJoin('{{%vendor}}', '{{%vendor}}.vendor_id = {{%vendor_item}}.vendor_id')
                ->leftJoin(
                    '{{%vendor_item_to_category}}', 
                    '{{%vendor_item_to_category}}.item_id = {{%vendor_item}}.item_id'
                )
                ->where([
                    '{{%vendor_item}}.item_status' => 'Active',
                    '{{%vendor_item}}.trash' => 'Default',
                    '{{%vendor_item_to_category}}.category_id' => $value1['category_id'],
                    '{{%vendor_item}}.type_id' => 2,
                    '{{%event_item_link}}.trash' => 'Default',
                    '{{%event_item_link}}.event_id' => $event_details->event_id
                ])
                ->andWhere('{{%image}}.image_path != ""')    
                ->asArray()
                ->all();
            ?>
            <div class="panel panel-default">
                <div class="panel-heading" role="tab">
                    <h4 class="panel-title">
                        <?php if(Yii::$app->language == "en"){
                                echo $value1['category_name'].' - '.'<span data-cateogry-id="'.$value1['category_id'].'" id="item_count">' .count($items). '</span>';
                              }else{
                                echo $value1['category_name_ar'].' - '.'<span id="item_count">' .count($items). '</span>';
                              }
                        ?>
                    </h4>
                </div>
                <div class="panel-body">

                    <?php 

                    $note = CategoryNote::getNote($value1['category_id'], $event_details->event_id); 
                    
                    if($note) { ?>
                    <p class="event_note">
                        <?= $note ?>
                    </p>
                    <?php } ?>
                    
                    <?php if($items) { ?>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th><?= Yii::t('frontend', 'Item') ?></th>
                                <th><?= Yii::t('frontend', 'Price') ?></th>
                                <th><?= Yii::t('frontend', 'Vendor') ?></th>
                                <th><?= Yii::t('frontend', 'Contact no') ?></th>
                                <th><?= Yii::t('frontend', 'Email') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $key => $value) { ?>
                            <tr>
                                <td>
                                    <a href="<?= Url::toRoute(['/browse/detail/', 'slug' => $value['slug']]); ?>">
                                        <?= LangFormat::format($value['item_name'], $value['item_name_ar'])?>
                                    </a>
                                </td>
                                <td>
                                    <?= CFormatter::format($value['item_price_per_unit']) ?>
                                </td>
                                <td>
                                    <?= LangFormat::format($value['vendor_name'], $value['vendor_name_ar']) ?>
                                </td>
                                <td>
                                    <?php 

                                    $phone_icons = [
                                        'Whatsapp' => 'fa fa-whatsapp',
                                        'Mobile' => 'fa fa-mobile',
                                        'Fax' => 'fa fa-fax',
                                        'Office' => 'fa fa-building'
                                    ];

                                    $phone_nos = VendorPhoneNo::find()
                                            ->where(['vendor_id' => $value['vendor_id']])
                                            ->all();

                                    foreach ($phone_nos as $key => $phone_no_detail) { ?>
                                        <a class="color-808080" href="tel:<?= $phone_no_detail->phone_no; ?>"><i class="<?= $phone_icons[$phone_no_detail->type] ?>"></i>&nbsp;<?= $phone_no_detail->phone_no; ?></a>&nbsp;&nbsp;
                                    <?php } ?>
                                </td>
                                <td>
                                    <?= $value['vendor_public_email'] ?>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <?php } ?>
                </div><!-- END .panel-body -->
            </div><!-- END .panel -->
            
            <br />

            <?php  
            }//for each category 
            ?>
            <!-- heading seven end -->

            <?php if($invitees) { ?>
            <hr />
            
            <h3 class="text-center"><?= Yii::t('frontend','Invitees');?></h3>

            <hr />

            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th><?= Yii::t('frontend', 'Name') ?></th>
                            <th><?= Yii::t('frontend', 'Email') ?></th>
                            <th><?= Yii::t('frontend', 'Phone') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($invitees as $key => $value) { ?>
                        <tr>
                            <td><?= $value->name ?></td>
                            <td><?= $value->email ?></td>
                            <td><?= $value->phone_number ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <?php } ?>

            </div><!-- END #accordion -->
            </div><!-- END .accad_menus -->
            </div><!-- END .right_descr_product -->
        </div><!-- END .events_detials_common -->
    </div><!-- END .container -->
</section>
