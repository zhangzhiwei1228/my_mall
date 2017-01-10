<?php
/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 16-12-13
 * Time: 下午2:02
 */

require_once LIB_DIR."Sdks/JPush/autoload.php";
/*require_once LIB_DIR."Sdks/jpush/Client.php";*/
use JPush\Client as JClient;
class Jpush {
    private $app_key = '99aca57ea1c603110cb1c381';
    private $master_secret = 'f427eef0ad87f9aaf18f360f';
    private $client;
    public function __construct(){
        $this->client   = new JClient($this->app_key, $this->master_secret,LOG_DIR.'jpush.log');
    }

    public function push($alias, $type = false, $data = false) {
        try {
            switch($type){
                case 1:
                    $title = '消息';
                    break;
                case 2:
                    $title = '新品来了！！！';
                    break;
                case 3:
                    $title = '订单发货了！！！';
                    break;
                default:
                    $title = '其他';
                    break;
            }
            $response = $this->client->push()
                ->setPlatform(array('ios', 'android'))
                // 一般情况下，关于 audience 的设置只需要调用 addAlias、addTag、addTagAnd  或 addRegistrationId
                // 这四个方法中的某一个即可，这里仅作为示例，当然全部调用也可以，多项 audience 调用表示其结果的交集
                // 即是说一般情况下，下面三个方法和没有列出的 addTagAnd 一共四个，只适用一个便可满足大多数的场景需求

                ->addAlias($alias)
                //->addTag(array('tag1', 'tag2'))
                // ->addRegistrationId($registration_id)

                ->setNotificationAlert($title)
                ->iosNotification($data['title'], array(
                    'sound' => 'sound.caf',
                    // 'badge' => '+1',
                    // 'content-available' => true,
                    // 'mutable-content' => true,
                    'category' => 'jiguang',
                    'extras' => $data['extras'],
                ))
                ->androidNotification($data['title'], array(
                    'title' => $data['title'],
                    // 'build_id' => 2,
                    'extras' => $data['extras'],
                ))
                ->message($data['content'], array(
                    'title' => $title,
                    // 'content_type' => 'text',
                    'extras' => $data['extras'],
                ))
                ->options(array(
                    // sendno: 表示推送序号，纯粹用来作为 API 调用标识，
                    // API 返回时被原样返回，以方便 API 调用方匹配请求与返回
                    // 这里设置为 100 仅作为示例

                    // 'sendno' => 100,

                    // time_to_live: 表示离线消息保留时长(秒)，
                    // 推送当前用户不在线时，为该用户保留多长时间的离线消息，以便其上线时再次推送。
                    // 默认 86400 （1 天），最长 10 天。设置为 0 表示不保留离线消息，只有推送当前在线的用户可以收到
                    // 这里设置为 1 仅作为示例

                    // 'time_to_live' => 1,

                    // apns_production: 表示APNs是否生产环境，
                    // True 表示推送生产环境，False 表示要推送开发环境；如果不指定则默认为推送生产环境

                    'apns_production' => false,

                    // big_push_duration: 表示定速推送时长(分钟)，又名缓慢推送，把原本尽可能快的推送速度，降低下来，
                    // 给定的 n 分钟内，均匀地向这次推送的目标用户推送。最大值为1400.未设置则不是定速推送
                    // 这里设置为 1 仅作为示例

                    // 'big_push_duration' => 1
                ))
                ->send();

        } catch (\JPush\Exceptions\APIConnectionException $e) {
            // try something here
            Suco_File::write(LOG_DIR.'error_'.date('Ymd').'.log', $e, 'a');
        } catch (\JPush\Exceptions\APIRequestException $e) {
            // try something here
            Suco_File::write(LOG_DIR.'error_'.date('Ymd').'.log', $e, 'a');
        }

        return $response;
    }
}