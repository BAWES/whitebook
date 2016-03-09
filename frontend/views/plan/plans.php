 <?php 
use yii\helpers\Url;

 ?>
 <!-- coniner start -->

        <section id="inner_pages_white_back">
		<div class="container paddng0">
		<div class="plan_sect">
		<div class="plan_inner_sec">
		<h2>Plan</h2>
		<h5>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec gravida convallis metus,</h5>
		</div>
		</div>
		<div class="plan_catg">
		<ul>
		<li>
		<a href="<?= Yii::$app->params['BASE_URL'].'/products/venues'; ?>" title="Venues">
		<span class="venue"></span>
		<span class="responsi_common">Venues</span>
		</a>
		</li>
		<li>
		<a href="<?= Yii::$app->params['BASE_URL'].'/products/invitations'; ?>" title="invitations">
		<span class="invitations "></span>
		Invitations 
		</a>
		</li>
			<li>
		<a href="<?= Yii::$app->params['BASE_URL'].'/products/food-beverage'; ?>" title="Food &amp; Beverage">
		<span class="food1"></span>
		Food &amp; Beverage 
		</a>
		</li>
		<li>
		<a href="<?= Yii::$app->params['BASE_URL'].'/products/decor'; ?>" title="Decor">
		<span class="decor1"></span>
		Decor 
		</a>
		</li>
		<li>
		<a href="<?= Yii::$app->params['BASE_URL'].'/products/supplies'; ?>" title="Supplies">
		<span class="supplies1"></span>
		Supplies 
		</a>
		</li>
		<li>
		<a href="<?= Yii::$app->params['BASE_URL'].'/products/entertainment'; ?>" title="Entertainment">
		<span class="entertainment  "></span>
		Entertainment 
		</a>
		</li>
		<li>
		<a href="<?= Yii::$app->params['BASE_URL'].'/products/services'; ?>" title="Services">
		<span class="services  "></span>
		Services 
		</a>
		</li>
		<li>
		<a href="<?= Yii::$app->params['BASE_URL'].'/products/others'; ?>" title="Other">
		<span class="other1"></span>
		Other     
		</a>
		</li>
		<li>
		<a href="<?= Yii::$app->params['BASE_URL'].'/products/say-thank-you'; ?>" title=" Say Thank you ">
		<span class="say1"></span>
		 Say "Thank You"      
		</a>
		</li>
		</ul>
		</div>
		
		<div class="add_banner">
                    <img alt="banner" src="<?php echo Yii::$app->params['BASE_URL'];?>/frontend/web/images/explore_banner.jpg">
                </div>
		
		</div>
		</section>
        <!-- continer end -->

        <?php
        // category display dynamic 
        	/*foreach ($category as $key => $value) { ?>
		<li>		
		<a href="<?= Url::to('products/'.$value['slug']); ?>" title="<?= $value['category_name'] ?>"> 
		<span class="<?= $value['slug'] ?>" style="background: url(../../images/sprit2.png) -3px -17px no-repeat;display: inline-block;
  width: 100%;  height: 50px;"></span>		
		<span class="responsi_common"><?= $value['category_name'] ?></span>    
		</a>
		</li>
		<?php } */?>