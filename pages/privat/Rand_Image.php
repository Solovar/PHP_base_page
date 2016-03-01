<?php
$img = HumanConfirm::getInstance()->make();
echo '<div class="columns twelve">';
    echo '<img alt="Embedded Image" src="' . 'data:image/png;base64,' . $img[0] . '">';
echo '</div>';