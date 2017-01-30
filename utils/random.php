<?php
/**
 * Utility for getting a list of posts and sorting them randomly, with the ability to keep the same order for a defined period of time and exclude recently displayed items
 * @author markparnell
 */
abstract class BB_Random {
    /**
     * @var string
     * Identifier for this randomiser
     */
    var $name;

    /**
     * @var mixed
     * Arguments for filtering results. Different child classes may expect different types of values.
     */
    var $args;

    /**
     * @var integer
     * How long (in hours) the results should be maintained for before being refreshed.
     */
    var $refresh_rate;

    /**
     * @var integer
     * How long (in hours) returned items should be excluded from being selected again. Ignored if $refresh_rate is empty.
     */
    var $exclusion_period;

    /**
     * @var array
     * Contains the posts which match the selected filters.
     */
    var $posts;

    /**
     * @var array
     * List of post IDs that are currently excluded from the results.
     */
    var $exclusions = array();

    /**
     * @var string
     * Prefix for this item's transients.
     */
    var $transient_prefix;

    /**
     * Constructor
     * @param string $name Identifier for this randomiser
     * @param mixed $args Arguments for filtering results. Different child classes may expect different types of values.
     * @param integer $refresh_rate Optional. How long (in hours) the results should be maintained for before being refreshed (default 24).
     * @param integer $refresh_rate Optional. How long (in hours) returned items should be excluded from being selected again (default 48). Ignored if $refresh_rate is empty.
     * @return null
     */
    public function __construct($name, $args, $refresh_rate = 24, $exclusion_period = 48) {
        $this->name = $name;
        $this->refresh_rate = $refresh_rate;
        $this->exclusion_period = $exclusion_period; // @todo should we enforce exclusion_period >= refresh_rate * 2?
        $this->args = $args;
        $this->transient_prefix = get_called_class().'_'.$name;

        $exclusions = BB_Transients::get($this->transient_prefix.'_exclusion');
        foreach ($exclusions as $exclusion) {
            $this->exclusions[$exclusion->value] = $exclusion->value;
        }

        if (false !== ($posts = get_transient($this->transient_prefix.'_posts'))) {
            $this->posts = $posts;
        } else {
            $this->load();
            $this->filter();
            if ($refresh_rate > 0) {
                set_transient($this->transient_prefix.'_posts', $this->posts, $refresh_rate*HOUR_IN_SECONDS);
            }
        }
    }

    /**
     * Placeholder for loading posts based on defined arguments. Must be overridden in child classes, and needs to populate $this->posts.
     * @return null
     */
    abstract protected function load();

    /**
     * Filters results. Removes excluded items and shuffles the list.
     * @return null
     */
    protected function filter() {
        if (count($this->posts) > count($this->exclusions)) {
            foreach ($this->posts as &$post) {
                if (in_array($post->ID, $this->exclusions)) {
                    unset($post);
                }
            }
        }
        shuffle($this->posts);
    }

    /**
     * Get some posts from the filtered results
     * @param integer $n Optional. Number of posts to return (default 1).
     * @return array containing the requested number of WP_Post objects
     */
    public function get_posts($n = 1) {
        $posts = array_slice($this->posts, 0, $n);
        $this->add_exclusions($posts);
        return $posts;
    }

    /**
     * Get a single post from the filtered results
     * @return WP_Post
     */
    public function get_post() {
        $post = $this->posts[0];
        $this->add_exclusions(array($post));
        return $post;
    }

    /**
     * Adds specified posts to the exclusion list
     * @param array $posts List of WP_Post objects to exclude
     * @return null
     */
    private function add_exclusions($posts) {
        foreach ($posts as $post) {
            $this->exclusions[$post->ID] = $post->ID;
            set_transient($this->transient_prefix.'_exclusion_'.$post->ID, $post->ID, $this->exclusion_period*HOUR_IN_SECONDS);
        }
    }

    /**
     * Clear all stored transients for this randomiser. Useful for debugging.
     * @return array|boolean List of matching transients if any, otherwise false
     */
    public function reset() {
        return BB_Transients::delete($this->transient_prefix);
    }
}

/**
 * Implementation of BB_Random which functions on children of the specified post
 */
class BB_Random_Children extends BB_Random {
    /**
     * Load all posts which are children of the specified post ID.
     * @return null
     */
    protected function load() {
        $this->posts = bb_get_children($this->args);
    }
}

/**
 * Implementation of BB_Random which functions on any get_posts() compatible list of arguments
 */
class BB_Random_Posts extends BB_Random {
    /**
     * Load all posts which match the defined arguments.
     * @return null
     */
    protected function load() {
        $args = $this->args;
        if (empty($args['posts_per_page'])) {
            $args['posts_per_page'] = get_option('posts_per_page');
        }

        if ($args['posts_per_page'] > 0) {
            $args['posts_per_page'] += count($this->exclusions);
        }

        $this->posts = get_posts($args);
    }
}

/**
 * Implementation of BB_Random which functions on any get_users() compatible list of arguments
 */
class BB_Random_Users extends BB_Random {
    /**
     * Load all users which match the defined arguments.
     * @return null
     */
    protected function load() {
        $args = $this->args;
        if ($args['number'] > 0) {
            $args['number'] += count($this->exclusions);
        }

        $this->posts = get_users($args);
    }
}
