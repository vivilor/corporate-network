$(document).ready(function(){
    $("button").click(function(){
        $("div#show").empty();
        console.log($("li"));
        jQuery.each($("li"), function(){
            var current_obj = $(this).find('div select');
            console.log($(this));
            console.log($("select").parents("li"));
            $("div#show").append('<div>' +
                current_obj.val() +
            '</div>');
        });
        console.log($("li:last").index());
    });
});