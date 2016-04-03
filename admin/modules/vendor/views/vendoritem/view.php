<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\models\Vendoritem;

/* @var $this yii\web\View */
/* @var $model backend\models\Vendoritem */

$this->title = 'Item details';
$this->params['breadcrumbs'][] = ['label' => 'Vendoritems', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-md-12 col-sm-12 col-xs-12">
<!-- Begin Twitter Tabs-->
<div class="tabbable">
  <ul class="nav nav-tabs">
    <li class="active">
      <a href="#1" data-toggle="tab">Priority Log</a>
    </li>   
    <?php if($model->item_for_sale=='Yes'){ ?> 
    <li>
      <a href="#2" data-toggle="tab">Question Answer Details</a>
    </li>  <?php } ?> 
    <li>
      <a href="#3" data-toggle="tab">Gallery</a>
    </li>
  </ul>
  <div class="tab-content">
  <div class="tab-pane" id="1" >
  <table class="table table-striped table-bordered detail-view">
    <tbody>
        <tr>
            <th>Priority Level</th><th>Start Date</th><th>End Date</th>
            </tr>
            <?php $model->item_id; foreach ($dataProvider1 as $log) {?>
            <tr>
            <td><?php print_r($log['priority_level']);?></td><td><?php $sd=($log['priority_start_date'][0]);$sd=($log['priority_start_date']); echo date("d/m/Y", strtotime($sd)); ?></td><td><?php $sd=($log['priority_end_date']);if($sd=='0000-00-00 00:00:00'){echo 'not set';}else { echo date("d/m/Y", strtotime($sd));} ?></td>
            </tr>
            <?php }?>
    </tbody>
</table>
  </div>

<!--End Second Tab -->
<?php if($model->item_for_sale=='Yes'){ ?> 
<div class="tab-pane" id="2">  
<?php 
$t=0;
foreach($model_question as $question_records)
     {?>
         <div class="form-group superbox" id="delete_<?= $t;?>"> 
     
        <div class="form-group superbox-s" id="delete_<?= $t;?>"> 

        <li class="parent_question"><?= ucfirst($question_records['question_text']); ?><span  class="plus"><a href="#" onclick="questionView('<?= $question_records['question_id']; ?>',this)"></a></span><div class="show_ques<?= $question_records['question_id']; ?>"></div></li>
    
    </div>
    </div>
    <?php $t++;}    ?>      
</div> 
<?php } ?>
<!--End third Tab -->

<div class="tab-pane" id="3" > 
<ul class="row">
            <?php foreach ($imagedata as $image) { ?>
            <li class="col-lg-2 col-md-2 col-sm-3 col-xs-4"> 
                <?= Html::img(Yii::getAlias('@vendor_images/').$image->image_path, ['class'=>'img-responsive','width'=>'125px','height'=>'125px','id'=>$image->image_id,'alt'=>'Gallery','data-img'=>Yii::getAlias('@vendor_images/').$image->image_path]);?> 
            </li>
             <?php } ?>     
</ul>
</div>
</div>
</div>
</div>
 <style>
      ul {         
          padding:0 0 0 0;
          margin:0 0 0 0;
      }
      ul li {     
          list-style:none;
          margin-bottom:25px;           
      }
      ul li img {
          cursor: pointer;
      }
      .modal-body {
          padding:5px !important;
      }
      .modal-content {
          border-radius:0;
      }
      .modal-dialog img {
          text-align:center;
          margin:0 auto;
      }
    .controls{          
        width:50px;
        display:block;
        font-size:11px;
        padding-top:8px;
        font-weight:bold;          
    }
    .next {
        float:right;
        text-align:right;
    }
      /*override modal for demo only*/
      .modal-dialog {
          max-width:500px;
          padding-top: 90px;
      }
      @media screen and (min-width: 768px){
          .modal-dialog {
              width:500px;
              padding-top: 90px;
          }          
      }
      @media screen and (max-width:1500px){
          #ads {
              display:none;
          }
      }
  </style>

 <script>
    var csrfToken = $('meta[name="csrf-token"]').attr("content");
// single question view
function questionView(q_id,tis){
    
    var check = $('.show_ques'+q_id).html();
    if(check==''){
    var path = "<?php echo Url::to(['/vendor/vendoritem/viewrenderquestion']); ?> "; 
    $.ajax({
        type : 'POST',
        url :  path,
        data: { q_id: q_id ,_csrf : csrfToken}, 
        success: function( data ) {        
        $('.show_ques'+q_id).html(data);
        $(tis).toggleClass("expanded");
        return false;       
        }
    })
    }else{
            $('.show_ques'+q_id).toggle();
            $(tis).toggleClass("expanded");
    }

}

    
 /* Begin when loading page first tab opened */
 $(function (){
        $('.nav-tabs li:first').addClass("active");
        $(".tab-content div:first").addClass("active");
    });
        /* End when loading page first tab opened */
 </script>   
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.2/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/bootstrap-modal-box/photo-gallery.js"></script>
    
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" style="z-index: 99999;">
        <div class="modal-content">         
          <div class="modal-body">                
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->