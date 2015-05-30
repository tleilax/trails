<?php
namespace Trails;

/**
 * The flash provides a way to pass temporary objects between actions.
 * Anything you place in the flash will be exposed to the very next action and
 * then cleared out. This is a great way of doing notices and alerts, such as
 * a create action that sets
 * <tt>$flash->set('notice', "Successfully created")</tt>
 * before redirecting to a display action that can then expose the flash to its
 * template.
 *
 *
 * @author    mlunzena
 * @copyright (c) Authors
 *
 * @version   $Id: trails.php 7001 2008-04-04 11:20:27Z mlunzena $
 */
class Flash implements \ArrayAccess
{
  /**
   * @ignore
   */
  private $flash = array();
  private $used = array();

  /**
   * <MethodDescription>.
   *
   * @return type       <description>
   */
  public static function instance()
  {
      if (!isset($_SESSION)) {
          throw new Exception\SessionRequired();
      }

      if (!isset($_SESSION['trails_flash'])) {
          $_SESSION['trails_flash'] = new self();
      }

      return $_SESSION['trails_flash'];
  }

    public function offsetExists($offset)
    {
        return isset($this->flash[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    public function offsetUnset($offset)
    {
        unset($this->flash[$offset], $this->used[$offset]);
    }

  /**
   * Used internally by the <tt>keep</tt> and <tt>discard</tt> methods
   *     use()               # marks the entire flash as used
   *     use('msg')          # marks the "msg" entry as used
   *     use(null, false)    # marks the entire flash as unused
   *                         # (keeps it around for one more action)
   *     use('msg', false)   # marks the "msg" entry as unused
   *                         # (keeps it around for one more action).
   *
   * @param mixed  a key.
   * @param bool   used flag.
   */
  public function _use($k = null, $v = true)
  {
      if ($k) {
          $this->used[$k] = $v;
      } else {
          foreach ($this->used as $k => $value) {
              $this->_use($k, $v);
          }
      }
  }

  /**
   * Marks the entire flash or a single flash entry to be discarded by the end
   * of the current action.
   *
   *     $flash->discard()             # discards entire flash
   *                                   # (it'll still be available for the
   *                                   # current action)
   *     $flash->discard('warning')    # discard the "warning" entry
   *                                   # (it'll still be available for the
   *                                   # current action)
   *
   * @param mixed  a key.
   */
  public function discard($k = null)
  {
      $this->_use($k);
  }

  /**
   * Returns the value to the specified key.
   *
   * @param mixed  a key.
   *
   * @return mixed the key's value.
   */
  public function &get($k)
  {
      $return = null;
      if (isset($this->flash[$k])) {
          $return = &$this->flash[$k];
      }

      return $return;
  }

  /**
   * Keeps either the entire current flash or a specific flash entry available
   * for the next action:.
   *
   *    $flash->keep()           # keeps the entire flash
   *    $flash->keep('notice')   # keeps only the "notice" entry, the rest of
   *                             # the flash is discarded
   *
   * @param mixed  a key.
   */
  public function keep($k = null)
  {
      $this->_use($k, false);
  }

  /**
   * Sets a key's value.
   *
   * @param mixed  a key.
   * @param mixed  its value.
   */
  public function set($k, $v)
  {
      $this->keep($k);
      $this->flash[$k] = $v;
  }

  /**
   * Sets a key's value by reference.
   *
   * @param mixed  a key.
   * @param mixed  its value.
   */
  public function set_ref($k, &$v)
  {
      $this->keep($k);
      $this->flash[$k] = &$v;
  }

  /**
   * <MethodDescription>.
   *
   * @return type       <description>
   */
  public function sweep()
  {

    # remove used values
    foreach (array_keys($this->flash) as $k) {
        if ($this->used[$k]) {
            unset($this->flash[$k], $this->used[$k]);
        } else {
            $this->_use($k);
        }
    }

    # cleanup if someone meddled with flash or used
    $fkeys = array_keys($this->flash);
      $ukeys = array_keys($this->used);
      foreach (array_diff($fkeys, $ukeys) as $k => $v) {
          unset($this->used[$k]);
      }
  }

  /**
   * <MethodDescription>.
   *
   * @return type       <description>
   */
  public function __toString()
  {
      $values = array();
      foreach ($this->flash as $k => $v) {
          $values[] = sprintf("'%s': [%s, '%s']",
                          $k, var_export($v, true),
                          $this->used[$k] ? 'used' : 'unused');
      }

      return '{'.implode(', ', $values)."}\n";
  }

  /**
   * <MethodDescription>.
   *
   * @param  type       <description>
   *
   * @return type       <description>
   */
  public function __sleep()
  {
      $this->sweep();

      return array('flash', 'used');
  }

  /**
   * <MethodDescription>.
   *
   * @param  type       <description>
   *
   * @return type       <description>
   */
  public function __wakeUp()
  {
      $this->discard();
  }
}
