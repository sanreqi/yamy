<?php
namespace app\modules\mobile\libs;

use Yii;
use app\modules\mobile\libs\RemoteHttp;

/*************************************************
 * 微信API相关接口调用类
 * 封装了和微信相关的所有接口内容
 * ***********************************************
 */
class WeixinApi {
    //微信所有相关接口url
    //@formatter:off
    private $weixinUrls = [
        'accessToken' => 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s',
        'jsApiTicket' => 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=%s&type=jsapi',
        'userInfo' => 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=%s&openid=%s&lang=zh_CN',
        'menuCreate' => 'https://api.weixin.qq.com/cgi-bin/menu/create',
        'menuGet' => 'https://api.weixin.qq.com/cgi-bin/menu/get',
        'menuDelete' => 'https://api.weixin.qq.com/cgi-bin/menu/delete',
        'templateSend' => 'https://api.weixin.qq.com/cgi-bin/message/template/send', //发送模板消息
        'groupSend' => 'https://api.weixin.qq.com/cgi-bin/message/mass/send', //群发（按openid集合）
        'groupSendAll' => 'https://api.weixin.qq.com/cgi-bin/message/mass/sendall', //群发（按组/全部）
        'qrcodeCreate' => 'https://api.weixin.qq.com/cgi-bin/qrcode/create', //创建带参数二维码
        'customSend' => 'https://api.weixin.qq.com/cgi-bin/message/custom/send', //客服发消息
    ];
    //@formatter:on

    //相关常量
    const CACHEKEY_ACCESSTOKEN = 'weixin_access_token';
    const CACHEKEY_JSAPITICKET = 'weixin_jsapi_ticket';

    const SCOPE_BASE = 'snsapi_base';
    const SCOPE_USERINFO = 'snsapi_userinfo';

    //单例模式：实例化对象
    static private $_instance = null;

    //单例模式：构造函数
    private function __construct() {
    }


    /**
     * 单例模式：取得实例化对象
     *
     * @author david@mgz.com
     * @since 2016
     *
     */
    static public function getInstance() {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }


    /**
     * 方法重载的魔术方法
     * 用来处理微信接口调用
     * 一般的微信调用都可以用这个接口处理
     * 类似于 requestMenuGet($postData)、requestAccessToken 之类的
     *
     * @author david@mgz.com
     * @since 2016
     *
     */
    public function __call($funcName, $funcArgs) {
        //如果是预期的函数形式
        if (0 === strpos($funcName, 'request')) {

            //将request后面的apiName抽取出来
            $apiName = lcfirst(substr($funcName, 7, strlen($funcName) - 7));

            //对一些特殊的请求做单独过滤
            if ($apiName == 'XXXXXXXXXXXX') {


            } else if (isset($this->weixinUrls[$apiName])) {

                $postData = [];
                if (!empty($funcArgs) && !empty($funcArgs[0])) {
                    $postData = $funcArgs[0];
                }

                $requestUrl = $this->getWeixinUrls($apiName);
                var_dump($postData);
                $requestResult = $this->requestWeixinApi($requestUrl, $postData);

                return $requestResult;
            }
        } else {
            echo 'Unknown method: ' . $funcName;
            return null;
        }
    }


    //判断请求是否来自于微信客户端
    public function isWeixinClient() {
        if (false === stripos(Yii::$app->request->userAgent, "micromessenger")) {
            return false;
        } else {
            return true;
        }
    }


    /**
     * 检查微信签名是否正确
     *
     * @author david@mgz.com
     * @since 2016
     */
    public function checkSignature($signature, $timestamp, $nonce) {
        $token = Yii::$app->params['wxapiToken'];
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * 得到微信accessToken
     *
     * @author david@mgz.com
     * @since 2016
     *
     * @param $fromCache 默认从缓存中读取，否则将向微信重新获取，并更新缓存
     * @return access token , false if error
     */
    public function requestAccessToken($fromCache = true) {

        //缓存部分使用memCache，可以用的时候加上去
        if ($fromCache) {
            $cachedAccessToken = Yii::$app->cache->get(self::CACHEKEY_ACCESSTOKEN);

            //如果缓存存在，则直接返回该缓存，否则和不fromCache一样，重新去获取
            if (false !== $cachedAccessToken) {
                return $cachedAccessToken;
            }
        }

        $url = sprintf($this->getWeixinUrls('accessToken', false), Yii::$app->params['wxapiAppID'], Yii::$app->params['wxapiAppSecret']);

        $resultArr = $this->requestWeixinApi($url);
        if (@array_key_exists('access_token', $resultArr)) {

            $accessToken = $resultArr['access_token'];
            $expireTime = $resultArr['expires_in'];

            //微信accessToken过期时间为2个小时（7200秒），这里保险减去120秒
            Yii::$app->cache->set(self::CACHEKEY_ACCESSTOKEN, $accessToken, $expireTime - 120);

            return $accessToken;
        } else {
            return false;
        }
    }


    /**
     * 获得微信jsapi-ticket
     *
     * @author david@mgz.com
     * @since 2016
     *
     * @param $fromCache 默认从缓存中读取，否则将向微信重新获取，并更新缓存
     * @return ticket , false if error
     */
    public function requestJsApiTicket($fromCache = true) {
        //缓存部分使用memCache，可以用的时候加上去
        if ($fromCache) {
            $cachedTicket = Yii::$app->cache->get(self::CACHEKEY_JSAPITICKET);

            //如果缓存存在，则直接返回该缓存，否则和不fromCache一样，重新去获取
            if (false !== $cachedTicket) {
                return $cachedTicket;
            }
        }

        $url = sprintf($this->getWeixinUrls('jsApiTicket', false), $this->requestAccessToken());

        $resultArr = $this->requestWeixinApi($url);
        if (@array_key_exists('ticket', $resultArr)) {

            $jsApiTicket = $resultArr['ticket'];
            $expireTime = $resultArr['expires_in'];

            //微信accessToken过期时间为2个小时（7200秒），这里保险减去30秒
            Yii::$app->cache->set(self::CACHEKEY_JSAPITICKET, $jsApiTicket, $expireTime - 30);

            return $jsApiTicket;
        } else {
            return false;
        }
    }


    /**
     * 获得微信已关注用户的用户信息
     *
     * @author david@mgz.com
     * @since 2016
     *
     * @param $openId ，用户的openId
     * @return userInfo array, false if 用户信息没有
     */
    public function requestUserInfo($openId) {
        $url = sprintf($this->getWeixinUrls('userInfo', false), $this->requestAccessToken(), $openId);
        $result = RemoteHttp::get($url);
        return $this->parseRequestResult($result);
    }

    /**
     * 创建带参数的二维码
     *
     * @author david@mgz.com
     * @since 2016
     *
     * @param $openId ，用户的openId ,type 0|1 代表临时或者永久二维码
     * @return qr_url qr图片地址
     */
    public function requestQrcodeUrl($openId, $type) {

        $url = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=";
        $resultArr = $this->qrcodeTicketCreate($openId, $type, 604800);
        if (@array_key_exists('ticket', $resultArr)) {

            $qrTicket = $resultArr['ticket'];
            $qr_url = $url . urlencode($qrTicket);
            return $qr_url;
        } else {
            return false;
        }
    }

    /**
     * 创建带参数的二给码的ticket
     *
     * @author david@mgz.com
     * @since 2016
     *
     * @param $openId ，用户的openId ,type 0|1 代表临时或者永久二维码
     * @return ticket
     */
    private function qrcodeTicketCreate($openId, $type, $expire_seconds = 604800) {

        //临时二维码ticket
        $postArr = [];
        if ($type == 0) {
            //临时格式 {"expire_seconds": 604800, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": 123}}}
            $postArr = [
                "expire_seconds" => $expire_seconds,
                "action_name" => "QR_SCENE",
                "action_info" => [
                    "scene" => [
                        "scene_id" => $openId
                    ]
                ]
            ];
        } else {
            //永久格式 {"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id": 123}}}
            $postArr = [
                "action_name" => "QR_LIMIT_SCENE",
                "action_info" => [
                    "scene" => [
                        "scene_id" => $openId
                    ]
                ]
            ];
        }
        $url = $this->getWeixinUrls('qrcodeCreate');

        return $this->requestWeixinApi($url, $postArr);
    }

    //生成JsApi需要的Signature
    public function generateJsApiSignature($url, $ticket, $noncestr, $timestamp) {
        $parseStr = sprintf('jsapi_ticket=%s&noncestr=%s&timestamp=%s&url=%s', $ticket, $noncestr, $timestamp, $url);
        return sha1($parseStr);
    }


    /**
     * 得到微信接口Url。并带上accessToken
     *
     * @author david@mgz.com
     * @since 2016
     *
     * @param $withAccessToken 默认带上accessToekn
     * @return url, empty if error
     */
    private function getWeixinUrls($id, $withAccessToken = true) {
        if (isset($this->weixinUrls[$id])) {

            if ($withAccessToken) {
                $accessToken = $this->requestAccessToken(true);

                return sprintf("%s?access_token=%s", $this->weixinUrls[$id], $accessToken);
            } else {
                return $this->weixinUrls[$id];
            }
        } else {
            return '';
        }
    }


    /**
     * 判断微信返回是否错误
     *
     * @author david@mgz.com
     * @since 2016
     *
     * @param $result 微信调用接口返回的json_decode结果
     * @return true/false
     */
    public function isError($result) {
        if (empty($result) || $result === false || isset($result['errcode']) && $result['errcode'] != 0) {
            return true;
        } else {
            return false;
        }
    }


    //得到错误信息
    public function getErrorMessage($result) {
        return $result['errmsg'];
    }


    /**
     * 请求微信服务器
     * 用post方法获得weixin返回，post的数据是JSON
     *
     * @author david@mgz.com
     * @since 2016
     *
     * @param $url 要访问的url
     * @param $postArray 要post的数组
     * @return 返回的内容，微信返回的错误会同时返回。其他类型错误，将返回false
     */
    private function requestWeixinApi($url, $postArray = []) {
        if (!empty($postArray)) {
            $result = RemoteHttp::postJson($url, $postArray);
        } else {
            $result = RemoteHttp::get($url);
        }

        return $this->parseRequestResult($result);
    }


    //微信授权相关内容
    public function getUserAuthCodeUrl($redirect_uri, $scope, $state = 'pos') {
        //https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=SCOPE&state=STATE#wechat_redirect
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize";

        //@formatter:off
        //下面顺序必须严格按照微信提供的顺序
        $getParam = [
            'appid' => Yii::$app->params['wxapiAppID'],
            'redirect_uri' => $redirect_uri,
            'response_type' => 'code',
            'scope' => $scope,
            'state' => $state,
            'mshts' => time(),
        ];
        //@formatter:on

        return sprintf("%s?%s#wechat_redirect", $url, http_build_query($getParam));
    }


    //使用code来获得用户的accessToken
    public function getUserAuthAccessTokenByCode($code) {
        //@formatter:off
        $requestUrl = sprintf("https://api.weixin.qq.com/sns/oauth2/access_token?appid=%s&secret=%s&code=%s&grant_type=authorization_code&mshts=%s",
            Yii::$app->params['wxapiAppID'],
            Yii::$app->params['wxapiAppSecret'],
            $code,
            time());
        //@formatter:on
        $result = RemoteHttp::get($requestUrl);
        return $this->parseRequestResult($result);
    }


    //使用refreshToken来更新accessToken（都是用户的，非公号的）
    public function getUserAuthAccessTokenByRefreshToken($refreshToken) {
        $requestUrl = sprintf("https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=%s&grant_type=refresh_token&refresh_token=%s&mshts=%s", Yii::$app->params['wxapiAppID'], $refreshToken, time());
        $result = RemoteHttp::get($requestUrl);
        return $this->parseRequestResult($result);
    }


    //获得用户信息（通过用户的accessToken，非公号的）
    public function getUserInfo($accessToken, $openId) {
        $requestUrl = sprintf("https://api.weixin.qq.com/sns/userinfo?access_token=%s&openid=%s&lang=zh_CN&mshts=%s", $accessToken, $openId, time());
        $result = RemoteHttp::get($requestUrl);
        return $this->parseRequestResult($result);
    }


    /**
     * 对request回来的json字串做出处理然后返回
     *
     * @author david@mgz.com
     * @since 2016
     *
     * @param $result request weixin api得到的json结果
     * @return 结果array, false if 连接过程出错或者json数据格式出错
     */
    private function parseRequestResult($result) {
        if (false === $result) {
            return false;
        } else {
            $jsonResult = json_decode($result, true);
            if (NULL == $jsonResult) {
                return false;
            } else {
                return $jsonResult;
            }
        }
    }


    /**
     * 处理微信服务器发来的消息，并返回处理后的消息信息
     * !!!! 返回的是一个OBJECT！！！
     *
     * @author david@mgz.com
     * @since 2016
     *
     * @param $rawBody XML结构体信息
     * @return Returns an object of class SimpleXMLElement with properties containing the data held within the xml document, 或者在失败时返回 FALSE.
     */
    public function parseMessage($rawBody) {
        if (empty($rawBody))
            return false;

        /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
         the best way is to check the validity of xml by yourself */
        libxml_disable_entity_loader(true);
        $postObj = simplexml_load_string($rawBody, 'SimpleXMLElement', LIBXML_NOCDATA);

        //转换失败则返回
        if (!$postObj)
            return false;

        return $postObj;
    }


    /**
     * 生成需要回复的文本
     *
     * @author david@mgz.com
     * @since 2016
     *
     * @param $content 回复内容
     * @return 返回回复的string
     */
    public function makeReplyText($toUsername, $content) {
        //@formatter:off
        $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                    </xml>";
        //@formatter:on

        $resultStr = sprintf($textTpl, $toUsername, Yii::$app->params['weixinID'], time(), WeixinMessageType::TEXT, $content);

        return $resultStr;
    }


    /**
     * 生成需要回复的图文消息
     *
     * @author david@mgz.com
     * @since 2016
     *
     * @param $contentArray 内容array
     * @return 返回回复的string
     */
    public function makeReplyNews($toUsername, $contentArray) {
        //@formatter:off
        $itemTpl = "<item>
                        <Title><![CDATA[%s]]></Title> 
                        <Description><![CDATA[%s]]></Description>
                        <PicUrl><![CDATA[%s]]></PicUrl>
                        <Url><![CDATA[%s]]></Url>
                    </item>";

        $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[news]]></MsgType>
                        <ArticleCount>%s</ArticleCount>
                        <Articles>
                        %s
                        </Articles>
                    </xml>";
        //@formatter:on

        $itemsContent = '';
        foreach ($contentArray as $content) {
            $itemsContent .= sprintf($itemTpl, $content['title'], $content['description'], $content['picUrl'], $content['url']);
        }

        $resultStr = sprintf($textTpl, $toUsername, Yii::$app->params['weixinID'], time(), count($contentArray), $itemsContent);

        return $resultStr;
    }


    //拼装多客服回复的内容
    public function makeReplyCustomTransfer($toUsername) {
        //@formatter:off
        $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[transfer_customer_service]]></MsgType>
                    </xml>";
        //@formatter:on

        $resultStr = sprintf($textTpl, $toUsername, Yii::$app->params['weixinID'], time());

        return $resultStr;
    }


    //拼装模板postData
    public function makeTemplatePostData($toUsername, $templateId, $url, $contentArray) {
        $returnData = ['touser' => $toUsername, 'template_id' => $templateId, 'url' => $url, 'topcolor' => '#FF6996', 'data' => $contentArray,];
        return $returnData;
    }


}


/*************************************************
 * 微信消息类型常量
 * @author david@mgz.com
 * @since 2016
 *************************************************/
class WeixinMessageType {
    const TEXT = 'text';
    const IMAGE = 'image';
    const VOICE = 'voice';
    const VIDEO = 'video';
    const NEWS = 'news';
    //图文消息
    const LOCATION = 'location';
    const LINK = 'link';
    const EVENT = 'event';
    const CUSTOMER_TRANSFER = 'transfer_customer_service';
}


/*************************************************
 * 微信事件类型常量
 * @author david@mgz.com
 * @since 2016
 *************************************************/
class WeixinEventType {
    const SUBSCRIBE = 'subscribe';
    const UNSUBSCRIBE = 'unsubscribe';
    const QRCODE_SCAN = 'SCAN';
    const REPORT_LOCATION = 'LOCATION';
    const MENU_CLICK = 'CLICK';
    const MENU_URL = 'VIEW';
    const TEMPLATE_SEND_FINISH = 'TEMPLATESENDJOBFINISH';
}
