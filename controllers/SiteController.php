<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\components\AuthHandler;
use app\models\LoginForm;
use app\components\Github;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'as access' => [
                'class' => '\app\components\AccessBehavior',
            ]
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }


    public function onAuthSuccess($client)
    {
        (new AuthHandler($client))->handle();
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $github = new Github();

        if (isset($_GET['closed'])) {
            $type = 'closed';
        } else {
            $type = 'open';
        }

        $issues = $github->getIssues($type);

        return $this->render('index', compact('issues'));

    }

    public function actionComments()
    {
        if (isset($_GET['id'])) {


            $github = new Github();

            $comments = $github->getComments($_GET['id']);

            return $this->render('comments', compact('comments'));
        } else {
            return $this->redirect('index');
        }
    }


    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        $this->layout = 'login';
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password_hash = '';
        return $this->render('login',
            [
                'model' => $model,
            ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }


}
