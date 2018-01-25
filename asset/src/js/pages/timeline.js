jQuery(document).ready(function($){
    var $timeline_block = $('.timeline li');

    //hide timeline blocks which are outside the viewport
    $timeline_block.each(function(){
        if($(this).offset().top > $(window).scrollTop()+$(window).height()*0.75) {
            $(this).find('.timeline-badge, .timeline-panel').addClass('is-hidden');
        }
    });

    //on scolling, show/animate timeline blocks when enter the viewport
    $(window).on('scroll', function(){
        $timeline_block.each(function(){
            if( $(this).offset().top <= $(window).scrollTop()+$(window).height()*0.75 && $(this).find('.timeline-badge').hasClass('is-hidden') ) {
                $(this).find('.timeline-badge, .timeline-panel').removeClass('is-hidden').addClass('animated fadeIn');
            }
        });
    });
});
jQuery(document).ready(function($){
    var $timeline_block = $('.timeline2 li');

    //hide timeline blocks which are outside the viewport
    $timeline_block.each(function(){
        if($(this).offset().top > $(window).scrollTop()+$(window).height()*0.75) {
            $(this).find('.timeline2-badge, .timeline2-panel').addClass('is-hidden');
        }
    });

    //on scolling, show/animate timeline blocks when enter the viewport
    $(window).on('scroll', function(){
        $timeline_block.each(function(){
            if( $(this).offset().top <= $(window).scrollTop()+$(window).height()*0.75 && $(this).find('.timeline2-badge').hasClass('is-hidden') ) {
                $(this).find('.timeline2-badge, .timeline2-panel').removeClass('is-hidden').addClass('animated bounceIn');
            }
        });
    });
});