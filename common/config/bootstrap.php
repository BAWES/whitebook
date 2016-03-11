<?php
Yii::setAlias('common', dirname(__DIR__));
Yii::setAlias('frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('console', dirname(dirname(__DIR__)) . '/console');


Yii::setAlias('@frontend_app_images', realpath(dirname(__FILE__).'/frontend/web/images/'));
Yii::setAlias('@vendor_images', realpath(dirname(__FILE__).'/backend/web/uploads/vendor_images/'));
Yii::setAlias('@sales_guide_images', realpath(dirname(__FILE__).'@app/web/uploads/guide_images/'));
Yii::setAlias('@vendor_item_images_210', realpath(dirname(__FILE__).'/backend/web/uploads/vendor_images/'));
Yii::setAlias('@sub_category', realpath(dirname(__FILE__).'@app/web/uploads/subcategory_icon/'));
Yii::setAlias('@vendor_image', realpath(dirname(__FILE__).'/backend/web/uploads/vendor_images/'));
Yii::setAlias('@gif_img', realpath(dirname(__FILE__).'frontend/web/images/ajax-loader.gif'));
Yii::setAlias('@sub_category', realpath(dirname(__FILE__).'/web/uploads/subcategory_icon/'));
Yii::setAlias('@top_category', realpath(dirname(__FILE__).'/web/uploads/category_ads/top/'));
Yii::setAlias('@bottom_category', realpath(dirname(__FILE__).'/web/uploads/category_ads/bottom/'));
Yii::setAlias('@home_ads', realpath(dirname(__FILE__).'/web/uploads/home_ads/')); 
