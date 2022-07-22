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
                url:`${ajax_url}login-ajax.php`,
                data:{'username':$('#username').val(), 'password':$('#password').val(), 'csrf':$('#csrf').val()},
                dataType:'json',
                success:(data)=>{
                    console.log(data);
                    log.prop('disabled', false);
                    log.val('Login');
                    if(!data.status) {
                        if (!data.errors) {
                            console.log('Error sending request');
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
                    console.log(`${a} ${b} ${c} `);
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
    });
})();