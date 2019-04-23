<footer id="footer">
        Copyright shogo. All Rights Reserved.

</footer>
<script
    src="https://code.jquery.com/jquery-3.3.1.js"
    integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
    crossorigin="anonymous"></script>

<script type="text/javascript">

$(function(){


    $('.js-login-button').on('click', function() {

        $('.js-set-email').val($('.js-get-email').text());
        $('.js-set-pass').val($('.js-get-pass').text());
    });


    //入力文字数カウント
    $('.js-get-count').on('keyup', function(e) {
        $('.js-show-count').text($(this).val().length);
    });




    //メッセージに値が入っていたらトグルする

    var $msg = $('.js-flash-msg');
    //メッセージがなくても空白が入ってしまうため、空白はすべて取り除く。
    text = $msg.text().replace(/\s+/g, "");

    if(text) {
        $msg.slideToggle('slow');

        setTimeout(function() {
            $msg.slideToggle('slow');
        }, 1200);
    }


    var $inputFile = $('.js-input-file');

    //画像をアップロードして変更があったとき
    $inputFile.on('change', function(){

        //ファイルオブジェクト、imgタグ、読み込み変数を定義
        var file = this.files[0],
            $img = $(this).siblings('.pre-img'),
            Reader = new FileReader();

        //読み込みが完了したときの処理をセット
        Reader.onload = function() {
            $img.attr('src', Reader.result);
        }
        //ファイルを読み込む
        Reader.readAsDataURL(file);
    });


    // フッターを最下部に固定
    var $ftr = $('#footer');
    if( window.innerHeight > $ftr.offset().top + $ftr.outerHeight() ){
      $ftr.attr({'style': 'position:fixed; top:' + (window.innerHeight - $ftr.outerHeight()) +'px;' });
    }

});

</script>
