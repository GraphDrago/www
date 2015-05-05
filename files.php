<html>
<form method="post" action="" enctype="multipart/form-data">
    <input name="file[]" id="file" type="file" multiple="true" />
</form>
<ul id = "log"></ul>

<script>
    $('#file').on('change', function (evt){
        var input = document.getElementById('file');

        // FileList object
        var output = [];
        for (var i = 0; i < input.files.length; i++) {
            output.push('<li>'+ input.files.name + '</li>');
        }
        $('#log').append('Выбранные файлы:'+output.join(''));
        $('#count').val(i);}
    });


</script>
</html>
<?
if(count($_FILES['uploads']['filesToUpload'])) {
    foreach ($_FILES['uploads']['filesToUpload'] as $file) {
        //загружаем файлы
        echo $file;
    }
}