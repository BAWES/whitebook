<?php
Yii::setAlias('common', dirname(__DIR__));
Yii::setAlias('frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('admin', dirname(dirname(__DIR__)) . '/admin');
Yii::setAlias('console', dirname(dirname(__DIR__)) . '/console');
Yii::setAlias('themes', dirname(dirname(dirname(dirname(dirname($_SERVER["REQUEST_URI"]))))) . '/admin/web/themes/default/'); // backend

Yii::setAlias('frontend_app_images', '/frontend/web/images'); // frontend

//Amazon S3 Alias
Yii::setAlias('s3','https://whitebook-files.s3.amazonaws.com');
Yii::setAlias('salesguides','@s3/sales_guide_images');
Yii::setAlias('vendorimages','@s3/vendor_images');
Yii::setAlias('vendoritem','@s3/vendor_item_images_210');
