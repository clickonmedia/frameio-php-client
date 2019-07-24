# Frame.io PHP Client

Run the following command to install the package:

```
composer require clickonmedia/frameio-php-client
```


## Frame.io API Documentation

https://docs.frame.io/reference


## Basic Usage

```
require __DIR__ . '/vendor/autoload.php';
use Frameio\FrameIOClient;

$frameIO =  new FrameIOClient(<frameio-token>);
echo $frameIO->getHost();
```


## Development / Run tests

Copy *.env.example* file from the root directory and rename the file to *.env*.
Fill in the variables in the file based on information from the Frame.io dashboard.

```
composer install
composer run-script test
```


## Available functionality

**Get user profile**

```
$frameIO->getProfile()
```


**Gets teams for a user**

```
$frameIO->getTeams()
```


**Get Teams By Account Id**

> @param string $accountId Account ID (required)

```
$frameIO->getTeamsByAccountId( $accountId )
```


**Get user membership for a team**

> @param string $teamId Team ID (required)

```
$frameIO->getTeamMembership( $teamId )
```


**Add user membership to a team**

> @param string $teamId Team ID (required)<br />
> @param string $userId User ID (required)<br />
> @param string $role Member Role (required)

```
$frameIO->addTeamMembership( $teamId, $userId, $role )
```


**Create project**

> @param string $name Project name (required)<br />
> @param string $teamId Team ID (required)<br />
> @param boolean $private Set project as private (optional)

```
$frameIO->createProject( $name, $teamId, $private )
```


**Get project by id**

> @param string $projectId Project ID (required)

```
$frameIO->getProjectById( $projectId )
```


**Delete project by id**

> @param string $projectId Project ID (required)

```
$frameIO->deleteProjectById( $projectId )
```


**Get Projects By Team ID**

> @param string $teamId Team ID (required)

```
$frameIO->getProjectsByTeamid( $teamId )
```


**Get user membership for project**

> @param string $projectId Project ID (required)

```
$frameIO->getUserMembershipForProject( $projectId )
```


**Add a collaborator to a team**

> @param string $projectId Project ID (required)<br />
> @param string $email User email address (required)

```
$frameIO->addCollaboratorToTeam( $projectId, $email )
```


**Create an asset**

https://docs.frame.io/reference#createasset

> @param string $projectId Project ID (required)<br />

> @param string $args Additional arguments (required):<br />

> name          string      Name (required)<br />
> filesize      int         Filesize (required)<br />
> type          string      Type (required: "file" or "folder")<br />
> description   string      Description (optional)<br />
> filetype      string      File type  (optional, e.g. "video/mp4")<br />
> fileUrl       string      File URL  (optional)<br />
> properties    array       Custom Properties  (optional)

```
$frameIO->createAsset( $projectId, $args );
```


**Get assets**

> @param string $rootAssetId Parent asset ID (required)<br />
> @param string $type Type (default value: "file")

```
$frameIO->getAssets( $rootAssetId, $type )
```


**Get an Asset by ID**

> @param string $assetId Asset ID (required)

```
$frameIO->getAssetById( $assetId )
```


**Update an Asset**

> @param string $assetId Asset ID (required)<br />
> @param string $name Name (required)<br />
> @param string $args Additional arguments (optional):<br />
> description   string      Description (default value: "")<br />
> properties    string      Properties (default value: []/key value pair array)

```
$frameIO->updateAssetById( $assetId, $name, $args )
```


**Delete an Asset By ID**

> @param string $assetId Asset ID (required)

```
$frameIO->deleteAssetById( $assetId )
```


**Add a version to an Asset**

> @param string $assetId Asset ID (required)<br />
> @param string $nextAssetId Next Asset ID (required)

```
$frameIO->addVersionToAsset( $assetId, $nextAssetId )
```


**Get Project Assets**

> @param string $projectId Project ID (required)

```
$frameIO->getProjectAssets( $projectId )
```


**Upload a file to an asset**

> @param object $asset The asset object (required)
> @param string $file_path File path of the file (required)

```
$frameIO->upload( $asset, $file_path );
```

**Create a Comment**

> @param string $assetId Asset ID (required)<br />
> @param object $args Additional arguments (required)<br />

> text              string      Text (default value: "")<br />
> annotation        string      Annotation (default value: "")<br />
> timestamp         string      Timestamp (default value: "")<br />
> napageme          string      napageme (default value: "")<br />
> pitch             string      Pitch (default value: "")<br />
> yaw               string      yaw (default value: "")

```
$frameIO->createComment( $assetId, $args )
```


**Get Comments By Asset Id**

> @param string $assetId Asset ID (required)

```
$frameIO->getComments( $assetId )
```


**Get Comment By Comment Id**

> @param string $commentId Comment ID (required)

```
$frameIO->getCommentById( $commentId )
```


**Update a Comment**

> @param string $commentId Comment ID (required)<br />
> @param string $text Comment text (default value: "")

```
$frameIO->updateComment( $commentId, $text )
```


**Delete a Comment**

> @param string $commentId Comment ID (required)

```
$frameIO->deleteCommentById ( $commentId )
```


**Get Review Links for Project**

> @param string $projectId Project ID (required)

```
$frameIO->getReviewLinks( $projectId )
```


**Create a Review Link**

> @param string $projectId Project ID (required)<br />
> @param string $name Review link name (required)<br />
> @param string $args Review link name (optional):<br />

> allow_approvals       boolean         Allow Approvals (default value: false)<br />
> current_version_only  boolean         Current Version Only (default value: false)<br />
> enable_downloading    boolean         Enable Downloading (default value: false)<br />
> requires_passphrase   boolean         Requires Passphrase (default value: false)<br />
> password              string          Password (default value: "")<br />
> expires_at            string          Expires At (default value: "")


```
$frameIO->createReviewLink( $projectId, $name, $args )
```


**Update a Review Link**

> @param string $reviewLinkId Review link ID (required)<br />
> @param string $name Review name (required)<br />
> @param string $args Review link name (optional):<br />

> allow_approvals       boolean         Allow Approvals (default value: false)<br />
> current_version_only  boolean         Current Version Only (default value: false)<br />
> enable_downloading    boolean         Enable Downloading (default value: false)<br />
> requires_passphrase   boolean         Requires Passphrase (default value: false)<br />
> password              string          Password (default value: "")<br />
> expires_at            string          Expires At (default value: "")

```
$frameIO->UpdateReviewLink( $reviewLinkId, $name, args )
```


**Get a Review Link**

> @param string $link_id Review link ID (required)

```
$frameIO->getReviewLink( $link_id )
```


**Get Review Link Items**

> @param string $link_id Review link ID (required)

```
$frameIO->getReviewLinkItems( $link_id )
```


**Add Assets to a Review Link**

https://docs.frame.io/reference#reviewlinkitemcreate

> @param    string     $reviewLinkId       Review link ID (required)<br />
> @param    array      $assetIds           Asset IDs (required, array of ids)

```
$frameIO->addAssetsToReviewLink( $reviewLinkId, $assetIds )
```


**Search for Assets**

> @param string $query Search query (default value: "")<br />
> @param string $teamId Team ID (default value: "")<br />
> @param string $accountId Account ID (default value: "")

```
$frameIO->getSearchAssets ( $query, $teamId, $accountId )
```


**Search for Assets (Complex)**

> @param string $query Search query (default value: "")<br />
> @param string $teamId Team ID (default value: "")<br />
> @param string $accountId Account ID (default value: "")<br />
> @param string $filter Filter for the query (default value: [], key value pair Array)

```
$frameIO->searchAssets ( $query , $teamId , $accountId, $filter )
```
