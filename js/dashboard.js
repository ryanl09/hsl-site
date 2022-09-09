(function(){
    $(document).ready(()=>{
        $('.copy-code').on('click', ()=>{
            navigator.clipboard.writeText($('#schoolcode').attr('href'));
            $('.copy-code').html('<i class="bx bxs-check-square"></i>')
            .css('color', '#36d660');
        });

        $('td.user-col').on('click', (e)=>{
            var name = $(e.target).text();
            var uname = $(e.target).attr('username');
            var id = $(e.target).attr('user-id');

            $('.empty-player').hide();
            $('.player-info').show();
            $('.set-teams').show();
            $('#p-name').html(`<strong>Name:</strong> ${name}`);
            $('#p-uname').html(`<strong>Username:</strong> ${uname}`);
        });
    });
})();