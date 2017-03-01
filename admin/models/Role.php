<?php

namespace admin\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\Expression;

/**
* This is the model class for table "{{%role}}".
*
* @property string $role_id
* @property string $role_name
* @property integer $created_by
* @property integer $modified_by
* @property string $created_datetime
* @property string $modified_datetime
* @property string $trash
*/
class Role extends \yii\db\ActiveRecord
{
    /**
    * @inheritdoc
    */
    public static function tableName()
    {
        return '{{%role}}';
    }

    /**
    * @inheritdoc
    */
    public function rules()
    {
        return [
            [['role_name'], 'required'],
            [['created_by', 'modified_by'], 'integer'],
            [['modified_by', 'created_datetime', 'modified_datetime', 'modified_datetime'], 'safe'],
            [['trash'], 'string'],
            [['role_name'], 'string', 'max' => 128]
        ];
    }

    /**
    * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
            'role_id' => 'Role ID',
            'role_name' => 'Role Name',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_datetime' => 'Created Datetime',
            'modified_datetime' => 'Modified Datetime',
            'trash' => 'Trash',
        ];
    }
    
    public static function getRoleName($id)
    {
        $rolename= Role::find()
            ->select ('role_name')
            ->where(['=', 'role_id', $id])
            ->one();

        return ($rolename['role_name']);
    }

    /*
    *
    *   To save created, modified user & date time
    */
    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'modified_by',
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_datetime',
                'updatedAtAttribute' => 'modified_datetime',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @return controller_id to method_id relations
     */
    public function actionList() {
        
        return [
	        'site' => ['index'],
            'address-type' => ['index', 'view', 'create', 'update', 'delete', 'block'],
            'address-question' => ['index', 'view', 'create', 'update', 'delete', 'block', 'sort_addressquestion'],
            'admin' => ['index', 'view', 'create', 'update', 'delete', 'galleryitem'],
            'advert-home' => ['index', 'view', 'create', 'update', 'delete'],
            'category' => ['index', 'manage_subcategory', 'child_category_index', 'view', 'sort_sub_category', 'sort_category', 'create', 'create_subcategory', 'child_category_create', 'update', 'subcategory_update', 'child_category_update', 'delete', 'category_delete', 'subcategory_delete', 'childcategory_delete', 'block', 'subcategory_block', 'loadcategory', 'vendorcategory', 'loadsubcategory', 'move'],
            'city' => ['index', 'view', 'create', 'update', 'delete', 'block'],
            'cms' => ['index', 'view', 'create', 'update', 'delete', 'block'],
            'contacts' => ['index', 'view', 'create', 'update', 'delete', 'block'],
            'country' => ['index', 'view', 'create', 'update', 'delete', 'block'],
            'customer' => ['index', 'view', 'create', 'update', 'delete', 'block', 'questions', 'export', 'newsletter', 'address_delete', 'address'],
            'events' => ['create','update', 'index', 'view', 'delete'],
            'event-type' => ['index', 'view', 'create', 'update', 'delete'],
            'faq' => ['index', 'view', 'create', 'update', 'delete', 'block', 'sort_faq'],
            'faq-group' => ['index', 'view', 'create', 'update', 'delete', 'sort_faq_group'],
            'feature-group' => ['index', 'view', 'create', 'update', 'delete', 'block'],
            'item-type' => ['index', 'view', 'create', 'update', 'delete'],
            'location' => ['index', 'view', 'create', 'update', 'delete', 'city', 'area'],
            'log' => ['view'],
            'order' => ['index', 'view', 'invoice', 'order-status', 'delete'],
            'order-status' => ['index', 'view', 'create', 'update', 'delete'],
            'package' => ['index', 'view', 'create', 'update', 'delete', 'update-item'],
            'payment-gateway' => ['index', 'view', 'create', 'update', 'delete'],
            'priority-item' => ['index', 'view', 'create', 'update', 'delete', 'loadcategory', 'loadsubcategory', 'loadchildcategory', 'loaditems', 'loaddatetime', 'checkprioritydate', 'checkitem', 'status', 'blockpriority'],
            'priority-log' => ['index', 'view', 'create', 'update', 'delete'],
            'report' => ['commission', 'package'],
            'role' => ['index', 'view', 'create', 'update', 'delete'],
            'site-info' => ['index', 'view', 'create', 'update', 'delete'],
            'slide' => ['index', 'view', 'create', 'update', 'delete', 'Sort_slide', 'status', 'block'],
            'social-info' => ['index', 'view', 'create', 'update', 'delete'],
            'themes' => ['index', 'view', 'create', 'update', 'delete', 'block', 'move-items'],
            'vendor' => ['index', 'view', 'vendoritemview', 'password', 'create', 'update', 'delete', 'loadcategory', 'loadsubcategory', 'emailcheck', 'block', 'changepackage', 'changeeditpackage', 'loadpackagedate', 'packageupdate', 'vendornamecheck', 'validate-vendor', 'vendor-logo', 'basic-info', 'main-info', 'additional-info', 'social-info','email-addresses', 'vendor-validate', 'croped-image-upload'],
            'vendor-item' => ['index', 'view', 'create', 'update', 'delete', 'check', 'block', 'approve', 'status', 'removequestion', 'sort_vendor_item', 'addquestion', 'guideimage', 'renderquestion', 'viewrenderquestion', 'renderanswer', 'galleryupload', 'ttemgallery', 'salesguideimage', 'deletesalesimage', 'deleteitemimage', 'deleteserviceguideimage', 'itemnamecheck', 'upload-cropped-image', 'item-info', 'item-description', 'item-price', 'item-approval', 'item-images', 'item-themes-groups', 'item-validate', 'add-theme', 'add-group', 'add-category', 'category-list','menu-items','addon-menu-items'],
            'vendor-item-pending' => ['index'],
            'vendor-draft-item' => ['index', 'approve', 'reject', 'view'],
            'vendor-item-question-answer-option' => ['index', 'view', 'create', 'update', 'delete', 'deletequestionoptions'],
            'vendor-item-question' => ['index', 'view', 'create', 'update', 'delete'],
            'vendor-item-question-guide' => ['index', 'view', 'create', 'update', 'delete'],
            'order-request-status' => ['index', 'view'],
            'payments' => ['index', 'view', 'create', 'update', 'delete'],
        ];        
    }
}
