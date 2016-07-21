/*function addauthor() {
 
    var element = document.createElement("input");
 
    element.setAttribute("type", text);
    element.setAttribute("class", addauthor);
    element.setAttribute("name", author);
    element.setAttribute("placeholder",author);
 
    var foo = document.getElementById("addauthor");
 
    foo.appendChild(element);
 
}

function addpublisher() {
 
    var element = document.createElement("input");
 
    element.setAttribute("type", text);
    element.setAttribute("class", addpublisher);
    element.setAttribute("name", publisher);
    element.setAttribute("placeholder",publisher);
 
    var foo = document.getElementById("addpublisher");
 
    foo.appendChild(element);
 
}
$(function(e) {
	e.preventDefault();
        var scntDiv = $('#p_scents');
        var i = $('#p_scents p').size() + 1;
        
        $('#addScnt').live('click', function() {
                $('<p><label for="p_scnts"><input type="text" id="addauthor" name="addauthor_' + i +'" value="" placeholder="author"/></label> <a href="#" id="remScnt">Remove</a></p>').appendTo(scntDiv);
                i++;
                return false;
        });
        
        $('#remScnt').live('click', function() { 
                if( i > 2 ) {
                        $(this).parents('p').remove();
                        i--;
                }
                return false;
        });
});*/
$('.multi-field-wrapper').each(function() {
    var $wrapper = $('.multi-fields', this);
    $(".add-field", $(this)).click(function(e) {
        $('.multi-field:first-child', $wrapper).clone(true).appendTo($wrapper).find('input').val('').focus();
    });
    $('.multi-field .remove-field', $wrapper).click(function() {
        if ($('.multi-field', $wrapper).length > 1)
            $(this).parent('.multi-field').remove();
    });
});