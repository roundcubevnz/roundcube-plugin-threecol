<?php

/**
 * ThreeCol
 *
 * Plugin to switch Roundcube to a three column layout
 *
 * @version @package_version@
 * @author Philip Weir
 * @author James Buncle <jbuncle@names.co.uk>
 */
class threecol extends rcube_plugin
{
	const LAYOUT_NONE = 'none';
	const LAYOUT_BELOW = 'below';
	const LAYOUT_RIGHT = 'right';
	const CONFIG_LAYOUT = 'previewpane_layout';
	const CONFIG_PANE = 'preview_pane';
	const PREFERENCE_LAYOUT = 'previewpane_layout';
	const INPUT_LAYOUT = '_previewpane_layout';

	public $task = 'mail|settings';
	private $driver;

	public function init()
	{
		$rcmail = rcube::get_instance();
		$no_override = array_flip($rcmail->config->get('dont_override', array()));
		$this->driver = $this->home . '/skins/' . $rcmail->config->get('skin') . '/func.php';

		if ($rcmail->task == 'mail' && $rcmail->action == '' && $rcmail->config->get('previewpane_layout', 'below') == 'right') {
			$this->add_hook('render_page', array($this, 'render'));
		} elseif ($rcmail->task == 'settings' && !isset($no_override['previewpane_layout'])) {
			$this->add_hook('preferences_list', array($this, 'show_settings'));
			$this->add_hook('preferences_save', array($this, 'save_settings'));
		}
	}

	public function render($args)
	{
		$this->include_script($this->local_skin_path() . '/threecol.js');
		$this->include_stylesheet($this->local_skin_path() . '/threecol.css');

		if (is_readable($this->driver)) {
			include_once($this->driver);
			if (!function_exists('render_page')) {
				rcube::raise_error(array(
					'code' => 600,
					'type' => 'php',
					'file' => __FILE__,
					'line' => __LINE__,
					'message' => "ThreeCol plugin: Broken driver: $this->driver"
					), true, false);
			}

			$args = render_page($args);
		}
		return $args;
	}

	public function show_settings($args)
	{
		if ($args['section'] == 'mailbox') {
			$this->add_texts('localization/');

			$field_id = 'rcmfd_previewpane_layout';
			$select = new html_select(array('name' => self::INPUT_LAYOUT, 'id' => $field_id));
			$select->add(rcmail::Q($this->gettext('none')), self::LAYOUT_NONE);
			$select->add(rcmail::Q($this->gettext('below')), self::LAYOUT_BELOW);
			$select->add(rcmail::Q($this->gettext('right')), self::LAYOUT_RIGHT);

			// add new option at the top of the list
			$val = $this->get_current_preference();
			$args['blocks']['main']['options']['preview_pane']['content'] = $select->show($val);
		}

		return $args;
	}

	private function get_current_preference()
	{
		$prefs = rcmail::get_instance()->user->get_prefs();
		if (array_key_exists(self::PREFERENCE_LAYOUT, $prefs)) {
			$preference = $prefs[self::PREFERENCE_LAYOUT];
			if (in_array($preference, [self::LAYOUT_BELOW, self::LAYOUT_RIGHT, self::LAYOUT_NONE,])) {
				return $preference;
			}
		}
		if (rcube::get_instance()->config->get(self::CONFIG_PANE)){
			return rcube::get_instance()->config->get(self::CONFIG_LAYOUT,'below');
		} else {
			return 'none';
		}
	}

	public function save_settings($args)
	{
		if ($args['section'] == 'mailbox') {
			$newLayout = $this->get_layout_from_post();

			$args['prefs']['preview_pane'] = $newLayout != self::LAYOUT_NONE;

			if ($newLayout != self::LAYOUT_NONE) {
				$args['prefs'][self::PREFERENCE_LAYOUT] = $newLayout;
			} else {
				$args['prefs'][self::PREFERENCE_LAYOUT] = rcube::get_instance()->config->get(self::CONFIG_LAYOUT, self::LAYOUT_BELOW);
			}
		}

		return $args;
	}

	private function get_layout_from_post()
	{
		return rcube_utils::get_input_value(self::INPUT_LAYOUT, rcube_utils::INPUT_POST);
	}
}