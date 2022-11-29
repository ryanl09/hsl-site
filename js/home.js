function menu_scroll(s){
    var nav = $('.top-menu');
    const opacity = s/200 >= 1 ? 1 : s/200;
    nav.css('background-color', `rgba(3, 6, 17, ${opacity})`);
}

$(document).ready(function() {
    menu_scroll(document.body.scrollTop);

    window.onscroll = function () { 
        menu_scroll(document.body.scrollTop);
    };


    $('.activation-btn').on('click', function(){
        send_activation_email();
    });

    function send_activation_email(){
        $.ajax({
            url:ajaxurl,
            type:'post',
            data:{'page':'activate', 'action':'activate_account', 'email':$('email').val(), 'activation_key':$('activation_key').val(), 'csrf':$('#csrf').val()},
            dataType:'json',
            success:(data)=>{
                console.log(data);
                if (!data.status){
                    //error
                    return;
                }
            },
            error:(a,b,c)=>{
                console.log(a+','+b+','+c);
            }
        });
    }
});