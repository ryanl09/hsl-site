$(document).ready(()=>{
    var e_id = window.location.pathname.split('/')[2] ?? 0;
    if (!e_id) {
        //invalid event
        return;
    }

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
                text: data.home.t_name
            });
            var h_logo = $('<img>', {
                src: data.home.logo
            }).attr('width', data.img.width)
            .attr('height', data.img.height);

            var a_name = $('<p>', {
                text: data.away.t_name
            });
            var a_logo = $('<img>', {
                src: data.away.logo
            }).attr('width', data.img.width)
            .attr('height', data.img.height);

            $('.home-team').append(h_logo).append(h_name);
            $('.away-team').append(a_logo).append(a_name);

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
                    t[_s[k].stat_id-1] = _s[k].stat_value;
                    _s.splice(k, 1);
                    k--;
                }
                t.unshift(pl.name);
                var tbl = pl.subteam_id === data.home.t_id ? 'home' : 'away';
                var tr = $('<tr>');
                for(var l = 0; l < t.length; l++){
                    var add = $('<td>', {
                        text: t[l]
                    });

                    if(p&&l){
                        add = $('<td>').append(`<input type="text" value="${t[l]}" class="st-mod" user-id="${pl.user_id}" stat-id="${l}">`);
                    }
                    tr.append(add);
                }
                $(`#tbody-${tbl}`).append(tr);
            }
        },
        error:(a,b,c)=>{
            console.log(a+','+b+','+c);
        }
    });
});