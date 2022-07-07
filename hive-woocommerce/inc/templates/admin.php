<div class="woohive-admin-wrapper">

    <div class="woohive-form">
        <?php settings_errors(); ?>
        <form method="post" action="options.php">
            <?php 
                settings_fields('hive_woo-settings-group');
                do_settings_sections('devshagor_hive_woo');
                submit_button()
            ?>
        </form>
    </div>
</div>


