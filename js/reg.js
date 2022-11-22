(function(){
    $(document).ready(function(){


        $('.copy-sc').on('click', function(){
            navigator.clipboard.writeText($(this).attr('data-link'));
            $(this).css('color', '#1c9c49');
            $(this).html("<i class='bx bxs-checkbox-checked'></i>Copied");
        });

        const t = $('.team');
        t.on('change', ()=>{
            if (t.val()=='-1'){
                ('.hs-players-table tbody tr').show();
                return;
            }

            $('.hs-players-table tbody tr').not(`.team-${t.val()}`).hide();
            $(`.hs-players-table tbody tr.team-${t.val()}`).show();
        });


        $('.team-id').on('click', function(){
            $.ajax({
                url:ajaxurl,
                type:'post',
                data:{'page':'reg', 'action':'0', 'update_team':$(this).text(), 'csrf':$('#csrf').val()},
                dataType:'json',
                success:(data)=>{
                    if (data.status){
                        window.location.reload();
                    }else{
                        show_error(data.errors);
                    }
                }
                ,error:(a,b,c)=>{
                    report_error('reg', a+','+b+','+c, 'update_team');
                }
            });
        });
    });
})();