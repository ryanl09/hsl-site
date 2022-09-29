(function() {
    $(document).ready(function (){
        $('.game-icon').on('click', function(){
            $('.game-icon').removeClass('selected');

            $(this).addClass('selected');

            var game = parseInt($(this).attr('game-id'), 10);

            $.ajax({
                url:``,
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
                        $('.table-today').html('');

                        var tb = $('table-today-tbody');
                        data.events.forEach(e =>{
                            var tr = $('<tr>', {
                                html:`<td>${fix_time(e.time)}</td><td>${e.home}</td><td>${e.away}</td><td>${e.division}</td><td><a href="${e.event_stream}"><i class='bx bxl-twitch'></i></a></td>`
                            });
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
        });
    });
})();