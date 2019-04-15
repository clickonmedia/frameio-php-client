# frameio-php-client

run the following command to install the package

composer require clickonmedia/frameio:dev-master

--------- Basic Usage -------

    use Frameio\FrameIOClient;

    $frameIO =  new FrameIOClient("TOKEN");


--------- Run tests -------

    composer run-script test


-------- All available functions ---------

--------------------------------------------------------------
    Get user profile

    $frameIO->getProfile()
--------------------------------------------------------------
    Gets teams for a user
    NOTE: This currently returns an error because of a bug in Frame.io API

    $frameIO->getTeams()
--------------------------------------------------------------
    Get user membership for team.

    NOTE: This currently returns an error because of a bug in Frame.io API

    PARAM : team_id (Required)

    $frameIO->getTeamMembership( $teamId )
--------------------------------------------------------------
    Get user membership for team.

    NOTE: This currently returns an error because of a bug in Frame.io API

    PARAM : team_id (Required)
    PARAM : user_id (Required)
    PARAM : role (Required)

    $frameIO->addTeamMembership( $teamId, $userId, $role )
---------------------------------------------------------------
    Get user membership for team.

    PARAM : name (Default Value: Current Time)
    PARAM : private (Default Value: false)

    $frameIO->createProject( $name , $private )
---------------------------------------------------------------
    Get project by id

    PARAM : project_id (Required)

    $frameIO->getProjectById( $projectId )
---------------------------------------------------------------
    Delete project by id

    PARAM : project_id (Required)

    $frameIO->deleteProjectById( $projectId )
---------------------------------------------------------------
    Get user membership for project

    PARAM : project_id (Required)

    $frameIO->getUserMembershipForProject( $projectId )
---------------------------------------------------------------
    Add a collaborator to a team.

    PARAM : project_id (Required)
    PARAM : email_of_user (Required )

    $frameIO->addCollaboratorToTeam( $projectId, $email )
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

    $frameIO->createAsset( $rootAssetId, $name, $filesize, $description, $type, $filetype, $fileUrl, $properties)

---------------------------------------------------------------
    Get assets

    PARAM :  parent_asset_id (Required)
    PARAM :  type  (Default Value: "file")

    $frameIO->getAssets( $rootAssetId, $type )
---------------------------------------------------------------
    Get an Asset by ID

    PARAM :  asset_id (Required)

    $frameIO->getAssetById( $assetId )

---------------------------------------------------------------
    Update an Asset

    PARAM :  asset_id (Required)
    PARAM :  name (Default Value:"")
    PARAM :  properties  (Default Value: []/key value pair array)

    $frameIO->updateAssetById( $assetId, $name, $description, $properties )

---------------------------------------------------------------
    Delete an Asset By ID

    PARAM :  asset_id (Required)

    $frameIO->deleteAssetById( $assetId )
---------------------------------------------------------------
    Add a version to an Asset

    PARAM :  asset_id (Required)
    PARAM :  next_asset_id (Required)

    $frameIO->addVersionToAsset( $assetId, $nextAssetId )
---------------------------------------------------------------
    Create a Comment

    PARAM :  asset_id (Required)
    PARAM :  text (Default Value : "")
    PARAM :  annotation (Default Value : "")
    PARAM :  timestamp (Default Value : "")
    PARAM :  napageme (Default Value : "")
    PARAM :  pitch (Default Value : "")
    PARAM :  yaw (Default Value : "")

    $frameIO->createComment( $assetId, $text, $annotation, $timestamp, $page, $pitch, $yaw)
---------------------------------------------------------------
    Get Comments By Asset Id

    PARAM :  asset_id (Required)

    $frameIO->getComments( $assetId )
---------------------------------------------------------------
    Get Comment By Comment Id

    PARAM :  comment_id (Required)

    $frameIO->getCommentById( $commentId )
---------------------------------------------------------------
    Update a Comment

    PARAM :  comment_id (Required)
    PARAM :  text (Default Value : "")

    $frameIO->updateComment( $commentId, $text )
---------------------------------------------------------------
    Delete a Comment

    PARAM :  comment_id (Required)

    $frameIO->deleteCommentById ( $commentId )
---------------------------------------------------------------
    Get Review Links for Project

    PARAM :  project_id (Required)

    $frameIO->getReviewLinks( $projectId )
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

    $frameIO->createReviewLink( $projectId, $name, $allowApprovals ,$currentVersionOnly, $enableDownloading, $requiresPassphrase, $password , $expiresAt )

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

    $frameIO->UpdateReviewLink( $reviewLinkId, $name, $allowApprovals ,$currentVersionOnly, $enableDownloading, $requiresPassphrase, $password , $expiresAt )
---------------------------------------------------------------
    Get a Review Link

    PARAM :  reviewLinkId (Required)

    $frameIO->getReviewLink( $link_id )
---------------------------------------------------------------
    Get Review Link Items

    PARAM :  reviewLinkId (Required)

    $frameIO->getReviewLinkItems( $link_id )
---------------------------------------------------------------
    Add Assets to a Review Link

    PARAM :  reviewLinkId (Required)
    PARAM :  assetIds (Required, Array of ids)

    $frameIO->addAssetsToReviewLink( $reviewLinkId, $assetIds )
---------------------------------------------------------------
    Search for Assets

    PARAM :  query (Default Value : "")
    PARAM :  teamId (Default Value : "")
    PARAM :  accountId (Default Value : "")

    $frameIO->getSearchAssets ( $query , $teamId , $accountId )
---------------------------------------------------------------
    Search for Assets (Complex)

    PARAM :  query (Default Value : "")
    PARAM :  teamId (Default Value : "")
    PARAM :  accountId (Default Value : "")
    PARAM :  filter (Default Value : [], key value pair Array)

    $frameIO->searchAssets ( $query , $teamId , $accountId, $filter )
---------------------------------------------------------------
}

