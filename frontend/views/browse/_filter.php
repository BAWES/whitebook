<?php 

use yii\helpers\Url;
use common\components\LangFormat;

?>

<div class="filter_content">
    <div class="filter_section">

        <div class=""><!-- responsive-category-top -->
            <div class="filter_title">
                <span class="title_filter color_yellow"><?= Yii::t('frontend', 'Filter by') ?></span>
            </div>

            <div class="listing_sub_cat1">
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

                    <option
                       data-icon=""
                       value="<?= Url::toRoute(['browse/list', 'slug'=> 'all']) ?>"
                       name="category" >
                       <?=Yii::t('frontend','All')?>
                   </option>

                    <?php 

                    foreach ($TopCategories as $category) {

                        if ((isset($Category->category_id))  && $Category->category_id == $category['category_id']) {
                            continue;
                        }
                        $category_name = LangFormat::format($category['category_name'],$category['category_name_ar']);
                        ?>
                        <option
                            data-icon="<?= $category['icon'] ?>"
                            value="<?= Url::toRoute(['browse/list', 'slug'=> $category['slug']]) ?>"
                            name="category">
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
                        <?=$this->render('@frontend/views/common/filter/category.php',['slug' => $slug]); ?>
                    </div>
                </div>
            </nav>
                                            
            <nav class="row-offcanvas row-offcanvas-left">
                <div class="listing_content_cat sidebar-offcanvas" id="sidebar" role="navigation" >
                    <div id="accordion" class="panel-group">
                        
                        <?= $this->render('@frontend/views/common/filter/price.php');  ?>
                        
                        <?= $this->render('@frontend/views/common/filter/theme.php', ['themes' => $themes]); ?>

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

        <button class="btn btn-close-filter visible-sm visible-xs"><?=Yii::t('frontend','Close filter')?></button>
    </div>
</div>