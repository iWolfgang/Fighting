# API请求规范

## 接口访问方式
服务端接口以restful－api的标准提供——传输依赖HTTP协议，已URL的形式访问服务端，服务端返回约定格式的json数据。

## 公共请求参数
|参数名称|描述|示例|
|---|---|---|
|v|app 版本号|1.0.1|
|os|操作系统版本 android 或 ios|android|
|osv|操作系统版本|6.0|
|token|用户登录的token 未登录状况 传空|一串token的字符|
|timestamp|客户端当前时间戳|1516864493|
|sk|请求的随机字符串 随机int即可 计算签名使用|1331|
|device_id|设备唯一标识 android为手机的IMEI ios用方法计算|447769804451095|
|device_name|设备名称 android获取 android.os.Build.MODEL |HUAWEI MT7-TL00|
|sign|请求签名 根据签名算法得出 |5fb2cac284b627513cf37e86a65d249a|

## 签名算法
### 所有动态API调用均必须签名

### 签名逻辑如下：

```
将v,os,token(若不存在，值为空字符串),timestamp,sk参数根据参数名称按照字母先后顺序排序。 【例如将foo=1, bar=2, baz=3排序为bar=2, baz=3, foo=1】

序连参数「名称/值」对和密钥 【例如*******】

计算此字串的md5() 杂凑值

将此值附加至参数清单，名称为sign 【例如sign=**********】

