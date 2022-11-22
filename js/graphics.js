(function(){
    $(document).ready(function(){
        const f = new FontFace('Bahnschrift', 'url(https://tecesports.com/fonts/BAHNSCHRIFT.TTF)');
        f.load().then(function(font){
            document.fonts.add(font);
            console.log(font);
        });

        const BX = [];
        var id = -1;
        
        get_weeks();

        var is_select = false;

        const canv = $('#canv');
        const ctx = canv[0].getContext('2d');

        /**
         * draws a red box on the image
         * @param {object} bx 
         */

        function draw_bx(bx){
            ctx.fillStyle = 'rgba(255,0,0,1)';
            ctx.fillRect(bx.x, bx.y, bx.w, bx.h);
            ctx.fillStyle = 'rgba(255,255,255,0.4)';
            ctx.fillRect(bx.x+1, bx.y+1, bx.w-2, bx.h-2);
        }

        function get_weeks(){
            $.ajax({
                url:ajaxurl,
                type:'get',
                data:{'page':'graphics','action':'get_weeks','season':$('#season').val(),'csrf':$('#csrf').val()},
                dataType:'json',
                success:(data)=>{
                    if(!data.status){
                        show_error(data.errors);
                        return;
                    }

                    const w =  $('#week');
                    w.html('');
                    data.weeks.forEach(e=>{
                        const o = $('<option>', { text: e }).val(e);
                        w.append(o);
                    });
                },error:(a,b,c)=>{
                    report_error('graphics', a+','+b+','+c, 'get_weeks');
                }
            });
        }

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

        /**
         * gets real x of scaled image
         * @param {int} x scaled x
         * @param {double} sf scale factor
         * @returns {double}
         */

        let nx = (x, sf) =>{
            return x * sf;
        }

        /**
         * gets real y of scaled image
         * @param {int} y scaled y
         * @param {double} sf scale factor
         * @returns {double}
         */

        let ny = (y, sf) =>{
            return y * sf;
        }

        /**
         * calculates scale factor of image (dim = w or h)
         * @param {int} old_dim
         * @param {int} new_dim
         * @returns 
         */

        let scale_factor = (old_dim, new_dim) =>{
            return old_dim / new_dim;
        }

        /**
         * gets pixel rgb at (x, y)
         * @param {int} x 
         * @param {int} y 
         * @returns {object} { r, g, b }
         */

        let get_pixel = (x, y) =>{
            return col(ctx.getImageData(x, y,1,1).data);
        }

        /**
         * converts rgb to lab
         * @param {object} rgb 
         * @returns {array} lab value of rgb
         */

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

          /**
           * formats rgb array to object
           * @param {object} px 
           * @returns 
           */
        let col = (px) => {
            return {
                r: px[0],
                g: px[1],
                b: px[2]
            }
        }

        /**
         * compares 2 colors to 10% similarity
         * @param {object} p1 
         * @param {object} p2 
         * @returns {boolean} if the two colors are atleast 10% similar
         */

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

        /**
         * formats coordinates & dimensions to an object
         * @param {double} _x 
         * @param {double} _y 
         * @param {int} _w 
         * @param {int} _h 
         * @returns {object}
         */

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
            add_bx(bx);
            draw_bx(bx);
        }

        $('#season').on('change', function(){
            get_weeks();
        });

        $('#data').on('change', function(){
            const val = parseInt($(this).val(),10);
            switch(val){
                case 1://matches
                    $('.wsel').show();
                    break;
                case 2://standings
                    $('.wsel').hide();
                    break;
                case 3://final score
                    $('.wsel').hide();
                    break;
                case 4://roster
                    $('.wsel').hide();
                    break;
            }
        });

        function add_bx(bx){
            BX.push(bx);
            const tr = $('<tr>')
                .append($('<td>', {text: bx.x}))
                .append($('<td>', {text: bx.y}))
                .append($('<td>', {text: bx.w}))
                .append($('<td>', {text: bx.h}));
            $('.bx-pts').append(tr);
        }

        /**
         * removes all selected boxes
         */

        function clear_bx(){
            while (BX.length){
                BX.pop();
            }
            $('.bx-pts').html('');
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

        $('.img-box').on('click', function(){
            clear_bx();
            const url = $($(this).children()[0]).attr('src');
            id = parseInt($(this).attr('upload-id'), 10);

            draw_img(url, get_data);
        });

        function draw_img(url, callback=function(){}){
            const img = new Image();
            img.src = url;
            img.onload = function(){
                ctx.drawImage(img,0,0);
                callback();
            }
        }

        $('.btn-clear').on('click', function(){
            clear_bx();

            const url = $($(`[upload-id=${id}]`).children()[0]).attr('src');
            draw_img(url);
        });

        $('.btn-save').on('click', function(){
            set_data(id);
        });

        $('.btn-undo').on('click', function(){
            if (BX.length){
                BX.pop();
                const c = $('.bx-pts').children();
                c[c.length-1].remove();
    
                const url = $($(`[upload-id=${id}]`).children()[0]).attr('src');
                draw_img(url, function(){
                    BX.forEach(e=>{
                        draw_bx(e);
                    })
                });
            }
        });

        $('.btn-adddata').on('click', function(){
            const c = BX.length;

            fetch_data(function(data){
                if (data.arr.length !== c){
                    console.log('data /= boxes');
                }
                const url = $($(`[upload-id=${id}]`).children()[0]).attr('src');
                draw_img(url);

                const min = Math.min(c, data.arr.length);
                for (let i = 0; i < min; i++){
                    const bx = BX[i];
                    const d = data.arr[i];

                    if(data.mode===1){
                        const vs = draw_text('VS', bx);
                        const lb = box(bx.x-vs.w, bx.y, vs.x-bx.x, bx.h);
                        const rb = box(vs.x+(2*vs.w),bx.y,bx.w-(vs.x+vs.w),bx.h);

                        draw_text(d.home, lb, 'left');
                        draw_text(d.away, rb, 'right');

                    }
                }
            });
        });

        function fetch_data(callback){
            const s = $('#season').val();
            const g = $('#games').val();
            const d = $('#div').val();
            const w = $('#week').val();
            const a = `get_${['matches', 'standings', 'scores', 'roster'][parseInt($('#data').val(),10)-1]}`;

            $.ajax({
                url:ajaxurl,
                type:'get',
                data:{'page':'graphics', 'action':a, 'season':s,'game':g,'div':d,'week':w,'csrf':$('#csrf').val()},
                dataType:'json',
                success:(data)=>{
                    if (!data.status){
                        show_error(data.errors);
                        return;
                    }

                    callback(data);
                },error:(d,b,c)=>{
                    report_error('graphics', d+','+b+','+c, a);
                }
            });
        }

        function draw_text(text, bx, opt='center'){
            text=text.toUpperCase();
            const size = text2dim(text);
            let tx = bx.x + (bx.w / 2) - (size.w / 2);
            let ty = bx.y + (bx.h / 2) + (size.h / 2);

            switch (opt){
                case 'left':
                    tx = bx.w-size.w+bx.x;
                    break;
                case 'right':
                    tx = bx.x;
                    break;
            }

            const f = new FontFace('Bahnschrift', 'url(https://tecesports.com/fonts/BAHNSCHRIFT.TTF)');
            f.load().then(function(font){
                document.fonts.add(font);
                ctx.font='400 30px Bahnschrift Condensed';
                ctx.fillStyle='#000000';
                ctx.fillText(text, tx, ty);
            });

            const obj = {
                x: tx,
                y: ty,
                w: size.w,
                h: size.h
            };
            console.log(obj);
            return obj;
        }

        function oldt2d(text){
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

        function text2dim(text){

            const canvas = text2dim.canvas || (text2dim.canvas = document.createElement("canvas"));
            const context = canvas.getContext("2d");
            context.font = '400 30px Bahnschrift Condensed';
            const metrics = context.measureText(text);
            return {
                w: Math.floor(metrics.width),
                h: 30 * 0.7
            };
        }
        
        $('#upload-image').on('click', function(e){
            e.preventDefault();
            $('.upload-img').click();
        });

        $('.upload-img').change(function(){
            
            var file = $(this)[0].files;
            if (file.length > 0){
                var formData = new FormData();
                formData.append("fileToUpload", file[0]);

                var xhr = new XMLHttpRequest;
                xhr.open('post', 'https://tecesports.com/ajax/upload.php', true);
                xhr.onreadystatechange=function(){
                    if (this.readyState===4 && this.status===200){
                        console.log(this.responseText);
                        var data = JSON.parse(this.responseText);
                        if (!data.status){
                            show_error(data.errors);
                            return;
                        }

                        var img = data.url;
                        $('.img-list').append(
                            $('<div>').append(
                                $('<img>').attr('src', data.url)
                            ).addClass('img-box')
                                .attr('upload-id', data.id)
                        );
                    }
                }
                xhr.send(formData);
            }
        
                console.log('submitted');

            //$('#pfp-form').submit();
        });

        function get_data(){
            $.ajax({
                url:ajaxurl,
                type:'get',
                data:{'page':'graphics', 'action':'get_data', 'upload_id':id, 'csrf':$('#csrf').val()},
                dataType:'json',
                success:(data)=>{
                    if (!data.status){
                        show_error(data.errors);
                    }

                    data.data.forEach(e=>{
                        const bx = box(e.x, e.y, e.width, e.height);
                        add_bx(bx);
                        draw_bx(bx);
                    });

                },error:(a,b,c)=>{
                    report_error('graphics', a+','+b+','+c, 'get_data');
                }
            });
        }

        function set_data(id){
            const data = JSON.stringify(BX);
            
            $.ajax({
                url:ajaxurl,
                type:'post',
                data:{'page':'graphics', 'action':'set_data', 'upload_id':id, 'data':data, 'csrf':$('#csrf').val()},
                dataType:'json',
                success:(data)=>{
                    if (!data.status){
                        show_error(data.errors);
                        return;
                    }
                    show_success('Data updated!');
                },error:(a,b,c)=>{
                    report_error('graphics', a+','+b+','+c, 'set_data');
                }
            });
        }
    });

})();