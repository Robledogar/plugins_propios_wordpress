<?php
/*
Plugin Name: Listado de Categorías con Entradas
Description: Muestra una lista de categorías del blog, y al hacer clic, muestra los títulos de las entradas de esa categoría.
Version: 1.0
Author: robledogar
*/

// Función para obtener todas las categorías y sus enlaces
function mostrar_categorias_con_post_titulos() {
    // Obtener todas las categorías del blog
    $categorias = get_categories();

    // HTML para la lista de categorías
    $output = '<div class="listado-categorias"><ul>';

    foreach ( $categorias as $categoria ) {
        // Crear enlace hacia los posts de cada categoría
        $url_categoria = esc_url( add_query_arg( 'categoria', $categoria->term_id, get_permalink() ) );
        $output .= '<li><a href="' . $url_categoria . '">' . esc_html( $categoria->name ) . '</a></li>';
    }

    $output .= '</ul></div>';

    // Comprobar si se ha hecho clic en alguna categoría
    if ( isset( $_GET['categoria'] ) ) {
        $categoria_id = intval( $_GET['categoria'] );
        
        // Obtener los posts de la categoría seleccionada
        $posts = get_posts( array(
            'category' => $categoria_id,
            'posts_per_page' => -1, // Mostrar todos los posts
        ));

        if ( ! empty( $posts ) ) {
            $output .= '<div class="post-titulos"><h3>Entradas en esta categoría:</h3><ul>';
            foreach ( $posts as $post ) {
                $output .= '<li><a href="' . get_permalink( $post->ID ) . '">' . esc_html( $post->post_title ) . '</a></li>';
            }
            $output .= '</ul></div>';
        } else {
            $output .= '<p>No hay entradas en esta categoría.</p>';
        }
    }

    return $output;
}

// Shortcode para mostrar la lista de categorías con posts
add_shortcode( 'listado_categorias', 'mostrar_categorias_con_post_titulos' );
