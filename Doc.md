## 宅急快递小程序接口文档
> - 部分接口需要使用HTTP头信息添加`Authorization`作为权限验证,值为登录后返回的`authorization`值
---
> ## 登录
### 1.获取OpenID
HTTP: **`POST`**
URL: `{host}/code`

参数说明:
|参数名|类型|描述|示例|
|----|----|---|----|
|**send**|||
|code|string|临时登录凭证code|02150KjH1eYgs807gwjH1GG1kH150Kje|
|**response**|||
|openid|String|用户唯一标识|eyJpdiI6InI1cVlRNWI0b3lBemloXC...|




调用成功的返回值示例：
```json
{
    "errcode": 0,
    "status": 200,
    "errmsg": "请求成功",
    "data": {
        "openid": "oU7a05GPfgn_tIZIDsFR6Xm0tUm4",
        "session_key": "lJFclJAnQ1yU9wIfs6ShvQ=="
    }
}
```

### 2.登录
HTTP: **`POST`**
URL: `{host}/login`

参数说明:
|参数名|类型|描述|示例|
|----|----|---|----|
|**send**|||
|open_id|string|微信用户open_id|olAkh0WP8heDV35dgEXcoOJDZSjc...|
|**response**|||
|authorization|String|鉴权码|eyJpdiI6InI1cVlRNWI0b3lBemloXC...|
|headimg_url|String|头像图片路径|http://sasdas.com/aaa.jpg|
|addresses|Array|收货地址信息|    |
|address_id|String|地址编号|A_00000001|
|name|String|姓名|王二小|
|address|String|地址|湖南省湘潭市雨湖区|
|address_detail|String|地址详情|湘潭大学北苑7栋|
|phone|String|手机号|18670999999|

调用成功的返回值示例：
```json
{
    "errcode": 0,
    "status": 200,
    "errmsg": "请求成功",
    "data": {
        "authorization": "eyJpdiI6InI1cVlRNWI0b3lBemloXC9NRHIzSVwvUT09IiwidmFsdWUiOiJ3SkNNRWgram9mcDhUUjdsRitkd2Z6ZE15SWVPZFYydHpoVmhJZnlFS3p3PSIsIm1hYyI6IjE5ZjViNjVjMmRmM2Y1MWE0ZjIxMjAzYzQ2MjhlNzcwYTIwMzJlNTNhODI0ZDVkMzYyZjA3YTM5YzMzOWY4MWUifQ==",
        "headimg_url": "http://sasdas.com/aaa.jpg",
        "addresses": [
            {
                "address_id": "A_00000001",
                "name": "王二小",
                "address": "湖南省湘潭市雨湖区",
                "address_detail": "湘潭大学北苑7栋",
                "phone": "18670999999"
            }
        ],
        "phone": "18670999999"
    }
}
```

---
> ## 用户相关

### 1.获取验证码
HTTP: **`POST`**
URL: `{host}/msg/send`
参数说明:
|参数名|类型|描述|示例|
|----|----|---|----|
|**send**|||
|open_id|String|微信用户open_id|olAkh0WP8heDV35dgEXcoOJDZSjc...|
|phone|String|手机号|18670999999|
|**response**|||
|captcha|String|验证码123456|

调用成功的返回值示例：
```json
{
    "errcode": 0,
    "status": 200,
    "errmsg": "请求成功",
    "data": {
        "captcha": 597111
    }
}
```

### 2.验证验证码

HTTP: **`POST`**
URL: `{host}/msg/verify`
参数说明:
|参数名|类型|描述|示例|
|----|----|---|----|
|**send**|||
|open_id|String|微信用户open_id|olAkh0WP8heDV35dgEXcoOJDZSjc...|
|phone|String|手机号|18670999999|
|captcha|String|验证码|123456|
|**response**|||
|无|无|无|无|

调用成功的返回值示例：
```json
{
    "errcode": 0,
    "status": 200,
    "errmsg": "请求成功",
    "data": null
}
```

### 3.添加用户(注册)
> 初次登录，验证电话号码无误后进行注册

HTTP: **`POST`**
URL: `{host}/user`
参数说明:
|参数名|类型|描述|示例|
|----|----|---|----|
|**send**|||
|open_id|String|微信用户open_id|olAkh0WP8heDV35dgEXcoOJDZSjc...|
|phone|String|手机号|18670999999|
|headimg_url|String|头像图片路径|http://sasdas.com/aaa.jpg|
|**response**|||
|无|无|无|无|

调用成功的返回值示例：
```json
{
    "errcode": 0,
    "status": 200,
    "errmsg": "请求成功",
    "data": null
}
```

### 4.修改信息
HTTP: **`put`**
URL: `{host}/user`
HTTP头信息:`Authorization:authorization`

参数说明:
|参数名|类型|描述|示例|
|----|----|---|----|
|**send**|||
|phone|String|手机号|18670999999|
|**response**|||
|无|无|无|无|

调用成功的返回值示例：
```json
{
    "errcode": 0,
    "status": 200,
    "errmsg": "请求成功",
    "data": null
}
```

---
> ## 订单相关
### 1.获取本人订单列表
HTTP: **`GET`**
URL: `{host}/order`
HTTP头信息:`Authorization:authorization`

参数说明:
|参数名|类型|描述|示例|
|----|----|---|----|
|**send**|||
|无|无|无|无|
|**response**|||
|order_id|String|订单号|O_00000001|
|order_time|String|下单时间|2018-09-25 14:21:06|
|deliverer_id|String|接单快递员号|U_00000001|
|package_id|String|取货号|1-1-1|
|status|String|状态|已完成|
|money|String|金额|1|
|mark_status|Int|评价状态|0|
|name|String|姓名|王二小|
|address|String|地址|湖南省湘潭市雨湖区|
|address_detail|String|地址详情|湘潭大学北苑7栋|
|phone|String|手机号|18670999999|
|express|String|快递平台|中通|

调用成功的返回值示例：
```json
{
    "errcode": 0,
    "status": 200,
    "errmsg": "请求成功",
    "data": [
        {
            "order_id": "O_00000001",
            "order_time": "2018-09-25 14:21:06",
            "deliverer_id": "U_00000001",
            "status": "已完成",
            "money": "1",
            "mark_status": 0,
            "name": "王二小",
            "address": "湖南省湘潭市雨湖区",
            "address_detail": "湘潭大学北苑1栋",
            "phone": "186709999999",
            "express": "中通"
        }
    ]
}
```
### 2.获取订单信息

HTTP: **`GET`**
URL: `{host}/order/{order_id}`
HTTP头信息:`Authorization:authorization`

参数说明:
|参数名|类型|描述|示例|
|----|----|---|----|
|**send**|||
|无|无|无|无|
|**response**|||
|order_id|String|订单号|O_00000003|
|address_id|String|地址编号|A_00000001|
|package_id|String|取货码|10-22|
|insurance|String|保险金额|1|
|money|String|费用|2|
|package_size|String|包裹大小|2|
|status|String|状态|已接单|
|deliverer_id|String|快递员编号|D_00000001|
|order_time|String|下单时间|2018-09-25 14:21:18|
|take_order_time|String|接单时间|2018-09-25 14:21:18|
|finish_time|String|完成时间|2018-09-25 14:21:18|
|mark|String|评价|100|
|mark_status|int|评价状态|0|
|note|String|备注|无|
|cancel_reason|String|取消原因|不想要了|
|created_at|String|创建时间|2018-09-25 14:21:18|
|updated_at|String|更新时间|2018-09-25 14:21:18|
|name|String|姓名|王二小|
|address|String|地址|湖南省湘潭市雨湖区|
|address_detail|String|地址详情|湘潭大学北苑7栋|
|phone|String|手机号|18670999999|
|express|String|物流平台|申通|

调用成功的返回值示例：
```json
{
    "errcode": 0,
    "status": 200,
    "errmsg": "请求成功",
    "data": {
        "order_id": "O_00000003",
        "address_id": "A_00000001",
        "package_id": "10-22",
        "insurance": "1",
        "money": "1",
        "package_size": "2",
        "status": "未接单",
        "deliverer_id": null,
        "order_time": "2018-09-25 14:21:18",
        "take_order_time": null,
        "finish_time": null,
        "mark": null,
        "mark_status":0,
        "note": null,
        "cancel_reason": null,
        "created_at": "2018-09-25 14:21:18",
        "updated_at": "2018-09-25 14:21:18",
        "name": "王二小",
        "address": "湖南省湘潭市雨湖区",
        "address_detail": "湘潭大学北苑1栋",
        "phone": "18670999791",
        "express": "中通"
    }
}
```

### 3.下单

HTTP: **`POST`**
URL: `{host}/order`
HTTP头信息:`Authorization:authorization`

参数说明:
|参数名|类型|描述|示例|
|----|----|---|----|
|**send**|||
|address_id|String|地址编号|A_00000001|
|express_id|String|物流编号|Express_zhongtong|
|package_id|String|取货码|11-2-2|
|insurance|String|保险额|2|
|money|String|费用|3|
|package_size|String|包裹重量|2|
|**response**|||
|无|无|无|无|

调用成功的返回值示例：
```json
{
    "errcode": 0,
    "status": 200,
    "errmsg": "请求成功",
    "data": null
}
```

### 4.申请取消订单


HTTP: **`put`**
URL: `{host}/order/apply-cancel/{order_id}`
HTTP头信息:`Authorization:authorization`

参数说明:
|参数名|类型|描述|示例|
|----|----|---|----|
|**send**|||
|无|无|无|无|
|**response**|||
|status_code|String|申请后状态码|4|
|status|String|申请后状态|已取消|

调用成功的返回值示例：
```json
{
    "errcode": 0,
    "status": 200,
    "errmsg": "请求成功",
    "data": {
        "status_code": "4",
        "status": "已取消"
    }
}
```

### 5.确认完成订单

HTTP: **`put`**
URL: `{host}/order/confirm/{order_id}`
HTTP头信息:`Authorization:authorization`

参数说明:
|参数名|类型|描述|示例|
|----|----|---|----|
|**send**|||
|无|无|无|无|
|**response**|||
|无|无|无|无|

调用成功的返回值示例：
```json
{
    "errcode": 0,
    "status": 200,
    "errmsg": "请求成功",
    "data": null
}
```

### 6.评价订单 

HTTP: **`put`**
URL: `{host}/order/mark/{order_id}`
HTTP头信息:`Authorization:authorization`

参数说明:
|参数名|类型|描述|示例|
|----|----|---|----|
|**send**|||
|mark|String|评论|挺好的|
|**response**|||
|无|无|无|无|

调用成功的返回值示例：
```json
{
    "errcode": 0,
    "status": 200,
    "errmsg": "请求成功",
    "data": null
}
```

---
> ##  地址相关 
### 1.添加地址

HTTP: **`POST`**
URL: `{host}/address/`
HTTP头信息:`Authorization:authorization`

参数说明:
|参数名|类型|描述|示例|
|----|----|---|----|
|**send**|||
|name|String|姓名|收货人姓名|
|address|String|地址|湖南省湘潭市雨湖区|
|address_detail|String|地址详情|湘潭大学北苑7栋|
|latitude|String|经度|15.65|
|longitude|String|纬度|31.5|
|phone|String|手机号|18670999999|
|**response**|||
|无|无|无|无|

调用成功的返回值示例：
```json
{
    "errcode": 0,
    "status": 200,
    "errmsg": "请求成功",
    "data": null
}
```

### 2.删除地址 

HTTP: **`DELETE`**
URL: `{host}/address/{address_id}`
HTTP头信息:`Authorization:authorization`

参数说明:
|参数名|类型|描述|示例|
|----|----|---|----|
|**send**|||
|无|无|无|无|
|**response**|||
|无|无|无|无|

调用成功的返回值示例：
```json
{
    "errcode": 0,
    "status": 200,
    "errmsg": "请求成功",
    "data": null
}
```

### 3.修改地址

HTTP: **`put`**
URL: `{host}/address/{address_id}`
HTTP头信息:`Authorization:authorization`

参数说明:
|参数名|类型|描述|示例|
|----|----|---|----|
|**send**|||
|name|String|姓名|收货人姓名|
|address|String|地址|湖南省湘潭市雨湖区|
|address_detail|String|地址详情|湘潭大学北苑7栋|
|phone|String|手机号|18670999999|
|**response**|||
|无|无|无|无|

调用成功的返回值示例：
```json
{
    "errcode": 0,
    "status": 200,
    "errmsg": "请求成功",
    "data": null
}
```


---
> ##  快递员相关
### 1.获取快递可接单列表

HTTP: **`GET`**
URL: `{host}/deliverer/order/{start}/{limit}`
HTTP头信息:`Authorization:authorization`

`start`为数据起始点,`limit`为数据长度

参数说明:
|参数名|类型|描述|示例|
|----|----|---|----|
|**send**|||
|无|无|无|无|
|**response**|||
|order_id|String|订单号|O_00000001|
|package_size|String|快递大小|2|
|address|String|地址|湘潭市雨湖区|
|address_detail|String|地址详情|湘潭大学北苑7栋|
|phone|String|手机号|18670999999|
|express|String|快递平台|中通|
|note|String|备注|加急|
|order_time|String|下单时间|2018-09-25 18:55:49|

调用成功的返回值示例：
```json
{
    "errcode": 0,
    "status": 200,
    "errmsg": "请求成功",
    "data": [
        {
            "order_id": "O_00000008",
            "package_size": "2",
            "address": "湖南省湘潭市雨湖区",
            "address_detail": "湘潭大学北苑1栋",
            "note": null,
            "order_time": "2018-09-25 18:55:49",
            "express": "中通"
        },
        {
            "order_id": "O_00000008",
            "package_size": "2",
            "address": "湖南省湘潭市雨湖区",
            "address_detail": "湘潭大学北苑1栋",
            "note": null,
            "order_time": "2018-09-25 18:55:49",
            "express": "中通"
        }
    ]
}
```
### 2.获取自己接的单列表

### 2.接单

HTTP: **`POST`**
URL: `{host}/deliverer/receive/{order_id}`
HTTP头信息:`Authorization:authorization`

参数说明:
|参数名|类型|描述|示例|
|----|----|---|----|
|**send**|||
|无|无|无|无|
|**response**|||
|无|无|无|无|

调用成功的返回值示例：
```json
{
    "errcode": 0,
    "status": 200,
    "errmsg": "请求成功",
    "data": null
}
```

### 3.取消订单


HTTP: **`put`**
URL: `{host}/deliverer/cancel/{order_id}`
HTTP头信息:`Authorization:authorization`

参数说明:
|参数名|类型|描述|示例|
|----|----|---|----|
|**send**|||
|无|无|无|无|
|**response**|||
|status_code|String|申请后状态码|4|
|status|String|申请后状态|已取消|

调用成功的返回值示例：
```json
{
    "errcode": 0,
    "status": 200,
    "errmsg": "请求成功",
    "data": {
        "status_code": "4",
        "status": "已取消"
    }
}
```

### 5.拒绝取消订单


HTTP: **`put`**
URL: `{host}/deliverer/refuse-cancel/{order_id}`
HTTP头信息:`Authorization:authorization`

参数说明:
|参数名|类型|描述|示例|
|----|----|---|----|
|**send**|||
|无|无|无|无|
|**response**|||
|status_code|String|申请后状态码|6|
|status|String|申请后状态|已拒绝|

调用成功的返回值示例：
```json
{
    "errcode": 0,
    "status": 200,
    "errmsg": "请求成功",
    "data": {
        "status_code": "6",
        "status": "已拒绝"
    }
}
```

### 6.获取电话


HTTP: **`put`**
URL: `{host}/deliverer/phone/{deliverer_id}`
HTTP头信息:`Authorization:authorization`

参数说明:
|参数名|类型|描述|示例|
|----|----|---|----|
|**send**|||
|无|无|无|无|
|**response**|||
|phone|String|电话号|18670999999|

调用成功的返回值示例：
```json
{
    "errcode": 0,
    "status": 200,
    "errmsg": "请求成功",
    "data": {
        "phone": "18670999999",
    }
}
```

### 6.获取快递员信息


HTTP: **`get`**
URL: `{host}/deliverer/info/{deliverer_id}`
HTTP头信息:`Authorization:authorization`

参数说明:
|参数名|类型|描述|示例|
|----|----|---|----|
|**send**|||
|无|无|无|无|
|**response**|||
|phone|String|电话号|18670999999|
|name|String|姓名|冯快快|
|mark|String|评价|5|
|order_money|String|总收入|300|
|order_count|String|总订单|100|
|order_money_today|String|今日收入|88|
|order_count_today|String|今日订单|26|

调用成功的返回值示例：
```json
{
    "errcode": 0,
    "status": 200,
    "errmsg": "请求成功",
    "data": {
        "phone": "18670999791",
        "name": "冯快快",
        "mark": "5",
        "order_money": "300",
        "order_count": "100",
        "order_money_today": "88",
        "order_count_today": "26"
    }
}
```

---
> ##  地图相关
### 3.获取距离

HTTP: **`POST`**
URL: `{host}/distance/{start_latitude}/{start_longitude}/{end_latitude}/{end_longitude}`
HTTP头信息:`Authorization:authorization`


参数说明:
|参数名|类型|描述|示例|
|----|----|---|----|
|**send**|||
|start_latitude|String|起点纬度|111.11|
|start_longitude|String|起点经度|111.11|
|end_latitude|String|终点纬度|111.11|
|end_longitude|String|终点经度|111.11|
|**response**|||
参考百度api

调用成功的返回值示例：
```json
{
    "errcode": 0,
    "status": 200,
    "errmsg": "请求成功",
    "data": [
        {
            "order_id": "O_00000008",
            "package_size": "2",
            "address": "湖南省湘潭市雨湖区",
            "address_detail": "湘潭大学北苑1栋",
            "note": null,
            "order_time": "2018-09-25 18:55:49",
            "express": "中通"
        },
        {
            "order_id": "O_00000008",
            "package_size": "2",
            "address": "湖南省湘潭市雨湖区",
            "address_detail": "湘潭大学北苑1栋",
            "note": null,
            "order_time": "2018-09-25 18:55:49",
            "express": "中通"
        }
    ]
}
```

---
> ##  错误码
|错误码|错误详情|
|--|--|
|0|请求成功|
|-4001|缺失参数|
|-4002|用户未注册|
|-4003|手机号已注册|
|-4004|微信号已注册|
|-4005|注册失败|
|-4006|修改失败|
|-4007|open_id参数错误|
|-4008|user_id参数错误|
|-4009|address_id参数错误|
|-4010|express_id参数错误|
|-4011|package_id参数错误|
|-4012|insurance参数错误|
|-4013|package_size参数错误|
|-4014|status参数错误|
|-4015|phone参数错误|
|-4016|deliverer_id参数错误|
|-4017|name参数错误|
|-4018|address参数错误|
|-4019|address_detail参数错误|
|-4020|添加失败|
|-4021|删除失败|
|-4022|下单失败|
|-4023|鉴权失败|
|-4024|接单失败|
|-4025|order_id参数错误|
|-4026|不可确认状态|
|-4027|不可接单状态|
|-4028|不可取消状态|
|-4029|不可评价状态|
|-4030|此账户不可接单|
|-4031|money参数错误| 
|-4032|code无效| 
|-4033|频率限制，每个用户每分钟100次|
|-4034|系统繁忙，此时请开发者稍候再试| 
|-4035|未知错误| 
|-4036|code参数错误| 
|-4037|code已被使用|
|-4038|短信发送失败|
|-4039|一分钟内只可发送一次|
|-4040|captcha参数错误|
|-4041|验证码失效|
|-4042|验证码错误|
|-4043|无发送记录|
|-4044|手机号在黑名单库|
|-4045|手机号格式错误|
|-4046|短信发送频繁|
|-4047|发送时间限制|
|-4048|获取地点失败|
|-4049|搜索地点失败|
|-4050|user鉴权失败|
|-4051|此账户不可操作|
|-4052|不可操作状态|