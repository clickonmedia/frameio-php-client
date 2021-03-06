<?php
namespace Frameio;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Frameio\FrameIOUploader;

 /**
  * FrameIOClient
  *
  * Frame.io PHP Client, for accessing the Frame.io API
  *
  * @link https://docs.frame.io/docs
  *
  */
class FrameIOClient
{
    private $host = "https://api.frame.io/v2";
    private $token;

    /**
     * The class constructor
     *
     * Token variable is assigned on initialization
     *
     * @param  string  $token  Frame.io token
     */
    public function __construct( $token )
    {
        $this->token = $token;
    }

    /**
     * Get Profile
     *
     * Fetches user profile
     *
     * @return  object  The user profile
     */
    public function getProfile()
    {
        return $this->HttpRequest( "GET", "/me" );
    }

    /**
     * Get Teams
     *
     * Fetches teams for a user
     *
     * @return  array  List of teams
     */
    public function getTeams()
    {
        return $this->HttpRequest( "GET", "/teams" );
    }

    /**
     * Get Teams By Account ID
     *
     * Gets teams for a user
     *
     * @param  string  $accountID  Frame.io account ID
     *
     * @return  array  List of teams
     */
    public function getTeamsByAccountID( $accountID )
    {
        return $this->HttpRequest( "GET", "/accounts/{$accountID}/teams" );
    }

    /**
     * Get Teams Team Membership By Team ID
     *
     * Get Team Membership By Team ID
     *
     * @param  string  $teamID  Frame.io tean ID
     *
     * @return  object  Object with attribute "role" for user team role
     */
    public function getTeamMembership( $teamID )
    {
        return $this->HttpRequest( "GET", "/teams/{$teamID}/membership" );
    }

    /**
     * Add Teams Team Membership By Team ID
     *
     * @param  string  $teamID  Frame.io tean ID
     * @param  string  $userID  Frame.io tean ID
     * @param  string  $role  Frame.io tean ID
     *
     * @return  object  Object with user role and ID
     */
    public function addTeamMembership( $teamID, $userID, $role )
    {

        $payload = array(
            "user_id"   =>  $userID,
            "role"      =>  $role
        );

        return $this->HttpRequest( "POST", "/teams/{$teamID}/membership", $payload );
    }

    /**
     * Create Project
     *
     * @param  string  $name  Project name
     * @param  string  $teamID  Frame.io tean ID
     * @param  bool  $private  Set to true if project should be private
     *
     * @return  object  Project details
     */
    public function createProject( $name, $teamID, $private = false )
    {

        $url = "/teams/{$teamID}/projects";

        $payload = array(
            "name" => $name,
            "private" => $private
        );

        return $this->HttpRequest( "POST", $url, $payload );
    }

    /**
     * Get Projects By ID
     *
     * @param  string  $projectID  Project ID
     *
     * @return  object  Project details
     */
    public function getProjectByID( $projectID )
    {

        $url = "/projects/{$projectID}";

        return $this->HttpRequest( "GET", $url );
    }

    /**
     * Delete Projects By ID
     *
     * @param  string  $projectID  Project ID
     *
     * @return  object  Project details
     */
    public function deleteProjectByID( $projectID )
    {

        $url = "/projects/{$projectID}";

        return $this->HttpRequest( "DELETE", $url );
    }

    /**
     * Get Projects By Team ID
     *
     * @param  string  $teamID  Team ID
     *
     * @return  array  List of projects
     */
    public function getProjectsByTeamid( $teamID )
    {

        $url = "/teams/{$teamID}/projects";

        return $this->HttpRequest( "GET", $url);
    }

    /**
     * Get User Membership for Project
     *
     * @param  string  $projectID  Project ID
     *
     * @return  object  Membership information
     */
    public function getUserMembershipForProject( $projectID )
    {

        $url = "/projects/{$projectID}/membership";

        return $this->HttpRequest( "GET", $url);
    }

    /**
     * Add A Collaborator To A Team
     *
     * @param  string  $projectID  Project ID
     * @param  string  $email  Email address
     *
     * @return  object  Membership information
     */
    public function addCollaboratorToTeam( $projectID, $email )
    {

        $url = "/projects/{$projectID}/collaborators";

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

    /**
     * Create Asset
     *
     * @link https://docs.frame.io/reference#createasset
     *
     * @param  string  $projectID  Frame.io Project ID  (required)
     * @param  array  $args  Multidimensional array, with additional parameters
     *
     *
     *
     * @return  object  Membership information
     */
    public function createAsset( $projectID, $args )
    {

        $project = $this->getProjectByID( $projectID );

        $url = "/assets/{$project->root_asset_id}/children";

        return $this->HttpRequest( "POST", $url, $args );
    }

    /*
    |-------------------------------------------------------------------------------
    | Get Assets
    |-------------------------------------------------------------------------------
    | Description:    Get Assets
    */
    public function getAssets( $rootAssetID, $type = "file" )
    {

        $url = "/assets/{$rootAssetID}/children";

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
    public function getAssetByID( $assetID )
    {

        $url = "/assets/{$assetID}";

        return $this->HttpRequest( "GET", $url );
    }

    /*
    |-------------------------------------------------------------------------------
    | Update an Asset By ID
    |-------------------------------------------------------------------------------
    | Description:    Update an Asset By ID
    |
    | assetID                   String      Frame.io asset ID (required)
    | name                      String      Asset name (required)
    |
    | args                      Object      Additional optional parameters
    |
    |   description             String      Brief description of the Asset (optional)
    |   properties              Array       Custom key-value data (optional)
    |
    */
    public function updateAssetByID( $assetID, $name, $args = [] )
    {

        $url = "/assets/{$assetID}";

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
    public function deleteAssetByID( $assetID )
    {

        $url = "/assets/{$assetID}";

        return $this->HttpRequest( "DELETE", $url );
    }

    /*
    |-------------------------------------------------------------------------------
    | Add a version to an Asset
    |-------------------------------------------------------------------------------
    | Description:    Add a version to an Asset
    |
    | Parameters:
    | assetID                   String      Frame.io asset ID (required)
    | nextAssetID               String      Asset ID for the next version (required)
    */
    public function addVersionToAsset( $assetID, $nextAssetID )
    {

        $url = "/assets/{$assetID}/version";

        $payload = array(
            "next_asset_id" => $nextAssetID,
        );

        return $this->HttpRequest( "POST", $url, $payload );
    }

    /*
    |-------------------------------------------------------------------------------
    | Get Project Assets
    |-------------------------------------------------------------------------------
    | Description:    Get Project Assets
    */
    public function getProjectAssets( $projectID )
    {

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
    | assetID                   String      Frame.io asset ID (required)
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
    public function createComment( $assetID, $args )
    {

        $url = "/assets/{$assetID}/comments";

        return $this->HttpRequest( "POST", $url, $args );
    }

    /*
    |-------------------------------------------------------------------------------
    | Get Comments
    |-------------------------------------------------------------------------------
    | Description:    Get Comments
    */
    public function getComments( $assetID )
    {

        $url = "/assets/{$assetID}/comments";

        return $this->HttpRequest( "GET", $url );
    }

    /*
    |-------------------------------------------------------------------------------
    | Get Comments
    |-------------------------------------------------------------------------------
    | Description:    Get Comments
    */
    public function getCommentByID( $commentID )
    {

        $url = "/comments/{$commentID}";

        return $this->HttpRequest( "GET", $url );
    }

    /*
    |-------------------------------------------------------------------------------
    | Update a Comment
    |-------------------------------------------------------------------------------
    | Description:    Update a Comment
    */
    public function updateComment( $commentID, $text = "" )
    {

        $url = "/comments/{$commentID}";

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
    public function deleteCommentByID ( $commentID )
    {

        $url = "/comments/{$commentID}";

        return $this->HttpRequest( "DELETE", $url );
    }

    /*
    |-------------------------------------------------------------------------------
    | Get Review Links for Project
    |-------------------------------------------------------------------------------
    | Description:    Get Review Links for Project
    */
    public function getReviewLinks( $projectID )
    {

        $url = "/projects/{$projectID}/review_links";

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
    | projectID                 String      Frame.io Project ID
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
    public function createReviewLink( $projectID, $name, $args = [] )
    {

        $url = "/projects/{$projectID}/review_links";

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
    | reviewLinkID             String      Frame.io review link ID
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
    public function UpdateReviewLink( $reviewLinkID, $name, $args = [] )
    {

        $url = "/review_links/{$reviewLinkID}";

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
    public function getReviewLink( $linkID )
    {

        $url = "/review_links/{$linkID}";

        return $this->HttpRequest( "GET", $url );
    }

    /*
    |-------------------------------------------------------------------------------
    | Get Review Link Items
    |-------------------------------------------------------------------------------
    | Description:    Get Review Link Items
    */
    public function getReviewLinkItems( $linkID )
    {

        $url = "/review_links/{$linkID}/items";

        return $this->HttpRequest( "GET", $url );
    }

    /*
    |-------------------------------------------------------------------------------
    | Add Assets to a Review Link
    |-------------------------------------------------------------------------------
    | Description:    Add Assets to a Review Link
    */
    public function addAssetsToReviewLink( $reviewLinkID, $assetIDs )
    {

        $url = "/review_links/{$reviewLinkID}/assets";

        $payload = array(
            "asset_ids" => $assetIDs
        );

        return $this->HttpRequest( "POST", $url, $payload, true );
    }

    /*
    |-------------------------------------------------------------------------------
    | Search for Assets
    |-------------------------------------------------------------------------------
    | Description:    Search for Assets
    */
    public function getSearchAssets ( $query = "", $teamID = "", $accountID = "" )
    {

        $url = "/search/assets?";

        if( $query ) {
            $url .= "q=" . $query;
        }

        if( $teamID ) {
            $url .= "team_id=" . $teamID;
        }

        if( $accountID ) {
            $url .= "account_id=" . $accountID;
        }

        return $this->HttpRequest( "post", $url, $payload );
    }

    /*
    |-------------------------------------------------------------------------------
    | Search for Assets
    |-------------------------------------------------------------------------------
    | Description:    Search for Assets
    */
    public function searchAssets ( $query = "", $teamID = "", $accountID = "", $filter = [] )
    {

        $url = "/search/assets?";

        $payload = array(
            "q"             =>  $query,
            "team_id"       =>  $teamID,
            "account_id"    =>  $accountID,
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
    public function getHost()
    {
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
    public function upload( $asset, $file_path )
    {
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
    protected function HttpRequest( $method, $url, $payload = false )
    {

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