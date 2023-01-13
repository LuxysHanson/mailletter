<?php

namespace app\repositories;

use app\components\enums\MailMessageStateEnum;
use app\models\MailMessage;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Class MailMessageRepository
 * @package app\repositories
 */
class MailMessageRepository extends BaseRepository
{

    public function getModel(): ActiveRecord
    {
        return new MailMessage;
    }

    /**
     * @param array $posts
     * @return bool
     */
    public function getWeeklyMailers(array $posts): bool
    {
        return $this->find()
            ->andWhere(['>', 'DATE(addDate)', date('Y-m-d', strtotime('-7 days'))])
            ->andWhere(['mailType' => 0])
            ->andWhere(['state' => MailMessageStateEnum::STATE_FINISH])
            ->andWhere(['in', 'post_id', ArrayHelper::getColumn($posts, 'id')])
            ->exists();
    }

    /**
     * @param array $post
     * @return array|false|\yii\db\DataReader
     * @throws \yii\db\Exception
     */
    public function getMonthlyMailer(array $post)
    {

        $command = $this->find()
            ->andWhere(['>', 'DATE(addDate)', date('Y-m-d', strtotime('-1 month'))])
            ->andWhere(['mailType' => 0])
            ->andWhere(['state' => MailMessageStateEnum::STATE_FINISH])
            ->andWhere(['id' => $post['id']])
            ->orderBy('addDate DESC')
            ->createCommand();

        return $command->queryOne();
    }

    public function mailingDataChanges(MailMessage $message, bool $send): void
    {

        if ($message->send_count == 0 || $message->error_count == 0) {
            $message->startDate = date('Y-m-d H:i:s');
            $message->state = MailMessageStateEnum::STATE_IN_PROCESS;
        }

        if ($message->error_count + $message->send_count == $message->total_count) {
            $message->endDate = date('Y-m-d H:i:s');
            $message->state = MailMessageStateEnum::STATE_FINISH;
        }

        if ($send) {
            $message->send_count += 1;
        } else {
            $message->error_count += 1;
        }

    }

}
