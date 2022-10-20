(function(){
    $(document).ready(function() {

        $('.post-a').on('click', function() {
            $.ajax({
                url:`${ajax_url}admin-ajax.php`,
                type:'post',
                data:{'action':'add_announcement', 'a-title':$('#a-title').val(), 'a-body': $('#a-body').val(), 'csrf':$('#csrf').val()},
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







        $('.btn-create').on('click', function(){
            $.ajax({
                url:`${ajax_url}admin-ajax.php`,
                type:'post',
                data:{'action':'add_temp_pl', 'ign':$('#ign').val(), 'team': $('#team').val(), 'csrf':$('#csrf').val()},
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
                url:`${ajax_url}admin-ajax.php`,
                type:'post',
                data:{'action':'allocate_temp_pl', 'id':$('#pl-id').val(), 'game':$('#game').val(), 'div':$('#div').val(), 'csrf':$('#csrf').val()},
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