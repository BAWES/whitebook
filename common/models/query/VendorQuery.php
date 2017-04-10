<?php

namespace common\models\query;
use Yii;
/**
 * This is the ActiveQuery class for [[Vendor]].
 *
 * @see Booking
 */
class VendorQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Vendor[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Vendor|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function vendorIDs($vendor_ids)
    {
        return $this->andWhere(['IN', '{{%vendor}}.vendor_id', $vendor_ids]);
    }

    public function defaultVendor()
    {
        return $this->andWhere(['{{%vendor}}.trash'=>'Default']);
    }

    public function approved()
    {
        return $this->andWhere(['{{%vendor}}.approve_status'=>'Yes']);
    }

    public function active()
    {
        return $this->andWhere(['{{%vendor}}.vendor_status'=>'Active']);
    }

    public function joinVendorItem(){
        return $this->leftJoin('{{%vendor_item}}', '{{%vendor_item}}.vendor_id = {{%vendor}}.vendor_id');
    }

    public function joinVendorToCategory(){
        return $this->leftJoin('{{%vendor_item_to_category}}', '{{%vendor_item}}.item_id = {{%vendor_item_to_category}}.item_id');
    }

    public function joinCategoryPath(){
        return $this->leftJoin('{{%category_path}}', '{{%category_path}}.category_id = {{%vendor_item_to_category}}.category_id');
    }

    public function categoryPathID($category_id) {
        return $this->andWhere(['{{%category_path}}.path_id' => $category_id]);
    }

    public function vendorName($search){
        return $this->andWhere(['like', 'vendor_name', $search]);
    }

    public function vendorByEmail($email) {
        return $this->andWhere(['vendor_contact_email'=>$email]);
    }

    public function authToken($token) {
        return $this->andWhere(['auth_token'=>$token]);
    }

}