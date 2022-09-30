(function() {
    $(document).ready(function (){

        //fill in 'current event' box
        $.ajax({
            url:`${ajax_url}events-ajax.php`,
            type:'get',
            data:{'action': 'get_current', 'csrf':$('#csrf').val()},
            dataType:'json',
            success:(data)=>{

                console.log(data);
                if (!data.status){
                    //error
                    return;
                }

                if (data.now){
                    $('.watch-now').on('click', function(){
                        window.open(data.now.event_stream, '_blank');
                    });
                }else if (data.next) {
                    $('.ce-header > h2').text('Next event begins:');
                    $('.watch-now').hide();
                    $('.matchup img').remove();
                    var countDownDate = new Date(`${data.next.event_date} ${data.next.event_time}`).getTime();
                    var myfunc = setInterval(function() {
                        const now = new Date().getTime();
                        const timeleft = countDownDate - now;
                            
                        const days = Math.floor(timeleft / (1000 * 60 * 60 * 24));
                        const hours = Math.floor((timeleft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        const minutes = Math.floor((timeleft % (1000 * 60 * 60)) / (1000 * 60));
                        const seconds = Math.floor((timeleft % (1000 * 60)) / 1000);
                        
                        $('.matchup > h2').text(`${days}d ${hours}h ${minutes}m ${seconds}s`);
                        }, 1000);
                }
            },
            error:(a,b,c)=>{
                console.log(a+','+b+','+c);
            }
        });

        get_game_today(1);

        function get_game_today(game) {
            $.ajax({
                url:`${ajax_url}events-ajax.php`,
                type:'get',
                data:{'action':'get_today', 'game':game, 'csrf':$('#csrf').val()},
                dataType:'json',
                success:(data)=>{
                    if (!data.status){
                        //error
                        return;
                    }

                    if (data.events.length > 0){
                        $('.today-nogames').hide();
                        $('.table-today').show();

                        const tb = $('.table-today-tbody');
                        tb.html('');
                        data.events.forEach(e =>{
                            var res = 'TBD';
                            if (e.event_winner === e.h_id){
                                res = '1 - 0';
                            } else {
                                if (e.event_winner!==0){
                                    res = '0 - 1';
                                }
                            }
                            var tr = $('<tr>', {
                                html:`<td>${fix_time(e.event_time)}</td><td>${e.event_home}</td><td>${e.event_away}</td><td>${e.division}</td><td>${res}</td><td><a href="${e.event_stream}"><i class='bx bxl-twitch'></i></a></td>`
                            });

                            console.log(tr);
                            tb.append(tr);
                        });
                    } else {
                        $('.today-nogames').show();
                        $('.table-today').hide();
                    }
                },
                error:(a,b,c)=>{
                    console.log(a+','+b+','+c);
                }
            });
        }

        $('.game-icon').on('click', function(){
            $('.game-icon').removeClass('selected');
            $(this).addClass('selected');

            var game = parseInt($(this).attr('game-id'), 10);
            get_game_today(game);
            
        });
    });
})();