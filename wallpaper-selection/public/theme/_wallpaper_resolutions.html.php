<?php
    $numItems = count($resolutions);
    $i = 0;
?>
<?php foreach($resolutions as $resolution):?>
    
    <?php
        $glue = ', ';
        if(++$i === $numItems) {
            $glue = '.';
        }
        
        $datetime = new DateTime($wallpaper['friendly_month']);
        $link = $datetime->format('m-Y') . '/' . $wallpaper['shortcut'] . '/' . $calendar . '/' . strtolower($datetime->format('M-y')) .
                '-' . $wallpaper['shortcut'] . '-' . $calendar . '-' . trim($resolution) . $wallpaper['type'];
    ?>
    <a href="wallpapers/<?php echo $link; ?>"><?php echo trim($resolution); ?></a><?php echo $glue; ?>
<?php endforeach;?>
