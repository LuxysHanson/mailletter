<?php
namespace models\entities;

use app\models\entities\MailEntity;

class MailEntityTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var MailEntity
     */
    private $entity;

    /*public function testMailCorrectData()
    {

        $this->entity = new MailEntity();
        $this->entity->setEmail('admin@example.com');
        $this->entity->setSubject('Заголовок письма');
        $this->entity->setTemplate('@app/mail/email_send_template.php');
        $this->entity->setData([]);

        verify($this->entity->getEmail())->notNull();
        verify($this->entity->getSubject())->notNull();
        verify($this->entity->getSenderEmail())->equals(\Yii::$app->params['senderEmail']);

        $this->entity->setSenderEmail('example@bk.ru');
        verify($this->entity->getSenderEmail())->notNull();
//        verify($this->entity->getContent())->isString();
    }*/

}
