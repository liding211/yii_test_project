<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\SignupForm;
use app\models\Like;
use yii\web\Response;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;
use Github\Exception\RuntimeException;

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
        if($this->authToGithubApi()){
            try{
                $project_info = Yii::$app->github_client->api('repo')->
                    show($username, $repository);
                $contributors = (array) Yii::$app->github_client->api('repo')->
                    contributors($username, $repository);
            } catch (RuntimeException $e) {
                throw new NotFoundHttpException();
            }
        }        
        foreach($contributors as $key => $contributor){
            $project_contributors[$key] = $contributor;
            $project_contributors[$key]['is_liked'] = Like::isLiked(
                Like::OBJECT_TYPE_GITHUB_USER,
                $contributor['id']
            );
        }
        
        return $this->render('project', array(
            'project' => $project_info, 'contributors' => $project_contributors
        ));
    }

    public function actionUser($username)
    {
        $user = array();
        if($this->authToGithubApi()){
            try{
                $user = Yii::$app->github_client->api('user')->show($username);         
            } catch (RuntimeException $e) {
                throw new NotFoundHttpException();
            }
        }
        return $this->render('user', array('user' => $user));
    }

    public function actionConvert_like()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if(!Yii::$app->user->isGuest && Yii::$app->request->isAjax){
            $post = Yii::$app->request->post();
            $object_type = $post['object_type'];
            $object_id = $post['object_id'];
            if(Like::isLiked($object_type, $object_id)){
                if(Like::deleteLike($object_type, $object_id)){
                    return '[Like]';
                }
            } else {
                if(Like::addLike($object_type, $object_id)){
                    return '[UnLike]';
                }
            }
        }
        return false;
    }
    
    public function actionSearch()
    {
        $repositories = array();
        $post = ['query' => ''];
        if(Yii::$app->request->post()){
            $post = Yii::$app->request->post();
            if(!empty($post['query'])){
                if($this->authToGithubApi()){
                    try{
                        $search_result = Yii::$app->github_client->api('search')->repositories( Html::encode($post['query']) );
                        $repositories = $search_result['items'];
                    } catch (RuntimeException $e){
                        throw new NotFoundHttpException();
                    }
                }
            }
        }
        return $this->render('search', array('repos_list' => $repositories, 'search_query' => $post['query']));
    }
    
    private function authToGithubApi()
    {
        static $is_authenticated = false;
        if(!$is_authenticated){
            try{
                Yii::$app->github_client->authenticate(GITHUB_API_LOGIN, GITHUB_API_PASSWORD);
                $is_authenticated = true;
            } catch (RuntimeException $e){
                $is_authenticated = false;
            }
        }
        return $is_authenticated;
    }
}
