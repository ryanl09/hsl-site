$(document).ready(function() {
    const errors = $('.errors');
    var btn = $('#submit-btn');

    var btn2 = $('#submit-btn-2');

    btn.on('click', function(){
        btn.val('');
        btn.prop('disabled', true);
        errors.hide();
        errors.html('');

        $.ajax({
            url:ajaxurl,
            type:'post',
            data:{'page':'forgot', 'action':'request', 'email':$('#username').val(),'csrf':$('#csrf').val()},
            dataType:'json',
            success:(data)=>{
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
                report_error('forgot', a+','+b+','+c, 'request');
            }
        });
    });

    btn2.on('click', function(){
        btn2.val('');
        btn2.prop('disabled', true);
        errors.hide();
        errors.html('');
        const p = $('#password').val();
        const p2 = $('#cpassword').val();
        $.ajax({
            url:ajaxurl,
            type:'post',
            data:{'page':'forgot', 'action':'reset', 'password':p, 'cpassword':p2,'csrf':$('#csrf').val()},
            dataType:'json',
            success:(data)=>{

                if(!data.status){
                    btn2.val('Submit');
                    btn2.prop('disabled', false);
                    data.errors.forEach(error => {
                        errors.html(`${errors.html()}${error}<br>`);
                    });
                    errors.show();
                    return;
                }

                $('.login-box').html(`<p>${data.success}</p>`);
            },error:(a,b,c)=>{
                report_error('forgot', a+','+b+','+c, 'reset');
            }
        });
    });
});