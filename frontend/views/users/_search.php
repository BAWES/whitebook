<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model  */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="col-md-4 padding-right0 responsive-number">
    <div class="form-group">
        <input type="text" placeholder="Name/Email/Phone" id="inviteesearch" class="form-control">
        <span class="input-group-btn mobile-search-icon">
            <button class="btn btn-default" type="button">Go!</button>
        </span>
    </div>
</div>

<div class="col-md-1 padding0">
    <div class="add_events_new">
        <a class="btn btn-default search-hide" onClick="Searchinvitee('<?php echo $event_details[0]['event_id']; ?>')" type="button" title="Search">search</a> </div>
</div>
<div class="col-md-1 padding0">
    <div class="add_events_new">
        <a class="btn btn-default search-hide" onClick="window.location.reload()" type="button" title="Reset">RESET</a>
    </div>
</div>
