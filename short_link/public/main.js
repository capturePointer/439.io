$(function() {
    new Clipboard('.output button');

    var validateUrl = function() {
        var reg = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
        $('.input input').on('input', function() {
            if (reg.test($(this).val())) {
                $('.input button').prop('disabled', false);
            } else {
                $('.input button').prop('disabled', true);
            }
        });
    };

    validateUrl();

    var setOutput = function(shortId) {
        //var url = $('.output a').attr("data-url-pre") + '/' + shortId;
        var url = location.protocol + '//' + location.host + '/' + shortId;
        $('.output a').attr('href', url).text(url);
        $('.output button').attr('data-clipboard-text', url);
        //$('.content img').attr('src', $(".content img").attr("data-src-pre") + '/' + shortId);
        $('.content img').attr('src', '//490.io/qr/' + shortId);
    };
    $('.input button').on('click', function() {
        $('.output').hide();
        $('.content img').hide();
        $('#load').show();
        var longUrl = $('.input input').val();
		var postUrl = $("#btn-go").attr("data-url");
        $.post(postUrl, {
            url: longUrl
        }, function(data) {
            //var data = eval('(' + data + ')');
            $('#load').hide();
            if (data && 0==data['error']) {
                setOutput(data['data']['hash_id']);
                $('.output').show();
                $('.content img').show();
            }
        },
		'json');
    });
});
