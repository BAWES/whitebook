<?php
Yii::setAlias('uploads', '/backend/web/uploads'); // backend
Yii::setAlias('directory', dirname(dirname(dirname(dirname(dirname($_SERVER["REQUEST_URI"]))))).Yii::getAlias('@uploads')); 

Yii::setAlias('vendor_images', '@directory/vendor_images');
Yii::setAlias('sales_guide_images', '@directory/guide_images');
Yii::setAlias('vendor_item_images_210', '@directory/vendor_images'); // product images
Yii::setAlias('sub_category', '@uploads/subcategory_icon');
Yii::setAlias('gif_img', '@frontend_app_images/ajax-loader.gif');
Yii::setAlias('sub_category', '@directory/subcategory_icon');
Yii::setAlias('top_category', '@directory/category_ads/top');
Yii::setAlias('bottom_category', '@directory/category_ads/bottom');
Yii::setAlias('home_ads', '@directory/home_ads');
