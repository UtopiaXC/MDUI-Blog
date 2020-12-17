$(function () {
    var $document = $(document);

    // 代码高亮
    hljs.initHighlightingOnLoad();

    // 页面滚动
    var scroll = new SmoothScroll('[data-scroll]', {
        speed: 300,
        speedAsDuration: true
    });

    $('.mdui-toolbar-spacer').on('click', function () {
        scroll.animateScroll(0);
    });

    // 图片占位符
    Holder.addTheme("gray", {
        bg: "#BCBEC0",
        fg: "rgba(255, 255, 255, 1)",
        size: 12,
        fontweight: "normal"
    });

    // 示例处理
    $('.viewsource').on('click', function () {
        var $this = $(this);
        $this.parents('.doc-example').eq(0).toggleClass('doc-example-showcode');
    });

    /**
     * 设置文档主题
     */
    var DEFAULT_PRIMARY = 'indigo';
    var DEFAULT_ACCENT = 'pink';
    var DEFAULT_LAYOUT = 'auto';

    // 设置 cookie
    var setCookie = function (key, value) {
        // cookie 有效期为 1 年
        var date = new Date();
        date.setTime(date.getTime() + 365 * 24 * 3600 * 1000);
        document.cookie = key + '=' + value + '; expires=' + date.toGMTString() + '; path=/';
    };

    var setDocsTheme = function (theme) {
        if (typeof theme.primary === 'undefined') {
            theme.primary = false;
        }
        if (typeof theme.accent === 'undefined') {
            theme.accent = false;
        }
        if (typeof theme.layout === 'undefined') {
            theme.layout = false;
        }

        var i, len;
        var $body = $('body');

        var classStr = $body.attr('class');
        var classs = classStr.split(' ');

        // 设置主色
        if (theme.primary !== false) {
            for (i = 0, len = classs.length; i < len; i++) {
                if (classs[i].indexOf('mdui-theme-primary-') === 0) {
                    $body.removeClass(classs[i])
                }
            }
            $body.addClass('mdui-theme-primary-' + theme.primary);
            setCookie('primary', theme.primary);
            $('input[name="doc-theme-primary"][value="' + theme.primary + '"]').prop('checked', true);
        }

        // 设置强调色
        if (theme.accent !== false) {
            for (i = 0, len = classs.length; i < len; i++) {
                if (classs[i].indexOf('mdui-theme-accent-') === 0) {
                    $body.removeClass(classs[i]);
                }
            }
            $body.addClass('mdui-theme-accent-' + theme.accent);
            setCookie('accent', theme.accent);
            $('input[name="doc-theme-accent"][value="' + theme.accent + '"]').prop('checked', true);
        }

        // 设置主题色
        if (theme.layout !== false) {
            for (i = 0, len = classs.length; i < len; i++) {
                if (classs[i].indexOf('mdui-theme-layout-') === 0) {
                    $body.removeClass(classs[i]);
                }
            }
            $body.addClass('mdui-theme-layout-' + theme.layout);
            setCookie('layout', theme.layout);
            $('input[name="doc-theme-layout"][value="' + theme.layout + '"]').prop('checked', true);
        }
    };

    // 切换主色
    $document.on('change', 'input[name="doc-theme-primary"]', function () {
        setDocsTheme({
            primary: $(this).val()
        });
    });

    // 切换强调色
    $document.on('change', 'input[name="doc-theme-accent"]', function () {
        setDocsTheme({
            accent: $(this).val()
        });
    });

    // 切换主题色
    $document.on('change', 'input[name="doc-theme-layout"]', function () {
        setDocsTheme({
            layout: $(this).val()
        });
    });

    // 恢复默认主题
    $document.on('cancel.mdui.dialog', '#dialog-theme', function () {
        setDocsTheme({
            primary: DEFAULT_PRIMARY,
            accent: DEFAULT_ACCENT,
            layout: DEFAULT_LAYOUT
        });
    });

    // 如果抽屉栏当前激活项不在视野中，则滚动抽屉栏，使激活项位于垂直居中
    (function () {
        var $drawer = $('#main-drawer');
        var $activeItem = $drawer.find('.mdui-list-item-active');

        if (!$activeItem.length) {
            return;
        }

        var activeItemOffsetTop = $activeItem.offset().top;
        var drawerHeight = $drawer.innerHeight();

        if (activeItemOffsetTop - 64 < 0 || activeItemOffsetTop - 64 + 238 > drawerHeight) {
            $drawer[0].scrollTop = activeItemOffsetTop + $drawer[0].scrollTop - drawerHeight / 2;
        }
    })();
});

(function ($) {
    $.getUrlParam = function (name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
        var r = window.location.search.substr(1).match(reg);
        if (r != null) return decodeURI(r[2]);
        return null;
    }
})(jQuery);

function add_card(father, img, title, description, time, index, PID) {
    father.append('<div class="mdui-card" style="margin-top: 30px">\n' +
        '                    <div class="mdui-card-media">\n' +
        '                        <img src="' + img + '" alt=""/>\n' +
        '                        <div class="mdui-card-media-covered">\n' +
        '                            <div class="mdui-card-primary">\n' +
        '                                <div class="mdui-card-primary-title">' + title + '</div>\n' +
        '                                <div class="mdui-card-primary-subtitle">' + description + '</div>\n' +
        '                                <div class="mdui-card-primary-subtitle">' + time + '</div>\n' +
        '                            </div>\n' +
        '                            <div class="mdui-card-actions" style="text-align: right">\n' +
        '                                <button class="mdui-btn mdui-ripple mdui-ripple-white" onclick="window.location.href=\'page_index.html?index=' + index + '\'">访问目录</button>\n' +
        '                                <button class="mdui-btn mdui-ripple mdui-ripple-white" onclick="window.location.href=\'page.html?PID=' + PID + '\'">阅读全文</button>\n' +
        '                            </div>\n' +
        '                        </div>\n' +
        '                    </div>\n' +
        '                </div>');
}

if (getCookie("layout")!=='undefined'){
    $('body').addClass(('mdui-theme-layout-'+getCookie("layout"))); //在原来的后面加这个
}

if (getCookie("primary")!=='undefined'){
    $('body').addClass(('mdui-theme-primary-'+getCookie("primary"))); //在原来的后面加这个
}

if (getCookie("accent")!=='undefined'){
    $('body').addClass(('mdui-theme-accent-'+getCookie("accent"))); //在原来的后面加这个
}

function getCookie(cookie_name) {
    let allcookies = document.cookie;
    let value;
    let cookie_pos = allcookies.indexOf(cookie_name);
    if (cookie_pos !== -1) {
        cookie_pos += cookie_name.length + 1;
        let cookie_end = allcookies.indexOf(";", cookie_pos);

        if (cookie_end === -1) {
            cookie_end = allcookies.length;
        }

        value = unescape(allcookies.substring(cookie_pos, cookie_end));
    }
    return value;
}