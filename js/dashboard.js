(function(){
    $(document).ready(function(){

        /**
         * team manager
         */

        $('.copy-code').on('click', function(){
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

        $('#add-team').on('click', function(){
            $('#add-team').toggleClass('m-cancel');
            if (!$('#add-team').hasClass('m-cancel')){ //add mode
                $('.game-time[st-id="pending"]').remove();
                return;
            }

            //cancel mode

            const len = $('.game-time').length;
            var add = $('<div>', {

            })
            .attr('st-id', 'pending')
            .attr('id', `subteam-${len}`)
            .addClass('game-time');
            $('.game-times').append($());
        });

        $('.game-time > i.bxs-checkbox-minus').on('click', function(){ //remove team
            remove_team($(this));
        });
        
        async function remove_team(ctrl){
            var div = ctrl.prev();
            var gam = div.prev();
            var s_id = ctrl.parent().attr('st-id');

            if (!div.val() || !gam.val()) {
                ctrl.parent().remove();
                return;
            }

            $.ajax({
                url:`${ajax_url}tm-db-ajax.php`,
                type:'post',
                data:{'action':'delete', 'csrf':$('#csrf').val(), 'st_id':s_id, 'game_id':gam.val(), 'div':div.val()},
                dataType:'json',
                success:(data)=>{
                    if (!data.status || data.errors){
                        var e_m = '';
                        data.errors.forEach(e => {
                            e_m+=e;
                        });
                        console.log(`Error: ${e_m}`);
                        return;
                    }

                    console.log(data.success);
                    ctrl.parent().remove();
                },
                error:(a,b,c)=>{
                    console.log(a+','+b+','+c);
                }
            });

            console.log(gam.val() + ',' + div.val() + ',' + s_id);
        }

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