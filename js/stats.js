(function(){
    $(document).ready(function(){

        fetch_teams(1);
        fetch_stats(1);

        $('.game-icon').on('click', function(){
            $('.game-icon').removeClass('selected');
            $(this).addClass('selected');

            var game = parseInt($(this).attr('game-id'), 10);
            fetch_teams(game);
            fetch_stats(game);
        });
    });

    
    /**
     * populate teams select
     */
    function fetch_teams(game){
        var div = $('#div').val();
        $.ajax({
            url:`${ajax_url}events-ajax.php`,
            type:'get',
            data:{'action':'get_teams','game':game,'div':div, 'csrf':$('#csrf').val()},
            dataType:'json',
            success:(data)=>{
                console.log(data);
                if (!data.status){
                    //error
                    return;
                }
                $('#team').html('<option value="-1">Any team</option>');
                data.teams.forEach(e => {
                    let opt = $('<option>',{
                        value:e.subteam_id,
                        text:`${e.team_name} ${e.tag}`
                    });
                    $('#team').append(opt);
                });
                //$('#sort-team').val(val);
            },
            error:(a,b,c)=>{
                console.log(a+','+b+','+c);
            }
        });
    }

    function show_teams(val){
        if(val){
            $('.team-col').show();
            return;
        }
        $('.team-col').hide();
    }

    let td = (data) =>{
        return `<td>${data}</td>`;
    }

    var _SORT_BY = -1;
    var _DATA = [];

    function process_stats(tb){
        tb.html('');
        const keys = Object.keys(_DATA.stats);
        for(var i = 0; i < keys.length;i++){
            const e = _DATA.stats[keys[i]];
            var blocks = '';
            data.cols.forEach(f => {
                blocks+=td(e[f.id]);
            });
            const row = $('<tr>', {
                html:`${td(keys[i])}${td(e.team)}${blocks}`
            });
            tb.append(row);
        }
    }

    function _sort(){
        if ( a.stats[_SORT_BY] < b.stats[_SORT_BY] ){
            return -1;
        }
        if ( a.stats[_SORT_BY] > b.stats[_SORT_BY] ){
            return 1;
        }
        return 0;
    }
    
    function sort_table(){
        const tb = $('.stats-tbody');
        _DATA.stats.sort(_sort);
        process_stats(tb);
    }

    /**
     * populate stats
     */

    function fetch_stats(game){
        var team = $('#team').val();
        var div = $('#div').val();
        $.ajax({
            url:`${ajax_url}events-ajax.php`,
            type:'get',
            data:{'action':'get_all_stats','team':team, 'game':game,'div':div, 'csrf':$('#csrf').val()},
            dataType:'json',
            success:(data)=>{
                console.log(data);
                if (!data.status){
                    //error
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
                cols.forEach(e => {
                    const th = $('<th>',{
                        html: `${e.name}<i class='bx bx-sort-alt-2 clickable sort' sort-by="${e.id}"></i>`
                    });
                    tr.append(th);
                });
                th.append(tr);
                _DATA = data;

                $('.sort').each(function(){
                    const by = $(this).attr('sort-by');
                    _SORT_BY = by;
                    sort_table();
                });

                process_stats($('.stats-tbody'));
            },
            error:(a,b,c)=>{
                console.log(a+','+b+','+c);
            }
        });
    }
})();