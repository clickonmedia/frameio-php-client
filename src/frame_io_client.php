<?php
namespace Frameio;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;


class FrameIOClient
{
	private $host = "https://api.frame.io/v2";
	private $token;
	private $team;

    public function __construct($token) {
        $this->token =  $token;
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
    public function createProject($name = '' , $private = false ) {

		$url = "/projects";
		$payload = array(
			"name" => $name ? $name : time(),
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

		$url = "/projects/" . $projectId;

        return $this->HttpRequest( "GET", $url );
    }

    /*
    |-------------------------------------------------------------------------------
    | Delete Projects By Id
    |-------------------------------------------------------------------------------
    | Description:    Delete Projects By Id
    */
    public function deleteProjectById( $projectId ) {

		$url = "/projects/" . $projectId;

        return $this->HttpRequest( "DELETE", $url );
    }

    /*
    |-------------------------------------------------------------------------------
    | Get Projects By Team Id
    |-------------------------------------------------------------------------------
    | Description:    Get Projects By Team Id
    */
    public function getProjectsByTeamid( $teamId ) {

		$url = "/teams/" . $teamId . "/projects";

        return $this->HttpRequest( "GET", $url);
    }

    /*
    |-------------------------------------------------------------------------------
    | Get User Membership for Project
    |-------------------------------------------------------------------------------
    | Description:   Get User Membership for Project
    */
    public function getUserMembershipForProject( $projectId ) {

        $url = "/projects/" . $projectId . "/membership";

        return $this->HttpRequest( "GET", $url);
    }

    /*
    |-------------------------------------------------------------------------------
    | Add A Collaborator To A Team.
    |-------------------------------------------------------------------------------
    | Description:    Add A Collaborator To A Team.
    */
    public function addCollaboratorToTeam( $projectId, $email ) {

        $url = "/projects/" . $projectId . "/collaborators";
        $payload = array(
            "email" => $email
        );

        return $this->HttpRequest( "POST", $url);
    }

    /*
    |-------------------------------------------------------------------------------
    | Create Asset
    |-------------------------------------------------------------------------------
    | Description:    Create an asset
    */

    public function createAsset( $rootAssetId, $name, $filesize, $description = "", $type = "file", $filetype = "mp4", $fileUrl = "", $properties = [] ) {

        $url = "/assets/" . $rootAssetId . "/children";
        $payload = array(
            "name"          =>  $name,
            "description"   =>  $description,
            "type"          =>  $type,
            "filetype"      =>  $filetype,
            "filesize"      =>  $filesize,
            "source"        =>  ["url" => $url],
            "properties"    =>  $properties

        );
        return $this->HttpRequest( "POST", $url, $payload );
    }

    /*
    |-------------------------------------------------------------------------------
    | Get Assets
    |-------------------------------------------------------------------------------
    | Description:    Get Assets
    */

    public function getAssets( $rootAssetId, $type = 'file' ) {

        $url = "/assets/" . $rootAssetId . "/children";

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

        $url = "/assets/" . $assetId;

        return $this->HttpRequest( "GET", $url );
    }

    /*
    |-------------------------------------------------------------------------------
    | Update an Asset By Id
    |-------------------------------------------------------------------------------
    | Description:    Update an Asset By Id
    */

    public function updateAssetById( $assetId, $name = '', $description = '', $properties = [] ) {

        $url = "/assets/" . $assetId;
        $payload = array(
            "name" => $name,
            "description"   =>  $description,
            "properties"    =>  $properties

        );

        return $this->HttpRequest( "PUT", $url, $payload);
    }

    /*
    |-------------------------------------------------------------------------------
    | Delete an Asset
    |-------------------------------------------------------------------------------
    | Description:    Delete an Asset
    */

    public function deleteAssetById( $assetId ) {

        $url = "/assets/" . $assetId;

        return $this->HttpRequest( "DELETE", $url );
    }

    /*
    |-------------------------------------------------------------------------------
    | Add a version to an Asset
    |-------------------------------------------------------------------------------
    | Description:    Add a version to an Asset
    */

    public function addVersionToAsset( $assetId, $nextAssetId ) {

        $url = "/assets/" . $assetId . "/version";
        $payload = array(
            "next_asset_id" => $nextAssetId,
        );

        return $this->HttpRequest( "POST", $url, $payload );
    }

    /*
    |-------------------------------------------------------------------------------
    | Create a Comment
    |-------------------------------------------------------------------------------
    | Description:    Create a Comment
    */

    public function createComment( $assetId, $text = '', $annotation = '', $timestamp = '', $page = '', $pitch = '', $yaw = '') {

        $url = "/assets/" . $assetId . "/comments";
        $payload = array(
            "text"          =>  $text,
            "annotation"    =>  $annotation,
            "timestamp"     =>  $timestamp,
            "page"          =>  $page,
            "pitch"         =>  $pitch,
            "yaw"           =>  $yaw,
        );

        return $this->HttpRequest( "POST", $url, $payload );
    }

    /*
    |-------------------------------------------------------------------------------
    | Get Comments
    |-------------------------------------------------------------------------------
    | Description:    Get Comments
    */

    public function getComments( $assetId ) {

        $url = "/assets/" . $assetId . "/comments";

        return $this->HttpRequest( "GET", $url );
    }

    /*
    |-------------------------------------------------------------------------------
    | Get Comments
    |-------------------------------------------------------------------------------
    | Description:    Get Comments
    */

    public function getCommentById( $commentId ) {

        $url = "/comments/" . $commentId;

        return $this->HttpRequest( "GET", $url );
    }

    /*
    |-------------------------------------------------------------------------------
    | Update a Comment
    |-------------------------------------------------------------------------------
    | Description:    Update a Comment
    */

    public function updateComment( $commentId, $text = '' ) {

         $url = "/comments/" . $commentId;
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

         $url = "/comments/" . $commentId;

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
    */
    public function createReviewLink( $projectId, $name, $allowApprovals = false ,$currentVersionOnly = fasle, $enableDownloading = false, $requiresPassphrase = false, $password = '', $expiresAt = '' ) {

	    $url = "/projects/{$projectId}/review_links";
        $payload = array(
            "name" => $name,
            "allow_approvals" => $allowApprovals,
            "current_version_only" => $currentVersionOnly,
            "enable_downloading" => $enableDownloading,
            "requires_passphrase" => $requiresPassphrase,
            "password" => $password,
            "expires_at" => $expiresAt,
        );

		return $this->HttpRequest( "POST", $url, $payload );
    }

    /*
    |-------------------------------------------------------------------------------
    | Update a Review Link
    |-------------------------------------------------------------------------------
    | Description:    Update a Review Link
    */
    public function UpdateReviewLink( $reviewLinkId, $name, $allowApprovals = false ,$currentVersionOnly = fasle, $enableDownloading = false, $requiresPassphrase = false, $password = '', $expiresAt = '' ) {

        $url = "/review_links/{$reviewLinkId}";
        $payload = array(
            "name" => $name,
            "allow_approvals" => $allowApprovals,
            "current_version_only" => $currentVersionOnly,
            "enable_downloading" => $enableDownloading,
            "requires_passphrase" => $requiresPassphrase,
            "password" => $password,
            "expires_at" => $expiresAt,
        );

        return $this->HttpRequest( "PUT", $url, $payload );
    }

    /*
    |-------------------------------------------------------------------------------
    | Get a Review Link
    |-------------------------------------------------------------------------------
    | Description:    Get a Review Link
    */
    public function getReviewLink( $link_id ) {

	    $url = "/review_links/{$link_id}";

		return $this->HttpRequest( "GET", $url );
    }

    /*
    |-------------------------------------------------------------------------------
    | Get Review Link Items
    |-------------------------------------------------------------------------------
    | Description:    Get Review Link Items
    */
    public function getReviewLinkItems( $link_id ) {

	    $url = "/review_links/{$link_id}/items";

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
    public function getSearchAssets ( $query = '' , $teamId = '' , $accountId = '' ) {

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

        return $this->HttpRequest( "post", $url, $payload, true );
    }

    /*
    |-------------------------------------------------------------------------------
    | Search for Assets
    |-------------------------------------------------------------------------------
    | Description:    Search for Assets
    */
    public function searchAssets ( $query = '' , $teamId = '' , $accountId = '', $filter = [] ) {

        $url = "/search/assets?";
        $payload = array(
            "q"             =>  $query,
            "team_id"       =>  $teamId,
            "account_id"    =>  $accountId,
            "filter"        =>  $filter
        );

        return $this->HttpRequest( "post", $url, $payload, true );
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
    | All Http Request Handle Hare
    |-------------------------------------------------------------------------------
    | Description:    All Http Request Handle Hare
    */
    public function HttpRequest( $method, $url, $payload = false, $json = false ) {
        try {

            $url = $this->host . $url;
            $result = null;

            //initialize GuzzleHttp client
            $client = new Client([
                'http_errors' => false
            ]);

            //Config header
            $headers = array(
                'accept' => 'application/json',
                'authorization' => 'Bearer ' . $this->token,
                'content-type' => 'application/json'
            );

            if ( $json ) {
                $payload = $payload;
            } else {
                $payload = json_encode( $payload );
            }

            $response = $client->request($method, $url, [
                            'headers'  => $headers,
                            'body' =>  $payload
                        ]);

            $result = $response->getBody()->getContents();
            return \GuzzleHttp\json_decode( $result );

        } catch (\ClientException $e){
            return $e->getMessage();
        }
    }
}