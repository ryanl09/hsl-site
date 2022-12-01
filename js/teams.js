(function() {

    function add_team_box(e){

        const teams = $('.teams');
        const a = $('<a>')
            .attr('href', `https://tecesports.com/team/${e.slug}`)
            .css('text-decoration', 'none');
        const t = $('<div>');
        t.addClass(`team-box box ${e.slug}`);
        
        const img = $('<img>');
        img.attr('src', e.team_logo)
            .attr('width', '60')
            .attr('height', '60');

        t.append(img);
        t.append($('<h3>', { text: e.team_name }));
        /*
        t.append($('<div>').addClass('games')
            .append($('<img>').attr('src', e.url).attr('width', '26').attr('height', '26')));
            */
        teams.append(a.append(t));
    }

    $(document).ready(function(){
        $.ajax({
            url:ajaxurl,
            type:'get',
            data:{'page':'teams', 'action':'get_teams', 'type':'hs', 'csrf':$('#csrf').val()},
            dataType:'json',
            success:(data)=>{
                add_team_box(data.teams[0]);
                for (let i = 1; i < data.teams.length; i++){
                    if(data.teams[i-1].id !== data.teams[i].id){
                        add_team_box(data.teams[i]);
                    }/*else{
                        
                        const b = $(`.${data.teams[i].slug}`);
                        b.append($('<img>').attr('src', data.teams[i].url).attr('width', '26').attr('height', '26'));
                            
                    }*/
                }

            },error:(a,b,c)=>{
                report_error('teams', a+','+b+','+c, 'get_teams');
            }
        });
    });
})();