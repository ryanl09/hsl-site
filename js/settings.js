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
                dataType:'text',
                success:(data)=>{
                    console.log(data);
                    if (data.status){
                        alert(data.success);
                    }
                },
                error:(a,b,c)=>{
                    console.log(a+','+b+','+c);
                }
            });
        });
    });
})();