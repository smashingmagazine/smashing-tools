<li data-id="<?php echo $wallpaper['id']; ?>">
    <div class="wallpaper-item-menu">
        <i class="icon-move move-anchor"></i>
    </div>
    
    <h2 data-position="<?php echo ($number + 1); ?>" class="wallpaper-name">
        <?php echo $wallpaper['theme']; ?>
    </h2>
    <h3>Designed by <a href="<?php echo $wallpaper['url']; ?>"><?php echo $wallpaper['designer']; ?></a> from <?php echo $wallpaper['country']; ?>.</h3>
    <div class="wallpaper-infos">
        <?php
            $new_datetime_friendly = new DateTime($wallpaper['friendly_month']);
            $date_directory = $new_datetime_friendly->format('m-Y');
            $theme_directory = $wallpaper['parsed_theme'];
            $image_url = 'wallpapers/' . get_wallpaper_preview_file_name(new DateTime($wallpaper['friendly_month']), $wallpaper['shortcut'], $wallpaper['shortcut'], $wallpaper['type']); 
            $class_id = $wallpaper['shortcut'] .
                        '-' . $date_directory;
            
            $datetime = new DateTime($wallpaper['friendly_month']);
            $full_link = get_wallpaper_full_file_name(new DateTime($wallpaper['friendly_month']), $wallpaper['shortcut'], $wallpaper['shortcut'], $wallpaper['type']);
        ?>
        
        <a href="<?php echo "wallpapers/" . $full_link; ?>">
            <img src="<?php echo $image_url; ?>" class="wallpaper-preview" />
        </a>
        
        <div class="wallpaper-description">
            <textarea class="wallpaper-text">
<?php echo $wallpaper['wordpress_description']; ?>
            </textarea>
        
            <?php $resolutions = explode(',', $wallpaper['resolution']); ?>
        
            <?php if ($wallpaper['calendar'] == 'both' || $wallpaper['calendar'] == 'calendar-only'): ?>        
                <p>
                    <?php $calendar = 'cal' ?>
                    with calendar: 
                    <?php include(THEME_DIRECTORY . '_wallpaper_resolutions.html.php'); ?>
                </p>
            <?php endif; ?>
        
            <?php if ($wallpaper['calendar'] == 'both' || $wallpaper['calendar'] == 'no-calendar-only'): ?>        
                <p>
                    <?php $calendar = 'nocal' ?>
                    without calendar: 
                    <?php include(THEME_DIRECTORY . '_wallpaper_resolutions.html.php'); ?>
                </p>
            <?php endif; ?>
        </div>
    </div>
</li>