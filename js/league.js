(function(){
    $(document).ready(function(){
        const n = $('.info-text');
        const d = n.attr('e-date');
        const t = n.attr('e-time');
        n.text(`${fix_date(d)} @${fix_time(t)}`);

        $('.view-standings').on('click', function(){
            window.location='https://tecesports.com/standings';
        });

        $('.view-stats').on('click', function(){
            window.location='https://tecesports.com/stats';
        });

        $('.view-teams').on('click', function(){
            window.location='https://tecesports.com/teams';
        });
    });
})();