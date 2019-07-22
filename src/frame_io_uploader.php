<?php

namespace Frameio;

class FrameIOUploader {

    private $asset;
    private $file;

    public function __construct( $asset, $filepath ) {
        $this->asset = $asset;
        $this->file = $filepath;
    }

    private function _uploadRequest( $url, $chunk ) {
        $curl = curl_init( $url );

        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, "PUT" );

        curl_setopt( $curl, CURLOPT_POSTFIELDS, $chunk );
        curl_setopt( $curl, CURLOPT_URL, $url );

        curl_setopt( $curl, CURLOPT_HTTPHEADER, array(
            'content-type: ' . $this->asset->filetype,
            'x-amz-acl: private'
        ));

        curl_exec( $curl );

        $info = curl_getinfo( $curl );

        if ( curl_errno( $curl ) ) {
            trigger_error( 'cURL error: ' . curl_error( $curl ) );
        }

        curl_close( $curl );

        return $info;
    }

    public function upload() {
        $total_size = filesize( $this->file );
        $upload_urls = $this->asset->upload_urls;

        $file = fopen( $this->file, "r+" ) or die( "Unable to open file!" );
        $size = intval( ceil ( $total_size / sizeof( $upload_urls ) ) );
        $info = array();

        if ( $file ) {
            $buffer_size = $size + 1;
            $i = 0;

            // While not at end of file
            while( !feof( $file ) ) {
                $chunk = fread( $file, $buffer_size );

                // Upload chunk into next upload URL
                if( ! empty( $chunk ) ) {
                    $info = $this->_uploadRequest( $upload_urls[$i], $chunk );
                }

                $i++;
            }

            fclose( $file );
        }

        return array(
            'success' => $info["http_code"] == 200,
            'status' => $info["http_code"],
            'info' => $info
        );
    }
}
