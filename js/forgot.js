$(document).ready(function() {
    const errors = $('.errors');
    var btn = $('#submit-btn');

    btn.on('click', function(){
        btn.val('');
        btn.prop('disabled', true);
        errors.hide();
        errors.html('');

        $.ajax({
            url:`${ajax_url}forgot-ajax.php`,
            type:'post',
            data:{'action':'request', 'email':$('#username').val(),'csrf':$('#csrf').val()},
            dataType:'text',
            success:(data)=>{
                console.log(data);
                return;

                if(!data.status){
                    btn.val('Submit');
                    btn.prop('disabled', false);
                    data.errors.forEach(error => {
                        errors.html(`${errors.html()}${error}<br>`);
                    });
                    errors.show();
                    return;
                }

                $('.login-box').html(`<p>${data.success}</p>`);
            },
            error:(a,b,c)=>{
                btn.prop('disabled', false);
                btn.val('Submit');
                console.log(a+','+b+','+c);
            }
        });
    });
});