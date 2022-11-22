String.prototype.stripSlashes = function(){
    return this.replace(/\\(.)/mg, "$1");
}

String.prototype.replaceAt = function(index, replacement) {
    return this.substring(0, index) + replacement + this.substring(index + replacement.length);
}

function locs(substring,string){
    var a=[],i=-1;
    while((i=string.indexOf(substring,i+1)) >= 0) a.push(i);
    return a;
}

function rem_btw(s, e, str){
    const si = locs(s, str);
    const ei = locs(e, str);

    for (let i = si.length-1; i >= 0; i--){
        const _s = si[i];
        const _e = ei[i];

        const sub = str.substring(_s, _e+e.length);
        str = str.replace(sub, '');
    }

    return str;
}

function get_blog_posts(){
    $.ajax({
        url:'https://theesportcompany.com/wp-content/tec-blog-post.php',
        type:'get',
        headers:{ 'Auth-User': 'tecesports', 'Auth-Token':'843nvasdj9244357t8bgbdfkw40723fslkdujf4937fhrebfsd' },
        data:{'action':'get_blog_posts'},
        dataType:'json',
        success:(data)=>{
            if (!data.status){
                show_error(data.errors);
                return;
            }

            $('.showloading').removeClass('showloading');
            const bp = $('.blog-posts');

            data.posts.forEach(e => {
                const a = $('<a>')
                    .attr('href', e.url)
                    .addClass('blog-link');

                e.content = rem_btw('<!-- wp:image', 'wp:image -->', e.content);
                e.content = rem_btw('<h2>', '</h2>', e.content);

                const words = e.content.split(' ');
                const cnt = (words.length > 26 ? words.slice(0, 26).join(' ') : e.content) + ' ...';

                const div = $('<div>')
                    .addClass('blog-post')
                    .html(`<div class="blog-img">${e.img.stripSlashes()}</div><div class="blog-title"><p>${e.title}</p>
                        <p class="blog-date">${fix_date(e.date.split(' ')[0])}, ${fix_time(e.date.split(' ')[1])}</p>
                        <span class="blog-content">${cnt}</span>
                        <span class="read-all">Read<i class='bx bxs-chevrons-right'></span>`);

                a.append(div);
                bp.append(a);
            });
        },error:(a,b,c)=>{
            report_error('dashboard', a+','+b+','+c, 'get_blog_posts');
        }
    });
}

function get_announcements(){
    $.ajax({
        url:ajaxurl,
        type:'post',
        data:{'page':'admin', 'action':'get_announcements', 'csrf':$('#csrf').val()}, 
        dataType:'json',
        success:(data)=>{
            if (!data.status){
                show_error(data.status);
                return;
            }

            $('.tab .announce').html('');
            data.announcements.forEach(e => {

                const ti = e.time.split(' ');
                const d = fix_date(ti[0]);
                const t= fix_time(ti[1]);

                $('.tab .announce').append(`
                <div class="box">
                    <div class="ann">
                        <h2 class="ann-title">${e.title}</h2>
                        <div class="ann-body">
                            <p class="body-text">${e.body}</p>
                        </div>
                        <div class="ann-info">
                            <div class="ann-author">
                                <img src="https://tecesports.com/uploads/${e.pfp_url}" alt="" width="40" height="40">
                                <p class="author">${e.name}</p>
                            </div>
                            <p class="ann-time">${d}, ${t}</p>
                        </div>
                    </div>
                </div>`);
                console.log(e);
            });
        },error:(a,b,c)=>{
            report_error('dashboard', a+','+b+','+c, 'get_announcements');
        }
    });
}

$(document).ready(function(){
    get_blog_posts();

    $('.tab-change').on('click', function(){
        const id = $(this).attr('tab-id');
        const target = $(this).attr('tab-target');
        window.location.hash = `#${target}`;

        $('.tab').hide();
        $('.tab-change').removeClass('selected');
        $('.tab[tab--id='+id+']').show();
        $(this).addClass('selected');

        if (id == 3) {
            get_announcements();
        }
    });

    var hash = window.location.hash.substring(1);
    if (!hash){
        hash = 'home';
    }
    if ($(`[tab-target=${hash}]`).length > 0){
        $(`[tab-target=${hash}]`).click();
    }

});