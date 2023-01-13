<?php

namespace app\services;

use app\components\Hydrator;
use app\components\jobs\EmailSendJob;
use app\models\entities\MailEntity;
use app\models\entities\TourEntity;
use app\models\MailMessage;
use app\models\Post;
use app\repositories\CityRepository;
use app\repositories\MailMessageRepository;
use app\repositories\MailRepository;
use app\repositories\PostRepository;
use Yii;

/**
 *
 * Сервис для рассылки горящих предложений
 *
 * Class MailLetterService
 * @package app\services
 */
class MailLetterService
{

    /**
     * @var Hydrator
     */
    protected $hydrator;

    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * @var MailMessageRepository
     */
    private $mailMessageRepository;

    /**
     * @var CityRepository
     */
    private $cityRepository;

    /**
     * @var MailRepository
     */
    private $mailRepository;

    public function __construct(
        Hydrator $hydrator,
        PostRepository $postRepository,
        MailMessageRepository $mailMessageRepository,
        CityRepository $cityRepository,
        MailRepository $mailRepository
    )
    {
        $this->hydrator = $hydrator;
        $this->postRepository = $postRepository;
        $this->mailMessageRepository = $mailMessageRepository;
        $this->cityRepository = $cityRepository;
        $this->mailRepository = $mailRepository;
    }

    /**
     * @param int|null $city_id
     * @throws \ReflectionException
     * @throws \yii\db\Exception
     */
    public function sendMailLetter($city_id): void
    {

        if (is_null($city_id)) {

            // Получаем города по которым можно сделать рассылку
            $tbl_city_ids = $this->cityRepository->getCitiesForMailingLetters();

            foreach ($tbl_city_ids as $id) {
                $this->checkMailingListByCity($id);
            }

        } else {
            $this->checkMailingListByCity($city_id);
        }
    }

    /**
     * @param int $city_id
     * @throws \ReflectionException
     * @throws \yii\db\Exception
     */
    private function checkMailingListByCity(int $city_id): void
    {

        // Получаем горящие предложения по городу
        $posts = $this->postRepository->getPostsByCityId($city_id);

        if ($posts) {

            // Есть ли рассылки за последнюю неделю
            $mailers_exists = $this->mailMessageRepository->getWeeklyMailers($posts);

            if (!$mailers_exists) {

                $desired_post = $this->getAHotOffer($posts);
                if ($desired_post) {

                    // Получаем подписчиков по  этому городу
                    $subscriber_list = $this->mailRepository->getSubscriberListByCityId($city_id);

                    $mailEntity = $this->formMailEntity($desired_post);
                    $mailMessage = $this->createMailMessage($mailEntity, $desired_post, $subscriber_list->count());

                    foreach ($subscriber_list as $row) {
                        $mailEntity->setEmail($row['email']);

                        Yii::$app->queue->push(new EmailSendJob([
                            'entity' => $mailEntity,
                            'subscribe_id' => $row['id'],
                            'message_id' => $mailMessage->id
                        ]));
                    }
                }
            }
        }
    }

    /**
     * @param array $posts
     * @return object|Post|null
     * @throws \ReflectionException
     * @throws \yii\db\Exception
     */
    private function getAHotOffer(array $posts) :?Post
    {

        foreach ($posts as $item) {

            // Есть ли горящее по текущему посту (за последний месяц)
            $mail_message_per_month = $this->mailMessageRepository->getMonthlyMailer($item);
            if ($mail_message_per_month) {
                return $this->hydrator->hydrate(Post::class, $item);
            }
        }

        return null;
    }


    /**
     * @param Post $desired_post
     * @return object|MailEntity
     * @throws \ReflectionException
     * @throws \app\components\exceptions\CurlException
     */
    private function formMailEntity(Post $desired_post): MailEntity
    {

        $tours = $this->getToursByHotOffer($desired_post);

        $params = [
            'email' => '',
            'subject' => Yii::t('app', 'Рассылка писем'),
            'template' => '.\mail\email_send_template.php',
            'data' => [
                'tours' => $tours,
                'hot_offer' => $desired_post
            ]
        ];

        return $this->hydrator->hydrate(MailEntity::class, $params);
    }

    /**
     * @param Post $offer
     * @return array
     * @throws \ReflectionException
     * @throws \app\components\exceptions\CurlException
     */
    private function getToursByHotOffer(Post $offer): array
    {

        /** @var TourEntity $entity */
        $entity = $this->hydrator->hydrate(TourEntity::class, [
            'offerId' => $offer->id,
            'expand' => ['tours']
        ]);

        $tourService = new TourService($entity);
        $response = $tourService->send();

        return $response && isset($response['tours']) ? $response['tours'] : $response;
    }

    /**
     * @param MailEntity $mailEntity
     * @param Post $desired_post
     * @param int $count
     * @return MailMessage
     * @throws \app\components\exceptions\RepositoryException
     */
    private function createMailMessage(MailEntity $mailEntity, Post $desired_post, int $count): MailMessage
    {

        /** @var MailMessage $mailMessage */
        $mailMessage = $this->hydrator->hydrate(MailMessage::class, [
            'title' => Yii::t('app', 'Рассылка писем!'),
            'titleBig' => Yii::t('app', 'Рассылка писем!'),
            'content' => $mailEntity->getContent(),
            'site' => $desired_post->brand_id,
            'post_id' => $desired_post->id,
            'total_count' => $count
        ]);

        $this->mailMessageRepository->save($mailMessage);
        return $mailMessage;
    }

}
