<?php namespace com\github\mitogh\EasyTabs\lib;

class Shortcode extends Wordpress{

    public function __construct(){
        parent::__construct();

        $this->register();
    }

    private function register(){
        add_shortcode( 'easytabs', $this->call_method('easytabs_shortcode') );
        add_shortcode( 'EasyTabs', $this->call_method('easytabs_shortcode') );
        add_shortcode( 'easyTabs', $this->call_method('easytabs_shortcode') );
        add_shortcode( 'Easytabs', $this->call_method('easytabs_shortcode') );
        add_shortcode( 'tab', $this->call_method('tab_shortcode') );
    }

    public function easytabs_shortcode( $atts, $content = null){
        $html_content = $this->add_titles( $content );
        $html_content .= $this->add_content( $content );
        $attributes = array(
            'id' => 'tab-container',
            'class' => 'tab-container'
        );

        return HTML::div( $html_content, $attributes );
    }

    private function add_content( $content ) {
        return do_shortcode( $content );
    }

    public function tab_shortcode( $atts, $content = null ){
        $options = shortcode_atts( array(
            'title' => 'Default title',
        ), $atts);

        $attributes = array(
            'id' => HTML::generate_ID( $options['title'] )
        );

        return HTML::div( $content, $attributes);
    }

    private function add_titles( $content ){
        $titles = $this->get_titles( $content );
        $titles = $this->format_titles( $titles );
        $attributes = array(
            'class' => 'etabs'
        );
        $list = '';

        foreach( $titles as $title ){
            $list .= 
                '<li class="tab"><a href="#'. HTML::generate_ID($title) .'">' .
                $title .
                '</a></li>';
        }

        return HTML::ul( $list, $attributes );
    }

    private function get_titles( $content ){
        $start = 'title="';
        $end = '"';
        return HTML::get_inside_of($start, $end, $content);
    }

    private function format_titles( $titles = array() ){
        $result = array();
        foreach( $titles as $title ){
            $result[] = $this->get_title_inside_double_quotes( $title );
        }
        return $result;
    }

    private function get_title_inside_double_quotes( $text ){
        $pattern = '/"[^"]*"/';
        $title = "";

        if ( preg_match( $pattern, $text, $result ) ){
            $title = HTML::delete_quotes( $result[0] );
        }

        return $title;
    }
}
