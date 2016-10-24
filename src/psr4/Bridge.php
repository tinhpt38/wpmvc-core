<?php

namespace WPMVC;

use WPMVC\Cache;
use WPMVC\Log;
use WPMVC\Contracts\Plugable;
use WPMVC\MVC\Engine;

/**
 * Plugin class.
 * To be extended as main plugin / theme class.
 * Part of the core library of Wordpress Plugin / Wordpress Theme.
 *
 * @link https://github.com/amostajo/wordpress-plugin-core/blob/v1.0/src/psr4/Plugin.php
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC
 * @version 2.0.4
 */
abstract class Bridge implements Plugable
{
    /**
     * Configuration file.
     * @var array
     * @since 1.0.0
     */
    protected $config;

    /**
     * MVC engine.
     * @var object Engine
     * @since 1.0.0
     */
    protected $mvc;

    /**
     * Add ons.
     * @var array
     * @since 1.0.2
     */
    protected $addons;

    /**
     * List of Wordpress action hooks to add.
     * @var array
     * @since 1.0.3
     */
    protected $actions;

    /**
     * List of Wordpress filter hooks to add.
     * @var array
     * @since 1.0.3
     */
    protected $filters;

    /**
     * List of Wordpress shortcodes to add.
     * @var array
     * @since 1.0.3
     */
    protected $shortcodes;

    /**
     * List of Wordpress widgets to add.
     * @var array
     * @since 1.0.3
     */
    protected $widgets;

    /**
     * List of Models (post_type) to add/register.
     * @var array
     * @since 2.0.4
     */
    protected $models;

    /**
     * Main constructor
     * @since 1.0.0
     * @since 2.0.3 Cache and log are obligatory configuration settings.
     * @since 2.0.4 Added models.
     *
     * @param array $config Configuration options.
     */
    public function __construct( Config $config )
    {
        $this->actions = [];
        $this->filters = [];
        $this->shortcodes = [];
        $this->widgets = [];
        $this->models = [];
        $this->config = $config;
        $this->mvc = new Engine(
            $this->config->get( 'paths.views' ),
            $this->config->get( 'paths.controllers' ),
            $this->config->get( 'namespace' )
        );
        $this->addons = array();
        $this->set_addons();
        Cache::init( $this->config );
        Log::init( $this->config );
    }

    /**
     * Returns READ-ONLY properties.
     * @since 1.0.2
     *
     * @param string $property Property name.
     *
     * @return mixed
     */
    public function __get( $property )
    {
        if ( property_exists( $this, $property ) ) {
            switch ( $property ) {
                case 'config':
                    return $this->$property;
            }
        }
        return null;
    }

    /**
     * Calls to class or addon method.
     * Checks "addon_" prefix to search for addon methods.
     * @since 1.0.2
     * @since 1.0.3 Added MVC controller and views direct calls.
     *
     * @return mixed
     */
    public function __call( $method, $args )
    {
        if ( preg_match( '/addon\_/', $method ) ) {
            $method = preg_replace( '/addon\_/', '', $method );
            // Search addons
            for ( $i = count( $this->addons ) - 1; $i >= 0; --$i ) {
                if ( method_exists( $this->addons[$i], $method ) ) {
                    call_user_func_array( [ $this->addons[$i], $method ], $args );
                    break;
                }
            }
        } else if ( preg_match( '/^\_c\_/', $method ) ) {
            // Expected format
            // _c_[return]_[mvccall]
            // Sample - _c_void_ConfigController@local
            $is_return = preg_match( '/^\_c\_return\_/', $method );
            $method = preg_replace( '/^\_c\_(return|void)\_/', '', $method );
            if ( $is_return ) {
                return $this->mvc->action_args(
                    $method,
                    $this->override_args( $method, $args )
                );
            } else {
                $this->mvc->call_args(
                    $method,
                    $this->override_args( $method, $args )
                );
            }
        } else if ( preg_match( '/^\_v\_/', $method ) ) {
            // Expected format
            // _v_[return]_[mvccall]
            // Sample - _v_void_View@test.view
            $is_return = preg_match( '/^\_v\_return\_/', $method );
            $method = preg_replace( '/^\_v\_(return|void)\_/', '', $method );
            $view =  preg_replace( '/[vV]iew\@/', '', $method );
            if ( $is_return ) {
                return $this->mvc->view->get(
                    $view,
                    $this->override_args( $method, $args )
                );
            } else {
                $this->mvc->view->show(
                    $view,
                    $this->override_args( $method, $args )
                );
            }
        } else {
            return call_user_func_array( [ $this, $method ], $args );
        }
    }

    /**
     * Sets plugin addons.
     * @since 1.0.2
     *
     * @return void
     */
    protected function set_addons()
    {
        if ( $this->config->get( 'addons' ) ) {
            foreach ( $this->config->get( 'addons' ) as $addon ) {
                $this->addons[] = new $addon( $this );
            }
        }
    }

    /**
     * Displays view with the parameters passed by.
     * @since 1.0.1
     *
     * @param string $view   Name and location of the view within "theme/views" path.
     * @param array  $params View parameters passed by.
     *
     * @return void
     */
    public function view( $view, $params = array() )
    {
        $this->mvc->view->show( $view, $params );
    }

    /**
     * Called by autoload to init class.
     * @since 1.0.2
     * @return void
     */
    public function autoload_init()
    {
        $this->init();
        // Addons
        for ( $i = count( $this->addons ) - 1; $i >= 0; --$i ) {
            $this->addons[$i]->init();
        }
    }

    /**
     * Called by autoload to init on admin.
     * @since 1.0.2
     * @return void
     */
    public function autoload_on_admin()
    {
        $this->on_admin();
        // Addons
        for ( $i = count( $this->addons ) - 1; $i >= 0; --$i ) {
            $this->addons[$i]->on_admin();
        }
    }

    /**
     * Init.
     * @since 1.0.2
     * @return void
     */
    public function init()
    {
        // TODO custom code.
    }

    /**
     * On admin Dashboard.
     * @since 1.0.2
     * @return void
     */
    public function on_admin()
    {
        // TODO custom code.
    }

    /**
     * Adds a Wordpress action hook.
     * @since 1.0.3
     *
     * @param string $hook             Wordpress hook name.
     * @param string $mvc_call      Lightweight MVC call. (i.e. 'Controller@method')
     * @param mixed  $priority      Execution priority or MVC params.
     * @param mixed  $accepted_args Accepted args or priority.
     * @param int    $args          Accepted args.
     */
    public function add_action( $hook, $mvc_call, $priority = 10, $accepted_args = 1, $args = 1 )
    {
        $this->actions[] = $this->get_hook(
            $hook,
            $mvc_call,
            $priority,
            $accepted_args,
            $args
        );
    }

    /**
     * Adds a Wordpress filter hook.
     * @since 1.0.3
     *
     * @param string $hook             Wordpress hook name.
     * @param string $mvc_call      Lightweight MVC call. (i.e. 'Controller@method')
     * @param mixed  $priority      Execution priority or MVC params.
     * @param mixed  $accepted_args Accepted args or priority.
     * @param int    $args          Accepted args.
     */
    public function add_filter( $hook, $mvc_call, $priority = 10, $accepted_args = 1, $args = 1 )
    {
        $this->filters[] = $this->get_hook(
            $hook,
            $mvc_call,
            $priority,
            $accepted_args,
            $args
        );
    }

    /**
     * Adds a Wordpress shortcode.
     * @since 1.0.3
     *
     * @param string $tag        Wordpress tag name.
     * @param string $mvc_call Lightweight MVC call. (i.e. 'Controller@method')
     */
    public function add_shortcode( $tag, $mvc_call, $mvc_args = null )
    {
        $this->shortcodes[] = [
            'tag'        => $tag,
            'mvc'        => $mvc_call,
            'mvc_args'    => $mvc_args,
        ];
    }

    /**
     * Adds a Wordpress shortcode.
     * @since 1.0.3
     *
     * @param string $class Widget class name to add.
     */
    public function add_widget( $class )
    {
        $this->widgets[] = $class;
    }

    /**
     * Adds a Model for registration.
     * @since 2.0.4
     *
     * @param string $class Model class name to add.
     */
    public function add_model( $class )
    {
        $this->models[] = $class;
    }

    /**
     * Adds hooks and filters into Wordpress core.
     * @since 1.0.3
     * @since 2.0.4 Added models.
     */
    public function add_hooks()
    {
        if ( function_exists( 'add_action' )
            && function_exists( 'add_filter' )
            && function_exists( 'add_shortcode' )
        ) {
            // Actions
            foreach ( $this->actions as $action ) {
                add_action(
                    $action['hook'],
                    [ &$this, $this->get_mapped_mvc_call( $action['mvc'] ) ],
                    $action['priority'],
                    $action['args']
                );
            }
            // Filters
            foreach ( $this->filters as $filter ) {
                add_filter(
                    $filter['hook'],
                    [ &$this, $this->get_mapped_mvc_call( $filter['mvc'], true ) ],
                    $filter['priority'],
                    $filter['args']
                );
            }
            // Filters
            foreach ( $this->shortcodes as $shortcode ) {
                add_shortcode(
                    $shortcode['tag'],
                    [ &$this, $this->get_mapped_mvc_call( $shortcode['mvc'], true ) ]
                );
            }
            // Widgets
            if ( count( $this->widgets ) > 0 ) {
                add_action( 'widgets_init', [ &$this, '_widgets' ], 1 );
            }
            // Models
            if ( count( $this->models ) > 0 ) {
                add_action( 'init', [ &$this, '_models' ], 1 );
            }
        }
    }

    /**
     * Registers added widgets into Wordpress.
     * @since 1.0.3
     */
    public function _widgets()
    {
        foreach ( $this->widgets as $widget ) {
            register_widget( $widget );
        }
    }

    /**
     * Registers added models into Wordpress.
     * @since 2.0.4
     */
    public function _models()
    {
        foreach ( $this->models as $model ) {
            $post_name = $this->config['namespace'].'\Models\\'.$model;
            $post = new $post_name;
            unset($post_name);
            // Build registration
            if (empty($post->registry_labels)) {
                $name = ucwords(preg_replace('/\-\_/', ' ', $post->type));
                $plural = strpos( $name, ' ' ) !== false ? $name.'s' : $name;
                $post->registry['labels'] = [
                    'name'               => $plural,
                    'singular_name'      => $name,
                    'menu_name'          => $plural,
                    'name_admin_bar'     => $name,
                    'add_new_item'       => sprintf( 'Add New %s', $name ),
                    'new_item'           => sprintf( 'New %s', $name ),
                    'edit_item'          => sprintf( 'Edit %s', $name ),
                    'view_item'          => sprintf( 'View %s', $name ),
                    'all_items'          => sprintf( 'All %s', $plural ),
                    'search_items'       => sprintf( 'Search %s', $plural ),
                    'parent_item_colon'  => sprintf( 'Parent %s', $plural ),
                    'not_found'          => sprintf( 'No %s found.', strtolower( $plural ) ),
                    'not_found_in_trash' => sprintf( 'No %s found in Trash.', strtolower( $plural ) ),
                ];
            } else {
                $post->registry['labels'] = $post->registry_labels;
            }
            $post->registry['supports'] = $post->registry_supports;
            if (empty($post->registry_rewrite)) {
                $slug = strtolower(preg_replace('/\_/', '-', $post->type));
                $post->registry['rewrite'] = [
                    'slug' => strtolower(preg_replace('/\_/', '-', $post->type)),
                ];
            } else {
                $post->registry['rewrite'] = $post->registry_rewrite;
            }
            // Register
            register_post_type( $post->type, $post->registry );
        }
    }

    /**
     * Returns class method call mapped to a mvc engine method.
     * @since 1.0.3
     *
     * @return string
     */
    private function get_mapped_mvc_call( $call, $return = false )
    {
        return ( preg_match( '/[vV]iew\@/', $call ) ? '_v_' : '_c_' )
            . ( $return ? 'return_' : 'void_' )
            . $call;
    }

    /**
     * Returns valid action filter item.
     * @since 1.0.3
     *
     * @param string $hook             Wordpress hook name.
     * @param string $mvc_call      Lightweight MVC call. (i.e. 'Controller@method')
     * @param mixed  $priority      Execution priority or MVC params.
     * @param mixed  $accepted_args Accepted args or priority.
     * @param int    $args          Accepted args.
     *
     * @return array
     */
    private function get_hook( $hook, $mvc_call, $priority = 10, $accepted_args = 1, $args = 1 )
    {
        return [
            'hook'        => $hook,
            'mvc'        => $mvc_call,
            'priority'    => is_array( $priority ) ? $accepted_args : $priority,
            'args'        => is_array( $priority ) ? $args : $accepted_args,
            'mvc_args'    => is_array( $priority ) ? $priority : null,
        ];
    }

    /**
     * Override mvc arguments with those defined when adding an action or filter.
     * @since 1.0.3
     *
     * @param string $mvc_call Lightweight MVC call. (i.e. 'Controller@method')
     * @param array  $args     Current args for call.
     *
     * @return array
     */
    private function override_args( $mvc_call, $args )
    {
        // Check on actions
        for ( $i = count( $this->actions ) - 1; $i >= 0; --$i ) {
            if ( ! empty( $this->actions[$i]['mvc_args'] )
                && $this->actions[$i]['mvc'] === $mvc_call
            ) {
                return $this->actions[$i]['mvc_args'];
            }
        }
        // Check on filters
        for ( $i = count( $this->filters ) - 1; $i >= 0; --$i ) {
            if ( ! empty( $this->filters[$i]['mvc_args'] )
                && $this->filters[$i]['mvc'] === $mvc_call
            ) {
                return $this->filters[$i]['mvc_args'];
            }
        }
        // Check on shortcodes
        for ( $i = count( $this->shortcodes ) - 1; $i >= 0; --$i ) {
            if ( ! empty( $this->shortcodes[$i]['mvc_args'] )
                && $this->shortcodes[$i]['mvc'] === $mvc_call
            ) {
                return $this->shortcodes[$i]['mvc_args'];
            }
        }
        return $args;
    }
}