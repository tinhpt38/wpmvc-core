<?php
class WP_Image extends stdClass
{
    public function __construct($path = 0)
    {
        $this->ID = 0;
        $this->path = $path;
        $this->post_name = 'hello-world';
        $this->post_title = trim('Hello World ' . $id);
        $this->post_content = 'Hello World';
    }

    public function resize($width, $height, $crop) {}

    public function save($path) {}
}