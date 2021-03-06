<?php
use yii\helpers\Html;
use app\models\Like;

$this->title = 'Main';
?>
<div class="site-index">
    <div class="body-content">
        <div class="row">
            <div class="col-lg-1"></div>
            <div class="col-lg-6">
                <h2><?= $project['full_name']; ?></h2>
                <p>Description: <?= Html::encode($project['description']); ?></p>
                <p>Watchers: <?= $project['watchers_count']; ?></p>
                <p>Forks: <?= $project['forks']; ?></p>
                <p>Open issues: <?= $project['open_issues']; ?></p>
                <p>Home page: <?= Html::a($project['homepage'],$project['homepage']); ?></p>
                <p>GitHub repo: <?= Html::a($project['html_url'],$project['html_url']); ?></p>
                <p>Created: <?= $project['created_at']; ?></p>
            </div>
            <div class="col-lg-4">
                <h2>Contributors</h2>
                <table width="100%">
                    <?php foreach($contributors as $contributor): ?>
                        <tr>
                            <td width="80%">
                                <?= Html::a($contributor['login'],['user', 'username' => $contributor['login']]); ?>
                            </td>
                            <td width="20%">
                                <?php if(!Yii::$app->user->isGuest): ?>
                                    <?= Html::a(
                                        Like::isLiked(Like::OBJECT_TYPE_GITHUB_USER, $contributor['id']) ?
                                            '[UnLike]' : '[Like]',
                                        ['site/convert_like'],
                                        [
                                            'class' => 'like_link',
                                            'id' => Like::OBJECT_TYPE_GITHUB_USER . '_' . $contributor['id'],
                                            'onclick' => 'return convertLike(this);',
                                        ]
                                    ); ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
            <div class="col-lg-1"></div>
        </div>
    </div>
</div>
