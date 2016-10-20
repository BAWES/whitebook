<?php

namespace admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PriorityitemSearch represents the model behind the search form about `common\models\Priorityitem`.
 */
class PriorityitemSearch extends Priorityitem
{
    /**
     * @inheritdoc
     */
    public $item_name;

    public function rules()
    {
        return [
            [['item_name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params,$start=false,$end=false, $status=false,$level=false)
    {  

        if($start =="" && $end !="")
        {
		 $startdate= Priorityitem::find()
		 ->select('priority_start_date')
         ->where(['trash'=>'Default'])
         ->orderby(['priority_start_date'=>SORT_ASC])
         ->asArray()
         ->all();
         $start = date ("Y-m-d", strtotime($startdate[0]['priority_start_date']));
            //$start = '01-01-2010';
        }
        elseif($start !="" && $end =="")
        {         
		 $enddate= Priorityitem::find()
		 ->select('priority_end_date')
         ->where(['trash'=>'Default'])
         ->orderby(['priority_end_date'=>SORT_DESC])
         ->asArray()
         ->all();
         
        $end = date ("Y-m-d", strtotime($enddate[0]['priority_end_date']));
        }    
        if($start !="" && $end !="" && $status !="" && $level !="")
        {
  		    $all_priority= Priorityitem::find()
  		     ->select(['priority_end_date','priority_start_date','priority_id'])
           ->where(['trash'=>'Default'])
           ->orderby(['priority_end_date'=>SORT_DESC])
           ->asArray()
           ->all();
         
        /* BEGIN GET BETWEEN DATES FROM START DATE AND END DATE */     
            if(strtotime($start) <= strtotime($end)) {
            while (strtotime($start) <= strtotime($end)) {
            $selected_dates[]=$start;
            $start = date ("Y-m-d", strtotime("+1 day", strtotime($start)));
            }
            }
       /* END GET BETWEEN DATES FROM START DATE AND END DATE */   
        $avlble_id = array();         
        foreach($all_priority as $priority)
        {
            $single_item = $priority['priority_id'];
            
            /* check if date available in between dates */            
            foreach($selected_dates as $sel_date)
            {
                $paymentDate=date('Y-m-d', strtotime($sel_date));
              
                $contractDateBegin =  $priority['priority_start_date'];
                $contractDateEnd =  $priority['priority_end_date'];

                if (($paymentDate >= $contractDateBegin) && ($paymentDate <= $contractDateEnd))
                {
                  array_push($avlble_id, $single_item);
                }                 
            }           
        }
            $item_val = array_unique($avlble_id);
            if($status =='All' && $level=='All')
            {
             $query = Priorityitem::find()
                ->where(['priority_id'=>$item_val])                                      
                ->orderBy('priority_id');
            }
            else if($status !='All' && $level=='All')
            {
             $query = Priorityitem::find()
                ->where(['priority_id'=>$item_val])   
                ->andwhere(['status'=>$status]) 
                ->orderBy('priority_id');
            }
            else if($status =='All' && $level!='All')
            {
             $query = Priorityitem::find()
                ->where(['priority_id'=>$item_val])                                      
                ->andwhere(['priority_level'=>$level])   
                ->orderBy('priority_id');
            }
            else
            {
            $query = Priorityitem::find()
                ->where(['priority_id'=>$item_val])                      
                ->andwhere(['=', 'status', $status])
                ->andwhere(['=', 'priority_level', $level])
                ->orderBy('priority_id');
            }
        }
        else if($start =="" && $end =="" && $status !="" && $level !="")
        {            
            if($status =='All'&& $level=='All')
            {
             $query = Priorityitem::find()
                ->where(['!=', '{{%priority_item}}.trash', 'Deleted'])
                ->orderBy('priority_id');
            }
            else if($status !='All' && $level=='All')
            {
                $query = Priorityitem::find()
                ->where(['!=', '{{%priority_item}}.trash', 'Deleted'])  
                ->andwhere(['status'=>$status])                                  
                ->orderBy('priority_id');
            }
            else if($status =='All' && $level!='All')
            {
             $query = Priorityitem::find()
             ->where(['!=', '{{%priority_item}}.trash', 'Deleted'])  
                ->andwhere(['priority_level'=>$level])                                      
                ->orderBy('priority_id');
            }else
            {
            $query = Priorityitem::find()                                     
                ->where(['=', 'status', $status])
                ->andwhere(['=', 'priority_level', $level])
                ->andwhere(['trash'=>'Default'])
                ->orderBy('priority_id');
            }

        } 
        else
        {            
            $query = Priorityitem::find()
                ->where(['!=', '{{%priority_item}}.trash', 'Deleted'])
                ->orderBy('priority_id');
        }   

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort'=> ['defaultOrder' => ['priority_id'=>SORT_DESC]]
        ]);

        $query->joinWith(['vendoritem']); 
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'priority_id' => $this->priority_id,
            'priority_start_date' => $this->priority_start_date,
            'priority_end_date' => $this->priority_end_date,
            'created_by' => $this->created_by,
            'modified_by' => $this->modified_by,
            'created_datetime' => $this->created_datetime,
            'modified_datetime' => $this->modified_datetime,
        ]);

        $query->andFilterWhere(['like', 'priority_level', $this->priority_level])
         ->andFilterWhere(['like', '{{%vendor_item}}.item_name',$this->item_name]);
            

        return $dataProvider;
    }
    
}
