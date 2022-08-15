<?php
/**
 * The template for messages page
 *
 * Template Name: Messages
 *
 * @package Rookie
 */

if (wp_get_current_user()->ID) {
    global $wpdb;
	$to = wp_get_current_user()->ID;
	$wpdb->query("UPDATE messages SET seen = '1' WHERE id LIKE '%$to%';");
    get_header();
    
} else {
    wp_redirect('https://tecschoolesports.com');
}

?>

<script src="/htdocs/wp-content/plugins/tecschoolesports/js/alertify.js" type="text/javascript"></script>

	<div id="primary" class="content-area content-area-full-width">
		<main id="main" class="site-main" role="main">
            
            <!-- -->
            <table class="inboxtable">
                <thead>
                    <tr">
                        <td style="width:20%;"><strong>From</strong></td>
                        <td style="width:20%;"><strong>Date</strong></td>
                        <td style="width:53%;"><strong>Message</strong></td>
                        <td style="width:7%;"></td>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $to = wp_get_current_user()->ID;
                    global $wpdb;
                    $msg = $wpdb->get_results("SELECT * FROM messages WHERE id LIKE '%$to%';", ARRAY_A);
                    $count = $wpdb->num_rows;
                    if ($count > 0) {
                        
                        foreach ($msg as $row) {
                            $current = '<tr id="msg-' . $row['identifier'] . '" style="height:60px;">';
                            $current .= "<td>" . get_user_by('ID', $row['idfrom'])->user_login . "</td>";
                            $current .= "<td>" . $row['date'] . "</td>";
                            $current .= "<td>" . $row['msg'] . "</td>";
                            $current .= '<td><a id="trash-' . $row['identifier'] . '">🗑️</a></td>';
                            $current .= '</tr>';
                            echo $current;
                        }
                    } else {
                        echo '<h3 style="text-align:center;">You have no messages!</h3>';
                    }
                    ?>
                </tbody>
            </table>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>
