<?php
declare(strict_types=1);

namespace Exewen\Test;

use Exewen\Nacos\Util\FileUtil;
use PHPUnit\Framework\TestCase;

class BaseTest extends TestCase
{
    public function __construct()
    {
        parent::__construct();

        FileUtil::setSnapshotPath(dirname(__DIR__) . "/nacos/config");
    }

}