$('.you-used h3').on('click',function(){
    $(this).parent().toggleClass('active');
})

$('.calc-block form input').on('keyup',function(){

    $(".calc-block form input").each(function() {
        var element = $(this);
        if (element.val() == "") {
            $('.calc-block form button').removeClass('active');

        }
        else {
            $('.calc-block form button').addClass('active');
        }
     });
    
}) 