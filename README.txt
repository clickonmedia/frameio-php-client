# frameio-php-client

run the following command to install the package

composer require clickonmedia/frameio:dev-master

--------- Config FrameIOClient -------

    use Frameio\FrameIOClient;

    $frameIO =  new FrameIOClient("TOKEN");

-------- all function in $frameIO variable---------

--------------------------------------------------------------
    Get user profile

    $frameIo->getProfile()
--------------------------------------------------------------
    Gets teams for a user
    NOTE: This currently returns an error because of a bug in Frame.io API

    $frameIo->getTeams()
--------------------------------------------------------------
    Get user membership for team.

    NOTE: This currently returns an error because of a bug in Frame.io API

    PARAM : team_id (Required)

    $frameIo->getTeamMembership( $teamId )
--------------------------------------------------------------
    Get user membership for team.

    NOTE: This currently returns an error because of a bug in Frame.io API

    PARAM : team_id (Required)
    PARAM : user_id (Required)
    PARAM : role (Required)

    $frameIo->addTeamMembership( $teamId, $userId, $role )
---------------------------------------------------------------
    Get user membership for team.

    PARAM : name (Default Value: Current Time)
    PARAM : private (Default Value: false)

    $frameIo->createProject( $name , $private )
---------------------------------------------------------------
    Get project by id

    PARAM : project_id (Required)

    $frameIo->getProjectById( $projectId )
---------------------------------------------------------------
    Delete project by id

    PARAM : project_id (Required)

    $frameIo->deleteProjectById( $projectId )
---------------------------------------------------------------
    Get user membership for project

    PARAM : project_id (Required)

    $frameIo->getUserMembershipForProject( $projectId )
---------------------------------------------------------------
    Add a collaborator to a team.

    PARAM : project_id (Required)
    PARAM : email_of_user (Required )

    $frameIo->addCollaboratorToTeam( $projectId, $email )
---------------------------------------------------------------
    Create an asset

    PARAM :  parent_asset_id (Required)
    PARAM :  name (Required)
    PARAM :  filesize (Required)
    PARAM :  description (Default Value: "")
    PARAM :  type  (Default Value: "file")
    PARAM :  filetype  (Default Value: "mp4")
    PARAM :  fileUrl  (Default Value: "")
    PARAM :  properties  (Default Value: []/key value pair array)

    $frameIo->createAsset( $rootAssetId, $name, $filesize, $description, $type, $filetype, $fileUrl, $properties)

---------------------------------------------------------------
    Get assets

    PARAM :  parent_asset_id (Required)
    PARAM :  type  (Default Value: "file")

    $frameIo->getAssets( $rootAssetId, $type )
---------------------------------------------------------------
    Get an Asset by ID

    PARAM :  asset_id (Required)

    $frameIo->getAssetById( $assetId )

---------------------------------------------------------------
    Update an Asset

    PARAM :  asset_id (Required)
    PARAM :  name (Default Value:"")
    PARAM :  properties  (Default Value: []/key value pair array)

    $frameIo->updateAssetById( $assetId, $name, $description, $properties )

---------------------------------------------------------------
    Delete an Asset By ID

    PARAM :  asset_id (Required)

    $frameIo->deleteAssetById( $assetId )
---------------------------------------------------------------
    Add a version to an Asset

    PARAM :  asset_id (Required)
    PARAM :  next_asset_id (Required)

    $frameIo->addVersionToAsset( $assetId, $nextAssetId )
---------------------------------------------------------------
    Create a Comment

    PARAM :  asset_id (Required)
    PARAM :  text (Default Value : "")
    PARAM :  annotation (Default Value : "")
    PARAM :  timestamp (Default Value : "")
    PARAM :  napageme (Default Value : "")
    PARAM :  pitch (Default Value : "")
    PARAM :  yaw (Default Value : "")

    $frameIo->createComment( $assetId, $text, $annotation, $timestamp, $page, $pitch, $yaw)
---------------------------------------------------------------
    Get Comments By Asset Id

    PARAM :  asset_id (Required)

    $frameIo->getComments( $assetId )
---------------------------------------------------------------
    Get Comment By Comment Id

    PARAM :  comment_id (Required)

    $frameIo->getCommentById( $commentId )
---------------------------------------------------------------
    Update a Comment

    PARAM :  comment_id (Required)
    PARAM :  text (Default Value : "")

    $frameIo->updateComment( $commentId, $text )
---------------------------------------------------------------
    Delete a Comment

    PARAM :  comment_id (Required)

    $frameIo->deleteCommentById ( $commentId )
---------------------------------------------------------------
    Get Review Links for Project

    PARAM :  project_id (Required)

    $frameIo->getReviewLinks( $projectId )
---------------------------------------------------------------

    Create a Review Link

    PARAM :  project_id (Required)
    PARAM :  name (Required)
    PARAM :  allow_approvals (Default Value : false)
    PARAM :  current_version_only (Default Value : false)
    PARAM :  enable_downloading (Default Value : false)
    PARAM :  requires_passphrase (Default Value : false)
    PARAM :  password  (Default Value : "")
    PARAM :  expires_at  (Default Value : "")

    $frameIo->createReviewLink( $projectId, $name, $allowApprovals ,$currentVersionOnly, $enableDownloading, $requiresPassphrase, $password , $expiresAt )

---------------------------------------------------------------
    Update a Review Link

    PARAM :  reviewLinkId (Required)
    PARAM :  name (Required)
    PARAM :  allow_approvals (Default Value : false)
    PARAM :  current_version_only (Default Value : false)
    PARAM :  enable_downloading (Default Value : false)
    PARAM :  requires_passphrase (Default Value : false)
    PARAM :  password  (Default Value : "")
    PARAM :  expires_at  (Default Value : "")

    $frameIo->UpdateReviewLink( $reviewLinkId, $name, $allowApprovals ,$currentVersionOnly, $enableDownloading, $requiresPassphrase, $password , $expiresAt )
---------------------------------------------------------------
    Get a Review Link

    PARAM :  reviewLinkId (Required)

    $frameIo->getReviewLink( $link_id )
---------------------------------------------------------------
    Get Review Link Items

    PARAM :  reviewLinkId (Required)

    $frameIo->getReviewLinkItems( $link_id )
---------------------------------------------------------------
    Add Assets to a Review Link

    PARAM :  reviewLinkId (Required)
    PARAM :  assetIds (Required, Array of ids)

    $frameIo->addAssetsToReviewLink( $reviewLinkId, $assetIds )
---------------------------------------------------------------
    Search for Assets

    PARAM :  query (Default Value : "")
    PARAM :  teamId (Default Value : "")
    PARAM :  accountId (Default Value : "")

    $frameIo->getSearchAssets ( $query , $teamId , $accountId )
---------------------------------------------------------------
    Search for Assets (Complex)

    PARAM :  query (Default Value : "")
    PARAM :  teamId (Default Value : "")
    PARAM :  accountId (Default Value : "")
    PARAM :  filter (Default Value : [], key value pair Array)

    $frameIo->searchAssets ( $query , $teamId , $accountId, $filter )
---------------------------------------------------------------
}

