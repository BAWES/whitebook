<?php
/* @var $this yii\web\View */
use yii\bootstrap\Alert;
use yii\web\Session;
use backend\models\Vendor; 
$this->title = 'Whitebook Application';
 
?>
			   <!-- BEGIN DASHBOARD TILES -->
	  <div class="row">	 
			<?php
			
			?>
			<p style="font-weight: bold;font-size: 22px; margin-left: 15px;">Your package will expire on <?php echo $packageenddate; ?></p>
		<div class="col-md-4 col-vlg-3 col-sm-6">
			<div class="tiles green m-b-10">
              <div class="tiles-body">
			  
			  <div class="controller"> <a href="javascript:;" class="reload"></a>  </div>
                <div class="tiles-title text-black">OVERALL PRODUCT </div>
			         <div class="widget-stats">
                      <div class="wrapper transparent">
						<span class="item-title">Overall items</span> <span class="item-count animate-number semi-bold" data-value="<?php echo $vendoritemcnt;?>" data-animation-duration="700">0</span>
					  </div>
                    </div>
                    <div class="widget-stats">
                      <div class="wrapper transparent">
						<span class="item-title">Month item</span> <span class="item-count animate-number semi-bold" data-value="<?php echo $monthitemcnt;?>" data-animation-duration="700">0</span> 
					  </div>
                    </div>
                    <div class="widget-stats">
                      <div class="wrapper transparent">
						<span class="item-title">Today item</span> <span class="item-count animate-number semi-bold" data-value="<?php echo $dateitemcnt;?>" data-animation-duration="700">0</span> 
					  </div>
                    </div>
                    <div class="progress transparent progress-small no-radius m-t-20" style="width:90%">
                      <div class="progress-bar progress-bar-white animate-progress-bar" data-percentage="64.8%" ></div>
                    </div>
			  </div>			
			  
			</div>	
			
		</div>
	 </div>
	  <!-- END DASHBOARD TILES -->


