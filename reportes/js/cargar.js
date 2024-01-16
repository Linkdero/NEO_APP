setTimeout(() => {
    init_graficas($(".nav-link.active").attr("href"));  
}, 1000);

$('#graph-list a').on('click', function (e) {
    e.preventDefault();
    $(this).tab('show');  
    setTimeout(() => {
        init_graficas($(this).attr("href"));  
    }, 500);
});