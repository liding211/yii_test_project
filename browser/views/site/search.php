<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = "Search";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-lg-12">
        <?php foreach($repos_list as $repo): ?>
            <div class="search_result_item">
                <table width="100%">
                    <tr>
                        <td width="34%">
                            <?= Html::a( 
                                $repo['name'], 
                                ['project', 'username' => $repo['owner']['login'], 'repository' => $repo['name']], 
                                ['class' => 'search_result_item_project_name']) 
                            ?>
                        </td>
                        <td width="33%">
                            <?= Html::a(
                                $repo['homepage'], 
                                //creating direction link
                                (!filter_var($repo['homepage'], FILTER_VALIDATE_URL) === false) ?
                                    $repo['homepage'] :
                                    "http://" . $repo['homepage']
                            ); ?>
                        </td>
                        <td width="33%">
                            <?= Html::a( 
                                $repo['owner']['login'], 
                                ['user', 'id' => $repo['owner']['id']] 
                            ); ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <?= Html::encode($repo['description']); ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="34%">
                            Watchers: <?= (int) $repo['watchers_count']; ?>
                        </td>
                        <td width="33%">
                            Forks: <?= (int) $repo['forks']; ?>
                        </td>
                        <td width="33%" align="right">
                            <?php //Yii::$app->user->isGuest ? '':'';?> [Like]
                        </td>
                    </tr>
                </table>
            </div>
        <?php endforeach; ?>
    </div>
</div>
