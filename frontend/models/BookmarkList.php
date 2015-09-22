<?php
namespace frontend\models;

use Yii;
use common\models\Bookmark;
use common\components\Util;


/**
 * BookmarkList Model
 */
class BookmarkList
{
    /**
     * @param  \yii\db\ActiveRecord  $member
     * @return array
     */
    public static function get($member)
    {
        $request = &Yii::$app->request;
        $offset  = (int) $request->getQueryParam('o', 0);

        $query   = Bookmark::find()->where(['memberId' => $member->id]);
        $count   = $query->count();

        $bookmarks = $query
            ->orderBy('createdTime DESC')
            ->offset($offset)
            ->limit(20)
            ->all();

        return ['bookmarks' => $bookmarks, 'pagination' => Util::getPagination($offset, $count, 20, 'o')];
    }
}
