<?php
/**
* The front page template file
*
* @package _q
*/

get_header(); ?>

<div class="masthead">
    <a href="/" title="home" class="logo"><img src="<?php bloginfo('template_directory'); ?>/img/qltd-logo.svg" alt="Swift Biosciences" /></a>

    <div class="text"></div>

</div>

<div class="applications-section">
    <h3>See How Swift Technology Benefits Your Application</h3>
    <select>
        <option>Option</option>
    </select>
</div>

<div class="callouts">

    <div class="callout blue">
        <h3>The Swift...</h3>
        <a href="#" class="button">Technology</a>
    </div>

    <div class="callout purple">
        <h3>The Swift...</h3>
        <a href="#" class="button">Technology</a>
    </div>

    <div class="callout blue">
        <h3>The Swift...</h3>
        <a href="#" class="button">Technology</a>
    </div>

</div> <!-- .application-section -->


 <div class="testimonial">
    <h4>What Swift Clients Say</h4>
    <p>We use Swift Products and we love them!</p>
 </div> <!-- .testimonial -->

 <div class="features">

    <div class="featured-product">
        <img src="#" />
        <h3>Featured Product Title</h3>
        <p><strong>Sub title</strong></p>
        <p>description <a href="#">more...</a></p>

        <a href="#" class="button">Similar Products</a>
    </div>

    <div class="featured-news">
        <h3>News Title</h3>
        <p><strong>date</strong></p>
        <p>excerpt <a href="#">more...</a></p>

        <a href="#" class="button">All News</a>
    </div>

 </div> <!-- .features -->


 <div class="event-callout">
    <div class="left">
        date
    </div>
    <div class="middle">
        <h3>Title</h3>
        <h5>Location | Booth</h5>
    </div>
    <div class="right">
        <a href="#" class="button">Meet with Swift</a>
        <a href="#" class="button">See All Eventsmm</a>
    </div>
 </div> <!-- .event-callout -->




<?php get_footer();