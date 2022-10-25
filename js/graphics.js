(function(){
    $(document).ready(function(){
        const canv = $('#canv');
        const ctx = canv[0].getContext('2d');
        const img = new Image();
        img.src='https://tecesports.com/images/test1.png';
        img.onload = function(){
            ctx.drawImage(img,0,0);

            ctx.font='40px bahnschrift';
            ctx.fillStyle='#ffffff';
            ctx.fillText('aaaaaaaaa', 200, 320);
        }
    });
})();