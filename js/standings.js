(function(){
    $(document).ready(function(){
        function get_stnd(p){
            const game_id = p[0];
            const div = p[1];

            $.ajax({
                url:ajaxurl,
                type:'get',
                data:{'page':'standings','action':'get_standings',
                    'game_id':game_id,'div':div,'csrf':$('#csrf').val()},
                dataType:'json',
                success:(data)=>{
                    console.log(data);
                    if(!data.status){
                        //error
                        return;
                    }
                }
            });
        }
        
        get_stnd([1,1]);

        let params = () => {
            return [parseInt($('.game-btn.selected').attr('game-id'), 10), parseInt($('#div').val(),10)];
        }

        $('.game-btn').on('click', function(){
            get_stnd(params());
        });

        $('#div').on('change', function(){
            get_stnd(params());
        });
    });
})();