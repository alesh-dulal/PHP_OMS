<?php
namespace common\components;

use Yii;
use yii\base\Component;

class MailHelper extends Component{
	public function Test()
	{
		echo  "I got this.";
	}
    public static function sendemail($to,$body,$subject)
    {
        Yii::$app->mailer->compose()
            ->setFrom('info@topnepal.com.np')
            ->setTo($to)
            ->setSubject($subject)
            ->setHtmlBody($body)
            ->send();            
        return 'true';
    }
	
}
?>