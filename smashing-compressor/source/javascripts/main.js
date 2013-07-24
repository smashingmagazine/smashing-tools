$(document).ready(function () {
    
    $('.no-js-hidden').removeClass('hidden');
    $('#mark-all-button').on('click', function() {
        var checked = '';
        if ($(this).val() == 'Check all') {
            $(this).val('Uncheck All');
            $('.input-resolutions-checkbox').prop('checked', true);
        } else {
            $(this).val('Check all');
            $('.input-resolutions-checkbox').prop('checked', false);
        };
    });
    
    error = '';
    
    function validateResolution(width, height) {
        
        validatedResolution = true;
        
        var regularExpresion = /^[0-9]{3,5}$/;
        var validatedFormat = regularExpresion.test(width) && regularExpresion.test(height);
        
        if (!validatedFormat) {
            error = 'Invalid resolution.';
            validatedResolution = false
        } else {
        
            $('#all-resolutions-available-list input + label').each(function () {
                var dataResolution = $(this).attr('data-resolution');
                resolutionAlreadyExists = (dataResolution == (width + 'x' + height));
            
                if (resolutionAlreadyExists) {
                    error = 'This resolution is available on the left.';
                    validatedResolution = false;
                }
            });
        }
        
        return validatedResolution;
    }
    
    $("#id-input-new-resolution-submit").on('click', function (e) {
        e.preventDefault();
    
        var width = $("#id-input-new-resolution-width").val();
        var height = $("#id-input-new-resolution-height").val();
        
        if (validateResolution(width, height)) {
        
            var resolutionWithX = width + 'x' + height;
            var resolutionWithTime = width + '&times;' + height;
    
            var newSpanToInsert = '<li>' +
                                      '<input id="id-resolution-' + resolutionWithX + '" name="input-resolutions[]" value="' + resolutionWithX + '" type="checkbox" checked>' +
                                      '&nbsp;<label for="id-resolution-' + resolutionWithX + '" data-resolution="' + resolutionWithX + '">' + resolutionWithTime + '</label>' +
                                  '</li>';
    
            $("#all-resolutions-available-list").append(newSpanToInsert);
            
            $('#wallpaper-form').parsley( 'removeItem', '#id-resolution-320x480');
            $('#wallpaper-form').parsley( 'addItem', '#id-resolution-320x480');
            
            $('#id-resolution-320x480').
            
            $('#new-resolution-error').html('')
        } else {
            $('#new-resolution-error').html(error);
        }
    });
    
    $('#id-theme-title').on('focusin focusout', function (e) {
        var themeTitle = $(this).val();
        
        if ($.trim(themeTitle) !== '') {
            $('#subject-line').html('Please use this subject for your email: <span class="email-subject">' + themeTitle + '</span>');
        } else {
            $('#subject-line').html('');
        }
    });
    
    $('#wallpaper-form').parsley( {
        errors: {
            classHandler: function ( elem, isRadioOrCheckbox ) {
                
                if ($(elem).is('#id-resolution-320x480'))
                    return $('.resolution-errors-container');
                else if ($(elem).attr('name') == 'input-file-format')
                    return $('.file-format-errors-container');
                else if ($(elem).attr('name') == 'input-calendars')
                    return $('.calendar-errors-container');
                else
                    return $(elem);
            }
        }
    } );
    
});