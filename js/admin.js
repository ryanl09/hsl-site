(function(){
    $(document).ready(function() {

        const fb = $('.announcement-box-wrapper');

    window.onclick = function(admin){
        if (admin.target === fb[0]){
            fb.addClass('hide-box');
        }
    }

    $('.del-ann-btn').on('click', function(){
        $('.announcement-box-wrapper').removeClass('hide-box');
        get_announcements();
    });

        $('.tab-change').on('click', function(){
            const id = $(this).attr('tab-id');

            $('.tab').hide();
            $('.tab-change').removeClass('selected');
            $('.tab[tab--id='+id+']').show();
            $(this).addClass('selected');
        });

        $('.post-a').on('click', function() {
            $.ajax({
                url:ajaxurl,
                type:'post',
                data:{'page':'admin', 'action':'add_announcement', 'a-title':$('#a-title').val(), 'a-body': $('#a-body').val(), 'csrf':$('#csrf').val()},
                dataType:'json',
                success:(data)=>{
                    console.log(data);
                    if (!data.status){
                        //error
                        return;
                    }
                },
                error:(a,b,c)=>{
                    console.log(a+','+b+','+c);
                }
            });
        });

        function remove_announcement(id){
            $.ajax({
                url:ajaxurl,
                type:'post',
                data:{'page':'admin', 'action':'delete_announcement', 'announcement_id':id, 'csrf':$('#csrf').val()},
                dataType:'json',
                success:(data)=>{
                    console.log(data);
                    if (!data.status){
                        //error
                        return;
                    }
                    get_announcements();
                },
                error:(a,b,c)=>{
                    console.log(a+','+b+','+c);
                }
            });
        }

        function get_announcements(){
            $.ajax({
                url:ajaxurl,
                type:'post',
                data:{'page':'admin', 'action':'get_announcements', 'announcement_id':$('#announcement_id').val(), 'csrf':$('#csrf').val()}, 
                dataType:'json',
                success:(data)=>{
                    console.log(data);

                    if (!data.status){
                        //error
                        return;
                    }

                    $('.tab .announce').html('');
                    data.announcements.forEach(e => {
                        $('.tab .announce').append(`
                        <div class="box ann-box" announcement-id="${e.announcement_id}">
                            <div class="ann">
                                <h2 class="ann-title">${e.title}</h2>
                                <div class="ann-info">
                                    <div class="ann-author">
                                        <p class="author">${e.name}</p>
                                    </div>
                                    <p class="ann-time">${e.time}</p>
                                </div>
                            </div>
                        </div>`);
                        console.log(e);
                    });

                    $('.ann-box').on('click', function(){
                        var announcement_id = parseInt($(this).attr('announcement-id'), 10);
                        remove_announcement(announcement_id);
                    });
                },
                error:(a,b,c)=>{
                    console.log(a+','+b+','+c);
                }
            });
        }

        $('.btn-create').on('click', function(){
            $.ajax({
                url:ajaxurl,
                type:'post',
                data:{'page':'admin', 'action':'add_temp_pl', 'ign':$('#ign').val(), 'team': $('#team').val(), 'csrf':$('#csrf').val()},
                dataType:'json',
                success:(data)=>{
                    console.log(data);
                    if (!data.status){
                        //error
                        return;
                    }

                    $('#pl-id').val(data.id);
                },
                error:(a,b,c)=>{
                    console.log(a+','+b+','+c);
                }
            });
        });

        $('.btn-assign').on('click', function(){
            $.ajax({
                url:ajaxurl,
                type:'post',
                data:{'page':'admin', 'action':'allocate_temp_pl', 'id':$('#pl-id').val(), 'game':$('#game').val(), 'div':$('#div').val(), 'csrf':$('#csrf').val()},
                dataType:'text',
                success:(data)=>{
                    console.log(data);
                },
                error:(a,b,c)=>{
                    console.log(a+','+b+','+c);
                }
            });
        });
    });    
})();