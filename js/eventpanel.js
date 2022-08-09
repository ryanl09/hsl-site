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

    let num_times = () => {
        return ;
    }

    async function get_teams() {
        if ($('#clear-fields').is(':checked')){
            $('input[name="day"]').prop('checked', false);
            $('.game-times').html('');
            add_time_box();
        }

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

    $('#add-time').on('click', ()=>{
        add_time_box();
    });
});