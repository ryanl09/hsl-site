(()=> {
    $(document).ready(()=>{

        get_tab_info();

        const e =$('#edit'); //mode=0
        const p =$('#prev'); //mode=1
        const s =$('#save-changes');

        //observer for changes to update edit => save
        changes = {
            cInternal: [],
            cListener: function(val) {},
            set c(val) {
                this.cInternal = val;
                this.aListener(val);
            },
            get c() {
                return this.cInternal;
            },
            registerListener: function(listener) {
                this.cListener = listener
            }
        }

        changes.registerListener(function(val) {
            if (val) { //if a change was made, show save
                e.text('Save');
                e.html('<i class="bx bx-save"></i>');
                e.toggleClass('save-profile-btn');
                return;
            }
            //else show edit (if change was undone? not implemented yet)

        });

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
            const _t = tab.split('-');
            tabs.removeClass('selected');
            e.target.classList.add('selected');
            $('.--tab').hide();
            $(`.${_t[1]}-${_t[0]}`).show();
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

    async function get_tab_info() {
        $.ajax({
            type: 'get',
            url: `${ajax_url}get-profile-ajax.php`,
            data: { 'tab': 'info', 'csrf':$('#csrf').val() },
            dataType: 'text',
            success:(data)=>{
                if (!data.status) {

                    $('.loading.box-info').remove();
                    document.getElementsByClassName('page-content')[0].insertAdjacentHTML('beforeend', `<p>${data}</p>`);
                    return;
                }

                $('.loading.box-info').remove();
            },
            error:(a,b,c)=>{

            }
        });
    }

    async function get_tab_stats() {

    }

    async function get_tab_highlights() {

    }

    function update_display(m) {
        if(m){//preview mode
            $('.e-c').hide();
            $('.p-c').show();
            return;
        }

        $('.e-c').show();
        $('.p-c').hide();
    }

    var _LIKED = 0;
    var _FOLLOWED = 0;

    /**
     * ui functions
     */

    function like() {

    }
})();