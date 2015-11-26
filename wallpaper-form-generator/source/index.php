<?php

/**
 * Main wallpaper form generator file.
 * 
 * Authors are reported in case you need help or 
 * someone to blame. Please remember to include yourself!
 * 
 * @author     Frédéric Maquin <frederic.maquin@smashingmagazine.com>
 * @copyright  2013 - Smashing Magazine
 * @version    0.1
 */

require_once('includes/environment.inc.php');
require_once('includes/functions.inc.php');

$errors = validate_fields();

// We got data and they validates. Let's
// generate the json file!
if (!empty($_POST) && empty($errors)):
    
    header('Content-disposition: attachment; filename=form');
    header('Content-type: application/json');
    
    setlocale(LC_ALL, 'en_GB');
    
    // Theme prefix will be used when importing files.
    $theme_prefix = strtolower(
                        preg_replace('/[^\w-]/', '',
                            preg_replace('/[\s]/', '-',
                                iconv('UTF-8', 'ASCII//TRANSLIT', $_POST['input-theme-title'])
                            )
                        )
                    );

    $file = array(
        "designer" => $_POST['input-designer'],
        "email" => $_POST['input-email'],
        "url" => $_POST['input-url'],
        "country" => $_POST['input-country'],
    
    
        "month" => $_POST['select-month'],
        "theme-title" => $_POST['input-theme-title'],
        "theme-prefix" => $theme_prefix,
        "description" => $_POST['input-description'],
    
        "resolutions" => $_POST['input-resolutions'],
        "file-format" => $_POST['input-file-format'],
    );
    
    echo json_format(json_encode($file));
    
else:
    // We have errors. Let's tell the user all
    // about them.
    header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Smashing Form Generator for Monthly Wallpaper Calendars</title>
        <meta name="description" content="The Smashing Form Generator allows you to generate a properly formated form, only by specifying your theme information.">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <meta property="og:type"            content="website"> 
        <meta property="og:url"             content="http://tools.smashingmagazine.com/wallpaper-form-generator/">
        <meta property="og:site_name"       content="Smashing Magazine">
        <meta property="og:title"           content="Smashing Form Generator for Monthly Wallpaper Calendars">
        <meta property="og:description"     content="The Smashing Form Generator allows you to generate a properly formated form, only by specifying your theme information.">
        
        <meta name="twitter:card"           content="summary">
        <meta name="twitter:url"            content="http://tools.smashingmagazine.com/wallpaper-form-generator/">
        <meta name="twitter:title"          content="Smashing Form Generator for Monthly Wallpaper Calendars">
        <meta name="twitter:description"    content="The Smashing Form Generator allows you to generate a properly formated form, only by specifying your theme information.">
        
        <meta name="twitter:site"           content="@smashingmag">
        <meta name="twitter:site:id"        content="15736190">
        <meta name="twitter:creator"        content="@fredericmaquin">
        <meta name="twitter:creator:id"     content="258489954">
        
        <link rel="shortcut icon" href="images/favicon.ico">
        
        <!--[if lt IE 9]>
            <script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.6.2/html5shiv.js"></script>
        <![endif]-->
        
    <?php if (ENVIRONMENT == 'DEVELOPMENT'): ?>
        <link rel="stylesheet" href="stylesheets/main.css">
    <?php else: ?>
        <link rel="stylesheet" id="fontdeck-css"
              href="http://f.fontdeck.com/s/css/qjAxanDb3GzL8rviVV7PWUySExw/*.smashingmagazine.com/12777.css"
              type="text/css" media="screen, print">
              
        <link rel="stylesheet" href="stylesheets/app.css">
    <?php endif; ?>
    </head>

    <body id="container" class="container clearfix">
        <header class="clearfix" role="banner">
            <div class="logo">
                <a href="http://www.smashingmagazine.com/">
                    <img src="images/logo.png" alt="Smashing Magazine" title="Jump to the front page">
                </a>
            </div>
            
            <div class="title">
                <h1>Smashing Form Generator</h1>
                <h2><em>for</em> Monthly Wallpaper Calendars</h2>
            </div>
        </header>
        
        <p class="pro-tip">
            Please make sure you have read the <a href="http://smashed.by/wp-guidelines">submission guidelines</a> before you fill out this form.
        </p>
        
        <form id="wallpaper-form" class="smashing-wallpapers-form"
              data-validate="parsley" action="" method="post">
              
            <fieldset class="fieldset-info">
                <legend>Basic Information</legend>
                <ol>
                    <li>
                        <label for="id-input-designer" class="field-required">What's your name?</label>
                        
                        <div id="designer-errors" class="errors errors-right clearfix">
                            <?php
                                if(isset($errors['input-designer'])) {
                                    echo '<ul class="parsley-error-list"><li class="required">',
                                             $errors['input-designer'],
                                         '</li></ul>';
                                }
                            ?>
                        </div>
                        
                        <input id="id-input-designer" name="input-designer" type="text"
                               placeholder="e.g. John Doe/Jane Doe"
                               data-required="true" data-trigger="focusin focusout"
                               data-error-container="#designer-errors"
                               class="<?php if(empty($_POST)) echo 'parsley-validated';
                                            else if(isset($errors['input-designer'])) echo 'parsley-error';
                                            else echo 'parsley-success'; ?>"
                               value="<?php if(isset($_POST['input-designer'])) echo $_POST['input-designer']; ?>">
                               
                    </li>
                
                    <li>
                        <label for="id-input-email" class="field-required">Please provide us with a valid email address:</label>
                        
                        <div id="email-errors" class="errors errors-right clearfix">
                            <?php
                                if(isset($errors['input-email'])) {
                                    echo '<ul class="parsley-error-list"><li class="required">',
                                             $errors['input-email'],
                                         '</li></ul>';
                                }
                            ?>
                        </div>
                        
                        <input id="id-input-email" name="input-email" type="text" placeholder="e.g. example@example.org"
                               data-required="true" data-type="email" data-trigger="focusin focusout"
                               data-error-container="#email-errors"
                               class="<?php if(empty($_POST)) echo 'parsley-validated';
                                            else if(isset($errors['input-email'])) echo 'parsley-error';
                                            else echo 'parsley-success'; ?>"
                               value="<?php if(isset($_POST['input-email'])) echo $_POST['input-email']; ?>">
                        
                    </li>
                
                    <li>
                        
                        <label for="id-input-url" class="field-required">A link to your website URL or portfolio website:</label>
                        
                        <div class="errors errors-right clearfix" id="url-errors">
                            <?php
                                if(isset($errors['input-url'])) {
                                    echo '<ul class="parsley-error-list"><li class="required">',
                                              $errors['input-url'],
                                         '</li></ul>';
                                }
                            ?>
                        </div>
                        
                        <input id="id-input-url" name="input-url" type="text" placeholder="e.g. http://mypersonalwebsite.com"
                               data-required="true" data-type="urlstrict" data-trigger="focusin focusout"
                               data-error-container="#url-errors"
                               class="<?php if(empty($_POST)) echo 'parsley-validated';
                                            else if(isset($errors['input-url'])) echo 'parsley-error';
                                            else echo 'parsley-success'; ?>"
                               value="<?php if(isset($_POST['input-url'])) echo $_POST['input-url']; ?>">
                    </li>
                
                    <li>
                        
                        <label for="id-input-country" class="field-required">Where are you from?</label>
                        
                        <div id="country-errors" class="errors errors-right clearfix">
                            <?php
                                if(isset($errors['input-country'])) {
                                    echo '<ul class="parsley-error-list"><li class="required">',
                                              $errors['input-country'],
                                         '</li></ul>';
                                }
                            ?>
                        </div>
                        
                        <input id="id-input-country" name="input-country" type="text" placeholder="e.g. France"
                               data-required="true" data-trigger="focusin focusout"
                               data-error-container="#country-errors"
                               class="<?php if(empty($_POST)) echo 'parsley-validated';
                                            else if(isset($errors['input-country'])) echo 'parsley-error';
                                            else echo 'parsley-success'; ?>"
                               value="<?php if(isset($_POST['input-country'])) echo $_POST['input-country']; ?>">
                               
                        
                    </li>
                
                </ol>
            </fieldset>
            <fieldset class="fieldset-info">
                <legend>Wallpapers Information</legend>
                <ol>
                    <li>
                        <label for="id-select-month" class="select-label field-required">Month of the wallpaper:</label>
                        
                        <div id="month-errors" class="errors errors-right clearfix">
                            <?php
                                if(isset($errors['select-month'])) {
                                    echo '<ul class="parsley-error-list"><li class="required">',
                                              $errors['select-month'],
                                         '</li></ul>';
                                }
                            ?>
                        </div>
                        
                        <select id="id-select-month" name="select-month" data-required="true" 
                                data-error-container="#month-errors"
                                class="<?php if(empty($_POST)) echo 'parsley-validated';
                                             else if(isset($errors['select-month'])) echo 'parsley-error';
                                             else echo 'parsley-success'; ?>">
                             
                            <?php
                            $months = array(
                                "Please choose a month" => "",
                                date("F Y", strtotime("+1 Months"))  =>  date("m-y", strtotime("+1 Months")),
                                date("F Y", strtotime("+2 Months"))  =>  date("m-y", strtotime("+2 Months")),
                                date("F Y", strtotime("+3 Months"))  =>  date("m-y", strtotime("+3 Months"))
                            );
                            
                            foreach ($months as $name => $value):
                            
                            ?>   
                            <option value="<?php echo $value; ?>"
                                    <?php if(isset($_POST['select-month']) && $_POST['select-month'] == $value)
                                              echo 'selected="selected"'
                                    ?> >
                                <?php echo $name; ?>
                            </option>
                        <?php endforeach; ?>
                            
                        <select>
                        
                    </li>
                
                    <li>
                        <label for="id-input-theme-title" class="field-required">Title of your wallpaper theme:</label>
                        
                        <div id="title-errors" class="errors errors-right clearfix">
                            <?php
                                if(isset($errors['input-theme-title'])) {
                                    echo '<ul class="parsley-error-list"><li class="required">',
                                              $errors['input-theme-title'],
                                         '</li></ul>';
                                }
                            ?>
                        </div>
                        
                        <input id="id-input-theme-title" name="input-theme-title" type="text" placeholder="e.g. It smells like Spring!"
                               data-required="true" data-error-container="#title-errors"
                               class="<?php if(empty($_POST)) echo 'parsley-validated';
                                            else if(isset($errors['input-theme-title'])) echo 'parsley-error';
                                            else echo 'parsley-success'; ?>"
                               value="<?php if(isset($_POST['input-theme-title'])) echo $_POST['input-theme-title']; ?>">
                    </li>
                
                    <li>
                        <label for="id-input-description">What inspired you to create your wallpaper?</label>
                        <textarea id="id-input-description" name="input-description" placeholder="e.g. March brings the smell of Spring, so I designed a wallpaper to remind everyone to enjoy the fresh flowers and sunny days…" data-error-container="#id-input-description + .errors"></textarea>
                        <div class="errors errors-right clearfix"></div>
                    </li>
                </ol>
            </fieldset>
            <fieldset id="all-resolutions-available">
                <legend>Resolutions</legend>
                <div class="left-fieldset resolution-fieldset resolution-errors-container <?php if(empty($_POST)) echo 'parsley-validated';
                                                                            else if(isset($errors['input-resolutions'])) echo 'parsley-error';
                                                                            else echo 'parsley-success'; ?>">
                    <div class="resolution-errors errors">
                        <?php
                            if(isset($errors['input-resolutions'])) {
                                echo '<ul class="parsley-error-list"><li class="required">',
                                          $errors['input-resolutions'],
                                     '</li></ul>';
                            }
                        ?>
                    </div>
                    <ul id="all-resolutions-available-list" class="columned">
                        
                        <?php
                        $resolutions = array(
                            '320x480', '640x480', '800x480', '800x600',
                            '1024x768', '1024x1024', '1152x864', '1280x720',
                            '1280x800', '1280x960', '1280x1024', '1400x1050',
                            '1440x900', '1600x1200', '1680x1050', '1680x1200',
                            '1920x1080', '1920x1200', '1920x1440', '2560x1440'
                        );
                        
                        $first = true;
                        
                        if (empty($_POST['input-resolutions']))
                            $_POST['input-resolutions'] = array();
                        
                        foreach ($resolutions as $resolution):
                            
                        ?>
                        
                        <li>
                            <input id="id-resolution-<?php echo $resolution; ?>"
                                   class="input-resolutions-checkbox" name="input-resolutions[]"
                                   value="<?php echo $resolution; ?>" type="checkbox"
                                   data-error-message="You must select at least one resolution."
                                   <?php if($first) { echo 'data-mincheck="1" data-trigger="change"
                                   data-error-container=".resolution-errors"';} if (in_array($resolution, $_POST['input-resolutions'])) echo 'checked="checked"'; ?>>&nbsp;<label for="id-resolution-<?php echo $resolution; ?>" data-resolution="<?php echo $resolution; ?>"><?php echo preg_replace('/x/', '&times;', $resolution); ?></label>
                        </li>
                        
                        <?php
                            $first = false;
                            endforeach;
                        ?>
                    </ul>
                    <input id="mark-all-button" type="button" class="minor-button mark-all no-js-hidden hidden" value="Check all">
                </div>
                <div class="right-fieldset add-new-resolution no-js-hidden hidden">
                    <label for="id-input-new-resolution-width">Add another resolution:</label>

                        <input id="id-input-new-resolution-width" name="input-new-resolution-width" type="text" placeholder="2560">
                        <span class="span-times">&times;</span>
                        <input id="id-input-new-resolution-height" name="input-new-resolution-height" type="text" placeholder="1600">

                        <input id="id-input-new-resolution-submit" name="input-new-resolution-submit" type="button" value="Add a new resolution" class="minor-button">
                        <div class="errors new-resolution-error" id="new-resolution-error"></div>
                </div>
            </fieldset>
        
            <fieldset id="id-file-type-fieldset" class="left-fieldset clearfix two-options-fieldset">
                <legend>File Format</legend>
                <div class="file-format-errors-container <?php if(empty($_POST)) echo 'parsley-validated';
                                                               else if(isset($errors['input-file-format'])) echo 'parsley-error';
                                                               else echo 'parsley-success'; ?>">
                    <div class="file-format-errors errors">
                        <?php
                            if(isset($errors['input-file-format'])) {
                                echo '<ul class="parsley-error-list"><li class="required">',
                                          $errors['input-file-format'],
                                     '</li></ul>';
                            }
                        ?>
                    </div>
                    <ul>
                        <li>
                                <input id="id-file-format-png" name="input-file-format" type="radio" value="png"
                                <?php if(isset($_POST['input-file-format']) && $_POST['input-file-format'] == 'png')
                                          echo 'checked="checked"'
                                ?> >
                                <label for="id-file-format-png">PNG</label>
                        </li>
            
                        <li>
                                <input id="id-file-format-jpg" name="input-file-format" type="radio" value="jpeg"
                                       data-required="true" data-error-container=".file-format-errors"
                                       data-error-message="Please select one of the formats below:"
                                       <?php if(isset($_POST['input-file-format']) && $_POST['input-file-format'] == 'jpeg')
                                                 echo 'checked="checked"'
                                       ?> >
                                <label for="id-file-format-jpg">JPEG</label>
                        </li>
                    </ul>
                </div>
            </fieldset>
            
            <fieldset id="id-calendar-fieldset" class="right-fieldset clearfix two-options-fieldset">
                <legend>Last Step</legend>
                
                <div class="clearfix calendar-errors-container <?php if(empty($_POST)) echo 'parsley-validated';
                                                                else if(isset($errors['input-calendars'])) echo 'parsley-error';
                                                                else echo 'parsley-success'; ?>">  
                    <div class="calendar-errors errors">
                        <?php
                            if(isset($errors['input-calendars'])) {
                                echo '<ul class="parsley-error-list"><li class="required">',
                                          $errors['input-calendars'],
                                     '</li></ul>';
                            }
                            
                            if (empty($_POST['input-calendars']))
                                $_POST['input-calendars'] = array();
                        ?>
                    </div>
                    
                    <ul>
                        <li>
                                <input id="id-with-calendar" name="input-calendars" type="checkbox" value="agreed" data-error-container=".calendar-errors" data-mincheck="1" data-trigger="change" <?php if (isset($_POST['input-calendars']) && $_POST['input-calendars'] == "agreed") echo 'checked="checked"'; ?> data-error-message="Please, <em>pleeease</em> check the box below. This will make a lot of folks enjoy your wallpaper even more — we promise!">
                                <label for="id-with-calendar">I will do my best to submit the wallpapers both with and without calendars — I promise!</label>
                        </li>
                    </ul>
                </div>
            </fieldset>
            
            <div class="form-footer">
                <p id="subject-line"></p>
                <button type="submit" class="major-button">Generate!</button>
            </div>
        </form>
        
        <footer class="clearfix">
            <div class="proudly">
                <span>Proudly presented by</span>
                <a href="http://www.smashingmagazine.com"><img src="images/logo.png" alt="Smashing Magazine"></a>
            </div>
            
            <div class="made-by">
                <span>This tool is made with a lot of love by <a href="https://twitter.com/ephread">@ephread</a></span>
            </div>
        </footer>
        
    <?php if (ENVIRONMENT == 'DEVELOPMENT'): ?>
        <script src="javascripts/jquery-1.10.1.min.js"></script>
        <script src="javascripts/parsley.min.js"></script>
        <script src="javascripts/main.js"></script>
    <?php else: ?>
        <script async defer src="javascripts/app.js"></script>
    <?php endif; ?>
    </body>
</html>

<?php endif;