<?php
$searchResultArr = '';
if(Input::get('search')) {
    $information = DB::getInstance()->queryAndSet('SELECT `boeger`.`bog_id`,
`boeger`.`bog_titel`,
`boeger`.`bog_pris`,
`forfattere`.`forfatter_navn`,
`genrer`.`genre_titel`
FROM `boeger`
INNER JOIN `forfattere` ON `boeger`.`fk_forfatter_id` = `forfattere`.`forfatter_id`
INNER JOIN `genrer` ON `boeger`.`fk_genre_id` = `genrer`.`genre_id`');

    $searchResultArr = AdvancedSearch::lookFor(Input::get('search'), $information->results(), 'bog_id');

}
?>
<form method="get" class="columns twelve">
    <input type="hidden" name="page" value="p_Search">
    <input name="search" type="search" value="<?php echo Input::get('search') ?>">
</form>
<?php

echo '<pre class="columns twelve">';
        print_r($searchResultArr);
        //print_r(Input::get('search'));
echo '</pre>';