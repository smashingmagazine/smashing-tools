<?php

    // Getting the project-wide config constants
    require_once('config.php');
	
    // Getting the utility functions
    require_once(INCLUDE_DIRECTORY . 'functions.php');

    // Database handler
    require_once(INCLUDE_DIRECTORY . 'database.php');

    $first_day_of_current_month = get_ISO_date_of_first_day_of_the_current_month();

    $query_valid = "SELECT * FROM wallpapers " .
                   "WHERE friendly_month >= '$first_day_of_current_month' " .
                   "AND rejected = '0'" .
                   "ORDER BY list_index ASC;";
    
    $wallpapers = get_nice_array_from_sqlite_set($db->query($query_valid));
    
    header('Content-disposition: attachment; filename=code.txt');
    header('Content-type: text/plain');
?>

<?php foreach($wallpapers as $wallpaper):
    $new_datetime_friendly = new DateTime($wallpaper['friendly_month']);
    $date_directory = strtolower($new_datetime_friendly->format('M-y'));
    $file_name_preview = $new_datetime_friendly->format('M-y');
    
    $file_name_full = get_wallpaper_full_file_name(new DateTime($wallpaper['friendly_month']), $wallpaper['shortcut'], $wallpaper['shortcut'], $wallpaper['type'], false); 
    
    $file_name_preview = get_wallpaper_preview_file_name(new DateTime($wallpaper['friendly_month']), $wallpaper['shortcut'], $wallpaper['shortcut'], $wallpaper['type'], false); 
    
    $class_id = $wallpaper['shortcut'] . '-' . $date_directory;
    
    $theme_name = $wallpaper['shortcut'];
    
    echo $wallpaper['wordpress_description'] . '.';?>
<p><a href="http://files.smashingmagazine.com/wallpapers/<?php echo $date_directory; ?>/<?php echo $theme_name; ?>/<?php echo $file_name_full; ?>" title="<?php echo $wallpaper['theme']; ?>"><img width="500" height="281" alt="<?php echo $wallpaper['theme']; ?>" src="http://files.smashingmagazine.com/wallpapers/<?php echo $date_directory; ?>/<?php echo $theme_name; ?>/<?php echo $file_name_preview; ?>" /></a></p>
<ul>
    <li><a href="http://files.smashingmagazine.com/wallpapers/<?php echo $date_directory; ?>/<?php echo $theme_name; ?>/<?php echo $file_name_preview; ?>" title="<?php echo $wallpaper['theme']; ?> - Preview">preview</a></li>
    <?php $resolutions = explode(',', $wallpaper['resolution']); ?>
    <?php if ($wallpaper['calendar'] == 'both' || $wallpaper['calendar'] == 'calendar-only'): ?><?php $calendar = 'cal' ?>
    <li>
        with calendar: <?php include(THEME_DIRECTORY . '_wallpaper_resolutions_export.html.php'); ?>
    </li>
    <?php endif; ?>
    <?php if ($wallpaper['calendar'] == 'both' || $wallpaper['calendar'] == 'no-calendar-only'): ?><?php $calendar = 'nocal' ?>
    <li>
        without calendar: <?php include(THEME_DIRECTORY . '_wallpaper_resolutions_export.html.php'); ?>
    </li>
    <?php endif; ?>
</ul>



<?php endforeach; ?>