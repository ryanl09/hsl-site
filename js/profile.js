(function() {
    $(document).ready(()=>{
        $('#edit-pfp').on('click', function(e){
            e.preventDefault();
            $('#fileToUpload').click();
        });
            
        $('#fileToUpload').change(function() {
            var file = $('#fileToUpload')[0].files;
            if (file.length > 0){
                var formData = new FormData();
                formData.append("fileToUpload", file[0]);

                var xhr = new XMLHttpRequest;
                xhr.open('post', 'https://tecesports.com/ajax/upload-pfp-ajax.php', true);
                xhr.onreadystatechange=function(){
                    if (this.readyState===4 && this.status===200){
                        var response = this.responseText;
                        console.log(response);
                    }
                }
                xhr.send(formData);
            }
        
                console.log('submitted');

            $('#pfp-form').submit();
        });

        const win = $(window);
        const bio_e = $('.bio-text-edit') ?? 0;

        var got = {
            'info': 0,
            'stats': 0,
            'highlights': 0
        }

        var calc_bio_h = () => {
            var w = bio_e.width();
            var h = -1 * Math.sqrt(60 * w) + 320;
            if(h >160) {
                h=160;
            }
            if(h<62) {
                h=62;
            }
            return Math.round(h);
        }

        if(bio_e){
            win.resize(()=>{
                bio_e.height(calc_bio_h());
            });
        }

        get_tab('info');
        got['info']=1;

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

            if (!got[_t[1]]){
                get_tab(_t[1]);
                got[_t[1]]=1;
            }

            $(`.${_t[1]}-${_t[0]}`).show();
        });

        async function user_actions() {

        }
    });

    async function get_tab(tab){
        console.log(tab);
        $.ajax({
            type: 'get',
            url: `${ajax_url}get-profile-ajax.php`,
            data: { 'tab': 'info', 'csrf':$('#csrf').val() },
            dataType: 'json',
            async: true,
            success:(data)=>{
                parse_data(tab,data);
            },
            error:(a,b,c)=>{
                console.log(a+','+b+','+c);
            }
        });
    }

    function parse_data(tab, dat){
        switch(tab){
            case 'info':
                console.log(dat);
                $('.loading.box-info').remove();
                if (!dat.status) {
                    document.getElementsByClassName('page-content')[0].insertAdjacentHTML('beforeend', `<p>${dat}</p>`);
                    return;
                }

                var data = dat.data;

                $('.bio-text').text(data.bio);

                if ($('.bio-text-edit')) {
                    $('.bio-text-edit').text(data.bio);
                }

                $('#student-school-value').text(data.school);

                if (!data.grad_year) {
                    $('.grad-year-info').remove();
                } else {
                    $('#grad-year-value').text(data.grad_year);
                }

                if (!data.twitch_username) {
                    $('.twitch-info').remove();
                } else {
                    $('#twitch-value').attr('href', data.twitch_href);
                    $('#twitch-value').text(data.twitch_href);
                }

                if (!data.games) {
                    $('#games-info').html('<div><p class="nogames">None</p></div>');
                } else {
                    data.games.forEach(e => {
                        $('#games-info').append($('<div>', {
                            class: 'info',
                            html: `<div class="games-entry"><img src="${e.url}" width="24" height="24"><p>${e.game_name}<p></div>`
                        }));
                    });
                }

                if (dat.events){
                    var uc = $('#ucmatches');
                    dat.events.forEach(e => {
                        var row = $(document.createElement('tr'));
                        //console.log(data.games.map(object => object.id));
                        var idx = data.games.map(object => object.id).indexOf(e.event_game);
                        var url=data.games[idx].url;
                        row.append(`<td><img src="${url}" width="24" height="24"></td><td>${fix_date(e.event_date)}</td><td>${fix_time(e.event_time)}</td>`);
                        uc.append(row);
                    });
                }

                $('.info-tab .tab .row .box').show();
                break;
            case 'stats':

                $('.stats-tab .tab .row .box').show();
                break;
            case 'highlights':
                break;
        }
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