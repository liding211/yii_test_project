<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\SignupForm;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'login', 'signup'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['login', 'signup'],
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->actionProject(
            MAIN_PAGE_PROJECT_USERNAME, 
            MAIN_PAGE_PROJECT_REPOSITORY
        );
    }

    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if($user = $model->signup()){
                if(Yii::$app->getUser()->login($user)){
                    $this->goHome();
                }
            }
        } 
        
        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionProject($username, $repository)
    {
        $project_info = $project_contributors = array();
        Yii::$app->github_client->authenticate(GITHUB_API_LOGIN, GITHUB_API_PASSWORD);
        $project_info = Yii::$app->github_client->api('repo')->
            show($username, $repository);
        $project_contributors = Yii::$app->github_client->api('repo')->
            contributors($username, $repository);
        return $this->render('project', array(
            'project' => $project_info, 'contributors' => $project_contributors
        ));
    }

    public function actionUser($username)
    {
        $user = array();
        Yii::$app->github_client->authenticate(GITHUB_API_LOGIN, GITHUB_API_PASSWORD);
        $user = Yii::$app->github_client->api('user')->show($username);
        return $this->render('user', array('user' => $user));
    }
    
    public function actionSearch()
    {
        $repositories = array();
        if(Yii::$app->request->post()){
            $post = Yii::$app->request->post();
            if(!empty($post['query'])){
                Yii::$app->github_client->authenticate(GITHUB_API_LOGIN, GITHUB_API_PASSWORD);
                $search_result = Yii::$app->github_client->api('search')->repositories( $post['query'] );
                $repositories = $search_result['items'];
            }
        }
        return $this->render('search', array('repos_list' => $repositories, 'search_query' => $post['query']));
    }
}
