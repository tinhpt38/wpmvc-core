<?php
class WP_Error extends stdClass
{
    public function __construct($code = 0, $message = '')
    {
        $this->message = $message;
        $this->code = $code;
    }
    public function get_error_message()
    {
        return $this->message;
    }
    public function get_error_code()
    {
        return $this->code;
    }
}