<?php
namespace frontend\models;

use Yii;
use common\components\Util;
use common\models\Snippet;


/**
 * MemberSnippetsList Model
 */
class MemberSnippetsList
{
    /**
     * @param  \yii\db\ActiveRecord  $member
     * @return array
     */
    public static function get($member)
    {
        $request = &Yii::$app->request;
        $offset  = (int) $request->getQueryParam('o', 0);

        $query = Snippet::find()->where([
            'isHidden' => 0,
            'memberId' => $member->id,
        ]);

        $count = $query->count();

        $snippets = $query
            ->orderBy('createdTime DESC')
            ->offset($offset)
            ->limit(10)
            ->all();

        return ['snippets' => $snippets, 'pagination' => Util::getPagination($offset, $count, 20, 'o')];
    }
}
