$(document).ready(()=>{
    get_teams(1);

    const games = $('#games');
    games.on('change', ()=>{
        get_teams();
    });

    const div = $('#div');
    div.on('change', ()=>{
        get_teams();
    });

    function add_time_box() {
        const time_count = document.getElementsByClassName('game-time').length;
        var w = $(document.createElement('div'));
        w.addClass('game-time');
        w.attr('id', `time-${time_count}`);

        var t = $(document.createElement('input'));
        t.attr('type', 'text');

        var s = $(document.createElement('select'));
        s.append('<option value="am">AM</option><option value="pm">PM</option>');
        s.val('pm');

        w.append(t);
        w.append(s);
        if(time_count > 0){
            var rem = $(document.createElement('i'));
            rem.addClass('bx bxs-checkbox-minus clickable');
            rem.on('click', ()=>{
                $(`#time-${time_count}`).remove();
            });
            w.append(rem);
        }
        $('.game-times').append(w);
    }

    async function get_teams() {
        if ($('#clear-fields').is(':checked')){
            $('input[name="day"]').prop('checked', false);
            $('.game-times').html('');
            add_time_box();
        }
        $('.btn-upload').remove();
        $('#no-team-selected').text('');
        $('.teamlist').addClass('showloading');
        $('.team-cbox').remove();
        $.ajax({
            type:'get',
            url:`${ajax_url}eventpanel-ajax.php`,
            data:{ 'action': 'get_teams', 'game_id': $('#games').val(), 'div':$('#div').val(), 'csrf':$('#csrf').val() },
            dataType:'json',
            async: true,
            success:(data)=>{
                console.log(data);
                $('.teamlist').removeClass('showloading');

                if (data.errors) {
                    return;
                }

                if (!data.teams || data.teams.length < 1){
                    $('#no-team-selected').text('No teams for this game');
                    return;
                }

                $('no-team-selected').hide();
                data.teams.forEach(e => {
                    var c = $(document.createElement('input'));
                    c.attr('type', 'checkbox');
                    c.attr('id', e.slug);
                    c.attr('team-id', e.team_id);
                    c.attr('subteam-id', e.subteam_id);
                    c.attr('name', 'team-select');
                    c.prop('checked', true);

                    var l = $(document.createElement('label'));
                    l.attr('for', e.slug);
                    l.text(e.team_name);

                    var wrapper = $(document.createElement('div'));
                    wrapper.addClass('team-cbox');
                    wrapper.append(c);
                    wrapper.append(l);
                    $('.teamlist').append(wrapper);
                    
                });
            },error:(a,b,c)=>{
                console.log(a+','+b+','+c);
            }
        });
    }

    let pull_schedule = () => {
        var times=[];
        var time_len='';
        Array.from(document.getElementsByClassName('game-time')).forEach(e => {
            times.push($(e).val());
            time_len += $(e).val();
        });
        if (!time_len.length){
            //return { error: 'No times selected' };
        }

        var days=[];
        Array.from(document.getElementsByName('day')).forEach(e => {
            if ($(e).is(':checked')) {
                days.push($(e).val());
            }
        });
        if (!days.length) {
            //return { error: 'No days selected' };
        }

        var date = $('#start-date').val();
        if (!date){
            //return { error: 'No date selected' };
        }

        var weeks = $('#numweeks').val();
        if(!weeks){
            //return { error: 'No # weeks selected' };
        }

        var teams = [];
        Array.from(document.getElementsByName('team-select')).forEach(e =>{
            if($(e).is(':checked')) {
                teams.push($(e).attr('subteam-id'));
            }
        });
        if (teams.length < 2) {
            return { error: 'Not enough teams selected' };
        }

        
        days=['monday','wednesday','friday'];
        times=['3:30pm', '4:15pm', '5:00pm'];
        date='2022-08-16';
        weeks=2;

        $.ajax({
            type:'get',
            url:`${ajax_url}eventpanel-ajax.php`,
            data:{'action':'schedule', 'teams':teams, 'days': days, 'start_day': date, 'weeks':weeks, 'times':times,'csrf':$('#csrf').val() },
            dataType:'json',
            async:true,
            success:function(data){
                $('.btn-generate').prop('disabled', false);
                $('.btn-generate').text('Generate');
                $('.btn-generate').addClass('clickable');
                console.log(data);
                if(data.errors){
                    console.log(data);
                    return;
                }

                const tbl = $('#schedule-body');
                tbl.html('');

                var week=1;
                var last_day = -1;
                var idx=0;
                data.forEach(e => {
                    idx++;
                    if (e.meta) {
                        last_day = e.meta[e.meta.length-1];
                        console.log(last_day);
                        tbl.append(`<tr style="background-color:#ddd !important;"><td>Week ${week}</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>`);
                        week++;
                        return;
                    }
                    var c=0;
                    var last_entry = idx >= data.length;
                    
                    e.matches.forEach(f => {
                        const row = $(document.createElement('tr'));
                        var border = (c === e.matches.length-1 && !last_entry) ? ' style="border-bottom: 1px solid #ddd;"' : '';
                        row.append(`<td${border}>${!c ? e.date : '&nbsp;'}</td>`);
                        row.append(`<td${border}>${f.time}</td>`);
                        row.append(`<td${border}>${f.home}</td>`);
                        row.append(`<td${border}>${f.away}</td>`);
                        tbl.append(row);
                        c++;
                    });
                    if (e.day===last_day && !last_entry){
                        tbl.append(`<tr style="background-color:#ddd !important;"><td>Week ${week}</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>`);
                        week++;
                    }
                });

                console.log(data);

                var p = document.getElementsByClassName('post-gen')[0];
                var up = document.createElement('button');
                const schedule = JSON.stringify(data);
                console.log(schedule);
                $(up).text('Upload');
                $(up).addClass('btn-upload green clickable');
                $(up).on('click', ()=>{
                    $.ajax({
                        type:'post',
                        url:`${ajax_url}eventpanel-ajax.php`,
                        data:{'action':'upload', 'schedule':schedule, 'csrf':$('#csrf').val(), 'game_id':$('#games').val() },
                        dataType:'text',
                        success:(dat)=>{
                            console.log(dat);

                            if (dat.errors) {

                                return;
                            }

                            console.log(dat.success);
                        }
                    });
                });
                p.insertAdjacentElement('afterbegin', up);
            },
            error:function(a,b,c){
                console.log(a+','+b+','+c);
            }
        });

        return true;
    }

    $('.btn-generate').on('click', ()=>{
        $('.btn-generate').prop('disabled', true);
        $('.btn-generate').text('');
        $('.btn-generate').removeClass('clickable');
        var s = pull_schedule();

        if(s.error){
            console.log(`Error: ${s.error}`);
        }
    });

    $('#add-time').on('click', ()=>{
        add_time_box();
    });
});