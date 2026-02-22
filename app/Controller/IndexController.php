<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Controller;

use Hyperf\DbConnection\Db;

class IndexController extends AbstractController
{
    public function index()
    {
        $result = Db::select('SELECT DATABASE() as db');

        return [
            'status' => 'ok',
            'database' => $result[0]->db ? 'ok' : 'down',
        ];
    }
}
