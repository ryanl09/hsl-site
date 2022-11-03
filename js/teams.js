(function() {
    $(document).ready(function(){
        $.ajax({
            url:ajaxurl,
            type:'get',
            data:{'page':'teams', 'action':'get_teams', 'type':'hs', 'csrf':$('#csrf').val()},
            dataType:'json',
            success:(data)=>{
                console.log(data);
                const teams = $('.teams');

                data.teams.forEach(e => {
                    const t = $('<div>');
                    t.addClass('team-box box');

                    const a = $('<a>');
                    a.addClass('team-link');
                    a.css('display', 'block');
                    a.attr('href', `https://tecesports.com/team/${e.slug}`);
                    
                    const img = $('<img>');
                    img.attr('src', e.team_logo)
                        .attr('width', '100')
                        .attr('height', '100');

                    a.append(img);
                    t.append(a);
                    teams.append(t);
                });
            }
        });
    });
})();