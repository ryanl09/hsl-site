(function() {
    $(document).ready(function (){

        //fill in 'current event' box
        $.ajax({
            url:ajaxurl,
            type:'get',
            data:{'page': 'events', 'action': 'get_current', 'csrf':$('#csrf').val()},
            dataType:'json',
            success:(data)=>{

                if (!data.status){
                    show_error(data.errors);
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
                    // var myfunc = setInterval(function() {
                    //     const now = new Date().getTime();
                    //     const timeleft = countDownDate - now;
                            
                    //     const days = Math.floor(timeleft / (1000 * 60 * 60 * 24));
                    //     const hours = Math.floor((timeleft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    //     const minutes = Math.floor((timeleft % (1000 * 60 * 60)) / (1000 * 60));
                    //     const seconds = Math.floor((timeleft % (1000 * 60)) / 1000);
                        
                    //     $('.matchup > h2').text(`${days}d ${hours}h ${minutes}m ${seconds}s`);
                    //     }, 1000);
                }
            },error:(a,b,c)=>{
                report_error('events', a+','+b+','+c, 'get_current');
            }
        });

        get_game_today(1);

        function get_game_today(game) {
            $.ajax({
                url:ajaxurl,
                type:'get',
                data:{'page': 'events', 'action':'get_today', 'game':game, 'csrf':$('#csrf').val()},
                dataType:'json',
                success:(data)=>{
                    const tb = $('.table-today-tbody');
                    tb.html('');
                    if (!data.status){
                        show_error(data.errors);
                        return;
                    }

                    if (data.events.length > 0){
                        $('.today-nogames').hide();
                        $('.table-today').show();

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

                            tr.on('click', function(){
                                window.open(`https://tecesports.com/event/${e.event_id}`, '_blank');
                            });

                            tb.append(tr);
                        });
                    } else {
                        $('.today-nogames').show();
                        $('.table-today').hide();
                    }
                },error:(a,b,c)=>{
                    report_error('events', a+','+b+','+c, 'get_today');
                }
            });
        }

        $('.default-view').on('click', function(){
            if (!$(this).hasClass('selected')){
                $(this).addClass('selected');
                $('.calendar-view').removeClass('selected');
                $('.mid-section').show();
                $('.mid-section2').hide();
            }
        });

        $('.calendar-view').on('click', function(){
            if (!$(this).hasClass('selected')){
                $(this).addClass('selected');
                $('.default-view').removeClass('selected');
                $('.mid-section').hide();
                $('.mid-section2').show();
            }
        });
        /**
         * populate teams select
         */

        fetch_teams(1);

        function fetch_teams(game){
            var div = $('#sort-div').val();
            $.ajax({
                url:ajaxurl,
                type:'get',
                data:{'page': 'events', 'action':'get_teams','game':game,'div':div, 'csrf':$('#csrf').val()},
                dataType:'json',
                success:(data)=>{
                    if (!data.status){
                        show_error(data.errors);
                        return;
                    }
                    const val = $('#sort-team').val();
                    $('#sort-team').html('<option value="-1">Any team</option>');
                    data.teams.forEach(e => {
                        let opt = $('<option>',{
                            value:e.subteam_id,
                            text:`${e.team_name} ${e.tag}`
                        });
                        $('#sort-team').append(opt);
                    });
                    //$('#sort-team').val(val);
                },error:(a,b,c)=>{
                    report_error('events', a+','+b+','+c, 'get_teams');
                }
            });
        }

        $('#sort-team').on('change', function(){
            var game = parseInt($('.e-all .game-icon.selected').attr('game-id'),10);
            $('#sort-time').val('all');
            all_events_sort(game);
        });

        $('#sort-div').on('change', function(){
            var game = parseInt($('.e-all .game-icon.selected').attr('game-id'),10);
            all_events_sort(game);
            fetch_teams(game);
        });

        $('#sort-time').on('change', function(){
            var game = parseInt($('.e-all .game-icon.selected').attr('game-id'),10);
            all_events_sort(game);
        });
        
        all_events_sort(1);

        function all_events_sort(game){
            $.ajax({
                url:ajaxurl,
                type:'get',
                data:{'page': 'events', 'action':'all_events','sort-team':$('#sort-team').val(),'sort-div':$('#sort-div').val(), 'game':game, 'time': $('#sort-time').val(), 'csrf':$('#csrf').val()},
                dataType:'json',
                success:(data)=>{
                    if (!data.status){
                        show_error(data.errors);
                        return;
                    }

                    const tb = $('.table-all-tbody');
                    tb.html('');

                    if (data.events.length > 0){  
                        //var mySpans = new Array();                 
                        //var tempSpans = document.getElementsByClassName('calendar-date-text');
                        //mySpans = Array.from(tempSpans);
                        //console.log(mySpans);

                        data.events.forEach(e =>{
                            var res = 'TBD';
                            if (e.event_winner!==0){
                                res = `${e.home_score} - ${e.away_score}`;
                            }
                            var tr = $('<tr>', {
                                html:`<td>${fix_date(e.event_date)}</td><td>${fix_time(e.event_time)}</td><td>${e.event_home} ${e.home_tag}</td><td>${e.event_away} ${e.away_tag}</td><td>${e.division}</td><td>${res}</td><td><a href="${e.event_stream}"><i class='bx bxl-twitch'></i></a></td>`
                            });

                            tr.on('click', function(){
                                window.open(`https://tecesports.com/event/${e.event_id}`, '_blank');
                            });

                            // [0] year, [1] month, [2] day
                            /*var arr = e.event_date.split("-");
                            let currentDate = new Date();
                            const month = currentDate.getMonth() + 1;
                            const year = currentDate.getFullYear();
                            if (month == arr[1] && year == arr[0]) {
                                //console.log(arr[0] + " " + arr[1] + " " + arr[2] + " is in current month");
                                var calendar_entry = document.createElement('div');
                                calendar_entry.classList.add("event");
                                switch (game) {
                                    case 1: // Rocket League
                                        calendar_entry.style.backgroundColor = '#001f7f';
                                        break;
                                    case 2: // Valorant
                                        calendar_entry.style.backgroundColor = '#ab0013';
                                        break;
                                    case 3: // Overwatch 2
                                        calendar_entry.style.backgroundColor = '#e78500';
                                        break;
                                    case 4: // League of Legends
                                        calendar_entry.style.backgroundColor = '#6a9c54';
                                        break;
                                    case 5: // Fortnite
                                        calendar_entry.style.backgroundColor = '#0085ff';
                                        break;
                                    case 6: // Super Smash Bros
                                        calendar_entry.style.backgroundColor = '#670000';
                                        break;
                                    case 7: // Multiversus
                                        calendar_entry.style.backgroundColor = '#ff2300';
                                        break;
                                }
                                calendar_entry.innerHTML = `${e.event_home} vs ${e.event_away} - ${fix_time(e.event_time)}`;
                                mySpans[parseInt(arr[2])-1].closest('div.day_num').appendChild(calendar_entry);
                            }*/
                            
                            tb.append(tr);
                        });
                    }
                },error:(a,b,c)=>{
                    report_error('events', a+','+b+','+c, 'all_events');
                }
            });
        }

        //all_events_calendar();

        function all_events_calendar(){
            $.ajax({
                url:ajaxurl,
                type:'get',
                data:{'page': 'events', 'action':'all_events_calendar','sort-team':$('#sort-team').val(),'sort-div':$('#sort-div').val(), 'time': $('#sort-time').val(), 'csrf':$('#csrf').val()},
                dataType:'json',
                success:(data)=>{
                    if (!data.status){
                        show_error(data.errors);
                        return;
                    }

                    if (data.events.length > 0){  
                        var mySpans = new Array();                 
                        var tempSpans = document.getElementsByClassName('calendar-date-text');
                        mySpans = Array.from(tempSpans);
                        //console.log(mySpans);

                        data.events.forEach(e =>{
                            var res = 'TBD';
                            if (e.event_winner!==0){
                                res = `${e.home_score} - ${e.away_score}`;
                            }

                            // [0] year, [1] month, [2] day
                            var arr = e.event_date.split("-");
                            let currentDate = new Date();
                            const month = currentDate.getMonth() + 1;
                            const year = currentDate.getFullYear();
                            if (month == arr[1] && year == arr[0]) {
                                //console.log(arr[0] + " " + arr[1] + " " + arr[2] + " is in current month");
                                var calendar_entry = document.createElement('div');
                                calendar_entry.classList.add("event");
                                switch (game) {
                                    case 1: // Rocket League
                                        calendar_entry.style.backgroundColor = '#001f7f';
                                        break;
                                    case 2: // Valorant
                                        calendar_entry.style.backgroundColor = '#ab0013';
                                        break;
                                    case 3: // Overwatch 2
                                        calendar_entry.style.backgroundColor = '#e78500';
                                        break;
                                    case 4: // League of Legends
                                        calendar_entry.style.backgroundColor = '#6a9c54';
                                        break;
                                    case 5: // Fortnite
                                        calendar_entry.style.backgroundColor = '#0085ff';
                                        break;
                                    case 6: // Super Smash Bros
                                        calendar_entry.style.backgroundColor = '#670000';
                                        break;
                                    case 7: // Multiversus
                                        calendar_entry.style.backgroundColor = '#ff2300';
                                        break;
                                }
                                calendar_entry.innerHTML = `${e.event_home} vs ${e.event_away} - ${fix_time(e.event_time)}`;
                                mySpans[parseInt(arr[2])-1].closest('div.day_num').appendChild(calendar_entry);
                            }
                        });
                    }
                },error:(a,b,c)=>{
                    report_error('events', a+','+b+','+c, 'all_events_calendar');
                }
            });
        }

        $('.e-today .game-icon').on('click', function(){
            $('.e-today .game-icon').removeClass('selected');
            $(this).addClass('selected');

            var game = parseInt($(this).attr('game-id'), 10);
            get_game_today(game);
            
        });

        $('.e-all .game-icon').on('click', function(){
            $('.e-all .game-icon').removeClass('selected');
            $(this).addClass('selected');

            $('#sort-team').val('-1');
            
            var game = parseInt($(this).attr('game-id'), 10);
            all_events_sort(game);
            fetch_teams(game);
            
        });
    });
})();