(function(){
    $(document).ready(function(){

        const canv = $('#canv');
        const ctx = canv[0].getContext('2d');

        function relMouseCoords(event){
            var totalOffsetX = 0;
            var totalOffsetY = 0;
            var canvasX = 0;
            var canvasY = 0;
            var currentElement = this;
        
            do{
                totalOffsetX += currentElement.offsetLeft - currentElement.scrollLeft;
                totalOffsetY += currentElement.offsetTop - currentElement.scrollTop;
            }
            while(currentElement = currentElement.offsetParent)
        
            canvasX = event.pageX - totalOffsetX;
            canvasY = event.pageY - totalOffsetY;
        
            return {x:canvasX, y:canvasY}
        }
        HTMLCanvasElement.prototype.pos = relMouseCoords;

        let nx = (x, sf) =>{
            return x * sf;
        }

        let ny = (y, sf) =>{
            return y * sf;
        }

        let scale_factor = (old_dim, new_dim) =>{
            return old_dim / new_dim;
        }

        let get_pixel = (x, y) =>{
            return col(ctx.getImageData(x, y,1,1).data);
        }

        let col = (px) => {
            return {
                r: px[0],
                g: px[1],
                b: px[2]
            }
        }

        let cmp = (p1, p2) => {
            if (p1.r === p2.r && p1.g === p2.g && p1.b === p2.b){
                return true;
            }
            return false;
        }

        reset();

        canv[0].onclick=function(e){
            const w = canv[0].width;
            const h = canv[0].height;
            const x_sf = scale_factor(w, canv[0].clientWidth);
            const y_sf = scale_factor(h, canv[0].clientHeight);

            const pos = canv[0].pos(e);
            const mx = nx(pos.x, x_sf);
            const my = ny(pos.y, y_sf);

            const px = get_pixel(mx, my);

            let right = false;
            let down = false;
            let left = false;
            let up = false;

            const o = 5;
            let x = mx;
            let y = my;
            const s = [0,0,0,0];

            //calculate right bound relative to click

            while(!right){
                if (x + o >= w){
                    right=true;
                    break;
                }
                x+=o;
                s[0]+=o;
                const r = get_pixel(x, y);
                if (!cmp(px, r)){
                    var a = o;
                    while (a > 0){
                        const nr = get_pixel(x-(o-a), y);
                        if(cmp(px, nr)){
                            s[0]-=(o-a);
                            right=true;
                            console.log(a);
                            break;
                        }
                        a--;
                    }
                }
            }
            console.log(s[0]);

            ctx.fillStyle=`rgba(255,0,0,1)`;
            for(let i = 0; i < s[0]; i++){
                ctx.fillRect(mx+i, y, 1, 1);
            }

            while(!down){
                down=true;
            }
            return;

            while(!left){

            }

            while (!up){

            }
                ox += offset;
                oy += offset;

                console.log(`r: ${x+ox}, d: ${y+oy}, l: ${x-ox}, u: ${y-oy}`);

                if (!right){
                }

                if (!down){
                    const d = get_pixel(x, y + oy);
                    if(!cmp(px, d)){
                        var a = 0;
                        while (a < offset){
                            const nd = get_pixel(x, y+oy+a);
                            if(!cmp(px, nd)){
                                s[1]+=a;
                                down=true;
                                break;
                            }
                            a++;
                        }
                    }

                    if (y + oy >= h){
                        down=true;
                    }else{
                        s[1]+=offset;
                    }
                }

                if (!left){
                    const l = get_pixel(x - ox,y);
                    if (!cmp(px, l)){
                        var a = offset;
                        while (a > 0){
                            const nl = get_pixel(x-ox-a, y);
                            if(!cmp(px, nl)){
                                s[2]+=a;
                                left=true;
                                break;
                            }
                            a--;
                        }
                    }
                    
                    if (x - ox <= 0){
                        left=true;
                    }
                    else{
                        s[2]+=offset;
                    }
                }

                if (!up){
                    const u = get_pixel(x, y - oy);
                    if (!cmp(px, u)){
                        var a = offset;
                        while (a > 0){
                            const nu = get_pixel(x-ox-a, y);
                            if(!cmp(px, nu)){
                                s[3]+=a;
                                up=true;
                                break;
                            }
                            a--;
                        }
                    }

                    if (y - oy <= 0){
                        up=true;
                    }else{
                        s[3]+=offset;
                    }
                }

            console.log(s);
        }

        function reset(){
            const img = new Image();
            img.src='https://tecesports.com/images/graphics/match-rl.png';
            img.onload = function(){
                ctx.drawImage(img,0,0);

                const f = new FontFace('Bahnschrift', 'url(https://tecesports.com/fonts/BAHNSCHRIFT.TTF)');
                f.load().then(function(font){
                    document.fonts.add(font);
                    console.log(font);
                    ctx.font='400 30px Bahnschrift Condensed';
                    ctx.fillStyle='#000000';
                    //ctx.fillText('BERLIN BROTHERSVALLEY', 200, 320);
                });

            }
        }
    });
})();