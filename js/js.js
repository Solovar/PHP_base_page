$( document ).ready(function() {
    $('.menu li i, .adminMenuDefult ul li b').click(function(){
        $(this).parent().nextAll('li').stop().slideToggle();
    });
    var adminMenu   = $('.adminNavigation');
    $(window).bind("load resize", function()
    {
        var menuFirstChild  = $('.menu li:first-child').css('display');
        var adminMenuDefults = $('.adminMenuDefult li:first-child').css('display');

        if($( window ).width() < '1360')
        {
            if(adminMenu.hasClass('admin_nav'))
            {
                adminMenu.addClass('container');
                adminMenu.removeClass('admin_nav');
                adminMenu.find('a').addClass('adminMenuDefult_A');
                adminMenu.find('ul').addClass('columns').addClass('twelve').addClass('adminMenuDefult');
            }
        }
        else
        {
            $('.adminMenuDefult li:first-child').show();
            if(adminMenu.hasClass('container'))
            {
                adminMenu.addClass('admin_nav');
                adminMenu.removeClass('container');
                adminMenu.find('a').removeClass('adminMenuDefult_A');
                adminMenu.find('ul').removeClass('columns').removeClass('twelve').removeClass('adminMenuDefult');
            }
        }
        if($( window ).width() < '550')
        {
            if(menuFirstChild == 'none' || adminMenuDefults == 'none')
            {
                $('.menu li').hide();
                $('.menu li:first-child').show();

                $('.adminMenuDefult ul li').hide();
                $('.adminMenuDefult ul li:first-child').show();
            }
        }
        else
        {
                $('.menu li').show();
                $('.menu li:first-child').hide();

                $('.adminMenuDefult ul li').show();
                $('.adminMenuDefult ul li:first-child').hide();
        }
    });
});