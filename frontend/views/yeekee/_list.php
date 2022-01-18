<?php
$classActive = 'bg-secondary';
if ($index === 0) {
    $classActive = 'bg-success';
} elseif ($index === 15) {
    $classActive = 'bg-success';
}
$split_postname = str_split($model->post_name);
$username = '';
for ($i =0; $i < count($split_postname); $i++) {
    if($i === 2 || $i === 3 || $i === 4) {
        $username .= '*';
    }else{
        $username .= $split_postname[$i];
    }
}
?>

<div class="d-flex flex-row justify-content-between align-items-stretch mb-1">
    <div class="<?= $classActive ?> text-white text-center rounded py-2 px-1 w-25 d-flex flex-column justify-content-around">
        <span class="badge badge-pill badge-light text-success">อันดับ <?= $index+1 ?></span>
        <span class="mb-0" style="font-size:145%">
            <?= str_pad($model->post_num, 5, '0', STR_PAD_LEFT); ?>
        </span>
    </div>
    <div class="border border-success bg-light_bkk rounded p-2 ml-1 flex-fill d-flex flex-column justify-content-around">
        <div>
            <span class="badge badge-pill badge-light text-success"><i class="fas fa-user-circle"></i>
                ผู้ส่งเลข
            </span>
            <?= $username ?>
        </div>
        <hr class="my-1">
        <div>
            <span class="badge badge-pill badge-light text-success">
                <i class="fas fa-calendar-check"></i>
                เมื่อ
            </span>
            <?= $model->create_at ?>
        </div>
    </div>
</div>