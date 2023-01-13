<?php
?>
<p style="line-height:22px;color:#727c84;padding-left: 20px;padding-right: 20px;">
<p>Уважаемый kлиент!</p>
<p>У нас есть горящее предложение - <?= $hot_offer->title ?></p>

<?php if ($tours) : ?>
    <h4>Туры по предложению:</h4>
    <ul>
        <?php foreach ($tours as $tour) : ?>
            <li><?= $tour->name ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
