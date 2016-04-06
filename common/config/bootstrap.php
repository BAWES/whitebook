<?php
Yii::setAlias('common', dirname(__DIR__));
Yii::setAlias('frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('admin', dirname(dirname(__DIR__)) . '/admin');
Yii::setAlias('console', dirname(dirname(__DIR__)) . '/console');
Yii::setAlias('themes', dirname(dirname(dirname(dirname(dirname($_SERVER["REQUEST_URI"]))))) . '/admin/web/themes/default/'); // backend

Yii::setAlias('frontend_app_images', '/frontend/web/images'); // frontend