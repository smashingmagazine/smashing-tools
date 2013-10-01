<?php

/**
 * Parsing utilities for form.txt.
 *
 * The parser doesn't try to be smart. If the file
 * is not well formated, you will get strange behaviors.
 * 
 * Please note that to ensure maximum compatibility,
 * auto_detect_line_endings has to be set to true
 * in the ini file.
 *
 * @author     Frédéric Maquin <frederic@smashingmagazine.com>
 */

abstract class FormParser {
    
    protected $path_to_form_file;
    
    function __construct($path_to_form_file) {
        $this->path_to_form_file = $path_to_form_file;
    }
    
    /**
     * Function parsing the given file and returning a
     * nice data structure.
     *
     * @param path_to_form_file the file to parse.
     * @return parsed data in form of an associative array.
     */
    public abstract function parseForm();
}

class JsonFormParser extends FormParser {
    
    public function parseForm() {
        
        // {
        //   "designer": "John Doe",
        //   "email": "johndoe@example.org",
        //   "url": "http:\/\/example.org\/",
        //   "country": "India",
        //   "month": "08-13",
        //   "theme-title": "Theme Title",
        //   "theme-prefix": "theme-title",
        //   "description": "Gorgeous theme!",
        //   "resolutions": [ "1280x960", "1280x1024", … ],
        //   "file-format": "jpeg"
        // }
        
        $form_data = array();
        $form_data['info'] = array();
        $form_data['wallpapers'] = array();
        $form_data['wallpapers']['resolutions'] = array();
        
        $json = file_get_contents($this->path_to_form_file);
        $json_data = null;

        if ($json) {
            $json_data = json_decode($json, true);
            
            if ($json_data) {
                $form_data['info']['designer']             = $json_data['designer'];
                $form_data['info']['e-mail']               = $json_data['email'];
                $form_data['info']['url']                  = $json_data['url'];
                $form_data['info']['country']              = $json_data['country'];
                $form_data['info']['url']                  = $json_data['url'];
                $form_data['info']['month']                = $json_data['month'];
                $form_data['info']['title-of-the-theme']   = $json_data['theme-title'];
                $form_data['info']['file-name-shortcut']   = $json_data['theme-prefix'];
                $form_data['info']['description']          = $json_data['description'];
                                                           
                $form_data['wallpapers']['resolutions']    = $json_data['resolutions'];
                $form_data['wallpapers']['calendar']       = 'both';
                
                
                if ($json_data['file-format'] == "jpeg") $json_data['file-format'] = "jpg";
                
                $form_data['wallpapers']['format']         = '.' . $json_data['file-format'];
                    
                return $form_data;
            }
        }
        
        return null;
    }
}

class TextFormParser extends FormParser {
    
    public function parseForm() {
    
        // ### NOTICE: this form is processed automatically. Please do not make any changes in the structure of the form. ###
        // ### Your e-mail (below) will be used only for contacting you in case there will be any problems. It will not be displayed online. ###
        // 
        // Month: 
        // Title of the theme: 
        // File name shortcut: 
        // Designer: 
        // E-mail: 
        // URL: 
        // Country: 
        //
        // Resolutions of my wallpapers (please mark available resolutions with [x]):
        // 
        // [ ] 320x480      [ ] 640x480      [ ] 800x480      [ ] 800x600      [ ] 1024x768 
        // [ ] 1024x1024    [ ] 1152x864     [ ] 1280x720     [ ] 1280x800     [ ] 1280x960 
        // [ ] 1280x1024    [ ] 1400x1050    [x] 1440x900     [ ] 1600x1200    [ ] 1680x1050
        // [ ] 1680x1200    [ ] 1920x1080    [x] 1920x1200    [ ] 1920x1440    [ ] 2560x1440
        // 
        // [ ] With calendar    [ ] Without calendar
        // 
        // [ ] .jpg    [ ] .png
        //
        // Description: 
    
    
        // Preparing the structure that will hold the
        // parsed data.
        $form_data = array();
        $form_data['info'] = array();
        $form_data['wallpapers'] = array();
        $form_data['wallpapers']['resolutions'] = array();
    
        // Retrieve all the entire file line by line.
        // and put it in an array.
        $array_of_lines = file($this->path_to_form_file);
    
        /* 
         * The description parsed from the file.
         * This structure is out of the loop just
         * in case the description is written on
         * multiple lines.
         */
        $multi_line_description = array();
    
        // Let's loop on the retrieved lines.
        foreach ($array_of_lines as $line_num => $line) {
            // All lines begins by a tab. Let's remove it.
            $cleaned_line = trim($line);
        
            // From line 3 to line 9, we have basic information
            // about the artist.
            if ($line_num >= 3 && $line_num <= 9) {
            
                // Each piece of information is splitted as follows:
                // <type>: <value>
                $exploded_line = explode(':', $cleaned_line);
            
                // Hence, the type is the first element in
                // the array of token.
                $info_type = strtolower(str_replace(' ', '-', array_shift($exploded_line)));
                $info_value = implode(':', $exploded_line);
            
                // Finally, we put all of this into our
                // main structure.
                $form_data['info'][$info_type] = trim($info_value);
        
            // From line 13 to 16, we have the different
            // wallpaper resolutions available.
            } else if ($line_num >= 13 && $line_num <= 16) { // Wallpapers Resolutions
            
                // Each resolutions are separated by a tab.
                $exploded_line = explode("\t", $cleaned_line);
            
                // We loop through all resolution to get which
                // ones are available.
                foreach($exploded_line as $resolution) {
                
                    // If it's not available, we ignore it.
                    if(substr($resolution, 0, 3) == '[ ]') {
                        continue;
                    // but if it's available, we add the resolution
                    // to our structure.
                    } else {
                        $form_data['wallpapers']['resolutions'][] = str_replace(substr($resolution, 0, 4), '', $resolution);
                    }
                }
        
            // At line 18, we got whether or not there are
            // wallpapers versions with calendars.
            } else if ($line_num == 18) { 
                $match = array();
            
                $regexp = '/\[ *([ x]) *\] (With) calendar[ ]+\[ *([ x]) *\] (Without) calendar/';
            
                // We look both for the x and the suffix
                // -out, discriminatin between the
                // two possibilities.
                if (preg_match($regexp, $cleaned_line, $match)) {
                
                    $with_calendar = False;
                    $without_calendar = False;

                    if (count($match) == 5) { // If everything went right, we'll always have 5 matches
                        $with_calendar = ($match[1] == "x" && $match[2] == "With");
                        $without_calendar = ($match[3] == "x" && $match[4] == "Without");
                    }

                    if ($with_calendar && $without_calendar) {
                        $form_data['wallpapers']['calendar'] = 'both';
                    } else if ($with_calendar && !$without_calendar) {
                        $form_data['wallpapers']['calendar'] = 'calendar-only';
                    } else if (!$with_calendar && $without_calendar) {
                        $form_data['wallpapers']['calendar'] = 'no-calendar-only';
                    }
                }
        
            // At line 20, we have got the file formats.
            // (Either PNG of JPEG.)
            } else if ($line_num == 20) {
                $match = array();
            
                $regexp = '/\[ *x *\] (.png|.jpg)/';
            
                // We look both for the x and the suffix
                // -out, discriminating between the
                // two possibilities.
                if (preg_match($regexp, $cleaned_line, $match)) {

                    if (count($match) > 1) {
                        $form_data['wallpapers']['format'] = $match[1];
                    } else {
                        $form_data['wallpapers']['format'] = '.png';
                    }
                }
        
            // At line 22 and beyond, we have a
            // short description of the work.    
            } else if ($line_num >= 22) {
            
                // As before, description is formated this way:
                // description: <multi-line description>
            
                // First line, let's explode the line
                // and remove "description:"
                if ($line_num == 22) {
                    $exploded_line = explode(':', $cleaned_line);
                
                    // Remove "description".
                    array_shift($exploded_line);
                
                    // Turn the array back into string
                    // and add it to our multi-line array.
                    $multi_line_description[] = implode(':', $exploded_line);
            
                // Next lines
                } else {
                    $multi_line_description[] = $cleaned_line;
                }
            }
        }
    
        // Concatenate all the lines of the description into a single string.
        $form_data['info']['description'] = implode(' ', $multi_line_description);
    
        return $form_data;
    }
}
