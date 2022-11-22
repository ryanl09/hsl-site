const ajax_url = '/ajax/';
const ajaxurl = 'https://tecesports.com/ajax/tec-ajax.php';

var alerts = [];

$(document).ready(function(){
    const request_token = $('meta[name="request-token"]').attr('content') ?? 0;
    if (!request_token) {
        return;
    }
    $.ajaxSetup({
        headers: { 'request_token': request_token }
    });
    document.body.insertAdjacentHTML('afterbegin', '<div class="alerts"></div>');
});

function fix_date(date) {
    var m = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    var _m = parseInt(date.split('-')[1], 10);
    var suf = 'th';
    var d = parseInt(date.split('-')[2], 10);
    if(d%10===1 && d!==11){
        suf='st';
    }
    if(d%10===2 && d!==12){
        suf='nd';
    }
    if(d%3===3 && d!==13){
        suf='rd';
    }
    return `${m[_m-1]} ${d}${suf}, ${date.split('-')[0]}`;
}

function fix_time(time) {
    var h = parseInt(time.split(':')[0]);
    var suf = ' PM';
    var _h = h % 12;
    if(_h===0 || _h===h){
        suf=' AM';
        _h = _h || 12;
    }
    return `${_h}:${time.split(':')[1]}${suf}`;
}

function make_alert(t, cl) {
    const a = $('<div>', {text: t})
        .addClass('alert')
        .addClass(cl);
    const b = $('#alert-container');
    b.prepend(a);
    a.addClass('show');
    alerts.push(a);
    if (alerts.length > 3){
        const c = alerts.shift();
        c.remove();
    }
    setTimeout(function(){ 
        a.removeClass('show'); 
    }, 3000);
}

function show_error(t) {
    if (Array.isArray(t)){
        t.forEach(e=>{
            make_alert(e, 'error');
        });
        return;
    }
    make_alert(t, 'error');
}

function show_success(t) {
    make_alert(t, 'success');
}

function show_info(t) {
    make_alert(t, 'info');
}

function report_error(p,e,fn){
    $.ajax({
        url:ajaxurl,
        type:'post',
        data:{'page':'error','action':'report_error','e_page':p,'e_msg':e,'e_fn':fn,'csrf':$('#csrf').val()},
        dataType:'json',
        success:(data)=>{
            if (!data.status){
                show_error(data.errors);
                return;
            }
            show_info(data.success);
        },error:(a,b,c)=>{
            console.log(a+','+b+','+c);
        }
    });
}