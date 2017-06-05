<?php 

use yii\helpers\Url;
use common\models\CategoryPath;
use common\components\LangFormat;

?>

<div class="panel" id="top_panel_category">
    <div class="panel-heading">
        <p>
            <?= Yii::t('frontend', 'Categories') ?>      
        </p>
    </div>
    <div class="panel-collapse">
        <div class="panel-body filter_content">
            <select class="selectpicker" id="main-category" placeholder="test"> 
                
                <?php

                if(!empty($Category)) {
                    $category_name = LangFormat::format($Category['category_name'],$Category['category_name_ar']);
                    ?>
                    <option
                        data-hidden="true"
                        data-icon="<?= $Category['icon'] ?>"
                        value="<?= $Category['slug'] ?>"
                        name="category"><?= $category_name ?>
                    </option>                        
                <?php
                }//if category selected ?>

                <option
                   data-icon=""
                   value="all"
                   name="category">
                   <?=Yii::t('frontend','All')?>
               </option>

                <?php 

                foreach ($TopCategories as $category) {

                    if ((isset($Category->category_id))  && $Category->category_id == $category['category_id']) {
                        continue;
                    }

                    //check if items available in this category 
                    $have_item = CategoryPath::find()
                        ->leftJoin(
                            '{{%vendor_item_to_category}}',
                            '{{%vendor_item_to_category}}.category_id = {{%category_path}}.category_id'
                        )
                        ->leftJoin(
                            '{{%vendor_item}}',
                            '{{%vendor_item}}.item_id = {{%vendor_item_to_category}}.item_id'
                        )
                        ->where([
                            '{{%vendor_item}}.trash' => 'Default',
                            '{{%vendor_item}}.item_status' => 'Active',
                            '{{%vendor_item}}.item_approved' => 'Yes',
                            '{{%category_path}}.path_id' => $category['category_id']
                        ])
                        ->groupBy('{{%vendor_item}}.item_id')
                        ->one();

                    if(!$have_item)
                    {
                        continue;
                    }
                    
                    $category_name = LangFormat::format($category['category_name'],$category['category_name_ar']);
                    ?>
                    <option
                        data-icon="<?= $category['icon'] ?>"
                        value="<?= $Category['slug'] ?>"
                        name="category">
                        <?= $category_name ?>
                    </option>
                <?php } ?>
            </select>
        </div>
    </div>
</div>
