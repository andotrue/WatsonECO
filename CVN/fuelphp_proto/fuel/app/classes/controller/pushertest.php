<?php
use Fuel\Core\Log;
use Fuel\Core\DB;
//use Fuel\Vendor\Pusher\pusher\php\server\lib\Pusher;

class Controller_Pushertest extends Controller {
  
    public function action_index(){
        //echo VENDORPATH;
        //require('Pusher.php');
        require(VENDORPATH.'pusher/pusher-php-server/lib/Pusher.php');
        
        $options = array(
            'encrypted' => true
        );
        $pusher = new Pusher(
            'af84690ae32132ce6058',
            '8c3a92021a6be2080a08',
            '313964',
            $options
        );
        
        $data['message'] = 'hello world';
        $pusher->trigger('my-channel', 'my-event', $data);
    }
}
