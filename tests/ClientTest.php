<?php

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use PHPUnit\Framework\TestCase;
use Frameio\FrameIOClient;

$dotenv = Dotenv\Dotenv::create( dirname(__DIR__, 1) );
$dotenv->load();


final class ClientTest extends TestCase
{
    protected $token;
    protected $client;

    protected function setUp(): void
    {
        $this->token = getenv('FRAMEIO_TOKEN');
        $this->client = new FrameIOClient( $this->token );

    }

    public function testDotenvFileExists()
    {
        $this->assertFileExists( __DIR__ . "/../.env" );
    }

    public function testTokenExists()
    {
        $tokenExists = isset( $this->token ) && !empty( $this->token );

        $this->assertTrue( $tokenExists );
    }

    public function testFailure()
    {
        $this->assertInstanceOf( FrameIOClient::class, $this->client );
    }

    public function testHasExpectedHost()
    {
        $host = $this->client->getHost();
        $expectedHost = 'https://api.frame.io/v2';

        $this->assertEquals(
            $host,
            $expectedHost
        );
    }

    public function testAccountExists()
    {
        $profile = $this->client->getProfile();

        $accountIdExists = isset( $profile->account_id ) && !empty( $profile->account_id );
        $emailExists = isset( $profile->email ) && !empty( $profile->email );

        $this->assertTrue( $accountIdExists && $emailExists );
    }

    public function testTeamExists()
    {
        $teams = $this->client->getTeams();

        $this->assertNotEmpty( $teams );
    }

    public function testTeamMembership()
    {
        $teamId = getenv('FRAMEIO_TEAM');
        $teamMembership = $this->client->getTeamMembership( $teamId );

        $isMember = isset( $teamMembership->role ) && !empty( isset( $teamMembership->role ) );

        $this->assertTrue( $isMember );
    }
}
