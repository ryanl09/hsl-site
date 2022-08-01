(()=> {
    $(document).ready(()=>{
        const e =$('#edit'); //mode=0
        const p =$('#prev'); //mode=1
        const s =$('#save-changes');

        const changes = [];

        var _MODE = 0;

        e.on('click', ()=>{
            _MODE=0;
            e.addClass('c-mode');
            p.removeClass('c-mode');
            update_display(_MODE);
        });

        p.on('click', ()=>{
            _MODE=1;
            p.addClass('c-mode');
            e.removeClass('c-mode');
            update_display(_MODE);
        });

        s.on('click', ()=>{
            s.prop('disabled', true);
            $.ajax({
                type:'post',
                url:`${ajax_url}editpfp-ajax.php`,
                data:{},
                dataType:'json',
                success:(data)=>{
                    s.prop('disabled', false);
                },
                error:(a,b,c)=>{
                    s.prop('disabled', false);
                }
            });
        });

        const tabs = $('.profile-tabs > button');
        tabs.on('click', (e)=>{
            const tab = e.target.id;
            tabs.removeClass('selected');
            e.target.classList.add('selected');

            switch (tab) {
                case 'tab-info':
                    break;
                case 'tab-stats':
                    break;
                case 'tab-highlights':
                    break;
            }
        });

        /*
        * ui events *
        */

        /*
        const _l = $('#i-like');
        $('#like').hover(
            ()=>{ 
                _l.addClass('bxs-heart');
                $('#like > p').animate({width: 'toggle'}, 150);
            },
            ()=>{ 
                _l.removeClass('bxs-heart');
                $('#like > p').animate({width: 'hide'}, 1);
            }
        );
        const _f = $('#i-follow');
        $('#follow').hover(
            ()=>{ 
                _f.addClass('bxs-user-plus');
                $('#follow > p').animate({width: 'toggle'}, 150);
            },
            ()=>{ 
                _f.removeClass('bxs-user-plus');
                $('#follow > p').animate({width: 'hide'}, 1);
            }
        );
        const _d = $('#i-dm');
        $('#dm').hover(
            ()=>{ 
                _d.addClass('bxs-chat');
                $('#dm > p').animate({width: 'toggle'}, 150);
            },
            ()=>{ 
                _d.removeClass('bxs-chat');
                $('#dm > p').animate({width: 'hide'}, 1);
            }
        );
        const _r = $('#i-report');
        $('#report').hover(
            ()=>{ 
                _r.addClass('bxs-alarm-exclamation');
                $('#report > p').animate({width: 'toggle'}, 150);
            },
            ()=>{ 
                _r.removeClass('bxs-alarm-exclamation');
                $('#report > p').animate({width: 'hide'}, 1);
            }
        );
        const _b = $('#i-block');
        $('#block').hover(
            ()=>{
                $('#block > p').animate({width: 'toggle'}, 150);
            },
            ()=>{
                $('#block > p').animate({width: 'hide'}, 1);
            }
        );

        */
        async function user_actions() {

        }
    });

    function update_display(m) {
        if(m){//preview mode
            $('.edit-ctrl').attr('hidden', true);
            return;
        }


    }

    var _LIKED = 0;
    var _FOLLOWED = 0;

    /**
     * ui functions
     */

    function like() {

    }
})();