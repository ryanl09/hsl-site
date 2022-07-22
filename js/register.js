(()=>{
    $(document).ready(()=>{
        const reg = $('#register-btn');
        const errors = $('.errors');

        reg.on('click', (e)=>{
            e.preventDefault();
            reg.prop('disabled', true);
            reg.val('');
            errors.hide();
            errors.html('');

            $.ajax({
                type:'post',
                url:`${ajax_url}register-ajax.php`,
                data:{'f_name':$('#firstname').val(), 'l_name':$('#lastname').val(), 'pronouns': $('#pronouns').val(), 'email': $('#email').val(), 'username':$('#username').val(), 'password':$('#password').val(), 'c_password':$('#c_password').val(), 'csrf':$('#csrf').val()},
                dataType:'json',
                success:(data)=>{
                    console.log(data);
                    reg.prop('disabled', false);
                    reg.val('Login');
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
                    reg.prop('disabled', false);
                    reg.val('Login');
                    console.log(`${a} ${b} ${c} `);
                }
            });
        });

        const showpass = $('#showpass');
        showpass.on('change', ()=>{
            if (showpass.is(':checked')) {
                $('#showpass-label').text('Hide password');
                $('#password').attr('type', 'text');
                $('#c_password').attr('type', 'text');
                return;
            }
            $('#showpass-label').text('Show password');
            $('#password').attr('type', 'password');
            $('#c_password').attr('type', 'password');
        });
    });    
})();