const ajax_url = '/ajax/';

$(document).ready(()=>{
    const request_token = $('meta[name="request-token"]').attr('content') ?? 0;
    if (!request_token) {
        return;
    }
    $.ajaxSetup({
        headers: { 'request_token': request_token }
    });
});