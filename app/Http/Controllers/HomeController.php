<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function setEnvironmentValue($envKey, $envValue)
    {
      $sep = "\n"; // \r\n in Windows!
      $envFile = app()->environmentFilePath();
      $str = file_get_contents($envFile);
      $array = explode($sep,$str);
        foreach ($array as $key => $value) {
          $index = explode('=', $value);
            if ($index[0] == $envKey)
              $array[$key] = $index[0].'="'.$envValue.'"';
        }

        $str = implode($sep,$array);
        $fp = fopen($envFile, 'w');
        fwrite($fp, $str);
        fclose($fp);
    }

    public function setEnv(Request $request)
    {
      $this->setEnvironmentValue($request->key, $request->value);

      return response(['message' => 'OK'], 200);
    }

    public function setLocale(Request $request)
    {
      $this->setEnvironmentValue('APP_LOCALE', $request->locale);
      // AND SERVER IS RESTARTED AFTER THAT - THERE SHOULD BE A BETTER SOLUTION!!

      return response(['message' => 'OK'], 200);
    }
}
