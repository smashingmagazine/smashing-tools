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
                <h1>Smashing Image Compressor</h1>
            </div>
        </header>
        
        <form id="wallpaper-form" class="smashing-wallpapers-form"
              data-validate="parsley" action="" method="post">
              
            <input id="file-input" type="file" style="display:none">
            
            <input id="photoCover" class="input-large" type="text">
            <a class="btn" onclick="$('input[id=lefile]').click();">Browse</a>

            <script type="text/javascript">
                $('input[id=lefile]').change(function() {
                    $('#photoCover').val($(this).val());
                });
            </script>

            <button type="submit" class="major-button">Generate!</button>
        </form>
        
    <?php if (ENVIRONMENT == 'DEVELOPMENT'): ?>
        <script src="javascripts/jquery-1.10.1.min.js"></script>
        <script src="javascripts/main.js"></script>
    <?php else: ?>
        <script async defer src="javascripts/app.js"></script>
    <?php endif; ?>
    </body>
</html>

<?php endif;