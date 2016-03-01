<div class="columns twelve">
    <?php
    if(Session::exsists('success'))
    {
        echo '<p class="success">' . Session::flash('success') . '<p>';
    }
    if(Session::exsists('error'))
    {
        echo '<p class="error">' . Session::flash('error') . '<p>';
    }
    ?>
</div>