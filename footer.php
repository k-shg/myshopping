<footer id="footer">
        Copyright shogo. All Rights Reserved.

</footer>
<script
    src="http://code.jquery.com/jquery-3.3.1.js"
    integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
    crossorigin="anonymous"></script>

<script type="text/javascript">

$(function(){

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
});

</script>
