<?php
    
require(INCLUDE_DIRECTORY . 'FormParser.class.php');
    
class WallpapersFetcher {
    
    protected $wallpapers_directory;
    protected $current_month;
    protected $errors;
    
    public function __construct($wallpapers_directory) {
        
        $datetime = new DateTime();
        $this->current_month = $datetime->modify('first day of next month')->format('m-Y');

        $this->wallpapers_directory = $wallpapers_directory;
        
        $this->errors = array();
    }
    
    public function fetch() {
 
        $directories = glob($this->wallpapers_directory . "/*");
        
        foreach($directories as $subdirectory) {
            
            echo $subdirectory . "\n";
            
            if (is_dir($subdirectory)) {
            
                $theme_name = '';
            
                $parsed_form_data = $this->parseFromFile($subdirectory);

                if (empty($parsed_form_data)) {
                    add_error($this->errors, 'form', "Couldn't parse the form in : ". $subdirectory);
                    continue;
                }
                
                if ($theme_name == '') {
                    $theme_name = $parsed_form_data['info']['title-of-the-theme'];
                }
                
                if ($this->is_there_an_older_record($theme_name)) {
                    continue; // Record already present, no need to to anything.
                              // We skip this loop iteration.
                }
                
                $month_year_formated_date = get_nice_date_from_month($parsed_form_data['info']['month']);
                
                $parent_directory = $month_year_formated_date->format('m-Y') . '/';
                $new_directory_name = $parsed_form_data['info']['file-name-shortcut'];
                $current_directory = WALLPAPERS_DIRECTORY . $parent_directory . $new_directory_name;
                
                // Let's create the directory beforehand
                if (!is_dir($current_directory) && !file_exists($current_directory)) {
                
                    $old_umask = umask(0);
                    mkdir($current_directory, 0777, true);
                    umask($old_umask);
                    
                }
                
                recurse_copy($subdirectory, $current_directory);
            
                rename_files($current_directory, $parsed_form_data['info']['file-name-shortcut'], $month_year_formated_date);
                $this->createPreviews($parsed_form_data, $theme_name, $month_year_formated_date);
                $this->moveWallpapersIfWrongMonth($this->current_month, $month_year_formated_date->format('m-Y'), $theme_name);
                $this->addNewWallpaperEntry($parsed_form_data, $parsed_form_data['info']['file-name-shortcut'], $month_year_formated_date);
            } else {
                continue;
            }
        }

    }

    protected function parseFromFile($current_directory) {
        
        // In all these extracted content, let's search
        // for a txt file...
        $form_exists = file_exists($current_directory . '/form');
        $json_exists = file_exists($current_directory . '/form.json');
        $txt_exists = file_exists($current_directory . '/form.txt');
        
        $form_name = '';
        
        // ...and parse the first one found.
        //if (isset($txt_files_found) && count($txt_files_found) >= 1) {
        if ($form_exists || $json_exists || $txt_exists) {
            
            if ($form_exists)      {$form_name = '/form';}
            else if ($json_exists) {$form_name = '/form.json';}
            else                   {$form_name = '/form.txt';}
            
            // Instanciate the form parser
            $formParser = new JsonFormParser($current_directory . $form_name);
            return $formParser->parseForm();
            
        } else {
            return false;
        }
    }
    
    protected function createPreviews($parsed_form_data, $theme_name, $month_year_formated_date) {
        
        // Now, let's take the biggest image ( so we are sure it's good)
        // And shrink it to some preview (700px for now)
        include_once(INCLUDE_DIRECTORY . 'libs/SimpleImage.php');
        
        $resolution = end($parsed_form_data['wallpapers']['resolutions']);
        reset($parsed_form_data['wallpapers']['resolutions']);
        
        // Get the wallpaper file name.
        $file_name = get_wallpaper_file_name($month_year_formated_date, $parsed_form_data['info']['file-name-shortcut'], $parsed_form_data['info']['file-name-shortcut'], 'cal', $resolution, $parsed_form_data['wallpapers']['format']);
        
        $file_to_save_to = get_wallpaper_preview_file_name($month_year_formated_date, $parsed_form_data['info']['file-name-shortcut'], $parsed_form_data['info']['file-name-shortcut'], $parsed_form_data['wallpapers']['format']);
        
        $file_to_save_to_full = get_wallpaper_full_file_name($month_year_formated_date, $parsed_form_data['info']['file-name-shortcut'], $parsed_form_data['info']['file-name-shortcut'], $parsed_form_data['wallpapers']['format']);
        
        if (file_exists(WALLPAPERS_DIRECTORY . $file_name)) {
            
            $extension = strtolower(pathinfo(WALLPAPERS_DIRECTORY . $file_name, PATHINFO_EXTENSION));
            
            $format = "";
            
            if ($extension == 'jpg' || $extension == 'jpeg') {
                $format = 'jpeg';
            } else if ($extension == 'png') {
                $format = 'png';
            }
            
            if ($format != "") {
            
                $image = new imagick(WALLPAPERS_DIRECTORY . $file_name);
                $image->setResourceLimit(6, 1);
            
                $image->setImageFormat($format);
            
                if ($format == 'jpeg') {
                    $image->setCompression(Imagick::COMPRESSION_JPEG);
                    $image->setImageCompressionQuality(90);
                } else if ($format == 'png') {
                    $image->setImageCompression(\Imagick::COMPRESSION_UNDEFINED);
                    $image->setImageCompressionQuality(0);
                }
            
                $image_full = clone $image;

                $image->resizeImage(500, 0, imagick::FILTER_LANCZOS, 1);  
                $image_full->resizeImage(1000, 0, imagick::FILTER_LANCZOS, 1);  

                $image->writeImage(WALLPAPERS_DIRECTORY . $file_to_save_to); 
                $image->clear(); 
                $image->destroy();
            
                $image_full->writeImage(WALLPAPERS_DIRECTORY . $file_to_save_to_full); 
                $image_full->clear(); 
                $image_full->destroy();
            } else {
                add_error($this->errors, 'names', "The file most probably has a bad format: " . $file_name);
            }
        } else {
            add_error($this->errors, 'names', "Couldn't find the files. The name is most probably invalid: " . $file_name);
        }
        
    }
    
    protected function moveWallpapersIfWrongMonth($current_month, $date_retrieved_from_form, $theme_name) {
        
        if (strtolower($current_month) != $date_retrieved_from_form) {
            
            if (!is_dir(WALLPAPERS_DIRECTORY . $date_retrieved_from_form) && 
                !file_exists(WALLPAPERS_DIRECTORY . $date_retrieved_from_form)) {
        
                $old_umask = umask(0);
                mkdir(WALLPAPERS_DIRECTORY . $date_retrieved_from_form, 0777, true);
                umask($old_umask);
        
            }
            
            rename(WALLPAPERS_DIRECTORY . strtolower($current_month) . '/' . $theme_name . '/',
                   WALLPAPERS_DIRECTORY . $date_retrieved_from_form . '/' . $theme_name . '/');
        }
    }
    
    protected function addNewWallpaperEntry($parsed_form_data, $theme_name, $month_year_formated_date) {    
        
        $class_id = get_unique_id_for_each_wallpaper_suggestion($parsed_form_data['info']['title-of-the-theme'], $month_year_formated_date);
        
        if (trim($parsed_form_data['info']['description']) != '') 
            $conditional_description = '&#147;' . trim($parsed_form_data['info']['description']) . '&#148; &mdash; ';
        else
            $conditional_description = '';
        
        $wordpress_description = 
            '<h3 id="'. $class_id . '">'. $parsed_form_data['info']['title-of-the-theme'] .'</h3>' . "\n" .
            '<p>' . $conditional_description . 'Designed by <a href="' . $parsed_form_data['info']['url'] . '">' . $parsed_form_data['info']['designer'] . '</a> from ' . 
            $parsed_form_data['info']['country'] . '</p>';
    
        // We'll store resolutions as a comma separated list.
        $resolution_comma_separated =
            implode(',', $parsed_form_data['wallpapers']['resolutions']);
        
        $message_id = $theme_name;// $overview[0]->message_id
        
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
                         SQLite3::escapeString($parsed_form_data['info']['month']) . "', '" .
                         SQLite3::escapeString($parsed_form_data['info']['title-of-the-theme']) . "', '" .
                         SQLite3::escapeString($parsed_form_data['info']['file-name-shortcut']) . "', '" .
                         SQLite3::escapeString($parsed_form_data['info']['designer']) . "', '" .
                         SQLite3::escapeString($parsed_form_data['info']['e-mail']) . "', '" .
                         SQLite3::escapeString($parsed_form_data['info']['url']) . "', '" .
                         SQLite3::escapeString($parsed_form_data['info']['country']) . "', '" .
                         SQLite3::escapeString($resolution_comma_separated) . "', '" .
                         SQLite3::escapeString($parsed_form_data['wallpapers']['calendar']) . "', '" .
                         SQLite3::escapeString($parsed_form_data['wallpapers']['format']) . "', '" .
                         SQLite3::escapeString($parsed_form_data['info']['description']) . "', '" .
/* Not yet rejected */   0 . "', '" .
/* No index yet */      -1 . "', '" .
                         SQLite3::escapeString($message_id) . "', '" .
                         SQLite3::escapeString($theme_name) . "', '" .
                         SQLite3::escapeString($month_year_formated_date->format(DateTime::ISO8601)) . "', '" .
                         SQLite3::escapeString($wordpress_description) . 
                     "');";
                         
        return $db->query($query);
    }
    
    protected function is_there_an_older_record($mail_id) {
    
        global $db;
    
        $rows = $db->query("SELECT COUNT(*) FROM wallpapers WHERE mail_id='" . SQLite3::escapeString($mail_id) . "';");

        $first_row = $rows->fetchArray(SQLITE3_ASSOC);
    
        if ($first_row['COUNT(*)'] >= 1) {
            return true;
        }
    
        return false;
    }
    
    public function printErrors() {

        echo '<ul>';
        foreach($this->errors as $name => $error_array) {
                echo '<li><p>' , $name , '</p><ul>';
            
                foreach($error_array as $error_item) {
                        echo '<li>', $error_item ,'</li>';
                }
            
                echo '</ul></li>';
            }
        echo '</ul>';
    }
    
    public function areThereErrors () {
        return isset($this->errors) && !empty($this->errors);
    }
}
