(function(){
    $(document).ready(function(){

        fetch_teams(1);
        fetch_stats(1);

        $('.game-icon').on('click', function(){
            $('.game-icon').removeClass('selected');
            $(this).addClass('selected');

            var game = parseInt($(this).attr('game-id'), 10);

            $('#team').val('-1');
            $('#div').val('1');

            fetch_teams(game);
            fetch_stats(game);
        });

        
        $('#top-stat').on('change', function(){
            get_top_pl();
        });

        $('#team').on('change', function(){
            const t = parseInt($('#team').val(), 10);
            if(t<0){
                $('.stats-tbody > tr').show();
                show_teams(true);
                $('[data-is="team"]').show();
                update_tr_bg();
                return true;
            }
    
            $(`tbody tr:not([team-id="${t}"])`).hide();
            show_teams(false);
            $(`tr[team-id="${t}"]`).show();

            update_tr_bg(t);
        });
    });

    function update_tr_bg(t=-1){
        if(t<0){
            $(`.stats-tbody tr:nth-child(even)`)
                .css('background', '#ffffff');
            $('.stats-tbody tr:nth-child(odd)')
                .css('background', '#e8f3f7');
            return;
        }
        $(`.stats-tbody tr[team-id="${t}"]:nth-of-type(even)`)
            .css('background', '#ffffff');
        $(`.stats-tbody tr[team-id="${t}"]:nth-of-type(odd)`)
            .css('background', '#e8f3f7');
    }
    
    /**
     * populate teams select
     */
    function fetch_teams(game){
        var div = $('#div').val();
        $.ajax({
            url:ajaxurl,
            type:'get',
            data:{'page':'events', 'action':'get_teams','game':game,'div':div, 'csrf':$('#csrf').val()},
            dataType:'json',
            success:(data)=>{
                if (!data.status){
                    show_error(data.errors);
                    return;
                }
                const val = $('#team').val();
                $('#team').html('<option value="-1">Any team</option>');
                data.teams.forEach(e => {
                    let opt = $('<option>',{
                        value:e.team_id,
                        text:`${e.team_name} ${e.tag}`
                    });
                    $('#team').append(opt);
                });
                //$('#sort-team').val(val);
            },error:(a,b,c)=>{
                report_error('stats', a+','+b+','+c, 'get_teams');
            }
        });
    }

    function show_teams(val){
        if(val){
            $('.team-col').show();
            $(`[data-is="team"]`).show();
            return;
        }
        $('.team-col').hide();
        $(`[data-is="team"]`).hide();
    }

    let td = (data) =>{
        return `<td>${data}</td>`;
    }

    let td_c = (data, k, v) =>{
        return `<td ${k}="${v}">${data}</td>`;
    }

    var _SORT_BY = -1;
    var _DATA = [];

    function process_stats(tb){
        tb.html('');
        for(var i = 0; i < _DATA.stats.length;i++){
            const e = _DATA.stats[i];
            var blocks = '';
            _DATA.cols.forEach(f => {
                blocks+=td(e.stats[e.stats.map(e => e.stat_id).indexOf(f.id)].stat_total);
            });
            const row = $('<tr>', {
                html:`${td(e.ign)}${td_c(e.team, 'data-is', 'team')}${blocks}`
            })
                .attr('team-id', e.team_id);
            tb.append(row);
        }
    }

    let do_sort = (d, by, state) => {
        if (state == 1) {
            for(let i = 0; i < d.length; i++){
                for(let j = i + 1; j < d.length; j++){
                    var k = d[i].stats.map(e => e.stat_id).indexOf(parseInt(by,10));
                    const x = parseInt(d[i].stats[k].stat_total);
                    const y = parseInt(d[j].stats[k].stat_total);
                    if(x<y){
                        const temp=d[i];
                        d[i]=d[j];
                        d[j]=temp;
                    }
                }
            }
        } else {
            for(let i = 0; i < d.length; i++){
                for(let j = i + 1; j < d.length; j++){
                    var k = d[i].stats.map(e => e.stat_id).indexOf(parseInt(by,10));
                    const x = parseInt(d[i].stats[k].stat_total);
                    const y = parseInt(d[j].stats[k].stat_total);
                    if(x > y){
                        const temp=d[i];
                        d[i]=d[j];
                        d[j]=temp;
                    }
                }
            }
        }
        return d;
    }
    
    function sort_table(){
    }

    /**
     * populate stats
     */

    function fetch_stats(game){
        var team = $('#team').val();
        var div = $('#div').val();
        $.ajax({
            url:ajaxurl,
            type:'get',
            data:{'page':'events', 'action':'get_all_stats','team':team, 'game':game,'div':div, 'csrf':$('#csrf').val()},
            dataType:'json',
            success:(data)=>{
                if (!data.status){
                    show_error(data.errors);
                    return;
                }
                const cols = data.cols;
                const stats = data.stats;
                const pl = data.players;
                const th = $('.stats-thead');

                th.html('');

                var tr = $('<tr>');
                tr.append($('<th>',{
                    text:'Player'
                }));
                var te = $('<th>',{
                    text:'Team'
                }).addClass('team-col');
                tr.append(te);

                const sel = $('#top-stat');
                sel.html('');
                cols.forEach(e => {
                    const th = $('<th>',{
                        html: `${e.name}<i class='bx bx-sort-alt-2 clickable sort' sort-by="${e.id}" state="0"></i>`
                    });
                    tr.append(th);

                    sel.append($('<option>', {text: e.name}).val(e.id));
                });
                th.append(tr);
                _DATA = data;

                

                $('.sort').on('click', function(){
                    let attribute = $(this).attr('state');
                    if (attribute == "1") {
                        $(this).attr('state', "2");
                    } else {
                        $(this).attr('state', "1");
                    }
                    const by = $(this).attr('sort-by');
                    const state = $(this).attr('state');

                    _SORT_BY = by;
                    sort_table();
                    _DATA.stats = do_sort(_DATA.stats, by, state);
                    process_stats($('.stats-tbody'));
                });

                process_stats($('.stats-tbody'));
                get_top_pl();
            },error:(a,b,c)=>{
                report_error('stats', a+','+b+','+c, 'get_all_stats');
            }
        });
    }


    function get_top_pl(){
        const game=parseInt($('.game-icon.selected').attr('game-id'),10);
        const stat_id=parseInt($('#top-stat').val(),10);
        const div=parseInt($('#div').val(),10);
        
        $.ajax({
            url:ajaxurl,
            type:'get',
            data:{'page': 'events', 'action':'get_top_players', 'game':game, 'div':div, 'stat_id':stat_id, 'csrf':$('#csrf').val()},
            dataType:'json',
            success:(data)=>{
                if (!data.status){
                    show_error(data.errors);
                    return;
                }

                const body = $('.top-tbody');
                body.html('');
                data.stats.forEach(e => {
                    const tr = $('<tr>');
                    tr.append($('<td>', { text: e.ign }).on('click', function(){
                        window.location=`https://tecesports.com/user/${e.username}`;
                    }));
                    tr.append($('<td>', { text: e.total }));
                    tr.append($('<td>', { text: e.team_name }));
                    body.append(tr);
                });
                
            },error:(a,b,c)=>{
                report_error('stats', a+','+b+','+c, 'get_top_players');
            }
        });
    }
})();