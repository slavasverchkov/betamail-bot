<?php

namespace App\Http\Controllers\Backend;

use App\Setting;
use Illuminate\Http\Request;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class SettingController extends Controller
{
    public function index () {
    	return view('backend.setting', Setting::getSettings());
    }

    public function store (Request $request) {

    	Setting::where('key', '!=', NULL)->delete();

    	foreach ($request->except('_token') as $key => $value) {
			$setting = new Setting;
			$setting->key = $key;
			$setting->value = $request->$key;
			$setting->save();
    	}

    	return redirect()->route('admin.setting.index');
    }
 public function setwebhook (Request $request) {

        $result = $this->sendTelegramData('setwebhook', [
            'query' => ['url' => $request->url . '/' . \Telegram::getAccessToken()]
        ]);
        return redirect()->route('admin.setting.index')->with('status', $result);

    }

    public function getwebhookinfo (Request $request) {

        $result = $this->sendTelegramData('getWebhookInfo');
        
        return redirect()->route('admin.setting.index')->with('status', $result);

    }

    public function sendTelegramData ( $route = '', $params = [], $method = 'POST' ) {

        $client = new \GuzzleHttp\Client( ['base_uri' => 'https://api.telegram.org/bot' . \Telegram::getAccessToken() . '/'] );
        $result = $client->request( $method, $route, $params);
        return (string) $result->getBody();

    }
}