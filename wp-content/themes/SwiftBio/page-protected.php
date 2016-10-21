<?php
/**
* Password protected page template
* Template Name: Password Protected
*
* @package _q
*/

get_header(); ?>
    <div class="row share">
            <?php get_template_part('template-parts/social-sharing'); ?>
    </div>

    <div id="body-wrap" class="row">

        <div class="main">
            <?php if (is_user_logged_in()): ?>
                <?php
                    // is there a session/cookie that exists?
                    // if not then send the salesforce user data
                    // then create a new session/cookie
                ?>
                <?php get_template_part('template-parts/content-page'); ?>

                <?php if (get_field('accordion_block')): ?>
                    <?php get_template_part('buckets/accordion'); ?>
                <?php endif; ?>

            <?php else: ?>
                <?php if (get_field('password_protected_alternate_content')): ?>
                    <?php the_field('password_protected_alternate_content'); ?>
                <?php endif; ?>

                    You must be logged in to view this content. Register using the form below or log in if you already have an account.

<div class="user-row">
                <div class="register-form">
                    <h3>Register</h3>
                    <form name="registerform" id="registerform" action="/wp-login.php?action=register" method="post" novalidate="novalidate">
                       <p>
                            <label for="first_name">First Name <span class="req">*</span><br />
                            <input type="text" name="first_name" id="first_name" class="input" value="" size="20" required /></label>
                        </p>
                        <p>
                            <label for="last_name">Last Name <span class="req">*</span><br />
                            <input type="text" name="last_name" id="last_name" class="input" value="" size="20" required /></label>
                        </p>
                        <p>
                            <label for="company">Company <span class="req">*</span><br />
                            <input type="text" name="company" id="company" class="input" value="" size="20" required /></label>
                        </p>
                        <p>
                            <label for="Phone">Phone <span class="req">*</span><br />
                            <input type="text" name="phone" id="phone" class="input" value="" size="20" required /></label>
                        </p>
                        <p>
                            <label for="user_login">Username <span class="req">*</span><br>
                            <input type="text" name="user_login" id="user_login" class="input" value="" size="20" required /></label>
                        </p>
                        <p>
                            <label for="user_email">Email <span class="req">*</span><br>
                            <input type="email" name="user_email" id="user_email" class="input" value="" size="20" required /></label>
                        </p>

                        <p>
                            <label for="user_pass">Password <span class="req">*</span><br>
                                <input type="password" name="pwd" id="user_pass" class="input" value="" size="20" required /></label>
                        </p>
                                <br class="clear">
                                <input type="hidden" name="redirect_to" value="<?php the_permalink(); ?>">
                                <input type="hidden" name="lead_source" value="<?php echo (get_field('lead_source')) ? get_field('lead_source') : get_the_title(); ?>">
                                <input type="hidden" name="pp-reg" value="1" />
                                <p class="submit"><input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="Register"><span class="acf-spinner"></span></p>

                            </form>

                        </div>

<div class="login-form">
    <h3>Login</h3>
    <form name="loginform" id="loginform" action="/wp-login.php" method="post" _lpchecked="1">
        <p>
            <label for="user_login">Username or Email <span class="req">*</span><br>
                <input type="text" name="log" id="user_login" class="input" value="" size="20" required></label>
            </p>
            <p>
                <label for="user_pass">Password <span class="req">*</span><br>
                    <input type="password" name="pwd" id="user_pass" class="input" value="" size="20" required></label>
                </p>
                <br class="clear">
                <p class="submit">
                    <input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="Log In">
                    <input type="hidden" name="redirect_to" value="<?php the_permalink(); ?>">
                    <input type="hidden" name="testcookie" value="1">
                    <input type="hidden" name="lead_source" value="<?php echo (get_field('lead_source')) ? get_field('lead_source') : get_the_title(); ?>">
                    <input type="hidden" name="pp-lg" value="1" />
                </p>
            </form>
        </div>
</div>



            <?php endif; ?>
        </div>

        <div class="sidebar">
            <?php get_sidebar(); ?>
        </div>
        <div style="clear:both;"></div>

    </div>
<?php get_footer(); ?>