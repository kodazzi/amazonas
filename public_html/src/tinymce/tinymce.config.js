/**
 * Created by jorge on 13/10/15.
 */

$(document).ready(function() {
    tinymce.init({
        selector: "textarea.editor",
        plugins: [
            "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker",
            "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
            "table contextmenu directionality emoticons template textcolor paste fullpage textcolor colorpicker textpattern jbimages"
        ],
        toolbar1: "bold italic underline strikethrough | forecolor backcolor | alignleft aligncenter alignright alignjustify | table | bullist numlist | searchreplace link unlink anchor | link image jbimages | preview code",
        menubar: false,
        toolbar_items_size: 'small',
        height: 350,
        language : 'es',
        relative_urls: false
    });
});