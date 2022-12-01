(()=>{
    $(document).ready(()=>{
        const log = $('#login-btn');
        const errors = $('.errors');

        log.on('click', (e)=>{
            e.preventDefault();
            log.prop('disabled', true);
            log.val('');
            errors.hide();
            errors.html('');

            $.ajax({
                type:'post',
                url:ajaxurl,
                data:{ 'page':'login', 'action':'0', 'username':$('#username').val(), 'password':$('#password').val(), 'csrf':$('#csrf').val(), 'nonce':$('#nonce').val()},
                dataType:'json',
                success:(data)=>{
                    log.prop('disabled', false);
                    log.val('Login');
                    if(!data.status) {
                        if (!data.errors) {
                            errors.html(`${'Invalid request.'}<br>`);
                            return;
                        }
                        
                        data.errors.forEach(error => {
                            errors.html(`${errors.html()}${error}<br>`);
                        });
                        errors.show();
                        return;
                    }

                    window.location=data.href;
                },
                error:(a,b,c)=>{
                    log.prop('disabled', false);
                    log.val('Login');
                    report_error('login', a+','+b+','+c, '0_login');
                }
            });
        });

        const showpass = $('#showpass');
        showpass.on('change', ()=>{
            if (showpass.is(':checked')) {
                $('#showpass-label').text('Hide password');
                $('#password').attr('type', 'text');
                return;
            }
            $('#showpass-label').text('Show password');
            $('#password').attr('type', 'password');
        });

        $('#password').keyup((e)=>{
            if(e.keyCode == 13) {
                $('#login-btn').click();
            }
        });
    });
})();