$(document).ready(()=>{
    var e_id = window.location.pathname.split('/')[2] ?? 0;
    if (!e_id) {
        //invalid event
        return;
    }

    var save = $('.save-btn');
    var eventFlag = $('.flag-btn');

    if(save){
        save.on('click', ()=>{
            save.prop('disabled', true);
            const save_html = save.html();
            save.html('');
            
            var obj=[];
            var tb = $('.st-mod');
            tb.each(function() {
                var o = {
                    u: $(this).attr('user-id'),
                    s: $(this).attr('stat-id'),
                    v: $(this).val()
                };
                obj.push(o);
            });

            console.log($('#home-score').val());

            var home_score = $('#home-score').val() ?? 0;
            var away_score = $('#away-score').val() ?? 0;

            console.log(home_score);
            $.ajax({
                url:`${ajax_url}event-ajax.php`,
                type:'post',
                data:{'action':'stats', 'data':JSON.stringify(obj), 'event_id':e_id, 'home_score':home_score, 'away_score':away_score, 'csrf':$('#csrf').val()},
                dataType:'text',
                success:(data)=>{
                    save.prop('disabled', false);
                    save.html(save_html);
                    console.log(data);
                    alert(data);
                    if (!data.status && data.errors){

                        return;
                    }
                },
                error:(a,b,c)=>{
                    save.prop('disabled', false);
                    save.html(save_html);
                    console.log(a+','+b+','+c);
                }
            });
        });
    }

    if (eventFlag) {

    }

    function add_event_flag(flag) {

    }

    $('.rem-pl').on('click', function(){
        var pl_id = parseInt($(this).attr('pl-id'), 10);
        remove_from_roster(pl_id);
    });

    function remove_from_roster(id){
        $.ajax({
            url:`${ajax_url}event-ajax.php`,
            type:'post',
            data:{'action':'remove_roster', 'event_id':e_id, 'pl_id':id, 'csrf':$('#csrf').val()},
            dataType:'json',
            success:(data)=>{
                console.log(data);
                if(!data.status){
                    //error
                    return;
                }

                window.location.reload();
            },
            error:(a,b,c)=>{
                console.log(a+','+b+','+c);
            }
        });
    }

    function add_to_roster(team){
        if (team!=='away'&&team!=='home'){
            console.log('cant');
            return;
        }
        var team_id = $(`#${team}-team-id`).val();
        var pl_id = $(`#temp-${team}`).val();

        $.ajax({
            url:`${ajax_url}event-ajax.php`,
            type:'post',
            data:{'action':'add_roster', 'event_id':e_id, 'pl_id':pl_id, 'team_id':team_id, 'csrf':$('#csrf').val()},
            dataType:'text',
            success:(data)=>{
                console.log(data);
                if(!data.status){
                    //error
                    return;
                }

                window.location.reload();
            },
            error:(a,b,c)=>{
                console.log(a+','+b+','+c);
            }
        });
    }

    $('.btn-add-home').on('click', function(){
        add_to_roster('home');
    });

    $('.btn-add-away').on('click', function(){
        add_to_roster('away');
    });

    $.ajax({
        url:`${ajax_url}event-ajax.php`,
        type:'get',
        data:{'action':'stats', 'event_id':e_id, 'csrf':$('#csrf').val()},
        dataType:'json',
        async: true,
        success:(data)=>{
            console.log(data);
            var p = data.p ?? 0;
            
            if (data.errors){
                alert(data.errors);
                return;
            }

            /**
             * construct header (team logos & names)
             */

            var h_name = $('<p>', {
                text: `${data.home.t_name} (${data.home.record.wins} - ${data.home.record.losses})`
            });
            var h_logo = $('<img>', {
                src: data.home.logo
            }).attr('width', data.img.width)
            .attr('height', data.img.height)
            .addClass('img-ref');

            var a_name = $('<p>', {
                text: `${data.away.t_name} (${data.away.record.wins} - ${data.away.record.losses})`
            });
            var a_logo = $('<img>', {
                src: data.away.logo
            }).attr('width', data.img.width)
            .attr('height', data.img.height)
            .addClass('img-ref');

            $('.home-team').append(h_logo).append(h_name);
            $('.away-team').append(a_logo).append(a_name);

            $('#home-score').val(data.home.score);
            $('#away-score').val(data.away.score);



            /**
             * construct stat table headers
             */

            var cols = data.cols;
            //cols.unshift({ id: 0, name: 'IGN' });
            cols.unshift({ id: 0, name: 'Player'});

            for (var i = 0; i < cols.length; i++) {
                var c = cols[i];
                
                $('#thead-home').append($('<th>', {
                    text: c.name
                }));
                $('#thead-away').append($('<th>', {
                    text: c.name
                }));
            }

            /**
             * fill in players & stats
             */

            var _s = [...data.stats];
            for (var j = 0; j < data.players.length; j++){
                var pl = data.players[j];
                var t = Array(data.cols.length-1).fill(0);
                for (var k = 0; k < _s.length; k++){ 
                    if (_s[k].user_id!==pl.user_id){
                        continue;
                    }
                    console.log(data.cols);
                    const m = data.cols.map(e => e.id);
                    var idx = m.indexOf(_s[k].stat_id);
                    t[idx-1] = _s[k].stat_value;
                    _s.splice(k, 1);
                    k--;
                }
                t.unshift(pl.ign);
                var tbl = pl.subteam_id === data.home.t_id ? 'home' : 'away';
                var tr = $('<tr>');
                for(var l = 0; l < t.length; l++){
                    var add = $('<td>', {
                        text: t[l]
                    });

                    if(data.p&&l){
                        console.log(t);
                        console.log(cols);
                        console.log(cols[l].id + ',' + l);
                        add = $('<td>').append(`<input type="text" value="${t[l]}" class="st-mod" user-id="${pl.user_id}" stat-id="${cols[l].id}">`);
                    }
                    tr.append(add);
                }
                $(`#tbody-${tbl}`).append(tr);
            }

            $('.home').toggleClass('loading c-auto');
            $('.show-onload').show();
        },
        error:(a,b,c)=>{
            console.log(a+','+b+','+c);
        }
    });
});