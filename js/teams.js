(function() {

    function add_team_box(e){

        const teams = $('.teams');
        const t = $('<button>');
        t.addClass(`team-box box ${e.slug}`);

        const a = $('<a>');
        a.addClass('team-link');
        a.css('display', 'block');
        t.attr('onClick', `window.location=\"https://tecesports.com/team/${e.slug}\"`);
        
        const img = $('<img>');
        img.attr('src', e.team_logo)
            .attr('width', '80')
            .attr('height', '80');

        a.append(img);
        t.append(a);
        t.append($('<h3>', { text: e.team_name }));
        t.append($('<div>').addClass('games')
            .append($('<img>').attr('src', e.url).attr('width', '26').attr('height', '26')));
        teams.append(t);
    }

    $(document).ready(function(){
        $.ajax({
            url:ajaxurl,
            type:'get',
            data:{'page':'teams', 'action':'get_teams', 'type':'hs', 'csrf':$('#csrf').val()},
            dataType:'json',
            success:(data)=>{
                console.log(data);

                add_team_box(data.teams[0]);
                for (let i = 1; i < data.teams.length; i++){
                    if(data.teams[i-1].id !== data.teams[i].id){
                        add_team_box(data.teams[i]);
                    }else{
                        const b = $(`.${data.teams[i].slug}`);
                        b.append($('<img>').attr('src', data.teams[i].url).attr('width', '26').attr('height', '26'));
                            
                    }
                }

            },error(a,b,c){
                console.log(a+','+b+','+c);
            }
        });
    });
})();