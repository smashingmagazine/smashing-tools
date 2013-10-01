$.fn.fixBroken = function(){
    return this.each(function(){
        var tag = $(this);
        var alt_img = 'http://placehold.it/700x384&text=Malformed+files';
        tag.error(function() { // this adds the onerror event to images
            tag.attr("data-bad-url", tag.attr("src")); // change the src attribute of the image
            tag.attr("src",alt_img); // change the src attribute of the image
        return true;
        } );
    });
};

$(document).ready(function () {
    
    $('.wallpaper-preview').fixBroken();
    
});