<?php if (!defined('CMS_VERSION')) {
    exit();
} ?>
<Admintemplate file="Common/Head"/>
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap" id="body" v-cloak>
    <Admintemplate file="Common/Nav"/>

    <div class="h_a">{{ operator.name }}</div>

    <div class="table_full">
        <table width="100%" class="table_form contentWrap">
            <template v-for="module in modules">
                <tr v-for="field in fields">
                    <td>
                        <strong>{{ field.field }}</strong>
                    </td>
                    <td>
                        <input style="width: 100%;" type="text" v-model="module[field.field]" :name="field.field">
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td style="text-align: right;">
                        <a class="btn btn-primary text-center" @click="save(module)"
                           :data-operator="operator.tablename">修改</a>
                    </td>
                </tr>
            </template>
        </table>
    </div>
</div>

<script src="//cdn.bootcss.com/vue/2.1.5/vue.js"></script>
<script>
    $.get("{:U('Index/get_modules',['operator'=>$_GET['operator']])}", {}, function (data) {
        if (data.status) {
            new Vue({
                el: "#body",
                data: data.datas,
                methods: {
                    save: function (item) {
                        var data = item;
                        data.operator = this.operator.tablename;
                        $.post("{:U('Index/save')}", data, function (res) {
                            alert(res.msg);
                        }, 'json');
                    },
                },
                mounted: function () {
                    var vm = this;
                }
            });
        } else {
            $('.table_full').text(data.error);
        }
    }, 'json');
</script>
</body>
</html>
