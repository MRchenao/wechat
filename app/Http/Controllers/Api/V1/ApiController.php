<?php
/**
 * Created by PhpStorm.
 * User: Gilbert.Ho
 * Date: 20/03/2018
 * Time: 10:44 AM
 * FILENAME:BaseController.php
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    public $pageSize = 10;
    protected $startTime;
    public $isCache = false;
    public $app = null;

    public function __construct()
    {
        $this->startTime = microtime(true);
        $app = \app();
        $this->app = $app;
    }

}
