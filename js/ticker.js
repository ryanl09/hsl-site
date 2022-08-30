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
        url:`${ajax_url}ticker-ajax.php`,
        type:'get',
        data:{'action':'init','csrf':$('#csrf').val()},
        dataType:'json',
        success:(data)=>{
            console.log(data);
            tabs[0] = '';

            /**
             * creating each tab as an array then loop to the start
             */
            
            data.games.forEach(e =>{
                var el = $(document.createElement('div'));
                el.css({'display':'flex', 'justify-content':'center', 'align-items':'center', 'gap':'10px'});
                el.attr('game-id', e.id);
                el.append(`<img src="${e.url}" width="${e.id===3?'46':'30'}" height="30">`);
                el.append(`<p>${e.game_name}</p>`);
                tab_divs.push(el);
                games.push({'id':e.id-1, 'url':e.url});
                
                var n = (e.id-1)*2;
                if(tabs[n]===undefined){
                    tabs[n]='';
                }
                if(tabs[n+1]===undefined) {
                    tabs[n+1]='';
                }
                tabs[n+1] += `<img src="${e.url}" width="${e.id===3?'46':'30'}" height="30" style="margin-left:10px;">`;
            });

            console.log(games);

            for (var j = 0; j < games.length; j++) {
                var eq = (games[j].id)*2;

                for (var k = 0; k < tab_divs.length; k++) {
                    //console.log(eq +',' + tab_divs[k].attr('game-id'));
                    if(j+1===parseInt(tab_divs[k].attr('game-id'), 10)) {
                        tab_divs[k].css({'background-color': '#0e0e0e'});
                    }

                    tabs[eq] += tab_divs[k].outerHTML();
                    tab_divs[k].css({'background-color': '#222222'});
                }
            }

            if (tabs[0]===tabs[tabs.length-1]){
                tabs.pop();
            }

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
        setInterval(()=>{

            var c = '#';
            while (c.length < 7){
                c += '0123456789ABCDEF'.charAt(Math.floor(Math.random() * '0123456789ABCDEF'.length));
            }

            console.log(m);
    
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
            url:`${ajax_url}ticker-ajax.php`,
            type:'get',
            data:{},
            dataType:'json',
            success:(data)=>{

            }
        });
    }
}); 