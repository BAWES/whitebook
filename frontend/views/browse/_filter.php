<?php 

use yii\helpers\Url;
use common\models\CategoryPath;
use common\components\LangFormat;

?>

<div class="filter_content">
    <div class="filter_section">

        <div class=""><!-- responsive-category-top -->
            <div class="filter_title hidden-xs hidden-sm">
                <span class="title_filter color_yellow"><?= Yii::t('frontend', 'Filter by') ?></span>
            </div>

            <div class="listing_sub_cat1 hidden-sm hidden-xs">
                <span class="title_filter"><?= Yii::t('frontend', 'Categories') ?></span>
                <select class="selectpicker" id="main-category" placeholder="test"> 
                    
                    <?php

                    if(!empty($Category)) {
                        $category_name = LangFormat::format($Category['category_name'],$Category['category_name_ar']);
                        ?>
                        <option
                            data-hidden="true"
                            data-icon="<?= $Category['icon'] ?>"
                            value="<?= Url::toRoute(['browse/list', 'slug'=> $Category['slug']]) ?>"
                            name="category"><?= $category_name ?>
                        </option>                        
                    <?php
                    }//if category selected ?>

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
                            data-href="<?= Url::toRoute(['browse/list', 'slug'=> $category['slug']]) ?>"
                            value="<?= $category['slug'] ?>"
                            name="category">
                            <?= $category_name ?>
                        </option>
                    <?php } ?>
                </select>
            </div><!-- END .listing_sub_cat1 -->
        </div><!-- END .responsive-category-top -->

        <div class="responsive-category-bottom">

            <nav class="row-offcanvas row-offcanvas-left hidden-sm hidden-xs">
                <div class="listing_content_cat sidebar-offcanvas" id="sidebar" role="navigation" >
                    <div id="accordion" class="panel-group sub-category-wrapper">
                        <?=$this->render('@frontend/views/common/filter/category.php',['slug' => $slug]); ?>
                    </div>
                </div>
            </nav>
                                            
            <nav class="row-offcanvas row-offcanvas-left">
                <div class="listing_content_cat sidebar-offcanvas" id="sidebar" role="navigation" >
                    <div id="accordion" class="panel-group">
                        
                        <?= $this->render('@frontend/views/common/filter/price.php');  ?>
                        
                        <?php //$this->render('@frontend/views/common/filter/theme.php', ['themes' => $themes]); ?>

                        <?php 

                        if($vendor) 
                        {
                            echo $this->render('@frontend/views/common/filter/vendor.php', [
                                'vendor' => $vendor
                            ]); 
                        } 

                        ?>

                    </div>
                </div>
            </nav>
        </div>

        <br class="visible-sm visible-xs" />

        <div class="mobile_only_filter row"></div>
    </div>
</div>