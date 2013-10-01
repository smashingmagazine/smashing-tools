<?php

/**
 * Helper file, full of utility functions.
 *
 * @author     Vitaly's Father <vitalysfather@smashing-media.com>
 * @author     Frédéric Maquin <frederic@smashingmagazine.com>
 */

/**
 * Pop a javascript alert window displaying
 * the content of the given string.
 *
 * @param $alert_string Alert message to display
 */
function alert ($alert_string) {
    echo '<script>alert(', $alert_string, ');</script>';
}

function get_text_object_from_decoded_imap($array_element) {
	return $array_element->text;
}

function decode_imap_data($string_to_decode) {
    
    print_r(imap_utf8($string_to_decode));
    
    $subject_to_implode = array_map("get_text_object_from_decoded_imap",
                                    imap_utf8($string_to_decode));
                                    
    return implode('', $subject_to_implode);
}

function get_nice_date_from_month($month) {
    
    try {
        $parsed_array = date_parse('01 '. $month);
        
        if ($parsed_array['warning_count'] == 0) {
            return new DateTime(date("Y") . '-' . $parsed_array['month'] . '-' . $parsed_array['day']);
        } else {
            return new DateTime(date_create()->modify('first day of next month')->format('Y-m-1'));
        }
        
    } catch (Exception $e) {
        return new DateTime(date_create()->modify('first day of next month')->format('Y-m-1'));
    }
}

function get_ISO_date_of_first_day_of_the_current_month() {
    return get_date_of_first_day_of_the_current_month()->format(DateTime::ISO8601);
}

function get_date_of_first_day_of_the_current_month() {
    return (new DateTime(date_create()->format('Y-m-1')));
}

// Cory dot mawhorter at ephective dot com
// http://www.php.net/manual/en/ziparchive.extractto.php#97409

function zip_flatten ($zipfile, $dest='.') 
{ 
    $zip = new ZipArchive; 
    if ( $zip->open( $zipfile ) ) 
    { 
        for ( $i=0; $i < $zip->numFiles; $i++ ) 
        { 
            $entry = $zip->getNameIndex($i); 
            if ( substr( $entry, -1 ) == '/' ) continue; // skip directories 
            
            $fp = $zip->getStream( $entry ); 
            $ofp = fopen( $dest.'/'.basename($entry), 'w' ); 
            
            if ( ! $fp ) 
                throw new Exception('Unable to extract the file.'); 
            
            while ( ! feof( $fp ) ) 
                fwrite( $ofp, fread($fp, 8192) ); 
            
            fclose($fp); 
            fclose($ofp); 
        } 

        $zip->close(); 
    } 
    else 
        return false; 
    
    return $zip; 
}

function get_nice_array_from_sqlite_set($sqlite_set) {
    $rows = array(); 
    $i = 0; 

    while($res = $sqlite_set->fetchArray(SQLITE3_ASSOC)) { 
        
        if(!isset($res['id'])) continue;
        
        $rows[$i]['id']                    = $res['id'];
        $rows[$i]['friendly_month']        = $res['friendly_month'];
        $rows[$i]['month']                 = $res['month'];
        $rows[$i]['theme']                 = $res['theme'];
        $rows[$i]['parsed_theme']          = $res['parsed_theme'];
        $rows[$i]['shortcut']              = $res['shortcut'];
        $rows[$i]['designer']              = $res['designer'];
        $rows[$i]['email']                 = $res['email'];
        $rows[$i]['url']                   = $res['url'];
        $rows[$i]['country']               = $res['country'];
        $rows[$i]['resolution']            = $res['resolution'];
        $rows[$i]['calendar']              = $res['calendar'];
        $rows[$i]['type']                  = $res['type'];
        $rows[$i]['description']           = $res['description'];
        $rows[$i]['rejected']              = $res['rejected'];
        $rows[$i]['list_index']            = $res['list_index'];
        $rows[$i]['mail_id']               = $res['mail_id'];
        $rows[$i]['wordpress_description'] = $res['wordpress_description'];
            
        $i++;
    }

    return $rows;
}

function extract_wallpapers($zip_file_path) {
    
    zip_flatten($zip_file_path, dirname($zip_file_path) . '/');
    
}

// And here is the magic. We will try to guess things
// and the rename the files in a proper way! :)
// This is a work in progress.
// function rename_files($parent_dir, $shortcut, $friendly_month) {
//     $files = scandir($parent_dir);
// 
//     foreach($files as $file) {
//         if (!is_dir($file)) {
// 
//             $match = array();
//             if (preg_match('/^(\._)?(.*(calender|calendar|nocal)|.*)?-([0-9]{3,4})[x\x{00D7}]([0-9]{3,4})(\..*)$/u', $file, $match)) {
//                 
//                 if ($match[1] != "") // Crappy OSX hidden files!
//                     continue;
//                 
//                 $calendar = 'nocal';
//                 
//                 if ($match[3] != "") {
//                     if ($match[3] == "calender") {
//                         $calendar = "calendar";
//                     } else if ($match[3] == "no-cal") {
//                         $calendar = "nocal";
//                     } else {
//                         $calendar = $match[3];
//                     }
//                 }
//                 
//                 $new_name = strtolower($friendly_month->format('M-y')) . '-' . $shortcut . '-' . $calendar . '-' . $match[4] . 'x' . $match[5] . $match[6];
//                 
//                 echo $new_name . "\n";
//                 
//                 rename($parent_dir . '/' . $file, $parent_dir . '/' . $new_name);
//             }
//         }
//     }
// }

function rename_files_all($parent_dir, $shortcut, $friendly_month, $files, $calendar) {
    
    foreach($files as $file) {
        if (!is_dir($file)) {

            $match = array();
            if (preg_match('/^([0-9]{3,4})[x\x{00D7}]([0-9]{3,4})(\..*)$/u', $file, $match)) {

                $new_name = strtolower($friendly_month->format('M-y')) . '-' . $shortcut . '-' . $calendar . '-' . $match[1] . 'x' . $match[2] . $match[3];
                
                rename($parent_dir . '/' . $calendar . '/' . $file, $parent_dir . '/' . $calendar . '/' . $new_name);
            }
        }
    }
    
}

// And here is the magic. We will try to guess things
// and the rename the files in a proper way! :)
// This is a work in progress.
function rename_files($parent_dir, $shortcut, $friendly_month) {
    $files_cal = scandir($parent_dir . '/cal');
    $files_nocal = scandir($parent_dir . '/nocal');
    
    rename_files_all($parent_dir, $shortcut, $friendly_month, $files_cal, 'cal');
    rename_files_all($parent_dir, $shortcut, $friendly_month, $files_nocal, 'nocal');
    
    // foreach($files as $file) {
//         if (!is_dir($file)) {
// 
//             $match = array();
//             if (preg_match('/^(\._)?(.*(calender|calendar|nocal)|.*)?-([0-9]{3,4})[x\x{00D7}]([0-9]{3,4})(\..*)$/u', $file, $match)) {
//                 
//                 if ($match[1] != "") // Crappy OSX hidden files!
//                     continue;
//                 
//                 $calendar = 'nocal';
//                 
//                 if ($match[3] != "") {
//                     if ($match[3] == "calender") {
//                         $calendar = "calendar";
//                     } else if ($match[3] == "no-cal") {
//                         $calendar = "nocal";
//                     } else {
//                         $calendar = $match[3];
//                     }
//                 }
//                 
//                 $new_name = strtolower($friendly_month->format('M-y')) . '-' . $shortcut . '-' . $calendar . '-' . $match[4] . 'x' . $match[5] . $match[6];
//                 
//                 echo $new_name . "\n";
//                 
//                 rename($parent_dir . '/' . $file, $parent_dir . '/' . $new_name);
//             }
//         }
//     }
}

function sanitize_name($name) {
    $first_round = preg_replace('/-+/', '-', preg_replace("/[^A-Za-z0-9 -_]/", '',  preg_replace('/(\.|"|\'|:)/', '_', preg_replace('/\s/', '-',  trim($name)))));
    
    // Remove Left-To-right and Right-To-Left Characters.
    // And POP directional Formating.
    return preg_replace('/(\x{200E}|\x{200F}|x{202C})/u', '', $first_round);
}

function sanitize_and_lower_case_of_name($name) {
    return strtolower(sanitize_name($name));
}

function get_image_from_directory($directory, $extension) {

    $txt_files_found = glob($directory . '/*' . $extension);

    if (isset($txt_files_found) && count($txt_files_found) >= 1) {
        return str_replace(ROOT_PROJECT_PATH, "", $txt_files_found[0]);
    }
    
    return "";
}

function get_wallpaper_vanilla_name($friendly_month, $parsed_theme, $shortcut, $calendar, $resolution, $type) {
    return get_wallpaper_directory($friendly_month, $parsed_theme) . '/' . $calendar . '/' . preg_replace('/\s/', '', $resolution) . $type;
}
     
function get_wallpaper_file_name($friendly_month, $parsed_theme, $shortcut, $calendar, $resolution, $type) {
    return get_wallpaper_directory($friendly_month, $parsed_theme) . '/' . $calendar . '/' . strtolower($friendly_month->format('M-y')) .
            '-' . $shortcut . '-' . $calendar . '-' . preg_replace('/\s/', '', $resolution) . $type;
}

function get_wallpaper_preview_file_name($friendly_month, $parsed_theme, $shortcut, $type, $with_directory=true) {
    
    $filename = strtolower($friendly_month->format('M-y')) . '-' . $shortcut . '-preview' . $type;
    
    if ($with_directory)
        return get_wallpaper_directory($friendly_month, $parsed_theme) . $filename;
    else
        return $filename;
}

function get_wallpaper_full_file_name($friendly_month, $parsed_theme, $shortcut, $type, $with_directory=true) {
    
    $filename = strtolower($friendly_month->format('M-y')) . '-' . $shortcut . '-full' . $type;
    
    if ($with_directory)
        return get_wallpaper_directory($friendly_month, $parsed_theme) . $filename;
    else
        return $filename;
}

function get_wallpaper_directory($friendly_month, $parsed_theme) {
    return $friendly_month->format('m-Y') . '/' . $parsed_theme . '/';
}

function get_unique_id_for_each_wallpaper_suggestion($theme, $friendly_month) {
    return sanitize_and_lower_case_of_name($theme) .
                            '-' . $friendly_month->format('m-Y');
}

function recurse_copy($src,$dst) { 
    $dir = opendir($src); 
    @mkdir($dst); 
    while(false !== ( $file = readdir($dir)) ) { 
        if (( $file != '.' ) && ( $file != '..' )) { 
            if ( is_dir($src . '/' . $file) ) { 
                recurse_copy($src . '/' . $file,$dst . '/' . $file); 
            } 
            else { 
                copy($src . '/' . $file,$dst . '/' . $file); 
            } 
        } 
    } 
    closedir($dir); 
}

function add_error(&$error_array, $for, $value) {
    if (is_array($error_array)) {
        
        if (!array_key_exists($for, $error_array)) {
            $error_array[$for] = array();
        }
        
        $error_array[$for][] = $value;
        return true;
    }
    
    return false;
}

























