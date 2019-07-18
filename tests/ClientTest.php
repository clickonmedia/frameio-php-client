<?php

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

    protected function TearDown(): void
    {
        unset( $this->token );
        unset( $this->client );
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

    public function testInstance()
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

        $this->assertObjectHasAttribute( 'account_id', $profile );
        $this->assertObjectHasAttribute( 'email', $profile );
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
