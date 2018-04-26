$(".smooth-scroll").on("click", "a", function(e) {
        e.preventDefault();
        var t = $(this).attr("href"),
            n = $(this).attr("data-offset") ? $(this).attr("data-offset") : 100,
            i = $(this).closest("ul").attr("data-allow-hashes");
        $("body,html").animate({
            scrollTop: $(t).offset().top - n
        }, 700), void 0 !== i && !1 !== i && history.replaceState(null, null, t)
    });
    