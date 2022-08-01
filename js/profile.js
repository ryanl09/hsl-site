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

        /*
        * ui events *
        */

        const _l = $('#i-like');
        $('#like').hover(
            ()=>{ _l.addClass('bxs-heart'); },
            ()=>{ _l.removeClass('bxs-heart'); }
        );
        const _f = $('#i-follow');
        $('#follow').hover(
            ()=>{ _f.addClass('bxs-user-plus'); },
            ()=>{ _f.removeClass('bxs-user-plus'); }
        );
        const _d = $('#i-dm');
        $('#dm').hover(
            ()=>{ _d.addClass('bxs-chat'); },
            ()=>{ _d.removeClass('bxs-chat'); }
        );
        const _r = $('#i-report');
        $('#report').hover(
            ()=>{ _r.addClass('bxs-alarm-exclamation'); },
            ()=>{ _r.removeClass('bxs-alarm-exclamation'); }
        );
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