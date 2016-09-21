var currentPage = 1;
var maxPage;
var articlesData;

$(document).ready(function(){
    maxPage = $('#pagination-info').text();

    if(maxPage > 0){
        articlesData = $.parseJSON($.ajax({
            url: "getarticles.php",
            dataType: "json",
            async: false
        }).responseText);

        $('.next').click(function(){
            if(currentPage+1 <= maxPage){
                loadPage(currentPage+1);
            }
        });

        $('.previous').click(function(){
            if(currentPage-1 > 0){
                loadPage(currentPage-1);
            }
        });

        $('.first').click(function(){
            loadPage(1);
        });

        $('.last').click(function(){
            loadPage(maxPage);
        });

        loadPage(1);
    }
    else{
        $('.c-articlespagination').css("display", "none");
    }
});

function loadPage(page){
    currentPage = page;
    $('.pageindex').val(currentPage);
    var pg = page;
    var artPerPage = 8;
    var list = $('.c-articleslist');
    list.empty();
    
    for(var i = 1+(artPerPage*(page-1)); i < 9+(artPerPage*(page-1)); i++){
        if(articlesData[i-1] !== undefined){
            var title = articlesData[i-1].title;
            if(title.length > 30){
                title = title.substring(0, 30);
                title += "...";
            }

            var string ="<li class='c-articleslist-item'>"+
                        "<a href='article?id="+articlesData[i-1].id+"'><div class='c-articleslist-item_outer'"+
                        "style='background: url(/images/articles/"+articlesData[i-1].img+"); background-position: center; background-size: cover;'>"+
                        "<span class='c-aticleslist-item_inner'>"+title+
                        "</span></div></a></li>";
            list.append(string);
        }
    }
}