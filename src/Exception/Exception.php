<?php
namespace Trails\Exception;

/**
 * TODO
 *
 * @package   trails
 *
 * @author    mlunzena
 * @copyright (c) Authors
 * @version   $Id: trails.php 7001 2008-04-04 11:20:27Z mlunzena $
 */

class Exception extends \Exception {

  /**
   * <FieldDescription>
   *
   * @access private
   * @var <type>
   */
  public $headers;


  /**
   * @param  int     the status code to be set in the response
   * @param  string  a human readable presentation of the status code
   * @param  array   a hash of additional headers to be set in the response
   *
   * @return void
   */
  function __construct($status = 500, $reason = NULL, $headers = array()) {
    if ($reason === NULL) {
      $reason = Response::get_reason($status);
    }
    parent::__construct($reason, $status);
    $this->headers = $headers;
  }


  /**
   * <MethodDescription>
   *
   * @param  type       <description>
   *
   * @return type       <description>
   */
  function __toString() {
    return "{$this->code} {$this->message}";
  }
}
