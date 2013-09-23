<?php
/**
 * Convenience AppController that allows a controller to
 * automatically serve up a JSON version of the response
 * when it is requested via an Ajax call, such as in jQuery's
 * jQuery.ajax() method
 *
 * @package default
 */
abstract class AjaxController extends Controller {

/**
 * Override for the current HTTP-Status code
 *
 * For use when Controller::redirect() is not called and
 * the status code is the result of some request error
 *
 * @var string
 * @access protected
 */
	protected $_status = null;

/**
 * Special JSON-response short-circuit variable
 *
 * @var string
 * @access protected
 */
	protected $_disableAjax = false;

/**
 * Object constructor - Adds the RequestHandler and Session
 * components if necessary
 *
 * @return void
 */
	public function __construct() {
		if (!in_array('RequestHandler', $this->components)) {
			$this->components[] = 'RequestHandler';
		}

		if (!in_array('Session', $this->components)) {
			$this->components[] = 'Session';
		}

		parent::__construct();
	}

/**
 * Convenience method to short-circuit Ajax access to methods
 * and force a regular response from the controller
 *
 * @return void
 */
	protected function _disableAjax() {
		$args = func_get_args();
		if (is_array($args[0])) {
			$args = $args[0];
		}

		foreach ($args as $arg) {
			if ($arg == $this->action) {
				$this->_disableAjax = true;
				break;
			}
		}
	}

/**
 * Override the redirect method to call $this->_respond() in case
 * the response should be json
 *
 * Will terminate if the response should be json
 *
 * @param mixed $url A string or array-based URL pointing to another location within the app,
 *     or an absolute URL
 * @param integer $status Optional HTTP status code (eg: 404)
 * @param boolean $exit If true, exit() will be called after the redirect
 * @return mixed void if $exit = false. Terminates script if $exit = true
 */
	public function redirect($url, $status = null, $exit = true) {
		$this->_respond(array(
			'redirect' => $url,
			'status' => $status,
		));

		parent::redirect($url, $status, $exit);
	}

/**
 * Called after the controller action is run to set the response to
 * json if necessary
 *
 * @link http://book.cakephp.org/view/984/Callbacks
 */
	public function beforeRender() {
		$this->_respond();
		return parent::beforeRender();
	}

/**
 * Turns the current controller setup into a JSON response for use
 * in Ajax requests. If the current request should not be responded to
 * in JSON, then this short-circuits itself. Otherwise, it returns the
 * JSON response and stops processing
 *
 * @param mixed $options Override array for options
 * @return mixed false if not Ajax/JSON request, stops processing otherwise
 * @access protected
 */
	protected function _respond($options = array()) {
		$isAjax = !$this->_disableAjax
							&& $this->RequestHandler->isAjax()
							&& $this->RequestHandler->accepts('json');
		if (!$isAjax) {
			return false;
		}

		$message = $this->Session->read('Message.flash');
		if ($message) {
			$this->Session->delete('Message.flash');
		}

		$this->RequestHandler->respondAs('json');
		$this->RequestHandler->renderAs($this, 'json');
		$this->set($options);

		$options = array_merge(array(
			'referer' => $this->referer(),
			'status' => empty($this->_status) ? 200 : $this->_status,
			'redirect' => null,
			'message' => ($message) ? $message : null,
			'content' => null,
			'data' => empty($this->data) ? null : $this->data,
			'errors' => empty($this->validationErrors) ? null : $this->validationErrors,
			'variables' => empty($this->viewVars) ? null : $this->viewVars,
		), $options);

		if (is_array($options['redirect'])) {
			$options['redirect'] = Router::url($options['redirect'], true);
		}

		Configure::write('debug', 0);
		header('Content-type: application/json');
		echo json_encode($options);
		$this->_stop();
	}

}
