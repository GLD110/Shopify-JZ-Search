$(document).ready(function() {
    $('#summernote').summernote();
$("#category").select2({
    theme: "bootstrap"
});
    $("#selecttag").select2({
        tags: true,
        tokenSeparators: [',', ' '],
        theme: "bootstrap",
        width:"100%",
        placeholder:"tags"
    });
    $('.featured').fileinput()
});