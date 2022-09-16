(function(){
    $(document).ready(function(){

        /**
         * team manager
         */

        $('.copy-code').on('click', function(){
            navigator.clipboard.writeText($('#schoolcode').attr('href'));
            $('.copy-code').html('<i class="bx bxs-check-square"></i>')
            .css('color', '#36d660');
        });

        $('td.user-col').on('click', (e)=>{
            const name = $(e.target).text();
            const uname = $(e.target).attr('username');
            const pid = $(e.target).attr('user-id');

            $('.empty-player').hide();
            $('.player-info').show();
            $('.set-teams').show();
            $('#p-name').html(`<strong>Name:</strong> ${name}`);
            $('#p-uname').html(`<strong>Username:</strong> ${uname}`);

            $('#save-pl-t').on('click', function(){
                const tid = [];
                $('.t-select > input').each(function() {

                });
            });
        });

        $('.save-teams').on('click', ()=>{
            save_teams();
            $('.hide-rem').removeClass('.hide-rem');
        });

        $('#add-team').on('click', function(){
            $('#add-team').toggleClass('m-cancel');
            if (!$('#add-team').hasClass('m-cancel')){ //add mode
                $('#add-team').addClass('bxs-plus-square');
                $('#add-team').removeClass('bxs-x-square');
                $('.save-teams').hide();
                $('.game-time[st-id="pending"]').remove();
                return;
            }

            //cancel mode

            $('.save-teams').show();
            $('#add-team').removeClass('bxs-plus-square');
            $('#add-team').addClass('bxs-x-square');
            
            const len = $('.game-time').length;
            var add = $('<div>')
            .attr('st-id', 'pending')
            .attr('id', `subteam-${len}`)
            .addClass('game-time');

            var _s = '';
            var sel = '<select class="sel-gam" name="team-game" id="team-game">';
            games.forEach(e => {
                if(e.id===1){
                    _s = ' selected';
                }
                sel += `<option value="${e.id}"${_s}>${e.name}</option>`;
            });
            sel += '</select>';

            var sel2 = '<select class="sel-div" name="team-div" id="team-div">';
            divs.forEach(e => {
                _s='';
                if(e.id===1){
                    _s = ' selected';
                }
                sel2 += `<option value="${e.id}"${_s}>${e.name}</option>`;
            });
            sel2+='</select>';

            var rem = $('<i>').addClass('bx bxs-checkbox-minus clickable hide-rem');
            rem.on('click', function(){
                remove_team($(this));
            });

            add.append(sel).append(sel2).append(rem);

            $('.game-times').append(add);
            var p = $('.game-times').parent()[0];
            p.scrollTop = p.scrollHeight;

        });

        $('.game-time > i.bxs-checkbox-minus').on('click', function(){ //remove team
            remove_team($(this));
        });
        
        async function remove_team(ctrl){
            var div = ctrl.prev();
            var gam = div.prev();
            var s_id = ctrl.parent().attr('st-id');

            if (!div.val() || !gam.val()) {
                ctrl.parent().remove();
                return;
            }

            $.ajax({
                url:`${ajax_url}tm-db-ajax.php`,
                type:'post',
                data:{'action':'delete', 'csrf':$('#csrf').val(), 'st_id':s_id, 'game_id':gam.val(), 'div':div.val()},
                dataType:'json',
                success:(data)=>{
                    if (!data.status || data.errors){
                        var e_m = '';
                        data.errors.forEach(e => {
                            e_m+=e;
                        });
                        console.log(`Error: ${e_m}`);
                        return;
                    }

                    console.log(data.success);
                    ctrl.parent().remove();
                    window.location.reload();
                },
                error:(a,b,c)=>{
                    console.log(a+','+b+','+c);
                }
            });

            console.log(gam.val() + ',' + div.val() + ',' + s_id);
        }

        async function save_teams(){
            let teams=[];

            $('.game-time').each(function(){
                
                console.log(`#${$(this).attr('id')} > select.sel-gam`);
                let o = {
                    'game_id': $(this).children('select.sel-gam').val(),
                    'div':$(this).children('select.sel-div').val()
                };
                teams.push(o);
            });

            $.ajax({
                url:`${ajax_url}tm-db-ajax.php`,
                type:'post',
                data:{'action':'save_teams', 'teams':JSON.stringify(teams), 'csrf':$('#csrf').val()},
                dataType:'json',
                success:(data)=>{
                    console.log(data);

                    if (data.status){
                        window.location.reload();
                    }
                },
                error:(a,b,c)=>{
                    console.log(a+','+b+','+c);
                }
            });
        }
    });
})();