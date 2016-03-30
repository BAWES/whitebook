<?php
Yii::setAlias('common', dirname(__DIR__));
Yii::setAlias('frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('console', dirname(dirname(__DIR__)) . '/console');

Yii::setAlias('frontend_app_images', '/frontend/web/images'); // frontend
Yii::setAlias('uploads', '/backend/web/uploads/'); // backend

Yii::setAlias('vendor_images', '@uploads/vendor_images/');
Yii::setAlias('sales_guide_images', '@uploads/guide_images/');
Yii::setAlias('vendor_item_images_210', '@uploads/vendor_images/');
Yii::setAlias('sub_category', '@uploads/subcategory_icon/');
Yii::setAlias('gif_img', '@frontend_app_images/ajax-loader.gif');
Yii::setAlias('sub_category', '@uploads/subcategory_icon/');
Yii::setAlias('top_category', '@uploads/category_ads/top/');
Yii::setAlias('bottom_category', '@uploads/category_ads/bottom/');
Yii::setAlias('home_ads', '@uploads/home_ads/');
