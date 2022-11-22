(function() {
    $(document).ready(()=>{

        /*
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
                        console.log(this.responseText);
                        var data = JSON.parse(this.responseText);
                        if (!data.status){
                            console.log(data.errors);
                            return;
                        }

                        console.log(data);

                        var img = data.url;
                        $('.pfp').css('background', `url(${img}) 50% 50% no-repeat`);
                        $('.image-text > .image > img').attr('src', img);
                        //https://cdn.searchenginejournal.com/wp-content/uploads/2022/06/image-search-1600-x-840-px-62c6dc4ff1eee-sej-1280x720.png
                    }
                }
                xhr.send(formData);
            }
        
                console.log('submitted');

            $('#pfp-form').submit();
        });*/

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
    });

    async function get_tab(tab){
        console.log(tab);
        $.ajax({
            type: 'get',
            url: ajaxurl,
            data: {'page':'team', 'action':`get_${tab}_tab`, 'team':$('#team').val(),'csrf':$('#csrf').val() },
            dataType: 'json',
            async: true,
            success:(data)=>{
                parse_data(tab,data);
            },error:(a,b,c)=>{
                report_error('team', a+','+b+','+c, `get_${tab}_tab`);
            }
        });
    }

    function get_players(st_id){
        $.ajax({
            url:ajaxurl,
            type:'get',
            data:{'page':'team','action':'get_players','st_id':st_id,'csrf':$('#csrf').val()},
            dataType:'json',
            success:(data)=>{
                if(!data.status){
                    //error
                    return;
                }
                const p = $('#ucmatches');
                p.html('');
                data.players.forEach(e => {
                    const u = $('<td>', {text: e.username });
                    const i = $('<td>', {text: (e.ign ?? 'Not set') });
                    const tr = $('<tr>')
                        .append(u)
                        .append(i)
                        .on('click', function(){
                            window.open(`https://tecesports.com/user/${e.username}`, '_blank');
                        });
                    p.append(tr);
                });

            },error:(a,b,c)=>{
                report_error('team', a+','+b+','+c, 'get_players');
            }
        });
    }

    function parse_data(tab, dat){
        switch(tab){
            case 'info':
                $('.loading.box-info').remove();
                if (!dat.status) {
                    document.getElementsByClassName('page-content')[0].insertAdjacentHTML('beforeend', `<p>${dat}</p>`);
                    return;
                }

                var data = dat.data;

                const ts = $('.team-seasons');
                data.seasons.forEach(e => {
                    ts.append($('<tr>')
                        .append($('<td>', {text: e.season_name})));
                });

                if (!data.games) {
                    $('#games-info').html('<div><p class="nogames">None</p></div>');
                } else {
                    let idx=0;
                    data.games.forEach(e => {
                        let c ='';
                        if(!idx){
                            c=' game-sel';
                            get_players(e.id);
                        }
                        idx++;
                        const d = $('<div>')
                            .addClass(`games-entry${c}`)
                            .on('click', function(){
                                $('.games-entry.game-sel').removeClass('game-sel');
                                $(this).addClass('game-sel');
                                const st_id = e.id;
                                get_players(st_id);
                            })
                            .append($('<img>')
                                .attr('src', e.url)
                                .attr('width', '24')
                                .attr('height', '24'))
                            .append($('<p>', {text: `${e.game_name} - D${e.division}`}));

                        $('#games-info').append($('<div>', {
                            class: 'info end'
                        })
                        .append(d));

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
})();