<?php
namespace frontend\models;

use Yii;
use common\components\Util;
use common\models\Snippet;
use common\models\Language;

/**
 * PopularSnippets Model
 * Will fetch from cache if it's there, to prevent people smashing the site
 */
class PopularSnippets
{

    /**
     * @param  \yii\db\ActiveRecord  $language
     * @return array
     */
    public static function getTopTen($language = null)
    {
        $cache     = &Yii::$app->cache;
        $cacheKey  = Yii::$app->params['cacheKeys']['popularSnippets'] . ($language ? '_' . $language->id : '');
        $snippets  = $cache->get($cacheKey);

        if (!$snippets) {
            $snippets = [];

            $where = ['isHidden' => 0];
            if ($language) {
                $where['languageId'] = $language->id;
            }

            $popularSnippets = Snippet::find()
                ->where($where)
                ->orderBy('views DESC')
                ->limit(10)
                ->all();

            foreach ($popularSnippets as $snippet) {
                $snippets[$snippet->id]             = $snippet->attributes;
                if (!$language) {
                    $snippets[$snippet->id]['language'] = $snippet->language->attributes;
                }
            }

            $cache->set($cacheKey, $snippets, 30);
        }

        return $snippets;
    }


    /**
     * @param  \yii\db\ActiveRecord  $language
     * @return array
     */
    public static function get($language = null)
    {
        $request = &Yii::$app->request;
        $offset  = (int) $request->getQueryParam('o', 0);

        $where = ['isHidden' => 0];
        if ($language) {
            $where['languageId'] = $language->id;
        }

        $query = Snippet::find()->where($where);
        $count = $query->count();

        $snippets = $query
            ->orderBy('views DESC')
            ->offset($offset)
            ->limit(20)
            ->all();

        return ['snippets' => $snippets, 'pagination' => Util::getPagination($offset, $count, 20, 'o')];
    }
}
