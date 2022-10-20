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
        for(var i = 0; i < _DATA.stats.length;i++){
            const e = _DATA.stats[i];
            var blocks = '';
            _DATA.cols.forEach(f => {
                blocks+=td(e.stats[e.stats.map(e => e.stat_id).indexOf(f.id)].stat_total);
            });
            const row = $('<tr>', {
                html:`${td(e.ign)}${td(e.team)}${blocks}`
            });
            tb.append(row);
        }
    }

    let do_sort = (d, by) => {
        for(let i = 0; i<d.length;i++){
            for(let j=i+1;j<d.length;j++){
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

                $('.sort').on('click', function(){
                    const by = $(this).attr('sort-by');
                    _SORT_BY = by;
                    sort_table();
                    _DATA.stats = do_sort(_DATA.stats, by);
                    process_stats($('.stats-tbody'));
                });

                process_stats($('.stats-tbody'));
            },
            error:(a,b,c)=>{
                console.log(a+','+b+','+c);
            }
        });
    }
})();