(function(){
    $(document).ready(function(){
        $('.save-ign').on('click', function(){

            const vals = [];

            $('.ign-box').each(function(){
                const obj={
                    game: $(this).attr('game-id'),
                    ign: $(this).val()
                };
                vals.push(obj);
            });

            $.ajax({
                url:ajaxurl,
                type:'post',
                data:{'page': 'settings', 'action':'set_ign', 'data':JSON.stringify(vals), 'csrf':$('#csrf').val()},
                dataType:'json',
                success:(data)=>{
                    if (!data.status){
                        show_error(data.errors);
                        return;
                    }

                    show_success(data.success);
                },error:(a,b,c)=>{
                    report_error('settingss', a+','+b+','+c, 'set_ign');
                }
            });
        });
    });
})();