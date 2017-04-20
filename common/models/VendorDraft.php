<?php

namespace common\models;

use Yii;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "whitebook_vendor_draft".
 *
 * @property integer $vendor_draft_id
 * @property string $image_id
 * @property string $vendor_name
 * @property string $vendor_name_ar
 * @property string $vendor_return_policy
 * @property string $vendor_return_policy_ar
 * @property string $vendor_public_email
 * @property string $vendor_contact_name
 * @property string $vendor_contact_email
 * @property string $vendor_contact_number
 * @property string $vendor_contact_address
 * @property string $vendor_contact_address_ar
 * @property string $vendor_emergency_contact_name
 * @property string $vendor_emergency_contact_email
 * @property string $vendor_emergency_contact_number
 * @property string $vendor_fax
 * @property string $vendor_logo_path
 * @property string $short_description
 * @property string $short_description_ar
 * @property string $vendor_website
 * @property string $vendor_facebook
 * @property string $vendor_facebook_text
 * @property string $vendor_twitter
 * @property string $vendor_twitter_text
 * @property string $vendor_instagram
 * @property string $vendor_instagram_text
 * @property string $vendor_youtube
 * @property string $vendor_youtube_text
 * @property integer $created_by
 * @property integer $modified_by
 * @property string $created_datetime
 * @property string $modified_datetime
 * @property string $vendor_bank_name
 * @property string $vendor_bank_branch
 * @property string $vendor_account_no
 * @property string $slug
 *
 * @property VendorDraftCategory[] $vendorDraftCategories
 * @property VendorDraftOrderAlertEmails[] $vendorDraftOrderAlertEmails
 * @property VendorDraftPhoneNo[] $vendorDraftPhoneNos
 */
class VendorDraft extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'whitebook_vendor_draft';
    }

    public function behaviors()
    {
        return [
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'vendor_name',
            ],
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
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vendor_id', 'created_by', 'modified_by'], 'integer'],
            [['vendor_name', 'vendor_contact_number', 'vendor_name_ar', 'vendor_emergency_contact_name', 'vendor_emergency_contact_email', 'vendor_emergency_contact_number', 'vendor_logo_path', 'created_by', 'modified_by', 'created_datetime', 'modified_datetime'], 'required'],
            
            [['vendor_return_policy', 'vendor_return_policy_ar', 'vendor_contact_address', 'vendor_contact_address_ar', 'short_description', 'short_description_ar'], 'string'],
            [['created_datetime', 'modified_datetime', 'is_ready'], 'safe'],
            [['vendor_name', 'vendor_public_email', 'vendor_contact_name', 'vendor_contact_email', 'vendor_emergency_contact_name', 'vendor_emergency_contact_email', 'vendor_website', 'vendor_facebook', 'vendor_twitter', 'vendor_instagram'], 'string', 'max' => 128],
            [['vendor_name_ar', 'slug'], 'string', 'max' => 255],
            [['vendor_emergency_contact_number'], 'string', 'max' => 256],
            [['vendor_fax'], 'string', 'max' => 50],
            [['vendor_logo_path'], 'string', 'max' => 250],
            [['vendor_facebook_text', 'vendor_twitter_text', 'vendor_instagram_text', 'vendor_youtube', 'vendor_youtube_text'], 'string', 'max' => 100],
            [['vendor_bank_name', 'vendor_bank_branch', 'vendor_account_no'], 'string', 'max' => 200],
            [['vendor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Vendor::className(), 'targetAttribute' => ['vendor_id' => 'vendor_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'vendor_draft_id' => 'Vendor Draft ID',
            'image_id' => 'Image ID',
            'vendor_name' => 'Vendor Name',
            'vendor_name_ar' => 'Vendor Name Ar',
            'vendor_return_policy' => 'Vendor Return Policy',
            'vendor_return_policy_ar' => 'Vendor Return Policy Ar',
            'vendor_public_email' => 'Vendor Public Email',
            'vendor_contact_name' => 'Vendor Contact Name',
            'vendor_contact_email' => 'Vendor Contact Email',
            'vendor_contact_number' => 'Vendor Contact Number',
            'vendor_contact_address' => 'Vendor Contact Address',
            'vendor_contact_address_ar' => 'Vendor Contact Address Ar',
            'vendor_emergency_contact_name' => 'Vendor Emergency Contact Name',
            'vendor_emergency_contact_email' => 'Vendor Emergency Contact Email',
            'vendor_emergency_contact_number' => 'Vendor Emergency Contact Number',
            'vendor_fax' => 'Vendor Fax',
            'vendor_logo_path' => 'Vendor Logo Path',
            'short_description' => 'Short Description',
            'short_description_ar' => 'Short Description Ar',
            'vendor_website' => 'Vendor Website',
            'vendor_facebook' => 'Vendor Facebook',
            'vendor_facebook_text' => 'Vendor Facebook Text',
            'vendor_twitter' => 'Vendor Twitter',
            'vendor_twitter_text' => 'Vendor Twitter Text',
            'vendor_instagram' => 'Vendor Instagram',
            'vendor_instagram_text' => 'Vendor Instagram Text',
            'vendor_youtube' => 'Vendor Youtube',
            'vendor_youtube_text' => 'Vendor Youtube Text',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_datetime' => 'Created Datetime',
            'modified_datetime' => 'Modified Datetime',
            'vendor_bank_name' => 'Vendor Bank Name',
            'vendor_bank_branch' => 'Vendor Bank Branch',
            'vendor_account_no' => 'Vendor Account No',
            'slug' => 'Slug',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVendor()
    {
        return $this->hasOne(Vendor::className(), ['vendor_id' => 'vendor_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVendorDraftCategories()
    {
        return $this->hasMany(VendorDraftCategory::className(), ['vendor_draft_id' => 'vendor_draft_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVendorDraftOrderAlertEmails()
    {
        return $this->hasMany(VendorDraftOrderAlertEmails::className(), ['vendor_draft_id' => 'vendor_draft_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVendorDraftPhoneNos()
    {
        return $this->hasMany(VendorDraftPhoneNo::className(), ['vendor_draft_id' => 'vendor_draft_id']);
    }

    public function createDraft($vendor_id)
    {
        //whitebook_vendor_draft
        
        $vendor = Vendor::findOne($vendor_id);

        if(!$vendor)
            return null;

        $vendor_draft = new VendorDraft;
        $vendor_draft->attributes = $vendor->attributes;
        $vendor_draft->is_ready = 0;
        $vendor_draft->save(false);

        //whitebook_vendor_draft_phone_no

        $phone_nos = VendorPhoneNo::findAll(['vendor_id' => $vendor_id]);

        foreach ($phone_nos as $key => $value) {
            $phone_no = new VendorDraftPhoneNo;
            $phone_no->vendor_draft_id = $vendor_draft->vendor_draft_id;
            $phone_no->attributes = $value->attributes;
            $phone_no->save();
        }

        //whitebook_vendor_draft_category

        $categories = VendorCategory::findAll(['vendor_id' => $vendor_id]);

        foreach ($categories as $key => $value) {
            $category = new VendorDraftCategory;
            $category->vendor_draft_id = $vendor_draft->vendor_draft_id;
            $category->attributes = $value->attributes;
            $category->save();
        }

        //whitebook_vendor_draft_order_alert_emails

        $emails = VendorOrderAlertEmails::findAll(['vendor_id' => $vendor_id]);

        foreach ($emails as $key => $value) {
            $email = new VendorDraftOrderAlertEmails;
            $email->attributes = $value->attributes;
            $email->vendor_draft_id = $vendor_draft->vendor_draft_id;            
            $email->save();
        }

        return $vendor_draft;
    }
}
