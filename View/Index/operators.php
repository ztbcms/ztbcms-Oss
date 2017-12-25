<?php if (!defined('CMS_VERSION')) exit(); ?>
<Admintemplate file="Common/Head"/>
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">
    <Admintemplate file="Common/Nav"/>
    <div class="h_a">平台配置</div>
    <form action="{:U('Index/addOperator')}" method="post">
        <div class="table_full">
            <table class="table_form hidden" width="100%" cellspacing="0" id="operators" v-cloak>
                <tbody>
                <tr>
                    <th>平台名称</th>
                    <th>表名</th>
                    <th>描述</th>
                    <th>操作</th>
                </tr>
                <tr v-for="operator in operators" :operator="operator">
                    <td>{{ operator.name }}</td>
                    <td>{{ operator.tablename }}</td>
                    <td>{{ operator.remark }}</td>
                    <td>
                        <a @click="conf" :data-operator="operator.tablename" href="javascript:">参数设置</a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </form>
</div>


<script src="//cdn.bootcss.com/vue/2.1.5/vue.min.js"></script>
<script>
    $.get("{:U('Index/get_operators')}", null, function (data) {
        if (data.status) {
            new Vue({
                el: "#operators",
                data: data.datas,
                methods: {
                    conf: function (e) {
                        window.location.href = "{:U('Index/modules')}&operator=" + $(e.toElement).data('operator');
                    }
                },
                mounted: function () {
                    var vm = this;
                    $(vm.$options.el).removeClass('hidden');
                }

            })
        } else {
            $('.table_full').text(data.error);
        }
    }, 'json');
</script>
</body>
</html>
