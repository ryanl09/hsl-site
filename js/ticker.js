$(document).ready(()=>{

    jQuery.fn.outerHTML = function() {
        return jQuery('<div />').append(this.eq(0).clone()).html();
      };

    const t = $('.ticker');
    const t2 = $('.ticker2');
    const tabs = [];
    const games = [];
    const tab_divs = [];

    var c_game = 0;

    //grab all information for tabs when document loads
    $.ajax({
        url:ajaxurl,
        type:'get',
        data:{ 'page':'ticker', 'action':'init','csrf':$('#csrf').val()},
        dataType:'json',
        success:(data)=>{
            console.log(data);

            /**
             * creating each tab as an array then loop to the start
             */

            data.games.forEach(e => {
                var el = $(document.createElement('div'));
                el.css({'display':'flex', 'justify-content':'center', 'align-items':'center', 'gap':'10px'});
                el.attr('game-id', e.id);
                el.append(`<img src="${e.url}" width="${e.id===3?'46':'30'}" height="30">`);
                el.append(`<p>${e.game_name}</p>`);
                tabs.push(el.outerHTML());

                var ev = data.events[e.id];

                for (var i = 0; i < ev.length; i++){

                    /* legacy
                    var extra = '';
                    if (ev[i].event_winner !== 0){
                        var w = ev[i].home;
                        var score = ev[i].h_score + ' - ' + ev[i].a_score;
                        if(ev[i].event_winner === ev[i].a_id){
                            w = ev[i].away;
                            score = ev[i].a_score + ' - ' + ev[i].h_score;
                        }

                        extra = `${w} wins ${score}`;
                    }*/

                    var extra = '';
                    if (ev[i].event_winner !== 0){
                        var w = ev[i].home;
                        var score = ev[i].h_score + ' - ' + ev[i].a_score;
                        if(ev[i].event_winner === ev[i].a_id){
                            w = ev[i].away;
                            score = ev[i].a_score + ' - ' + ev[i].h_score;
                        }

                        extra = `${w} moves on!`;
                    }

                    var hr = '';//legacy `(` + data.records[ev[i].h_id].wins + ` - ` + data.records[ev[i].h_id].losses + `)`;
                    var ar = '';//legacy `(` + data.records[ev[i].a_id].wins + ` - ` + data.records[ev[i].a_id].losses + `)`;
                    var s = '';

                    /* legacy
                    var h = `<span><img src="${e.url}" width="30" height="30"></span><span class="div-mark">Division ${ev[i].division}</span>|<span>${fix_date(ev[i].event_date)}</span>|<span>${fix_time(ev[i].event_time)}</span>`;
                    h += `|<span><img src="${ev[i].home_logo}" width="30" height="30"></span><span>${ev[i].home_tag} ${hr}</span><span>vs</span><span><img src="${ev[i].away_logo}" width="30" height="30"></span><span>${ev[i].away_tag} ${ar}</span>${s===''?s:`|<span>${s}</span>`}`;
                    if(extra){
                        h+=`|<span>${extra}</span>`;
                    }
                    */


                    var h = `<span><img src="${e.url}" width="30" height="30"></span><span class="div-mark">Division ${ev[i].division}</span>|<span>${fix_time(ev[i].event_time)}</span>`;
                    h += `|<span><img src="${ev[i].home_logo}" width="30" height="30"></span><span>${ev[i].home_tag} ${hr}</span><span>vs</span><span><img src="${ev[i].away_logo}" width="30" height="30"></span><span>${ev[i].away_tag} ${ar}</span>${s===''?s:`|<span>${s}</span>`}`;
                    if(extra){
                        h+=`|<span>${extra}</span>`;
                    }

                    tabs.push(h);

                }
            });

            set_intv();

        },error:(a,b,c)=>{
            console.log(a+','+b+','+c);
        }
    }); 



    var m = 0; //mode

    var i = 0;//iteration
    var showing = 1; //which block is current visible

    t2.slideDown();

    
    function set_intv() {

        var to =0;

        var inv = setInterval(()=>{

            /*
            var c = '#';
            while (c.length < 7){
                c += '0123456789ABCDEF'.charAt(Math.floor(Math.random() * '0123456789ABCDEF'.length));
            }

            console.log(m);

            
    
            if (!m){
                to++;
            }

            if ( to > 1){
                
                if (m===0){
                    clearInterval(inv);
                    return;
                }
            }

            console.log(m);

            */
            var c = '#2dd881';
            if (m===0||m===3) {

                //$('.progress').width('0px');
                /*
                    HIDE LAST BLOCK BEHIND SHOWING BLOCK
                */

                switch (m) {
                    case 0:
                        t.css({
                            'z-index':1
                        });
                        t2.css({
                            'z-index':2
                        });
                        break;
                    case 3:
                        t.css({
                            'z-index':2
                        });
                        t2.css({
                            'z-index':1
                        });
                        break;
                }
            } else if (m===1||m===4){

                /*
                    PREPARE NEXT BLOCK FOR SHOWING
                */

                switch(m){
                    case 1:
                        t.css({height: '60px' });
                        t.html(tabs[i]);
                        break;
                    case 4:
                        t2.css({height: '60px' });
                        t2.html(tabs[i]);
                        break;
                }
                m++;
                if (++i >= tabs.length){
                    i=0;
                }
                return;
            } else if (m===2||m===5) {

                $('.progress').css({'width':'0%', 'background-color':c});
                $('.progress').animate({
                    'width': '100%'
                }, 5900);
            
                /*
                    SHOWING NEXT BLOCK
                */

                showing = showing ^ 1;
                switch (m){
                    case 2:
                        t2.css({height:'0px'});
                        break;
                    case 5:
                        t.css({height:'0px'});
                        break;
                }
            }
    
            m++;
            if (m > 5) {
                m=0;
            }
    
            //console.log(c);
            //t.css({'background-color': c});
        }, 2000);
    }


    /**
     * hit the database to get updated information
     */

    async function poll() {
        $.ajax({
            url:ajaxurl,
            type:'get',
            data:{ 'page':'ticker', 'action':'0' },
            dataType:'json',
            success:(data)=>{

            }
        });
    }
}); 