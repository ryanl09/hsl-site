(()=>{
    $(document).ready(()=>{
        const reg = $('#register-btn');
        const errors = $('.errors');
        var _FOCUS=[]; //hold only 2 values, the last focused input and the current
        var pass_flags = [0,0,0,0,0];

        var is_ymca = false;

        var code = '';
        var pn = window.location.pathname.split('/');
        if(pn.length>2){
            if(pn[2]==='ymca'){
                is_ymca=true;
                $('#user-type').parent().hide();
                $('.login-header > h1').text('YMCA Registration');
            } else {
                $('#schoolcode').val(pn[2]);
            }
        }

        const ut = $('#user-type');

        ut.on('change', ()=>{
            switch (ut.val()){
                case 'player':
                    $('#field-schoolinfo').hide();
                    $('#field-schoolinfo2').hide();
                    $('#disp-schoolcode').show();
                    break;
                case 'team_manager':
                    $('#field-schoolinfo').show();
                    $('#field-schoolinfo2').show();
                    $('#disp-schoolcode').hide();
                    break;
                case 'caster':
                    $('#field-schoolinfo').hide();
                    $('#field-schoolinfo2').hide();
                    $('#disp-schoolcode').hide();
                    break;
                case 'college':
                    $('#field-schoolinfo').show();
                    $('#field-schoolinfo2').show();
                    $('#disp-schoolcode').hide();
                    break;
            }
        });

        if (is_ymca){
            $('#field-schoolinfo').show();
            $('#field-schoolinfo2').show();
            $('#disp-schoolcode').hide();

            $('#school').attr('placeholder', 'YMCA Location');
            $('#mascot').remove();
            $('#school').parent().parent().removeClass('e2');
            $('#field-schoolinfo2').css('margin-top', '0px');
        }

        reg.on('click', (e)=>{
            e.preventDefault();
            errors.hide();
            errors.html('');
            
            const terms = $('#terms').is(':checked');
            if (!terms){
                errors.html('You must accept the Terms & Conditions and Privacy Policy to register');
                errors.show();
                return;
            }

            const flag_count = pass_flags.reduce(function(a, b) { return a + b; }, 0);
            if (flag_count !== pass_flags.length){
                errors.html(`Please fix the remaining (${pass_flags.length - flag_count}) password errors.`);
                console.log(JSON.stringify(pass_flags));
                errors.show();
                return;
            }

            //disable the login button for all checks that do not happen instantaneously
            reg.prop('disabled', true);
            reg.val('');

            var mascot = $('#mascot').val() ?? '';
            var ymca = is_ymca ? 'ymca' : 'hs';
            var type = is_ymca ? 'team_manager' : ut.val();

            $.ajax({
                type:'post',
                url:ajaxurl,
                data:{ 'page':'register', 'action':'0', 'f_name':$('#firstname').val(), 'l_name':$('#lastname').val(), 'pronouns': $('#pronouns').val(), 'email': $('#email').val(), 
                    'username':$('#username').val(), 'password':$('#password').val(), 'c_password':$('#c_password').val(), 'csrf':$('#csrf').val(),
                    'terms':terms, 'type':type, 'discord': $('#discord').val(), 'school': $('#school').val(), 'mascot':mascot, 'phone':$('#phone').val(), 'primarycolor':$('#primarycolor').val(),
                    'secondarycolor':$('#secondarycolor').val(), 'schoolcode':$('#schoolcode').val(), 'isymca':ymca},
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

        /**password spec events */

        const ps = $('.pass-specs');

        $('#password').on('input propertychange paste', ()=>{
            const p = $('#password').val();
            if (!p.length){
                ps.slideUp();
                return;
            }

            var len = (p.length >= 8) | 0;
            pass_flags[0]=len;
            set_flag('p-len', len);

            var low = (p!=p.toUpperCase()) | 0;
            pass_flags[1]=low;
            set_flag('p-low', low);

            var upp = (p!=p.toLowerCase()) | 0;
            pass_flags[2]=upp;
            set_flag('p-upp', upp);

            const specialChars = /[`!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~]/;
            var spe = specialChars.test(p) | 0;
            pass_flags[3]=spe;
            set_flag('p-spe', spe);

            ps.slideDown();
        });

        $('#c_password').on('input propertychange paste', ()=>{
            const p = $('#c_password').val();
            if (!p.length){
                ps.slideUp();
                return;
            }

            ps.slideDown();

            var mat = (p===$('#password').val()) | 0;
            pass_flags[4]=mat;
            set_flag('p-mat', mat);
        });

        $('input').on('focus', (e)=>{
            _foc(e.target.id);
        });

        $('#showpass').on('click', (e)=>{
            _foc(e.target.id);
        });

        /**
         * color change events
         */

        const c_p = $('#primarycolor');
        const c_s = $('#secondarycolor');
        const c_pt = $('#primarycolor-text');
        const c_st = $('#secondarycolor-text');

        c_p.on('input paste', ()=>{
            c_pt.val(c_p.val());
        });

        c_s.on('input paste', ()=>{
            c_st.val(c_s.val());
        });

        c_pt.on('input paste', ()=>{
            c_p.val(c_pt.val());
        }).on('blur', ()=>{
            if (!valid_color(c_pt.val())) {
                c_pt.val('');
            }
        });

        c_st.on('input paste', ()=>{
            c_s.val(c_st.val());
        }).on('blur', ()=>{
            if (!valid_color(c_st.val())) {
                c_st.val('');
            }
        });

        let valid_color = (hex) =>{
            var s = new Option().style;
            s.color = hex;
            if(hex.charAt(0)==='#'){
                hex=hex.slice(1);
            }
            var s2 = `rgb(${parseInt(hex.substring(0, 2), 16)}, ${parseInt(hex.substring(2, 4), 16)}, ${parseInt(hex.substring(4, 6), 16)})`;
            return s.color == s2;
        }

        /**
         * update the password flag checks (length, letters, etc)
         * @param {string} id 
         * @param {boolean|int} val 
         */
    
        function set_flag(id, val){
            $(`#${id}`).removeClass();
            $(`#${id}`).addClass(val?'good':'bad');
    
            $(`#${id} > i`).removeClass();
            $(`#${id} > i`).addClass('bx');
            $(`#${id} > i`).addClass(val?'bx-check':'bx-x');
        }
    
        /**
         * focus event handler
         * @param {string} id
         */

        function _foc(id) {
            if(_FOCUS.length>1){
                _FOCUS.shift(); //remove the first element to make room for the 'new' 2nd
            }
            _FOCUS.push(id);
            
            if (_FOCUS[1]==='password' || _FOCUS[1]==='c_password' || _FOCUS[1]==='showpass'){
                if($(`#${_FOCUS[1]}`).val().length > 0) {
                    ps.slideDown();
                    return;
                }
            }
            ps.slideUp();
        }
    });
})();