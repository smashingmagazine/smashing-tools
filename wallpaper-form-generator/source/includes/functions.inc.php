<?php

/**
 * Functions used by the wallpaper form generator tool.
 * 
 * Authors are reported in case you need help or 
 * someone to blame. Please remember to include yourself!
 * 
 * @author     Frédéric Maquin <frederic.maquin@smashingmagazine.com>
 * @copyright  2013 - Smashing Magazine
 * @version    0.1
 */

/**
 * Handle the validation of the incoming form data.
 * 
 * @return An array filled with errors (can be empty but never null)
 */
function validate_fields() {
    
    $errors = array();
    
    // If we didn't receive any data,
    // let's not bother checking anything.
    if (empty($_POST))
        return array();
    
    // We don't want to be too strict with these regexp.
    $urlRegexp = "/^(https?|s?ftp|git):\/\/.*$/u";
    $emailRegexp = "/^.+@.+\..+$/i";
     
    // Designer field
    if (!isset($_POST['input-designer']) || trim($_POST['input-designer']) == '') {
        $errors['input-designer'] = 'This value is required.';
    }
    
    // Country field
    if (!isset($_POST['input-country']) || trim($_POST['input-country']) == '') {
        $errors['input-country'] = 'This value is required.';
    }
    
    // Email field
    if (!isset($_POST['input-email']) || trim($_POST['input-email']) == '') {
        $errors['input-email'] = 'This value is required.';
    } else if(!preg_match($emailRegexp, $_POST['input-email'])) {
        $errors['input-email'] = 'This value should be a valid email.';
    }
    
    // Url field
    if (!isset($_POST['input-url']) || trim($_POST['input-url']) == '') {
        $errors['input-url'] = 'This value is required.';
    } else if(!preg_match($urlRegexp, $_POST['input-url'])) {
        $errors['input-url'] = 'This value should be a valid url.';
    }
    
    // Month field
    if(!isset($_POST['select-month']) || trim($_POST['select-month']) == '') {
        $errors['select-month'] = 'This value is required.';
    }
    
    // Title of the theme
    if(!isset($_POST['input-theme-title']) || trim($_POST['input-theme-title']) == '') {
        $errors['input-theme-title'] = 'This value is required.';
    }
    
    // Resolutions
    if (empty($_POST['input-resolutions'])) {
       $errors['input-resolutions'] = 'You must select at least one resolution.';
    } else {
        $errors_resolutions = validate_resolutions($_POST['input-resolutions']);
        
        if (isset($errors_resolutions)) {
            $bad_resolutions = implode($errors_resolutions, ', ');
            if (count($errors_resolutions) > 1) {
                $error_prefix = 'The following resolutions are not accepted: ';
            } else {
                $error_prefix = 'The following resolutions is not accepted: ';
            }
            
            $errors['input-resolutions'] = $error_prefix . $bad_resolutions . '.';
        }
    }
    
    // Calendars
    if (!isset($_POST['input-calendars']) || $_POST['input-calendars'] != 'agreed') {
       $errors['input-calendars'] = 'Please, <em>pleeease</em> check the box below. This will make a lot of folks enjoy your wallpaper <em>even more</em> — we promise!';
    }
    
    // File format
    if(!isset($_POST['input-file-format']) || trim($_POST['input-file-format']) == '') {
        $errors['input-file-format'] = 'Please select one of the formats below:';
    }
    
    return $errors;
}

/**
 * Handle the validation for given resolutions.
 * This is safety mechanism. Bad resolutions should normally never
 * make their way to the server, as they are dismissed by javascript.
 * 
 * @param resolutions an array of resolutions (as strings – '00000x00000')
 * @return An array filled with good resolutions or null if no matching resolution
           were found.
 */
function validate_resolutions($resolutions) {
    
    $errors = array();
    
    foreach ($resolutions as $resolution) {
        // A valid resolution starts from 100x100 to 99999x99999.
        if (!preg_match('/^[1-9][0-9]{2,4}x[1-9][0-9]{2,4}$/', $resolution))
            array_push($errors, $resolution);
    }
    
    return empty($errors) ? null : $errors;
}

/**
 * Beautify json, by adding spaces, indents and new lines.
 *
 * Credits to umbrae@gmail.com
 * http://www.php.net/manual/en/function.json-encode.php#80339
 * 
 * @param json a string containing ugly json
 * @return a string conatining a far more readable json.
 */

function json_format($json) { 
    $tab = "  "; 
    $new_json = ""; 
    $indent_level = 0; 
    $in_string = false; 

    $json_obj = json_decode($json); 

    if($json_obj === false) 
        return false; 

    $json = json_encode($json_obj); 
    $len = strlen($json); 

    for($c = 0; $c < $len; $c++) { 
        $char = $json[$c]; 
        switch($char) { 
            case '{': 
            case '[': 
                if(!$in_string) { 
                    $new_json .= $char . "\n" . str_repeat($tab, $indent_level+1); 
                    $indent_level++; 
                } else { 
                    $new_json .= $char; 
                } 
                break; 
            case '}': 
            case ']': 
                if(!$in_string) { 
                    $indent_level--; 
                    $new_json .= "\n" . str_repeat($tab, $indent_level) . $char; 
                } else { 
                    $new_json .= $char; 
                } 
                break; 
            case ',': 
                if(!$in_string) { 
                    $new_json .= ",\n" . str_repeat($tab, $indent_level); 
                } else { 
                    $new_json .= $char; 
                } 
                break; 
            case ':': 
                if(!$in_string) { 
                    $new_json .= ": "; 
                } else { 
                    $new_json .= $char; 
                } 
                break; 
            case '"': 
                if($c > 0 && $json[$c-1] != '\\') { 
                    $in_string = !$in_string; 
                } 
            default: 
                $new_json .= $char; 
                break;                    
        } 
    } 

    return $new_json; 
}