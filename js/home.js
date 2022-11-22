$(document).ready(function() {
    $('.activation-btn').on('click', function(){
        send_activation_email();
    });

    function send_activation_email(){
        $.ajax({
            url:ajaxurl,
            type:'post',
            data:{'page':'activate', 'action':'activate_account', 'email':$('email').val(),, 'activation_key':$('activation_key').val(), 'csrf':$('#csrf').val()},
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