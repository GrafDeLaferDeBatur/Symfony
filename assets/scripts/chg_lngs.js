$('#en').click(url=>{
    window.location = window.location.origin + '/en' + window.location.pathname.replace('/ru', '');
})

$('#ru').click(()=>{
    window.location = window.location.origin + '/ru' + window.location.pathname.replace('/en', '');
})
