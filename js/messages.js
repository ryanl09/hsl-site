(function(){

    let AC = 0;

    $(document).ready(function(){
        /*const es=new EventSource('https://tecesports.com/classes/util/chat.php');
        console.log('setup');
        es.onopen = function(){
            console.log('connected');
        }
        es.onmessage = function(event){
            console.log(event.data);
        }*/

        $.ajax({
            url:ajaxurl,
            type:'get',
            data:{'page':'messages','action':'get_convos','csrf':$('#csrf').val()},
            dataType:'json',
            success:(data)=>{
                console.log(data);
                if (!data.status){
                    //error
                    return;
                }

                const c = $('.convos');
                data.convos.forEach(e => {
                    const cb = make_convobox(e);
                    c.append(cb);
                });

                init_convos();
            }
        });
    });

    function init_convos(){
        $('.convo-box').on('click', function(){
            AC = parseInt($(this).attr('user-id'), 10);
            $('.convo-box.selected').removeClass('selected');
            $(this).addClass('selected');
        });
    }




})();