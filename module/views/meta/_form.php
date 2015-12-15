<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="seo-meta-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'name')->dropDownList($model->nameList(), ['prompt' => 'Выбрать тип']) ?>
        </div>
        <div class="col-md-9">
            <?= $form->field($model, 'content')->textarea(['row' => 1]) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Редактировать', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>