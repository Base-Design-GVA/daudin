<?php 
// WP_Query arguments
$args = array(
	'post_type' => 'post',
    'posts_per_page' => 4
);

// The Query
$lastPosts = new WP_Query( $args );

if ( $lastPosts->have_posts() ) : ?>
<p>Actualités</p>
 <div class="news">
    <ul class="news-list">

    <?php while ( $lastPosts->have_posts() ) : $lastPosts->the_post(); 
        $excerpt = get_the_excerpt();
 
        $excerpt = substr($excerpt, 0, 200);
        $result = substr($excerpt, 0, strrpos($excerpt, ' '));
        $color = get_field('couleur', get_the_ID());
    ?>
        <li class="news-item" style="background-color: <?php echo $color; ?>;">
            <a class="news-item__link" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"></a>
            <div class="news-item__in">
                <div class="news-item__top">
                    <h2 class="news-item__title"><?php the_title(); ?></h2>
                    <time class="news-item__time"><?php echo get_the_date('d.m.y'); ?></time>
                </div>
                <div class="news-item__bottom">
                    <div class="news-item__excerpt"><p><?php echo $excerpt; ?></p></div>
                    <div class="news-item__read-more"><span>Lire l'article</span></div>
                </div>
            </div>
        </li>

    <?php endwhile; ?>
    
    </ul>
</div>
<div class="back-all-news"><a href="<?php echo get_home_url() . '/actualites'; ?>" title="Voir toutes les actualités de Daudin">Voir toutes les actualités de Daudin</a></div>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<style>
    .article-title {
            font-size: 6.5rem;
            margin-bottom: 1em;
            display: flex;
            flex-direction: column;
        }

        .article-title h1 {
            line-height: 1;
            letter-spacing: 0.15rem;
            font-family: 'Teodor','Times New Roman',serif;
            margin-bottom: .1em;
        }

        .article-title time {
            font-size: 2.2rem;
            line-height: 1;
            margin-top: .5em;
        }

        .news {
            container-type: inline-size;
            margin-top: 100px;
        }

        .news-list {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            grid-column-gap: 40px;
            grid-row-gap: 40px;
            margin: 0 0 40px 0;
            padding: 0;
            list-style: none;
        }

        .news-item {
            position: relative;
            width: 100%;
            aspect-ratio: 1/1;
            padding: 20px;
        }

        .news-item__link {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 10;
        }

        .news-item__in {
            display: flex;
            height: 100%;
            flex-direction: column;
            justify-content: space-between;
        }

        .news-item__top {

        }

        .news-item__title {
            font-family: 'Teodor','Times New Roman',serif;
            font-weight: 300;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2; /* number of lines to show */
                    line-clamp: 2; 
            -webkit-box-orient: vertical;
        }

        .news-item__time {

        }

        .news-item__bottom {

        }

        .news-item__excerpt {
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 3; /* number of lines to show */
                    line-clamp: 3; 
            -webkit-box-orient: vertical;
        }

        .news-item__excerpt .read-more {
            display: none;
        }

        .news-item__read-more,
        .back-all-news {
            display: flex;
            justify-content: center;
        }

        .news-item__read-more span,
        .back-all-news a {
            border: 1px solid black;
            border-radius: 200px;
            padding: 1rem 2rem;
            font-size: 2.2rem;
            line-height: 2.9rem;
            margin-top: 1em;
            text-align: center;
            transition: all .25s ease-in-out;
        }

        .news-item__link:hover + .news-item__in span,
        .back-all-news a:hover {
            background-color: white;
        }

        @media screen and (max-width: 2200px){
            .news-list {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media screen and (max-width: 1750px){
            .news-list {
                grid-template-columns: repeat(2, 1fr);
            }

            .news-item {
                padding: 30px;
            }
        }

        @media screen and (max-width: 1330px){
            .news-list {
                grid-template-columns: repeat(1, 1fr);
            }
        }

        @media screen and (max-width: 1279px){
            .news-list {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media screen and (max-width: 768px){
            .news-list {
                grid-template-columns: repeat(1, 1fr);
            }
        }
</style>
 

<?php wp_reset_postdata(); endif;  ?>
