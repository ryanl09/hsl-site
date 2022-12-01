(function(){
    $(document).ready(function(){

        /**
         * get games on page load
         */

        $.ajax({
            url:ajaxurl,
            type:'get',
            data:{'page':'games','action':'get_all','csrf':$('#csrf').val()},
            dataType:'json',
            success:(data)=>{
                if (!data.status){
                    show_error(data.errors);
                    return;
                }

                const pt = $('.profile-tabs');
                let i = 0;
                data.games.forEach(e=>{
                    const btn = $('<button>', {html:`<img src="${e.url}" width="15" height="15">${e.game_name}`})
                        .addClass('game-btn')
                        .attr('game-id', e.id)
                        .css('display', 'flex')
                        .css('align-items', 'center')
                        .css('gap', '6px')
                        .css('font-size', '14px')
                        .css('padding', '8px 18px');
                    if(!i){
                        btn.addClass('selected');
                    }
                    btn.on('click', function(){
                        $('.game-btn.selected').removeClass('selected');
                        $(this).addClass('selected');
                        $('#div').val(1);
                        get_stnd();
                    });
                    pt.append(btn);
                    i++;
                });
        
                get_stnd();
            },error:(a,b,c)=>{
                report_error('standings', a+','+b+','+c, 'get_all');
            }
        });

        function get_stnd(){
            const game_id = parseInt($('.game-btn.selected').attr('game-id'), 10);
            const div = parseInt($('#div').val(),10);

            $.ajax({
                url:ajaxurl,
                type:'get',
                data:{'page':'standings','action':'get_standings',
                    'game_id':game_id,'div':div,'csrf':$('#csrf').val()},
                dataType:'json',
                success:(data)=>{
                    if(!data.status){
                        show_error(data.errors);
                        return;
                    }

                    console.log(data);

                    const st = data.standings;

                    const th = $('.stnd-thead');
                    th.html(`
                        <th>Rank</th>
                        <th>Team</th>
                        <th>Wins</th>
                        <th>Losses</th>`);
                    st.cols.forEach(e => {
                        const _th = $('<th>',{
                            text: e.name
                        }).addClass('is-stat');
                        th.append(_th);
                    });

                    if (!st.stats){
                        return;
                    }

                    const tb = $('.stnd-tbody');
                    tb.html('');
                    let i = 0;
                    st.recs.forEach(e=>{
                        i++;
                        const tr = $('<tr>',{
                            html:`
                                <td label="Rank">${i}</td>
                                <td label="Team">${e.name}</td>
                                <td label="Wins">${e.wins}</td>
                                <td label="Losses">${e.losses}</td>`
                        });

                        const idx = st.stats.map(f => f.st_id).indexOf(e.st_id);
                        const n = st.cols.map(f => f.id);
                        const s = st.stats[idx].stats;
                        s.forEach(f => {
                            tr.append($('<td>', {
                                text: f.stat_total
                            }).attr('label', st.cols[n.indexOf(f.stat_id)].name)
                                .addClass('is-stat'));
                        });

                        tb.append(tr);
                    });
                },error:(a,b,c)=>{
                    report_error('standings', a+','+b+','+c, 'get_standings');
                }
            });
        }

        $('#div').on('change', function(){
            get_stnd();
        });
    });
})();