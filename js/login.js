(()=>{
    $(document).ready(()=>{
        const log = $('#login-btn');
        const errors = $('.errors');

        log.on('click', (e)=>{
            e.preventDefault();
            errors.hide();
            errors.html('');

            const cap = grecaptcha.getResponse();
            if (cap.length === 0){
                errors.html('Please complete the captcha!');
                errors.show();
                return;
            }

            log.prop('disabled', true);
            log.val('');


            $.ajax({
                type:'post',
                url:ajaxurl,
                data:{ 'page':'login', 'action':'0', 'username':$('#username').val(), 'password':$('#password').val(), 'csrf':$('#csrf').val(), 'nonce':$('#nonce').val(), 'cap':cap},
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