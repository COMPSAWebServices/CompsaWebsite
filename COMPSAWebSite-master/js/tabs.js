$(document).ready(function() {

    (function ($) {
        $('.tabs ul.tab-list').addClass('active').find('> li:eq(0)').addClass('current');

        $('.tabs ul.tab-list li a').click(function (g) {
            var tab = $(this).closest('.tabs'),
                index = $(this).closest('li').index();

            tab.find('ul.tab-list > li').removeClass('current');
            $(this).closest('li').addClass('current');

            tab.find('.tab-content').find('div.tab-item').not('div.tab-item:eq(' + index + ')').slideUp();
            tab.find('.tab-content').find('div.tab-item:eq(' + index + ')').slideDown();

            g.preventDefault();
        } );
    })(jQuery);

});