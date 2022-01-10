$(document).ready(function(){

$('.you-used h3').on('click',function(){
    $(this).parent().toggleClass('active');
})

// $('.calc-block form input[type="text"]').on('keyup',function(){
//     let i = 0;
//     $(".calc-block form input").each(function() {
//         var element = $(this);
//         if (element.val() == "") {
//             i++;
//         }
//      }); 
//      if(i == 0){
//          $('.calc-block form button').prop('disabled',true);
//          $('.calc-block form button').addClass('active');
//      }
//      else {
//         $('.calc-block form button').prop('disabled',false);
//         $('.calc-block form button').removeClass('active');
//      }
// }); 


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


$('.button-open').on('click',function(){
    let open = $(this).data('open');
    $('.'+open).fadeIn();
})

})

$('.action-setting').on('click',function(){
    $(this).toggleClass('active');
})