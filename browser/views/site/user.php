<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

$this->title = 'User';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-lg-2">
        <img src='<?= isset($user['avatar_url']) ? $user['avatar_url'] : ''; ?>' width="100" height="100" bgcolor="#141414" />
        <p>[Like]</p>
    </div>
    <div class="col-lg-6">
        <?php if(isset($user['name'])): ?>
            <h3 style="margin-top: 0px;"><?= $user['name']; ?></h3>
        <?php endif; ?>
        <?php if(isset($user['login'])): ?>
            <p><?= $user['login']; ?></p>
        <?php endif; ?>
        <?php if(isset($user['company'])): ?>
            <p>Company: <?= $user['company']; ?></p>
        <?php endif; ?>
        <?php if(isset($user['blog'])): ?>
            <p>Blog: <?= $user['blog']; ?></p>
        <?php endif; ?>
        <?php if(isset($user['followers'])): ?>
            <p>Folowers: <?= $user['followers']; ?></p>
        <?php endif; ?>
    </div>
</div>
