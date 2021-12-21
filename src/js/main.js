$('.you-used h3').on('click',function(){
    $(this).parent().toggleClass('active');
})

$('.calc-block form input').on('keyup',function(){

    $(".calc-block form input").each(function() {
        var element = $(this);
        if (element.val() == "") {
            $('.calc-block form button').removeClass('active');
            $('.calc-block form button').prop( "disabled", true);

        }
        else {
            $('.calc-block form button').addClass('active');
            $('.calc-block form button').prop( "disabled", false );
        }
     });
    
}) 


$('.add-to-fav').on('click',function(){
    $(this).toggleClass('active');
})

$('.fav-status').on('click',function(){
    $(this).toggleClass('active');
})

$('.plenka').on('click',function(){
    $(this).parent().fadeOut();
})

$('.search-button').on('click',function(){
   $('.search-popup').fadeIn();
})

$('.burger').on('click',function(){
    $(this).toggleClass('active');
    $('body').toggleClass('hiddy');
    $('.mobile-menu').toggleClass('active');
})

$('.droppy-action').on('click',function(){
    $(this).toggleClass('active');
    $(this).closest('.with-drop').find('.droppy').slideToggle();
})

$('.isotopegrid').isotope({
    // options
 
    itemSelector: '.grid-item',
    percentPosition: true,
    masonry: {
        gutter: 46,

      },
  });
