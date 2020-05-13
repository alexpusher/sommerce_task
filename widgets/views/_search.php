<?php

/* @var $this yii\web\View */
/* @var array $searchStatuses */
/* @var array $searchOrderParams */


?>
<ul class="nav nav-tabs p-b">
    <?php foreach($searchStatuses as $id => $status): ?>
        <li class="<?= $status['active'] ? 'active' : '' ?>">
            <a href="/orders<?= $status['param'] ?>"><?= $status['name'] ?></a>
        </li>
    <?php endforeach; ?>
    <li class="pull-right custom-search">
        <form class="form-inline" action="/orders" method="get">
            <div class="input-group">
                <input
                    type="text"
                    name="<?= $searchOrderParams['inputName'] ?>"
                    class="form-control"
                    value=""
                    placeholder="Search orders"
                >
                <?php if($searchOrderParams['statusInput']):?>
                    <input
                        hidden
                        value="<?= $searchOrderParams['statusInput']['value'] ?>"
                        name="<?= $searchOrderParams['statusInput']['name'] ?>"
                    >
                <?php endif;?>
                <span class="input-group-btn search-select-wrap">
                    <select class="form-control search-select" name="<?= $searchOrderParams['selectName'] ?>">
                    <?php foreach($searchOrderParams['selectOptions'] as $optionId => $option): ?>
                        <option value="<?= $optionId ?>" <?= $option['selected'] ? 'selected' : '' ?>>
                            <?= $option['name'] ?>
                        </option>
                    <?php endforeach; ?>
                    </select>
                    <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                </span>
            </div>
        </form>
    </li>
</ul>