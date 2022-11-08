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
                console.log(data);
                if (!data.status){
                    //error
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
                    console.log(data);
                    if(!data.status){
                        //error
                        return;
                    }

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
                        });
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
                                <td>${i}</td>
                                <td>${e.name}</td>
                                <td>${e.wins}</td>
                                <td>${e.losses}</td>`
                        });

                        const idx = st.stats.map(f => f.st_id).indexOf(e.st_id);
                        const s = st.stats[idx].stats;
                        s.forEach(f => {
                            tr.append($('<td>', {
                                text: f.stat_total
                            }));
                        });

                        tb.append(tr);
                    });
                },
                error:(a,b,c)=>{
                    console.log(a+','+b+','+c);
                }
            });
        }

        $('#div').on('change', function(){
            get_stnd();
        });
    });
})();