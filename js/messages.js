(function(){

    let AC = 0;

    const added = [];

    $(document).ready(function(){
        const es=new EventSource('https://tecesports.com/classes/util/chat.php');
        es.onmessage = function(event){
            const m = JSON.parse(event.data);
            if (m.length <= 0){
                return;
            }

            for (let i = 0;i  <m.length; i++){
                if (m[i].hasOwnProperty('message')){
                    if (AC === m[i].id_from && added.indexOf(m[i].id) === -1){
                        added.push(m[i].id);
                        make_chat(m[i].message, 0);
                    }
                }
            }
        }

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
                    cb.on('click', function(){
                        const uid = e.user_id;
                        $('.convo-box.selected').removeClass('selected');
                        $(this).addClass('selected');
                        if (AC !== uid){
                            get_convo(uid);
                            $('.pfp.active').css('background-image', `url(${e.pfp_url})`);
                            $('.msg-sender.active').text(e.username);
                        }
                        AC = uid;
                    });
                    c.append(cb);
                    c.append($('<hr>').addClass('sep'));
                });
            },
            error:(a,b,c)=>{
                console.log(a+','+b+','+c);
            }
        });
        
        $('.send-msg').on('click', function(){
            send_msg(AC);
            return false;
        });

        $('.msg').on('keypress', function(e){
            if (e.keyCode == 13){
                send_msg(AC);
                return false;
            }
        });
    });

    let make_convobox=(e)=>{
        const a = $('<div>')
            .addClass('convo-box')
            .attr('user-id', e.user_id);
        const b = [];
        b.push($('<div>')
            .addClass('pfp-wrap')
            .append($('<div>')
                .addClass('pfp')
                .css('background-image', `url(${e.pfp_url})`)));
        b.push($('<div>')
            .addClass('msg-info')
            .append($('<p>', { text: e.username })
                .addClass('msg-sender'))
            .append($('<p>', { text: e.message })
                .addClass('msg-prev')));
        b.push($('<div>')
            .addClass('msg-time')
            .append($('<p>', { text: fix_time(e.time_sent) })));
        b.push($('<div>')
            .addClass('msg-view')
            .append($('<i>')
                .addClass('bx bx-chevron-right')));
        b.forEach(f => {
            a.append(f);
        });
        return a;
    }

    function get_convo(uid) {
        $.ajax({
            url:ajaxurl,
            type:'get',
            data:{'page':'messages', 'action':'get_convo','user_id':uid, 'csrf':$('#csrf').val()},
            dataType:'json',
            success:(data)=>{
                clear_chats();

                for (let i = data.convo.length - 1; i >= 0; i--){
                    const c = data.convo[i];
                    make_chat(c.message, c.is_mine);
                }

                console.log(data);
            },
            error:(a,b,c)=>{
                console.log(a+','+b+','+c);
            }
        });
    }

    function send_msg(uid){
        const msg = $('.msg').val();
        $('.msg').val('');
        if (msg.trim().length === 0){
            return;
        }

        $.ajax({
            url:ajaxurl,
            type:'post',
            data:{'page':'messages','action':'send_msg','to':uid,'msg':msg, 'csrf':$('#csrf').val()},
            dataType:'json',
            success:(data)=>{
                if (!data.status){
                    //error
                    return;
                }
                make_chat(msg, 1);
            },
            error:(a,b,c)=>{
                console.log(a+','+b+','+c);
            }
        });
    }

    function make_chat(msg, mine) {
        const cm = $('.chat-m');
        const a = mine ? 'right': 'left';
        cm.append($('<div>')
            .addClass('chat-msg')
            .append($('<span>', { text: msg })
                .addClass(a)));
                
        cm.animate({
            scrollTop: cm[0].clientHeight
            },
            10,
            "swing"
        );
    }

    function clear_chats(){
        const cm = $('.chat-m');
        cm.html('');
    }


})();