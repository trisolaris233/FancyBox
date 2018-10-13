$(document).ready(function(){
    $("img").each(function(){
        $(this).wrap(function(){
            return '<a data-fancybox="gallery" no-pjax="" data-type="image" data-caption="" href="' + $(this).attr("src") + '" class="light-link"></a>';
        })
        $(this).attr("style", 'border: none; display: inline; max-width: 100% !important;');
    })
 })