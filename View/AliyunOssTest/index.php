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
    //授权链接，此链接由后台提供
    var get_token_url = "http://xingxia.de/Oss/AliyunOssTest/getToken";
    var OssData = null;

    //获取凭证
    function getToken() {
        if (!OssData || OssData.expire < new Date().getTime() / 1000) {
            $.ajax({
                url: get_token_url,
                type: 'get',
                dataType: 'json',
                async: false,//使用同步请求，保证 token 的可靠性，如不考虑兼容低版本的浏览器，这里考虑直接使用 async/await
                success: function (res) {
                    OssData = res;
                }
            })
        }
    }

    //上传
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
        formData.append('file', file, file.name);

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
                    // 文件地址
                    var url = res.find('Location').text()

                    //do string
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