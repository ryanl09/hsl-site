(function(){
    $(document).ready(function(){
        const canv = $('#canv');
        const ctx = canv[0].getContext('2d');
        const img = new Image();
        img.src='https://tecesports.com/images/test1.png';
        img.onload = function(){
            ctx.drawImage(img,0,0);

            const f = new FontFace('bahn', 'url(fonts/bahn.tff)');
            f.onload().then(function(font){
                document.fonts.add(font);
                ctx.font='600 34px bahnschrift';
                ctx.fillStyle='#000000';
                ctx.fillText('GATEWAY', 200, 320);
            });

        }
    });
})();