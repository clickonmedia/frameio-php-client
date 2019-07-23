<?php
namespace Frameio;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Frameio\FrameIOUploader;


class FrameIOClient
{
    private $host = "https://api.frame.io/v2";
    private $token;

    public function __construct( $token ) {
        $this->token = $token;
    }

    /*
    |-------------------------------------------------------------------------------
    | Get Profile
    |-------------------------------------------------------------------------------
    | Description:    Gets user profile
    */
    public function getProfile() {
        return $this->HttpRequest( "GET", "/me" );
    }

    /*
    |-------------------------------------------------------------------------------
    | Get Teams
    |-------------------------------------------------------------------------------
    | Description:    Gets teams for a user
    | NOTE: This currently returns an error because of a bug in Frame.io API
    */
    public function getTeams() {
        return $me = $this->HttpRequest( "GET", "/teams" );
    }

     /*
    |-------------------------------------------------------------------------------
    | Get Teams By Account Id
    |-------------------------------------------------------------------------------
    | Description:    Gets teams for a user
    | NOTE: This currently returns an error because of a bug in Frame.io API
    */
    public function getTeamsByAccountId( $accountId ) {
        return $this->HttpRequest( "GET", "/accounts/{$accountId}/teams" );
    }

    /*
    |-------------------------------------------------------------------------------
    | Get Teams Team Membership By Team Id
    |-------------------------------------------------------------------------------
    | Description:    Get Team Membership By Team Id
    | NOTE: This currently returns an error because of a bug in Frame.io API
    */
    public function getTeamMembership( $teamId ) {
        return $this->HttpRequest( "GET", "/teams/{$teamId}/membership" );
    }

    /*
    |-------------------------------------------------------------------------------
    | Add Teams Team Membership By Team Id
    |-------------------------------------------------------------------------------
    | Description:    Add Team Membership By Team Id
    | NOTE: This currently returns an error because of a bug in Frame.io API
    */
    public function addTeamMembership( $teamId, $userId, $role ) {

        $payload = array(
            "user_id"   =>  $userId,
            "role"      =>  $role
        );

        return $this->HttpRequest( "POST", "/teams/{$teamId}/membership", $payload );
    }

    /*
    |-------------------------------------------------------------------------------
    | Create Project
    |-------------------------------------------------------------------------------
    | Description:    Create a project
    */
    public function createProject( $name, $teamId, $private = false ) {

        $url = "/teams/{$teamId}/projects";

        $payload = array(
            "name" => $name,
            "private" => $private
        );

        return $this->HttpRequest( "POST", $url, $payload );
    }

    /*
    |-------------------------------------------------------------------------------
    | Get Projects By Id
    |-------------------------------------------------------------------------------
    | Description:    Get Projects By Id
    */
    public function getProjectById( $projectId ) {

        $url = "/projects/{$projectId}";

        return $this->HttpRequest( "GET", $url );
    }

    /*
    |-------------------------------------------------------------------------------
    | Delete Projects By Id
    |-------------------------------------------------------------------------------
    | Description:    Delete Projects By Id
    */
    public function deleteProjectById( $projectId ) {

        $url = "/projects/{$projectId}";

        return $this->HttpRequest( "DELETE", $url );
    }

    /*
    |-------------------------------------------------------------------------------
    | Get Projects By Team Id
    |-------------------------------------------------------------------------------
    | Description:    Get Projects By Team Id
    */
    public function getProjectsByTeamid( $teamId ) {

        $url = "/teams/{$teamId}/projects";

        return $this->HttpRequest( "GET", $url);
    }

    /*
    |-------------------------------------------------------------------------------
    | Get User Membership for Project
    |-------------------------------------------------------------------------------
    | Description:   Get User Membership for Project
    */
    public function getUserMembershipForProject( $projectId ) {

        $url = "/projects/{$projectId}/membership";

        return $this->HttpRequest( "GET", $url);
    }

    /*
    |-------------------------------------------------------------------------------
    | Add A Collaborator To A Team.
    |-------------------------------------------------------------------------------
    | Description:    Add A Collaborator To A Team.
    */
    public function addCollaboratorToTeam( $projectId, $email ) {

        $url = "/projects/{$projectId}/collaborators";

        $payload = array(
            "email" => $email
        );

        return $this->HttpRequest( "POST", $url);
    }

    /*
    |-------------------------------------------------------------------------------
    | Create Asset
    |-------------------------------------------------------------------------------
    | Description:      Create an asset
    |                   https://docs.frame.io/reference#createasset
    |
    | Parameters:
    |
    |    project_id     String      Frame.io Project ID  (required)
    |
    |    args           Object      Additional parameters
    |
    |      name         String      Asset name (required)
    |      filesize     Integer     File size in bytes (required)
    |      type         String      File type / "file" or "folder" (required)
    |
    |      description  String      Asset description (optional)
    |      filetype     String      File type, e.g. "video/mp4" (optional)
    |      source       Array       Asset name / ["url" => $url] (optional)
    |      properties   Array       Custom key-value data (optional)
    */
    public function createAsset( $projectId, $args ) {

        $project = $this->getProjectById( $projectId );

        $url = "/assets/{$project->root_asset_id}/children";

        return $this->HttpRequest( "POST", $url, $args );
    }

    /*
    |-------------------------------------------------------------------------------
    | Get Assets
    |-------------------------------------------------------------------------------
    | Description:    Get Assets
    */
    public function getAssets( $rootAssetId, $type = "file" ) {

        $url = "/assets/{$rootAssetId}/children";

        if( $type ) {
           $url .="?type = " . $type;
        }

        return $this->HttpRequest( "GET", $url );
    }

    /*
    |-------------------------------------------------------------------------------
    | Get an Asset by ID
    |-------------------------------------------------------------------------------
    | Description:    Get an Asset by ID
    */
    public function getAssetById( $assetId ) {

        $url = "/assets/{$assetId}";

        return $this->HttpRequest( "GET", $url );
    }

    /*
    |-------------------------------------------------------------------------------
    | Update an Asset By Id
    |-------------------------------------------------------------------------------
    | Description:    Update an Asset By Id
    |
    | assetId                   String      Frame.io asset ID (required)
    | name                      String      Asset name (required)
    |
    | args                      Object      Additional optional parameters
    |
    |   description             String      Brief description of the Asset (optional)
    |   properties              Array       Custom key-value data (optional)
    |
    */
    public function updateAssetById( $assetId, $name, $args = [] ) {

        $url = "/assets/{$assetId}";

        $defaults = array(
            "name" => $name,
            "description" => "",
            "properties" => []
        );

        $payload = array_merge( $defaults, $args );

        return $this->HttpRequest( "PUT", $url, $payload);
    }

    /*
    |-------------------------------------------------------------------------------
    | Delete an Asset
    |-------------------------------------------------------------------------------
    | Description:    Delete an Asset
    */
    public function deleteAssetById( $assetId ) {

        $url = "/assets/{$assetId}";

        return $this->HttpRequest( "DELETE", $url );
    }

    /*
    |-------------------------------------------------------------------------------
    | Add a version to an Asset
    |-------------------------------------------------------------------------------
    | Description:    Add a version to an Asset
    |
    | Parameters:
    | assetId                   String      Frame.io asset ID (required)
    | nextAssetId               String      Asset ID for the next version (required)
    */
    public function addVersionToAsset( $assetId, $nextAssetId ) {

        $url = "/assets/{$assetId}/version";

        $payload = array(
            "next_asset_id" => $nextAssetId,
        );

        return $this->HttpRequest( "POST", $url, $payload );
    }

    /*
    |-------------------------------------------------------------------------------
    | Get Project Assets
    |-------------------------------------------------------------------------------
    | Description:    Get Project Assets
    */
    public function getProjectAssets( $projectId ) {

        $project = $this->HttpRequest( "GET", "/projects/" . $project_id );

        return $this->getAssets( $project->root_asset_id );
    }

    /*
    |-------------------------------------------------------------------------------
    | Create a Comment
    |-------------------------------------------------------------------------------
    | Description:    Create a Comment
    |
    | Parameters:
    | assetId                   String      Frame.io asset ID (required)
    |
    | args                      Object      Comment data (required)
    |
    |   text                    String      The body of the comment (optional)
    |   annotation              String      Serialized list of geometry and/or drawing data (optional)
    |   timestamp               Integer     Timestamp for the comment, in frames (optional)
    |   page                    Integer     Page number for a comment (optional)
    |   pitch                   String      Pitch measurement for the comment / 360deg video only (optional)
    |   yaw                     String      Yaw measurement for the comment / 360deg video only (optional)
    */
    public function createComment( $assetId, $args ) {

        $url = "/assets/{$assetId}/comments";

        return $this->HttpRequest( "POST", $url, $args );
    }

    /*
    |-------------------------------------------------------------------------------
    | Get Comments
    |-------------------------------------------------------------------------------
    | Description:    Get Comments
    */
    public function getComments( $assetId ) {

        $url = "/assets/{$assetId}/comments";

        return $this->HttpRequest( "GET", $url );
    }

    /*
    |-------------------------------------------------------------------------------
    | Get Comments
    |-------------------------------------------------------------------------------
    | Description:    Get Comments
    */
    public function getCommentById( $commentId ) {

        $url = "/comments/{$commentId}";

        return $this->HttpRequest( "GET", $url );
    }

    /*
    |-------------------------------------------------------------------------------
    | Update a Comment
    |-------------------------------------------------------------------------------
    | Description:    Update a Comment
    */
    public function updateComment( $commentId, $text = "" ) {

        $url = "/comments/{$commentId}";

        $payload = array(
            "text" => $text,
        );

        return $this->HttpRequest( "PUT", $url, $payload );
    }

    /*
    |-------------------------------------------------------------------------------
    | Delete a Comment
    |-------------------------------------------------------------------------------
    | Description:    Delete a Comment
    */
    public function deleteCommentById ( $commentId ) {

        $url = "/comments/{$commentId}";

        return $this->HttpRequest( "DELETE", $url );
    }

    /*
    |-------------------------------------------------------------------------------
    | Get Review Links for Project
    |-------------------------------------------------------------------------------
    | Description:    Get Review Links for Project
    */
    public function getReviewLinks( $projectId ) {

        $url = "/projects/{$projectId}/review_links";

        return $this->HttpRequest( "GET", $url );
    }

    /*
    |-------------------------------------------------------------------------------
    | Create a Review Link
    |-------------------------------------------------------------------------------
    | Description:    Create a Review Link
    | https://docs.frame.io/docs/working-with-review-links#section-step-2-create-the-review-link
    |
    | Parameters:
    | projectId                 String      Frame.io Project ID
    | name                      String      Name of the Review Link
    | args
    |   allow_approvals         Boolean     Allow approvals in Review Link (optional, default: false)
    |   current_version_only    Boolean     View current version of asset only (optional, default: false)
    |   enable_downloading      Boolean     Enable asset downloading (optional, default: false)
    |   requires_passphrase     Boolean     Allow approvals in Review Link (optional, default: false)
    |   password                String      Password to protect Review Link (optional, default: none)
    |   expires_at              Date        Set expiry date for the Review Link (optional, default: none)
    |
    */
    public function createReviewLink( $projectId, $name, $args = [] ) {

        $url = "/projects/{$projectId}/review_links";

        $defaults = array(
            "name" => $name,
            "allow_approvals" => false,
            "current_version_only" => false,
            "enable_downloading" => false,
            "requires_passphrase" => false,
            "password" => "",
            "expires_at" => ""
        );

        $payload = array_merge( $defaults, $args );

        return $this->HttpRequest( "POST", $url, $payload );
    }

    /*
    |-------------------------------------------------------------------------------
    | Update a Review Link
    |-------------------------------------------------------------------------------
    | Description:    Update a Review Link
    |
    | Parameters:
    | reviewLinkId             String      Frame.io review link ID
    | name                     String      Review link name
    |
    | args                     Object      Additional optional parameters
    |
    |   allow_approvals         Boolean     Allow approvals in Review Link (optional, default: false)
    |   current_version_only    Boolean     View current version of asset only (optional, default: false)
    |   enable_downloading      Boolean     Enable asset downloading (optional, default: false)
    |   requires_passphrase     Boolean     Allow approvals in Review Link (optional, default: false)
    |   password                String      Password to protect Review Link (optional, default: none)
    |   expires_at              Date        Set expiry date for the Review Link (optional, default: none)
    */
    public function UpdateReviewLink( $reviewLinkId, $name, $args = [] ) {

        $url = "/review_links/{$reviewLinkId}";

        $defaults = array(
            "name" => $name,
            "allow_approvals" => false,
            "current_version_only" => false,
            "enable_downloading" => false,
            "requires_passphrase" => false,
            "password" => "",
            "expires_at" => ""
        );

        return $this->HttpRequest( "PUT", $url, $payload );
    }

    /*
    |-------------------------------------------------------------------------------
    | Get a Review Link
    |-------------------------------------------------------------------------------
    | Description:    Get a Review Link
    */
    public function getReviewLink( $linkId ) {

        $url = "/review_links/{$linkId}";

        return $this->HttpRequest( "GET", $url );
    }

    /*
    |-------------------------------------------------------------------------------
    | Get Review Link Items
    |-------------------------------------------------------------------------------
    | Description:    Get Review Link Items
    */
    public function getReviewLinkItems( $linkId ) {

        $url = "/review_links/{$linkId}/items";

        return $this->HttpRequest( "GET", $url );
    }

    /*
    |-------------------------------------------------------------------------------
    | Add Assets to a Review Link
    |-------------------------------------------------------------------------------
    | Description:    Add Assets to a Review Link
    */
    public function addAssetsToReviewLink( $reviewLinkId, $assetIds ) {

        $url = "/review_links/{$reviewLinkId}/assets";

        $payload = array(
            "asset_ids" => $assetIds
        );

        return $this->HttpRequest( "POST", $url, $payload, true );
    }

    /*
    |-------------------------------------------------------------------------------
    | Search for Assets
    |-------------------------------------------------------------------------------
    | Description:    Search for Assets
    */
    public function getSearchAssets ( $query = "", $teamId = "", $accountId = "" ) {

        $url = "/search/assets?";

        if( $query ) {
            $url .= "q=" . $query;
        }

        if( $teamId ) {
            $url .= "team_id=" . $teamId;
        }

        if( $accountId ) {
            $url .= "account_id=" . $accountId;
        }

        return $this->HttpRequest( "post", $url, $payload );
    }

    /*
    |-------------------------------------------------------------------------------
    | Search for Assets
    |-------------------------------------------------------------------------------
    | Description:    Search for Assets
    */
    public function searchAssets ( $query = "", $teamId = "", $accountId = "", $filter = [] ) {

        $url = "/search/assets?";

        $payload = array(
            "q"             =>  $query,
            "team_id"       =>  $teamId,
            "account_id"    =>  $accountId,
            "filter"        =>  $filter
        );

        return $this->HttpRequest( "post", $url, $payload );
    }

    /*
    |-------------------------------------------------------------------------------
    | Get Host
    |-------------------------------------------------------------------------------
    | Description:    Returns the API base URL
    */
    public function getHost() {
        return $this->host;
    }

    /*
    |-------------------------------------------------------------------------------
    | Upload
    |-------------------------------------------------------------------------------
    | Description:      Upload an asset to Frame.io
    |
    |  $asset           Object      The asset object
    |  $file_path       String      The path of the file to upload
    */
    public function upload( $asset, $file_path ) {
        // Upload file to Frame
        $uploader = new FrameIOUploader( $asset, $file_path );
        return $uploader->upload();
    }

    /*
    |-------------------------------------------------------------------------------
    | HTTP Request
    |-------------------------------------------------------------------------------
    | Description:    Method for all HTTP requests
    */
    protected function HttpRequest( $method, $url, $payload = false ) {

        try {
            $url = $this->host . $url;
            $result = null;

            // Initialize GuzzleHttp client
            $client = new Client([
                "http_errors" => false
            ]);

            // Configure HTTP headers
            $headers = array(
                "accept" => "application/json",
                "authorization" => "Bearer " . $this->token,
                "content-type" => "application/json"
            );

            $args = [
                "headers" => $headers
            ];

            if ( $payload ) {
                $args['body'] = json_encode( $payload );
            }

            $response = $client->request( $method, $url, $args );

            $result = $response->getBody()->getContents();

            return \GuzzleHttp\json_decode( $result );

        } catch (\ClientException $e){
            return $e->getMessage();
        }
    }
}