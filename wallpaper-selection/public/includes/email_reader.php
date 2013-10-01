<?php

/**
 * Class handling
 *
 *
 * @author     Frédéric Maquin <frederic@smashingmagazine.com>
 * @author     Chris Hope <chris@isavvy.co.nz>
 */

require(INCLUDE_DIRECTORY . 'form_parser.php');

function retrieve_wallpapers_from_mailbox() {

    $mailbox_handler = imap_open(POP_MAILBOX, POP_USERNAME, POP_PASSWORD, OP_READONLY)
                       or die("Sorry sir, I could not connect: " . imap_last_error());
    
    $date_since = get_date_of_first_day_of_the_current_month()->format('d-M-Y');

    $email_list = imap_search($mailbox_handler, 'SINCE ' . $date_since); //BEFORE 30-Mar-2013

    if(is_array($email_list)) {
    
        // Sort by newest emails.
        rsort($email_list);

        foreach($email_list as $email_number) {

            // Get email overview (to further retrieve the sender and the subject)
            $overview = imap_fetch_overview($mailbox_handler, $email_number, 0);
            
            // Let's check whether we already have downloaded this email.
            if (is_there_an_older_record($overview[0]->message_id)) {
                continue; // Record already present, no need to to anything.
                          // We skip this loop iteration.
            }
        
            // Get the current email structure
            $structure = imap_fetchstructure($mailbox_handler, $email_number);
        
            // Have we got some parts? (attachements will always be "parts")
            if (isset($structure->parts)) {
        
                // Flatten all the parts
                $flattened_parts = flattenParts($structure->parts);
            
                foreach($flattened_parts as $part_number => $part) {

                    // Refer to http://php.net/manual/en/function.imap-fetchstructure.php
                    switch($part->type) {
	
                        case 0: // HTML or plain text part of the email, useless to us
                        case 1: // Multi-part headers, useless to us.
                        case 2: // Attached message headers, useless to us.
                        case 3: // Application, useless to us.
                        case 4: // Audio, useless to us.
                        case 5: // Image, useless to us.
                        case 6: // Video, useless to us.
                        case 7: // Other, attachments
        
                            /*
                             * Checking whether the file is a zip file.
                             * TODO: Let's be smarter and check the file header instead.
                             */
                            $filename = getFilenameFromPart($part);
                            $extension = pathinfo($filename, PATHINFO_EXTENSION);
                        
                            if ($extension == "zip") {
                                
                                $attachment = getPart($mailbox_handler, $email_number, $part_number, $part->encoding);
                            
                                // Let's save the file on the disk.
                                // But first let's check which month the wallpapers are sent for.
                                $timezone = new DateTimeZone('CEST');
                                $datetime = DateTime::createFromFormat('D, d M Y H:i:s O', $overview[0]->date, $timezone);
                                $month_and_year = $datetime->modify('first day of next month')->format('m-Y');
                                
                                $decoded_subject = imap_utf8($overview[0]->subject);
                                
                                $exploded_subject = explode(',', $decoded_subject);
                                
                                if (count($exploded_subject) >= 2) {
                                    // The theme name will always be the second token, if the stuff is well-formed. 
                                    $theme_name = sanitize_name($exploded_subject[1]);
                                } else {
                                    break;
                                }
                            
                                // To create the right path to which write the attachment.
                                $directory_where_to_write = WALLPAPERS_DIRECTORY . $month_and_year . '/' . $theme_name . '/';
                                
                                $zip_filename = $theme_name . '.zip';
                                
                                // Let's create the directory beforehand
                                if (!is_dir($directory_where_to_write)) {
                                
                                    $old_umask = umask(0);
                                    mkdir($directory_where_to_write, 0777, true);
                                    umask($old_umask);
                                
                                }
                                        
                                // Let's write the content out!
                                file_put_contents($directory_where_to_write . $zip_filename, $attachment);
                                extract_wallpapers($directory_where_to_write . $zip_filename);
                                
                                // In all these extracted content, let's search
                                // for a txt file. And parse the first one found.
                                $txt_files_found = glob($directory_where_to_write . '/*.txt');
                                
                                $form_txt;
                                
                                if (isset($txt_files_found) && count($txt_files_found) >= 1) {
                                    $form_txt = parse_form($txt_files_found[0]);
                                } else {
                                    break;
                                }
                                
                                // We'll store resolutions as a comma separated list.
                                $resolution_comma_separated =
                                    implode(',', $form_txt['wallpapers']['resolutions']);
                                
                                $friendly_date = get_nice_date_from_month($form_txt['info']['month']);
                                
                                rename_files($directory_where_to_write . $zip_filename, $form_txt['info']['file-name-shortcut'], $friendly_date);
                                
                                // Now, let's take the biggest image ( so we are sure it's good)
                                // And shrink it to some preview (700px for now)
                                
                                include_once(INCLUDE_DIRECTORY . 'libs/SimpleImage.php');
                                
                                if ($form_txt['wallpapers']['calendar'] == 'both' ||
                                    $form_txt['wallpapers']['calendar'] == 'calendar-only') {
                                    $calendar = 'calendar';
                                } else if ($form_txt['wallpapers']['calendar'] == 'no-calendar-only') {
                                    $calendar = 'no-cal';
                                }
                                
                                $resolution = end($form_txt['wallpapers']['resolutions']);
                                reset($form_txt['wallpapers']['resolutions']);
                                
                                
                                
                                $file_name = get_wallpaper_file_name($friendly_date, $theme_name, $form_txt['info']['file-name-shortcut'], $calendar, $resolution, $form_txt['wallpapers']['format']);
                                
                                $file_to_save_to = get_wallpaper_preview_file_name($friendly_date, $theme_name, $form_txt['info']['file-name-shortcut'], $form_txt['wallpapers']['format']);
                                
                                $file_to_save_to_full = get_wallpaper_full_file_name($friendly_date, $theme_name, $form_txt['info']['file-name-shortcut'], $form_txt['wallpapers']['format']);
                                
                                
                                $new_date_parsed = strtolower(get_nice_date_from_month($form_txt['info']['month'])->format('m-Y'));
                                
                                if (strtolower($month_and_year) != $new_date_parsed) {
                                    
                                    if (!is_dir(WALLPAPERS_DIRECTORY . $new_date_parsed)) {
                                
                                        $old_umask = umask(0);
                                        mkdir(WALLPAPERS_DIRECTORY . $new_date_parsed, 0777, true);
                                        umask($old_umask);
                                
                                    }
                                    
                                    rename($directory_where_to_write, WALLPAPERS_DIRECTORY . $new_date_parsed . '/' . $theme_name . '/');
                                }
                                
                                if (file_exists(WALLPAPERS_DIRECTORY . $file_name)) {
                                    $image = new SimpleImage();
                                    $image->load(WALLPAPERS_DIRECTORY . $file_name);
                                    $image->resizeToWidth(500);
                                    $image->save(WALLPAPERS_DIRECTORY . $file_to_save_to);
                                    
                                    $image2 = new SimpleImage();
                                    $image2->load(WALLPAPERS_DIRECTORY . $file_name);
                                    $image2->resizeToWidth(1000);
                                    $image2->save(WALLPAPERS_DIRECTORY . $file_to_save_to_full);
                                }
                                
                                $class_id = get_unique_id_for_each_wallpaper_suggestion($form_txt['info']['title-of-the-theme'], $friendly_date);
                                
                                $wordpress_description = 
                                    '<h3 id="'. $class_id . '">'. $form_txt['info']['title-of-the-theme'] .'</h3>' . "\n" .
                                    '<p>Designed by <a href="' . $form_txt['info']['url'] . '">' . $form_txt['info']['designer'] . '</a> from ' . 
                                    $form_txt['info']['country'] . '</p>';
                                
                                global $db;
                                
                                // Then, let's create a record in our little database!
                                $query =   "INSERT INTO wallpapers (
                                                 month,       theme,
                                                 shortcut,    designer,
                                                 email,       url,
                                                 country,     resolution,
                                                 calendar,    type,
                                                 description, rejected,
                                                 list_index,  mail_id,
                                                 parsed_theme, friendly_month,
                                                 wordpress_description" .
                                            ") VALUES ('" .
                                                 SQLite3::escapeString($form_txt['info']['month']) . "', '" .
                                                 SQLite3::escapeString($form_txt['info']['title-of-the-theme']) . "', '" .
                                                 SQLite3::escapeString($form_txt['info']['file-name-shortcut']) . "', '" .
                                                 SQLite3::escapeString($form_txt['info']['designer']) . "', '" .
                                                 SQLite3::escapeString($form_txt['info']['e-mail']) . "', '" .
                                                 SQLite3::escapeString($form_txt['info']['url']) . "', '" .
                                                 SQLite3::escapeString($form_txt['info']['country']) . "', '" .
                                                 SQLite3::escapeString($resolution_comma_separated) . "', '" .
                                                 SQLite3::escapeString($form_txt['wallpapers']['calendar']) . "', '" .
                                                 SQLite3::escapeString($form_txt['wallpapers']['format']) . "', '" .
                                                 SQLite3::escapeString($form_txt['info']['description']) . "', '" .
                        /* Not yet rejected */   0 . "', '" .
                        /* No index yet */      -1 . "', '" .
                                                 SQLite3::escapeString($overview[0]->message_id) . "', '" .
                                                 SQLite3::escapeString($theme_name) . "', '" .
                                                 SQLite3::escapeString($friendly_date->format(DateTime::ISO8601)) . "', '" .
                                                 SQLite3::escapeString($wordpress_description) . 
                                             "');";
                                                 
                                $db->query($query);
                            
                            } else {
                                break; // Not a zip file, let's break the switch, baby!
                            }
                    }
                }
            }
        }
    } else {
        echo "Jesus! I couldn't retrieve the emails! " . imap_last_error() . "\n";
    }

    imap_close($mailbox_handler);

}

/**
 * Recursive methods flattening all message parts into a one dimensional array.
 * 
 * Courtesy of http://www.electrictoolbox.com/php-imap-message-body-attachments/
 *
 * @author Chris Hope <chris@isavvy.co.nz>
 * @param messageParts all the parts retrieved using imap_fetchstructure
 * @param flattenedParts the parts, in a single dimensional array.
 * @param prefix the part "identifier", initially empty.
 */
function flattenParts($messageParts, $flattenedParts = array(), $prefix = '', $index = 1, $fullPrefix = true) {

    foreach ($messageParts as $part) {
        $flattenedParts[$prefix.$index] = $part;
        if (isset($part->parts)) {
            if($part->type == 2) { // 2 is "message" -> http://php.net/manual/en/function.imap-fetchstructure.php
                $flattenedParts = flattenParts($part->parts, $flattenedParts, $prefix.$index.'.', 0, false);
            } elseif($fullPrefix) {
                $flattenedParts = flattenParts($part->parts, $flattenedParts, $prefix.$index.'.');
            } else {
                $flattenedParts = flattenParts($part->parts, $flattenedParts, $prefix);
            }
            unset($flattenedParts[$prefix.$index]->parts);
        }
        $index++;
    }

    return $flattenedParts;
}

/**
 * Retrieve the content of a part.
 * 
 * Courtesy of http://www.electrictoolbox.com/php-imap-message-body-attachments/
 *
 * @author Chris Hope <chris@isavvy.co.nz>
 * @param connection IMAP connection handler
 * @param messageNumber message number
 * @param partNumber part number
 * @param encoding type (useful to decode the file!)
 */

function getPart($connection, $messageNumber, $partNumber, $encoding) {

    $content = imap_fetchbody($connection, $messageNumber, $partNumber);

    switch($encoding) {
    
        case 0:    // 7bit
        case 1:    // 8bit
        case 2:    // Binary
        case 5:    // Other data type
            break; // --- Nothing do to for those cases. ---
        case 3:    // Base64
            $content = base64_decode($content, TRUE);
            break;
        case 4:    // Quoted printable
            $content = quoted_printable_decode($content);
            break;
    }
    
    return $content;
}

function getFilenameFromPart($part) {

    $filename = '';

    if($part->ifdparameters) {
        foreach($part->dparameters as $object) {
            if(strtolower($object->attribute) == 'filename') {
                $filename = $object->value;
            } else if(strtolower($object->attribute) == 'filename*') {
                $filename = $object->value;
            }
        }
    }

    if(!$filename && $part->ifparameters) {
        foreach($part->parameters as $object) {
            if(strtolower($object->attribute) == 'name') {
                $filename = $object->value;
            }
        }
    }

    return $filename;
}

function is_there_an_older_record($mail_id) {
    
    global $db;
    
    $rows = $db->query('SELECT COUNT(*) FROM wallpapers WHERE mail_id="' . SQLite3::escapeString($mail_id) . '";');

    $first_row = $rows->fetchArray(SQLITE3_ASSOC);
    
    if ($first_row['COUNT(*)'] >= 1) {
        return true;
    }
    
    return false;
}

?>