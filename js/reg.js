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
    });
})();