# 前端的HTML设计

## 结构

已经提供了工具入口文件 `view.php` ,其对应的默认模板存放文件夹是 `views/`。入口文件名可以随意修改，但是当修改默认的模板文件夹时，请修改对应的入口文件中的 `VIEWS` 后面的值改为对应的名称。

在实际使用中最好不要修改，使用入口名为`index.php`即可，及下面的目录结构

    /www/xiaomuzhi
    | - statics/
    | - upload/
    | - views/
    | - index.php

这样组织最佳。

修改index.php 中**常量值** `GLOBAL_URL` 修改HTTP路径。


### 关于静态文件组织结构

在使用静态文件的时候，公用静态文件和项目所使用的静态文件请分开存放，


    |-- static/                     # 静态文件
        |   |-- css/                # 公用基础css文件
        |   |   |-- site/           # APP1 私有css
        |   |   |-- adminer/        # APP2 私有css
        |   |   |-- reset.css       # 公用css文件
        |   |   |-- jquery.ui.css 
        |   |   `......
        |   |-- js/
        |   |   |-- site/           # APP1 私有 Js
        |   |   |-- adminer/        # APP2 私有 Js
        |   |   |-- jquery-1.10.2.js
        |   |   |-- jquery-2.0.3.js
        |   |   `......
        |   |-- img                 # css用图片
        |   |   `......
        |   `......                 # 存放其他静态文件

在使用素材的时候，比如demo素材（非css使用和ui使用，仅指临时文件）时，可以在static文件夹下创建一个临时用的文件夹，比如`uidemo`来存放，而非是和css所使用的img存放一处。

另外css所使用的img原则上可以和css存放同一处，还是建议放在和css对应的img文件夹中。

关于静态文件的支持站点分离的使用方法可以阅读下面的[函数](#funs)部分。

### 模板文件结构

根据**栏目**以及**栏目的深度**进行构建文件结构。

现有的模板设计是根据 **ULR 路径**来默认处理对应文件路径。
假设默认的入口文件为 `index.php` ,认的模板文件文件夹为 `views/`。

路径                  | 对应模板文件
--------------------|----------------------------
index.php/          | **welcome.php**
index.php/list      | list.php
index.php/list/1/23 | list.php
index.php/list/show | list/show.php
index.php/list_show | list_show.php

当URL地址路径的`数字开始后都为参数存储在$reg`，如果前端要使用的话可以根据数字个数来通过 `$reg[index]` 来获得值。

> index 索引从 0 开始

`index.php/list/1/23` 可以获得 `$reg`

    array(
        0 => 1
        1 => 23
    )

在js脚本中使用

    <script>
        var a1 = <?php echo $reg[0] ?>
    </script>

> 请单独设计 **404** 页面。

## 函数 {#funs}

使用简单的函数，节约时间和增加代码的复用，要求前端会使用几个预备函数。

> 使用这几个函数主要是为了后期的静态资源分离和服务器架构做准备。

**引用模块** : 增强重用代码，使内容结构话。

    <?php include_once(VIEWS.'filepath.php') ?>

**静态文件**引用， 使用这个函数的主要原因为站点的分离。

    <?php echo static_file('x.js|x.css|x.png...') ?>

js/css 文件会自动寻找 **STATIC_PATH** 下的 js/css 文件夹中的文件以及 `.min.js/css` 文件，开发模式下会自动加上时间戳，即不会使用浏览器的缓存文件。 然后生成对应的 `script`/`link`标签。 css支持 接受第二参数`print`。 给出第二参数为`true`时返回的是 url 路径。

非 js/css 文件要求必须给出 **STATIC_PATH** 下的相对路径 比如 `img/a.png` ，返回的一律是对应的 url路径。

**链接生成**

    <a href="<?php echo site_url();?>" ... >...</a>
    <a href="<?php echo site_url(a/b);?>" ... >...</a>

**JSON**输出,该函数用于ajax调式时输出json数据的页面，不要在普通的页面中使用该函数。

    json_echo('{json_string} or array')


### 几个常量值

    GLOBAL_URL  # 主页路径
    STATIC_URL  # 静态文件的路径
    UPLOAD_URL  # 上传文件的路径
    ADMINER_URL # 后台路径

## 数据约定

约定数据可以有效的交代该页面需要什么样的数据，以及大体的数据结构。页面中数据大体分为 列表、单个信息主题、具体值，json数据，判定值等。

在页面的头部中标记所需要的数据，在数据插入位置写入对应的标记以方便查找。

例如 `list.php` 和 `list/show.php`

    <?php 
    // VDATA
    // 大标题
    // 历史记录 list page /1  20个
    // banners list json 轮番图片 3 个
    // read 阅读数目 
    ?>
    <?php include_once VIEWS.'inc_header.php'; ?>
    <?php include_once VIEWS.'inc_sidebar.php'; ?>

    <h2>
    <!-- 大标题 -->
    hello ,this is test
    <!-- read 阅读数目 -->
    <span class="read">20</span>
    </h2>

    <!-- banners 图片 -->
    <script>
        banners ={
            "img1": "<?php echo static_file('banner/b1.png') ?>"
            ,"img2": "<?php echo static_file('banner/b2.png') ?>"
            ,"img3": "<?php echo static_file('banner/b3.png') ?>"
        }
    </script>

    <div class="banner"> 
    js处理banner变量...
    </div>

    <ul>
        <!-- list page 1 -->
        <li> <a href="<?php echo site_url('test/show/2');?>">test/show/2</a> </li>
        <li> <a href="<?php echo site_url('test/show/3');?>">test/show/3</a> </li>
        <li> <a href="<?php echo site_url('test/show/4');?>">test/show/4</a> </li>
        <li> <a href="<?php echo site_url('test/show/5');?>">test/show/5</a> </li>
        <li> <a href="<?php echo site_url('test/show/6');?>">test/show/6</a> </li>
        <li> <a href="<?php echo site_url('test/show/7');?>">test/show/7</a> </li>
    </ul>

    <!-- 分页页码 -->
    <div class="pagination">
        <ul>
            <li><a href="#">Prev</a></li>
            <li><a href="<?php echo site_url('test/1');?>">1</a></li>
            <li><a href="<?php echo site_url('test/2');?>">2</a></li>
            <li class="disabled"><a href="#">3</a></li>
            <li class="active"><a href="#">4</a></li>
            <li><a href="#">5</a></li>
            <li><a href="#">Next</a></li>
        </ul>
    </div>

    <?php include_once VIEWS.'inc_footer.php'; ?>

具体所引用的引用文件中的数据约定则写在其文件中，具体数据分配和详细的结构不需要前端来描述，写个简单大概即可。PHP前端数据处理就可以根据给出的大概的数据结构定义处理后的结果，放入对应位置即可。

另外需要判定的地方请用注释注明，比如登录后的状态，以及一些切换按钮等。
    
> 关于对列表页面分页的HTML结构已经定义，需要定义样式

    .pagination ul{}
    .pagination ul li.disabled{}
    .pagination ul li.active{}
    .pagination ul li.active a{}
    .pagination ul li.active a{}

    
## 关于JS和ajax

在使用JS文件前的数据初始化，将常用的常量值输出，为分离js片段做准备工作。比如

    <script type="text/javascript" charset="utf-8">
        //  初始化路径
        var STATIC_URL  = "<?php echo STATIC_URL  ?>" ;
        var UPLOAD_URL  = "<?php echo UPLOAD_URL  ?>" ;
    </script>

将js和PHP**分离**,不做混合编程。 在JS代码中使用PHP的输出的值时，必须在php页面中实现**约定**定义json数据结构和简单注释，然后js引用这些值。而非在使用时采取定义。

在某一个但页面中使用js多变量值时，尽量防止变量的污染。比如 list.php 中

    var var_list = {
        url_a:....
        ,btn_b:....
        ,....
    }

这样基本上就不会产生污染全局变量。在js中使用**PHP提供的数据**和作为参数传输时，初始定义变量集合，然后再给js使用，不要在js逻辑处理中使用php输出的值。

对IE支持HTML5辅助JS文件 `html5.js`

    <!-- 提供HTML5支持 Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <?php 
        echo static_file('html5.js');
    ?>
    <![endif]-->

其他还还有 respond.js, modernizr.js, json2.js 等。


### AJAX

在使用**AJAX**时，也要约定数据，以及协议

* 协议 post/get 
* data json数据
* return 获取json

关于返回数据的定义，必须带有两个值，状态和消息，其他为自定义。即

    {
        "status":"0/1"
        ,"msg":"消息"
        ,"data":"自定义结构"
        ,...
    }

ajax路径也可以自己来定义，用 `_ajax`,`_json` 为后缀的路径模板，将json字符串数据放入其中,在此处使用`json_echo`函数，输出json数据，这样子就可以在设计HTML阶段进行ajax调试，PHP程序员则根据json数据结构输出对应的数组结构即可。

    <?php
    // 数据约定定义

    // 下面两种格式交付于 json_echo 输出
    
    // PHP Array 格式
    $data_ajax = array(
        'status' => '0'
        ,'msg'   => '消息内容'
        ,"data"  => '自定义结构，数组以及其他'
    );

    // 原生写法
    // header('Vary: Accept');
    // if (isset($_SERVER['HTTP_ACCEPT']) && (strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)) {
    //      header('Content-type: application/json');
    // } else {
    //      header('Content-type: text/plain');
    // }
    // echo json_encode($data_ajax);

    //-------------------------------------------------------------------------------
    //前端写法

    // 前端所看到的JSON结构
    // String 格式 javascript object struct 
    $data_ajax = '{
        "status":"0/1"
        ,"msg":"消息"
        ,"data":"自定义结构"
    }';
    
    // 这里使用了在index中定义的函数
    echo json_echo($data_ajax);

    // 原生写法
    // 在自己的项目中，字符串形式下使用下面的代码输出
    // header('Vary: Accept');
    // header('Content-type: application/json');
    // echo $data_ajax;


另外最好也验证200等错误时返回。

    $.ajax({
        url: '/path/to/file',
        type: 'default GET (Other values: POST)',
        dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
        data: {param1: 'value1'},
    })
    .done(function(data) {
        if (data.status==1) {};
        console.log("success");
    })
    .fail(function(XMLHttpRequest, textStatus, errorThrown){  // 验证header头部返回的错误。
        m = XMLHttpRequest.responseJSON;
        console.log("error:"+m);
    })
    .always(function() {
        console.log("complete");
    });

不建议使用ajax 获取HTML代码，而是获取JSON数据，对数据进行绑定HTML代码。
可以使用javascript模板js库来处理，推荐使用`artTemplate`(已经在static/js/template.js)中，了解[使用方法](http://aui.github.io/artTemplate/)。

## 交付

模板完成后，将父文件夹交与即可，或者只交付 static 和 views 文件夹也可以。