<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2017 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to version 2.0 of the Apache license,    |
 * | that is bundled with this package in the file LICENSE, and is        |
 * | available through the world-wide-web at the following url:           |
 * | http://www.apache.org/licenses/LICENSE-2.0.html                      |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Tests\API2;

use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Tests\TestCase;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TestReportFeed extends TestCase
{
    use DatabaseTransactions;

    private $api = '/api/v2/feeds';

    private $user;

    /**
     * 前置条件.
     */
    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    public function testFeedRank()
    {
        $this->user->roles()->sync([2]);

        $token = $this->guard()->login($this->user);

        $this->addTestFeedData($token);

        $res = $this->post($this->api."/{$this->feed['id']}/reports?token=".$token);

        $res->assertStatus(201)->assertJsonStructure(['message']);
    }

    /**
     * @return Guard
     */
    protected function guard(): Guard
    {
        return Auth::guard('api');
    }

    /**
     * @param $token
     * @return mixed
     */
    protected function addTestFeedData($token)
    {
        $data = [
            'feed_content' => '单元测试动态数据',
            'feed_from' => 1,
            'feed_mark' => time(),
            'feed_latitude' => '',
            'feed_longtitude' => '',
            'feed_geohash' => '',
            'amount' => 100,
            'images' => [],
        ];

        $this->feed = $this->post('/api/v2/feeds?token='.$token, $data)->json();
    }
}