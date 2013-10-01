                <div class="side-list">
                    <h4>Wallpapers</h4>
                    <ul class="sortable-menu">
                        <?php
                            $button_class = 'btn-danger delete-wallpaper';
                            $icon_class = 'icon-remove';
                            $move_class = '';
                            $li_class = '';
                            
                            foreach($valid_wallpapers as $number => $wallpaper) {
                                include(THEME_DIRECTORY . '_wallpaper_menu_list_element.html.php');
                            }
                        ?>
                    </ul>
            
                    <h4>Rejected Wallpapers</h4>
                    <ul class="wallpaper-menu-trash">
                        <?php
                            $button_class = 'btn-success recycle-wallpaper';
                            $icon_class = 'icon-ok';
                            $move_class = 'hidden';
                            $li_class = 'no-number';
                            
                            foreach($rejected_wallpapers as $number => $wallpaper) {
                                include(THEME_DIRECTORY . '_wallpaper_menu_list_element.html.php');
                            }
                        ?>
                    </ul>
                </div>
            </div>
    
            <div class="wallpapers">
                <div class="refresh-banner">
                    <a class="btn btn-danger btn delete-all-button" href="empty_database.php">
                        <i class="icon-trash"></i>
                    </a>
                    
                    <a class="btn btn-success btn save-all-button" href="#">
                        <i class="icon-save"></i>
                    </a>
                    <a class="btn btn-inverse btn download-button" href="generate_code.php">
                        <i class="icon-download-alt"></i>
                    </a>
                    <a class="btn btn-info btn refresh-button" href="refresh_wallpapers.php">
                        <i class="icon-refresh"></i>
                    </a>
                </div>
        
                <div class="wallpaper-list">
                    <ul class="sortable-wallpapers">
                        <?php 
                            foreach($valid_wallpapers as $number => $wallpaper) {
                                include(THEME_DIRECTORY . '_wallpaper_list_element.html.php');
                            }
                        ?>
                    </ul>
                    
                    <ul class="sortable-wallpapers-trash hidden">
                        <?php 
                            foreach($rejected_wallpapers as $number => $wallpaper) {
                                include(THEME_DIRECTORY . '_wallpaper_list_element.html.php');
                            }
                        ?>
                    </ul>
            
                </div>
            </div>