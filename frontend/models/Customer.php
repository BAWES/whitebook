<?php

namespace frontend\models;

use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "customer".
 * It extends from \common\models\Customer but with custom functionality for Customer application module
 *
 */
class Customer extends \common\models\Customer {

    /**
     * @inheritdoc
     */
    public function rules() {
        return array_merge(parent::rules(), [
            //[['step', 'majorsSelected', 'languagesSelected'], 'required'],

        ]);
    }

    /**
     * Attribute labels that are inherited are extended here
     */
    public function attributeLabels() {
        return array_merge(parent::attributeLabels(), [
            //'majorsSelected' => Yii::t('app', 'Major(s) Studied'),
        ]);
    }

    /**
     * Scenarios for validation and massive assignment
     */
    public function scenarios() {
        $scenarios = parent::scenarios();

        //$scenarios['changeEmailPreference'] = ['employer_email_preference'];

        return $scenarios;
    }


}
