<?php

// set up basic connection
$ftp_connection_id = ftp_connect('46.4.78.111'); 

// login with username and password
$ftp_login_result = ftp_login($ftp_connection_id, 'ftpuser', 'ii9YxFHrHBQ5g7zqt9eu'); 

// check connection
if ((!$ftp_connection_id) || (!$ftp_login_result)) {
    die("FTP connection has failed !");
}

echo "Current directory: " . ftp_pwd($ftp_connection_id) . "\n";

// try to change the directory to somedir
if (ftp_chdir($ftp_connection_id, '/files.smashingmagazine.com/public/wallpapers/may-13/images/')) {
    echo "Current directory is now: " . ftp_pwd($ftp_connection_id) . "\n";
} else { 
    echo "Couldn't change directory\n";
}

foreach (glob(WALLPAPERS_DIRECTORY . '05-2013/*') as $directory) {
    
    if (is_dir($directory)) {
        
        echo basename($directory);
        
        if (ftp_mkdir($ftp_connection_id, '/files.smashingmagazine.com/public/wallpapers/may-13/images/' . basename($directory))) {
            
            if (ftp_chdir($ftp_connection_id, '/files.smashingmagazine.com/public/wallpapers/may-13/images/' . basename($directory))) {
                foreach (glob("$directory/*.{png,jpg}", GLOB_BRACE) as $filename) {
                    ftp_put($ftp_stream, basename($filename), $filename, FTP_BINARY);
                }
                
            } else { 
                echo "Couldn't change directory\n";
            }
            
            ftp_chdir($ftp_connection_id, '/files.smashingmagazine.com/public/wallpapers/may-13/images/');
        }
    }
}

// close the connection
ftp_close($ftp_connection_id);