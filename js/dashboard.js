(function(){
    $(document).ready(()=>{
        $('.copy-code').on('click', ()=>{
            navigator.clipboard.writeText($('#schoolcode').attr('href'));
            $('.copy-code').html('<i class="bx bxs-check-square"></i>')
            .css('color', '#36d660');
        });

        $('td.user-col').on('click', (e)=>{
            const name = $(e.target).text();
            const uname = $(e.target).attr('username');
            const id = $(e.target).attr('user-id');

            $('.empty-player').hide();
            $('.player-info').show();
            $('.set-teams').show();
            $('#p-name').html(`<strong>Name:</strong> ${name}`);
            $('#p-uname').html(`<strong>Username:</strong> ${uname}`);
        });

        $('.save-teams').on('click', ()=>{
            save_teams();
        });

        async function save_teams(){
            let teams=[];

            $('.game-time').each(function(){
                
                console.log(`#${$(this).attr('id')} > select.sel-gam`);
                let o = {
                    'game_id': $(this).children('select.sel-gam').val(),
                    'div':$(this).children('select.sel-div').val()
                };
                teams.push(o);
            });

            $.ajax({
                url:`${ajax_url}tm-db-ajax.php`,
                type:'post',
                data:{'action':'save_teams', 'teams':JSON.stringify(teams), 'csrf':$('#csrf').val()},
                dataType:'text',
                success:(data)=>{
                    console.log(data);
                },
                error:(a,b,c)=>{
                    console.log(a+','+b+','+c);
                }
            });
        }
    });
})();