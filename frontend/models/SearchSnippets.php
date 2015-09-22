<?php
namespace frontend\models;

use Yii;
use common\components\Util;
use common\models\Snippet;


/**
 * SearchSnippets Model
  */
class SearchSnippets
{
    /**
     * @param  string  $searchString
     * @return array
     */
    public static function get($searchString)
    {
        $request = &Yii::$app->request;
        $offset  = (int) $request->getQueryParam('o', 0);

        $query = Snippet::find()->where(['isHidden' => 0]);
        $query->andFilterWhere(['like', 'name', $searchString]);

        $count = $query->count();

        $snippets = $query
            ->orderBy('createdTime DESC')
            ->offset($offset)
            ->limit(20)
            ->all();

        return ['snippets' => $snippets, 'pagination' => Util::getPagination($offset, $count, 20, 'o')];
    }
}
