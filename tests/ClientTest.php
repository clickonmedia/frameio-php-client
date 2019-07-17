<?php

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use PHPUnit\Framework\TestCase;
use Frameio\FrameIOClient;

$dotenv = Dotenv\Dotenv::create( dirname(__DIR__, 1) );
$dotenv->load();


final class ClientTest extends TestCase
{
    public function testDotenvFileExists()
    {
        $this->assertFileExists( __DIR__ . "/../.env" );
    }

    public function testTokenExists()
    {
        $token = getenv('FRAMEIO_TOKEN');
        $tokenExists = isset( $token ) && !empty( $token );

        $this->assertTrue( $tokenExists );
    }

    public function testFailure()
    {
        $token = getenv('FRAMEIO_TOKEN');
        $client = new FrameIOClient( $token );

        $this->assertInstanceOf( FrameIOClient::class, $client );
    }

    public function testHasExpectedHost()
    {
        $token = getenv('FRAMEIO_TOKEN');
        $client = new FrameIOClient( $token );

        $host = $client->getHost();
        $expectedHost = 'https://api.frame.io/v2';

        $this->assertEquals(
            $host,
            $expectedHost
        );
    }

    public function testAccountExists()
    {
        $token = getenv('FRAMEIO_TOKEN');
        $client = new FrameIOClient( $token );
        $profile = $client->getProfile();

        $accountIdExists = isset( $profile->account_id ) && !empty( $profile->account_id );
        $emailExists = isset( $profile->email ) && !empty( $profile->email );

        $this->assertTrue( $accountIdExists && $emailExists );
    }

    public function testTeamExists()
    {
        $token = getenv('FRAMEIO_TOKEN');
        $client = new FrameIOClient( $token );

        $teams = $client->getTeams();

        $this->assertGreaterThan( 0, sizeof( $teams ) );
    }
}
