<?php
namespace frontend\models;

use common\models\SnippetComment;
use Yii;


/**
 * CommentForm Model
 */
class CommentForm extends \yii\base\Model
{

    public $comment;


    /**
     * Input Rules
     *
     * @return array
     */
    public function rules()
    {
        return [
            // the email and password attributes are required
            [['comment'], 'required', 'message' => 'Please provide all fields'],

            // trim the comment
            [['comment'], 'trim'],
        ];
    }


    /**
     * Attempts to find the member and send them an email
     *
     * @param  \yii\db\ActiveRecord  $member
     * @param  \yii\db\ActiveRecord  $snippet
     * @return bool
     */
    public function save($member, $snippet)
    {
        if ($this->comment && strlen($this->comment) > 3) {
            $cmt            = new SnippetComment;
            $cmt->memberId  = $member->id;
            $cmt->snippetId = $snippet->id;
            $cmt->comment   = $this->comment;

            return $cmt->save();
        }
        return false;
    }
}
