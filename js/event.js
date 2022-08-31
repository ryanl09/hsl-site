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

            if (data.errors){
                alert(data.errors);
                return;
            }

            /**
             * construct header (team logos & names)
             */

            var h_name = $('<p>', {
                text: data.home.name
            });
            var h_logo = $('<img>', {
                src: data.home.logo
            }).attr('width', data.img.width)
            .attr('height', data.img.height);

            var a_name = $('<p>', {
                text: data.away.name
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

            function place (obj, tbl) {
                var tr = $(document.createElement('tr'));
                tr.append('<td>', {

                });
                obj.forEach(e => {
                    tr.append($('<td>', {
                        text: ''
                    }));
                });
                $(`#tbody-${tbl}`).append(tr);
            }

            place(data.home.stats);
            place(data.away.stats);
        },
        error:(a,b,c)=>{
            console.log(a+','+b+','+c);
        }
    });
});