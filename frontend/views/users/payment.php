<?php
use yii\helpers\Url;
use common\models\Vendor;
use common\models\Country;
use common\models\City;
use common\models\Location;
use common\models\Area;
use common\models\AddressType;
use yii\helpers\Html;
use common\models\VendorItemPricing;
use common\models\ItemType;
use common\models\Category;
use yii\widgets\Breadcrumbs;

$this->title='Whitebook - Checkout';
?>

<!-- coniner start -->
<section id="inner_pages_white_back" class="product_details_com">
    <div class="container paddng0">
    <!-- Events slider start -->
    <?php require(__DIR__ . '/../browse/events_slider.php'); ?>
    <!-- Events slider end -->
    <div class="breadcrumb_common">
        <div class="bs-example">
            <h2>Delivery & Payment Details</h2>
        </div>
    </div>
    <!-- Mobile start Here-->

    <div class="product_detail_section responsive-detail-section"><!--product detail start-->
    <div class="col-md-12 padding0">
    <div class="product_detials_common normal_tables">

    <table>
     <tr><td>
      <span>Delivery Details</span>
      </td>
     </tr>
      <tr>
          <td>
          <?php
              //print_r ($customer_details);
            ?>
         </td>
     </tr>
    	<tr><td><?php if(!empty($customer_details['customer_email'])){echo $customer_details['customer_email'];}?></td></tr>
    	<tr><td><?php if(!empty($customer_details['block'])){echo $customer_details['block'];}?></td></tr>
    	<tr><td><?php if(!empty($customer_details['street'])){echo $customer_details['street'];};?></td></tr>
    	<tr><td><?php if(!empty($customer_details['juda'])){echo $customer_details['juda'];}?></td></tr>
    	<tr><td><?php if(!empty($customer_details['customer_address'])){echo $customer_details['customer_address'];}?></td></tr>
    	<tr><td><?php if(!empty($customer_details['area'])){echo City::getCity($customer_details['area']);}?></td></tr>
    	<tr><td><?php if(!empty($customer_details['country'])){echo Country::getCountry($customer_details['country']);}?></td></tr>
    	<tr><td><?php if(!empty($customer_details['phone'])){echo $customer_details['phone'];}?></td></tr>

    	<tr><td><?php if(!empty($customer_details['address_type_id'])){echo AddressType::getAddresstype($customer_details['address_type_id']);}?></td></tr>
    	<tr><td><?php if(!empty($customer_details['address_data'])){echo ($customer_details['address_data']);}?></td></tr>
    	<tr><td><?php if(!empty($customer_details['area_id'])){echo Location::getlocation($customer_details['area_id']);}?></td></tr>
    	<tr><td><?php if(!empty($customer_details['city_id'])){echo City::getCity($customer_details['city_id']);}?></td></tr>
    	<tr><td><?php if(!empty($customer_details['country_id'])){echo Country::getCountry($customer_details['country_id']);}?></td></tr>


    </table>
    <a href="<?= Yii::$app->homeUrl; ?>/cod">Cash on Delivery</a>

    <!-- Mobile end Here-->


    </div>
    <!-- one end -->
    </div>
</section>
<!-- continer end -->
<!-- end -->
