<?php

namespace common\models\query;

/**
 * This is the ActiveQuery class for [[CustomerCartItemQuestionAnswer]].
 *
 * @see CustomerCartItemQuestionAnswer
 */
class CustomerCartItemQuestionAnswerQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return CustomerCartItemQuestionAnswer[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CustomerCartItemQuestionAnswer|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
