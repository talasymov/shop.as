$(document).ready(function () {
    CKEDITOR.config.extraPlugins = 'filebrowser';
    CKEDITOR.config.filebrowserBrowseUrl = '/Libs/FrontEnd/ckeditor/browser.php';
    CKEDITOR.config.filebrowserUploadUrl = '/Libs/FrontEnd/ckeditor/upload.php';
    CKEDITOR.config.extraPlugins = 'youtube';
    CKEDITOR.replace('text-content');

    $(".add-news").click(function () {
        formData = new FormData();

        imagePreview = $(".news-preview");

        formData.append("query", "AddNews");
        formData.append("name", $(".news-name").val());
        formData.append("content", contentToTextarea("#text-content"));

        $.each(imagePreview[0].files, function (key, value) {
            formData.append("imagePreview", value);
        });

        $.ajax({
            url: ajaxDir + "Dispatcher",
            type: "POST",
            method: "post",
            contentType: false,
            cache: false,
            processData: false,
            data: formData,
            success: function ()
            {
                location.href = "/dashboard/news/";
            }
        });
    });

    $(".edit-news").click(function () {
        formData = new FormData();

        imagePreview = $(".news-preview");

        formData.append("query", "EditNews");
        formData.append("name", $(".news-name").val());
        formData.append("content", contentToTextarea("#text-content"));
        formData.append("idNews", $(".news-id").val());

        $.each(imagePreview[0].files, function (key, value) {
            formData.append("imagePreview", value);
        });

        $.ajax({
            url: ajaxDir + "Dispatcher",
            type: "POST",
            method: "post",
            contentType: false,
            cache: false,
            processData: false,
            data: formData,
            success: function ()
            {
                location.href = "/dashboard/news/";
            }
        });
    });

    $(".delete-news").click(function () {
        $.ajax({
            url: ajaxDir + "Dispatcher",
            type: "POST",
            method: "post",
            data: {
                "query": "DeleteNews",
                "idNews": $(".news-id").val()
            },
            success: function ()
            {
                location.href = "/dashboard/news/";
            }
        });
    });
});

function contentToTextarea(element){
    var iframe2 = $(element).parent().find('.cke_wysiwyg_frame');

    return iframe2.contents().find(".cke_editable").html();

    return true;
}