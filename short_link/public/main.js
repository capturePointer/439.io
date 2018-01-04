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
        var url = location.protocol + '//' + location.host + '/' + shortId;
        $('.output a').attr('href', url).text(url);
        $('.output button').attr('data-clipboard-text', url);
        $('.content img').attr('src', '//qr.302.at/chart?chs=150x150&cht=qr&choe=UTF-8&chl=' + url);
    };
    $('.input button').on('click', function() {
        $('.output').hide();
        $('.content img').hide();
        $('#load').show();
        var longUrl = $('.input input').val();
        $.post('/', {
            longUrl: longUrl
        }, function(data) {
            $('#load').hide();
            if (data && data.is_url) {
                setOutput(data.short);
                $('.output').show();
                $('.content img').show();
            }
        });
    });
});
