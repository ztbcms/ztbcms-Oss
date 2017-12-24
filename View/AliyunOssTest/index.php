<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>


<input type="file" name="file" id="file">
<button onclick="upload()">提交</button>

<script src="//cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
<script>
    var get_token_url = "http://ztbcms.de/Oss/AliyunOssTest/getToken";
    var expire_time = new Date().getTime();
    var OssData = null;

    function getToken() {
        if (!OssData || OssData.expire < expire_time / 1000) {
            $.ajax({
                url: get_token_url,
                type: 'get',
                dataType: 'json',
                async: false,
                success: function (res) {
                    console.log(res);
                    OssData = res;
                }
            })
        }
    }

    function upload() {
        getToken();
        var formData = new FormData();
        // 添加签名信息
        formData.append('OSSAccessKeyId', OssData.accessid);
        formData.append('policy', OssData.policy);
        formData.append('Signature', OssData.signature);
        formData.append('key', OssData.dir);
        formData.append('success_action_status', '201');

        // 添加文件
        var file = document.getElementById('file').files[0];
        formData.append('file', file, file.filename);

        $.ajax({
            url: OssData.host,
            data: formData,
            dataType: 'xml',
            processData: false,
            contentType: false,
            type: 'POST',
            success: function (data) {
                console.log(data)
                if ($(data).find('PostResponse')) {
                    var res = $(data).find('PostResponse');
                    console.info('Bucket：' + res.find('Bucket').text());
                    console.info('Location：' + res.find('Location').text());
                    console.info('Key：' + res.find('Key').text());
                    console.info('ETag：' + res.find('ETag').text());
                }
            },
            error: function (res) {
                console.log(res)
            }
        })
    }
</script>
</body>
</html>