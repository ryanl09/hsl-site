(function(){
    $(document).ready(function(){
        $('#apply-n').on('change', function(){
            const checked = $('#apply-n').is(':checked');
            $.ajax({
                url:ajaxurl,
                type:'post',
                data:{'page':'tm-db', 'action':'apply_next', 'apply':checked, 'csrf':$('#csrf').val()},
                dataType:'json',
                success:(data)=>{
                    if (!data.status){
                        show_error(data.errors);
                        return;
                    }
                    show_success('Application status updated!');
                },error:(a,b,c)=>{
                    report_error('dashboard-tm', a+','+b+','+c, 'apply_next');
                }
            });
        });

        $('.copy-code').on('click', function(){
            navigator.clipboard.writeText($('#schoolcode').attr('href'));
            $('.copy-code').html('<i class="bx bxs-check-square"></i>')
            .css('color', '#36d660');
        });

        $('td.user-col').on('click', (e)=>{
            $('.t-select > input').each(function(){
                $(this).prop('checked', false);
            });
            
            const name = $(e.target).text();
            const uname = $(e.target).attr('username');
            const pid = $(e.target).attr('user-id');

            $.ajax({
                url:ajaxurl,
                type:'get',
                data:{'page':'tm-db', 'action':'get_teams', 'pl_id':pid, 'csrf':$('#csrf').val()},
                dataType:'json',
                success:(data)=>{
                    if (data.errors){
                        show_error(data.errors);
                        return;
                    }

                    data.forEach(e => {
                        console.log(e);
                        $(`#st-${e.subteam_id}`).prop('checked', true);
                    });
                },error:(a,b,c)=>{
                    report_error('dashboard-tm', a+','+b+','+c, 'get_teams');
                }
            });

            $('.empty-player').hide();
            $('.player-info').show();
            $('.set-teams').show();
            $('#p-name').html(`<strong>Name:</strong> ${name}`);
            $('#p-uname').html(`<strong>Username:</strong> ${uname}`);

            $('#save-pl-t').unbind('click');

            $('#save-pl-t').on('click', function(){
                var ids = [];
                $('.t-select > input').each(function() {
                    var c = $(this);
                    if (c.is(':checked')) {
                        ids.push(parseInt(c.attr('id').split('-')[1], 10));
                    }

                });

                $.ajax({
                    url:ajaxurl,
                    type:'post',
                    data:{'page':'tm-db', 'action':'allocate','pl_id':pid,'teams':JSON.stringify(ids),'csrf':$('#csrf').val()},
                    dataType:'json',
                    success:(data)=>{
                        if(!data.status){
                            show_error(data.errors);
                            return;
                        }
                        show_success('Player\'s teams updated!');
                    },error:(a,b,c)=>{
                        report_error('dashboard-tm', a+','+b+','+c, 'allocate');
                    }
                });
            });

            $('#p-delete').unbind('click');

            $('#p-delete').on('click', function(){
                var conf = confirm("Are you sure you want to remove this player from your team?");
                if (conf){
                    $.ajax({
                        url:ajaxurl,
                        type:'post',
                        data:{'page':'tm-db', 'action':'remove','pl_id':pid,'csrf':$('#csrf').val()},
                        dataType:'json',
                        success:(data)=>{
                            if (data.errors){
                                show_error(data.errors);
                                return;
                            }
    
                            show_success('Player was removed!');
                            //window.location.reload();
                        },error:(a,b,c)=>{
                            report_error('dashboard-tm', a+','+b+','+c, 'remove(from_team)');
                        }
                    });
                }
            });
        });

        $('.save-teams').unbind('click');
        $('.save-teams').on('click', ()=>{
            save_teams();
            $('.hide-rem').removeClass('.hide-rem');
        });

        $('#add-team').unbind('click');
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

        $('#roster-team').on('change', function(){
            update_events();
        });

        $('.tr-set').on('click', function(){
            var d = fix_date($(this).attr('e-date'));
            var t = fix_time($(this).attr('e-time'));
            var e_id = parseInt($(this).attr('e-id'),10);

            do_trset(d, t, e_id);
        });




        async function remove_team(ctrl){
            var div = ctrl.prev();
            var gam = div.prev();
            var s_id = ctrl.parent().attr('st-id');
    
            if (!div.val() || !gam.val()) {
                ctrl.parent().remove();
                return;
            }
    
            var conf = confirm("Are you sure you want to remove this team? If it's on the schedule, you won't be able to recover these games!");
    
            if (conf){
                $.ajax({
                    url:ajaxurl,
                    type:'post',
                    data:{'page':'tm-db', 'action':'delete', 'csrf':$('#csrf').val(), 'st_id':s_id, 'game_id':gam.val(), 'div':div.val()},
                    dataType:'json',
                    success:(data)=>{
                        if (!data.status || data.errors){
                            show_error(data.errors);
                            return;
                        }
    
                        show_success(data.success);
                        ctrl.parent().remove();
                        //window.location.reload();
                    },error:(a,b,c)=>{
                        report_error('dashboard-tm', a+','+b+','+c, 'delete(subteam)');
                    }
                });
            }
            //console.log(gam.val() + ',' + div.val() + ',' + s_id);
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
                url:ajaxurl,
                type:'post',
                data:{'page':'tm-db', 'action':'save_teams', 'teams':JSON.stringify(teams), 'csrf':$('#csrf').val()},
                dataType:'json',
                success:(data)=>{
                    if (!data.status){
                        show_error(data.errors);
                        return;
                    }

                    show_success('Teams updated!');
                    //window.location.reload();
                },error:(a,b,c)=>{
                    report_error('dashboard-tm', a+','+b+','+c, 'save_teams');
                }
            });
        }
    
        function update_events(){
            
            $.ajax({
                url:ajaxurl,
                type:'get',
                data:{'page':'tm-db', 'action':'get_events', 'team':$('#roster-team').val(), 'csrf':$('#csrf').val()},
                dataType:'json',
                success:(data)=>{
                    if (!data.status){
                        show_error(data.errors);
                        return;
                    }
    
                    $('.events-tbody').html('');
                    data.ev.forEach(e => {
    
                        var status = $('<td>', {
                            html:`<i class="bx bxs-circle ${(e.has_roster ? 'green' : 'red')}"></i>`
                        });
                        var date = $('<td>', {
                            text:`${fix_date(e.event_date)} @${fix_time(e.event_time)}`
                        });
                        var op = $('<td>', {
                            text:`${parseInt($('#roster-team').val(),10)===e.a_id ? e.event_home : e.event_away}`
                        });
    
                        var tr = $('<tr>').attr('e-id', e.e_id)
                        .attr('e-time', e.event_time)
                        .attr('e-date', e.event_date)
                        .addClass('tr-set');
    
                        tr.append(status)
                        .append(date)
                        .append(op);
    
                        tr.on('click', function(){
                            var d = fix_date(e.event_date);
                            var t = fix_time(e.event_time);
                            var e_id = parseInt($(this).attr('e-id'),10);
                
                            do_trset(d, t, e_id);
                        });
    
                        $('.events-tbody').append(tr);
                    });
                },error:(a,b,c)=>{
                    report_error('dashboard-tm', a+','+b+','+c, 'get_events');
                }
            });
        }
    
        function do_trset(d, t, e_id){
            const a = $('.avail-pl');
            const add_s = $('.add-s');
    
            a.html('');
            var p = $('<p>', {
                text: `Match on ${d} @${t}`
            });
            a.append(p);
    
            /**
             * get players
             */
            var d = [];
    
            $.ajax({
                url:ajaxurl,
                type:'get',
                data:{'page':'tm-db', 'action':'get_players', 'st':$('#roster-team').val(), 'e_id':e_id, 'csrf':$('#csrf').val()},
                dataType:'json',
                async:false,
                success:(data)=>{
                    if (!data.status){
                        show_error(data.errors);
                        return;
                    }
    
                    data.players.forEach(e => {
    
                        var on_ros = '';
    
                        data.roster.forEach(f => {
                            if (e.user_id===f.user_id){
                                on_ros='checked';
                            }
                        });
    
                        var div = $('<div>',{
                            html: `<input type="checkbox" id="pl-${e.user_id}" class="roster-box"${on_ros}><label for="pl-${e.user_id}">${e.name}</label>`
                        });
                        a.append(div);
                    });
    
                    var b = $('<button>', {
                        html:'<i class="bx bx-save"></i>Save'
                    }).on('click', function(){
                        var pl=[];
    
                        $('.roster-box').each(function(){
                            if ($(this).is(':checked')){
                                var jd = parseInt($(this).attr('id').split('-')[1]);
                                pl.push(jd);
                            }
                        });
    
                        $.ajax({
                            url:ajaxurl,
                            type:'post',
                            data:{'page':'tm-db', 'action':'set_roster', 'e_id':e_id, 'players':JSON.stringify(pl), 'team_id': $('#roster-team').val(), 'csrf':$('#csrf').val()},
                            dataType:'json',
                            success:(data)=>{
                                if (!data.status){
                                    show_error(data.errors);
                                    return;
                                }
    
                                show_success('Roster set!');
                                //window.location.reload();
                            },error:(a,b,c)=>{
                                report_error('dasboard-tm', a+','+b+','+c, 'set_roster');
                            }
                        });
                    });
                    b.addClass('save-btn clickable btm');
                    add_s.append(b);
    
                },error:(a,b,c)=>{
                    report_error('dashboard-tm', a+','+b+','+c, 'get_players');
                }
            });
        }

    });
})();