<?php

use PHPUnit\Framework\TestCase;
use Frameio\FrameIOClient;
use PackageVersions\Versions;

// Determine Dotenv version
$versionHash = Versions::getVersion( 'vlucas/phpdotenv' );
$version = intval( $versionHash[1] );

// Initialize Dotenv with syntax based on version
if ( $version < 3 ) {
    $dotenv = new Dotenv\Dotenv( dirname(__DIR__, 1) );
} else {
    $dotenv = Dotenv\Dotenv::create( dirname(__DIR__, 1) );
}
$dotenv->load();


final class ClientTest extends TestCase
{
    protected $token;
    protected $client;

    protected function setUp(): void
    {
        $this->token = getenv( 'FRAMEIO_TOKEN' );
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
        $teamId = getenv( 'FRAMEIO_TEAM_ID' );
        $teamMembership = $this->client->getTeamMembership( $teamId );

        $isMember = isset( $teamMembership->role ) && !empty( isset( $teamMembership->role ) );

        $this->assertTrue( $isMember );
    }

    public function testAssetCreation()
    {
        $projectId = getenv( 'FRAMEIO_TEST_PROJECT_ID' );

        $filesize = filesize( __DIR__ . "/example.png" );

        $args = array(
            'name' => 'Asset name',
            'filesize' => $filesize,
            'type' => 'file'
        );

        $response = $this->client->createAsset( $projectId, $args );

        $this->assertObjectHasAttribute( 'id', $response );
        $this->assertObjectHasAttribute( 'updated_at', $response );
    }

    public function testGetAsset()
    {
        $assetId = getenv('FRAMEIO_TEST_ASSET_ID');

        $response = $this->client->getAssetById( $assetId );

        $this->assertObjectHasAttribute( 'id', $response );
    }


    public function testAddAssetToReviewLink()
    {
        $reviewLinkId = getenv( 'FRAMEIO_TEST_REVIEW_LINK_ID' );
        $assetId = getenv( 'FRAMEIO_TEST_ASSET_ID' );
        $assetIds = [ $assetId ];

        $response = $this->client->addAssetsToReviewLink( $reviewLinkId, $assetIds );

        $this->assertObjectHasAttribute( 'id', $response );
    }
}
