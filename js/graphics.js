(function(){
    $(document).ready(function(){

        var is_select = false;

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

        function rgb2lab(rgb){
            var r = rgb[0] / 255,
                g = rgb[1] / 255,
                b = rgb[2] / 255,
                x, y, z;
          
            r = (r > 0.04045) ? Math.pow((r + 0.055) / 1.055, 2.4) : r / 12.92;
            g = (g > 0.04045) ? Math.pow((g + 0.055) / 1.055, 2.4) : g / 12.92;
            b = (b > 0.04045) ? Math.pow((b + 0.055) / 1.055, 2.4) : b / 12.92;
          
            x = (r * 0.4124 + g * 0.3576 + b * 0.1805) / 0.95047;
            y = (r * 0.2126 + g * 0.7152 + b * 0.0722) / 1.00000;
            z = (r * 0.0193 + g * 0.1192 + b * 0.9505) / 1.08883;
          
            x = (x > 0.008856) ? Math.pow(x, 1/3) : (7.787 * x) + 16/116;
            y = (y > 0.008856) ? Math.pow(y, 1/3) : (7.787 * y) + 16/116;
            z = (z > 0.008856) ? Math.pow(z, 1/3) : (7.787 * z) + 16/116;
          
            return [(116 * y) - 16, 500 * (x - y), 200 * (y - z)]
          }
          
          function deltaE(labA, labB){
            var deltaL = labA[0] - labB[0];
            var deltaA = labA[1] - labB[1];
            var deltaB = labA[2] - labB[2];
            var c1 = Math.sqrt(labA[1] * labA[1] + labA[2] * labA[2]);
            var c2 = Math.sqrt(labB[1] * labB[1] + labB[2] * labB[2]);
            var deltaC = c1 - c2;
            var deltaH = deltaA * deltaA + deltaB * deltaB - deltaC * deltaC;
            deltaH = deltaH < 0 ? 0 : Math.sqrt(deltaH);
            var sc = 1.0 + 0.045 * c1;
            var sh = 1.0 + 0.015 * c1;
            var deltaLKlsl = deltaL / (1.0);
            var deltaCkcsc = deltaC / (sc);
            var deltaHkhsh = deltaH / (sh);
            var i = deltaLKlsl * deltaLKlsl + deltaCkcsc * deltaCkcsc + deltaHkhsh * deltaHkhsh;
            return i < 0 ? 0 : Math.sqrt(i);
          }

        let col = (px) => {
            return {
                r: px[0],
                g: px[1],
                b: px[2]
            }
        }

        let cmp = (p1, p2) => {
            const lab1 = rgb2lab([p1.r, p1.g, p1.b]);
            const lab2 = rgb2lab([p2.r, p2.g, p2.b]);

            const e = deltaE(lab1, lab2);
            return e < 10; //weight of comparison



            //legacy [exact comparison, bad]
            /*
            if (p1.r === p2.r && p1.g === p2.g && p1.b === p2.b){
                return true;
            }
            return false;
            */


        }

        let box = (_x, _y, _w, _h) => {
            return {
                x:_x,
                y:_y,
                w:_w,
                h:_h
            };
        }

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

            const o = 2;
            let x = mx;
            let y = my;
            const s = {
                r:0,
                d:0,
                l:0,
                u:0
            }

            //calculate right bound relative to click

            while(!right){
                if (x + o >= w){
                    right=true;
                    break;
                }
                x+=o;
                s.r+=o;
                const r = get_pixel(x, y);
                if (!cmp(px, r)){
                    var a = o;
                    while (a >= 0){
                        const nr = get_pixel(x-(o-a), y);
                        if(cmp(px, nr)){
                            s.r-=(o-a);
                            right=true;
                            break;
                        }
                        a--;
                    }
                }
            }
            x=mx;
            y=my;
            while(!down){
                if (y + o >= h){
                    down=true;
                    break;
                }
                y+=o;
                s.d+=o;
                const r = get_pixel(x, y);
                if (!cmp(px, r)){
                    var a = o;
                    while (a >= 0){
                        const nr = get_pixel(x, y-(o-a));
                        if(cmp(px, nr)){
                            s.d-=(o-a);
                            down=true;
                            break;
                        }
                        a--;
                    }
                }
            }

            x=mx;
            y=my;
            while(!left){
                if (x - o < 0){
                    left=true;
                    break;
                }
                x-=o;
                s.l+=o;
                const r = get_pixel(x, y);
                if (!cmp(px, r)){
                    var a = o;
                    while (a >= 0){
                        const nr = get_pixel(x+(o-a), y);
                        if(cmp(px, nr)){
                            s.l-=(o-a);
                            left=true;
                            break;
                        }
                        a--;
                    }
                } 
            }

            x=mx;
            y=my;
            while (!up){
                if (y - o < 0){
                    up=true;
                    break;
                }
                y-=o;
                s.u+=o;
                const r = get_pixel(x, y);
                if (!cmp(px, r)){
                    var a = o;
                    while (a >= 0){
                        const nr = get_pixel(x, y+(o-a));
                        if(cmp(px, nr)){
                            s.u-=(o-a);
                            up=true;
                            break;
                        }
                        a--;
                    }
                }
            }

            const bx = box(mx-s.l, my-s.u, s.l+s.r, s.u+s.d);
            //draw_text('VS', bx);

            //console.log(s);
        }

        function draw_lines(s, mx, my){
            ctx.fillStyle=`rgba(255,0,0,1)`;
            for(let i = 0; i < s.r; i++){
                ctx.fillRect(mx+i, my, 1, 1);
            }

            ctx.fillStyle=`rgba(0,0,0,1)`;
            for(let i = 0; i < s.d; i++){
                ctx.fillRect(mx, my+i, 1, 1);
            }
            
            ctx.fillStyle=`rgba(0,0,255,1)`;
            for(let i = 0; i < s.l; i++){
                ctx.fillRect(mx-i, my, 1, 1);
            }
            
            ctx.fillStyle=`rgba(255,0,255,1)`;
            for(let i = 0; i < s.u; i++){
                ctx.fillRect(mx, my-i, 1, 1);
            }
        }

        $('img').on('click', function(){
            const url = $(this).attr('src');
            const img = new Image();
            img.src = url;
            img.onload = function(){
                ctx.drawImage(img,0,0);
            }
        });

        function draw_text(text, bx){

            const size = text2dim(text);
            const tx = bx.x + (bx.w / 2) - (size.w / 2);
            const ty = bx.y + (bx.h / 2) + (size.h / 2);

            const f = new FontFace('Bahnschrift', 'url(https://tecesports.com/fonts/BAHNSCHRIFT.TTF)');
            f.load().then(function(font){
                document.fonts.add(font);
                console.log(font);
                ctx.font='400 30px Bahnschrift Condensed';
                ctx.fillStyle='#000000';
                ctx.fillText(text, tx, ty);
            });
        }

        function text2dim(text){
            //assuming font size is 30px, each letter is ~15px
            let w = 0;
            const FONT_SIZE=30;
            for (let i = 0; i < text.length; i++){
                switch (text[i]){
                    case 'l':
                    case 'I':
                        w+=FONT_SIZE * 0.2;//6
                        break
                    case ' ':
                        w+=FONT_SIZE*0.2667;
                        break;
                    default:
                        w+=FONT_SIZE * 0.5;//15
                        break;
                }
                if(i<text.length-1){
                    w+=1;
                }
            }
            return {
                w: Math.floor(w), 
                h:FONT_SIZE * 0.7
            };
        }
    });
})();