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
        dataType:'text',
        async: true,
        success:(data)=>{
            console.log(data);
        },
        error:(a,b,c)=>{
            console.log(a+','+b+','+c);
        }
    });
});