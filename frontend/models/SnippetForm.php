<?php
namespace frontend\models;

use common\components\Util;
use common\models\Language;
use common\models\Snippet;
use Yii;


/**
 * LoginForm Model
 *
 * @see http://www.yiiframework.com/doc-2.0/guide-input-validation.html
 */
class SnippetForm extends \yii\base\Model
{
    public $languageId;
    public $name;
    public $description;
    public $code;


    /**
     * Input Rules
     *
     * @return array
     */
    public function rules()
    {
        return [
            // these are required
            [['languageId', 'name', 'description', 'code'], 'required', 'message' => 'Please provide all fields'],

            // trim some fields
            [['name', 'description'], 'trim'],
        ];
    }


    /**
     * Saves the Form
     *
     * @param  \yii\db\ActiveRecord  $member
     * @return bool
     */
    public function save($member)
    {
        // Find language
        $language = Language::find()->where(['id' => $this->languageId])->limit(1)->one();
        if (!$language) {
            $this->addError('languageId', 'Could not find Language');
            return false;
        }

        $slug      = Util::generateUrlSafeSlug($this->name);
        $iteration = 0;

        // Generate a unique slug
        do {
            $exists = Snippet::find()->where(['slug' => $slug])->count();

            if ($exists) {
                $iteration++;
                $slug = Util::generateUrlSafeSlug($this->name . '-' . $iteration);
            }
        } while ($exists);

        // TODO: strip <script tags from description

        $snippet              = new Snippet;
        $snippet->memberId    = $member->id;
        $snippet->languageId  = $language->id;
        $snippet->slug        = $slug;
        $snippet->name        = $this->name;
        $snippet->description = $this->description;
        $snippet->code        = $this->code;

        if ($snippet->save()) {
            return '/snippets/' . $language->slug . '/' . $snippet->slug;
        } else {
            $this->addError('code', 'There was an error saving the Snippet!');
            return false;
        }
    }
}
