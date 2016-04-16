<?php
Yii::setAlias('common', dirname(__DIR__));
Yii::setAlias('frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('admin', dirname(dirname(__DIR__)) . '/admin');
Yii::setAlias('console', dirname(dirname(__DIR__)) . '/console');

Yii::setAlias('themes', '/admin/web/themes/default/'); // backend
Yii::setAlias('frontend_app_images', '/frontend/web/images'); // frontend

//Remove The Following Aliases Later
Yii::setAlias('uploads', '/uploads'); // backend
Yii::setAlias('sub_category', '@uploads/subcategory_icon/');
Yii::setAlias('gif_img', '@frontend_app_images/ajax-loader.gif');
Yii::setAlias('top_category', '@uploads/category_ads/top/');
Yii::setAlias('bottom_category', '@uploads/category_ads/bottom/');
Yii::setAlias('home_ads', '@uploads/home_ads/');

//Amazon S3 Alias
Yii::setAlias('s3','https://thewhitebook.s3.amazonaws.com');
Yii::setAlias('banner_images','@s3/banner_images');
Yii::setAlias('sales_guide_images','@s3/sales_guide_images');
Yii::setAlias('slider_uploads','@s3/slider_uploads');
Yii::setAlias('vendor_images','@s3/vendor_images');
Yii::setAlias('vendor_logo','@s3/vendor_logo');
Yii::setAlias('vendor_item_images_210','@s3/vendor_item_images_210');
Yii::setAlias('vendor_item_images_530','@s3/vendor_item_images_530');
Yii::setAlias('vendor_item_images_1000','@s3/vendor_item_images_1000');
