<?php
    $numItems = count($resolutions);
    $i = 0;

    foreach($resolutions as $resolution) {

        $glue = ', ';
        if(++$i === $numItems) {
            $glue = '';
        }
        
        $datetime = new DateTime($wallpaper['friendly_month']);
        $link = strtolower($datetime->format('M-y')) . '/' . $wallpaper['shortcut'] . '/' . $calendar . '/' . strtolower($datetime->format('M-y')) .
                '-' . $wallpaper['shortcut'] . '-' . $calendar . '-' . trim($resolution) . $wallpaper['type'];
        
        $resolution_name = str_replace('x', '&times;', trim($resolution));
        
        print '<a href="http://files.smashingmagazine.com/wallpapers/' . $link . '" title="'. $wallpaper['theme']. ' - '. trim($resolution) . '">' . trim($resolution) . '</a>' . $glue;
     }
     