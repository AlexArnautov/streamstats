<?php

declare(strict_types=1);

namespace app\components\auth;

use app\models\User;
use Exception;
use Yii;
use yii\authclient\ClientInterface;
use yii\helpers\ArrayHelper;

/**
 * AuthHandler handles successful authentication via Yii auth component
 */
class AuthHandler
{
    private string $email;
    private string $twitchId;
    private string $username;

    /**
     * @throws Exception
     */
    public function __construct(private readonly ClientInterface $client)
    {
        $attributes = $this->client->getUserAttributes();
        $this->email = ArrayHelper::getValue($attributes, 'email');
        $this->twitchId = ArrayHelper::getValue($attributes, 'id');
        $this->username = ArrayHelper::getValue($attributes, 'login');
    }

    /**
     * @throws Exception
     */
    public function handle(): void
    {
        /* @var User $user */
        $user = User::find()->where([
            'twitch_id' => $this->twitchId,
        ])->one();

        if (Yii::$app->user->isGuest) {
            if ($user !== null) { //login
                $this->updateUser($user);
            } else { //signup
                $user = $this->createUser();
            }
            Yii::$app->user->login($user, Yii::$app->params['user.rememberMeDuration']);
        } else { //user logged in
            Yii::$app->getSession()->setFlash('error', [
                Yii::t(
                    'app',
                    'Unable to link {client} account. There is another user using it.',
                    ['client' => $this->client->getTitle()]
                ),
            ]);
        }
    }

    /**
     * @return User
     * @throws \yii\db\Exception
     * @throws \yii\base\Exception
     */
    private function createUser(): User
    {
        $user = new User();
        $user->username = $this->username;
        $user->twitch_id = $this->twitchId;
        $user->email = $this->email;
        $user->generateAuthKey();
        $transaction = User::getDb()->beginTransaction();

        if ($user->save()) {
            $transaction->commit();
        } else {
            Yii::$app->getSession()->setFlash('error', [
                Yii::t('app', 'Unable to save user: {errors}', [
                    'client' => $this->client->getTitle(),
                    'errors' => json_encode($user->getErrors()),
                ]),
            ]);
        }

        return $user;
    }

    /**
     * @param User $user
     * @throws Exception
     */
    private function updateUser(User $user): void
    {
        $user->email = $this->email;
        $user->username = $this->username;
        $user->save();
    }
}