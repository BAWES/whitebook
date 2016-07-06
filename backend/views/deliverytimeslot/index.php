<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\Deliverytimeslot;

/* @var $this yii\web\View */
/* @var $searchModel common\models\DeliverytimeslotSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Delivery time slots';
$this->params['breadcrumbs'][] = $this->title;
$timeslot_val = array();
$timesot_id = 2;
?>
<div class="deliverytimeslot-index">
<p>
	<div class="row-fluid">
       <div class="span12">
         <div class="grid simple ">
		<div class="tools">
        <?= Html::a('Create delivery time slot', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="day_head">SUNDAY</div>
    <div class="day_head">MONDAY</div>
    <div class="day_head">TUESDAY</div>
    <div class="day_head">WEDNESDAY</div>
    <div class="day_head">THURSDAY</div>
    <div class="day_head">FRIDAY</div>
    <div class="day_head">SATURDAY</div>

    <div class="delivery_days">
      <div class="sun">
      <ul>
      <?php  $sun = Deliverytimeslot::deliverytimeslot('Sunday');

      foreach ($sun as $key => $value) {
        $timeslot_id = array_push($timeslot_val, $value['timeslot_id']);
        $start = date('g:ia', strtotime($value['timeslot_start_time']));
        $end =  date('g:ia', strtotime($value['timeslot_end_time']));
        $orders =  $value['timeslot_maximum_orders'];
        echo '<div class="one_slot">';
        echo '<a href="'. Url::to(['deliverytimeslot/update', 'id' => $value['timeslot_id']]).'" class="delivery_href">'.'<li>'. $start .' - '. $end .'</li>'.'</a>';
        echo '<a href="'. Url::to(['deliverytimeslot/update', 'id' => $value['timeslot_id']]).'" class="delivery_href ">'.'<li><span class="timeslot_orders">'.$orders.'</span></li>'.'</a><span class="glyphicon glyphicon-trash delivery_delete" onClick="deletetimeslot('.$value['timeslot_id'].')"></span>';
        echo '</div>';
      } ?>
    </ul>

      </div>
      <div class="mon">
        <ul>
      <?php  $mon = Deliverytimeslot::deliverytimeslot('Monday');

      foreach ($mon as $key => $value) {
        $timeslot_id = array_push($timeslot_val, $value['timeslot_id']);
        $start = date('g:ia', strtotime($value['timeslot_start_time']));
        $end =  date('g:ia', strtotime($value['timeslot_end_time']));
        $orders =  $value['timeslot_maximum_orders'];
        echo '<div class="one_slot">';
        echo '<a href="'. Url::to(['deliverytimeslot/update', 'id' => $value['timeslot_id']]).'" class="delivery_href">'.'<li>'. $start .' - '. $end .'</li>'.'</a>';
        echo '<a href="'. Url::to(['deliverytimeslot/update', 'id' => $value['timeslot_id']]).'" class="delivery_href ">'.'<li><span class="timeslot_orders">'.$orders.'</span></li>'.'</a><span class="glyphicon glyphicon-trash delivery_delete" onClick="deletetimeslot('.$value['timeslot_id'].')"></span>';
        echo '</div>';
      } ?>
    </ul>
      </div>
      <div class="tue">
          <ul>
      <?php  $tue = Deliverytimeslot::deliverytimeslot('Tuesday');

      foreach ($tue as $key => $value) {
        $timeslot_id = array_push($timeslot_val, $value['timeslot_id']);
        $start = date('g:ia', strtotime($value['timeslot_start_time']));
        $end =  date('g:ia', strtotime($value['timeslot_end_time']));
        $orders =  $value['timeslot_maximum_orders'];
        echo '<div class="one_slot">';
        echo '<a href="'. Url::to(['deliverytimeslot/update', 'id' => $value['timeslot_id']]).'" class="delivery_href">'.'<li>'. $start .' - '. $end .'</li>'.'</a>';
        echo '<a href="'. Url::to(['deliverytimeslot/update', 'id' => $value['timeslot_id']]).'" class="delivery_href ">'.'<li><span class="timeslot_orders">'.$orders.'</span></li>'.'</a><span class="glyphicon glyphicon-trash delivery_delete" onClick="deletetimeslot('.$value['timeslot_id'].')"></span>';
        echo '</div>';
      } ?>
    </ul>
      </div>
      <div class="wed">
          <ul>
      <?php  $wed = Deliverytimeslot::deliverytimeslot('Wednesday');
      foreach ($wed as $key => $value) {
        $timeslot_id = array_push($timeslot_val, $value['timeslot_id']);
        $start = date('g:ia', strtotime($value['timeslot_start_time']));
        $end =  date('g:ia', strtotime($value['timeslot_end_time']));
        $orders =  $value['timeslot_maximum_orders'];
        echo '<div class="one_slot">';
        echo '<a href="'. Url::to(['deliverytimeslot/update', 'id' => $value['timeslot_id']]).'" class="delivery_href">'.'<li>'. $start .' - '. $end .'</li>'.'</a>';
        echo '<a href="'. Url::to(['deliverytimeslot/update', 'id' => $value['timeslot_id']]).'" class="delivery_href ">'.'<li><span class="timeslot_orders">'.$orders.'</span></li>'.'</a><span class="glyphicon glyphicon-trash delivery_delete" onClick="deletetimeslot('.$value['timeslot_id'].')"></span>';
        echo '</div>';
      } ?>
    </ul>
      </div>
      <div class="thu">
          <ul>
      <?php  $thu = Deliverytimeslot::deliverytimeslot('Thursday');
      foreach ($thu as $key => $value) {
        $timeslot_id = array_push($timeslot_val, $value['timeslot_id']);
        $start = date('g:ia', strtotime($value['timeslot_start_time']));
        $end =  date('g:ia', strtotime($value['timeslot_end_time']));
        $orders =  $value['timeslot_maximum_orders'];
        echo '<div class="one_slot">';
        echo '<a href="'. Url::to(['deliverytimeslot/update', 'id' => $value['timeslot_id']]).'" class="delivery_href">'.'<li>'. $start .' - '. $end .'</li>'.'</a>';
        echo '<a href="'.Url::to(['deliverytimeslot/update', 'id' => $value['timeslot_id']]).'" class="delivery_href ">'.'<li><span class="timeslot_orders">'.$orders.'</span></li>'.'</a><span class="glyphicon glyphicon-trash delivery_delete" onClick="deletetimeslot('.$value['timeslot_id'].')"></span>';
        echo '</div>';
      } ?>
    </ul>
      </div>
      <div class="fri">
          <ul>
      <?php  $fri = Deliverytimeslot::deliverytimeslot('Friday');

      foreach ($fri as $key => $value) {
        $timeslot_id = array_push($timeslot_val, $value['timeslot_id']);
        $start = date('g:ia', strtotime($value['timeslot_start_time']));
        $end =  date('g:ia', strtotime($value['timeslot_end_time']));
          $orders =  $value['timeslot_maximum_orders'];
        echo '<div class="one_slot">';
        echo '<a href="'. Url::to(['deliverytimeslot/update', 'id' => $value['timeslot_id']]).'" class="delivery_href">'.'<li>'. $start .' - '. $end .'</li>'.'</a>';
        echo '<a href="'. Url::to(['deliverytimeslot/update', 'id' => $value['timeslot_id']]).'" class="delivery_href ">'.'<li><span class="timeslot_orders">'.$orders.'</span></li>'.'</a><span class="glyphicon glyphicon-trash delivery_delete" onClick="deletetimeslot('.$value['timeslot_id'].')"></span>';
        echo '</div>';
      } ?>
    </ul>
      </div>
      <div class="sat">
          <ul>
      <?php  $sat = Deliverytimeslot::deliverytimeslot('Saturday');
      foreach ($sat as $key => $value) {
        $timeslot_id = array_push($timeslot_val, $value['timeslot_id']);
        $start = date('g:ia', strtotime($value['timeslot_start_time']));
        $end =  date('g:ia', strtotime($value['timeslot_end_time']));
        $orders =  $value['timeslot_maximum_orders'];
        echo '<div class="one_slot">';
        echo '<a href="'. Url::to(['deliverytimeslot/update', 'id' => $value['timeslot_id']]).'" class="delivery_href">'.'<li>'. $start .' - '. $end .'</li>'.'</a>';
        echo '<a href="'. Url::to(['deliverytimeslot/update', 'id' => $value['timeslot_id']]).'" class="delivery_href ">'.'<li><span class="timeslot_orders">'.$orders.'</span></li>'.'</a><span class="glyphicon glyphicon-trash delivery_delete" onClick="deletetimeslot('.$value['timeslot_id'].')"></span>';
        echo '</div>';
      } ?>
    </ul>
      </div>
    </div>
	 </div>
	 </div>
</div>

</div>

<script>
function deletetimeslot(id)
  {
    var r = confirm("Are you sure want to delete?");
        if (r == true) {
        var csrfToken = $('meta[name="csrf-token"]').attr("content");
        var path = "<?php echo Url::to(['deliverytimeslot/delete']); ?>";      
        $.ajax({
        type: 'POST',
        url: path,
        data: { id: id,_csrf : csrfToken},
        success: function(data) {
         }
        });
     }
 }
</script
