<?php

namespace common\models;

use Yii;
use yii\base\Model;

class VendorRequest extends Model
{
    public $business;
    public $name;
    public $mobile;
    public $email;
    public $licence;
    public $description;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['business', 'name', 'mobile', 'email', 'licence', 'description'], 'required'],
            [['email'],'email'],
            ['mobile','match', 'pattern' => '/^[0-9+ -]+$/','message' => 'Phone number accept only numbers and +,-']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'business' => 'Business',
            'name' => 'Name',
            'mobile' => 'Mobile',
            'email' => 'Email',
            'licence' => 'licence',
            'description' => 'Description'
        ];
    }

    public function errorDetail() {
        if ($this->errors) {
            foreach ($this->errors as $key => $value) {
                return $this->getFirstError($key);
                exit;
            }
        }
    }
}
